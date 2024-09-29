<x-layout-dashboard title="{{__('Campaign')}}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if (session()->has('alert'))
        <x-alert>
            @slot('type', session('alert')['type'])
            @slot('msg', session('alert')['msg'])
        </x-alert>
    @endif
    {{-- table --}}
    <div class="row">
        <div class="col-12 col-lg-9 d-flex">
            <div class="card rounded-4">
                <div class="card-header">
                    <h5>{{__('Campaign')}}</h5>
					<div class="ms-auto">
						<button onclick="clearCampaign()" type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
							data-bs-target="#deleteAllModal">
							<i class="bi bi-trash-fill"></i> {{__('Clear Campaign')}}
					</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="font-size: 0.755rem;">
                            <thead>
                                <tr>
                                    <th>{{__('Device')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Schedule')}}</th>
                                    <th>{{__('Summary')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($campaigns->total() == 0)
                                    <x-no-data colspan="7" text="{{__('No Autoreplies added yet')}}" text="{{__('No Campaigns added yet')}}" />
                                @endif
                                @foreach ($campaigns as $campaign)
                                    <tr>
                                        <td>{{ $campaign->device->body }}</td>
                                        <td>{{ $campaign->name }}</td>
                                        <td>{{ $campaign->schedule }}</td>
                                        <td>
                                            <span class="badge bg-primary w-100"><span class="float-start">{{ $campaign->blasts_count }}</span> {{__('total')}}</span>
                                            <br>
                                            <span class="badge bg-success w-100"><span class="float-start">{{ $campaign->blasts_success }}</span> {{__('Success')}}</span>
                                            <br>
                                            <span class="badge bg-danger w-100"><span class="float-start">{{ $campaign->blasts_failed }}</span> {{__('Failed')}}</span>
                                            <br>
                                            <span class="badge bg-warning w-100"><span class="float-start">{{ $campaign->blasts_pending }}</span> {{__('Waiting')}}</span>
                                            {{-- button view blasts list --}}
                                            <br>
                                        </td>
                                        <td>
                                            @if ($campaign->status == 'completed')
                                                <span class="badge rounded-pill bg-success">{{__('Completed')}}</span>
                                            @elseif ($campaign->status == 'paused')
                                                <span class="badge rounded-pill bg-secondary">{{__('Paused')}}</span>
                                            @elseif ($campaign->status == 'waiting')
                                                <span class="badge rounded-pill bg-warning">{{__('Waiting')}}</span>
                                            @elseif ($campaign->status == 'processing')
                                                <span class="badge rounded-pill bg-info">{{__('Processing')}}</span>

                                            @else
                                                <span
                                                    class="badge rounded-pill bg-danger">{{ $campaign->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="gap-3 fs-6">
                                                 <a href="{{route('campaign.blasts', $campaign->id)}}"
                                                    class="text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="{{__('View Data')}}">
                                                <i class="bi bi-eye-fill"></i>
                                                </a>
                                                @if ($campaign->status == 'processing' || $campaign->status == 'waiting')
                                                    <a href="#" onclick="pauseCampaign('{{ $campaign->id }}')"
                                                        class="btn btn-warning shadow btn-xs sharp" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" title="{{__('Pause')}}"><i
                                                            class="fa fa-pause"></i></a>
                                                @endif
                                                @if ($campaign->status == 'paused')
                                                    <a href="#" onclick="resumeCampaign('{{ $campaign->id }}')"
                                                        class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" title="{{__('Resume')}}"><i
                                                            class="fa fa-play"></i></a>
                                                @endif
                                                <a href="#" onclick="deleteCampaign('{{ $campaign->id }}')"
                                                    class="btn btn-danger shadow btn-xs sharp" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="{{__('Delete')}}"><i
                                                        class="fa fa-trash"></i></a>

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
						  <li class="page-item {{ $campaigns->currentPage() == 1 ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $campaigns->previousPageUrl() }}">{{__('Previous')}}</a>
						  </li>

						  @for ($i = 1; $i <= $campaigns->lastPage(); $i++)
							<li class="page-item {{ $campaigns->currentPage() == $i ? 'active' : '' }}">
							  <a class="page-link" href="{{ $campaigns->url($i) }}">{{ $i }}</a>
							</li>
						  @endfor

						  <li class="page-item {{ $campaigns->currentPage() == $campaigns->lastPage() ? 'disabled' : '' }}">
							<a class="page-link" href="{{ $campaigns->nextPageUrl() }}">{{__('Next')}}</a>
						  </li>
						</ul>
					  </nav>
					</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 d-flex">
            <div class="card w-100">
                <div class="card-header py-3">
                    <h5 class="mb-0">{{__('Filter by')}}</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{__('Device')}}</label>
                            <input
                            value="{{ request()->has('device') ? request()->device : '' }}"
                             type="number" name="device" class="form-control" placeholder="{{__('Order ID')}}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{__('Status')}}</label>
							<select class="default-select  form-control wide" id="status" name="status">
                                <option {{ request()->has('status') && request()->status == 'all' ? 'selected' : '' }} value="all">{{__('All')}}</option>
                                <option {{ request()->has('status') && request()->status == 'completed' ? 'selected' : '' }} value="completed">{{__('Completed')}}</option>
                                <option {{ request()->has('status') && request()->status == 'processing' ? 'selected' : '' }} value="processing">{{__('Processing')}}</option>
                                <option {{ request()->has('status') && request()->status == 'waiting' ? 'selected' : '' }} value="waiting">{{__('Waiting')}}</option>
                                <option {{ request()->has('status') && request()->status == 'paused' ? 'selected' : '' }} value="paused">{{__('Paused')}}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <button class="btn btn-primary">{{__('Filter Campaign')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end table --}}

    {{-- Modal preview message --}}
    <div class="modal fade" id="previewMessage" tabindex="-1" aria-labelledby="previewMessage" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Campaign Message Preview')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body preview-message-area">
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal Preview Message --}}
        </div>
    </div>
</x-layout-dashboard>
<script>
    function viewMessage(id) {
        $.ajax({
            url: `/preview-message`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: {
                id: id,
                table: 'campaigns',
                column: 'message'
            },
            dataType: 'html',
            success: (result) => {

                $('.preview-message-area').html(result);
                $('#previewMessage').modal('show')
            },
            error: (error) => {
                console.log(error);
                toastr['error']('{{__("something went wrong")}}')
            }
        })
        // 
    }

    function pauseCampaign(id) {
        $.ajax({
            url: `/campaign/pause/${id}`,
            type: 'POST',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('{{__("something went wrong when pausing campaign")}}')
            }
        })
    }

    function resumeCampaign(id) {
        $.ajax({
            url: `/campaign/resume/${id}`,
            type: 'POST',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('{{__("something went wrong when resuming campaign")}}')
            }
        })
    }

    function deleteCampaign(id) {
        if (!confirm('{{__("Are you sure you want to delete this campaign?")}}')) {
            toastr['error']('{{__("Cancel deleting campaign")}}')
            return;
        }
        $.ajax({
            url: `/campaign/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('{{__("something went wrong when deleting campaign")}} ')
            }
        })
    }

    function clearCampaign(id) {
        if (!confirm('{{__("Are you sure you want to clear this campaign?")}}')) {
            toastr['error']('{{__("Cancel clearing campaign")}}')
            return;
        }
        $.ajax({
            url: `/campaign/clear`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('{{__("something went wrong when clearing campaign")}} ')
            }
        })
    }
</script>
