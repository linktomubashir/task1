<nav class="app-header navbar z-5 navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->

        <ul class="navbar-nav ms-auto">
            <!-- Subscription Status -->
            @if (Auth::user()->currentSubscription())
                <li class="nav-item">

                    <a href="#" class="nav-link" data-url="{{ route('subscribe.index') }}"
                            data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Brand') }}"
                            data-bs-toggle="tooltip">
                            <i class="bi bi-card-checklist"></i>
                            <span>{{ Auth::user()->currentSubscription()['product']->name ?? 'No Subscription' }}</span>
                        </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{route('subscribe.plan.show')}}">
                        <i class="bi bi-card-checklist"></i>
                        No Subscription
                    </a>
                </li>
            @endif
            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" class="user-image rounded-circle"
                        alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User Image -->
                    <li class="user-header text-bg-primary">
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle shadow"
                            alt="User Image">
                        <p>
                            {{ Auth::user()->name }} - Web Developer
                            <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer -->
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>
                        <a href="#" class="btn btn-default btn-flat float-end"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                            out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
