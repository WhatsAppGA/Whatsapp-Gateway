<x-layout-dashboard title="{{__('Authenticator 2FA')}}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
               <div class="card">
                  <div class="card-body">
                     @if (session()->has('alert'))
                     <x-alert>
                        @slot('type', session('alert')['type'])
                        @slot('msg', session('alert')['msg'])
                     </x-alert>
                     @endif
                     <div class="row col-md-12 mx-auto">
                        <div class="col-md-12 text-center">
                           <h2>{{__('Enable Authenticator 2FA')}}</h2>
                           <p>{{__('Scan the following QR code using the Google Authenticator app')}}</p>
						   <div class="row border p-1">
                           <div class="col-md-6">{!! $qrCodeImage !!}</div>
                           <div class="col-md-6 d-flex flex-column justify-content-center">
						   <form method="POST" action="{{ route('user.2fa.verify') }}">
                              @csrf
                              <div class="col-md-6 mx-auto d-block">
                                 <label for="2fa_code" class="form-label">{{__('Enter the code')}}</label>
                                 <input type="number" name="2fa_code" class="form-control">
                              </div>
                              <button type="submit" class="btn btn-info text-white m-t-sm mt-3">{{__('Confirm')}}</button>
                              <button type="button" class="btn btn-danger text-white m-t-sm mt-3" onclick="window.location.href='{{ url('/user/settings') }}'">{{__('Cencel')}}</button>
                           </form>
						   </div>
						   </div>
                        </div>
                     </div>
					 <div class="row col-md-12 mx-auto mt-2 border text-center p-1">
					 <h4>{{__('Recovery Codes')}}</h4>
					 <p>{{__('You can use Recovery Codes if you accidentally delete the Google Authenticator app or lose your phone. Use these codes when logging in instead of the app')}}</p>
					 <div class="col-12">
					    <div class="row">
           		        @foreach($recoveryCodes as $code)
              		        <div class="col-3">{{ $code }}</div>
           		        @endforeach
						</div>
					 </div>
					 </div>
                  </div>
               </div>
            </div>
         </div>
</x-layout-dashboard>