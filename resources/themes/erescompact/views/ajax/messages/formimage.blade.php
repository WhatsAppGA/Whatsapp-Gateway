<label class="form-label">{{__('Image')}}</label>
<div class="input-group media-area">
    <span class="input-group-btn">
        <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
            <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
    </span>
    <input id="thumbnail" class="form-control" type="text" name="image">
</div>

<label for="caption" class="form-label">{{__('Caption')}}</label>
<textarea type="text" name="caption" class="form-control" id="caption" required> </textarea>

<script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script>
    $('#image').filemanager('file')
</script>
