<x-layout-dashboard title="{{__('Setting')}}">
    <div class="app-content">
        <div class="content-wrapper">
            <div class="container">



                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">{{__('User')}}</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->

                <div class="row">
                    @if (session()->has('alert'))
                        <x-alert>
                            @slot('type', session('alert')['type'])
                            @slot('msg', session('alert')['msg'])
                        </x-alert>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h5 class="">{{__('Settings')}}</h5>
                            <div class="row mx-auto border p-4 rounded">
                                    <form action="{{ route('generateNewApiKey') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">{{__('API Key')}}</span>

                                            <input type="text" class="form-control"
                                                value="{{ Auth::user()->api_key }}" aria-label="Username"
                                                aria-describedby="basic-addon1" readonly>
                                            <button type="submit" name="api_key" class="btn btn-primary">{{__('Generate New')}}</button>
                                        </div>
                                    </form>
                            </div>
                            <div class="row mt-4">
								<div class="col-md-6 mb-4"> 
									<div class="border rounded p-3"> 
										<form action="{{ route('changePassword') }}" method="POST">
											@csrf

											<div class="form-group mb-3"> 
												<label for="settingsCurrentPassword" class="form-label">{{__('Current Password')}}</label>
												<input type="password" name="current"
													class="form-control {{ $errors->has('current') ? 'is-invalid' : '' }} "
													aria-describedby="settingsCurrentPassword"
													placeholder="●●●●●●●●">
												@if ($errors->has('current'))
													<div class="invalid-feedback">
														{{ $errors->first('current') }}
													</div>
												@endif
											</div>

											<div class="form-group mb-3"> 
												<label for="password" class="form-label">{{__('New Password')}}</label>
												<input type="password" name="password"
													class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
													aria-describedby="password"
													placeholder="●●●●●●●●">
												@if ($errors->has('password'))
													<div class="invalid-feedback">
														{{ $errors->first('password') }}
													</div>
												@endif
											</div>

											<div class="form-group mb-3"> 
												<label for="settingsConfirmPassword" class="form-label">{{__('Confirm Password')}}</label>
												<input type="password" name="password_confirmation" class="form-control"
													aria-describedby="settingsConfirmPassword"
													placeholder="●●●●●●●●">
											</div>

											<div class="row m-t-lg mt-3">
												<div class="col">
													<button type="submit" class="btn btn-info text-white m-t-sm">{{__('Change Password')}}</button>
												</div>
											</div>
										</form>
									</div> 
								</div>

								<div class="col-md-6"> 
									<div class="border rounded p-3"> 
										<form method="POST" action="{{ route('user.settings.2fa') }}">
											@csrf
											<div class="row m-t-lg mt-3">
												<div class="col">
													@if (auth()->user()->two_factor_enabled)
														<button type="submit" name="action" class="btn btn-danger text-white m-t-sm col-12" value="disable">{{__('Disable Authenticator 2FA?')}}</button>
													@else
														<button type="submit" name="action" class="btn btn-info text-white m-t-sm col-12" value="enable">{{__('Enable Authenticator 2FA?')}}</button>
													@endif
												</div>
											</div>
										</form>

										@if (auth()->user()->two_factor_enabled)
											<div class="row col-md-12 mx-auto mt-3 border text-center p-2"> 
												<h4>{{__('Recovery Codes')}}</h4>
												<p>{{__('You can use Recovery Codes if you accidentally delete the Google Authenticator app or lose your phone. Use these codes when logging in instead of the app')}}</p>
												<div class="col-12">
													<div class="row">
														@foreach(json_decode(auth()->user()->recovery_codes) as $code)
															<div class="col-3">{{ $code }}</div>
														@endforeach
													</div>
												</div>
											</div>
										@endif
									</div> 
									<div class="border rounded p-3 mt-3"> 
										<form method="POST" action="{{ route('deleteHistory') }}">
											@csrf
											<label for="delete_history" class="form-label">{{__('Automatically delete message history:')}}</label>
											<div class="d-flex justify-content-between align-items-center">
												<div class="d-flex align-items-center flex-grow-1">
													<select name="delete_history" class="form-control">
														<option value="0" 
														@if (auth()->user()->delete_history == 0)
														selected
														@endif
														>{{ __("Don't Delete") }}</option>
														
														@foreach (range(1, 30) as $number)
															@if ($number == auth()->user()->delete_history)
																<option value="{{ $number }}" selected>{{ $number }}</option>
															@else
																<option value="{{ $number }}">{{ $number }}</option>
															@endif
														@endforeach
													</select>
													<span class="text-nowrap ms-2 me-2">{{__('In Days')}}</span>
												</div>
												<div>
													<button type="submit" class="btn btn-info text-white m-t-sm">{{__('Save')}}</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-layout-dashboard>
