ProjectName.modules.PostlistListing = {
    init: function() {
        this.selectAllCheckbox();
		this.ChangeStatus();
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
			var post_status = $(this).attr('post_status');
			$(".add_status").html('Are you sure you want to '+post_status+'?');
			$('.update_status_link').attr('href',link);
		});
	},
	
};