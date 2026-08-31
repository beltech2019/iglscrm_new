<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SalesforceAddressMaster;

class SalesforceService
{
    /**
     * Get Salesforce access token
     */
    public function authenticate()
    {
        $url = config('services.salesforce.base_url') . '/services/oauth2/token';

        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.salesforce.client_id'),
            'client_secret' => config('services.salesforce.client_secret'),
        ]);

        Log::info('Salesforce Authentication Response', [
            'status' => $response->status(),
            'success' => $response->successful(),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        $this->handleSalesforceError(
            $response,
            'Salesforce authentication'
        );
    }

    /**
     * Create Domestic Case
     */
    public function createDomesticCase(array $caseData)
    {
        $auth = $this->authenticate();

        $url = $auth['instance_url']
            . '/services/data/v60.0/sobjects/Case';

        $caseData['RecordTypeId'] = '012Bi000000inbJIAQ';

        $response = Http::withToken($auth['access_token'])
            ->post($url, $caseData);

        Log::info('Salesforce Domestic Case Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->successful()) {

            $createResponse = $response->json();

            $caseId = $createResponse['id'] ?? null;

            if (!$caseId) {
                throw new \Exception(
                    'Salesforce created the Domestic Case but Case ID was not returned.'
                );
            }

            // Get Case Number after creation
            $caseDetailsUrl = $auth['instance_url']
                . '/services/data/v60.0/sobjects/Case/'
                . $caseId
                . '?fields=Id,CaseNumber,Status,RecordTypeId';

            $caseDetailsResponse = Http::withToken($auth['access_token'])
                ->get($caseDetailsUrl);

            Log::info('Salesforce Domestic Case Details Response', [
                'status' => $caseDetailsResponse->status(),
                'body' => $caseDetailsResponse->json(),
            ]);

            if ($caseDetailsResponse->successful()) {

                $caseDetails = $caseDetailsResponse->json();

                return [
                    'id' => $caseDetails['Id'] ?? $caseId,
                    'caseNumber' => $caseDetails['CaseNumber'] ?? null,
                    'status' => $caseDetails['Status'] ?? 'New',
                ];
            }

            // Case was created, but details could not be fetched
            throw new \Exception(
                'Salesforce Domestic Case created, but Case Number could not be retrieved: '
                . $caseDetailsResponse->body()
            );
        }

        $this->handleSalesforceError(
            $response,
            'Salesforce Domestic Case creation'
        );
    }

    /**
     * Create I&C Case
     */
    public function createICCase(array $caseData)
    {
        $auth = $this->authenticate();

        $url = $auth['instance_url']
            . '/services/data/v60.0/sobjects/Case';

        $caseData['RecordTypeId'] = '012Bi000000inbKIAQ';

        $response = Http::withToken($auth['access_token'])
            ->post($url, $caseData);

        Log::info('Salesforce I&C Case Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->successful()) {

            $createResponse = $response->json();

            $caseId = $createResponse['id'] ?? null;

            if (!$caseId) {
                throw new \Exception(
                    'Salesforce created the I&C Case but Case ID was not returned.'
                );
            }

            // Get Case Number after creation
            $caseDetailsUrl = $auth['instance_url']
                . '/services/data/v60.0/sobjects/Case/'
                . $caseId
                . '?fields=Id,CaseNumber,Status,RecordTypeId';

            $caseDetailsResponse = Http::withToken($auth['access_token'])
                ->get($caseDetailsUrl);

            Log::info('Salesforce I&C Case Details Response', [
                'status' => $caseDetailsResponse->status(),
                'body' => $caseDetailsResponse->json(),
            ]);

            if ($caseDetailsResponse->successful()) {

                $caseDetails = $caseDetailsResponse->json();

                return [
                    'id' => $caseDetails['Id'] ?? $caseId,
                    'caseNumber' => $caseDetails['CaseNumber'] ?? null,
                    'status' => $caseDetails['Status'] ?? 'New',
                ];
            }

            // Case was created, but details could not be fetched
            throw new \Exception(
                'Salesforce I&C Case created, but Case Number could not be retrieved: '
                . $caseDetailsResponse->body()
            );
        }

        $this->handleSalesforceError(
            $response,
            'Salesforce I&C Case creation'
        );
    }



       /**
 * Get Salesforce Case Status using Salesforce Case ID
 */
    public function getCaseStatus($salesforceCaseId)
    {
        $auth = $this->authenticate();

        $salesforceCaseId = (string) $salesforceCaseId;

        $query = "SELECT Id,
                        CaseNumber,
                        Status,
                        IGL_Ticket_Type__c,
                        IGL_Ticket_Category__c,
                        IGL_Legacy_Case_Id__c
                FROM Case
                WHERE Id = '" . addslashes($salesforceCaseId) . "'";

        $url = $auth['instance_url']
            . '/services/data/v60.0/query/';

        $response = Http::withToken($auth['access_token'])
            ->get($url, [
                'q' => $query
            ]);

        if (!$response->successful()) {
            $this->handleSalesforceError(
                $response,
                'Salesforce Case Status API'
            );
        }

        $data = $response->json();

        if (empty($data['records'])) {
            throw new \Exception(
                'No Salesforce Case found for Salesforce Case ID: '
                . $salesforceCaseId
            );
        }

        return $data['records'][0];
    }



    /**
     * Get Salesforce Address Master
     */
    public function getAddressMaster()
    {
        $auth = $this->authenticate();

        $query = "SELECT Id,Name,Area__c,Zone__c,Control_Room_Name__c,City__c,State__c,Pincode__c
                FROM Address_Master__c";

        $url = $auth['instance_url']
            . '/services/data/v60.0/query/';

        $response = Http::withToken($auth['access_token'])
            ->get($url, [
                'q' => $query
            ]);

        Log::info('Salesforce Address Master Response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        $this->handleSalesforceError(
            $response,
            'Salesforce Address Master API'
        );
    }


    /**
     * Sync Salesforce Address Master to local database
     */
    public function syncAddressMaster()
    {
        $auth = $this->authenticate();

        $baseUrl = $auth['instance_url']
            . '/services/data/v60.0';

        $query = "SELECT Id,Name,Area__c,Zone__c,Control_Room_Name__c,City__c,State__c,Pincode__c
                FROM Address_master__c";

        $url = $baseUrl . '/query/';

        $totalSynced = 0;

        do {

            if ($query !== null) {

                $response = Http::withToken($auth['access_token'])
                    ->get($url, [
                        'q' => $query
                    ]);

            } else {

                $response = Http::withToken($auth['access_token'])
                    ->get($url);
            }

            if (!$response->successful()) {
                $this->handleSalesforceError(
                    $response,
                    'Salesforce Address Master Sync'
                );
            }

            $data = $response->json();

            foreach ($data['records'] as $record) {

                SalesforceAddressMaster::updateOrCreate(
                    [
                        'salesforce_id' => $record['Id']
                    ],
                    [
                        'name' => $record['Name'] ?? null,
                        'area' => $record['Area__c'] ?? null,
                        'zone' => $record['Zone__c'] ?? null,
                        'control_room_name' => $record['Control_Room_Name__c'] ?? null,
                        'city' => $record['City__c'] ?? null,
                        'state' => $record['State__c'] ?? null,
                        'pincode' => $record['Pincode__c'] ?? null,
                    ]
                );

                $totalSynced++;
            }

            if (!empty($data['nextRecordsUrl'])) {

                $url = $auth['instance_url'] . $data['nextRecordsUrl'];
                $query = null;

            } else {

                $url = null;
            }

        } while ($url !== null);

        Log::info('Salesforce Address Master Sync Completed', [
            'total_synced' => $totalSynced
        ]);

        return $totalSynced;
    }


    /**
     * Handle Salesforce API errors
     */
    private function handleSalesforceError($response, $operation = 'Salesforce API')
    {
        $status = $response->status();
        $body = $response->json();

        Log::error('Salesforce API Error', [
            'operation' => $operation,
            'status' => $status,
            'body' => $body,
        ]);

        if ($status === 400 && isset($body['error'])) {

            throw new \Exception(
                $operation . ' authentication failed: ' .
                ($body['error_description'] ?? $body['error'])
            );
        }

        if (is_array($body) && isset($body[0])) {

            $error = $body[0];

            $errorCode = $error['errorCode'] ?? 'UNKNOWN_ERROR';
            $message = $error['message'] ?? 'Unknown Salesforce error';

            throw new \Exception(
                $operation . ' failed [' . $errorCode . ']: ' . $message
            );
        }

        /*
        * HTTP status based errors
        */
        $statusMessages = [
            400 => 'Bad request',
            401 => 'Unauthorized / session expired',
            403 => 'Forbidden / insufficient access',
            404 => 'Record or endpoint not found',
            405 => 'HTTP method not allowed',
            409 => 'Duplicate record',
            413 => 'Request body too large',
            415 => 'Unsupported media type',
            500 => 'Salesforce internal server error',
            503 => 'Salesforce service unavailable',
        ];

        $message = $statusMessages[$status]
            ?? 'Unknown Salesforce HTTP error';

        throw new \Exception(
            $operation . ' failed [' . $status . ']: ' .
            $message
        );
    }



}