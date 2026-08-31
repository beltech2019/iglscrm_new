<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialTicket;
use App\Models\SapTicketCodeGroups;
use App\Models\TicketSapGroups;
use Log;
use App\Services\SalesforceService;
use App\Models\SalesforceCase;
use Illuminate\Support\Str;

class TicketResponseController extends Controller
{
    public function createSapTicket(Request $request,$id)
    {
        try {
            log::info($request);
            $data=createTicket($request->bpno,$request->ct,$request->cg,$request->description,$request->notes);
            log::info($data);
            $ticket=SocialTicket::find($id);
            log::info("before".$ticket->bipNumber);
            $groupcode=SapTicketCodeGroups::where('catalog_type',$request->ct)->where('code_group',$request->cg)->first();
            if (empty($data)) {
                return redirect()->back()->with('message','Ticket not created with this code group');
            }
            $ticket->update([        
                'bipNumber' => $request->bpno
            ]);
            log::info("after".$ticket->bipNumber);
            TicketSapGroups::create([
                'sap_ticket_status' => $data['status'],
                'sap_object_id' => $data['object_id'],
                'sap_process_type' => $data['process_type'],
                'sap_code_group_id' => $groupcode->id,
                'ticket_id'=> $ticket->id,
            ]);
            return redirect()->back()->with('success','Ticket Created');
        } catch (\Exception $e) {
            log::info($e->getMessage());
            return redirect()->back()->with('message','Ticket not created with this BP Number');
        }
    }

    public function getSapTicketStatus(Request $request,$id)
    {
        log::info($id);
        try {
         $ticketid =   TicketSapGroups::find($id);
         $ticket = SocialTicket::leftJoin('users', 'users.id', '=', 'tb_socialticket.assigned_to')
            ->select('tb_socialticket.*', 'users.name')
            ->where('tb_socialticket.id', $ticketid->ticket_id)
            ->first();
         $statushtml="";
         if($ticketid->sap_status == "Completed"){
            $statushtml .= "<table style='border-collapse: collapse; width: 100%;'>";
            $statushtml .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Ticket ID:</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $ticketid->sap_object_id . "</td></tr>";
            $statushtml .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>BP Number:</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $ticket->bipNumber . "</td></tr>";
            $statushtml .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Assigned To:</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $ticket->name . "</td></tr>";
            $statushtml .= "<tr><td style='border: 1px solid #000; padding: 8px;'><strong>Status:</strong></td><td style='border: 1px solid #000; padding: 8px;'>" . $ticketid->sap_status . "</td></tr>";
            $statushtml .= "</table>";
         }else{
            $statushtml = fetchTicketStatus($ticket->bipNumber,$ticketid->sap_object_id,$ticket->name,$ticket->id);
         }
         return $statushtml;
        } catch (\Exception $e) {
            log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function createSalesforceCase(Request $request, $id)
    {
        try {

            Log::info('Salesforce Case Request', [
                'ticket_id' => $id,
                'request' => $request->all()
            ]);

            $ticket = SocialTicket::findOrFail($id);

            /*
            * Build Salesforce Case data
            */
            $caseData = [
                'TicketGroup__c' => $request->ticket_group,
                'IGL_Ticket_Type__c' => $request->ticket_type,
                'IGL_Ticket_Category__c' => $request->ticket_category,
                'IGL_Legacy_Case_Id__c' => (string) $ticket->id,
                'Subject' => Str::limit($request->subject, 255, ''),
                'Comments' => $request->comments,

                'Origin' => $request->origin ?? 'Call Center',
                'status' => $response['status'] ?? 'New',
            ];

            if ($request->customer_type === 'registered') {

                $caseData['AccountId'] = $request->account_id;

            } else {

                $caseData['SuppliedName'] = $request->customer_name;
                $caseData['IGL_Mobile__c'] = $request->mobile;
                $caseData['Address_Master__c'] = $request->address_master_id;
            }

            /*
            * Create Salesforce Case
            */
            $salesforceService = app(SalesforceService::class);

            if ($request->case_type === 'ic') {

                $response = $salesforceService->createICCase($caseData);

                $recordType = 'I&C';

            } else {

                $response = $salesforceService->createDomesticCase($caseData);

                $recordType = 'Domestic';
            }

            Log::info('Salesforce Case Created', [
                'response' => $response
            ]);

            /*
            * Save Salesforce Case information locally
            */
            SalesforceCase::create([
                'ticket_id' => $ticket->id,
                'salesforce_case_id' => $response['id'] ?? null,
                'case_number' => $response['caseNumber'] ?? null,
                'record_type' => $recordType,
                'status' => $response['status'] ?? 'New',
            ]);

            return redirect()
                ->back()
                ->with('success', 'Salesforce Case Created Successfully');

        } catch (\Exception $e) {

            Log::error('Salesforce Case Creation Failed', [
                'message' => $e->getMessage(),
                'ticket_id' => $id
            ]);

            return redirect()
                ->back()
                ->with('message', 'Salesforce Case not created: ' . $e->getMessage());
        }
    }


    public function getSalesforceCaseStatus(Request $request, $id)
    {
        try {
            $salesforceCase = SalesforceCase::where('salesforce_case_id',$id)->latest()->firstOrFail();
            $salesforceService = app(SalesforceService::class);

            $liveCase = $salesforceService->getCaseStatus(
                (string) $salesforceCase->salesforce_case_id
            );

            $salesforceCase->update([
                'case_number' => $liveCase['CaseNumber']
                    ?? $salesforceCase->case_number,

                'status' => $liveCase['Status']
                    ?? $salesforceCase->status,
            ]);

            /*
            * Get local ticket information.
            */
            $ticket = SocialTicket::leftJoin(
                'users',
                'users.id',
                '=',
                'tb_socialticket.assigned_to'
            )
            ->select(
                'tb_socialticket.*',
                'users.name'
            )
            ->where(
                'tb_socialticket.id',
                $salesforceCase->ticket_id
            )
            ->first();

            $caseNumber = $liveCase['CaseNumber'] ?? '-';
            $status = $liveCase['Status'] ?? '-';

            $statushtml = "";

            $statushtml .= "<table style='border-collapse: collapse; width: 100%;'>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>Case Number:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        {$caseNumber}
                    </td>
                </tr>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>Salesforce Case ID:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        {$salesforceCase->salesforce_case_id}
                    </td>
                </tr>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>BP Number:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        " . ($ticket->bipNumber ?? '-') . "
                    </td>
                </tr>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>Assigned To:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        " . ($ticket->name ?? '-') . "
                    </td>
                </tr>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>Record Type:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        " . ($salesforceCase->record_type ?? '-') . "
                    </td>
                </tr>";

            $statushtml .= "
                <tr>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        <strong>Status:</strong>
                    </td>
                    <td style='border: 1px solid #000; padding: 8px;'>
                        {$status}
                    </td>
                </tr>";

            $statushtml .= "</table>";

            return $statushtml;

        } catch (\Exception $e) {

            Log::error('Salesforce Case Status Failed', [
                'message' => $e->getMessage(),
                'salesforce_case_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


}
