<div class="tab-pane fade show" id="templatemessage" role="tabpanel">
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
<label for="message" class="form-label">{{__('Message')}}</label>
<textarea type="text" value="" name="message" class="form-control" id="message" required> </textarea>

<label for="footer" class="form-label">{{__('Footer message *optional')}}</label>
<input type="text" name="footer" class="form-control" id="footer" >

 <label class="form-label">{{__('Image')}} <span class="text-sm text-warbubg">*{{__('OPTIONAL')}}</span></label>
                   <div class="input-group">
                     <span class="input-group-btn">
                       <a id="image-template" data-input="thumbnail-template" data-preview="holder" class="btn btn-primary text-white">
                         <i class="fa fa-picture-o"></i> {{__('Choose')}}
                       </a>
                     </span>
                     <input id="thumbnail-template" class="form-control"  type="text" name="image" >
                   </div>
				   <input type="hidden" name="type" value="template" />
                   <label class="form-label">{{__('Template Button')}} <span class="text-sm text-warbubg">*{{__('Minimum 1 template button')}}</span></label><br>
<button type="button" id="addbutton" class="btn btn-primary btn-sm">{{__('Add Button')}}</button>
<button type="button" id="reduceButton" class="btn btn-danger btn-sm">{{__('Reduce Button')}}</button>
<div id="emailHelp" class="form-text">{{__('Template = Button Link or button call message')}},<br>
    {{__('if you want to send Call button you can fill like this : call|YourText|Yournumber')}}<br>
    {{__('if you want to send Link button, you can fill like this : url|YourText|YourLink')}}<br>

    {{__('type only have two value, url and call !')}}
</div>

<label for="template1" class="form-label">{{__('Template')}} 1</label>
<input type="text" name="template[1]" value="type|text|linkornumber" class="form-control" id="template[1]" required>

<div class="template-area">

</div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script>
window.addEventListener('load', function() {
    // add button when click add button maximal 3 button
    $(document).ready(function() {
       
        var max_fields = 3; //maximum input boxes allowed
        var wrapper = $(".template-area"); //Fields wrapper
        var add_button = $("#addbutton"); //Add button ID
        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="form-group templateinput"><label for="template[' + x + ']" class="form-label">{{__("Template")}} ' + x + '</label><input type="text" name="template[' + x + ']" class="form-control" id="template[' + x + ']" required></div>'); //add input box
            } else {
                toastr['warning']('{{__("Maximum template button is 3 !")}}');
            }
        });
        // reduce button when click
        $(document).on('click', '#reduceButton', function(e) {
            e.preventDefault();
            if(x > 1){
                $('.templateinput').last().remove();
                x--;
            }
        });
       
    });
});
</script>

