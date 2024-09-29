
<label for="message" class="form-label">{{__('Message')}}</label>
<textarea type="text" name="message" class="form-control" id="message" required>{{$message ?? ''}}</textarea>
<label for="footer" class="form-label">{{__('Footer message *optional')}}</label>
<input type="text" name="footer" class="form-control" id="footer" value="{{$footer ?? ''}}" >
 <label class="form-label">Image <span class="text-sm text-warbubg">*{{__('OPTIONAL')}}</span></label>
                   <div class="input-group">
                     <span class="input-group-btn">
                       <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                         <i class="fa fa-picture-o"></i> {{__('Choose')}}
                       </a>
                     </span>
                     <input id="thumbnail" class="form-control"  type="text" name="image" value="{{$image ?? ''}}" >
                   </div>
<button type="button" id="addbutton{{$id}}" class="btn btn-primary btn-sm mr-2 mt-4">{{__('Add Button')}}</button>
<button type="button" id="reduceButton{{$id}}" class="btn btn-danger btn-sm ml-2 mt-4">{{__('Reduce Button')}}</button>
<div class="button-area{{$id}}">
@if($buttons)
	@foreach ($buttons as $index => $butt)
	<div class="form-group buttoninput{{$id}}">
		<label for="button[{{ $index + 1 }}]" class="form-label">{{__('Button')}} {{ $index + 1 }}</label>
		<input type="text" name="button[{{ $index + 1 }}]" class="form-control" id="button[{{ $index + 1 }}]" value="{{$butt->buttonText->displayText}}" required="">
	</div>
	@endforeach
@endif
</div>
<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button2.js')}}"></script>
<script>
    var max_fields = 5; //maximum input boxes allowed
    var wrapper = $(".button-area{{$id}}"); //Fields wrapper
    var add_button = $("#addbutton{{$id}}"); //Add button ID
    var x = {{ count($buttons) ?? 0 }}; //initlal text box count

    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper).append('<div class="form-group buttoninput{{$id}}"><label for="button[' + x + ']" class="form-label">{{__("Button")}} ' + x + '</label><input type="text" name="button[' + x + ']" class="form-control" id="button[' + x + ']" required></div>');
        } else {
            toastr['warning']('{{__("Maximal 5 buttons")}}');
        }
    });

    $(document).on('click', '#reduceButton{{$id}}', function(e) {
        e.preventDefault();
        var lastButton = wrapper.find('.buttoninput{{$id}}:last');
        if (lastButton.length > 0) {
            lastButton.remove();
            x--;
        }
    });
</script>