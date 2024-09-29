<x-layout-dashboard title="{{__('Themes Manager')}}">
	<!--breadcrumb-->
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">{{__('Admin')}}</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">{{__('Themes Manager')}}</li>
				</ol>
			</nav>
		</div>
	</div>
	<!--end breadcrumb-->
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
	<div class="row mt-4">
		<div class="col">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h5 class="card-title">{{__('Installed Themes')}}</h5>
				</div>
				<div class="container mt-3">
				 @if(session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
					<div class="themes">
						<!-- Installed Themes -->
						<div class="row mb-5">
							@foreach($themes as $theme)
								<div class="col-md-4 mb-4">
									<div class="card shadow-sm h-100 border">
										<img src="{{ $theme['screenshot'] }}" class="card-img-top theme-img cursor-pointer" alt="{{ $theme['name'] }}" data-bs-toggle="modal" data-bs-target="#themeModal" data-img="{{ $theme['screenshot'] }}">
										<div class="card-body text-center">
											<h5 class="card-title">{{ $theme['name'] }}</h5>
											<p class="card-text">{{__('Version:')}} {{ $theme['version'] }}</p>
											<p class="card-text">{{__('Author:')}} {{ $theme['author'] }}</p>
										<div class="d-flex justify-content-evenly">
										@if ($theme['folder'] != env('THEME_NAME') && $theme['folder'] != 'mpwa')
											<form method="POST" action="{{ route('themes.delete') }}" 
											onsubmit="return confirm('{{__('Are you sure will delete this theme?')}}')">
												@csrf
												<input type="hidden" name="folder" value="{{$theme['folder']}}" />
												<button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
											</form>
										@else
											<button onclick="#" class="btn btn-danger" disabled><i class="bi bi-trash"></i></button>
										@endif
										@if ($theme['website'] != '')
											<a href="{{ $theme['website'] }}" class="btn btn-primary" target="_blank">{{__('Visit')}}</a>
										@endif
										@if ($theme['folder'] == env('THEME_NAME'))
											<button onclick="#" class="btn btn-dark" disabled>{{__('Activated')}}</button>
										@else
											<button onclick="window.location.href = '{{ route('themes.active', $theme['folder']) }}'" class="btn btn-success">{{__('Activate')}}</button>
										@endif
										</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h5 class="card-title">{{__('Online Themes')}}</h5>
				</div>
				<div class="container mt-3">
					<div class="themes">
						<!-- Installed Themes -->
						<div class="row mb-5">
							@foreach($onlines as $onlone)
								<div class="col-md-4 mb-4">
									<div class="card shadow-sm h-100 border">
										<img src="{{ $onlone['screenshot'] }}" class="card-img-top theme-img cursor-pointer" alt="{{ $onlone['name'] }}" data-bs-toggle="modal" data-bs-target="#themeModal" data-img="{{ $onlone['screenshot'] }}">
										<div class="card-body text-center">
											<h5 class="card-title">{{ $onlone['name'] }}</h5>
											<p class="card-text">{{__('Version:')}} {{ $onlone['version'] }}</p>
											<p class="card-text">{{__('Author:')}} {{ $onlone['author'] }}</p>
										@if (in_array($currentVersion, $onlone['compatibility']))
											<p class="card-text">{{__('Compatibility:')}} <span class="text-success">{{__('Yes')}}</span></p>
										@else
											<p class="card-text">{{__('Compatibility:')}} <span class="text-danger">{{__('No')}}</span></p>
										@endif
										<div class="d-flex justify-content-evenly">
										@if ($onlone['demo'] == "")
											<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#themeModal" data-img="{{ $onlone['screenshot'] }}">{{__('Demo')}}</button>
										@else
											<button onclick="window.open('{{ $onlone['demo'] }}', '_blank')" class="btn btn-primary" >{{__('Demo')}}</button>
										@endif
										@if (in_array($currentVersion, $onlone['compatibility']))
											@if ($onlone['folder'] != env('THEME_NAME'))
												<form method="POST" action="{{ route('themes.download') }}">
													@csrf
													<input type="hidden" name="download" value="{{$onlone['download']}}" />
													<input type="hidden" name="folder" value="{{$onlone['folder']}}" />
													<button type="submit" class="btn btn-success">{{__('Download & Active')}}</button>
												</form>
											@else
												<button onclick="#" class="btn btn-success" disabled>{{__('Activated')}}</button>
											@endif
										@else
											<button onclick="#" class="btn btn-danger" disabled>{{__('Not compatible')}}</button>
										@endif
										</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal for Image Preview -->
	<div class="modal fade" id="themeModal" tabindex="-1" aria-labelledby="themeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="themeModalLabel">{{__('Theme Preview')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center">
					<img src="" id="theme-modal-img" class="img-fluid" alt="{{__('Theme Preview')}}">
				</div>
			</div>
		</div>
	</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var themeModal = document.getElementById('themeModal');
        themeModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var imgSrc = button.getAttribute('data-img'); // Extract info from data-img attribute
            var modalImg = themeModal.querySelector('#theme-modal-img'); // Modal image element
            modalImg.src = imgSrc; // Update the modal image src
        });
    });
</script>
</x-layout-dashboard>