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

                // 1. Create or Find Supplier
                // Use phone or name to find them.
                $supplier = Supplier::firstOrCreate([
                    'user_id' => auth()->id(),
                    'phone' => $phone ? (string) $phone : '0000000000',
                    'name' => (string) $name,
                ], [
                    'uuid' => Str::uuid(),
                    'email' => $email ? (string) $email : null,
                    'address' => (string) $address,
                    'type' => $type ? (string) $type : 'Whole Seller',
                    'shopname' => $shopname ? (string) $shopname : (string) $name,
                ]);

                // 2. Add Old Balance via Purchase if Total Bill is > 0
                if ($total_bill > 0) {
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
