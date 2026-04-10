@extends('auth.layouts')

@section('content')

<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-mailbox iconsbg"></i>Social Posts </h2>
                    </div>
                </div>
                <div class="col-md-6">
				
                    <div class="iconsmenu">
                        <ul class="ms-auto">
                            <li><i class="bi bi-funnel" data-bs-toggle="modal" data-bs-target="#exampleModal"></i></li>
                            <li><i class="bi bi-x-lg"></i></li>
                            <li><i class="bi bi-list-task"></i></li>
                            <li><i class="bi bi-plus-lg"></i></li>
                            <li><i class="bi bi-check2-square"></i></li>
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Post ID</th>
                        <th scope="col">Post message</th>
                        <th scope="col">Social User</th>
                        <th scope="col">Source</th>
                        <th scope="col">Post Url</th>
                        <th scope="col">Post Date</th>
                        <th scope="col">Edit</th>
                    </tr>
                </thead>
                <tbody>
					@if(!empty($post) && $post->count())
					@foreach($post as $key => $posts)
                    <tr>
      
                        <td scope="row"> <input class="form-check-input checkDele" type="checkbox" value="{{$posts->id}}" id="{{$posts->id}}">
						{{$posts->id}}</td>
                        <td>{{$posts->id}}</td>
                        <td>{!! getUrlinString($posts->postMessage)!!}</td>
                        <td>{{$posts->socialUser}}</td>
                        <td>{{$posts->source}}</td>
                        <td >{{$posts->postUrl}}</td>
                        <td>{{$posts->postDate}}</td>
                        <td>
                            <div class="editer_file">
                            <i class="bi bi-pencil-square"></i>
                            <i class="bi bi-gear"></i>
                            </div>
                        </td>
                   
                    </tr>
                  
                    @endforeach
					@endif
                </tbody>
            </table>
            </div>
				<div class="my-2 d-flex ">
				
				 {!! $post->links() !!}
				</div>
        </div>
    </div>
</div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<form method="POST" action="{{ route('dashboard') }}">
	@csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="filter">
                    <!-- Nav pills -->
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill" href="#home">Quick Filter </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#menu1">Advanced Filter</a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="home" class="container tab-pane active"><br>
                            <div class="tabsmain">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="text" class="form-label">Post ID</label>
                                            <input type="email" class="form-control" id="postid"
                                                placeholder="Post ID" name="postid">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user" class="form-label">Social User</label>
                                            <input type="text" class="form-control" id="user" placeholder="Social User" name="user">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="url" class="form-label">Post URL</label>
                                            <input type="url" class="form-control" id="url"
                                                placeholder="Post URL" name="url">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Post Message</label>
                                            <input type="email" class="form-control" id="message"
                                                placeholder="Post Message"name="message">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="source" class="form-label">Source</label>
                                            <textarea class="form-control" id="source" rows="3" placeholder="Source" name="source"></textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="converted" class="form-label">Converted</label>
                                            <input type="text" class="form-control" id="converted" name="converted"
                                                placeholder="converted">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="created" class="form-label">Created By</label>
                                            <input type="date" class="form-control" id="created"
                                                placeholder="Created By" name="created">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="menu1" class="container tab-pane fade"><br>
                            <h3>Menu 1</h3>
                            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.</p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Filter</button>
				<button type="submit" class="btn btn-danger" name="download" value="download">Download</button>
            </div>
        </div>
		</form>
    </div>
</div>
<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "64px";
    document.getElementById("main").style.marginLeft = "64px";
}

</script>

@endsection