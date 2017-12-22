 ProjectName.modules.LocationListing = {
    init: function () {
       
		this.ChangeStatus();
		this.deleteloc();
		this.selectAllCheckbox();
		this.map();
		this.formValidation();
},

		ChangeStatus: function(){
			$(".myLink").click(function() {			
				$('#update_status_Modal').modal('show');
				var link = $(this).attr('href_link');
				var loc_status = $(this).attr('loc_status');
				$(".add_status").html('Are you sure you want to '+loc_status+'?');
				$('.update_status_link').attr('href',link);
			});
		},
		
		deleteloc: function() {
			jQuery('.deletemodal').click( function(){
			$('#myModal').modal('show');
			var link = $(this).attr('target_href');
			var image_type = $(this).attr('type');
			var image_id = $(this).attr('image_id');
			$("#hidden_image_type").val(image_type);
			$("#hidden_image_id").val(image_id);
			$('.deletelink').attr('href',link);
			});
		},
		
		//check unchecked checkbox
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

				jQuery('#loc_latitude').val(latitude);
				jQuery('#loc_longitude').val(longitude);

				var google_link = 'https://maps.google.com/?q=@' + latitude + ',' + longitude;
				$("#view_on_google_map").attr('href', google_link);



				var draglatlng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
				geocoder.geocode({'latLng': draglatlng}, function (data, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var add = data[1].formatted_address; //this is the full address
						jQuery('#loc_name').val(add);
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
									jQuery('#loc_name').val(results[1].formatted_address);
								}
							} else {
								//alert("Geocoder failed due to: " + status);
							}
						});


						jQuery('#loc_latitude').val(position.coords.latitude);
						jQuery('#loc_longitude').val(position.coords.longitude);

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

			jQuery('#search-map').click(function () {
	//alert('here');
				var address = document.getElementById('loc_name').value;
				geocoder.geocode({'address': address}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						var latitude = results[0].geometry.location.lat();
						var longitude = results[0].geometry.location.lng();

						jQuery('#loc_latitude').val(latitude);
						jQuery('#loc_longitude').val(longitude);

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
		
		//check unchecked checkbox
	formValidation: function() {
		
		$('#location_submit').click(function() {
						
			 jQuery(".none").removeClass("error_message");
			var loc_name = jQuery("#loc_name").val();
			var loc_lat = jQuery("#loc_latitude").val();
			var loc_long = jQuery("#loc_longitude").val();
			var loc_status = jQuery("#loc_status").val();

			if (loc_name == '') {
				
                jQuery("#loc_name").addClass("error_message");
                return false;
			}else if (loc_lat == '') {
				
                jQuery("#loc_latitude").addClass("error_message");
                return false;
			}else if (loc_long == '') {
				
                jQuery("#loc_longitude").addClass("error_message");
                return false;
			}else if (loc_status == '') {
				
                jQuery("#loc_status").addClass("error_message");
                return false;
			}
		});
	}
};