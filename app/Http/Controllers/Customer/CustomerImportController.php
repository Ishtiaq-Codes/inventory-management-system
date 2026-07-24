<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Str;

class CustomerImportController extends Controller
{
    public function create()
    {
        return view('customers.import');
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
                $address = $sheet->getCell('C' . $row)->getValue();
                $type = $sheet->getCell('D' . $row)->getValue();
                $shopname = $sheet->getCell('E' . $row)->getValue();
                $total_bill = (float) $sheet->getCell('F' . $row)->getValue();
                $paid_amount = (float) $sheet->getCell('G' . $row)->getValue();
                $page_number = $sheet->getCell('H' . $row)->getValue();

                // 1. Create or Find Customer
                // Use name to find them. If phone is empty, leave it null to avoid unique constraint crashes.
                $customer = Customer::firstOrCreate([
                    'user_id' => auth()->id(),
                    'name' => (string) $name,
                ], [
                    'uuid' => Str::uuid(),
                    'phone' => $phone ? (string) $phone : null,
                    'address' => (string) $address,
                    'type' => $type ? (string) $type : 'Retail',
                    'shopname' => (string) $shopname,
                    'page_number' => $page_number ? (string) $page_number : null,
                ]);

                // 2. Add Old Balance if Total Bill is > 0
                if ($total_bill > 0) {
                    $due = max(0, $total_bill - $paid_amount);

                    // Check if we already imported an old balance for this customer
                    // to prevent doubling their debt if the user uploads the same file twice!
                    $existingOrder = Order::where('customer_id', $customer->id)
                                          ->where('notes', 'Old Register Ledger Import')
                                          ->first();

                    if (!$existingOrder) {
                        Order::create([
                            'customer_id' => $customer->id,
                            'payment_type' => 'Cash',
                            'pay' => $paid_amount,
                            'order_date' => now()->format('Y-m-d'),
                            'order_status' => \App\Enums\OrderStatus::COMPLETE,
                            'total_products' => 0,
                            'sub_total' => $total_bill,
                            'vat' => 0,
                            'total' => $total_bill,
                            'invoice_no' => 'OLD-BAL-' . strtoupper(Str::random(6)),
                            'due' => $due,
                            'user_id' => auth()->id(),
                            'uuid' => Str::uuid(),
                            'notes' => 'Old Register Ledger Import',
                        ]);
                    }
                }
            }

        } catch (Exception $e) {
            return redirect()
                ->route('customers.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customers and Old Ledgers have been imported successfully!');
    }
}
