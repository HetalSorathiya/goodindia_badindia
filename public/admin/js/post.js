ProjectName.modules.PostListing = {
    init: function() {
        this.selectAllCheckbox();
		this.map();
    },
    
    selectAllCheckbox: function() {
        $('#selectall').click(function() {
            if ($(this).is(':checked') === true) {
                $('.content-checkbox').prop('checked', true);
            } else {
                $('.content-checkbox').prop('checked', false);
            }
        });
    },
	
	map: function () {
        var geocoder;
        var map;
        var defaultLat = '';
        var defaultLon = '';
        if (LATITUDE === '') {
            defaultLat = 23.022505;
        } else {
            defaultLat = LATITUDE;
        }
        if (LONGITUDE === '') {
            defaultLon = 72.57136209999999;
        } else {
            defaultLon = LONGITUDE;
        }
        geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(defaultLat, defaultLon);

        var typeId = google.maps.MapTypeId.ROADMAP;

        //       var typeId = google.maps.MapTypeId.SATELLITE;

        var mapOptions = {
            zoom: 10,
            center: latlng,
            mapTypeId: typeId,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.TOP_LEFT
            },
            //mapTypeId: google.maps.MapTypeId.SATELLITE
        }
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: latlng
        });


        google.maps.event.addListener(marker, 'dragend', function (event) {

            latitude = event.latLng.lat();
            longitude = event.latLng.lng();

            jQuery('#post_lattitude').val(latitude);
            jQuery('#post_longitude').val(longitude);

            var google_link = 'https://maps.google.com/?q=@' + latitude + ',' + longitude;
            $("#view_on_google_map").attr('href', google_link);



            var draglatlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
            geocoder.geocode({'latLng': draglatlng}, function (data, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var add = data[1].formatted_address; //this is the full address
                    jQuery('#post_location').val(add);
                } else {
                    alert('Geocode was not successful for finding address for following reason: ' + status);
                }
            });
        });

        $('#current-location').click(function () {
            // Try HTML5 geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = new google.maps.LatLng(position.coords.latitude,
                            position.coords.longitude);

                    geocoder.geocode({'latLng': pos}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                jQuery('#post_location').val(results[1].formatted_address);
                            }
                        } else {
                            //alert("Geocoder failed due to: " + status);
                        }
                    });


                    jQuery('#post_lattitude').val(position.coords.latitude);
                    jQuery('#post_longitude').val(position.coords.longitude);

                    var google_link = 'https://maps.google.com/?q=@' + position.coords.latitude + ',' + position.coords.longitude;
                    $("#view_on_google_map").attr('href', google_link);

                    map.setCenter(pos);
                    marker.setPosition(pos);

                    //map.setCenter(pos);
                });
            } else {
                alert(" Browser doesn't support Geolocation");
                // Browser doesn't support Geolocation
                //handleNoGeolocation(false);
            }

        });

        $('#search-map').click(function () {

            var address = document.getElementById('post_location').value;
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    jQuery('#post_lattitude').val(latitude);
                    jQuery('#post_longitude').val(longitude);

                    var google_link = 'https://maps.google.com/?q=@' + latitude + ',' + longitude;
                    $("#view_on_google_map").attr('href', google_link);

                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    /*
                     var marker = new google.maps.Marker({
                     map: map,
                     position: results[0].geometry.location
                     });
                     */
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);

                }
            });
        });
    },

};