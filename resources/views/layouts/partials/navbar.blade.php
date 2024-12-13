<nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"> 
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> 
                    <i class="bi bi-list"></i> 
                </a> 
            </li>
           
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->

        <ul class="navbar-nav ms-auto"> 
            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu"> 
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> 
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" class="user-image rounded-circle" alt="User Image"> 
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> 
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User Image -->
                    <li class="user-header text-bg-primary"> 
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle shadow" alt="User Image">
                        <p>
                            {{ Auth::user()->name }} - Web Developer
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li> 
                    <!-- Menu Footer -->
                    <li class="user-footer"> 
                        <a href="{{route('profile.edit')}}" class="btn btn-default btn-flat">Profile</a> 
                        <a href="#" class="btn btn-default btn-flat float-end" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li> 
                </ul>
            </li> <!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->
</nav> <!--end::Header-->
