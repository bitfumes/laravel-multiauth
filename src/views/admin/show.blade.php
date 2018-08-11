@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @foreach ($admins as $admin)
                        {{ $admin->name }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection