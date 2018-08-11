@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">Add New Role</div>

                <div class="card-body">
                    <form action="/admin/role/store" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="role">Role Name</label>
                            <input type="text" placeholder="Give an awesome name for role" name="name" class="form-control" id="role">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Store</button>
                        <a href="/admin/roles" class="btn btn-sm btn-secondary float-right">Roles</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection