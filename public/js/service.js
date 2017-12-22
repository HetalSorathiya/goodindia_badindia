ProjectName.modules.Service = {
    init: function() {
        this.initMap();
    },
    initMap: function() {
        var geocoder;
        var map;
		
		if (LATITUDE === '') {
			var defaultLat = 37.98;
		} else {
			var defaultLat = LATITUDE;
		}
		if (LONGITUDE === '') {
			var defaultLon = 23.73;
		} else {
			var defaultLon = LONGITUDE;
		}
		
		
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(defaultLat, defaultLon);
        var mapOptions = {
            zoom: 12,
            center: latlng
        }

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);


        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: latlng
        });


        google.maps.event.addListener(marker, 'dragend', function(event) {

            latitude = event.latLng.lat();
            longitude = event.latLng.lng();

            jQuery('#srvc_lat').val(latitude);
            jQuery('#srvc_lon').val(longitude);

            var draglatlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
            geocoder.geocode({'latLng': draglatlng}, function(data, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var add = data[1].formatted_address; //this is the full address
                    jQuery('#srvc_address').val(add);
                } else {
                    alert('Geocode was not successful for finding address for following reason: ' + status);
                }
            });
        });


        $('#search-map').click(function() {

            var address = document.getElementById('srvc_address').value;
            geocoder.geocode({'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    jQuery('#srvc_lat').val(latitude);
                    jQuery('#srvc_lon').val(longitude);

                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);

                }
            });


        });


    }
     };

