@php
    $numbers = request()->user()->devices()->latest()->paginate(15);
@endphp
        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					<li class="{{ request()->is('home') ? 'mm-active' : '' }}">
						<a href="{{ route('home') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-home"></i>
							<span class="nav-text">{{__('Dashboard')}}</span>
						</a>
					</li>
					<li class="{{ request()->is('file-manager') ? 'mm-active' : '' }}">
						<a href="{{ route('file-manager') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-folder"></i>
							<span class="nav-text">{{__('File Manager')}}</span>
						</a>
					</li>
					<li class="{{ request()->is('phonebook') ? 'mm-active' : '' }}">
						<a href="{{ route('phonebook') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-phone-call"></i>
							<span class="nav-text">{{__('Phone Book')}}</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
							<i class="flaticon-381-newspaper"></i>
							<span class="nav-text">{{__('Reports')}}</span>
						</a>
						<ul aria-expanded="false">
                            <li class="{{ request()->is('campaigns') ? 'mm-active' : '' }}">
								<a href="{{ route('campaigns') }}">{{__('Campaign / Blast')}}</a>
							</li>
                            <li class="{{ request()->is('messages.history') ? 'mm-active' : '' }}">
								<a href="{{ route('messages.history') }}">{{__('Messages History')}}</a>
							</li>
						</ul>
					</li>
					
					<x-select-device :numbers="$numbers"></x-select-device>
					
				{{-- these menus only show if exists selected devices --}}
				@if (Session::has('selectedDevice'))
                    <li class="{{ request()->is('autoreply') ? 'mm-active' : '' }}">
						<a href="{{ route('autoreply') }}" class="ai-icon" aria-expanded="false">
							<i class="fa-regular fa-comment-dots"></i>
							<span class="nav-text">{{__('Auto Reply')}}</span>
						</a>
					</li>
					<li class="{{ request()->is('aibot') ? 'mm-active' : '' }}">
						<a href="{{ route('aibot') }}" class="ai-icon" aria-expanded="false">
							<i class="fa-solid fa-robot"></i>
							<span class="nav-text">{{__('AI Bot')}}</span>
						</a>
					</li>
					<li class="{{ request()->is('campaign.create') ? 'mm-active' : '' }}">
						<a href="{{ route('campaign.create') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-box"></i>
							<span class="nav-text">{{__('Create Campaign')}}</span>
						</a>
					</li>
					<li class="{{ request()->is('messagetest') ? 'mm-active' : '' }}">
						<a href="{{ route('messagetest') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-send-1"></i>
							<span class="nav-text">{{__('Test Message')}}</span>
						</a>
					</li>
				@endif
					<li class="{{ request()->is('rest-api') ? 'mm-active' : '' }}">
						<a href="{{ route('rest-api') }}" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-cloud-computing"></i>
							<span class="nav-text">{{__('API Docs')}}</span>
						</a>
					</li>
				@if (Auth::user()->level == 'admin')
                    <li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-settings-2"></i>
							<span class="nav-text">{{__('Admin')}}</span>
						</a>
                        <ul aria-expanded="false">
                            <li class="{{ request()->is('admin.settings') ? 'mm-active' : '' }}">
								<a href="{{ route('admin.settings') }}">{{__('Setting Server')}}</a>
							</li>
                            <li class="{{ request()->is('admin.manage-users') ? 'mm-active' : '' }}">
								<a href="{{ route('admin.manage-users') }}">{{__('Manage User')}}</a>
							</li>
							<li class="{{ request()->is('admin.manage-themes') ? 'active' : '' }}">
                               <a href="{{ route('admin.manage-themes') }}">{{__('Themes Manager')}}</a>
                            </li>
							<li class="{{ request()->is('update') ? 'mm-active' : '' }}">
								<a href="{{ route('update') }}">{{__('Update')}}</a>
							</li>
                        </ul>
                    </li>
				@endif
                </ul>

				<div class="copyright">
				{!! config('config.footer_copyright') !!}
				</div>
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->