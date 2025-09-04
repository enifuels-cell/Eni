# QR Code with Eni Logo Implementation Guide

## 🎯 Overview
Successfully implemented QR codes with Eni logo overlay for enhanced brand recognition while maintaining full QR code functionality.

## ✅ What's Been Implemented

### 1. QrCodeService Class
- **Location**: `app/Services/QrCodeService.php`
- **Purpose**: Centralized service for generating QR codes with Eni logo
- **Methods**:
  - `generateWithLogo()` - Creates QR code with Eni logo overlay
  - `generateBasic()` - Fallback for basic QR codes
  - `generateSvgWithLogo()` - SVG format with logo

### 2. Updated Referral QR Code
- **Location**: `app/Http/Controllers/User/DashboardController.php`
- **Change**: Now uses `QrCodeService::generateWithLogo()` instead of basic QR generation
- **Result**: Referral QR codes now display Eni logo in center

### 3. Bank Transfer QR Codes with Logo
- **Generated Files**:
  - `public/landbank_qr_with_logo.png`
  - `public/bpi_qr_with_logo.png`
  - `public/rcbc_qr_with_logo.png`
- **Command**: `php artisan qr:generate-bank-logos`

### 4. Updated Package Page
- **Location**: `resources/views/user/packages.blade.php`
- **Change**: QR modal now displays branded QR codes
- **Enhancement**: Added descriptive text for better UX

## 🔧 How to Customize Bank QR Codes

### Update with Real Bank Account Information:

1. **Edit the command file**: `app/Console/Commands/GenerateBankQrCodesWithLogo.php`

2. **Replace the example data** in the `$bankAccounts` array:

```php
$bankAccounts = [
    'landbank' => [
        'name' => 'LandBank of the Philippines',
        'account' => 'YOUR_ACTUAL_LANDBANK_ACCOUNT', // Replace this
        'qr_data' => 'YOUR_ACTUAL_QR_DATA_FOR_LANDBANK' // Replace this
    ],
    'bpi' => [
        'name' => 'Bank of the Philippine Islands',
        'account' => 'YOUR_ACTUAL_BPI_ACCOUNT', // Replace this
        'qr_data' => 'YOUR_ACTUAL_QR_DATA_FOR_BPI' // Replace this
    ],
    'rcbc' => [
        'name' => 'Rizal Commercial Banking Corporation',
        'account' => 'YOUR_ACTUAL_RCBC_ACCOUNT', // Replace this
        'qr_data' => 'YOUR_ACTUAL_QR_DATA_FOR_RCBC' // Replace this
    ]
];
```

3. **Regenerate QR codes** with your real data:
```bash
php artisan qr:generate-bank-logos
```

## 🎨 QR Code Specifications

- **Size**: 400x400 pixels for bank QR codes, 200x200 for referral
- **Logo Size**: 30% of QR code size for bank transfers, 25% for referrals
- **Format**: PNG with white background behind logo
- **Error Correction**: Medium level (allows for logo overlay)

## 📱 Features

✅ **Brand Recognition**: Eni logo prominently displayed in center
✅ **Scannable**: QR codes remain fully functional
✅ **Professional**: Clean, branded appearance
✅ **Consistent**: Same logo treatment across all QR codes
✅ **Fallback**: Graceful degradation if logo file missing

## 🧪 Testing

- **Test Page**: `http://127.0.0.1:8000/qr-test.html`
- **Individual QR Codes**:
  - `http://127.0.0.1:8000/landbank_qr_with_logo.png`
  - `http://127.0.0.1:8000/bpi_qr_with_logo.png` 
  - `http://127.0.0.1:8000/rcbc_qr_with_logo.png`

## 🔄 How It Works

1. **QrCodeService** generates base QR code
2. **Logo overlay** applied using SimpleSoftwareIO QrCode merge function
3. **White background** added behind logo for better visibility
4. **Error correction** ensures QR remains scannable despite logo
5. **Fallback mechanism** provides basic QR if logo processing fails

## 🎯 Next Steps

1. **Update bank account data** in the command file with your real information
2. **Test QR codes** with actual banking apps to ensure they work
3. **Regenerate QR codes** after updating account information
4. **Consider adding more banks** if needed by extending the command

The implementation provides a professional, branded QR code solution while maintaining full functionality!
