<li class="side-menus {{ Request::is('*') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>
<li class="{{ Request::is('opinions*') ? 'active' : '' }}">
    <a href="{{ route('opinions.index') }}"><i class="fa fa-edit"></i><span>@lang('models/opinions.plural')</span></a>
</li>

