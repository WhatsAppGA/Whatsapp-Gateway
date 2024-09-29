<x-layout-dashboard title="{{__('Messages History')}}">
	@if (session()->has('alert'))
	<x-alert>
		@slot('type', session('alert')['type'])
		@slot('msg', session('alert')['msg'])
	</x-alert>
	@endif
	<!--breadcrumb-->
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">{{__('Messages')}}</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">{{__('Messages History')}}</li>
				</ol>
			</nav>
		</div>
	</div>
	{{-- end breadcrumb --}}
	{{-- table --}}
	<div class="row">
		<div class="col-12 col-lg-12 d-flex">
			<div class="card w-100">
				<div class="card-header py-3">
					<div class="row g-3">
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table align-middle">
							<thead class="table-light">
								<tr>
									<th>{{__('ID')}}</th>
									<th>{{__('Sender')}}</th>
									<th>{{__('Number')}}</th>
									<th>{{__('Message')}}</th>
									<th>{{__('Status')}}</th>
									<th>{{__('Via')}}</th>
									<th>{{__('Last Updated')}}</th>
									<th>{{__('Action')}} </th>
								</tr>
							</thead>
							<tbody>
								@if ($messages->total() != 0)
								<tr>
									<td colspan="7" class="text-danger">{{__('Do you want to delete all your message history?')}}</td>
									<td>
										<a onclick="deleteAll({{ $userId }})" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i> {{__('Delete')}} &nbsp; </a>
									</td>
								</tr>
								@endif
								@if ($messages->total() == 0)
								<x-no-data colspan="8" text="{{__('No Messages History')}}" />
								@endif
								@foreach ($messages as $msg)
								<tr>
									<td>{{ $msg->id }}</td>
									<td>{{ $msg->device->body }}</td>
									<td>{{ $msg->number }}</td>
									<td>
										<span class="text-info">{{ $msg->type }} </span>:
										{{ substr($msg->message, 0, 20) }}
										{{ strlen($msg->message) > 20 ? '...' : '' }}
									<td>
										@if ($msg->status == 'success')
										<span class="badge rounded-pill bg-success">{{__('Sent')}}</span>
										@else
										<span class="badge rounded-pill bg-danger">{{__('Failed')}}</span>
										@endif
									</td>
									<td>
										@if ($msg->send_by == 'web')
										<span class="badge rounded-pill bg-primary">{{__('Web')}}</span>
										@else
										<span class="badge rounded-pill bg-warning">{{__('API')}}</span>
										@endif
									</td>
									<td>{{ date('d M Y', strtotime($msg->updated_at)) }}</td>
									<td>
										<a onclick="resend({{ $msg->id }}, '{{ $msg->status }}')"
											class="btn btn-sm btn-primary">
										<i class="bx bx-refresh"></i> {{__('Resend')}}
										</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<nav aria-label="Page navigation example text-center">
						<ul class="pagination justify-content-center">
							<li class="page-item {{ $messages->currentPage() == 1 ? 'disabled' : '' }}">
								<a class="page-link" href="{{ $messages->previousPageUrl() }}">{{__('Previous')}}</a>
							</li>

							@if($messages->currentPage() > 3)
								<li class="page-item">
									<a class="page-link" href="{{ $messages->url(1) }}">1</a>
								</li>
								@if($messages->currentPage() > 4)
									<li class="page-item disabled">
										<span class="page-link">...</span>
									</li>
								@endif
							@endif

							@for ($i = max(1, $messages->currentPage() - 2); $i <= min($messages->lastPage(), $messages->currentPage() + 2); $i++)
								<li class="page-item {{ $messages->currentPage() == $i ? 'active' : '' }}">
									<a class="page-link" href="{{ $messages->url($i) }}">{{ $i }}</a>
								</li>
							@endfor

							@if($messages->currentPage() < $messages->lastPage() - 2)
								@if($messages->currentPage() < $messages->lastPage() - 3)
									<li class="page-item disabled">
										<span class="page-link">...</span>
									</li>
								@endif
								<li class="page-item">
									<a class="page-link" href="{{ $messages->url($messages->lastPage()) }}">{{ $messages->lastPage() }}</a>
								</li>
							@endif

							<li class="page-item {{ $messages->currentPage() == $messages->lastPage() ? 'disabled' : '' }}">
								<a class="page-link" href="{{ $messages->nextPageUrl() }}">{{__('Next')}}</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	{{-- end table --}}
	<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="confirmDeleteModalLabel">{{__('Confirm Deletion')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					{{__('Are you sure you want to delete all message history?')}}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
					<button type="button" id="confirmDeleteButton" class="btn btn-danger">{{__('Delete All')}}</button>
				</div>
			</div>
		</div>
	</div>
</x-layout-dashboard>
<script>
function resend(id, status) {
	if (status == 'success') {
		toastr.info('{{__("Message already sent")}}');
		return;
	}

	$.ajax({
		url: '/resend-message',
		type: 'POST',
		data: {
			id: id,
			_token: '{{ csrf_token() }}'
		},
		success: function (res) {
			if (res.error) {
				toastr.error(res.msg);
				return;
			} else {
				toastr.success(res.msg);
				return;
			}
		},
		error: function (err) {
			toastr.error('{{__("Something went wrong")}}');
		}
	});
}
let deleteAllId = null;

function deleteAll(id) {
	deleteAllId = id;

	const myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
	myModal.show();
}

document.getElementById('confirmDeleteButton').addEventListener('click', function () {
	$.ajax({
		url: '/delete-messages',
		type: 'POST',
		data: {
			id: deleteAllId,
			_token: '{{ csrf_token() }}'
		},
		success: function (res) {
			if (res.error) {
				toastr.error(res.msg);
			} else {
				toastr.success(res.msg);
				setTimeout(function () {
					location.reload();
				}, 1500);
			}
		},
		error: function (err) {
			toastr.error('{{__("Something went wrong")}}');
		},
		complete: function () {
			const myModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
			myModal.hide();
		}
	});
});
</script>