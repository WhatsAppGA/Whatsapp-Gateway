
<div class="page">
    <div class="marvel-device nexus5">
      <div class="top-bar"></div>
      <div class="sleep"></div>
      <div class="volume"></div>
      <div class="camera"></div>
      <div class="screen">
        <div class="screen-container">
          <div class="status-bar">
            <div class="time"></div>
            <div class="battery">
              <i class="bi bi-battery-half"></i>
            </div>
            <div class="network">
              <i class="bi bi-reception-4"></i>
            </div>
            <div class="wifi">
              <i class="bi bi-wifi-2"></i>
            </div>
            <div class="star">
              <i class="bi bi-star-fill"></i>
            </div>
          </div>
          <div class="chat">
            <div class="chat-container">
              <div class="user-bar">
                <div class="back">
                  <i class="bi bi-arrow-left"></i>
                </div>
                <div class="avatar">
                  <img src="{{asset('assets/images/avatars/avatar-1.png')}}" alt="Avatar">
                </div>
                <div class="name">
                  <span>{{__('M Pedia')}}</span>
                  <span class="status">{{__('online')}}</span>
                </div>
                <div class="actions more">
                  <i class="bi bi-three-dots-vertical"></i>
                </div>
                <div class="actions attachment">
                  <i class="bi bi-paperclip"></i>
                </div>
                <div class="actions">
                  <i class="bi bi-telephone-fill"></i>
                </div>
              </div>
              <div class="conversation">
                <div class="conversation-container">
                 
                  <div class="message received">
                   {{$keyword}}
                    <span class="metadata"><span class="time"></span></span>
                  </div>
                  <div class="message sent">
                  @if($image != null)
                    <img src="{{$image}}" alt="" width="250px" height="150px"><br>
                    @endif
                 
                   {{$message}}<br>
                   <span class="footertext">{{$footer}}</span>
                   <hr>
                   @foreach($templates as $template)
                   <?php
                 
  if(property_exists($template,'urlButton')){
                   $button = [
                       'icon' => 'bi bi-box-arrow-up-right',
                       'type' => 'url',
                       'text' => $template->urlButton->displayText
                   ] ;
                   } else {
                   $button = [
                       'icon' => 'bi bi-telephone',
                       'type' => 'call',
                       'text' => $template->callButton->displayText
                   ] ;
                   }
                   
                   
                   
                  ?>
                    <button style="padding: 10px; border :0px; color:blue;" class="rounded text-dark  bg-white">
                   @if ($button['type'] == 'call')
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                  </svg>
                       @else
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                        <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                      </svg>
                   @endif
                        {{$button['text']}}
                    </button><br>
                   @endforeach 
                   
                   <br>
                 
                    <span class="metadata">
                        <span class="time"></span><span class="tick"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" id="msg-dblcheck-ack" x="2063" y="2076"><path d="M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z" fill="#4fc3f7"/></svg></span>
                    </span>
                  </div>
                 
                </div>
                <form class="conversation-compose">
                  <div class="emoji">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" id="smiley" x="3147" y="3209"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.153 11.603c.795 0 1.44-.88 1.44-1.962s-.645-1.96-1.44-1.96c-.795 0-1.44.88-1.44 1.96s.645 1.965 1.44 1.965zM5.95 12.965c-.027-.307-.132 5.218 6.062 5.55 6.066-.25 6.066-5.55 6.066-5.55-6.078 1.416-12.13 0-12.13 0zm11.362 1.108s-.67 1.96-5.05 1.96c-3.506 0-5.39-1.165-5.608-1.96 0 0 5.912 1.055 10.658 0zM11.804 1.01C5.61 1.01.978 6.034.978 12.23s4.826 10.76 11.02 10.76S23.02 18.424 23.02 12.23c0-6.197-5.02-11.22-11.216-11.22zM12 21.355c-5.273 0-9.38-3.886-9.38-9.16 0-5.272 3.94-9.547 9.214-9.547a9.548 9.548 0 0 1 9.548 9.548c0 5.272-4.11 9.16-9.382 9.16zm3.108-9.75c.795 0 1.44-.88 1.44-1.963s-.645-1.96-1.44-1.96c-.795 0-1.44.878-1.44 1.96s.645 1.963 1.44 1.963z" fill="#7d8489"/></svg>
                  </div>
                  <input class="input-msg" name="input" placeholder="{{__('Type a message')}}" autocomplete="off" autofocus disabled></input>
                  <div class="photo">
                    <i class="bi bi-camera"></i>
                  </div>
                  <button class="send">
                      <div class="circle">
                        <i class="bi bi-send"></i>
                      </div>
                    </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>