# MiscHelper - Miscellaneous Helper Functions

This file contains various utility functions that can be used throughout the Laravel application.

## Amount to Words Conversion

### Basic Usage
```php
use App\Helpers\MiscHelper;

// Convert amount to words (Indian Rupees)
$amount = 107280.50;
$words = MiscHelper::amountToWords($amount);
// Output: "One Lakh Seven Thousand Two Hundred Eighty Rupees Fifty Paise Only"

// For different currencies
$dollars = MiscHelper::amountToWords(1500.75, 'Dollars', 'Only');
// Output: "One Thousand Five Hundred Dollars Seventy Five Cents Only"

$euros = MiscHelper::amountToWords(2500.00, 'Euros');
// Output: "Two Thousand Five Hundred Euros Only"
```

### Function Parameters
- `$amount` (float): The amount to convert
- `$currency` (string): Currency name (default: 'Rupees')
- `$suffix` (string): Suffix to add (default: 'Only')

## Other Available Functions

### Currency Formatting
```php
$formatted = MiscHelper::formatCurrency(107280.50);
// Output: "₹107,280.50"

$formatted = MiscHelper::formatCurrency(1500.75, '$');
// Output: "$1,500.75"
```

### Random String Generation
```php
$random = MiscHelper::generateRandomString(10);
// Output: "aB3k9mN2pQ"

$alphabetic = MiscHelper::generateRandomString(8, 'alphabetic');
// Output: "aBcDeFgH"

$numeric = MiscHelper::generateRandomString(6, 'numeric');
// Output: "123456"
```

### File Size Formatting
```php
$size = MiscHelper::formatFileSize(1024);
// Output: "1 KB"

$size = MiscHelper::formatFileSize(1048576);
// Output: "1 MB"
```

### Get Initials from Name
```php
$initials = MiscHelper::getInitials('John Doe Smith');
// Output: "JS"

$initials = MiscHelper::getInitials('John Doe Smith', 3);
// Output: "JDS"
```

### Mask Sensitive Data
```php
$masked = MiscHelper::maskData('1234567890123456');
// Output: "************3456"

$masked = MiscHelper::maskData('1234567890123456', '*', 4, 12);
// Output: "1234********3456"
```

### Validation Functions
```php
// Indian Phone Number
$isValid = MiscHelper::isValidIndianPhone('9876543210');
// Output: true

// PAN Number
$isValid = MiscHelper::isValidPAN('ABCDE1234F');
// Output: true

// Aadhaar Number
$isValid = MiscHelper::isValidAadhaar('123456789012');
// Output: true
```

### Age Calculation
```php
$age = MiscHelper::getAge('1990-05-15');
// Output: 33 (current age)
```

### Percentage Calculation
```php
$percentage = MiscHelper::calculatePercentage(75, 100);
// Output: 75.0

$percentage = MiscHelper::calculatePercentage(25, 50, 1);
// Output: 50.0
```

## Usage in Blade Templates

```php
{{ \App\Helpers\MiscHelper::amountToWords($purchaseOrder->grand_total) }}
{{ \App\Helpers\MiscHelper::formatCurrency($item->price) }}
{{ \App\Helpers\MiscHelper::getInitials($user->name) }}
```

## Usage in Controllers

```php
use App\Helpers\MiscHelper;

class PurchaseOrderController extends Controller
{
    public function show(PurchaseOrder $purchaseOrder)
    {
        $amountInWords = MiscHelper::amountToWords($purchaseOrder->grand_total);
        
        return view('purchase-orders.show', compact('purchaseOrder', 'amountInWords'));
    }
}
```

## Features

- **Indian Numbering System**: Supports Lakh, Crore, Arab, etc.
- **Multiple Currencies**: Can be used for any currency
- **Decimal Support**: Handles paise/cents correctly
- **Negative Amounts**: Supports negative amounts
- **Zero Amount**: Handles zero amounts gracefully
- **Extensible**: Easy to add new helper functions 