 <header class="top-header">        
        <nav class="navbar navbar-expand gap-3 align-items-center">
          <div class="mobile-toggle-icon fs-3">
              <i class="bi bi-list"></i>
            </div>
            <form class="searchbar">
                <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
                <input class="form-control" type="text" placeholder="{{__('Type here to search')}}">
                <div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
            </form>
            <div class="top-navbar-right ms-auto">
              <ul class="navbar-nav align-items-center">
                <li class="nav-item search-toggle-icon">
                  <a class="nav-link" href="#">
                    <div class="">
                      <i class="bi bi-search"></i>
                    </div>
                  </a>
              </li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 15px;">
					<i class="bi bi-globe"></i>
					{{ __('Language') }}
				</a>
				<ul class="dropdown-menu" aria-labelledby="languageDropdown">
					@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
						<li>
							<a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
								<span class="flag-icon flag-icon-{{ strtolower($localeCode) }}"></span>
								{{ $properties['native'] }}
							</a>
						</li>
					@endforeach
				</ul>
			</li>
              <li class="nav-item dropdown dropdown-user-setting">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                  <div class="user-setting d-flex align-items-center">
                    <img src="{{asset('assets/images/avatars/avatar-1.png')}}" class="user-img" alt="">
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                          <img src="{{asset('assets/images/avatars/avatar-1.png')}}" alt="" class="rounded-circle" width="54" height="54">
                          <div class="ms-3">
                            <h6 class="mb-0 dropdown-user-name">{{Auth::user()->username}}</h6>
                            <small class="mb-0 dropdown-user-designation text-secondary">{{__(Auth::user()->level)}}</small>
                          </div>
                       </div>
                     </a>
                   </li>
                   <li><hr class="dropdown-divider"></li>
                   
                    <li>
                      <a class="dropdown-item" href="{{route('user.settings')}}">
                         <div class="d-flex align-items-center">
                           <div class=""><i class="bi bi-gear-fill"></i></div>
                           <div class="ms-3"><span>{{__('Setting')}}</span></div>
                         </div>
                       </a>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <form action="{{route('logout')}}" method="post" >
                        @csrf
                      <button class="dropdown-item" type="submit">
                         <div class="d-flex align-items-center">
                           <div class=""><i class="bi bi-lock-fill"></i></div>
                           <div class="ms-3"><span>{{__('Logout')}}</span></div>
                         </div>
                       </button>
                      </form>
                    </li>
                </ul>
              </li>
              </ul>
              </div>
        </nav>
</header>