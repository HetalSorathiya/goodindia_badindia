ProjectName.modules.UserListing = {
    init: function() {
        this.selectAllCheckbox();
		this.ChangeStatus();
		this.Assignvideo();
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
	
	ChangeStatus: function(){
		$(".myLink").click(function() {			
			$('#update_status_Modal').modal('show');
			var link = $(this).attr('href_link');
			var usr_status = $(this).attr('usr_status');
			$(".add_status").html('Are you sure you want to '+usr_status+'?');
			$('.update_status_link').attr('href',link);
		});
	},
	
	Assignvideo: function(){
		$(".assign_video").click(function() {		
			$('#my_assign_Modal').modal('show');
			var usr_id = $(this).attr('usr_id');
			
			var private_video = $(this).attr('private_video');
			var arr = private_video.split(',');
			
			$("#vp_video_id").val(arr);
			$("#usr_id").val(usr_id);
		});
		
		$(".submit_assign").click(function() {
			var usr_id = $("#usr_id").val();
			var vp_video_id = $("#vp_video_id").val();
			
			jQuery(".none").removeClass('error-class');
			
			if (vp_video_id == '') {
                jQuery("#vp_video_id").addClass('error-class');
                return false;
            } else {
                var postdata = {};
                var obj = $(this);
				
				var postdata = $('#assign-form').serializeArray();
				
                jQuery.post(BASE_PATH + '/admin/user/privateassignvideo', postdata, function(result) {					
					if(result == 1){
						$(".modal").modal('hide');
                        window.setTimeout(function() {
                            window.location = BASE_PATH + '/admin/user/list/';
                        }, 1000);
					}
                });
            }
			
		});
	}
};