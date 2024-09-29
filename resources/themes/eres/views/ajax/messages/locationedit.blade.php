<div id="map" style="width:100%;height:400px;z-index:1;" class="shadow"></div>
<label for="latitude" class="form-label">{{__('Latitude')}}</label>
<input type="text" name="latitude" class="form-control" id="latitude" value="{{$message->degreesLatitude ?? ''}}" required>
<label for="longitude" class="form-label">{{__('Longitude')}}</label>
<input type="text" name="longitude" class="form-control" id="longitude" value="{{$message->degreesLongitude ?? ''}}" required>


<script>
$(document).ready(function() {
	var mymap = L.map('map');
	var mmr = L.marker([0, 0]);
	mmr.bindPopup('0,0');
	mmr.addTo(mymap);
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
		foo: 'bar',
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(mymap);
	mymap.on('click', onMapClick);

	function isll(num) {
		var val = parseFloat(num);
		if (!isNaN(val) && val <= 90 && val >= -90) return true;
		else return false;
	}

	function onMapClick(e) {
		mmr.setLatLng(e.latlng);
		setui(e.latlng.lat, e.latlng.lng, mymap.getZoom());
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
	
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			sm({{$message->degreesLatitude ?? '-6.175110'}}, {{$message->degreesLongitude ?? '106.865036'}}, 12);
		}, function(error) {
			sm({{$message->degreesLatitude ?? '-6.175110'}}, {{$message->degreesLongitude ?? '106.865036'}}, 12);
		});
	} else {
		sm({{$message->degreesLatitude ?? '-6.175110'}}, {{$message->degreesLongitude ?? '106.865036'}}, 12);
	}
});
</script>