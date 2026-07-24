# Rebuild Product Bulk Import

The original Product Import feature forces you to type database ID numbers and computer slugs instead of actual category names. This plan will completely rebuild it to work exactly like the Customer/Supplier import.

## Proposed Changes

### Controller Redesign
#### [MODIFY] `app/Http/Controllers/Product/ProductImportController.php`
- Change the controller so it reads plain English column names instead of database IDs.
- If you type a new category like "Batteries" or a new unit like "Box" in the Excel sheet, the system will automatically create them for you on the fly.
- The system will automatically generate the internal Product Code (e.g., PC-0012) and Slug behind the scenes so you don't have to think about them.

### View Redesign
#### [MODIFY] `resources/views/products/import.blade.php`
- Add a beautiful UI matching the Customer/Supplier import pages.
- Add an instruction box detailing the exact columns required.
- Add a "Download Blank Template" button so you can instantly download the Excel template.

### Excel Template
#### [NEW] `public/assets/templates/product_import_template.xlsx`
- Create an official Excel template with these columns:
  - `Name` (e.g., General Tyre 145/80R12)
  - `Category` (e.g., Car Tyres)
  - `Unit` (e.g., Pcs)
  - `Buying Price` (e.g., 5000)
  - `Selling Price` (e.g., 6000)
  - `Quantity` (e.g., 20)
  - `Alert Quantity` (e.g., 5 - low stock warning)
  - `Tax %` (Optional - e.g., 0)

> [!NOTE]  
> Since you cannot easily upload pictures through a standard Excel text cell, all imported products will have a blank/default image. You can always edit a product later to add a photo.

## Verification Plan
- Generate the new Excel template.
- Update the controller and view.
- Verify the PHP syntax of the controller.
