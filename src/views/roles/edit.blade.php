@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit this Role</div>

                <div class="panel-body">
                    <form action="{{ route('admin.role.update', $role->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <div class="form-group">
                            <label for="role">Role Name</label>
                            <input type="text" autofocus value="{{ $role->name }}" name="name" class="form-control" id="role">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Change</button>
                        <a href="{{ route('admin.roles') }}" class="btn btn-danger btn-sm pull-right">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection