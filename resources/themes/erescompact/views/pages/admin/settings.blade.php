<x-layout-dashboard title="{{__('Settings Server')}}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
    @if (session()->has('alert'))
        <x-alert>
            @slot('type', session('alert')['type'])
            @slot('msg', session('alert')['msg'])
        </x-alert>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="server" role="tabpanel" aria-labelledby="account-tab">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                <div class="row m-t-lg">
                                    <form action="{{ route('setServer') }}" method="POST">
                                        @csrf
                                        <div class="col-md-12">
                                            <label for="typeServer" class="form-label">{{__('Server Type')}}</label>
                                            <select name="typeServer" class="form-control" id="server" required>

                                                @if (env('TYPE_SERVER') === 'localhost')
                                                    <option value="localhost" selected>{{__('Localhost')}}</option>
                                                    <option value="hosting">{{__('Hosting Shared')}}</option>
                                                    <option value="other">{{__('Other')}}</option>
                                                @elseif(env('TYPE_SERVER') === 'hosting')
                                                    <option value="localhost">{{__('Localhost')}}</option>
                                                    <option value="hosting" selected>{{__('Hosting Shared')}}</option>
                                                    <option value="other">{{__('Other')}}</option>
                                                @else
                                                    <option value="other" required>{{__('Other')}}</option>
                                                    <option value="localhost">{{__('Localhost')}}</option>
                                                    <option value="hosting">{{__('Hosting Shared')}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="Port" class="form-label">{{__('Port Node JS')}}</label>
                                            <input type="number" name="portnode" class="form-control" id="Port"
                                                value="{{ env('PORT_NODE') }}" required>
                                        </div>
                                </div>
                                <div class="row m-t-lg {{ env('TYPE_SERVER') === 'other' ? 'd-block' : 'd-none' }} formUrlNode">
                                    <div class="col-md-12">
                                        <label for="settingsInputUserName " class="form-label">{{__('URL Node')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="settingsInputUserName-add">{{__('URL')}}</span>
                                            <input type="text" class="form-control"
                                                value="{{ env('WA_URL_SERVER') }}" name="urlnode"
                                                id="settingsInputUserName" aria-describedby="settingsInputUserName-add">
                                        </div>
                                    </div>

                                </div>
                                <div class="row m-t-lg ">
                                    <div class="col mt-4">

                                        <button type="submit" class="btn btn-primary btn-sm">{{__('Update')}}</button>
                                    </div>
                                </div>
                                </div>
								<div class="col-md-6 mt-3 p-2 border rounded d-flex align-items-center justify-content-center flex-column">
									<div><h4>{{ __('Port (:port) Is', ['port' => $port]) }} {{ $isConnected ? __("Connected") : __("Disconnected") }}</h4></div>
									<div><h1>{{ $isConnected ? '✅' : '❌' }}</h1></div>
									<div><h4>{!! $protocolMatch !!}</h4></div>
								</div>
                                </form>
                            </div>
                        </div>
                    </div>
					<div class="card">
                        <div class="card-body">
                            <div class="row">
								<div class="col-md-12">
									<h5 class="text-center">{{__('Generate SSL For Your NodeJS')}}</h5>
									<div class="text-center">
										<form action="{{ route('generateSsl') }}" method="POST">
											@csrf
											<div class="row">
												<div class="col-md-6">
													<label for="settingsInputUserName " class="form-label">{{__('Domain')}}</label>
													<input type="text" name="domain" class="form-control" id="domain" value="{{$host}}" required readonly
													@if ($host === 'localhost')
														disabled
													@endif
													>
												</div>
												<div class="col-md-6">
													<label for="settingsInputUserName " class="form-label">{{__('Email')}}</label>
													<input type="email" name="email" class="form-control" id="email" value="" required
													@if ($host === 'localhost')
														readonly disabled
													@endif
													>
												</div>
											</div>
											@if ($host === 'localhost')
											<button type="submit" class="btn btn-danger mt-3" disabled><i class="fa-solid fa-lock me-2"></i>{{__("You Can't Generate SSL For Localhost")}}</button>
											@else
											<button type="submit" class="btn btn-primary btn-sm mt-3">{{__('Generate SSL Certificate')}}</button>
											@endif
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('setEnvAll') }}">
		@csrf
            <div class="row">
				<h5 class="text-center mb-4">{{__('Env file Settings')}}</h5>
                @foreach ($allEnv as $key => $value)
                @if (!in_array($key, [
                    'APP_KEY', 
                    'APP_URL',
					'THEME_NAME',
                    'PORT_NODE', 
                    'WA_URL_SERVER', 
                    'LICENSE_KEY', 
                    'APP_INSTALLED', 
                    'TYPE_SERVER', 
                    'DB_CONNECTION', 
                    'LOG_DEPRECATIONS_CHANNEL', 
                    'REDIS_PASSWORD', 
                    'REDIS_HOST', 
                    'REDIS_PORT', 
                    'MIX_PUSHER_APP_KEY', 
                    'MIX_PUSHER_APP_CLUSTER', 
                    'AUTH', 
                    'PORT', 
                    'WEBHOOK',
					'MEMCACHED_HOST',
					'ORIGIN',
					'LOG_CHANNEL'
                ]))
                <div class="col-md-6 mb-4">
                    <div class="input-group">
                        <span class="input-group-text col-4">{{ $key }}</span>
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ $value }}">
                    </div>
                    <small class="form-text text-muted">
                        @switch($key)
                            @case('APP_NAME')
								{{__('The name of the application, shown in page titles and notifications.')}}
                                @break
                            @case('APP_ENV')
                                {{__('The environment of the application (e.g., local for development, production for live use).')}}
                                @break
                            @case('APP_DEBUG')
                                {{__('Enables or disables debugging mode.')}}
                                @break
                            @case('BUYER_EMAIL')
                                {{__('The email of the buyer or license holder.')}}
                                @break
                            @case('DB_HOST')
                                {{__('The host address of the database.')}}
                                @break
                            @case('DB_PORT')
                                {{__('The port used to connect to the database.')}}
                                @break
                            @case('DB_DATABASE')
                                {{__('The name of the database.')}}
                                @break
                            @case('DB_USERNAME')
                                {{__('The username for the database connection.')}}
                                @break
                            @case('DB_PASSWORD')
                                {{__('The password for the database connection.')}}
                                @break
                            @case('LOG_CHANNEL')
                                {{__('The channel used for logging.')}}
                                @break
                            @case('LOG_LEVEL')
                                {{__('The level of logs to record (e.g., debug, error).')}}
                                @break
                            @case('BROADCAST_DRIVER')
                                {{__('The driver used for broadcasting events.')}}
                                @break
                            @case('CACHE_DRIVER')
                                {{__('The driver used for caching.')}}
                                @break
                            @case('FILESYSTEM_DRIVER')
                                {{__('The driver used for the file system (e.g., local, s3).')}}
                                @break
                            @case('QUEUE_CONNECTION')
                                {{__('The connection used for job queues.')}}
                                @break
                            @case('SESSION_DRIVER')
                                {{__('The driver used for session management.')}}
                                @break
                            @case('SESSION_LIFETIME')
                                {{__('The lifetime of a session, in minutes.')}}
                                @break
                            @case('CHATGPT_URL')
                                {{__('The URL for the ChatGPT API.')}}
                                @break
                            @case('CHATGPT_MODEL')
                                {{__('The model used in ChatGPT (e.g., gpt-3.5-turbo).')}}
                                @break
                            @case('GEMINI_URL')
                                {{__('The URL for the Gemini API.')}}
                                @break
                            @case('CLAUDE_URL')
                                {{__('The URL for the Claude API.')}}
                                @break
                            @case('CLAUDE_MODEL')
                                {{__('The model used in Claude.')}}
                                @break
                            @case('MAIL_MAILER')
                                {{__('The driver used for sending emails (e.g., smtp).')}}
                                @break
                            @case('MAIL_HOST')
                                {{__('The host address for the email service.')}}
                                @break
                            @case('MAIL_PORT')
                                {{__('The port used for the email service.')}}
                                @break
                            @case('MAIL_USERNAME')
                                {{__('The username for the email service.')}}
                                @break
                            @case('MAIL_PASSWORD')
                                {{__('The password for the email service.')}}
                                @break
                            @case('MAIL_ENCRYPTION')
                                {{__('The encryption type used for emails (e.g., tls).')}}
                                @break
                            @case('MAIL_FROM_ADDRESS')
                                {{__('The default sender email address.')}}
                                @break
                            @case('MAIL_FROM_NAME')
                                {{__('The default sender name.')}}
                                @break
                            @default
                                {{__('No description available for this key.')}}
                        @endswitch
                    </small>
                </div>
                @endif
                @endforeach
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">{{__('Edit')}}</button>
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

    <script>
	window.onload = function() {
		$(document).ready(function() {
			$('#server').on('change', function() {
				let type = $('#server :selected').val();
				console.log(type);
				if (type === 'other') {
					$('.formUrlNode').removeClass('d-none')
				} else {
					$('.formUrlNode').addClass('d-none')

				}
			});
		});
	};
    </script>
</x-layout-dashboard>
