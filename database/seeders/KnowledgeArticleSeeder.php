<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeArticle;

class KnowledgeArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [

            [
                'title' => 'Cash In Guide',
                'project' => 'Beepay',
                'content' => 'Customers can cash in through partner banks, payment centers, QR merchants, and accredited cash-in outlets.

Always verify the account number before confirming the transaction.

Cash-in limits depend on the customer verification level.'
            ],

            [
                'title' => 'Cash Out Guide',
                'project' => 'Beepay',
                'content' => 'Customers may cash out using partner outlets or transfer funds to supported banks.

A service fee may apply depending on the transaction amount.'
            ],

            [
                'title' => 'Transaction Status',
                'project' => 'Beepay',
                'content' => 'Transaction statuses:

SUCCESS
The transaction completed successfully.

PENDING
The transaction is still being processed.

FAILED
The transaction was unsuccessful.

REVERSED
Funds have been returned to the sender.'
            ],

            [
                'title' => 'KYC Verification',
                'project' => 'Beepay',
                'content' => 'KYC verification requires:

• Government-issued ID

• Selfie verification

• Complete personal information

Verification usually completes within one business day.'
            ],

            [
                'title' => 'Account Security',
                'project' => 'Beepay',
                'content' => 'Customers should:

• Never share their OTP.

• Never disclose their password.

• Enable device security.

• Report suspicious transactions immediately.'
            ],

            [
                'title' => 'Merchant Payments',
                'project' => 'Beepay',
                'content' => 'Customers can pay merchants using QR Ph or merchant QR codes.

Always verify the merchant name before confirming payment.'
            ],

            [
                'title' => 'Daily Limits',
                'project' => 'Beepay',
                'content' => 'Transaction limits vary depending on account verification level.

Fully verified accounts have higher daily transaction limits.'
            ],

            [
                'title' => 'Fraud Prevention',
                'project' => 'Beepay',
                'content' => 'Never send money to unknown individuals.

Always verify the recipient before transferring funds.

Immediately report unauthorized transactions.'
            ],

        ];

        foreach ($articles as $article) {

            KnowledgeArticle::updateOrCreate(
                [
                    'title' => $article['title']
                ],
                [
                    'project' => $article['project'] ?? null,
                    'content' => $article['content']
                ]
            );

        }
    }
}
