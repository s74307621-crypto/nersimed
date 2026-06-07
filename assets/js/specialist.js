(function($) {
	$(document).ready(function(){
		let $servicesSection = $('.specialist_services');
		if($servicesSection.outerHeight() >= 300) {
			$servicesSection.addClass('collapse');
		}
		$('.specialist_services-show-more-wrap').on('click', function() {
			$servicesSection.css('height', '100%');
			$('.specialist_services-show-more-wrap').fadeOut({
				complete: function() {
					$servicesSection.removeClass('collapse');
				}
			})
		})

		// Show certificates in lightgallery
		lightGallery(document.getElementsByClassName('specialist_certificates-list')[0], {
			zoomFromOrigin: true,
			selector: '.specialist_certificate-item[data-src]',
			download: false,
		})
	});
})(jQuery);