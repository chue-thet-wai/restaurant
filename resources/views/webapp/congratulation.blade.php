@extends('layouts.web_app')

@section('content')
<div class="container">
    <div class="mt-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="text-center">
            <div class="icon-check">
                <i class="bi bi-check-circle"></i>
            </div>
            <h2 class="congratulations-text">Congratulations!</h2>
            <a href="/reservation" class="btn-home">
                <button type="button" class="mt-3 px-4 btn">Back to Home</button>
            </a>
        </div>
    </div>
</div>
@endsection
