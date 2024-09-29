 @if ($contacts->total() == 0)
     <div class="d-flex justify-content-center align-items-center">

         <x-no-data />
     </div>
 @endif

 <div href="app-emailread.html " style="width: 100%;">
     <div class="d-md-flex align-items-center email-message px-3 py-1">
         <div class="d-flex align-items-center email-actions">
             <p class="mb-0"><b>{{__('Name')}}</b>
             </p>
         </div>
         <div class="">
             <p class="mb-0"><b>{{__('Number Whatsapp')}} </b></p>
         </div>


     </div>
     @foreach ($contacts as $contact)
         <div href="app-emailread.html " id="contact-{{ $contact->id }}" style="width: 100%;">
             <div class="d-md-flex align-items-center email-message px-3 py-1">
                 <div class="d-flex align-items-center email-actions">
                     <p class="mb-0"><b>{{ $contact->name }}</b>
                     </p>
                 </div>
                 <div class="">
                     <p class="mb-0"><b>{{ $contact->number }} </b></p>
                 </div>
                 <button onclick="deleteContact({{ $contact->id }})" class="btn btn-sm text-danger">
                     <i class="bi bi-trash"></i>
                 </button>

             </div>
         </div>
     @endforeach
 </div>
