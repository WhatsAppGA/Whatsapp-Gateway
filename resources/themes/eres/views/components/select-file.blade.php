 <div class="input-group ">
      <span class="input-group-btn">
        <a id="imageforbutton" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
          <i class="fa fa-picture-o"></i> {{__('Choose')}}
        </a>
      </span>
      <input id="thumbnail" class="form-control"  type="text" name="url" readonly>
    </div>

<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js'}}"></script>
    <script>
          $('#imagetest').filemanager('file');
    </script>