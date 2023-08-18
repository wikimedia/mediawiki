/**
 * Behavior related to the temporary user banner, specifically
 * the tooltip. Design: https://phabricator.wikimedia.org/T330510
 *
 * @ignore
 */

$( function () {

	var $tempUserBannerEl = $( '.mw-temp-user-banner ' ),
		$tempUserBannerTooltipEl = $( '.mw-temp-user-banner-tooltip ' ),
		$tempUserBannerTooltipButtonEl = $( '#mw-temp-user-banner-tooltip-button' );

	/**
	 * Builds a tooltip which is part of a banner for temporary account (IP masking) users.
	 *
	 * @param {jQuery} $bannerEl
	 * @param {jQuery} $tooltipEl
	 * @param {jQuery} $buttonEl
	 */
	function initTempUserBannerTooltip( $bannerEl, $tooltipEl, $buttonEl ) {
		if ( !$bannerEl.length || !$tooltipEl.length || !$buttonEl.length ) {
			return;
		}

		var $tooltipContent = $( '<div>' ).append(
			$( '<p>' ).append( mw.message( 'temp-user-banner-tooltip-description-learn-more' ).parseDom() ),
			$( '<p>' ).append( mw.message( 'temp-user-banner-tooltip-description-login' ).parseDom() )
		);
		var popup;

		/**
		 * Creates the tooltip if it doesn't already exist and toggles it.
		 */
		function showTooltip() {
			if ( !popup ) {
				popup = new OO.ui.PopupWidget( {
					icon: 'clock',
					padded: true,
					head: true,
					label: mw.msg( 'temp-user-banner-tooltip-title' ),
					$content: $tooltipContent,
					autoClose: true,
					$autoCloseIgnore: $buttonEl,
					$floatableContainer: $tooltipEl,
					position: 'below'
				} );
				$tooltipEl.append( popup.$element );
			}
			popup.toggle();
		}

		$buttonEl.on( 'click', function () {
			mw.loader.using( [
				'codex-search-styles',
				'oojs-ui-core',
				'oojs-ui-widgets',
				'oojs-ui.styles.icons-interactions'
			] )
				.then( showTooltip );
		} );
	}

	initTempUserBannerTooltip( $tempUserBannerEl, $tempUserBannerTooltipEl, $tempUserBannerTooltipButtonEl );

} );
