$(document).ready(function() {		

	//Delete Modal link 	
	$('.deletemodal').click( function(){
		$('#myModal').modal();
		var link = $(this).attr('target_href');
		var image_type = $(this).attr('type');
		var image_id = $(this).attr('image_id');
		$("#hidden_image_type").val(image_type);
		$("#hidden_image_id").val(image_id);
		$('.deletelink').attr('href',link);
	});
});
	