/**
 * Behavior related to the temporary user banner, specifically
 * the tooltip. Design: https://phabricator.wikimedia.org/T330510
 *
 * @ignore
 */

$( () => {

	var config = require( './config.json' ),
		$tempUserBannerEl = $( '.mw-temp-user-banner ' ),
		$tempUserBannerTooltipEl = $( '.mw-temp-user-banner-tooltip ' ),
		$tempUserBannerTooltipButtonEl = $( '#mw-temp-user-banner-tooltip-button' ),
		TTL_DAY_MS = 86400000;

	/**
	 * Get the time since the account was created
	 *
	 * @ignore
	 * @return {number} The duration since it was created until "now" in milliseconds
	 */
	function getTemporaryAccountDurationMs() {
		return Date.now() - mw.user.getFirstRegistration().getTime();
	}

	/**
	 * Whether the account will expire within the period defined
	 * by notifyBeforeExpirationDays in AutoCreateTempUser configuration
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function isWithinExpirationNotificationPeriod() {
		var expirationDurationMs = TTL_DAY_MS * config.AutoCreateTempUser.expireAfterDays;
		var notificationDurationMs = TTL_DAY_MS * config.AutoCreateTempUser.notifyBeforeExpirationDays;
		return getTemporaryAccountDurationMs() > ( expirationDurationMs - notificationDurationMs );
	}

	/**
	 * Get the time until the account will expire
	 *
	 * @ignore
	 * @return {number} The duration from "now" until the account expiration in milliseconds
	 */
	function getTimeToExpirationMs() {
		return ( TTL_DAY_MS * config.AutoCreateTempUser.expireAfterDays ) - getTemporaryAccountDurationMs();
	}

	/**
	 * Whether the expiration tooltip should be shown. The following criteria
	 * must be met:
	 *  - Expiration is set in AutoCreateTempUser configuration (expireAfterDays)
	 *  - The evaluated temporary account expiration is within the notification period
	 *  as defined in AutoCreateTempUser configuration (notifyBeforeExpirationDays)
	 *  - The user didn't proactively dismiss the tooltip clicking on the close button,
	 *  see showTooltip()
	 *
	 * @ignore
	 * @return {boolean}
	 */
	function shouldShowExpirationAlert() {
		var tempUserExpirationAlertDismissed = localStorage.getItem( 'tempUserExpirationAlertDismissed' );
		var expirationIsSet = typeof config.AutoCreateTempUser.expireAfterDays === 'number';
		var notifyBeforeExpirationIsSet = typeof config.AutoCreateTempUser.notifyBeforeExpirationDays === 'number';
		return expirationIsSet &&
			notifyBeforeExpirationIsSet &&
			!tempUserExpirationAlertDismissed &&
			isWithinExpirationNotificationPeriod();
	}

	/**
	 * Retrieve the expiration descriptive text
	 *
	 * @ignore
	 * @return {string}
	 */
	function getExpirationDescriptionText() {
		var timeToExpirationDays = Math.floor( getTimeToExpirationMs() / TTL_DAY_MS );
		var params = [ timeToExpirationDays ];
		var key = 'temp-user-banner-tooltip-description-expiration-soon';
		if ( timeToExpirationDays < 1 ) {
			key += '-day';
			params.pop();
		}
		// Messages that can be used here:
		// * temp-user-banner-tooltip-description-expiration-soon
		// * temp-user-banner-tooltip-description-expiration-soon-day
		return mw.message( key, params ).parseDom();
	}

	/**
	 * Retrieve the tooltip content
	 *
	 * @ignore
	 * @param {boolean} shouldShowExpiration
	 * @return {jQuery}
	 */
	function getTooltipContent( shouldShowExpiration ) {
		var descriptionText = shouldShowExpiration ?
			getExpirationDescriptionText() :
			mw.message( 'temp-user-banner-tooltip-description-learn-more' ).parseDom();
		return $( '<div>' ).append(
			$( '<p>' ).append( descriptionText ),
			$( '<p>' ).append( mw.message( 'temp-user-banner-tooltip-description-login' ).parseDom() )
		);
	}

	/**
	 * Builds a tooltip which is part of a banner for temporary account (IP masking) users.
	 *
	 * @ignore
	 * @param {jQuery} $bannerEl
	 * @param {jQuery} $tooltipEl
	 * @param {jQuery} $buttonEl
	 */
	function initTempUserBannerTooltip( $bannerEl, $tooltipEl, $buttonEl ) {
		if ( !$bannerEl.length || !$tooltipEl.length || !$buttonEl.length ) {
			return;
		}

		var popup;

		/**
		 * Creates the tooltip if it doesn't already exist and toggles it.
		 *
		 * @ignore
		 */
		function showTooltip() {
			return mw.loader.using( [
				'codex-styles',
				'oojs-ui-core',
				'oojs-ui-widgets',
				'oojs-ui.styles.icons-interactions'
			] ).then( () => {
				var shouldShowExpiration = shouldShowExpirationAlert();
				if ( !popup ) {
					popup = new OO.ui.PopupWidget( {
						icon: 'clock',
						padded: true,
						head: true,
						label: mw.msg( 'temp-user-banner-tooltip-title' ),
						$content: getTooltipContent( shouldShowExpiration ),
						autoClose: !shouldShowExpiration,
						$autoCloseIgnore: $buttonEl,
						$floatableContainer: $tooltipEl,
						position: 'below'
					} );
					$tooltipEl.append( popup.$element );

					if ( shouldShowExpiration ) {
						popup.on( 'toggle', ( visible ) => {
							if ( !visible ) {
								localStorage.setItem( 'tempUserExpirationAlertDismissed', true );
								popup = null;
							}
						} );
					}
				}
				popup.toggle();
			} );
		}

		$buttonEl.on( 'click', showTooltip );

		if ( shouldShowExpirationAlert() ) {
			showTooltip();
		}
	}

	initTempUserBannerTooltip( $tempUserBannerEl, $tempUserBannerTooltipEl, $tempUserBannerTooltipButtonEl );

} );
