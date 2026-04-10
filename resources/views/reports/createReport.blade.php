@extends('auth.layouts')

@section('content')
<?php $reportInfo = false;?>
<?php 
if(isset($reportData['report']))
{
	$reportInfo = $reportData['report'];
}
?>

<div class="container-fluid">
	@if($reportInfo && $reportInfo != "")
	<form method="POST" action="/createUpdateReport/{{$reportInfo->report_id}}" onsubmit="return validateForm();">
		@else
		<?php $reportData  = false;?>
		<form method="POST" action="/createUpdateReport" onsubmit="return validateForm();">
			@endif
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
            <div class="formscoulam">


                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="heading_two ">
                                    <h2><i class="bi bi-ticket iconsbg2"></i>Reports </h2>
                                </div>
                            </div>

                        </div>
                        <div class="formscoulam">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="report_name" class="form-label">Name <span class="starred">*</span></label>
                                        <input type="text" class="form-control" id="report_name" name="report_name" placeholder="Name"
                                            value="{{$reportInfo?$reportInfo->report_name:''}}" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="module" class="form-label">Module</label>
										<select class="form-control"  id="module" name="module_name" onChange="createHtml()">
											@foreach($tableNameArray as $table)
											<option {{$reportInfo && $reportInfo->module_name==$table ? 'selected':''}} value="{{$table}}">{{changeByKey($table)}}</option>
											@endforeach
										</select>  
                                    </div>
                                </div>


                            </div>
                        </div>
            </div>
            
            
        </div>
    </div>
    <div class="bgwhite2" id="createReportBox" style="display:none">
        <div class="vertical_tabs">
            <div class="row">
                <div class="col-md-3">
                    <div class="verticalmenu">
                        <div class="headingmain2 mb-3">
                            <h4>Fields</h4>
                        </div>
                        <ul class="ullinks" id="moduleTree">
							
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tabsnew tabscircle2" id="tabdata">
                        <ul class="nav nav-pills mb-3" style="float:none;" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-field-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-field" type="button" role="tab" aria-controls="pills-field"
                                    aria-selected="true">Fields</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-Conditions-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-Conditions" type="button" role="tab"
                                    aria-controls="pills-Conditions" aria-selected="false">Conditions</button>
                            </li>
                         
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-field" role="tabpanel"
                                aria-labelledby="pills-field-tab">
                                <table class="table {{ addUIComponent('DASHBOARD_TICKETS') }}">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:2%;"></th>
                                            <th scope="col">Module</th>
                                            <th scope="col">Field</th>
                                            
                                            <th scope="col">Label</th>
                                        </tr>
                                    </thead>
                                    <tbody id="field">
                                    @if($reportInfo)
									@foreach($reportData['reportMappField'] as $reportMappField)
									<tr id="field_{{$reportMappField->field_key}}">
									   <td onclick="deleteTr(this)"><i class="bi bi-dash-lg dash"></i></td>
									   <td>{{changeByKey($reportInfo->module_name)}} </td>
									   <td><input type="hidden" value="{{$reportMappField->field_key}}" id="field_{{$reportMappField->field_key}}" name="select_field[]">{{$reportMappField->field_key}}</td>
									   <td>
										  <div class="mb-3"><input type="text" class="form-control" id="text_{{$reportMappField->field_key}}" placeholder="Label" name="field_label[]" value="{{$reportMappField->field_label}}"></div>
									   </td>
									</tr>
								@endforeach
							@endif   
                                        
                                       

                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane fade" id="pills-Conditions" role="tabpanel"
                                aria-labelledby="pills-Conditions-tab">
                                <table class="table">
								  <thead>
									<tr>
									  <th scope="col"></th>
									  <th scope="col">Logic</th>
									  <th scope="col">Module</th>
									  <th scope="col">Field</th>
									  <th scope="col">Operator</th>
									  <th scope="col">Type</th>
									  <th scope="col">Value</th>
									</tr>
								  </thead>
								  <tbody id="condition">
									@if($reportInfo)
									<?php $count = 0;?>
									@foreach($reportData['reportMapp'] as $reportMapp)
									@if($reportMapp->report_id == $reportInfo->report_id)
									
									<tr id="conditaion_{{$reportMapp->field_key}}">
									   <td onclick="deleteTr(this)"><input class="form-check-input" type="hidden" value="{{$reportMapp->field_key}}" id="field_name" name="confield[]"><input class="form-check-input" type="hidden" value="STRING" id="dataType_{{$reportMapp->field_key}}" name="dataType[]"><i class="bi bi-dash-lg dash"></i></td>
									   <td>{{getLogic($count,$reportMapp)}}
									   </td>
									   <td>{{changeByKey($reportInfo->module_name)}}</td>
									   <td>{{changeByKey($reportMapp->field_key)}}</td>
									   <?php $count++; ?>
									   <td>
										  <select class="form-select" aria-label="Default select example" id="oprater-{{$reportMapp->field_key}}" name="oprater[]">
											@foreach($reportMapp->editInfo->operator as $operator)
											 <option value="{{$operator->value}}" {{$operator->value == $reportMapp->operator? "selected":""}}>{{$operator->label}}</option>
											 @endforeach
										  </select>
									   </td>
									   <td>
										  <select class="form-select" aria-label="Default select example" id="type-{{$reportMapp->field_key}}" name="type[]" onchange="showHideValField('{{$reportMapp->field_key}}',this.value)">
											@foreach($reportMapp->editInfo->type as $type)
											 <option value="{{$type->value}}" {{$type->value == $reportMapp->type? "selected":""}}>{{changeByKey($type->label)}}</option>
											 @endforeach
										  </select>
									   </td>
									   <td>
									   
									   
										@foreach($reportMapp->editInfo->type as $type)
										
										@if($type->value == "VALUE")
											@if($reportMapp->editInfo->dataType  =='DATETIME')
												<input type="date" class="form-control  {{$type->value}} showvalue" id="text_{{$type}}{{$reportMapp->field_key}}" placeholder="value" name="value[]" Value="{{$reportMapp->type == 'VALUE'?$reportMapp->value:''}}" {{$reportMapp->type == "VALUE"?'':'style=display:none disabled'}}>
												@else
										  <input type="text" class="form-control  {{$reportMapp->type == 'VALUE'?$reportMapp->value:''}} showvalue" id="text_{{$type->value}}{{$reportMapp->field_key}}" placeholder="value" name="value[]" Value="{{$reportMapp->type == 'VALUE'?$reportMapp->value:''}}" {{$reportMapp->type == "VALUE"?'':'style=display:none disabled'}} >
											@endif
										@elseif($type->value == "CURRENT_USER")
										<input type="hidden" name="value[]" class="form-control  {{$type->value}} showvalue"  value="{{$reportMapp->type == 'CURRENT_USER'?$reportMapp->value:''}}" {{$reportMapp->type == "CURRENT_USER"?'':'disabled'}} />
										@elseif($type->value == "FIELD")
										
											<select class="form-select  {{$type->value}} showvalue" aria-label="Default select example" name="value[]" id="logic-{{$reportMapp->field_key}}" {{$reportMapp->type == "FIELD"?'':'style=display:none disabled'}}>
											
											@foreach($reportMapp->column as $column)
											 <option value="{{$column->Field}}" {{$column->Field == $reportMapp->value && $reportMapp->type == "FIELD"? "selected":""}}>{{changeByKey($column->Field)}}</option>
											 @endforeach
											 
										  </select>
									
										@elseif($type->value == "ONE_OF")
										
											<select class="form-select  {{$type->value}}   {{$reportMapp->custom_field_value}} showvalue" aria-label="Default select example" name="value[]" id="logic-{{$reportMapp->field_key}}" {{$reportMapp->type == "ONE_OF"?'':"style=display:none disabled"}}>
											
											@foreach($reportMapp->editInfo->data as $data)
											 <option value="{{$data}}" {{$reportMapp->type == "ONE_OF" && $reportMapp->value == $data?'selected':''}} >{{$data}}</option>
											 @endforeach
											 
										  </select>
										  @elseif($type->value == "PERIOD" || $type->value == "DATE")
										
											<select class="form-select  {{$type->value}} showvalue" aria-label="Default select example" name="value[]" id="logic-{{$reportMapp->field_key}}" {{$reportMapp->type == "PERIOD"?'':"style=display:none disabled"}}>
											
											@foreach(explode($type->subvalue) as $date)
											 <option value="{{$date}}" {{($reportMapp->type == "PERIOD" || $reportMapp->type == "DATE") && $reportMapp->value == $date?'selected':''}} >{{$date}}</option>
											 @endforeach
											 
										  </select>
										@endif
										@endforeach
										  
										 
									   </td>
									</tr>
									@endif
										
										@endforeach
									@endif 
								  </tbody>
								</table>
                            
                            </div>
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-12">
                <div class="buttons_prime">
                   <button type="submit {{ addUIComponent('ADMINMANAGEMENT_ACCESS_SAVE') }}" class="btn btn-danger">Save</button>
                    <a type="button" href="/getReportList" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
    </div>

