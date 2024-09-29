<label class="form-label">{{__('Image Url')}} </label>
<div class="input-group media-area">
    <span class="input-group-btn">
        <a id="image-sticker" data-input="thumbnail-sticker" data-preview="holder" class="btn btn-primary text-white">
            <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
    </span>
    <input id="thumbnail-sticker" class="form-control" type="text" name="url" value="{{ $message->url ?? '' }}">
</div>
