 <!--  BEGIN SIDEBAR  -->
 <div class="sidebar-wrapper sidebar-theme">
     <nav id="sidebar">
         <div class="navbar-nav theme-brand flex-row text-center">
             <div class="nav-logo">
                 <div class="nav-item theme-logo" style="width:90px;">
                     <a href="{{ route('dashboard') }}">
                         <img src="{{ asset('assets/img/logo.svg') }}" class="" alt="logo" onerror="this.src='{{asset('adminProfile/defaulta_profile.jpg')}}'" class="rounded-circle">
                     </a>
                 </div>
                 <div class="nav-item theme-text">
                     <a href="{{ route('dashboard') }}" class="nav-link"> TMS </a>
                 </div>
             </div>
             <div class="nav-item sidebar-toggle">
                 <div class="btn-toggle sidebarCollapse">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                         <polyline points="11 17 6 12 11 7"></polyline>
                         <polyline points="18 17 13 12 18 7"></polyline>
                     </svg>
                 </div>
             </div>
         </div>

         <div class="shadow-bottom"></div>

         <ul class="list-unstyled menu-categories" id="accordionExample">
             <li class="menu menu-heading">
                 <div class="heading">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-minus">
                         <line x1="5" y1="12" x2="19" y2="12"></line>
                     </svg>
                     <span>MAIN NAVIGATIONS</span>
                 </div>
             </li>

            <li class="menu {{Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Task Management</span>
                </div>
            </li>

            <li class="menu {{Request::is('task/add') ? 'active' : '' }}">
                <a href="{{ route('task.add') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Add Task</span>
                    </div>
                </a>
            </li>

            <li class="menu {{Request::is('task/list') ? 'active' : '' }}">
                <a href="{{ route('task.list') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span>Task List</span>
                    </div>
                </a>
            </li>
            
         </ul>
     </nav>
 </div>
 <!--  END SIDEBAR  -->