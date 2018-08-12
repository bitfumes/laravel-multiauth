@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit details of {{$admin->name}}</div>

                <div class="card-body">
                    <form action="/admin/{{ $admin->id }}" method="post">
                        @csrf @method('patch')
                        <div class="form-group">
                            <label for="role">Name</label>
                            <input type="text" value="{{ $admin->name }}" name="name" class="form-control" id="role">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Email</label>
                            <input type="text" value="{{ $admin->email }}" name="name" class="form-control" id="role">
                        </div>
                        
                        <div class="form-group row">
                            <label for="role_id" class="col-md-4 col-form-label text-md-right">Assign Role</label>
                        
                            <div class="col-md-6">
                                <select name="role_id[]" id="role_id" class="form-control" multiple>
                                    <option selected disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Change</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection