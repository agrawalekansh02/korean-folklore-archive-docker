$(function() {
	$( "#datepicker" ).each(function(){
		$(this).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"yy/mm/dd",
			yearRange:"c-80:c+1"
		});	
	});
	$('.datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"yy/mm/dd",
			yearRange:"c-80:c+1"
	});	
});
