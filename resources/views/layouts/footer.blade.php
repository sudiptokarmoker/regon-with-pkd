<div class="row">
    <div class="col-md-3">
        <div class="footer-left">
            Logged in as <b class="text-info">{{ Auth::user()->name }}</b>
        </div>
    </div>
    <div class="col-md-3">
        Access to &copy; {{ date('Y') }}
    </div>
    <div class="col-md-3">
        <a href="#">Buy access link</a>
    </div>
    <div class="col-md-3">
        <a data-toggle="modal" data-target="#changePasswordModal" href="#" data-id="{{ \Auth::id() }}">Change password</a>
    </div>
</div>
