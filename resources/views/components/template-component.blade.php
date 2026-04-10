
@php
$data = \App\Models\Template::get();
@endphp
<!-- 
<div class="socialicons">
<a href="#" data-bs-toggle="modal" data-bs-target="#templateModel"><i class="bi bi-hand-index-thumb"></i></a>
<div style="clear:both;"></div>
</div>
-->
<div class="modal fade" id="templateModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">E-mail template</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
		  <thead>
			<tr>
		   
			  <th scope="col">Template Name</th>
			  <th scope="col">Template Content</th>
			</tr>
		  </thead>
		    <tbody id="template">
			@foreach($data as $template)
				<tr onClick='selectTemplate(event, {{ json_encode($template->template_content) }})'>
					<td>{{ $template->template_name }}</td>
					<td>{{ $template->template_content }}</td>
				</tr>
			@endforeach
		    </tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>
<script>
	var openInput = "";
	function openTemplate(id)
	{
		openInput = id;
		$("#templateModel").modal('show');
	}
	
	function selectTemplate(event,content)
	{
		event.preventDefault();
		$(openInput).val(content);
		$("#templateModel").modal('hide');
	}
</script>
