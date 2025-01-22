(($) => {
	"use strict";
	$(window).on('load', function() {
		$('.difl_social_share_item').each(function() {
			// const animationClass = $(this).data('animation');
			// $(this).on("mouseenter", () => $(this).addClass(animationClass));
			// $(this).on("mouseleave", () => $(this).removeClass(animationClass));
			$(this).on("click", (event) => {
				// $(this).removeClass(animationClass);
				if ($(this).hasClass('difl_print')) {
					event.preventDefault();
					print();
				}
			});
		});
	});
})(jQuery);