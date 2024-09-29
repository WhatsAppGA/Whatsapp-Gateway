<x-layout-auth>
    @slot('title', __('Authenticator 2FA'))

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
                            <h5 class="card-title">{{__('Authenticator 2FA')}}</h5>
                            <p class="card-text mb-5">{{__('Enter the code shown on the Authenticator app')}}</p>
                            <form method="POST" action="{{ route('2fa.verify') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="2fa_code" class="form-label">{{__('Authenticator Code')}}</label>
                                        <div class="ms-auto position-relative">
                                            <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </div>
                                            <input type="text" class="form-control radius-30 ps-5" id="2fa_code" name="2fa_code" autofocus required>
                                        </div>
                                        <p class="text-danger">
                                            @error('username')
                                                {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success radius-30">{{__('Submit')}}</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-grid">
                                            <button type="submit" onclick="window.location.href='#'" class="btn btn-primary radius-30">{{__('Forget?')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-12 mt-3">
                                <div class="d-grid">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger radius-30 col-12">{{__('Logout')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout-auth>
