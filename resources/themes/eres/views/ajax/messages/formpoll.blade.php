<label for="name" class="form-label">{{__('Name / Question')}}</label>
<textarea type="text" name="name" class="form-control" id="name" required> </textarea>
<label for="countable" class="form-label">{{__('Only one answer per number')}}</label>
<select class="default-select form-control wide" id="countable" name="countable">
    <option selected value="1">{{__('Yes')}}</option>
    <option value="0">{{__('No')}}</option>
</select>
<div class="poll-area">
    
</div>
<button type="button" id="addoption" class="btn btn-primary btn-sm mr-2 mt-4">+ {{__('Option')}}</button>
<button type="button" id="reduceoption" class="btn btn-danger btn-sm ml-2 mt-4">- {{__('Option')}}</button>

<script>
(function ($) {
        var max_fields = 5; //maximum input boxes allowed
        var wrapper = $(".poll-area"); //Fields wrapper
        var add_button = $("#addoption"); //Add button ID
        var x = 0; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment

                $(wrapper).append('<div class="form-group optioninput"><label for="option[' + x + ']" class="form-label">{{__("Option")}} ' + x + '</label><input type="text" name="option[' + x + ']" class="form-control" id="option[' + x + ']" required></div>'); //add input box
            } else {
                toastr['warning']('{{__("Maximal 5 option")}}');
            }
        });
        // reduce button when click
        $(document).on('click', '#reduceoption', function(e) {
            e.preventDefault();
           if(x > 0){

            $('.optioninput').last().remove();
            x--;
           }
        });

})(jQuery);

</script>