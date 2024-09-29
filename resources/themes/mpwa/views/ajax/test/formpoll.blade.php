<div class="tab-pane fade show" id="pollmessage" role="tabpanel">
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
<input type="hidden" name="type" value="poll" />
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


                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
<script>
window.addEventListener('load', function() {
    // add button when click add button maximal 3 button
    $(document).ready(function() {
      
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
       
    });
});

</script>