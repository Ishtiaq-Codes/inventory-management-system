# Customer & Old Ledger Bulk Import Feature

This plan covers adding a bulk Excel/CSV import feature for Customers, allowing you to instantly upload hundreds of old customers and their existing notebook debts.

## User Review Required

> [!IMPORTANT]
> The import file will use the following columns in exact order. Please review if this matches the data you have:
> - **A:** Name
> - **B:** Phone
> - **C:** Address
> - **D:** Type (e.g., Retail, Wholesale)
> - **E:** Shop Name
> - **F:** Total Old Bill (The total debt amount before any payments)
> - **G:** Paid Amount (How much they have paid from the old bill, if any)
> 
> *The system will automatically calculate the **Due Amount (Debt)** as `Total Old Bill - Paid Amount` and add it to their ledger as "Old Notebook Record".*

## Proposed Changes

### Controllers

#### [NEW] `app/Http/Controllers/CustomerImportController.php`
- Add `create()` to load the import view.
- Add `store()` to process the Excel/CSV file using `PhpOffice\PhpSpreadsheet`.
- Logic: Loop through the rows. First, create the `Customer`. Second, if column F (Total Old Bill) is greater than 0, create an `Order` attached to this customer with the old notebook debt balance.

### Views

#### [NEW] `resources/views/customers/import.blade.php`
- A file upload page with a stylized form.
- A "Download Template" button to provide you with a blank Excel file containing the exact columns needed.

#### [MODIFY] `resources/views/customers/index.blade.php`
- Add an "Import Customers" button next to the "Add Customer" button.

### Routing

#### [MODIFY] `routes/web.php`
- Add GET `/customers/import` routing to `CustomerImportController@create`.
- Add POST `/customers/import` routing to `CustomerImportController@store`.

## Verification Plan

### Manual Verification
1. I will download the Excel template and fill it with 3 sample customers (one with debt, one without).
2. I will upload it through the new UI.
3. I will verify that the 3 customers appear in the Customer List.
4. I will check the customer ledgers to ensure the old debts were calculated and added correctly.
