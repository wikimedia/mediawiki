/**
 * jQuery plugin to update the tooltip to show the correct access key
 *
 * @class jQuery.plugin.accessKeyLabel
 */
( function ( $ ) {

// Cached access key prefix for used browser
var cachedAccessKeyPrefix,

	// Wether to use 'test-' instead of correct prefix (used for testing)
	useTestPrefix = false,

	// tag names which can have a label tag
	// https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Form-associated_content
	labelable = 'button, input, textarea, keygen, meter, output, progress, select';

/**
 * Get the prefix for the access key.
 * Will only give correct prefix for browsers not implementing the accessKeyLabel property.
 * These browsers currently are:
 * Firefox 8+
 *
 * Exposed for testing.
 *
 * @private
 * @param {Object} ua An object with atleast a 'userAgent' and 'platform' key.
 * Defaults to the global Navigator object.
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
 * @private
 * @param {HTMLElement} domElement DOM element to get the label for
 * @return {string} Access key label
 */
function getAccessKeyLabel( domElement ) {
	// abort early if no access key
	if ( !domElement.accessKey ) {
		return '';
	}
	// use accessKeyLabel if possible
	// http://www.whatwg.org/specs/web-apps/current-work/multipage/editing.html#dom-accesskeylabel
	if ( !useTestPrefix && domElement.accessKeyLabel ) {
		return domElement.accessKeyLabel;
	}
	return ( useTestPrefix ? 'test-' : getAccessKeyPrefix() ) + domElement.accessKey;
}

/**
 * Update the title for an element (on the element with the access key or it's label) to show the correct access key label.
 *
 * @private
 * @param {HTMLElement} domElement DOM element with the accesskey
 * @param {HTMLElement} titleElement DOM element with the title to update
 */
function updateTooltipOnElement( domElement, titleElement ) {
	var oldTitle = titleElement.title,
		rawTitle = oldTitle.replace( / \[.*?\]$/, '' ),
		newTitle = rawTitle,
		accessKeyLabel = getAccessKeyLabel( domElement );

	// don't add a title if the element didn't have one before
	if ( !oldTitle ) {
		return;
	}

	if ( accessKeyLabel ) {
		newTitle += ' [' + accessKeyLabel + ']';
	}
	if ( oldTitle !== newTitle ) {
		titleElement.title = newTitle;
	}
}

/**
 * Update the title for an element to show the correct access key label.
 *
 * @private
 * @param {HTMLElement} domElement DOM element with the accesskey
 */
function updateTooltip( domElement ) {
	var id, $domElement, $label, $labelParent;
	updateTooltipOnElement( domElement, domElement );

	// update associated label if there is one
	$domElement = $( domElement );
	if ( $domElement.is( labelable ) ) {
		// Search it using 'for' attribute
		id = domElement.id.replace( /"/g, '\\"' );
		if ( id ) {
			$label = $( 'label[for="' + id + '"]' );
			if ( $label.length === 1 ) {
				updateTooltipOnElement( domElement, $label[0] );
			}
		}

		// Search it as parent, because the form control can also inside the label element itself
		$labelParent = $domElement.parents( 'label' );
		if ( $labelParent.length === 1 ) {
			updateTooltipOnElement( domElement, $labelParent[0] );
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

}( jQuery ) );
