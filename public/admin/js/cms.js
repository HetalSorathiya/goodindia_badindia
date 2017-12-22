ProjectName.modules.CmsListing = {
    init: function() {
        this.selectAllCheckbox();
		this.initCkEditor();
		this.ChangeStatus();
    },
    
	ChangeStatus: function(){
		$(".myLink").click(function() {
			
			$('#update_status_Modal').modal('show');
			var link = $(this).attr('href_link');
			var c_status = $(this).attr('c_status');
			$(".add_status").html('Are you sure you want to '+c_status+'?');
			$('.update_status_link').attr('href',link);
		});
	},
	
    selectAllCheckbox: function() {
        $('#selectall').click(function() {
            if ($(this).is(':checked') === true) {
                $('.content-checkbox').attr('checked', true);
            } else {
                $('.content-checkbox').attr('checked', false);
            }
        });
    },
	
	initCkEditor : function () {
		var config = {
			height: 350,
			width: 600,
			toolbar :
				[
					['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','Source', ],
					['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					['Styles','Format','Font','FontSize'],
					['Image','Table','HorizontalRule','SpecialChar','PageBreak'],
					['TextColor','BGColor']
				]
		};
		
		$('.wysihtml5').ckeditor(config);
	},
	
	
};