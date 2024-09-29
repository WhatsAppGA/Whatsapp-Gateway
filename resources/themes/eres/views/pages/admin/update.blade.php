<x-layout-dashboard title="{{__('Update Version')}}">
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
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">{{__('Update')}}</h5>
				</div>
				<div class="container mt-3">
				 @if(session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
					@if($updateAvailable)
					<div class="alert alert-info">
						{{__('A new version is available:')}} <span class="text-danger">v{{ $newVersion }}</span><br />
						{!!__('<span class="text-danger">Note: Turn off <span class="text-primary">Nodejs</span> before continuing with the update, after the update is complete you can turn it back on</span>')!!}
						@if($whatsNew)
							<br />{!! $whatsNew !!}
						@endif
					</div>
					<form method="POST" action="{{ route('update.install') }}">
						@csrf
						@if($serverProtocol == 'https')
						<div class="alert alert-danger">
							{!!__('<span class="text-danger">You are using SSL in the <span class="text-primary">server.js</span> file, but don\'t worry, <span class="text-primary">Smart Update</span> will update and run your site with SSL, just click update</span>')!!}<br />
							@if($updateSSL)
							<input type="hidden" name="ssl" value="ssl" />
							@endif
						</div>
						@endif
						@if($before)
							<input type="hidden" name="before" value="1" />
						@endif
						@if($after)
							<input type="hidden" name="after" value="1" />
						@endif
						<input type="hidden" name="version" value="{{ $newVersion }}" />
						<button type="submit" class="btn btn-primary mb-3">{{__('Update')}}</button>
					</form>
					@else
					<div class="alert alert-success">
						{{__('You are using the latest version.')}}
					</div>
					@endif
				</div>
			</div>
		</div>
</div>
</x-layout-dashboard>