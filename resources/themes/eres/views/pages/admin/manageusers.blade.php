<x-layout-dashboard title="{{__('Manage User')}}">
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
                    <h5 class="card-title">{{__('Users')}}</h5>
                    <button type="button" class="btn btn-primary" onclick="addUser();">
                        {{__('Add User')}}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md" style="font-size: 0.755rem;">
                            <thead>
                                <tr>
                                    <th>{{__('Username')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Total Device')}}</th>
                                    <th>{{__('Limit Device')}}</th>
                                    <th>{{__('Subscription')}}</th>
                                    <th>{{__('Expired subscription')}}</th>
                                    <th>{{__('Action')}}</th>
                                    {{-- <th class="d-flex justify-content-center">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->total_device }}</td>
                                        <td>{{ $user->limit_device }}</td>
                                        <td>
                                            @php
                                                if ($user->is_expired_subscription) {
                                                    $badge = 'danger';
                                                } else {
                                                    $badge = 'success';
                                                }
                                            @endphp
                                            <span
                                                class="badge bg-{{ $badge }}">{{ $user->active_subscription }}</span>
                                        </td>

                                        <td>
                                            @php
                                                if ($user->is_expired_subscription) {
                                                    echo '<span class="badge bg-danger">-</span>';
                                                } else {
                                                    if ($user->active_subscription == 'active') {
                                                        echo $user->subscription_expired;
                                                    } else {
                                                        echo '<span class="badge bg-danger">-</span>';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a onclick="editUser({{ $user->id }})" href="javascript:;"
                                                    class="btn btn-primary shadow btn-xs sharp me-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="{{__('Edit user')}}"><i class="fa fa-pencil"></i></a>

                                                <form action="{{ route('user.delete', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('{{__('Are you sure will delete this user ? all data user also will deleted')}}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <button type="submit" name="delete"
                                                        class="btn btn-danger shadow btn-xs sharp"><i
                                                                class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            <tfoot></tfoot>
                        </table>
                    </div>
                      <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $users->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}">{{__('Previous')}}</a>
                            </li>

                            @for ($i = 1; $i <= $users->lastPage(); $i++)
                                <li class="page-item {{ $users->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li
                                class="page-item {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}">{{__('Next')}}</a>
                            </li>
                        </ul>
                    </nav>
                </div>


        <!-- Modal -->
        <div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data" id="formUser">
                            @csrf
                            <input type="hidden" id="iduser" name="id">
                            <label for="username" class="form-label">{{__('Username')}}</label>
                            <input type="text" name="username" id="username" class="form-control" value="">
                            <label for="email" class="form-label">{{__('Email')}}</label>
                            <input type="email" name="email" id="email" class="form-control" value="">
                            <label for="password" class="form-label" id="labelpassword">{{__('Password')}}</label>
                            <input type="password" name="password" id="password" class="form-control" value="">
                            <label for="limit_device" class="form-label">{{__('Limit Device')}}</label>
                            <input type="number" name="limit_device" id="limit_device" class="form-control"
                                value="">
                            <label for="active_subscription" class="form-label">{{__('Active Subscription')}}</label><br>
                            <select name="active_subscription" id="active_subscription" class="form-control">
                                <option value="active" selected>{{__('Active')}}</option>
                                <option value="inactive">{{__('Inactive')}}</option>
                                <option value="lifetime">{{__('Lifetime')}}</option>
                            </select><br>
                            <label for="subscription_expired" class="form-label">{{__('Subscription Expired')}}</label>
                            <input type="date" name="subscription_expired" id="subscription_expired"
                                class="form-control" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" id="modalButton" name="submit" class="btn btn-primary">{{__('Add')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
        <script>
				function addUser() {
					$('#modalLabel').html('{{__("Add User")}}');
					$('#modalButton').html('{{__("Add")}}');
					$('#formUser').attr('action', '{{ route('user.store') }}');
					$('#modalUser').modal('show');
				}

				function editUser(id) {

					// return;
					$('#modalLabel').html('{{__("Edit User")}}');
					$('#modalButton').html('{{__("Edit")}}');
					$('#formUser').attr('action', '{{ route('user.update') }}');
					$('#modalUser').modal('show');
					$.ajax({
						url: "{{ route('user.edit') }}",
						type: "GET",
						data: {
							id: id
						},
						dataType: "JSON",
						success: function(data) {
							$('#labelpassword').html('{{__("Password *(leave blank if not change)")}}');
							$('#username').val(data.username);
							$('#email').val(data.email);
							$('#password').val(data.password);
							$('#limit_device').val(data.limit_device);
							$('#active_subscription').val(data.active_subscription);
							$('#subscription_expired').val(data.subscription_expired);
							$('#iduser').val(data.id);
						}
					});
				}
        </script>
</x-layout-dashboard>
