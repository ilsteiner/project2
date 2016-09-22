$(document).ready(function($) {
	if(typeof errors != 'undefined'){
		$.each(errors, function(i, error) {
			$("#" + i).parent().addClass('has-error');
			$("#" + i).prev('label').append('<span class="error-text">' + error + '</span>');
		});
	}

	if($("#with_number").hasClass('toggle-me')) {
		$("#with_number").bootstrapToggle('on');
	}
});