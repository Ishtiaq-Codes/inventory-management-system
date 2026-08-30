<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('products.import');
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

                $category_name = $sheet->getCell('B' . $row)->getValue();
                $unit_name = $sheet->getCell('C' . $row)->getValue();
                
                $buying_price = (float) $sheet->getCell('D' . $row)->getValue();
                $selling_price = (float) $sheet->getCell('E' . $row)->getValue();
                $quantity = (int) $sheet->getCell('F' . $row)->getValue();
                $quantity_alert = (int) $sheet->getCell('G' . $row)->getValue();
                $tax = (float) $sheet->getCell('H' . $row)->getValue();

                // 1. Find or Create Category
                $category = Category::firstOrCreate(
                    ['name' => $category_name ? (string) $category_name : 'Uncategorized'],
                    [
                        'slug' => Str::slug($category_name ? (string) $category_name : 'Uncategorized'),
                        'user_id' => auth()->id(),
                    ]
                );

                // 2. Find or Create Unit
                $unit = Unit::firstOrCreate(
                    ['name' => $unit_name ? (string) $unit_name : 'Pcs'],
                    [
                        'slug' => Str::slug($unit_name ? (string) $unit_name : 'Pcs'), 
                        'short_code' => $unit_name ? substr((string) $unit_name, 0, 3) : 'Pcs',
                        'user_id' => auth()->id(),
                    ]
                );

                // 3. Generate Product Code
                $product_code = IdGenerator::generate([
                    'table' => 'products',
                    'field' => 'code',
                    'length' => 4,
                    'prefix' => 'PC'
                ]);

                // 4. Create or Update Product
                $product = Product::where('name', (string) $name)->first();
                
                if ($product) {
                    // Update existing product
                    $product->update([
                        'category_id' => $category->id,
                        'unit_id' => $unit->id,
                        'quantity' => $quantity, // Overwrite with new quantity from Excel
                        'quantity_alert' => $quantity_alert,
                        'buying_price' => $buying_price,
                        'selling_price' => $selling_price,
                        'tax' => $tax,
                        'notes' => 'Updated via Excel Import',
                    ]);
                } else {
                    // Create new product
                    Product::create([
                        'name' => (string) $name,
                        'category_id' => $category->id,
                        'unit_id' => $unit->id,
                        'slug' => Str::slug((string) $name),
                        'code' => $product_code,
                        'quantity' => $quantity,
                        'quantity_alert' => $quantity_alert,
                        'buying_price' => $buying_price,
                        'selling_price' => $selling_price,
                        'tax' => $tax,
                        'tax_type' => 1, // Default to Exclusive
                        'notes' => 'Imported via Excel',
                        'user_id' => auth()->id(),
                        'uuid' => Str::uuid(),
                    ]);
                }
            }

        } catch (Exception $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Error reading Excel file. Please ensure it matches the new template.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Products have been imported successfully!');
    }
}
