        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
			<a href="{{url('/')}}" class="brand-logo">
				<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="50%" viewBox="0 0 225 80" preserveAspectRatio="xMidYMid meet" style="margin: auto;">
					 <g transform="translate(10,10)"> <g transform="translate(0, 5.90625)"> <g fill="#36C95F" transform="translate(0,3)"> <!-- الأخضر --> <g transform="scale(1.12)"> <path d="M40.99-35.16L29.33-35.16L29.33 0L20.54 0L20.54-35.16L8.79-35.16L8.79 0L0 0L0-38.09Q0-43.95 5.86-43.95L5.86-43.95L43.95-43.95Q49.80-43.95 49.80-38.09L49.80-38.09L49.80 0L41.02 0L40.99-35.16Z" transform="translate(0, 43.974609375)"></path> </g> </g> </g> <g transform="translate(57.78, 0)"> <polyline stroke="#ffffff" class="logo_logo_stroke" stroke-width="2" fill-opacity="0" stroke-linejoin="round" points="0,0 0,-5 50.1,-5 50.1,0"></polyline> <polyline stroke="#ffffff" class="logo_logo_stroke" stroke-width="2" fill-opacity="0" stroke-linejoin="round" points="0,60.16 0,65.16 50.1,65.16 50.1,60.16"></polyline> <g fill="#ffffff" class="logo_logo" transform="translate(3,3)"> <!-- الأسود --> <g transform="scale(1.2544)"> <path d="M0-38.09L0-38.09Q0-43.95 5.86-43.95L5.86-43.95L29.3-43.95Q35.16-43.95 35.16-38.09L35.16-38.09L35.16-17.58Q35.16-11.72 29.3-11.72L29.3-11.72L11.72-11.72L14.65-20.51L26.37-20.51L26.37-35.16L8.79-35.16L8.79 0L0 0L0-38.09Z" transform="translate(0, 43.974609375)"></path> </g> </g> </g> <g transform="translate(109.88, 5.90625)"> <g fill="#36C95F" transform="translate(0,3)"> <!-- الأخضر --> <g transform="scale(1.12)"> <path d="M29.33-43.95L29.33-8.79L40.99-8.79L41.02-43.95L49.80-43.95L49.80-5.86Q49.80 0 43.95 0L43.95 0L5.86 0Q0 0 0-5.86L0-5.86L0-43.95L8.79-43.95L8.79-8.79L20.54-8.79L20.54-43.95L29.33-43.95Z" transform="translate(0, 43.974609375)"></path> </g> </g> </g> <g transform="translate(167.66, 5.90625)"> <g fill="#36C95F" transform="translate(0,3)"> <!-- الأخضر --> <g transform="scale(1.12)"> <path d="M8.79 0L0 0L0-38.12Q0-43.97 5.86-43.97L5.86-43.97L29.3-43.97Q35.16-43.97 35.16-38.12L35.16-38.12L35.16 0L26.37 0L26.37-11.75L8.79-11.75L8.79 0ZM26.37-20.54L26.37-35.19L8.79-35.19L8.79-20.54L26.37-20.54Z" transform="translate(0, 43.974609375)"></path> </g> </g> </g> </g> 
				</svg>
			</a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                {{ $title }}
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
							<li class="nav-item">
								<strong>v{{config('app.version')}}</strong>
							</li>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link  ai-icon" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                    <i class="flaticon-381-earth-globe-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3">
										<ul class="timeline">
											@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
												<li>
													<a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
														<span class="flag-icon flag-icon-{{ strtolower($localeCode) }}"></span>
														{{ $properties['native'] }}
													</a>
												</li>
											@endforeach
										</ul>
									</div>
                                </div>
                            </li>
							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
									<i id="icon-light" class="fas fa-sun"></i>
                                    <i id="icon-dark" class="fas fa-moon"></i>
									
                                </a>
							</li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('images/profile/12.png') }}" width="20" alt="">
									<div class="header-info">
										<span><strong> {{Auth::user()->username}}</strong> - ({{__(Auth::user()->level)}})</span>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{route('user.settings')}}" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        <span class="ms-2">{{__('Setting')}} </span>
                                    </a>
                                    <a href="{{route('logout')}}" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ms-2">{{__('Logout')}} </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->
