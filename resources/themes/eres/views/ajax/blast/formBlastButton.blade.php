<link href="{{asset('plugins/select2/css/select2.css')}}" rel="stylesheet">

<div class="card-body">
    <form class="flex flex-row" method="POST" enctype="multipart/form-data" id="formblast">
        @csrf
        <input type="hidden" name="type" value="button">
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
                    <div class="d-flex justify-content-around">
                        <div class="col">
                            <label for="delay" class="form-label">{{__('Delay')}}</label>
                            <input type="number" id="delay" min="1" max="60" name="delay" class="form-control">
                        </div>
                        <div class="col mx-2">
                            <label for="tipe" class="form-label">{{__('Type')}}</label>
                            <select name="tipe" id="tipe" class="form-control" style="width: 100%; height:200px;">
                               <option value="immediately">{{__('Immediately')}}</option>
                               <option value="schedule">{{__('Schedule')}}</option>
                                 
                              </select>
                        </div>
                        
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
                </div> --}}
                {{-- <div class="form-check mt-3 checkboxTag">
                    <input class="form-check-input" id="byTag" type="checkbox" name="byTag" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                        {{__('Send by tag')}}
                    </label>
                </div> --}}
                <div class="d-flex justify-content-around">
                    <div class="col">
                        <label for="button1" class="form-label">{{__('Button')}} 1</label>
                        <input type="text" name="button1" id="button1" class="form-control">
                       
                    </div>
                    <div class="col mx-2">
                        <label for="button2" class="form-label">{{__('Button')}} 2</label>
                        <input type="text" name="button2" id="button2" class="form-control">
        
                    </div>
                </div>
              
              
                <label for="footer" class="form-label">{{__('Footer Message')}}</label>
                <input type="text" name="footer" id="footer" class="form-control">
    
            </div>
            <div class="col-md-5 mx-2">
                <label for="inputPassword4" class="form-label">{{__('Message')}}</label>
                <textarea name="message" id="message" cols="30" rows="10" class="form-control">{{__('This is your message,, use {name} to get a name.')}}</textarea>
            </div>
         
        </div>

        <div class="mt-2" id="buttonblast">
            <button type="submit" id="buttonStartBlast" name="submit" class="btn btn-primary">{{__('Start Blast')}}</button>
        </div>
    </form>
</div>

<script src="{{asset('js/pages/select2.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
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

        if(!$('#sender').val()    || !$('#message').val() || !$('#button1').val() || !$('#button2').val() || !$('#footer').val() || !$('#tag').val() || !$('#delay').val()){
            return alert('{{__("Please fill all field needed!")}}');
        }
     
        if($('#tigpe').val() === 'schedule' && !$('#datetime2').val()){
            return alert('{{__("Please set datetime if the tipe message is schedule")}}');
        }

        let data;
        let typeReceipt
       
            data = {
                type : 'button',
                typeReceipt : 'tag',
                tag : $('#tag').val(),
                sender : $('#sender').val(),
                message : $('#message').val(),
                button1 : $('#button1').val(),
                button2 : $('#button2').val(),
                footer : $('#footer').val(),
                delay : $('#delay').val(),
                tipe : $('#tipe').val(),
                datetime : $('#datetime2').val(),
                
            }

       
        $('#buttonStartBlast').html(`<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                {{__('Prossess Blasting...')}}`)
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