@extends('auth.layouts')

@section('content')



    <div class="container-fluid">
        <div class="div_container">
            <div class="bgwhite2">
                <div class="borderalignheading">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="heading_two">
                                <h2><i class="bi bi-ticket iconsbg2"></i>Favourite List</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
                        <div class="my-2 row">
                            <div class="col-md-9">
                                {!! $getFavourite->withQueryString()->links() !!}
                            </div>
                            <div class="col-md-3">
                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                    Showing {{ $getFavourite->firstItem() }} - {{ $getFavourite->lastItem() }} of {{ $getFavourite->total() }} favourite
                                </p>
                            </div>
                        </div>
                <div class="busines_details">
                    <div class="moreinfo">
                    <table class="table">
                        <thead>
                            <tr>   
                            <th scope="col">Id</th>
                            <th scope="col">Message</th>
                            <th scope="col">Type</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($getFavourite) && $getFavourite->count())
                          @foreach($getFavourite as $key => $favouriteToMes)
                            <tr>    
                            <td>
                            <div class="editer_file d-flex">
                            <a href="{{$favouriteToMes ? ($favouriteToMes->type == 'tb_leads' ? '/getLeadById/'.$favouriteToMes->type_id : ($favouriteToMes->type == 'tb_gettweet' ? '/getSocialPostById/'.$favouriteToMes->getTweet_id : '/getSocialTicketById/'.$favouriteToMes->type_id)) : '#'}}">{{$favouriteToMes ? ($favouriteToMes->type == 'tb_leads' ? $favouriteToMes->leadId : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->getTweet_id : $favouriteToMes->ticket_id)) : ''}}</a>
                            </div>     
                            </td>
                            <td class="line_break">{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->description : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->postMessage : $favouriteToMes->ticketPostMessage)}}</td>
                            <td>{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? 'Lead' : ($favouriteToMes->type == 'tb_gettweet' ? 'Post' : 'Ticket')}}</td>
                            <td>{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->first_name. ' ' . $favouriteToMes->last_name : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->socialUser_name : explode('(', $favouriteToMes->socialUser)[0])}}</td>
                            <td>{{$favouriteToMes && $favouriteToMes->type == 'tb_leads' ? $favouriteToMes->created_date : ($favouriteToMes->type == 'tb_gettweet' ? $favouriteToMes->istPostDate : $favouriteToMes->date_Created)}}</td>
                            </tr>
                          @endforeach
                        @endif   
                        </tbody>
                        </table>
                        <div class="my-2 row">
                            <div class="col-md-9">
                                {!! $getFavourite->withQueryString()->links() !!}
                            </div>
                            <div class="col-md-3">
                                <p style="margin-top: 8px;text-align: right;" class="pagination-info">
                                    Showing {{ $getFavourite->firstItem() }} - {{ $getFavourite->lastItem() }} of {{ $getFavourite->total() }} favourite
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>



<script>
$("#admin").addClass("active");

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