</form>
</div>

<style>
.ullinks {
    max-height: 350px;
    overflow-x: scroll;
}
</style>


<script>
var table= "";
var name= "";
var column= [];


$("#admin").addClass("active");

function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}
var tablesInfo = <?php echo json_encode($tablesInfo);?>;
console.log(tablesInfo);

function createHtml()
{
	//$("#field").html('');
	//$("#condition").html('');
	table= "";
	tablelabel= "";
	column= [];
	$("#createReportBox").show();
	var module = $("#module").val();
	var name = $("#name").val();
	tablesInfo.forEach((item,index) => {
		if(module == item.tableName)
		{
			table = item.tableName;
			tablelabel = item.tablelabel;
			column = item.column;
			createFields(column);
		}
	});
}

function createFields(column)
{
	$("#columnField").html("");
	//$("#columnConditaion").html("");
	//$("#columnField").html("");
	var textHtml = "";
	column.forEach((item,index) => {
		var text = item.Field;
		textHtml += "<li id='li_"+text+"' onClick='createField(&#39;"+text+"&#39;)'>"+item.label+"</li>";
		
	});
	$("#moduleTree").html(textHtml);
}

function getField(field)
{
	var data = "";
	column.forEach((item,index) => {
		if(item.Field == field)
		{
			data =  item;
			return false;
		}
	});
	return data;
}

