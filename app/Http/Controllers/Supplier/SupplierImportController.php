<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Purchase;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Str;

class SupplierImportController extends Controller
{
    public function create()
    {
        return view('suppliers.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv',
        ]);

        $the_file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $row_range    = range(2, $row_limit);

            foreach ($row_range as $row) {
                $name = $sheet->getCell('A' . $row)->getValue();
                
                // If name is empty, skip this row
                if (empty(trim((string)$name))) {
                    continue;
                }

                $phone = $sheet->getCell('B' . $row)->getValue();
                $email = $sheet->getCell('C' . $row)->getValue();
                $address = $sheet->getCell('D' . $row)->getValue();
                $type = $sheet->getCell('E' . $row)->getValue();
                $shopname = $sheet->getCell('F' . $row)->getValue();
                $total_bill = (float) $sheet->getCell('G' . $row)->getValue();
                $paid_amount = (float) $sheet->getCell('H' . $row)->getValue();

                $type_raw = strtolower(trim((string)$type));
                $valid_types = ['distributor', 'wholesaler', 'producer'];
                $supplier_type = in_array($type_raw, $valid_types) ? $type_raw : 'wholesaler';

                // 1. Create or Find Supplier
                // Use name to find them. Leave phone null if empty to avoid unique constraint crashes.
                $supplier = Supplier::firstOrCreate([
                    'user_id' => auth()->id(),
                    'name' => (string) $name,
                ], [
                    'uuid' => Str::uuid(),
                    'phone' => $phone ? (string) $phone : null,
                    'email' => $email ? (string) $email : null,
                    'address' => (string) $address,
                    'type' => $supplier_type,
                    'shopname' => $shopname ? (string) $shopname : (string) $name,
                ]);

                // 2. Add Old Balance via Purchase if Total Bill is > 0
                if ($total_bill > 0) {
                    
                    // Prevent doubling debt if the same file is uploaded twice
                    $existingPurchase = Purchase::where('supplier_id', $supplier->id)
                                                ->where('notes', 'Old Register Ledger Import')
                                                ->first();

                    if (!$existingPurchase) {
                        Purchase::create([
                            'supplier_id' => $supplier->id,
                            'date' => now()->format('Y-m-d'),
                            'purchase_no' => 'OLD-BAL-' . strtoupper(Str::random(6)),
                            'status' => 1, // 1 = Approved
                            'total_amount' => $total_bill,
                            'paid_amount' => $paid_amount,
                            'created_by' => auth()->id(),
                            'user_id' => auth()->id(),
                            'uuid' => Str::uuid(),
                            'notes' => 'Old Register Ledger Import',
                        ]);
                    }
                }
            }

        } catch (Exception $e) {
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Error reading Excel file. Please ensure it matches the template.');
        }

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Suppliers and Old Ledgers have been imported successfully!');
    }
}
