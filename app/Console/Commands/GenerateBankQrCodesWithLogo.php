<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\QrCodeService;

class GenerateBankQrCodesWithLogo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:generate-bank-logos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate bank QR codes with Eni logo overlay';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating bank QR codes with Eni logo...');
        
        // Bank account information (you can replace these with actual account numbers)
        $bankAccounts = [
            'landbank' => [
                'name' => 'LandBank of the Philippines',
                'account' => '1234567890', // Replace with actual account
                'qr_data' => 'bank://landbank/account/1234567890?amount=variable' // Example QR data
            ],
            'bpi' => [
                'name' => 'Bank of the Philippine Islands',
                'account' => '0987654321', // Replace with actual account
                'qr_data' => 'bank://bpi/account/0987654321?amount=variable'
            ],
            'rcbc' => [
                'name' => 'Rizal Commercial Banking Corporation',
                'account' => '1122334455', // Replace with actual account
                'qr_data' => 'bank://rcbc/account/1122334455?amount=variable'
            ]
        ];
        
        foreach ($bankAccounts as $bankCode => $bankInfo) {
            $this->info("Generating QR code for {$bankInfo['name']}...");
            
            try {
                // Generate QR code with logo using our service
                $qrCodeData = QrCodeService::generateWithLogo($bankInfo['qr_data'], 400);
                
                // Save to public directory
                $filename = public_path($bankCode . '_qr_with_logo.png');
                file_put_contents($filename, $qrCodeData);
                
                $this->info("✓ Generated: {$filename}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to generate QR for {$bankCode}: " . $e->getMessage());
            }
        }
        
        $this->info('');
        $this->info('QR code generation completed!');
        $this->info('');
        $this->info('Note: Update the QR data in this command with your actual bank account information.');
        $this->info('The generated files are saved in the public directory as:');
        $this->info('- landbank_qr_with_logo.png');
        $this->info('- bpi_qr_with_logo.png');
        $this->info('- rcbc_qr_with_logo.png');
        
        return Command::SUCCESS;
    }
}
