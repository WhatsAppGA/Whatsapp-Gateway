<div class="tab-pane fade show" id="vcardmessage" role="tabpanel">
<label for="name" class="form-label">{{__('Name')}}</label>
<input type="text" name="name" class="form-control" placeholder="john wick" id="name" value="{{ $contact['displayName'] ?? '' }}" required />
<label for="phone" class="form-label">{{__('Number')}}</label>
<input type="tel" name="phone" class="form-control" placeholder="6281xxxxxxx" id="phone" inputmode="numeric" pattern="[0-9]*" value="{{ $waid ?? '' }}" required />
</div>
<script>
(function ($) {
    const numberInput = document.getElementById('phone');
    numberInput.addEventListener('input', function(e) {
        let inputValue = this.value.replace(/[^0-9]/g, '');
        this.value = inputValue;
    }, { passive: true });
})(jQuery);
</script>