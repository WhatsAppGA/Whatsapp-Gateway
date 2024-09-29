<x-layout-dashboard title="{{__('Ai Bot')}}">
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
   <div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
      <div class="d-flex align-items-center">
         <div class="fs-3 text-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
         </div>
         <div class="ms-3">
            <p>{{__('The given data was invalid.')}}</p>
            <ul>
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
   @endif
   {{-- form --}}
   <div class="row">
      <div class="mx-auto">
         <div class="card">
            <div class="card-header">
                  <h5 class="card-title">{{__('Ai Bot Options')}}</h5>
            </div>
            <div class="card-body">
			@if (!session()->has('selectedDevice') || !session()->has('selectedDevice'))
            <div class="alert alert-danger">
               <ul>
                  <li>{{__('Please select a device to enable bot')}}</li>
               </ul>
            </div>
            @else
               <div class="row g-3">
                  <div class="card shadow-none border">
                     <div class="card-body">
                        <form class="row g-3" action="{{ route('aibot') }}" method="POST">
                           @csrf
                           <div class="col-12 border rounded pt-2">
								<label for="type" class="form-label">{{__('Type bot:')}}</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="typebot" id="disablebot" value="0" {{ $device->typebot == 0 ? 'checked' : '' }}>
									<label class="form-check-label" for="disablebot">{{__('Disable')}}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="typebot" id="onebot" value="1" {{ $device->typebot == 1 ? 'checked' : '' }}>
									<label class="form-check-label" for="onebot">{{__('One Bot')}}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="typebot" id="multibot" value="2" {{ $device->typebot == 2 ? 'checked' : '' }}>
									<label class="form-check-label" for="multibot">{{__('Multi Bot')}}</label>
								</div>
							</div>
						   <div id="hideandshow" class="col-12 border rounded pt-2" style="display: none;">
								<label for="type" class="form-label">{{__('Only reply when sender is:')}}</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="reply_when" id="Group" value="Group" {{ $device->reply_when == 'Group' ? 'checked' : '' }}>
									<label class="form-check-label" for="Group">{{__('Group')}}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="reply_when" id="Personal" value="Personal" {{ $device->reply_when == 'Personal' ? 'checked' : '' }}>
									<label class="form-check-label" for="Personal">{{__('Personal')}}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="reply_when" id="All" value="All" {{ $device->reply_when == 'All' ? 'checked' : '' }}>
									<label class="form-check-label" for="All">{{__('All')}}</label>
								</div>
							</div>
                           <div id="hideandshow" class="col-12 border rounded pt-2" style="display: none;">
                              <label for="type" class="form-label">{{__('Reject call:')}}</label>
                              <input id="toggle-act" class="form-check-input toggle-status" name="reject_call" value="1" type="checkbox" {{ $device->reject_call == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="toggle-act">{{__('active')}}</label><br />
                              <label for="reject_message" class="form-label">{{__('The message that will be sent when the call is rejected')}}</label>
                              <textarea type="text" id="reject_message" name="reject_message" class="form-control" id="keyword" cols="30" rows="4">{{ $device->reject_message ?? '' }}</textarea>
                           </div>
                           <div id="hideandshow" class="col-12 border rounded pt-2" style="display: none;">
                              <label for="type" class="form-label">{{__('Can bot read msg?:')}}</label>
                              <input id="toggle-read" class="form-check-input toggle-read" name="can_read_message" type="checkbox" {{ $device->can_read_message == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="toggle-read">{{__('active')}}</label>
                           </div>
						   <div id="hideandshow" class="col-12 border rounded pt-2" style="display: none;">
                              <label for="type" class="form-label">{{__('Bot can typing?:')}}</label>
                              <input id="toggle-typeing" class="form-check-input toggle-typing" name="bot_typing" type="checkbox" {{ $device->bot_typing == 1 ? 'checked' : '' }}>
                              <label class="form-check-label" for="toggle-typeing">{{__('active')}}</label>
                           </div>
                           <div id="hideandshow" class="col-12 border rounded pb-2 pt-1" style="display: none;">
								<div class="row">
									<div class="col-md-3 mb-3 nameshow">
										<label class="form-label">{{__('ChatGPT Name')}}</label>
										<input name="chatgpt_name" class="form-control form-control-sm" value="{{ $device->chatgpt_name ?? '' }}" placeholder="{{__('Ex: gpt')}}" type="text">
									</div>
									<div class="col-md-9 mb-3">
										<label class="form-label">{{__('ChatGPT API')}}</label>
										<input name="chatgpt_api" value="{{ $device->chatgpt_api ?? '' }}" type="text" class="form-control form-control-sm">
									</div>
								</div>
							</div>

							<div id="hideandshow" class="col-12 border rounded pb-2 pt-1" style="display: none;">
								<div class="row">
									<div class="col-md-3 mb-3 nameshow">
										<label class="form-label">{{__('DALL-E Name')}}</label>
										<input name="dalle_name" class="form-control form-control-sm" value="{{ $device->dalle_name ?? '' }}" placeholder="{{__('Ex: dalle')}}" type="text">
									</div>
									<div class="col-md-9 mb-3">
										<label class="form-label">{{__('DALL-E API')}}</label>
										<input name="dalle_api" value="{{ $device->dalle_api ?? '' }}" type="text" class="form-control form-control-sm">
									</div>
								</div>
							</div>

							<div id="hideandshow" class="col-12 border rounded pb-2 pt-1" style="display: none;">
								<div class="row">
									<div class="col-md-3 mb-3 nameshow">
										<label class="form-label">{{__('Gemini Name')}}</label>
										<input name="gemini_name" class="form-control form-control-sm" value="{{ $device->gemini_name ?? '' }}" placeholder="{{__('Ex: gemini')}}" type="text">
									</div>
									<div class="col-md-9 mb-3">
										<label class="form-label">Gemini API</label>
										<input name="gemini_api" value="{{ $device->gemini_api ?? '' }}" type="text" class="form-control form-control-sm">
									</div>
								</div>
							</div>

							<div id="hideandshow" class="col-12 border rounded pb-2 pt-1" style="display: none;">
								<div class="row">
									<div class="col-md-3 mb-3 nameshow">
										<label class="form-label">{{__('Claude Name')}}</label>
										<input name="claude_name" class="form-control form-control-sm" value="{{ $device->claude_name ?? '' }}" placeholder="{{__('Ex: claude')}}" type="text">
									</div>
									<div class="col-md-9 mb-3">
										<label class="form-label">{{__('Claude API')}}</label>
										<input name="claude_api" value="{{ $device->claude_api ?? '' }}" type="text" class="form-control form-control-sm">
									</div>
								</div>
							</div>
                           <div class="col-12 ajaxplace ">
                           </div>
                           {{-- button --}}
                           <div class="col-12 text-center">
                              <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Save')}}</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            @endif
            <!--end row-->
         </div>
      </div>
   </div>
   </div>
   </div>
</div>
   {{-- end form --}}
</x-layout-dashboard>
<script>
   document.addEventListener('DOMContentLoaded', function() {
       const radios = document.querySelectorAll('input[name="typebot"]');
       const hideAndShowDivs = document.querySelectorAll('#hideandshow');
       const nameShowElements = document.querySelectorAll('.nameshow');
   
       function updateVisibility() {
           const selectedValue = document.querySelector('input[name="typebot"]:checked').value;
   
           hideAndShowDivs.forEach(div => {
               if (selectedValue === '0') {
                   div.style.display = 'none';
               } else {
                   div.style.display = 'block';
               }
           });
   
           nameShowElements.forEach(element => {
               if (selectedValue === '1') {
                   element.style.display = 'none';
               } else {
                   element.style.display = 'block';
               }
           });
       }
   
       radios.forEach(radio => {
           radio.addEventListener('change', updateVisibility);
       });
       updateVisibility();
   });
</script>