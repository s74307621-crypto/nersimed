(function($) {
	$(document).ready(function(){
		$.ajax({
			url: drplusVars.ajaxUrl,
			type: 'POST',
			data: {
				action: 'drplus_update_checker',
			},
			success: function(res) {
				if(res) {
					$('#drplus-update-notices').html(res).show();
				}
			}
		});
	});
})(jQuery);