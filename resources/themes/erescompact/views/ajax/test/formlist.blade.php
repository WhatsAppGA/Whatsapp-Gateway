<div class="tab-pane fade show" id="listmessage" role="tabpanel">
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
						<input type="hidden" name="type" value="list" />
<label for="message" class="form-label">{{__('Message')}}</label>
<textarea type="text" name="message" class="form-control" id="message" required> </textarea>
<label for="buttontext" class="form-label">{{__('Button')}} </label>
<input type="text" name="buttontext" class="form-control" id="buttonlist">
<label for="name" class="form-label">{{__('Name List')}}</label>
<input type="text" name="name" class="form-control" id="namelist" required>
{{-- create input section and each section have list menu --}}
<label for="ttile" class="form-label">{{__('Title List')}}</label>
<input type="text" name="title" class="form-control" id="titlelist" required>
<button type="button" id="addlist" class="btn btn-primary btn-sm mt-4">{{__('Add List')}}</button>
<button type="button" id="reducelist" class="btn btn-danger btn-sm mt-4">{{__('Reduce List')}}</button><br>

<div class="area-list">

</div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
<script>
window.addEventListener('load', function() {
    // add list when click,maximal 5 list
    $(document).ready(function() {

        var max_fields = 5; //maximum input boxes allowed
        var wrapper = $(".area-list"); //Fields wrapper
        var add_button = $("#addlist"); //Add button ID
        var x = 0; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="form-group listinput"><label for="list' + x +
                    '" class="form-label">{{__("List")}} ' + x + '</label><input type="text" name="list[' +
                    x + ']" class="form-control" id="list' + x + '" required></div>'); //add input box
            } else {
                toastr['warning']('{{__("Maximal 5 list")}}');
            }
        });
        // reduce list when click
        $(document).on('click', '#reducelist', function(e) {
            e.preventDefault();
            if (x > 0) {
                $('.listinput').last().remove();
                x--;
            }
        });
    });
});
</script>
