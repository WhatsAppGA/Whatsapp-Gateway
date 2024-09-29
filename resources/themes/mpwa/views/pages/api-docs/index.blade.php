<x-layout-dashboard title="API Docs">

    {{-- <link href="{{asset('plugins/datatables/datatables.min.css')}}" rel="stylesheet"> --}}
    {{-- <link href="{{asset('plugins/select2/css/select2.css')}}" rel="stylesheet"> --}}

    <style>
        .tab-content {
            margin-top: 20px;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            background-color: #fff;
            color: #333;
        }
    </style>

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">API</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Documentation</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    {{-- API DOCUMENTATION --}}
    <div class="container-fluid">
        <div class="row flex-wrap">
            <div class="col-lg-3 mb-4">
                <ul class="nav nav-tabs flex-column w-100 mt-4 " role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#sendmessage" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Message</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendmedia" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Media</div>
                            </div>
                        </a>
                    </li>
					<li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendsticker" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Sticker</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendpoll" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Poll Message</div>
                            </div>
                        </a>
                    </li>
                    {{-- send button --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendbutton" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Button</div>
                            </div>
                        </a>
                    </li>
                    {{-- Send Template --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendtemplate" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-title">Send Template Button</div>
                            </div>
                        </a>
                    </li>
                    {{-- Send list --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendlist" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Send List Message</div>
                            </div>
                        </a>
                    </li>
					{{-- Send Location --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendlocation" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Send Location</div>
                            </div>
                        </a>
                    </li>
					{{-- Send Vcard --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#sendvcard" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Send Vcard</div>
                            </div>
                        </a>
                    </li>

                    {{-- Generate QR Code --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#generateqr" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class='fa fa-reply'></i></div>
                                <div class="tab-title">Generate QR Code </div>
                            </div>
                        </a>
                    </li>
                    {{-- Disconnect device --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#disconnectdevice" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class='fa fa-reply'></i></div>
                                <div class="tab-title">Disconnect device </div>
                            </div>
                        </a>
                    </li>
					{{-- Create user --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#createuser" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Create User</div>
                            </div>
                        </a>
                    </li>
					{{-- User info --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#userinfo" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">User Info</div>
                            </div>
                        </a>
                    </li>
					{{-- Device info --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#deviceinfo" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Device Info</div>
                            </div>
                        </a>
                    </li>
					{{-- Check Number --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#checknumber" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">

                                <div class="tab-title">Check Number</div>
                            </div>
                        </a>
                    </li>
                    {{-- webhook --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " data-bs-toggle="tab" href="#examplewebhook" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class='fa fa-reply'></i></div>
                                <div class="tab-title">Example Webhook </div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="col-lg-9 mb-4">
                <div class="tab-content pt-4 w-100">
                    {{-- send message --}}
                    @include('theme::pages.api-docs.send-message')
                    {{-- end send message --}}
                    {{-- send media --}}
                    @include('theme::pages.api-docs.send-media')
					@include('theme::pages.api-docs.send-sticker')
                    @include('theme::pages.api-docs.send-poll')
                    {{-- end send media --}}
                    {{-- send button --}}
                    @include('theme::pages.api-docs.send-button')
                    {{-- end send button --}}
                    {{-- send template --}}
                    @include('theme::pages.api-docs.send-template')
                    {{-- end send template --}}
                    {{-- send list --}}
                    @include('theme::pages.api-docs.send-list')
                    {{-- end send list --}}
					{{-- send location --}}
                    @include('theme::pages.api-docs.send-location')
                    {{-- end send location --}}
					{{-- send vcard --}}
                    @include('theme::pages.api-docs.send-vcard')
                    {{-- end send vcard --}}

                    {{-- generate qr code --}}
                    @include('theme::pages.api-docs.generateqr')
                    @include('theme::pages.api-docs.disconnectdevice')
                    {{-- end generate qr code --}}
					{{-- create user --}}
                    @include('theme::pages.api-docs.createuser')
					{{-- user info --}}
                    @include('theme::pages.api-docs.user-info')
					{{-- device info --}}
                    @include('theme::pages.api-docs.device-info')
					{{-- check number --}}
                    @include('theme::pages.api-docs.check-number')
                    {{-- end create user --}}
                    {{-- example webhook --}}
                    @include('theme::pages.api-docs.examplewebhook')
                    {{-- end example webhook --}}

                </div>






</x-layout-dashboard>
