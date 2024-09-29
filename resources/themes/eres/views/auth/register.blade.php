<x-layout-auth>
    <main class="authentication-content mt-5">
        <div class="container-fluid">
            @slot('title', __('Register'))

            @if (session()->has('alert'))
                <x-alert>
                    @slot('type', session('alert')['type'])
                    @slot('msg', session('alert')['msg'])
                </x-alert>
            @endif

            <div class="row">
                <div class="col-12 col-lg-4 mx-auto">
                    <div class="card shadow rounded-5 overflow-hidden">
                        <div class="card-body p-4 p-sm-5">
							<ul class="navbar-nav me-auto">
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
							</ul>
                            <h5 class="card-title">{{__('Register')}}</h5>
                            <p class="card-text mb-5">{{ __('Hi, welcome to :site_name.', ['site_name' => config('config.site_name')]) }}</p>
                            <form class="form-body" action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="username" class="form-label">{{__('Username')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                <i class="fa-regular fa-user"></i>
                                            </div>
                                            <input type="text"
                                                class="form-control radius-30 ps-5 {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                                id="username" name="username" placeholder="{{__('Username')}}" required>
                                        </div>
                                        <p class="text-danger">
                                            @error('username')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                     <div class="col-12">
                                        <label for="username" class="form-label">{{__('Email')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                {{-- email icon --}}
                                                <i class="fa-regular fa-envelope"></i>
                                            </div>
                                            <input type="text"
                                                class="form-control radius-30 ps-5 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                id="email" name="email" placeholder="{{__('email')}}" required>
                                        </div>
                                        <p class="text-danger">
                                            @error('email')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <label for="password" class="form-label">{{__('Enter Password')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="fa-solid fa-lock"></i></div>
                                            <input type="password" name="password" class="form-control radius-30 ps-5"
                                                id="password" placeholder="{{__('Enter Password')}}" required>

                                        </div>

                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary radius-30">{{__('Register')}}</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{__('Already have account?')}} <a href="{{ route('login') }}">{{__('Sign in here')}}</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
</x-layout-auth>
