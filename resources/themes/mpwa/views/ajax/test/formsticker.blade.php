<div class="tab-pane fade show" id="stickermessage" role="tabpanel">
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


<label class="form-label">{{__('Image Url')}} </label>
<div class="input-group media-area">
    <span class="input-group-btn">
        <a id="image-sticker" data-input="thumbnail-sticker" data-preview="holder" class="btn btn-primary text-white">
            <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
    </span>
    <input id="thumbnail-sticker" class="form-control" type="text" name="url">
</div>
<input type="hidden" name="type" value="sticker" />
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>