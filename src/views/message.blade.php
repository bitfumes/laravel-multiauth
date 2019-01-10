@if (session()->has('message') || session()->has('status'))
    <div class="alert alert-success">{{ session()->get('message') }}</div>
@endif

@if ($errors->count() > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
@endif