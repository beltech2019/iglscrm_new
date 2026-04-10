@extends('auth.layouts')

@section('content')

<!-- <div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @else
                    <div class="alert alert-success">
                        You are logged in!
                    </div>       
                @endif                
            </div>
        </div>
    </div>    
</div> -->


<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Create Post </h2>
                    </div>
                </div>
             
            </div>
        <div class="formscoulam">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Source</label>
                        <select class="form-control" placeholder="Source">
                            <option>Default select</option>
                            <option>Facebook</option>
                            <option>Twitter</option>
                            <option>Instagram</option>
                            <option>Linkedin</option>
                            </select>              
                              </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Post ID</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Post ID">
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Mobile Number</label>
                        <input type="number" class="form-control" id="exampleFormControlInput1" placeholder="Mobile Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Post ID">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Post Date</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Post Date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Social User</label>
                        <input type="text" class="form-control controlicons" id="exampleFormControlInput1" placeholder="Social User">
                        <div class="socialicons">
                        <a href="#"><i class="bi bi-hand-index-thumb"></i></a>
                        <i class="bi bi-trash3"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Post Message</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Post Message"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="buttons_prime">
                    <button type="button" class="btn btn-danger">Save </button>
                    <button type="button" class="btn btn-secondary">Cancel</button>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                                            <label for="exampleFormControlInput1" class="form-label">Post ID</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Post ID">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Social User</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Social User">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Post URL</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Post URL">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Post Message</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Post Message">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Source</label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Source"></textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Converted</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Converted">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Created By</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Created By">
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
                <button type="button" class="btn btn-danger">Filter</button>
            </div>
        </div>
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