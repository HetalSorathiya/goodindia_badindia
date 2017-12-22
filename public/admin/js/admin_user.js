ProjectName.modules.AdminuserListing = {
    init: function() {
        this.selectAllCheckbox();
    },
    
    selectAllCheckbox: function() {
        $('#selectall').click(function() {
            if ($(this).is(':checked') === true) {
                $('.content-checkbox').prop('checked', true);
            } else {
                $('.content-checkbox').prop('checked', false);
            }
        });
    }
};