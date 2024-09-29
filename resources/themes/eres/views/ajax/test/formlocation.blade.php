<div class="tab-pane fade show" id="locationmessage" role="tabpanel">
                    <form class="row g-3" action="{{ route('messagetest') }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">{{__('Sender')}}</label>
                            <input name="sender" value="{{ session()->get('selectedDevice')['device_body'] }}" type="text" class="form-control" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{__('Receiver Number')}}</label>
                            <textarea placeholder="628xxx|628xxx|628xxx" class="form-control" name="number" id="" cols="20" rows="2"></textarea>
                        </div>
						<input type="hidden" name="type" value="location" />
<div id="map" style="width:100%;height:400px;z-index:1;" class="shadow"></div>
<label for="latitude" class="form-label">{{__('Latitude')}}</label>
<input type="text" name="latitude" class="form-control" id="latitude" required>
<label for="longitude" class="form-label">{{__('Longitude')}}</label>
<input type="text" name="longitude" class="form-control" id="longitude" required>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-info btn-sm text-white px-5">{{__('Send Message')}}</button>
                        </div>
                    </form>
                </div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"></script>
<script>
var mymap;
var mmr;

function initializeMap() {
    mymap = L.map('map');
    mmr = L.marker([0, 0]);
    mmr.bindPopup('0,0');
    mmr.addTo(mymap);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
        foo: 'bar',
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mymap);
    mymap.on('click', onMapClick);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            sm(lat, lon, 12);
        }, function(error) {
            sm(-6.175110, 106.865036, 12);
        });
    } else {
        sm(-6.175110, 106.865036, 12);
    }
}

function onMapClick(e) {
    mmr.setLatLng(e.latlng);
    setui(e.latlng.lat, e.latlng.lng, mymap.getZoom());
}

function isll(num) {
    var val = parseFloat(num);
    if (!isNaN(val) && val <= 90 && val >= -90) return true;
    else return false;
}

function sm(lt, ln, zm) {
    setui(lt, ln, zm);
    mmr.setLatLng(L.latLng(lt, ln));
    mymap.setView([lt, ln], zm);
}

function setui(lt, ln, zm) {
    lt = Number(lt).toFixed(6);
    ln = Number(ln).toFixed(6);
    mmr.setPopupContent(lt + ',' + ln).openPopup();
    document.getElementById("latitude").value = lt;
    document.getElementById("longitude").value = ln;
}

function initMapIfNeeded() {
    if (!mymap) {
        initializeMap();
    }
}

document.querySelector('a[data-bs-toggle="tab"][href="#locationmessage"]').addEventListener('shown.bs.tab', function () {
    initMapIfNeeded();
    setTimeout(function() {
        mymap.invalidateSize();
    }, 100);
});
</script>