function createField(fieldName)
{
	var currentTab = $("#tabdata button.active").attr('id');
	if(currentTab == 'pills-field-tab')
	{
		createShowField(fieldName);
	}
	else{
		createConditaionField(fieldName);
	}
	
}


function createConditaionField(field)
{
	var fieldName = getField(field);
	// console.log(fieldName);
	var logic = "";
	if($("#condition tr").length > 0)
	{
		 logic = '<select class="form-select" aria-label="Default select example" name="logic[]" id="logic-'+fieldName.Field+'"><option value="AND">AND</option><option value="OR">OR</option></select>';
	}
	
	var conditaionHtml = '<tr id="conditaion_'+fieldName.Field+'"><td onClick="deleteTr(this)"><input class="form-check-input" type="hidden" value="'+fieldName.Field+'" id="field_'+fieldName.Field+'" name="confield[]"><input class="form-check-input" type="hidden" value="'+fieldName.dataType+'" id="dataType_'+fieldName.Field+'" name="dataType[]"><i class="bi bi-dash-lg dash"></i></td><td>'+logic+'</td><td>'+tablelabel+'</td><td>'+fieldName.label+'</td><td><select class="form-select" aria-label="Default select example" id="oprater-'+fieldName.Field+'" name="oprater[]">'+getOprator(fieldName.operator)+'</select></td><td><select class="form-select" aria-label="Default select example" id="type-'+fieldName.label+'" name="type[]" onChange="showHideValField(&#39;'+fieldName.Field+'&#39;,this.value)">'+getType(fieldName.type)+'</select></td><td>'+getValue(fieldName)+'</td></tr>';
	if(!$("#condition #conditaion_"+fieldName.Field).attr('id'))
	{
		$("#condition").append(conditaionHtml);
	}
	
	if($("#condition tr").length > 0)
	{
		$("#condition tr").eq(0).find('td').eq(1).html('<input type="hidden" name="logic[]" value="" >');
	}

	
}


function createShowField(field)
{
	var fieldName = getField(field);
	//console.log(fieldName);
	var fieldHtml = '<tr id="field_'+fieldName.Field+'"><td onClick="deleteTr(this)"><i class="bi bi-dash-lg dash"></i></td><td>'+tablelabel+'</td><td><input  type="hidden" value="'+fieldName.Field+'" id="field_'+fieldName.label+'" name="select_field[]">'+fieldName.label+'</td><td><div class="mb-3"><input type="text" class="form-control" id="text_'+fieldName.label+'"placeholder="Label" name="field_label[]" value="'+fieldName.label+'"></div></td></tr>';
	
	if(!$("#field #field_"+fieldName.Field).attr('id'))
	{
		$("#field").append(fieldHtml);
	}
}

function getOprator(oprator)
{
	var opratorHtml = "";
	oprator.forEach((item,index) => {
		opratorHtml+= '<option value="'+item.value+'">'+item.label+'</option>';
	});
	return opratorHtml;
}

