<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Validator;
use Log;

class TemplateController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except([]);
    }
    public function createUpdateTemplate(Request $request,$id=null)
    {	
		try{
			$template = false;
			if($id)
			{
				$template = Template::find($id);
			}
		} catch(Exception $e) {
			Log::debug($e->getMessage());
			return redirect()->back()->with('message',$e->getMessage());
       }
        return view('template.register',compact('template'));
    }

    public function storeTemplate(Request $request, $id = null)
    {
        $request->validate([
            'template_name' => 'required|string|max:255|unique:tb_template,template_name,' . $id,
            'template_content' => 'required|string|max:255',
        ]);

        try {
            if ($id) {
                $template = Template::find($id);
                $oldVal=Template::find($id);
                if ($template) {
                    $template->template_name = $request->template_name;
                    $template->template_content = $request->template_content;
                    $template->save();
                    $changes = $template->getChanges();
                    if($changes){
                        adminChange($oldVal,$changes,'tb_template','update');
                    }
                    $msg = "Successfully updated!";
                    return redirect()->route('getTemplateList')->withSuccess($msg);
                } else {
                    return redirect()->back()->with('message', 'Template not found.');
                }
            } else {
                $template = Template::create([
                    'template_name' => $request->template_name,
                    'template_content' => $request->template_content,
                    'template_code' => changeByCodeKey($request->template_name)
                ]);
                adminChange("no",$template,'tb_template','create');
                $msg = "Successfully registered!";
                return redirect()->route('getTemplateList')->withSuccess($msg);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::debug($e->getMessage());
            return redirect()->back()->with('message', $e->getMessage());
        }
    }


    public function getTemplateList()
    {
		try{
			$allTemplates = Template::get();

            return \View::make('template.getTemplateList', compact('allTemplates'));	
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
    }
	public function deleteTemplate($id)
    {
		try{
			$template = Template::find($id);
			$template->delete();
            adminChange("no",$id,'tb_template','delete');
            return redirect()->back()->with('message','Deleted');	
		} catch(Exception $e) {
           DB::rollback();
           Log::debug($e->getMessage());
		   return redirect()->back()->with('message',$e->getMessage());
       }
    }
}
