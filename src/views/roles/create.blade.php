@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading bg-info text-white">Add New Role</div>

                <div class="panel-body">
    @include('multiauth::message')
                    <form action="{{ route('admin.role.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="role">Role Name</label>
                            <input type="text" placeholder="Give an awesome name for role" name="name" class="form-control" id="role" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Store</button>
                        <a href="{{ route('admin.roles') }}" class="btn btn-sm btn-danger pull-right">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection