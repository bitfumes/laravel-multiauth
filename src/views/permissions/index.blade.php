@extends('multiauth::layouts.app') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    Permissions
                    <span class="float-right">
                        <a href="{{ route('admin.permission.create') }}" class="btn btn-sm btn-success">New Permission</a>
                    </span>
                </div>

                <div class="card-body">
    @include('multiauth::message')
                    <ol class="list-group">
                        @foreach ($permissions as $permission)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $permission->name }}
                            <span class="badge badge-primary badge-pill">{{ $permission->admins->count() }} {{ ucfirst(config('multiauth.prefix')) }}</span>
                            <div class="float-right">
                                <a href="" class="btn btn-sm btn-secondary mr-3" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $permission->id }}').submit();">Delete</a>
                                <form id="delete-form-{{ $permission->id }}" action="{{ route('admin.permission.delete',$permission->id) }}" method="POST" style="display: none;">
                                    @csrf @method('delete')
                                </form>

                                <a href="{{ route('admin.permission.edit',$permission->id) }}" class="btn btn-sm btn-primary mr-3">Edit</a>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection