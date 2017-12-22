ProjectName.modules.PostViewing = {
    init: function() {
		this.commenttoggle();
    },
	
commenttoggle: function(){
	 jQuery('.accordian-body').on('show.bs.collapse', function () {
    jQuery(this).closest("table")
        .find(".collapse.in")
        .not(this)
        .collapse('toggle')
})
	}
	
};
