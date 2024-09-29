<x-layout-dashboard title="{{__('Test Messages')}}">

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{__('Message')}}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('Test')}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    @if (session()->has('alert'))
        <x-alert>
            @slot('type', session('alert')['type'])
            @slot('msg', session('alert')['msg'])
        </x-alert>
    @endif
    @if ($errors->any())
        <div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>

                </div>
                <div class="ms-3">
                    <p>{{__('The given data was invalid.')}}</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
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
                <i class="bi bi-chat-dots-fill me-2"></i>
                <div class="tab-title">{{__('Text Message')}}</div>
            </a>
        </li>
        <li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#mediamessage" role="tab" aria-selected="false">
                <i class="bi bi-camera-fill me-2"></i>
                <div class="tab-title">{{__('Media Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#stickermessage" role="tab" aria-selected="false">
                <i class="bi bi-sticky-fill me-2"></i>
                <div class="tab-title">{{__('Sticker Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#pollmessage" role="tab" aria-selected="false">
                <i class="bi bi-blockquote-right me-2"></i>
                <div class="tab-title">{{__('Poll Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#listmessage" role="tab" aria-selected="false">
                <i class="bi bi-list-task me-2"></i>
                <div class="tab-title">{{__('List Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#locationmessage" role="tab" aria-selected="false">
                <i class="bi bi-geo-alt-fill me-2"></i>
                <div class="tab-title">{{__('Location Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#vcardmessage" role="tab" aria-selected="false">
                <i class="bi bi-credit-card-2-back-fill me-2"></i>
                <div class="tab-title">{{__('VCard Message')}}</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#buttonmessage" role="tab" aria-selected="false">
                <i class="bi bi-circle-fill me-2"></i>
                <div class="tab-title">{{__('Button Message')}} (*)</div>
            </a>
        </li>
		<li class="nav-item mb-2" role="presentation">
            <a class="nav-link d-flex align-items-center" data-bs-toggle="tab" href="#templatemessage" role="tab" aria-selected="false">
                <i class="bi bi-pen-fill me-2"></i>
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

</x-layout-dashboard>
