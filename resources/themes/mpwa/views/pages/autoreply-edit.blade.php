<x-layout-dashboard title="{{__('Auto Replies')}}">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	<!--breadcrumb-->
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">{{__('Whatsapp')}}</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">{{__('Auto Reply')}}</li>
				</ol>
			</nav>
		</div>
	</div>
    <!--end breadcrumb-->
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
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{__('Edit Auto Reply')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('autoreply.edit.update') }}" method="POST" enctype="multipart/form-data" id="formautoreplyedit{{ $autoreply->id }}">
                        @csrf
                        @if (Session::has('selectedDevice'))
                        <input type="hidden" name="device" value="{{ Session::get('selectedDevice')['device_id'] }}">
                        <input type="hidden" name="device_body" id="device" class="form-control" value="{{ Session::get('selectedDevice')['device_body'] }}" readonly>
                        @else
                        <input type="text" name="devicee" id="device" class="form-control" value="{{__('Please select device')}}" readonly>
                        @endif
                        <input type="hidden" name="edit_id" value="{{ $autoreply->id }}">

                        <div class="mb-3">
                            <label class="form-label">{{__('Type Keyword')}}</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input type="radio" value="Equal" name="type_keyword" class="form-check-input" id="keywordTypeEqual" @if ($autoreply->type_keyword == 'Equal') checked @endif>
                                    <label class="form-check-label" for="keywordTypeEqual">{{__('Equal')}}</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="Contain" name="type_keyword" class="form-check-input" id="keywordTypeContain" @if ($autoreply->type_keyword == 'Contain') checked @endif>
                                    <label class="form-check-label" for="keywordTypeContain">{{__('Contains')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{__('Only reply when sender is')}}</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input type="radio" value="Group" name="reply_when" class="form-check-input" id="replyWhenGroup" @if ($autoreply->reply_when == 'Group') checked @endif>
                                    <label class="form-check-label" for="replyWhenGroup">{{__('Group')}}</label>
                                </div>
                                <div class="form-check me-3">
                                    <input type="radio" value="Personal" name="reply_when" class="form-check-input" id="replyWhenPersonal" @if ($autoreply->reply_when == 'Personal') checked @endif>
                                    <label class="form-check-label" for="replyWhenPersonal">{{__('Personal')}}</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" value="All" name="reply_when" class="form-check-input" id="replyWhenAll" @if ($autoreply->reply_when == 'All') checked @endif>
                                    <label class="form-check-label" for="replyWhenAll">{{__('All')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keyword" class="form-label">{{__('Keyword')}}</label>
                            <input type="text" name="keyword" class="form-control" id="keyword" value="{{ $autoreply->keyword }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">{{__('Type Reply')}}</label>
                            <select name="type" id="typeEdit{{ $autoreply->id }}" class="js-statesEdit form-control" data-id="{{ $autoreply->id }}" tabindex="-1" required>
                                <option selected disabled>{{__('Select One')}}</option>
                                <option value="text" @if ($autoreply->type == 'text') selected @endif>{{__('Text Message')}}</option>
                                <option value="media" @if ($autoreply->type == 'media') selected @endif>{{__('Media Message')}}</option>
								<option value="location" @if ($autoreply->type == 'location') selected @endif>{{__('Location Message')}}</option>
								<option value="sticker" @if ($autoreply->type == 'sticker') selected @endif>{{__('Sticker Message')}}</option>
								<option value="vcard" @if ($autoreply->type == 'vcard') selected @endif>{{__('VCard Message')}}</option>
                                <option value="list" @if ($autoreply->type == 'list') selected @endif>{{__('List Message')}}</option>
                                <option value="button" @if ($autoreply->type == 'button') selected @endif>{{__('Button Message (Deprecated)')}}</option>
                                <option value="template" @if ($autoreply->type == 'template') selected @endif>{{__('Template Message (Deprecated)')}}</option>
                            </select>
                        </div>

                        <div class="ajaxplaceEdit{{ $autoreply->id }}"></div>
						<div id="loadjs{{ $autoreply->id }}"></div>
                        <div class="mt-3">
                            <button type="submit" name="submit" class="btn btn-primary">{{__('Edit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css" />
	<script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"></script>
	<script src="https://woody180.github.io/vanilla-javascript-emoji-picker/vanillaEmojiPicker.js"></script>
	<script>
	$(document).on('change', 'select[id^=typeEdit]', function() {
        const type = $(this).val();
        const id = $(this).data('id');
        loadAjaxContent(type, id);
    });
	function loadScript(url) {
			  var script = document.createElement('script');
			  script.src = url;
			  document.getElementById("loadjs{{ $autoreply->id }}").appendChild(script); 
	}
	function loadAjaxContent(types, id) {
			$.ajax({
				url: `/form-message-edit/${types}`,
				type: "GET",
				data: { id: id, type: types, table: 'autoreplies', column: 'reply' },
				dataType: "html",
				success: (result) => {
					$(`.ajaxplaceEdit{{ $autoreply->id }}`).html(result);
					loadScript('{{asset("js/text.js")}}');
					loadScript('{{asset("vendor/laravel-filemanager/js/stand-alone-button2.js")}}');
				},
				error: (error) => {
					console.log(error);
				},
			});
	}
	const type = $('#typeEdit{{ $autoreply->id }}').val();
	loadAjaxContent(type, {{ $autoreply->id }});
	</script>
</x-layout-dashboard>