<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reports;
use App\Models\Field;
use App\Models\ReportMapping;
use Log;
use DB;

class ReportsController extends Controller
{
    public function createUpdateReport(Request $request,$id =null)
    {
		DB::beginTransaction();
		try{
            $reportInfo ="";
            $reportMappingInfo ="";
			$leadById=loggedUserId();
			$requestInfoReport = [
                'report_name'=>$request->input('report_name'),
			//	'assigned_to'=>$request->input('assigned_to'),
				'module_name'=>$request->input('module_name'),
                'created_by'=>$leadById,
            ];

            if($id){
                $reportInfo = Reports::find($id);
                $reportInfo->update($requestInfoReport);
            }else{
                $reportInfo = Reports::create($requestInfoReport);
            }
            $requestInfo = [];
		
			if($request->confield)
			{
				$index = 0;
				foreach($request->confield as $confield)
				{
					$requestInfoReportMapping = [
						'report_id'=>$reportInfo->report_id,
						'field_key'=>$confield,
						'operator'=>$request->oprater[$index],
						'value'=>$request->value[$index]?$request->value[$index]:'',
						'type'=>$request->type[$index]?$request->type[$index]:'',
						'logic'=>$request->logic[$index]?$request->logic[$index]:'',
						'custom_field_value'=>$request->dataType[$index]?$request->dataType[$index]:'',
					];
					
					$requestInfo[] = $requestInfoReportMapping;
					$index++;
				}
			}
			
			$fieldInfo = [];
			if($request->select_field)
			{
				$index = 0;
				foreach($request->select_field as $select_field)
				{
					$fieldInfoMapping = [
						'report_id'=>$reportInfo->report_id,
						'field_key'=>$select_field,
						'field_label'=>$request->field_label[$index],
						'is_show'=>1
					];
					$fieldInfo[] = $fieldInfoMapping;
					$index++;
				}
			}
		
            if($id){
                $reportMappingInfo = ReportMapping::find($id);
                $reportMappingInfo->update($requestInfoReportMapping);
            }else{
                $reportMappingInfo = ReportMapping::insert($requestInfo);
                $reportMappingInfo = Field::insert($fieldInfo);
            }

			DB::commit();
            return redirect()->route('getReportList');
       } catch(Exception $e) {
           DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function getReportList(Request $request)
    {
		try{
			$reportList = Reports::leftjoin('users','users.id','=','tb_reports.created_by')
			->orderby('report_id','desc')
			->paginate(getValueByKey('PAGENATION_COUNT'));
            return \View::make('reports.report_list', compact(['reportList']));
		} catch(Exception $e) {
			return redirect()->back()->with('message',$e->getMessage());
        }
    }

    public function deleteReport($id)
    {  try{
           $item = Reports::find($id);
           $itemMapping = ReportMapping::where('report_id',$id)->get();    
           $fieldMapping = Field::where('report_id',$id)->get();    
           if (!$item) {
               throw new Exception('Item not found');
            }
			$item->delete();	
			foreach($itemMapping as $itemMap)
			{
				$itemMap->delete();
			}
			
			foreach($fieldMapping as $fieldMappi)
			{
				$fieldMappi->delete();
			}
			
			
			
           return redirect()->route('getReportList');
        } catch(Exception $e) {
           return redirect()->back()->with('message',$e->getMessage());
        }
    }
	
	public function getColumnInformation()
	{
		$options = getOptionByKey('OPERATOR,TYPE');
		
		$tablesName= getValueByKey('TABLES_NAME');
		$tableNameArray= explode(',',$tablesName);
		$tableInfo  = [];
		foreach($tableNameArray as $tableNameArrays) {
		   $tableName = $tableNameArrays;
		   $columns = DB::select("SHOW COLUMNS FROM $tableName");
		   $tableInfotemp['tableName']  = $tableName;
		   $tableInfotemp['tablelabel']  = changeByKey($tableName);
		   foreach ($columns as $column) {
			  $column->data = [];
			  $columnName = $column->Field;
			  
			  $name = changeByKey($columnName);
			  $dataType = $column->Type;
			  $dataTypes = explode("(",$dataType);
			  $column->isPrimary = false;  
			  $column->label = strtolower($name);  
			  if($column->Extra)
			  {
				$column->isPrimary = true;  
			  }
			  if(in_array($dataTypes[0],['bigint','int']))
			  {
				$column->dataType = 'NUMBER';
				$column->operator = getKeyByValue($options,'OPERATOR','NUMBER');
				$column->type = getKeyByValue($options,'TYPE','NUMBER');
			  }
			  elseif(in_array($dataTypes[0],['varchar','text']))
			  {
				$column->dataType = 'STRING';
				$column->operator = getKeyByValue($options,'OPERATOR','STRING');
				$column->type = getKeyByValue($options,'TYPE','STRING');

			  }
			  elseif(in_array($dataTypes[0],['timestamp','datetime']))
			  {
				$column->dataType = 'DATETIME';
				$column->operator = getKeyByValue($options,'OPERATOR','DATETIME');
				$column->type = getKeyByValue($options,'TYPE','DATETIME');

			  }
			  elseif(in_array($dataTypes[0],['enum']))
			  {
				$dataInfo = explode(")",$dataTypes[1]);
				$tempDataArray = explode(",",$dataInfo[0]);
				$dataArray =[];
				$remove[] = "'";
				$remove[] = '"';
				$remove[] = "-";
				foreach($tempDataArray as $val)
				{
					$dataArray[] = str_replace( $remove, "", $val );
					//$dataArray[] = str_replace(array('\'', '"'), '', $val);
				}
				$column->dataType = 'ENUM';
				$column->data = $dataArray;
				$column->operator = getKeyByValue($options,'OPERATOR','ENUM');
				$column->type = getKeyByValue($options,'TYPE','ENUM');

			  }
			  elseif(in_array($dataTypes[0],['tinyint']))
			  {
				$dataArray = [];
				if(in_array($column->Null,['YES','NO']))
				{
					$dataArray = ['Yes','No'];
				}
				$column->dataType = 'TINYINT';
				$column->data = $dataArray;
				$column->operator = getKeyByValue($options,'OPERATOR','TINYINT');
				$column->type = getKeyByValue($options,'TYPE','TINYINT');

			  }
			  
			}
			//$tableNameArray['column'] = $columns;
			$tableInfotemp['column']  = $columns;
			$tableInfo[] = $tableInfotemp;
		}
		
		return $tableInfo;
	}
	
	
	
	public function createReport(Request $request,$id =null)
    {
		DB::beginTransaction();
		try{
			$tablesName= getValueByKey('TABLES_NAME');
			$tableNameArray= explode(',', $tablesName);
            $tablesInfo = $this->getColumnInformation();
			$reportData = [];
			if($id)
			{
				$item = Reports::find($id);
			    $itemMapping = ReportMapping::where('report_id',$id)->get();    
			    $fieldMapping = Field::where('report_id',$id)->get();
				
				$reportData['report'] = $item;
				$reportData['reportMapp'] = $itemMapping;
				$reportData['reportMappField'] = $fieldMapping;
			}
		DB::commit();
        return \View::make('reports.createReport', compact(['tablesInfo','reportData','tableNameArray']));	
       } catch(Exception $e) {
           DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
    }


	public function showReport(Request $request,$id)
    {
		DB::beginTransaction();
		try{
			$report = Reports::leftjoin('users','users.id','=','tb_reports.created_by')
			->where('report_id',$id)
			->first();
			if(!$report)
			{
				throw new Exception("INVALID_REPORT_ID");
			}
			$field = Field::where('report_id',$id)->get();
			
			$fields = "*";
			$comma = "";
			if($field && count($field) > 0)
			{
				foreach($field as $fieldData)
				{
					//$fields .= $comma.'"'. $fieldData->field_key.'"' ;
					$comma = ", ";
				}
			}
			
			$conditaion = ReportMapping::where('report_id',$id)->get();
			
			$data = DB::table($report->module_name)->select($fields);
			$fieldsConditaion = "";
			$comma = "";
			if($conditaion && count($conditaion) > 0)
			{
				foreach($conditaion as $conditaionData)
				{
					$value = $conditaionData->value;
					$operator = getOperator($conditaionData->operator);
					if($operator == 'like')
					{
						if($conditaionData->operator == 'CONTAINS')
						{
							$value = "%".$value."%";
						}
						elseif($conditaionData->operator == 'STARTS_WITH')
						{
							$value = "%".$value;
						}
						elseif($conditaionData->operator == 'ENDS_WITH')
						{
							$value = $value."%";
						}
						
					}
					// if data type date and date time that time logic
					if($conditaionData->custom_field_value == "DATETIME" && $conditaionData->type != "FIELD")
					{
						
						if(in_array($conditaionData->type,['PERIOD']))
						{
							$value = getDateRange($value);
						}
						if($conditaionData->logic && $conditaionData->logic == "OR")
						{
							$data = $data->orWhereDate($conditaionData->field_key,$operator,$value);
						}
						else{
							
							$data  = $data->whereDate($conditaionData->field_key,$operator,$value);
						}

					}
					else{
						if($conditaionData->logic && $conditaionData->logic == "OR")
						{
							$data = $data->orWhere($conditaionData->field_key,$operator,$value);
						}
						else{
							$data  = $data->where($conditaionData->field_key,$operator,$value);
						}
					}
					
				}
			}
			//DB::enableQueryLog();
			
			if($request->download)
			{
				$col = [];
				$data = $data->get();
				if(!empty($field) && $field->count())
				{
				   foreach($field as $key => $fields) 
				   {			   
						$col[] = $fields->field_label;
				   }
				}
				return	downloadReportCsv($data,$col,$field);
			}
			else{
				$data = $data->paginate(getValueByKey('PAGENATION_COUNT'));	
			}
			//$query = DB::getQueryLog();
			
			DB::commit();
         return \View::make('reports.reportById', compact(['report','data','field']));	
       } catch(Exception $e) {
           DB::rollback();
			return redirect()->back()->with('message',$e->getMessage());
        }
    }
}
