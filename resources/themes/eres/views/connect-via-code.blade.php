<x-layout-dashboard title="{{__('Scan')}} {{ $number->body }}">
    <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card widget widget-stats-large">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="widget-stats-large-chart-container">
                            <div class="card-header logoutbutton">
								<h5 class="">{{ __('Whatsapp Account :number', ['number' => $number->body]) }}</h5>
								<div class="text-info">{{__('Dont leave your phone before connencted')}}</div>
                            </div>
                            <div class="card-body">
                                <div id="apex-earnings"></div>
                                <div class="imageee text-center">
                                    @if (Auth::user()->is_expired_subscription)
                                        {{-- text --}}
                                        <img src="{{ asset('images/other/expired.png') }}" height="300px"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/images/waiting.jpg') }}" height="300px"
                                            alt="">
                                    @endif
                                </div>
                                <div class="statusss text-center">
                                    @if (Auth::user()->is_expired_subscription)
                                        <button class="btn btn-danger   " type="button" disabled>
											{{__('Your subscription is expired. Please renew your subscription.')}}
                                        </button>
                                    @else
                                        <button class="btn btn-primary" type="button" disabled>
                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                aria-hidden="true"></span>
                                            {{__('Witing For node server..')}}
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="widget-stats-large-info-container">
                            <div class="card-header">
                                <h5 class="card-title">{{__('Whatsapp Info')}}<span
                                        class="badge badge-info badge-style-light">{{__('Updated 5 min ago')}}</span>
                                </h5>
                            </div>
                            <div class="card-body account">

                                <ul class="list-group account list-group-flush">
                                    <li class="list-group-item name">{{__('Name :')}} </li>
                                    <li class="list-group-item number">{{__('Number :')}} </li>
                                    <li class="list-group-item device">{{__('Device :')}} </li>

                                </ul>
                                {{-- <div class="card bg-dark text-white">
                                    <div class="card-body" style="height: 300px; overflow-y: scroll;">
                                        <p class="card-text">{{__('Log :')}}</p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
</x-layout-dashboard>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
    integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
</script>
<script>
    // if subscription not expired
    const is_expired_subscription = '{{ Auth::user()->is_expired_subscription }}';
    if (!is_expired_subscription) {
        let socket;
        let device = '{{ $number->body }}';
        if ('{{ env('TYPE_SERVER') }}' === 'hosting') {
            socket = io();
        } else {
            socket = io('{{ env('WA_URL_SERVER') }}', {
                transports: ['websocket', 'polling', 'flashsocket']
            });
        }


        socket.emit('ConnectViaCode', '{{ $number->body }}')
        socket.on('code', ({
            token,
            data,
            message
        }) => {
            if (token == device) {
                let code = data
                $('.imageee').html(` <h2 >${code}</h2>`)
                let count = 0;
                $('.statusss').html(`  <button class="btn btn-warning" type="button" disabled>
                                                     <span class="" role="status" aria-hidden="true"></span>
                                                   ${message}
                                                 </button>`)

            }

        })


        socket.on('connection-open', ({
            token,
            user,
            ppUrl
        }) => {
            if (token == device) {

                $('.name').html(`{{__('Name :')}} ${user.name}`)
                $('.number').html(`{{__('Number :')}} ${user.id}`)
                $('.device').html(`{{__('Device / Token : Not detected -')}} ${token}`)
                $('.imageee').html(` <img src="${ppUrl}" height="300px" alt="">`)
                $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   {{__('Connected')}}
                                                </button>`)
                $('.logoutbutton').html(` <button class="btn btn-danger" class="logout"  id="logout"  onclick="logout({{ $number->body }})">
                                                   {{__('Logout')}}
                                               </button>`)
            }
        })

        socket.on('Unauthorized', ({
            token
        }) => {
            if (token == device) {
                $('.statusss').html(`  <button class="btn btn-danger" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   {{__('Unauthorized')}}
                                                </button>`)
            }

        })
        socket.on('message', ({
            token,
            message
        }) => {
            if (token == device) {
                $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   ${message}
                                                </button>`);
                //if there is text connection close in message
                if (message.includes('Connection closed')) {
                    // count 5 second
                    let count = 5;
                    //set interval
                    let interval = setInterval(() => {
                        //if count is 0
                        if (count == 0) {
                            //clear interval
                            clearInterval(interval);
                            //reload page
                            location.reload();
                        }
                        //change text
                        $('.statusss').html(`  <button class="btn btn-success" type="button" disabled>
                                                    <span class="" role="status" aria-hidden="true"></span>
                                                   ${message} {{__('in')}} ${count} {{__('second')}}
                                                </button>`);
                        //count down
                        count--;
                    }, 1000);

                }
            }



        });




        function logout(device) {
            socket.emit('LogoutDevice', device)
        }
    }
</script>
