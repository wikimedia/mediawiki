/**
 * jQuery plugin to update the tooltip to show the correct access key
 *
 * @class jQuery.plugin.accessKeyLabel
 */
( function ( $, mw ) {

// Cached access key prefix for used browser
var cachedAccessKeyPrefix,

	// Whether to use 'test-' instead of correct prefix (used for testing)
	useTestPrefix = false,

	// tag names which can have a label tag
	// https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Form-associated_content
	labelable = 'button, input, textarea, keygen, meter, output, progress, select';

/**
 * Get the prefix for the access key for browsers that don't support accessKeyLabel.
 *
 * For browsers that support accessKeyLabel, #getAccessKeyLabel never calls here.
 *
 * @private
 * @param {Object} [ua] An object with a 'userAgent' and 'platform' property.
 * @return {string} Access key prefix
 */
function getAccessKeyPrefix( ua ) {
	// use cached prefix if possible
	if ( !ua && cachedAccessKeyPrefix ) {
		return cachedAccessKeyPrefix;
	}

	var profile = $.client.profile( ua ),
		accessKeyPrefix = 'alt-';

	// Opera on any platform
	if ( profile.name === 'opera' ) {
		accessKeyPrefix = 'shift-esc-';

	// Chrome on any platform
	} else if ( profile.name === 'chrome' ) {
		accessKeyPrefix = (
			profile.platform === 'mac'
				// Chrome on Mac
				? 'ctrl-option-'
				// Chrome on Windows or Linux
				// (both alt- and alt-shift work, but alt with E, D, F etc does not
				// work since they are browser shortcuts)
				: 'alt-shift-'
		);

	// Non-Windows Safari with webkit_version > 526
	} else if ( profile.platform !== 'win'
		&& profile.name === 'safari'
		&& profile.layoutVersion > 526
	) {
		accessKeyPrefix = 'ctrl-alt-';

	// Safari/Konqueror on any platform, or any browser on Mac
	// (but not Safari on Windows)
	} else if ( !( profile.platform === 'win' && profile.name === 'safari' )
		&& ( profile.name === 'safari'
		|| profile.platform === 'mac'
		|| profile.name === 'konqueror' )
	) {
		accessKeyPrefix = 'ctrl-';

	// Firefox/Iceweasel 2.x and later
	} else if ( ( profile.name === 'firefox' || profile.name === 'iceweasel' )
		&& profile.versionBase > '1'
	) {
		accessKeyPrefix = 'alt-shift-';
	}

	// cache prefix
	if ( !ua ) {
		cachedAccessKeyPrefix = accessKeyPrefix;
	}
	return accessKeyPrefix;
}

/**
 * Get the access key label for an element.
 *
 * Will use native accessKeyLabel if available (currently only in Firefox 8+),
 * falls back to #getAccessKeyPrefix.
 *
 * @private
 * @param {HTMLElement} element Element to get the label for
 * @return {string} Access key label
 */
function getAccessKeyLabel( element ) {
	// abort early if no access key
	if ( !element.accessKey ) {
		return '';
	}
	// use accessKeyLabel if possible
	// http://www.whatwg.org/specs/web-apps/current-work/multipage/editing.html#dom-accesskeylabel
	if ( !useTestPrefix && element.accessKeyLabel ) {
		return element.accessKeyLabel;
	}
	return ( useTestPrefix ? 'test-' : getAccessKeyPrefix() ) + element.accessKey;
}

/**
 * Update the title for an element (on the element with the access key or it's label) to show
 * the correct access key label.
 *
 * @private
 * @param {HTMLElement} element Element with the accesskey
 * @param {HTMLElement} titleElement Element with the title to update (may be the same as `element`)
 */
function updateTooltipOnElement( element, titleElement ) {
	var array = ( mw.msg( 'word-separator' ) + mw.msg( 'brackets' ) ).split( '$1' ),
		regexp = new RegExp( $.map( array, mw.RegExp.escape ).join( '.*?' ) + '$' ),
		oldTitle = titleElement.title,
		rawTitle = oldTitle.replace( regexp, '' ),
		newTitle = rawTitle,
		accessKeyLabel = getAccessKeyLabel( element );

	// don't add a title if the element didn't have one before
	if ( !oldTitle ) {
		return;
	}

	if ( accessKeyLabel ) {
		// Should be build the same as in Linker::titleAttrib
		newTitle += mw.msg( 'word-separator' ) + mw.msg( 'brackets', accessKeyLabel );
	}
	if ( oldTitle !== newTitle ) {
		titleElement.title = newTitle;
	}
}

/**
 * Update the title for an element to show the correct access key label.
 *
 * @private
 * @param {HTMLElement} element Element with the accesskey
 */
function updateTooltip( element ) {
	var id, $element, $label, $labelParent;
	updateTooltipOnElement( element, element );

	// update associated label if there is one
	$element = $( element );
	if ( $element.is( labelable ) ) {
		// Search it using 'for' attribute
		id = element.id.replace( /"/g, '\\"' );
		if ( id ) {
			$label = $( 'label[for="' + id + '"]' );
			if ( $label.length === 1 ) {
				updateTooltipOnElement( element, $label[ 0 ] );
			}
		}

		// Search it as parent, because the form control can also be inside the label element itself
		$labelParent = $element.parents( 'label' );
		if ( $labelParent.length === 1 ) {
			updateTooltipOnElement( element, $labelParent[ 0 ] );
		}
	}
}

/**
 * Update the titles for all elements in a jQuery selection.
 *
 * @return {jQuery}
 * @chainable
 */
$.fn.updateTooltipAccessKeys = function () {
	return this.each( function () {
		updateTooltip( this );
	} );
};

/**
 * Exposed for testing.
 *
 * @method updateTooltipAccessKeys_getAccessKeyPrefix
 * @inheritdoc #getAccessKeyPrefix
 */
$.fn.updateTooltipAccessKeys.getAccessKeyPrefix = getAccessKeyPrefix;

/**
 * Switch test mode on and off.
 *
 * @method updateTooltipAccessKeys_setTestMode
 * @param {boolean} mode New mode
 */
$.fn.updateTooltipAccessKeys.setTestMode = function ( mode ) {
	useTestPrefix = mode;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.accessKeyLabel
 */

}( jQuery, mediaWiki ) );
