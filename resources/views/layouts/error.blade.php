@if(count($errors))
    @foreach($errors->all() as $e)
        <p class="alert alert-danger">{{$e}}</p>
    @endforeach
@endif

@if(Session::has('success'))
    <!--<p class="alert alert-success">{{Session::get('success')}}</p>-->
    <div class="alert alert-success">{{Session::get('success')}}</div>
@endif

@if(Session::has('danger'))
    <!--<p class="alert alert-danger">{{Session::get('danger')}}</p>-->
    <div class="alert alert-danger">{{Session::get('danger')}}</div>
@endif