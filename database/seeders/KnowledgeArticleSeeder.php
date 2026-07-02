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
                'title' => 'BeePay API Overview',
                'content' => 'BeePay is a payment gateway that provides REST APIs for authentication, payment transactions, cashout transactions, transaction status checking, webhook callbacks, and OTP verification. All protected endpoints require authentication headers to ensure secure access.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Authentication Guide',
                'content' => 'Authenticate by sending a POST request to /login with your email and password. Use the returned authentication credentials for all protected API requests. Required headers include X-API-KEY, X-TIMESTAMP, X-SIGNATURE, and X-HASH.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Creating a Payment Transaction',
                'content' => 'Create a payment by sending a POST request to /transaction/create. Required fields include amount and return_url. Optional fields include destination_url, custom_field_1, custom_field_2, and payment_method. If payment_method is Maya, first_name, last_name, email, and mobile_number are required.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Payment Methods',
                'content' => 'BeePay supports GCash, Maya, and QRPH. GCash and QRPH only require the basic transaction details. Maya additionally requires the customer\'s first name, last name, email address, and mobile number.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Transaction Status API',
                'content' => 'Retrieve transaction status using GET /transaction/status. Provide an array of transaction numbers in trx_nos. You can check up to 100 transactions in a single request.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Callback Notifications',
                'content' => 'When a payment is completed, BeePay sends a POST request to your configured callback URL containing trx_no, status, and signature. Always verify the signature before updating your records.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Cashout Workflow',
                'content' => 'Create a cashout transaction by submitting the account information, receiving bank, amount, return URL, and remark. BeePay sends an OTP to the registered email. The cashout is completed after successful OTP verification.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'OTP Verification',
                'content' => 'Complete a cashout transaction by sending the OTP to POST /cash-out-transaction/verify-otp. A successful response returns the transaction ID and a signature for verification.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Idempotency-Key Explained',
                'content' => 'An Idempotency-Key prevents duplicate cashout transactions. If the same request is retried with the same key, BeePay returns the original response instead of creating a new transaction.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Bulk Cashout Upload',
                'content' => 'BeePay provides a bulk cashout upload endpoint that accepts an Excel (.xlsx) file containing Account No, Account Name, Account Middle Name, Account Last Name, Destination Bank Name, Account Full Name, and Amount. This feature is currently unavailable.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Common API Errors',
                'content' => 'Common API errors include Unauthorized (401), Invalid Credentials, Transaction amount below ₱20, User has no assigned aggregator, Aggregator has no merchants assigned, Daily transaction limit exceeded, and No merchant can process the transaction.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Security Headers',
                'content' => 'All protected API endpoints require X-API-KEY, X-TIMESTAMP, X-SIGNATURE, and X-HASH. These headers are used to authenticate requests and verify their integrity.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Webhook Resend',
                'content' => 'If your system did not receive a payment callback, you can resend it using GET /webhook/resend-by-invoice by providing up to 100 invoice numbers.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'Transaction Response Fields',
                'content' => 'A successful payment transaction returns redirect_url, trx_id, signature, and optionally destination_url, custom_field_1, and custom_field_2 if they were included in the request.',
                'project' => 'BeePay',
            ],
            [
                'title' => 'API Limits and Validation Rules',
                'content' => 'BeePay validates all requests. Batch endpoints accept a maximum of 100 items. Required fields depend on the endpoint and payment method. Invalid payloads return validation errors.',
                'project' => 'BeePay',
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
