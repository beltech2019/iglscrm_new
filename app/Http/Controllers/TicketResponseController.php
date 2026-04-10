<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialTicket;
use App\Models\SapTicketCodeGroups;
use App\Models\TicketSapGroups;
use Log;

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
}
