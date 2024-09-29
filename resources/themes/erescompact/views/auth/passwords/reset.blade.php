<x-layout-auth>
    @slot('title', __("Reset Password"))

    <main class="authentication-content mt-5">
        <div class="container-fluid">
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
                            @if (session()->has('alert'))
                                <x-alert>
                                    @slot('type', session('alert')['type'])
                                    @slot('msg', session('alert')['msg'])
                                </x-alert>
                            @endif
                            <h5 class="card-title">{{__('Reset Password')}}</h5>
                            <form class="form-body" action="{{ route('password.update') }}" method="POST">
                                @csrf
								<input type="hidden" name="token" value="{{ $token }}">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="email" class="form-label">{{__('Email')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                <i class="fa-regular fa-envelope"></i>
                                            </div>
                                            <input type="email"
                                                class="form-control radius-30 ps-5 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                id="email" name="email" placeholder="{{__('email')}}" required>
                                        </div>
                                        <p class="text-danger">
                                            @error('username')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
									<div class="col-12">
                                        <label for="password" class="form-label">{{__('Enter new password')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
											<i class="fa-solid fa-lock"></i></div>
                                            <input type="password" name="password" class="form-control radius-30 ps-5"
                                                id="password" placeholder="{{__('Enter new password')}}" required>

                                        </div>

                                    </div>
									<div class="col-12">
                                        <label for="password_confirmation" class="form-label">{{__('Confirm new password')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
											<i class="fa-solid fa-lock"></i></div>
                                            <input type="password" name="password_confirmation" class="form-control radius-30 ps-5"
                                                id="password_confirmation" placeholder="{{__('Confirm new password')}}" required>

                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary radius-30">{{__('Reset')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout-auth>
