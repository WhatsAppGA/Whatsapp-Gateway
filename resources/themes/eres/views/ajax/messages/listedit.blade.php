<label for="message" class="form-label">{{__('Message')}}</label>
<textarea type="text" name="message" class="form-control" id="message" required>{{$message ?? ''}}</textarea>
<label for="buttontext" class="form-label">{{__('Button')}} </label>
<input type="text" name="buttontext" class="form-control" id="buttonlist" value="{{$buttontext ?? ''}}">
<label for="name" class="form-label">{{__('Name List')}}</label>
<input type="text" name="name" class="form-control" id="namelist" value="{{$namelist ?? ''}}" required>
<label for="footer" class="form-label">{{__('Footer')}}</label>
<input type="text" name="footer" class="form-control" id="footer" value="{{$footer ?? ''}}" required>
{{-- create input section and each section have list menu --}}
<label for="titlelist" class="form-label">{{__('Title List')}}</label>
<input type="text" name="title" class="form-control" id="titlelist" value="{{$sections[0]->title ?? ''}}" required>
<button type="button" id="addlist{{$id}}" class="btn btn-primary btn-sm mt-4">{{__('Add List')}}</button>
<button type="button" id="reducelist{{$id}}" class="btn btn-danger btn-sm mt-4">{{__('Reduce List')}}</button><br>

<div class="area-list{{$id}}">
@if(isset($sections[0]->rows))
	@foreach ($sections[0]->rows as $index => $rows)
	<div class="form-group listinput{{$id}}">
		<label for="list{{$index + 1}}" class="form-label">{{__('List')}} {{$index + 1}}</label>
		<input type="text" name="list[{{$index + 1}}]" class="form-control" id="list{{$index + 1}}" value="{{$rows->title}}" required>
	</div>
	@endforeach
@endif
</div>

<script>
    // add list when click,maximal 5 list
    $(document).ready(function() {

        var max_fields = 5; //maximum input boxes allowed
        var wrapper = $(".area-list{{$id}}"); //Fields wrapper
        var add_button = $("#addlist{{$id}}"); //Add button ID
		@if(isset($sections[0]->rows))
        var x = {{ count($sections[0]->rows) ?? 0 }}; 
		@else
		var x = 0;
		@endif
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="form-group listinput{{$id}}"><label for="list' + x +
                    '" class="form-label">{{__("List")}} ' + x + '</label><input type="text" name="list[' +
                    x + ']" class="form-control" id="list' + x + '" required></div>'); //add input box
            } else {
                toastr['warning']('{{__("Maximal 5 list")}}');
            }
        });
        // reduce list when click
        $(document).on('click', '#reducelist{{$id}}', function(e) {
			e.preventDefault();
			var lastButton = wrapper.find('.listinput{{$id}}:last');
			if (lastButton.length > 0) {
				lastButton.remove();
				x--;
			}
		});
    });
</script>
