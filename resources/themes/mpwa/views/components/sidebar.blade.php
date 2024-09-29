
<div class="app align-content-stretch d-flex flex-wrap">
    <div class="app-sidebar">
        <div class="logo">
            <a href="{{route('home')}}" class="logo-icon"><span class="logo-text">{{config('config.footer_name')}}</span></a>
            <div class="sidebar-user-switcher user-activity-online">
                <a href="/">
                    <img src="{{asset('images/avatars/avatar2.png')}}">
                    <span class="activity-indicator"></span>
                    <span class="user-info-text">{{ Auth::user()->username}}<br></span>
                </a>
            </div>
        </div>
        <div class="app-menu">
            <ul class="accordion-menu">
                <li class="sidebar-title">
                    Apps
                </li>
                <li class="{{request()->is('home') ? 'active-page' : ''}}">
                    <a href="{{route('home')}}" class=""><i class="material-icons-two-tone">dashboard</i>{{__('system.home')}}</a>
                </li>
                 <li class="{{request()->is('file-manager') ? 'active-page' : ''}}">
                    <a href="{{route('file-manager')}}" class=""><i class="material-icons-two-tone">folder</i>{{__('File Manager')}}</a>
                </li>
                <x-select-device></x-select-device>
                @if(Session::has('selectedDevice'))
                 
                <li class="{{request()->is('autoreply') ? 'active-page' : ''}}">
                    <a href="{{route('autoreply')}}" class=""><i class="material-icons-two-tone">message</i>{{__('system.autoreply')}}</a>
                </li>
                <li class="{{request()->is('tag') ? 'active-page' : ''}}">
                    <a href="{{route('tag')}}"><i class="material-icons-two-tone">contacts</i>Phone Book</a>
                   
                </li>
                <li class="{{request()->is('campaign/create') ? 'active-page' : ''}}">
                    <a href="{{route('campaign.create')}}" class=""><i class="material-icons-two-tone">email</i>Create Campaign</a>
                </li>
                <li class="{{request()->is('campaigns') ? 'active-page' : ''}}">
                    <a href="{{route('campaign.lists')}}" class=""><i class="material-icons-two-tone">history</i>List Campaign</a>
                </li>
                <li class="{{request()->is('message/test') ? 'active-page' : ''}}">
                    <a href="{{route('messagetest')}}" class=""><i class="material-icons-two-tone">note</i>{{__('system.test')}}</a>
                </li>
                @endif
                <li class="{{request()->is('rest-api') ? 'active-page' : ''}}">
                    <a href="{{route('rest-api')}}"><i class="material-icons-two-tone">api</i>{{__('system.restapi')}}</a>
                </li>
                 <li class="{{request()->is('user/change-password') ? 'active-page' : ''}}">
                    <a href="{{route('changePassword')}}"><i class="material-icons-two-tone">settings</i>Setting</a>
                </li>
              
                {{-- <li class="{{request()->is('schedule') ? 'active-page' : ''}}">
                    <a href="{{route('scheduleMessage')}}" class=""><i class="material-icons-two-tone">schedule</i>Schedule Message</a>
                </li> --}}
                {{-- only level admin --}}
                @if(Auth::user()->level == 'admin')
                <li class="sidebar-title">
                    Admin Menu
                </li>

                <li class="{{request()->is('settings') ? 'active-page' : ''}}">
                    <a href="{{route('settings')}}"><i class="material-icons-two-tone">settings</i>Setting Server</a>
                </li>
                <li class="{{request()->is('admin/manage-user') ? 'active-page' : ''}}">
                    <a href="{{route('admin.manageUser')}}"><i class="material-icons-two-tone">people</i>User Manager</a>
                </li>
                @endif
               

            </ul>
        </div>
    </div>
    <div class="app-container">
        <div class="search">
            <form>
                <input class="form-control" type="text" placeholder="Type here..." aria-label="Search">
            </form>
            <a href="#" class="toggle-search"><i class="material-icons">close</i></a>
        </div>
        <div class="app-header">
            <nav class="navbar navbar-light navbar-expand-lg">
                <div class="container-fluid">
                    <div class="navbar-nav" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i></a>
                            </li>
                           
                        </ul>

                    </div>
                    <div class="d-flex">
                       
                        <ul class="navbar-nav">
                         

                            <li class="nav-item hidden-on-mobile">
                                <a class="nav-link nav-notifications-toggle" id="notificationsDropDown" href="#" data-bs-toggle="dropdown">4</a>
                                <div class="dropdown-menu dropdown-menu-end notifications-dropdown" aria-labelledby="notificationsDropDown">
                                    <form action="{{route('logout')}}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-header h6 " style="border: 0; background-color :white;">Logout</button>
                                    </form>
                                        {{-- <a href={{route('user.changePassword')}} class="dropdown-header h6" style="border: 0; background-color :white;">Setting</a>
                               --}} </div> 
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>