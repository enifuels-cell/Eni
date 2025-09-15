<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'What is an investment package?', 'answer' => 'A predefined set of parameters: min/max amount, daily shares rate, effective days, referral bonus rate, and optional limited slots.', 'category' => 'investments', 'intent' => 'invest.package.definition'],
            ['question' => 'How do I start a new investment?', 'answer' => 'Choose an active package, enter an amount within its limits, and submit. Your balance is debited and the investment becomes active (or pending if deposit based).', 'category' => 'investments', 'intent' => 'invest.create.how'],
            ['question' => 'Why was my investment rejected?', 'answer' => 'Likely reasons: amount outside package limits, insufficient balance, package inactive, or no remaining slots.', 'category' => 'investments', 'intent' => 'invest.create.fail'],
            ['question' => 'How is daily interest calculated?', 'answer' => 'Formula: amount * (daily_shares_rate / 100). Credited once per day if investment active with remaining days.', 'category' => 'interest', 'intent' => 'interest.calc'],
            ['question' => 'Can daily interest duplicate?', 'answer' => 'No. A unique constraint and idempotent logic prevent duplicate daily logs for the same date.', 'category' => 'interest', 'intent' => 'interest.idempotent'],
            ['question' => 'What is investment_code?', 'answer' => 'A random public-friendly code (e.g., INV-XXXXXX) generated on creation to avoid exposing sequential IDs.', 'category' => 'identifiers', 'intent' => 'codes.investment'],
            ['question' => 'What is receipt_code?', 'answer' => 'A short random code for a transaction receipt replacing direct numeric ID exposure.', 'category' => 'identifiers', 'intent' => 'codes.receipt'],
            ['question' => 'How do referral bonuses work?', 'answer' => 'If you invest with a valid referral code (not your own), a percentage of the investment is credited to the referrer immediately.', 'category' => 'referrals', 'intent' => 'referral.bonus.how'],
            ['question' => 'Why no referral bonus applied?', 'answer' => 'Referral code invalid, belongs to you, or a bonus already exists for that investment.', 'category' => 'referrals', 'intent' => 'referral.bonus.missing'],
            ['question' => 'How do I upload a payment receipt?', 'answer' => 'During bank transfer flow use the provided file input (JPG, PNG, or PDF up to 2MB).', 'category' => 'receipts', 'intent' => 'receipt.upload'],
            ['question' => 'Why was my receipt file rejected?', 'answer' => 'File type or size invalid or failed internal mime validation.', 'category' => 'receipts', 'intent' => 'receipt.invalid'],
            ['question' => 'Why am I rate limited?', 'answer' => 'High-frequency routes (investments, deposits, withdrawals) have per-minute caps for abuse prevention.', 'category' => 'security', 'intent' => 'security.rate_limit'],
            ['question' => 'What happens when slots reach zero?', 'answer' => 'Further investments are blocked; an atomic decrement logic ensures no overbooking.', 'category' => 'investments', 'intent' => 'invest.slots.full'],
            ['question' => 'Why did I not receive interest today?', 'answer' => 'Possible reasons: investment completed, already processed today, or not active.', 'category' => 'interest', 'intent' => 'interest.missing'],
            ['question' => 'Where are receipts stored?', 'answer' => 'Privately in local storage; not directly publicly accessible.', 'category' => 'receipts', 'intent' => 'receipt.storage'],
            ['question' => 'How do I identify a transaction?', 'answer' => 'Use the receipt_code for support references.', 'category' => 'identifiers', 'intent' => 'codes.transaction.ident'],
            ['question' => 'Can I cancel an active investment early?', 'answer' => 'Not supported in the current implementation.', 'category' => 'investments', 'intent' => 'invest.cancel'],
            ['question' => 'What transaction types exist?', 'answer' => 'deposit, withdrawal, interest, referral_bonus, other.', 'category' => 'transactions', 'intent' => 'transaction.types'],
            ['question' => 'Is my balance updated immediately after investing?', 'answer' => 'Yes, principal is debited right after investment creation.', 'category' => 'investments', 'intent' => 'invest.balance.debit'],
            ['question' => 'How are logs kept?', 'answer' => 'A dedicated investment log channel records creation, interest processing, and duplicate prevention events.', 'category' => 'logging', 'intent' => 'logging.investment']
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
