@extends('layouts.app')

<style>
.profile-img {
    max-width: 150px;
    border: 5px solid #fff;
    border-radius: 100%;
    box-shadow: 0 2px 3px rgba(0,0,0,0.3);
}
</style>

@section('content')
<div class="row">
<div class="col-md-6 col-md-offset-3 text-center">
    <div class="panel panel-default">
        <div class="panel-body">
            <img src="../img/profile.jpg" alt="" class="profile-img">
            <h3>{{ $user->name }}</h3>
            <h5>{{ $user->email }}</h5>
            <h5>{{ $user->dob->format('l j F Y')}} ({{ $user->dob->age }} years old)</h5>
            <button class="btn btn-success">Follow</button>
        </div>
    </div>
</div>
</div>
@endsection