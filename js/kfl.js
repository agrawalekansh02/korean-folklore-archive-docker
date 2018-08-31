/*!
 *
 * Author: Jun Wan @cdh
 * August, 2012
 */

$(document).ready (function () {
	$('.delete-link').click (function (e) {

		var thisItem = $(this).attr("data-name");
		var restrictionItem = $(this).attr("data-restriction");

		switch($(this).attr("type")){
			case 'data':
				return confirm ("Are you sure you want to delete " +$(this).attr("type")+" "+ $(this).attr ("title") + "?") ;
				break;
			case 'context':
				if ($(this).attr("title")==1){
					e.preventDefault();
					e.stopPropagation();
					return alert ("Unable to delete " + thisItem + " as it is being used with " + restrictionItem + ".  Delete or update associated " + restrictionItem + " before deleting " + thisItem);
				}
				else
					return confirm ("Are you sure you want to delete " +$(this).attr("type")+"?") ;
				break;
			case 'consultant':
				if ($(this).attr("title")==1){
					e.preventDefault();
					e.stopPropagation();
					return alert ("Unable to delete " + thisItem + " as it is being used with " + restrictionItem + ".  Delete or update associated " + restrictionItem + " before deleting " + thisItem);
				}
				else
					return confirm ("Are you sure you want to delete " +$(this).attr("type")+"?") ;
				break;
		}// end switch
	}) ; 

}) ;

function submitform(f){
	$('#'+f).submit();
	//f.submit();
}

/* passcode chnage function */

$(function() {
    $("#submit_button").click(function() {
		if(confirm ("Are you sure you want to make the change?")){
			submitform('passcode_form');
		}

	});
});

/* search button  */
$(function() {
    $(".search_button").click(function() {
		// getting the value that user typed
		var searchString    = $("#search_box").val();
		// forming the queryString
		var data            = 'search='+ searchString;
		// if searchString is not empty
		if(searchString) {
			// ajax call
			$.ajax({
				type: "POST",
				url: "do_search.php",
				data: data,
				beforeSend: function(html) { // this happens before actual call
					$("#results").html('');
					$("#searchresults").show();
					$(".word").html(searchString);
				},
				success: function(data){ // this happens after we get results
					//$("#results").show();
					//$("#results").text(html).load('div#r');
					//var $response=$(data);
					// dataValue = $response.filter('#r').text();
					var dataValue = $(data).find("#d123");
					$("#results").html(dataValue.html());
				}
			});   
		}
		return false;
	});
});

/* Datepicker */
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


/* radio buttons and checkboxes */

function check_category(group){
	switch(group){
		case 1:
			$('input.group1').attr('disabled', false);
			$('input.group2').attr('disabled', true);
			$('input.group3').attr('disabled', true);
			$('input.group2').attr('checked', false);
			$('input.group3').attr('checked', false);
			break;

		case 2:
			$('input.group2').attr('disabled', false);
			$('input.group3').attr('disabled', true);
			$('input.group1').attr('disabled', true);
			$('input.group3').attr('checked', false);
			$('input.group1').attr('checked', false);
			break;

		case 3:
			$('input.group3').attr('disabled', false);
			$('input.group1').attr('disabled', true);
			$('input.group2').attr('disabled', true);
			$('input.group1').attr('checked', false);
			$('input.group2').attr('checked', false);
			break;
	
		case 4:
			$('input.group2').attr('disabled', true);
			$('input.group1').attr('disabled', true);
			$('input.group3').attr('disabled', true);
			$('input.group2').attr('checked', false);
			$('input.group1').attr('checked', false);
			$('input.group3').attr('checked', false);
			$('#general_observation').val("General Observation");
			break;
		default:
			break;
		
	}
		
}