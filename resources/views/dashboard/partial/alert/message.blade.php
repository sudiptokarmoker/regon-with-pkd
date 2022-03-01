@if (session('message'))
    <div class="row mt-5">
        <div class="col-12 alert alert-success">
            {{ session('message') }}
        </div>
    </div>
@endif
