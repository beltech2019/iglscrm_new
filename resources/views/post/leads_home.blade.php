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
        <div class="bgwhite2">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="heading_two ">
                        <h2><i class="bi bi-ticket iconsbg2"></i>Leads </h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="iconsmenu">
                        <ul class="ms-auto">
                            <li><i class="bi bi-funnel" data-bs-toggle="modal" data-bs-target="#exampleModal"></i></li>
                            <li><i class="bi bi-x-lg"></i></li>
                            <li><i class="bi bi-list-task" data-bs-toggle="modal" data-bs-target="#myModal"></i></li>
                            <li><i class="bi bi-plus-lg"></i></li>
                            <li><i class="bi bi-check2-square"></i></li>
                            <div style="clear:both;"></div>
                        </ul>
                    </div>
                </div>
            </div>
        <div class="formscoulam">
        <table class="table">
  <thead>
    <tr>
      <th scope="col">Date Created</th>
      <th scope="col">Lead Number</th>
      <th scope="col">Description</th>
      <th scope="col">Name</th>
      <th scope="col">Lead Source</th>
      <th scope="col">User</th>

      <th scope="col">Status</th>

    </tr>
  </thead>
  <tbody>
    <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr>
    <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr> <tr>
    <td>11-02-2023, 2:00 PM</td>

      <td>L22515661061</td>
      <td>Description</td>
      <td>Name</td>
      <td>Lead Source</td>
      <td>User</td>
      <td>Status</td>
    </tr>
  </tbody>
</table>
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
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modalwidth">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Search social User</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="modalforms">
        <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label">Assigned to</label>
                                            <select class="form-control" placeholder="Source">
                            <option>Assigned to</option>
                            <option>Facebook</option>
                            <option>Twitter</option>
                            <option>Instagram</option>
                            <option>Linkedin</option>
                            </select>  
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                        <button type="button" class="btn btn-danger marginalign">Search</button>
                                        <button type="button" class="btn btn-secondary marginalign">Clear</button>
                                        </div>
                                    </div>
                                    </div>
            </div>
            <div class="socialuser mt-4">
            <div class="headingmain">
                <h5>Social User</h5>
            </div>
            <table class="table">
  <thead>
    <tr>
   
      <th scope="col">Name</th>
      <th scope="col">Assigned to</th>
      <th scope="col">Date Created</th>
    </tr>
  </thead>
  <tbody>
    <tr>

      <td>Deepesh jangid</td>
      <td></td>
      <td>10-03-2023</td>
    </tr>
    <tr>

    <td>Deepesh jangid</td>
      <td></td>
      <td>10-03-2023</td>
    </tr>
    <tr>

<td>Deepesh jangid</td>
  <td></td>
  <td>10-03-2023</td>
</tr>
<tr>

<td>Deepesh jangid</td>
  <td></td>
  <td>10-03-2023</td>
</tr>
<tr>

<td>Deepesh jangid</td>
  <td></td>
  <td>10-03-2023</td>
</tr>
<tr>

<td>Deepesh jangid</td>
  <td></td>
  <td>10-03-2023</td>
</tr>
 
  </tbody>
</table>
        </div>
      </div>
     

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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