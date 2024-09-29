<x-layout-dashboard title="{{__('Test Messages')}}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
    @if (session()->has('alert'))
        <x-alert>
            @slot('type', session('alert')['type'])
            @slot('msg', session('alert')['msg'])
        </x-alert>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2 border-0" role="alert">
    <div class="d-flex align-items-center">
        <div class="fs-3 text-danger"> <i class="fa-solid fa-circle-exclamation"></i> </div>
        <div class="ms-3">
            <p class="mb-0">{{__('The given data was invalid.')}}</p>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>* {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    @endif
    {{-- form --}}
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{__('Test Message')}}</h5>
                </div>
                @if (!session()->has('selectedDevice') || !session()->has('selectedDevice'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{__('Please select a device and a message to test')}}</li>
                        </ul>
                    </div>
                @else
                    <div class="card-body">
    <div class="row g-3">
<div class="col-lg-3">
    <ul class="nav nav-pills flex-column w-100 mt-4" role="tablist">
        <li class="nav-item mb-2" role="presentation">
            <a class="nav-link active d-flex align-items-center" data-bs-toggle="tab" href="#textmessage" role="tab" aria-selected="true">
                <i class="fa-solid fa-comment me-2"></i>
                <div class="tab-title">{{__('Text Message')}}</div>
            </a>
        </li>
        <li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#mediamessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-camera me-2"></i>
                <div class="tab-title">{{__('Media Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#stickermessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-note-sticky me-2"></i>
                <div class="tab-title">{{__('Sticker Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#pollmessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-square-poll-vertical me-2"></i>
                <div class="tab-title">{{__('Poll Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#listmessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-list me-2"></i>
                <div class="tab-title">{{__('List Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#locationmessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-location-dot me-2"></i>
                <div class="tab-title">{{__('Location Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#vcardmessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-address-card me-2"></i>
                <div class="tab-title">{{__('VCard Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#buttonmessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-circle-dot me-2"></i>
                <div class="tab-title">{{__('Button Message')}} (*)</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#templatemessage" role="tab" aria-selected="false">
                <i class="fa-solid fa-pen-fancy me-2"></i>
                <div class="tab-title">{{__('Template Message')}} (*)</div>
            </a>
        </li>
    </ul>
</div>

        <div class="col-lg-9">
            <div class="tab-content pt-4 w-100">
                @include('theme::ajax.test.formtext')
				@include('theme::ajax.test.formmedia')
				@include('theme::ajax.test.formsticker')
				@include('theme::ajax.test.formpoll')
				@include('theme::ajax.test.formlist')
				@include('theme::ajax.test.formlocation')
				@include('theme::ajax.test.formvcard')
				@include('theme::ajax.test.formbutton')
				@include('theme::ajax.test.formtemplate')
            </div>
        </div>
    </div>
</div>

                @endif
                <!--end row-->
            </div>
        </div>
    </div>
    </div>
    {{-- end form --}}
        </div>
</x-layout-dashboard>