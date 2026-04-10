@extends('auth.layouts')

@section('content')
<div class="container-fluid">
    <div class="div_container">
        <div class="bgwhite2 widthsmall">
                <h2>Import Data :</h2>

      <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        
    @csrf
    <div class="form-group">
            <h5 for="table">Select Table :</h5>
            <select name="table" id="table" class="form-control" required>
                <option value="tb_gettweet">GetTweet</option>
                <option value="tb_socialticket">Social Ticket</option>
            </select>
        </div><br>
          
          <input type="file" name="csv_file" >
         <div class="mt-5 buttons_prime">
          <button type="submit" class="btn btn-primary">Import Csv File</button>
          
        </div>

      </form>
</div>
</div>
</div>
@endsection      