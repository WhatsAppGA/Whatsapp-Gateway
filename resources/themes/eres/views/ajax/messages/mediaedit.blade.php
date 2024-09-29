<label class="form-label">{{__('Media Url')}} </label>
<div class="input-group media-area">
    <span class="input-group-btn">
        <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
            <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
    </span>
    <input id="thumbnail" class="form-control" type="text" name="url" value="{{ $message->url ?? '' }}">
</div>
<div class="form-group mt-4">
    <label class="form-label me-3">{{__('View Once')}}</label>
    <div class="form-check form-switch">
        <input class="form-check-input toggle-read" type="checkbox" name="viewonce" value="true" id="toggle-switch-once" @if ($message->viewonce == 'true') checked @endif>
        <label class="form-check-label" for="toggle-switch-once" id="toggle-label-once">@if ($message->viewonce == 'true') {{__('Yes')}} @endif</label>
        <small>({{__('Only works with images & videos')}})</small>
    </div>
</div>
<div class="form-group mt-4">
    <label class="form-label">{{__('Media Type')}}</label>
    {{-- dif flex gap 4 --}}

    <div class="d-flex ">
        <div class="custom-control custom-radio custom-control-inline me-3">
            <input type="radio" id="customRadioInline1" name="media_type" class="custom-control-input" value="image" @if (!empty($message->type) && $message->type == 'image') checked @endif>
            <label class="custom-control-label" for="customRadioInline1">{{__('Image')}}</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline me-3">
            <input type="radio" id="customRadioInline1" name="media_type" class="custom-control-input" value="document" @if (!empty($message->type) && $message->type == 'document') checked @endif>
            <label class="custom-control-label" for="customRadioInline1">{{__('Document')}}</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline me-3">
            <input type="radio" id="customRadioInline2" name="media_type" class="custom-control-input" value="video" @if (!empty($message->type) && $message->type == 'video') checked @endif>
            <label class="custom-control-label" for="customRadioInline2">{{__('Video')}}</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="customRadioInline3" name="media_type" class="custom-control-input" value="audio" @if (!empty($message->type) && $message->type == 'audio') checked @endif>
            <label class="custom-control-label" for="customRadioInline3">{{__('Voice Note')}}</label>
        </div>
        {{-- pdf,xls,xlsx,doc,docx,zip,mp3 --}}
    </div>
</div>
{{-- end optional --}}

<div class="form-group caption-area mt-4">

    <label for="caption" class="form-label">{{__('Caption')}}</label>
    <textarea type="text" name="caption" class="form-control" id="caption">{{$message->caption ?? ''}}</textarea>
</div>

<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button2.js')}}"></script>
<script>
    $('.type-audio').hide()
	
	const toggleSwitch = document.getElementById('toggle-switch-once');
    const toggleLabel = document.getElementById('toggle-label-once');

    toggleSwitch.addEventListener('change', function() {
        if (this.checked) {
            toggleLabel.textContent = "{{__('Yes')}}";
        } else {
            toggleLabel.textContent = "{{__('No')}}";
        }
    });

</script>
