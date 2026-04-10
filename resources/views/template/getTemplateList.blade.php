@extends('auth.layouts') <!-- Assuming you have a layout file, adjust this based on your project structure -->

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
        <h3>Template List</h3>
        <a href="/createUpdateTemplate" class="btn btn-primary mb-3 " id="pry">Add New Template</a>

        <table class="table {{ addUIComponent('TEMPLATE_TABLE') }}">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allTemplates as $template)
                    <tr>
                        <td>{{ $template->template_name }}</td>
                        <td>{{ $template->template_content }}</td>
                       
                        <td>
						 <div class="dropdown">   
                                <button class="settingicons" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">   
                                <a href=""><i class="bi bi-gear mainstyle"></i></a>                                </button>
                                <ul class="dropdown-menu dropdownmenu_innner " aria-labelledby="dropdownMenuButton1">
                                    <li class="{{ addUIComponent('TEMPLTE_EDIT') }}"><a class="dropdown-item  {{ addUIComponent('TEMPLTE_EDIT') }}" href='/createUpdateTemplate/{{ $template->id }}'><i class="bi bi-pencil"></i> Edit</a></li>
                                    <li {{ addUIComponent('TEMPLTE_DELETE') }}"><a class="dropdown-item deleteLink  {{ addUIComponent('TEMPLTE_DELETE') }}" href="#" id="{{$template->id}}"><i class="bi bi-trash3"></i> Delete</a></li>
                                                                  </ul>
                                </div>
                            
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div> </div>
<script>
    
   $("#admin").addClass("active");
   </script>
   <script>
    var deleteId ="";
    $(document).ready(function() {
       
		
		$(".deleteLink").click(function() {
			deleteId = $(this).attr('id');
			$("#commonModal").modal("show");
			$("#msg").text("Are you sure delete it.");
			$("#commonBtn").show();
        });
		
		$("#commonBtn").click(function() {
			location.href= "/deleteTemplate/"+deleteId;
        });
		
		
    });

    </script>
@endsection
