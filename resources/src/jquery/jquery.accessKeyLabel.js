/**
 * jQuery plugin to update the tooltip to show the correct access key
 *
 * @class jQuery.plugin.accessKeyLabel
 */
( function ( $, mw ) {

// Cached access key modifiers for used browser
var cachedAccessKeyModifiers,

	// Whether to use 'test-' instead of correct prefix (used for testing)
	useTestPrefix = false,

	// tag names which can have a label tag
	// https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Form-associated_content
	labelable = 'button, input, textarea, keygen, meter, output, progress, select';

/**
 * Find the modifier keys that need to be pressed together with the accesskey to trigger the input.
 *
 * The result is dependant on the ua paramater or the current platform.
 * For browsers that support accessKeyLabel, #getAccessKeyLabel never calls here.
 * Valid key values that are returned can be: ctrl, alt, option, shift, esc
 *
 * @private
 * @param {Object} [ua] An object with a 'userAgent' and 'platform' property.
 * @return {Array} Array with 0 or more of the string values: ctrl, option, alt, shift, esc
 */
function getAccessKeyModifiers( ua ) {
	// use cached prefix if possible
	if ( !ua && cachedAccessKeyModifiers ) {
		return cachedAccessKeyModifiers;
	}

	var profile = $.client.profile( ua ),
		accessKeyModifiers = [ 'alt' ];

	// Classic Opera on any platform
	if ( profile.name === 'opera' && profile.versionNumber < 15 ) {
		accessKeyModifiers = [ 'shift', 'esc' ];

	// Chrome and modern Opera on any platform
	} else if ( profile.name === 'chrome' || profile.name === 'opera' ) {
		accessKeyModifiers = (
			profile.platform === 'mac'
				// Chrome on Mac
				? [ 'ctrl', 'option' ]
				// Chrome on Windows or Linux
				// (both alt- and alt-shift work, but alt with E, D, F etc does not
				// work since they are browser shortcuts)
				: [ 'alt', 'shift' ]
		);

	// Non-Windows Safari with webkit_version > 526
	} else if ( profile.platform !== 'win'
		&& profile.name === 'safari'
		&& profile.layoutVersion > 526
	) {
		accessKeyModifiers = [ 'ctrl', 'alt' ];

	// Safari/Konqueror on any platform, or any browser on Mac
	// (but not Safari on Windows)
	} else if ( !( profile.platform === 'win' && profile.name === 'safari' )
		&& ( profile.name === 'safari'
		|| profile.platform === 'mac'
		|| profile.name === 'konqueror' )
	) {
		accessKeyModifiers = [ 'ctrl' ];

	// Firefox/Iceweasel 2.x and later
	} else if ( ( profile.name === 'firefox' || profile.name === 'iceweasel' )
		&& profile.versionBase > '1'
	) {
		accessKeyModifiers = [ 'alt', 'shift' ];
	}

	// cache modifiers
	if ( !ua ) {
		cachedAccessKeyModifiers = accessKeyModifiers;
	}
	return accessKeyModifiers;
}

/**
 * Get the access key label for an element.
 *
 * Will use native accessKeyLabel if available (currently only in Firefox 8+),
 * falls back to #getAccessKeyModifiers.
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
	return ( useTestPrefix ? 'test' : getAccessKeyModifiers().join( '-' ) ) + '-' + element.accessKey;
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
 * getAccessKeyModifiers
 *
 * @method updateTooltipAccessKeys_getAccessKeyModifiers
 * @inheritdoc #getAccessKeyModifiers
 */
$.fn.updateTooltipAccessKeys.getAccessKeyModifiers = getAccessKeyModifiers;

/**
 * getAccessKeyLabel
 *
 * @method updateTooltipAccessKeys_getAccessKeyLabel
 * @inheritdoc #getAccessKeyLabel
 */
$.fn.updateTooltipAccessKeys.getAccessKeyLabel = getAccessKeyLabel;

/**
 * getAccessKeyPrefix
 *
 * @method updateTooltipAccessKeys_getAccessKeyPrefix
 * @deprecated 1.27 Use #getAccessKeyModifiers
 */
$.fn.updateTooltipAccessKeys.getAccessKeyPrefix = function ( ua ) {
	return getAccessKeyModifiers( ua ).join( '-' ) + '-';
};

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
