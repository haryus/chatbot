<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            [
                'question' => 'How do I create a BeePay account?',
                'answer' => 'Download the BeePay app, register using your mobile number, verify your email address, and complete the KYC process.',
            ],

            [
                'question' => 'How can I cash in?',
                'answer' => 'You can cash in using online banking, partner outlets, over-the-counter payment centers, or QR-enabled merchants.',
            ],

            [
                'question' => 'How do I cash out?',
                'answer' => 'Cash out by visiting any BeePay partner outlet or transfer your balance to your linked bank account.',
            ],

            [
                'question' => 'How do I transfer money?',
                'answer' => 'Open the Transfer Money menu, enter the recipient details, review the information, and confirm the transaction.',
            ],

            [
                'question' => 'How long does a transfer take?',
                'answer' => 'Most transfers are completed within a few minutes. Some bank transfers may take longer depending on the receiving bank.',
            ],

            [
                'question' => 'What should I do if my transaction is pending?',
                'answer' => 'Please wait for a few minutes. If the status remains pending, contact BeePay Customer Support and provide your transaction reference number.',
            ],

            [
                'question' => 'How do I reset my password?',
                'answer' => 'Tap Forgot Password on the login screen and follow the password reset instructions sent to your registered email or mobile number.',
            ],

            [
                'question' => 'How do I verify my account?',
                'answer' => 'Upload a valid government-issued ID and complete the facial verification process inside the BeePay application.',
            ],

            [
                'question' => 'What IDs are accepted?',
                'answer' => 'Passport, Driver License, UMID, National ID, PRC ID, and other government-issued IDs are accepted.',
            ],

            [
                'question' => 'How do I contact customer support?',
                'answer' => 'You may contact BeePay Customer Support through the in-app chat, email, or official support hotline.',
            ],

        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(
                [
                    'question' => $faq['question']
                ],
                [
                    'answer' => $faq['answer']
                ]
            );
        }
    }
}
