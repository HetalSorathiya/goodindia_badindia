ProjectName.modules.MailtemplateListing = {
    init: function() {
        this.selectAllCheckbox();
		this.initCkEditor();
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