<x-layout-dashboard title="{{__('Auto Replies')}}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	{{-- alert --}}
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
	{{--  --}}
	<div class="card">
		<div class="card-header">
				<h5 class="card-title">{{__('Lists auto respond')}}
					({{ Session::has('selectedDevice') ?  Session::get('selectedDevice')['device_body'] : '' }})
				</h5>
				<div class="d-flex">
					<button data-bs-toggle="modal" data-bs-target="#addAutoRespond" type="button" class="btn btn-primary btn-sm me-2">
						<i class="bx bx-plus"></i>{{__('New Auto Reply')}}
					</button>
					<form class="ms-auto position-relative">
						<button class="btn  position-absolute top-50 translate-middle-y search-icon px-4"><i
							class="bi bi-search"></i></button>
						<input value="{{ request()->has('keyword') ? request()->get('keyword') : '' }}" name="keyword"
							class="form-control ps-5 px-4" type="text" placeholder="{{__('search')}}">
					</form>
				</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-responsive-md" style="font-size: 0.755rem;">
					<thead>
						<tr>
							<th>{{__('Keyword')}}</th>
							<th>{{__('Details')}}</th>
							<th>{{__('Status')}}</th>
							<th>{{__('Read')}}</th>
							<th>{{__('Typing')}}</th>
							<th>{{__('Quoted')}}</th>
							<th>{{__('Delay')}}</th>
							<th>{{__('Type')}}</th>
							<th>{{__('Action')}}</th>
						</tr>
					</thead>
					<tbody>
						@if (Session::has('selectedDevice'))
						@if ($autoreplies->total() == 0)
						<x-no-data colspan="9" text="{{__('No Autoreplies added yet')}}" />
						@endif
						@foreach ($autoreplies as $autoreply)
						<tr>
							<td>
								<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-control keyword-update" data-id="{{ $autoreply->id }}"
									type="text" name="id" value="{{ $autoreply->keyword }}" >
							</td>
							<td class="py-1">
								<span class="badge bg-success w-100">{{ __($autoreply['type_keyword']) }}</span><br />
								<span class="badge bg-warning w-100">{{ __($autoreply['reply_when']) }}</span>
							</td>
							<td>
								<div class="form-check form-switch">
									<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-check-input toggle-status" type="checkbox"
									data-id="{{ $autoreply->id }}"
									{{ $autoreply->status == 'active' ? 'checked' : '' }}>
									<label class="form-check-label"
										for="toggle-switch">{{ __($autoreply->status) }}</label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-check-input toggle-read" type="checkbox"
									data-id="{{ $autoreply->id }}"
									{{ $autoreply->is_read ? 'checked' : '' }}>
									<label class="form-check-label"
										for="toggle-switch">{{ $autoreply->is_read ? __('Yes') : __('No') }}</label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-check-input toggle-typing" type="checkbox"
									data-id="{{ $autoreply->id }}"
									{{ $autoreply->is_typing ? 'checked' : '' }}>
									<label class="form-check-label"
										for="toggle-switch">{{ $autoreply->is_typing ? __('Yes') : __('No') }}</label>
								</div>
							</td>
							<td>
								<div class="form-check form-switch">
									<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-check-input toggle-quoted" type="checkbox"
									data-id="{{ $autoreply->id }}"
									{{ $autoreply->is_quoted ? 'checked' : '' }}>
									<label class="form-check-label"
										for="toggle-switch">{{ $autoreply->is_quoted ? __('Yes') : __('No') }}</label>
								</div>
							</td>
							<td style="width: 10%;">
								<input data-url="{{ route('autoreply.update', $autoreply->id) }}"
									class="form-control delay-update" data-id="{{ $autoreply->id }}"
									type="text" name="delay" value="{{ $autoreply->delay }}" >
							</td>
							<td>{{ __($autoreply['type']) }}</td>
							<td>
								<div class="d-flex">
									<a onclick="viewReply({{ $autoreply->id }})" href="javascript:;"
										class="badge badge-info shadow btn-xs sharp me-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
										title="{{__('Views')}}"><i class="fa fa-eye"></i></a>
									<a href="{{ route('autoreply.edit', ['id' => $autoreply->id]) }}"
										class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
										title="{{__('Edit')}}"><i class="fa fa-pencil"></i></a>
									<form action="{{ route('autoreply.delete') }}" method="POST">
									@method('delete')
									@csrf
									<input type="hidden" name="id" value="{{ $autoreply->id }}">
									<button type="submit" name="delete"
										class="btn btn-danger shadow btn-xs sharp"><i
										class="fa fa-trash"></i></button>
									</form>
								</div>
							</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="4">{{__('Please select device')}}</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-center">
				<nav aria-label="Page navigation example">
					<ul class="pagination">
						<li class="page-item {{ $autoreplies->currentPage() == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $autoreplies->previousPageUrl() }}">{{__('Previous')}}</a>
						</li>
						@for ($i = 1; $i <= $autoreplies->lastPage(); $i++)
						<li class="page-item {{ $autoreplies->currentPage() == $i ? 'active' : '' }}">
							<a class="page-link" href="{{ $autoreplies->url($i) }}">{{ $i }}</a>
						</li>
						@endfor
						<li class="page-item {{ $autoreplies->currentPage() == $autoreplies->lastPage() ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $autoreplies->nextPageUrl() }}">{{__('Next')}}</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="addAutoRespond" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{__('Add Auto Reply')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form action="" method="POST" enctype="multipart/form-data" id="formautoreply">
						@csrf
						@if (Session::has('selectedDevice'))
						<input type="hidden" name="device" value="{{ Session::get('selectedDevice')['device_id'] }}">
						<input type="hidden" name="device_body" id="device" class="form-control" value="{{ Session::get('selectedDevice')['device_body'] }}" readonly>
						@else
						<input type="text" name="devicee" id="device" class="form-control" value="{{__('Please select device')}}" readonly>
						@endif
						<div class="mb-3">
							<label class="form-label">{{__('Type Keyword')}}</label>
							<div class="d-flex">
								<div class="form-check me-3">
									<input type="radio" value="Equal" name="type_keyword" checked class="form-check-input" id="keywordTypeEqual">
									<label class="form-check-label" for="keywordTypeEqual">{{__('Equal')}}</label>
								</div>
								<div class="form-check">
									<input type="radio" value="Contain" name="type_keyword" class="form-check-input" id="keywordTypeContain">
									<label class="form-check-label" for="keywordTypeContain">{{__('Contains')}}</label>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label">{{__('Only reply when sender is')}}</label>
							<div class="d-flex">
								<div class="form-check me-3">
									<input type="radio" value="Group" name="reply_when" class="form-check-input" id="replyWhenGroup">
									<label class="form-check-label" for="replyWhenGroup">{{__('Group')}}</label>
								</div>
								<div class="form-check me-3">
									<input type="radio" value="Personal" name="reply_when" class="form-check-input" id="replyWhenPersonal">
									<label class="form-check-label" for="replyWhenPersonal">{{__('Personal')}}</label>
								</div>
								<div class="form-check">
									<input type="radio" value="All" checked name="reply_when" class="form-check-input" id="replyWhenAll">
									<label class="form-check-label" for="replyWhenAll">{{__('All')}}</label>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="keyword" class="form-label">{{__('Keyword')}}</label>
							<input type="text" name="keyword" class="form-control" id="keyword" required>
						</div>
						<div class="mb-3">
							<label for="type" class="form-label">{{__('Type Reply')}}</label>
							<select name="type" id="type" class="js-states form-control" tabindex="-1" required>
								<option selected disabled>{{__('Select One')}}</option>
								<option value="text">{{__('Text Message')}}</option>
								<option value="media">{{__('Media Message')}}</option>
								<option value="location">{{__('Location Message')}}</option>
								<option value="sticker">{{__('Sticker Message')}}</option>
								<option value="vcard">{{__('VCard Message')}}</option>
								<option value="list">{{__('List Message')}}</option>
								<option value="button">{{__('Button Message (Deprecated)')}}</option>
								<option value="template">{{__('Template Message (Deprecated)')}}</option>
							</select>
						</div>
						<div class="ajaxplace"></div>
						<div id="loadjs"></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
							<button type="submit" name="submit" class="btn btn-primary">{{__('Add')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{__('Auto Reply Preview')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body showReply">
				</div>
			</div>
		</div>
	</div>
	<!--  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css" />
	<script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"></script>
	<script src="https://woody180.github.io/vanilla-javascript-emoji-picker/vanillaEmojiPicker.js"></script>
	<script src="{{asset('js/autoreply.js')}}"></script>
	<script>
		function loadScript(url) {
			  var script = document.createElement('script');
			  script.src = url;
			  document.getElementById("loadjs").appendChild(script); 
		}
		window.addEventListener('load', function() {
			$(document).ready(function() {
			$('#type').on('change', () => {
				const type = $('#type').val();
				$.ajax({
					url: `/form-message/${type}`,
					
					type: "GET",
					dataType: "html",
					success: (result) => {
						document.getElementById('loadjs').innerHTML = '';
						$(".ajaxplace").html(result);
						loadScript('{{asset("js/text.js")}}');
						loadScript('{{asset("vendor/laravel-filemanager/js/stand-alone-button2.js")}}');
					},
					error: (error) => {
						console.log(error);
					},
				});
			});
			});
		});
		function viewReply(id) {
			$.ajax({
				url: `/preview-message`,
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				type: "POST",
				data: {
					id: id,
					table: "autoreplies",
					column: "reply",
				},
				dataType: "html",
				success: (result) => {
					$(".showReply").html(result);
					$("#modalView").modal("show");
				},
				error: (error) => {
					console.log(error);
				},
			});
			// 
		}
	</script>
		</div>
	</div>
</x-layout-dashboard>