<x-layout-dashboard title="{{__('Home')}}">

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
				@if ($newVersion)
                    <div class="alert alert-danger">
                        {{__('A new version is available:')}} <span class="text-danger">v{{ $newVersion }}</span> <a href="{{ route('update') }}">{{__('Click Here')}}</a></ul>
                    </div>
                @endif
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 g-3">
				<div class="col">
					<div class="card rounded-4 h-100 overflow-hidden">
						<div class="card-body">
							<div class="d-flex align-items-center position-relative">
								<i class="fa-brands fa-whatsapp position-absolute top-50 start-100 translate-middle text-success display-1" style="opacity: 0.1;font-size: 170px;"></i> 
								<div class="me-auto position-relative"> 
									<p class="mb-1">{{__('Total Devices')}}</p>
									<h4 class="mb-0">{{ $user->devices_count }}</h4>
									<p class="mb-0 mt-2 font-13">
										<span>{{__('Your limit device :')}} {{ $user->limit_device }}</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card rounded-4 h-100 overflow-hidden">
						<div class="card-body">
							<div class="d-flex align-items-center position-relative">
								<i class="fa-regular fa-comments position-absolute top-50 start-100 translate-middle text-warning display-1" style="opacity: 0.1;font-size: 170px;"></i> 
								<div class="me-auto position-relative">
									<p class="mb-1">{{__('Blast/Bulk')}}</p>
									<p class="mb-0 badge bg-warning">{{ $user->blasts_pending }} {{__('Wait')}} </p>
									<p class="mb-0 badge bg-success">{{ $user->blasts_success }} {{__('Sent')}} </p>
									<p class="mb-0 badge bg-danger">{{ $user->blasts_failed }} {{__('Fail')}} </p>
									<p class="mb-0 mt-2 font-13">
										{{ __('From :count Campaigns', ['count' => $user->campaigns_count]) }}
									</p>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col">
					<div class="card rounded-4 h-100 overflow-hidden">
						<div class="card-body">
							<div class="d-flex align-items-center position-relative">
								<i class="fa-regular fa-handshake position-absolute top-50 start-100 translate-middle text-Info display-1" style="opacity: 0.1;font-size: 170px;"></i>
								<div class="me-auto position-relative">
									<p class="mb-1">{{__('Subscription Status')}}</p>
									<h4 class="mb-0">{{ $user->subscription_status }}</h4>
									<p class="mb-0 mt-2 font-13">
										<span>{{__('Expired :')}} {{ __($user->expired_subscription_status) }}</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card rounded-4 h-100 overflow-hidden">
						<div class="card-body">
							<div class="d-flex align-items-center position-relative">
								<i class="fa-regular fa-envelope position-absolute top-50 start-100 translate-middle text-warning display-1" style="opacity: 0.1;font-size: 170px;"></i>
								<div class="me-auto position-relative">
									<p class="mb-1">{{__('All Messages Sent')}}</p>
									<h4 class="mb-0">{{ $user->message_histories_count }}</h4>
									<p class="mb-0 mt-2 font-13">
										<span>{{__('From messages histories')}}</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

                <!--end row-->
                <div class="card mt-4">
					<div class="card-header">
                        <h5 class="card-title">{{__('Whatsapp Account')}}</h5>
						<form class="ms-auto position-relative">
							<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDevice"><i class="bi bi-plus"></i> {{__('Add Device')}}</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md" style="font-size: 0.755rem;">
                                <thead>
                                    <th>{{__('Number')}}</th>
                                    <th class="text-nowrap">{{__('Webhook URL')}}</th>
									<th>{{__('Read')}}</th>
									<th class="text-nowrap">{{__('Reject Call')}}</th>
									<th>{{__('Available')}}</th>
									<th>{{__('Typing')}}</th>
									<th>{{__('Delay')}}</th>
                                    <th>{{__('Sent')}}</th>
                                    <th>{{__('status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @if ($numbers->total() == 0)
                                        <x-no-data colspan="9" text="No Device added yet" />
                                    @endif
                                    @foreach ($numbers as $number)
                                        <tr>

                                            <td>{{ $number['body'] }}</td>
                                            <td>
                                                <form action="" method="post">
                                                    @csrf
                                                    <input type="text"
                                                        class="form-control form-control-solid-bordered webhook-url-form"
                                                        data-id="{{ $number['body'] }}" name=""
                                                        value="{{ $number['webhook'] }}" id="">
                                                </form>
                                            </td>
											<td>
												<div class="form-check form-switch">
													<input data-url="{{ route('setHookRead') }}"
														class="form-check-input toggle-read" type="checkbox"
														data-id="{{ $number['body'] }}"
														{{ $number['webhook_read'] ? 'checked' : '' }} />
													<label class="form-check-label"
														for="toggle-switch">{{ $number['webhook_read'] ? 'Yes' : 'No' }}</label>
												</div>
											</td>
											<td>
												<div class="form-check form-switch">
													<input data-url="{{ route('setHookReject') }}"
														class="form-check-input toggle-reject" type="checkbox"
														data-id="{{ $number['body'] }}"
														{{ $number['webhook_reject_call'] ? 'checked' : '' }} />
													<label class="form-check-label"
														for="toggle-switch">{{ $number['webhook_reject_call'] ? 'Yes' : 'No' }}</label>
												</div>
											</td>
											<td>
												<div class="form-check form-switch">
													<input data-url="{{ route('setAvailable') }}"
														class="form-check-input toggle-available" type="checkbox"
														data-id="{{ $number['body'] }}"
														{{ $number['set_available'] ? 'checked' : '' }} />
													<label class="form-check-label"
														for="toggle-switch">{{ $number['set_available'] ? 'Yes' : 'No' }}</label>
												</div>
											</td>
											<td>
												<div class="form-check form-switch">
													<input data-url="{{ route('setHookTyping') }}"
														class="form-check-input toggle-typing" type="checkbox"
														data-id="{{ $number['body'] }}"
														{{ $number['webhook_typing'] ? 'checked' : '' }} />
													<label class="form-check-label"
														for="toggle-switch">{{ $number['webhook_typing'] ? 'Yes' : 'No' }}</label>
												</div>
											</td>
											<td style="width: 10%">
                                                <form action="" method="post">
                                                    @csrf
                                                    <input type="text"
                                                        class="form-control form-control-solid-bordered delay-url-form"
                                                        data-id="{{ $number['body'] }}" name=""
                                                        value="{{ $number['delay'] }}" id="">
                                                </form>
                                            </td>
                                            <td>{{ $number['message_sent'] }}</td>
                                            <td><span
                                                    class="badge bg-{{ $number['status'] == 'Connected' ? 'success' : 'danger' }}"> </span>
                                            </td>
                                            <td>
												<div class="dropdown position-static">
													<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
														{{ __('Action') }}
													</button>
													<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
														<li>
															<a href="{{ route('connect-via-code', $number->body) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Connect Via Code') }}">
																<i class="fa-solid fa-mobile-screen-button me-2"></i> {{ __('Connect Via Code') }}
															</a>
														</li>
														<li>
															<a href="{{ route('scan', $number->body) }}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('connect Via QR') }}">
																<i class="fa-solid fa-qrcode me-2"></i> {{ __('connect Via QR') }}
															</a>
														</li>
														<li>
															<hr class="dropdown-divider">
														</li>
														<li>
															<form action="{{ route('deleteDevice') }}" method="POST">
																@method('delete')
																@csrf
																<input name="deviceId" type="hidden" value="{{ $number['id'] }}">
																<button type="submit" name="delete" class="dropdown-item text-danger">
																	<i class="fa fa-trash me-2"></i> {{ __('Delete') }}
																</button>
															</form>
														</li>
													</ul>
												</div>
											</td>
                                        </tr>
                                    @endforeach


                                </tbody>

                            </table>
                        </div>

                        <div class="d-flex justify-content-center"> 
						  <nav aria-label="Page navigation example">
							<ul class="pagination">
							  <li class="page-item {{ $numbers->currentPage() == 1 ? 'disabled' : '' }}">
								<a class="page-link" href="{{ $numbers->previousPageUrl() }}">{{__('Previous')}}</a>
							  </li>

							  @for ($i = 1; $i <= $numbers->lastPage(); $i++)
								<li class="page-item {{ $numbers->currentPage() == $i ? 'active' : '' }}">
								  <a class="page-link" href="{{ $numbers->url($i) }}">{{ $i }}</a>
								</li>
							  @endfor

							  <li class="page-item {{ $numbers->currentPage() == $numbers->lastPage() ? 'disabled' : '' }}">
								<a class="page-link" href="{{ $numbers->nextPageUrl() }}">{{__('Next')}}</a>
							  </li>
							</ul>
						  </nav>
						</div>
                    </div>
                </div>


            </div>
        </div>





    <div class="modal fade" id="addDevice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Add Device')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addDevice') }}" method="POST">
                        @csrf
                        <label for="sender" class="form-label">{{__('Number')}}</label>
                        <input type="number" name="sender" class="form-control" id="nomor" required>
                        <p class="text-small text-danger">*{{__('Use Country Code ( without + )')}}</p>
                        <label for="urlwebhook" class="form-label">{{__('Link webhook')}}</label>
                        <input type="text" name="urlwebhook" class="form-control" id="urlwebhook">
                        <p class="text-small text-danger">*{{__('Optional')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="submit" name="submit" class="btn btn-primary">{{__('Save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layout-dashboard>
<script>
    var typingTimer; //timer identifier
    var doneTypingInterval = 1000;

    $('.webhook-url-form').on('keyup', function() {
        clearTimeout(typingTimer);
        let value = $(this).val();
        let number = $(this).data('id');

        typingTimer = setTimeout(function() {

            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('setHook') }}',
                data: {
                    csrf: $('meta[name="csrf-token"]').attr('content'),
                    number: number,
                    webhook: value
                },
                dataType: 'json',
                success: (result) => {
                    toastr.success('{{__("Webhook URL has been updated")}}');
                },
                error: (err) => {
                    console.log(err);
                }
            })
        }, doneTypingInterval);
    });
	
	$('.delay-url-form').on('keyup', function() {
        clearTimeout(typingTimer);
        let value = $(this).val();
        let number = $(this).data('id');

        typingTimer = setTimeout(function() {

            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('setDelay') }}',
                data: {
                    csrf: $('meta[name="csrf-token"]').attr('content'),
                    number: number,
                    delay: value
                },
                dataType: 'json',
                success: (result) => {
                    toastr.success('{{__("Delay has been updated")}}');
                },
                error: (err) => {
                    console.log(err);
                }
            })
        }, doneTypingInterval);
    });
	
	$(".toggle-read").on("click", function () {
		let dataId = $(this).data("id");
		let isChecked = $(this).is(":checked");
		let url = $(this).data("url");
		$.ajax({
			url: url,
			type: "POST",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			data: {
				webhook_read: isChecked ? "1" : "0",
				id: dataId,
			},
			success: function (result) {
				if(!result.error){
					// find label
					let label = $(`.toggle-read[data-id=${dataId}]`)
						.parent()
						.find("label");
					// change label
					if (isChecked) {
						label.text("{{__('Yes')}}");
					} else {
						label.text("{{__('No')}}");
					}
					toastr['success'](result.msg);
				}
			},
		});
	});
	
	$(".toggle-reject").on("click", function () {
		let dataId = $(this).data("id");
		let isChecked = $(this).is(":checked");
		let url = $(this).data("url");
		$.ajax({
			url: url,
			type: "POST",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			data: {
				webhook_reject_call: isChecked ? "1" : "0",
				id: dataId,
			},
			success: function (result) {
				if(!result.error){
					// find label
					let label = $(`.toggle-reject[data-id=${dataId}]`)
						.parent()
						.find("label");
					// change label
					if (isChecked) {
						label.text("{{__('Yes')}}");
					} else {
						label.text("{{__('No')}}");
					}
					toastr['success'](result.msg);
				}
			},
		});
	});
	
	$(".toggle-typing").on("click", function () {
		let dataId = $(this).data("id");
		let isChecked = $(this).is(":checked");
		let url = $(this).data("url");
		$.ajax({
			url: url,
			type: "POST",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			data: {
				webhook_typing: isChecked ? "1" : "0",
				id: dataId,
			},
			success: function (result) {
				if(!result.error){
					// find label
					let label = $(`.toggle-typing[data-id=${dataId}]`)
						.parent()
						.find("label");
					// change label
					if (isChecked) {
						label.text("{{__('Yes')}}");
					} else {
						label.text("{{__('No')}}");
					}
					toastr['success'](result.msg);
				}
			},
		});
	});
	
	$(".toggle-available").on("click", function () {
		let dataId = $(this).data("id");
		let isChecked = $(this).is(":checked");
		let url = $(this).data("url");
		$.ajax({
			url: url,
			type: "POST",
			headers: {
				"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
			},
			data: {
				set_available: isChecked ? "1" : "0",
				id: dataId,
			},
			success: function (result) {
				if(!result.error){
					// find label
					let label = $(`.toggle-available[data-id=${dataId}]`)
						.parent()
						.find("label");
					// change label
					if (isChecked) {
						label.text("{{__('Yes')}}");
					} else {
						label.text("{{__('No')}}");
					}
					toastr['success'](result.msg);
				}
			},
		});
	});
</script>
