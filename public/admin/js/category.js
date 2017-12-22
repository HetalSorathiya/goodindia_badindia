ProjectName.modules.CategoryListing = {
    init: function() {
        this.selectAllCheckbox();
		this.ChangeStatus();
		this.formValidation();
    },
	
	ChangeStatus: function(){
		$(".myLink").click(function() {			
			$('#update_status_Modal').modal('show');
			var link = $(this).attr('href_link');
			var cat_status = $(this).attr('cat_status');
			$(".add_status").html('Are you sure you want to '+cat_status+'?');
			$('.update_status_link').attr('href',link);
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
	
	//check unchecked checkbox
	formValidation: function() {
		
		jQuery('.cat_image_msg').hide();
		jQuery('.cat_status_msg').hide();
		jQuery('.cat_name_msg').hide();
		
		$('#category-form').submit(function() {
						
			jQuery(".none").removeClass("error-class");
					
			var cat_name = jQuery(".cat_name").val();
			var cat_status = jQuery(".cat_status").val();
			var cat_image = jQuery(".cat_image").val();
			var bool = true;
			
			/*if(cat_image == ''){
				jQuery(".cat_image").addClass("error-class");
				jQuery(".cat_image_msg").show();
				jQuery('.cat_image_msg').html('Please upload image.');
				return false;
			} else {
				jQuery(".cat_image_msg").hide();
			} */
			if(cat_status == ''){
				jQuery(".cat_status").addClass("error-class");
				jQuery(".cat_status_msg").show();
				jQuery('.cat_status_msg').html('Please select status.');
				return false;
			} else {
				jQuery(".cat_status_msg").hide();
			} 

			jQuery(".cat_name").each( function(){
				if(jQuery(this).val() == ''){
					jQuery(this).addClass("error-class");
					jQuery('.cat_name_msg').show();
					var language = jQuery(this).data('language');
					jQuery('.cat_name_msg').html(language + ' category name should not be blank.');
					bool = false;
					return false;
				} else {
					jQuery('.cat_name_msg').hide();
				}
			});
			
			return bool;
		});
	}
};