function getType(types)
{
	var typesHtml = "";
	types.forEach((item,index) => {
		typesHtml+= '<option value="'+item.value+'">'+item.label+'</option>';
	});
	return typesHtml;
}

function getValue(field)
{
	var typesHtml = "";
	field.type.forEach((item,index) => {
		typesHtml += createValue(field,item);
	});
	return typesHtml;
}

function createValue(fieldName,item)
{
	console.log(item.value,"value");
	var valueField = "";
	if(item.value == "CURRENT_USER")
	{
		valueField = '<input type="hidden" name="value[]" class="form-control '+item.value+' showvalue"  value="{{loggedUserId()}}" disabled />';
	}
	else if(item.value == "VALUE")
	{
		if(fieldName.dataType  =='DATETIME')
		{
		valueField += '<input type="date" class="form-control '+item.value+' showvalue" id="text_'+item.value+fieldName.label+'"placeholder="value" name="value[]"  >';
		}
		else{
		valueField += '<input type="text" class="form-control  '+item.value+' showvalue" id="text_'+item.value+fieldName.label+'"placeholder="value" name="value[]"  >';
		}
	}
	else if(item.value == "FIELD")
	{
		valueField += '<select class="form-select  '+item.value+' showvalue" aria-label="Default select example" name="value[]" id="logic-'+fieldName.Field+'" style="display:none" disabled >';
		column.forEach((items,index) => {
			valueField += '<option value="'+items.Field+'">'+items.label+'</option>';
		});
		
		valueField +='</select>';
	}
	else if(item.value == "ONE_OF")
	{
		valueField += '<select class="form-select  '+item.value+' showvalue" aria-label="Default select example" name="value[]" id="value-'+fieldName.Field+'" style="display:none" disabled >';
		fieldName.data.forEach((items,index) => {
			valueField += '<option value="'+items+'">'+items+'</option>';
		});
		valueField +='</select>';
	}
	else if(item.value == "DATE" || item.value == "PERIOD")
	{
		var subvalue = item.subvalue;
		subvalue = subvalue.split(",");
		valueField += '<select class="form-select  '+item.value+' showvalue" aria-label="Default select example" name="value[]" id="value-'+fieldName.Field+'" style="display:none" disabled >';
		subvalue.forEach((items,index) => {
			valueField += '<option value="'+items+'">'+items+'</option>';
		});
		valueField +='</select>';
	}
	return valueField;
}

function showHideValField(id,val)
{
	$("#conditaion_"+id+" .showvalue").hide();
	$("#conditaion_"+id+" .showvalue").attr("disabled","disabled");
	$("#conditaion_"+id+" ."+val).show();
	$("#conditaion_"+id+" ."+val).removeAttr("disabled");
}

function deleteTr(ele)
{
	$(ele).parent().remove();
	if($("#condition tr").length > 0)
	{
		$("#condition tr").eq(0).find('td').eq(1).html('<input type="hidden" name="logic[]" value="" >');
	}
}

createHtml();

function validateForm() {
    const fieldTbody = document.getElementById("field");
    const rows = fieldTbody.getElementsByTagName("tr");
    
    if (rows.length < 1) {
        const modal = new bootstrap.Modal(document.getElementById("commonModal"));
		$("#commonModal #msg").text('At least one field row is required');
		$("#commonModal .delete_post_ticket img").attr('src','/images/table.png');
		$("#commonModal .delete_post_ticket img").addClass('imgtable');
        modal.show();
        return false;
    }
    return true;
}

document.getElementById('module').addEventListener('change', deleteAllRows);

function deleteAllRows() {
    const tbody = document.getElementById('field');
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    const conditiontbody = document.getElementById('condition');
    while (conditiontbody.firstChild) {
        conditiontbody.removeChild(conditiontbody.firstChild);
    }
}


</script>
<?php

function getLogic($count,$reportMapp)
{
	if($count > 0){
		echo '<select class="form-select" aria-label="Default select example" name="logic[]" id="logic-'.$reportMapp->logic.'-'.$reportMapp->field_key.'" >
		<option value="AND" ';
		if($reportMapp->logic=="AND"){echo  "selected";}
			echo '>AND</option>';
			echo '<option value="OR"';
			if($reportMapp->logic=="OR"){
				echo "selected";
			}
			echo '>OR</option></select>';
		}
	else { 
		echo '<input type="hidden" name="logic[]" value="'.$reportMapp->logic.'" />';
	}
}
?>
@endsection