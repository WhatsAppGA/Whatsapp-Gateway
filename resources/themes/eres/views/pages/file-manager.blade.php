<x-layout-dashboard title="{{__('File manager')}}">
	<div class="content-body">
            <!-- row -->
			<div class="container-fluid">
                <div class="row">
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
					<iframe src="{{url('/laravel-filemanager')}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                </div>
               
            </div>
        </div>

    <script>
	window.onload = function() {
		$(document).ready(function() {
			$('#server').on('change',function(){
			   let type = $('#server :selected').val();
				console.log(type);
				if(type === 'other'){
						$('.formUrlNode').removeClass('d-none')
					} else {
					$('.formUrlNode').addClass('d-none')

				}
			});
		});
	};
    </script>
</x-layout-dashboard>