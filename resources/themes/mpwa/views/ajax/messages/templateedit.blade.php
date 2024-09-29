<label for="message" class="form-label">{{__('Message')}}</label>
<textarea type="text" value="" name="message" class="form-control" id="message" required>{{$message ?? ''}}</textarea>

<label for="footer" class="form-label">{{__('Footer message *optional')}}</label>
<input type="text" name="footer" class="form-control" id="footer" value="{{$footer ?? ''}}" >

 <label class="form-label">{{__('Image')}} <span class="text-sm text-warbubg">*{{__('OPTIONAL')}}</span></label>
                   <div class="input-group">
                     <span class="input-group-btn">
                       <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                         <i class="fa fa-picture-o"></i> {{__('Choose')}}
                       </a>
                     </span>
                     <input id="thumbnail" class="form-control" type="text" name="image" value="{{$image ?? ''}}" >
                   </div>
                   <label class="form-label">Template Button <span class="text-sm text-warbubg">*{{__('Minimum 1 template button')}}</span></label><br>
<button type="button" id="addbutton{{$id}}" class="btn btn-primary btn-sm">{{__('Add Button')}}</button>
<button type="button" id="reduceButton{{$id}}" class="btn btn-danger btn-sm">{{__('Reduce Button')}}</button>
<div id="emailHelp" class="form-text">{{__('Template = Button Link or button call message')}},<br>
    {{__('if you want to send Call button you can fill like this : call|YourText|Yournumber')}}<br>
    {{__('if you want to send Link button, you can fill like this : url|YourText|YourLink')}}<br>

    {{__('type only have two value, url and call !')}}
</div>
@if(!$templates)
<label for="template1" class="form-label">{{__('Template 1')}}</label>
<input type="text" name="template[1]" value="type|text|linkornumber" class="form-control" id="template[1]" required>
@endif
<div class="template-area{{$id}}">
@if($templates)
	@foreach ($templates as $templ)
	<div class="form-group templateinput{{$id}}">
		<label for="template[{{$templ['index']}}]" class="form-label">{{__('Template')}} {{$templ['index']}}</label>
		<input type="text" name="template[{{$templ['index']}}]" class="form-control" id="template[{{$templ['index']}}]" value="{{$templ['type']}}" required>
	</div>
	@endforeach
@endif
</div>

<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button2.js')}}"></script>
<script>

        var max_fields = 3; //maximum input boxes allowed
        var wrapper = $(".template-area{{$id}}"); //Fields wrapper
        var add_button = $("#addbutton{{$id}}"); //Add button ID
        var x = {{ count($templates) ?? 0 }}; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="form-group templateinput{{$id}}"><label for="template[' + x + ']" class="form-label">{{__("Template")}} ' + x + '</label><input type="text" name="template[' + x + ']" class="form-control" id="template[' + x + ']" required></div>'); //add input box
            } else {
                toastr['warning'](`Maximum template button is ${max_fields} !`);
            }
        });
        $(document).on('click', '#reduceButton{{$id}}', function(e) {
			e.preventDefault();
			var lastButton = wrapper.find('.templateinput{{$id}}:last');
			if (lastButton.length > 0) {
				lastButton.remove();
				x--;
			}
		});

</script>

