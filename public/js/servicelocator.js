ProjectName.modules.Servicelocator = {
    init: function() {
        this.initMap();
		this.changeSelect();
    },
	changeSelect : function() {
		$('#srvc_location').change( function(){
			$('#servicelocatorfrm').submit();
		});
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
            zoom: 10,
            center: latlng
        }
		
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		
		
		if (MARKERLATITUDE != '') {
			if (MARKERLONGITUDE != '') {
			
				var contentString = MARKERCONTENT;

				  var infowindow = new google.maps.InfoWindow({
					  content: contentString
				  });
			
				var markerlatlng = new google.maps.LatLng(MARKERLATITUDE, MARKERLONGITUDE);
				marker = new google.maps.Marker({
					map: map,
					animation: google.maps.Animation.DROP,
					position: markerlatlng
				});
				
				
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map,marker);
				});
				
				map.setCenter(markerlatlng);
			}
		}
		
		
		
    }
     };

