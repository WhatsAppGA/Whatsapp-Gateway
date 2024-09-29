<link href="{{asset('plugins/select2/css/select2.css')}}" rel="stylesheet">

<div class="card-body">
    <form class="flex flex-row" method="POST" enctype="multipart/form-data" id="formblast">
        @csrf
        <input type="hidden" name="type" value="text">
        {{-- <div class="col-md-12"> --}}
            <label for="textmessage" class="form-label">{{__('Sender')}}</label>
            <select name="sender" id="sender" class="form-control" style="width: 100%;" required>
               @foreach ($numbers as $number)
               <option value="{{$number->body}}">{{$number->body}}</option>
               @endforeach
               
            </select>
        {{-- </div> --}}
        <div class="d-flex justify-content-between">

            <div class="col-md-7">
                {{-- <div class="thisselect">
                    <label for="inputEmail4" class="form-label">{{__('Numbers')}}</label>
    
                    <select name="listnumber[]" id="lists" class="form-control" style="width: 100%; height:200px;" multiple="multiple" required>
                      @foreach ($contacts as $contact)
                          
                      <option value="{{$contact->number}}">{{$contact->number}} ( {{$contact->name}} )</option>
                      @endforeach
                       
                    </select>
                </div> --}}
                <div>

                    <div class="tagsOption ">
                        <label for="inputEmail4" class="form-label">{{__('Tag Lists')}}</label>
                        <select name="tag" id="tag" class="form-control" style="width: 100%; height:200px;" required>
                          @foreach ($tags as $tag)
                              
                          <option value="{{$tag->id}}">{{$tag->name}}</option>
                          @endforeach
                           
                        </select>
                </div>
                <label for="delay" class="form-label">{{__('Delay')}}</label>
                <input type="number" id="delay" min="1" max="60" name="delay" class="form-control">
                <div class="col">
                    <label for="tipe" class="form-label">{{__('Type')}}</label>
                    <select name="tipe" id="tipe" class="form-control" style="width: 100%; height:200px;">
                       <option value="immediately">{{__('Immediately')}}</option>
                       <option value="schedule">{{__('Schedule')}}</option>
                         
                      </select>
                </div>
                <div class="col d-none" id="datetime">
            
                    <label for="datetime" class="form-label">{{__('Date Time')}}</label>
                    <input type="datetime-local" id="datetime2"  name="datetime" class="form-control">
                </div>
                </div>
                {{-- <div class="form-check mt-3 checkboxAll">
                    <input class="form-check-input" id="all" type="checkbox" name="all" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        {{__('Send to All numbers ( in contacts page )')}}
                    </label>
                </div>
                <div class="form-check mt-3 checkboxTag">
                    <input class="form-check-input" id="byTag" type="checkbox" name="byTag" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        {{__('Send by tag')}}
                    </label>
                </div> --}}
                <label class="form-label mt-4">{{__('Image')}}</label>
                <div class="input-group ">
                  <span class="input-group-btn">
                    <a id="image" data-input="thumbnail" data-preview="holder" class="btn btn-success text-white">
                      <i class="fa fa-picture-o"></i> {{__('Choose')}}
                    </a>
                  </span>
                  <input id="thumbnail" class="form-control imagee"  type="text" name="image" readonly>
                </div>
    
            </div>
            <div class="col-md-5 m-4">
                <label for="inputPassword4" class="form-label">{{__('Message')}}</label>
                <textarea name="message" id="message" cols="30" rows="10" class="form-control">{{__('This is your message,, use {name} to get a name.')}}</textarea>
            </div>
         
        </div>

        <div class="mt-0" id="buttonblast">
            <button type="submit" id="buttonStartBlast" name="submit" class="btn btn-primary">{{__('Start Blast')}}</button>
        </div>
    </form>
</div>

<script src="{{asset('js/pages/select2.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script>
  $('#image').filemanager('file')
</script>
                  
<script>
    $('#tipe').on('change',function(){
    
    if($(this).val() === 'schedule'){
        $('#datetime').removeClass('d-none')
    } else {
     $('#datetime').addClass('d-none')
    }
 })
     

     
      $('#buttonStartBlast').click((e)=> {
        e.preventDefault();
          let selected = []
        $('#lists option:selected').each(function() {
            selected.push($(this).val())
        });

        if(!$('#sender').val()    || !$('#message').val() || !$('.imagee').val()  || !$('#tag').val() || !$('#delay').val() ){
            return alert('{{__("Please fill all field needed!")}}');
        }
        if($('#tipe').val() === 'schedule' && !$('#datetime2').val()){
            return alert('{{__("Please set datetime if the tipe message is schedule")}}');
        }
        const img = $('.imagee').val();
        const arr = img.split('.');
        const allowed = ['jpg','png','jpeg'];
        const ext = arr[arr.length - 1 ];
        if(!allowed.includes(ext)){
            return alert('{{__("Allowed extensions are jpg,png,and jpeg!")}}')
        }
        //  if(checkboxAll === 0 && checkboxTag === 0 && selected.length === 0){
        //     return alert('Please fill number or select all, or select the tag!');
        // } 

        let data;
        let typeReceipt
      
            data = {
                type : 'image',
                typeReceipt : 'tag',
                tag : $('#tag').val(),
                sender : $('#sender').val(),
                message : $('#message').val(),
                image : $('.imagee').val(),
                delay : $('#delay').val(),
                tipe : $('#tipe').val(),
                datetime : $('#datetime2').val()
            }

      
        $('#buttonStartBlast').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>{{__('Prossess Blasting...')}}');
       $.ajax({
           method : 'POST',
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           url : '{{route('blast')}}',
           data : data,
           dataType : 'json',
           success : (result) => {
             //  return console.log(result)
           window.location = ''
           },
           error : (err) => {
                console.log(err);
           }
       })
      })
    
    
</script>