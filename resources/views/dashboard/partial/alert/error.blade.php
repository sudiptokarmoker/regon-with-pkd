@if (session('error'))
<div class="row mt-5">
    <div class="col-12 alert alert-danger">
        {{ session('error') }}
    </div>
</div>
@endif
