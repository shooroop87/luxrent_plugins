var wdtPortfolioSinglePageUtils = {

    wdtPortfolioCheckReadyState :  function(printWindow) {

        //if (printWindow.document.readyState == "complete") {
            printWindow.focus(); // necessary for IE >= 10
            printWindow.print();
            printWindow.close();
        //}

    },

};

var wdtPortfolioSinglePage = {

	dtInit : function() {


		// Add to favourite list

			jQuery( 'body' ).delegate( '.wdt-listings-utils-favourite-item', 'click', function(e) {

                var this_item = jQuery(this);

				if(jQuery(this).hasClass('wdt-login-link')) {

                    this_item.parents('.wdt-listings-utils-favourite').prepend( '<span class="wdt-fav-please-login">'+ wdtfrontendobject.pleaseLogin +'</span>' );
                    window.setTimeout(function(){
						this_item.parents('.wdt-listings-utils-favourite').find('span.wdt-fav-please-login').remove();
					}, 2000);

                } else {

					var listing_id = this_item.attr('data-listingid');
					var user_id = this_item.attr('data-userid');

					if(jQuery(this).hasClass('addtofavourite')) {
						var favourite_label = 'addtofavourite';
					} else {
						var favourite_label = 'removefavourite';
					}

					jQuery.ajax({
						type: "POST",
						url: wdtfrontendobject.ajaxurl,
						data:
						{
							action: 'wdt_listing_favourite_marker',
							listing_id: listing_id,
							user_id: user_id,
						},
						beforeSend: function(){
							this_item.parents('.wdt-listings-utils-favourite').prepend( '<span><i class="fa fa-spinner fa-spin"></i></span>' );
						},
						success: function (response) {
							if(favourite_label == 'addtofavourite') {
								this_item.html('<span class="fa fa-heart"></span>');
								this_item.removeClass('addtofavourite');
								this_item.addClass('removefavourite');
							} else {
								this_item.html('<span class="far fa-heart"></span>');
								this_item.removeClass('removefavourite');
								this_item.addClass('addtofavourite');
							}
						},
						complete: function(){
							this_item.parents('.wdt-listings-utils-favourite').find("span:first").remove();
						}
					});

				}

				e.preventDefault();

			});

		// Send request to view contact details

			jQuery( 'body' ).delegate( '.wdt-listings-contactdetails-request', 'click', function(e) {

				var this_item = jQuery(this);
				var listing_id = this_item.attr('data-listingid');

				jQuery.ajax({
					type: "POST",
					url: wdtfrontendobject.ajaxurl,
					data:
					{
						action: 'wdt_listing_contactdetails_request',
						listing_id: listing_id,
					},
					dataType: "JSON",
					beforeSend: function() {
						this_item.prepend( '<span><i class="fa fa-spinner fa-spin"></i></span>' );
					},
					success: function (response) {
						if(response.success) {
							location.reload();
						} else {
							this_item.parents('.wdt-listings-contactdetails-request-container').append('<div class="wdt-contactdetails-request-notification-box">'+response.message+'</div>');
							window.setTimeout(function(){
								this_item.parents('.wdt-listings-contactdetails-request-container').find('.wdt-contactdetails-request-notification-box').remove();
							}, 2000);
						}
					},
					complete: function() {
						this_item.find('span').remove();
					}
				});

				e.preventDefault();

			});


		// Activity Tracker - Website Visit, Phone & Mobile Click

			jQuery( 'body' ).delegate( '.wdt-listings-contactdetails-list a.web, .wdt-listings-contactdetails-list a.phone, .wdt-listings-contactdetails-list a.mobile', 'click', function(e) {

				var this_item  = jQuery(this);
				var listing_id = this_item.attr('data-listingid');
				var user_id    = this_item.attr('data-userid');

				var activity_type = '';
				if(this_item.hasClass('web')) {
					activity_type = 'website';
				} else if(this_item.hasClass('phone')) {
					activity_type = 'phone';
				} else if(this_item.hasClass('mobile')) {
					activity_type = 'mobile';
				}

				jQuery.getJSON('https://geoip-db.com/json/geoip.php?jsonp=?').done(function(location) {

					var country = location.country_name;
					var city    = location.city;
					var zip     = location.postal;

					jQuery.ajax({
						type: "POST",
						url: wdtfrontendobject.ajaxurl,
						data:
						{
							action       : 'wdt_listing_activity_tracker_contactdetails',
							activity_type: activity_type,
							listing_id   : listing_id,
							user_id      : user_id,
							country      : country,
							city         : city,
							zip          : zip
						},
						dataType: "JSON",
						success: function (response) {
						}
					});

				});

			});


            if(jQuery('.wdt-portfolio-sticky-section').length) {
                let $stickyInstanceOptions = {
                    topSpacing: 0,
                    bottomSpacing: 0,
                    containerSelector: '.wdt-portfolio-sticky-section-container',
                    innerWrapperSelector: '.wdt-portfolio-sticky-section-inner',
                };
                $stickyInstance = new StickySidebar(jQuery('.wdt-portfolio-sticky-section')[0], $stickyInstanceOptions);
            }

	}

};

jQuery(document).ready(function() {

	if(!wdtfrontendobject.elementorPreviewMode) {
		wdtPortfolioSinglePage.dtInit();
	}

});


( function( $ ) {

	var wdtPortfolioSinglePageJs = function($scope, $){
		wdtPortfolioSinglePage.dtInit();
	};

    $(window).on('elementor/frontend/init', function(){
		if(wdtfrontendobject.elementorPreviewMode) {
			elementorFrontend.hooks.addAction('frontend/element_ready/wdt-widget-sp-map.default', wdtPortfolioSinglePageJs);
		}
	});

} )( jQuery );