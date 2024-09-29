<x-layout-dashboard title="{{__('Schedule List')}}">
  
    <div class="app-content">
        <link href="{{asset('plugins/datatables/datatables.min.css')}}" rel="stylesheet">
        <link href="{{asset('plugins/select2/css/select2.css')}}" rel="stylesheet">
        <link href="{{asset('css/custom.css')}}" rel="stylesheet">
        <div class="content-wrapper">
            <div class="container">
                @if (session()->has('alert'))
                <x-alert>
                    @slot('type',session('alert')['type'])
                    @slot('msg',session('alert')['msg'])
                </x-alert>
             @endif
             @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul> 
    </div>
@endif
               
           
                
    
<div class="row mt-4">
  <div class="col">
      <div class="card">
          <div class="card-header d-flex justify-content-between">
          <h5 class="card-title">{{__('Schedule Blasts')}}</h5>

          <div class="d-flex">
          
            {{-- <form action="" method="POST">
              @method('delete')
              @csrf
              <button type="submit" class="btn btn-danger btn-sm">{{__('Delete All')}}</button>
            </form> --}}
          </div>
             
          </div>
          <div class="card-body">
              <table id="datatable1" class="display" style="width:100%">
                  <thead>
                      <tr>
                          <th>{{__('Total Receiver')}}</th>
                          <th>{{__('Type')}}</th>
                          <th>{{__('Sender')}}</th>
                          <th>{{__('Message')}}</th>
                          <th>{{__('Execute at')}}</th>
                          <th>{{__('Status')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                     @foreach ($schedule as $schedule)
                         
                     <tr>
                         <td>{{count(json_decode($schedule->numbers))}}</td>
                         <td><span class="badge badge-secondary badge-sm text-warning">{{$schedule->type}}</span></td>
                         <td><span class="badge badge-secondary badge-sm text-warning">{{$schedule->sender}}</span></td>
                         <td> <textarea name="" id="" cols="30" rows="2" disabled>{{Str::limit($schedule->text,100)}}</textarea> </td>
                         <td><span class="badge badge-sm text-primary">{{$schedule->datetime}}</span></td>
                         <td><span class="badge badge-{{$schedule->is_executed === 1 ? 'success' : 'danger'}}">{{$schedule->is_executed === 1 ? 'Executed' : 'Waiting' }}</span></td>
                         {{-- <td>
                             <div class="d-flex justify-content-center">
                                 <button class="btn btn-success btn-sm mx-3">{{__('Add to Tag')}}</button>
                                 <form action="{{route('contactDeleteOne',$contact->id)}}" method="POST">
                                  @method('delete')
                                  @csrf
                                     <input type="hidden" name="id" value="{{$contact->id}}">
                                     <button type="submit" name="delete" class="btn btn-danger btn-sm"><i class="material-icons">delete_outline</i>{{__('Delete')}}</button>
                                  </form>
                             </div>
                          </td> --}}
                      </tr>
                      @endforeach
                    

                  </tbody>
                  <tfoot></tfoot>
              </table>
          </div>
      </div>
  </div>

</div>






    <script src="{{asset('js/pages/datatables.js')}}"></script>
    <script src="{{asset('js/pages/select2.js')}}"></script>
    <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
  <script src="{{asset('js/autoreply.js')}}"></script>
</x-layout-dashboard>






