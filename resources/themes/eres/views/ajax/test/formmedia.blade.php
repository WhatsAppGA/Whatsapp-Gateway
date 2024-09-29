<div class="tab-pane fade show" id="mediamessage" role="tabpanel">
                    <form class="row g-3" action="{{ route('messagetest') }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">{{__('Sender')}}</label>
                            <input name="sender" value="{{ session()->get('selectedDevice')['device_body'] }}" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{__('Receiver Number')}}</label>
                            <textarea placeholder="628xxx|628xxx|628xxx" class="form-control" name="number" id="" cols="20" rows="2"></textarea>
                        </div>

<label class="form-label">{{__('Media Url')}} </label>
<div class="input-group media-area">
    <span class="input-group-btn">
        <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
            <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
    </span>
    <input id="thumbnail" class="form-control" type="text" name="url">
</div>
<div class="form-group mt-4">
    <label class="form-label me-3">{{__('View Once')}}</label>
    <div class="form-check form-switch">
        <input class="form-check-input toggle-read" type="checkbox" name="viewonce" value="true" id="toggle-switch-once">
        <label class="form-check-label" for="toggle-switch-once" id="toggle-label-once">{{__('No')}}</label>
        <small>({{__('Only works with images & videos')}})</small>
    </div>
</div>
<div class="form-group mt-4">
  <label class="form-label">{{__('Media Type')}}</label>
  <div class="d-flex">
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="media_type" id="customRadioInline1" value="image">
      <label class="form-check-label" for="customRadioInline1">{{__('Image')}}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="media_type" id="customRadioInline4" value="document" checked>
      <label class="form-check-label" for="customRadioInline4">{{__('Document')}}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="media_type" id="customRadioInline2" value="video">
      <label class="form-check-label" for="customRadioInline2">{{__('Video')}}</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="media_type" id="customRadioInline3" value="audio">
      <label class="form-check-label" for="customRadioInline3">{{__('Voice Note')}}</label>
    </div>
  </div>
</div>
<input type="hidden" name="type" value="media" />
<div class="form-group caption-area mt-4">

    <label for="caption" class="form-label">{{__('Caption')}}</label>
    <textarea type="text" name="caption" class="form-control" id="caption" required> </textarea>
</div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script>
window.addEventListener('load', function() {
    const toggleSwitch = document.getElementById('toggle-switch-once');
    const toggleLabel = document.getElementById('toggle-label-once');

    toggleSwitch.addEventListener('change', function() {
        if (this.checked) {
            toggleLabel.textContent = "{{__('Yes')}}";
        } else {
            toggleLabel.textContent = "{{__('No')}}";
        }
    });
});
</script>