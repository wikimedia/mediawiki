/**
 * jQuery plugin to update the tooltip to show the correct access key
 */

// Whether to use 'test-' instead of correct prefix (for unit tests)
let testMode = false;

let cachedModifiers;

/**
 * Find the modifier keys that need to be pressed together with the accesskey to trigger the input.
 *
 * The result is dependent on the ua paramater or the current platform.
 * For browsers that support accessKeyLabel, #getAccessKeyLabel never calls here.
 * Valid key values that are returned can be: ctrl, alt, option, shift, esc
 *
 * @private
 * @param {Object|undefined} [nav] A Navigator object with `userAgent` and `platform` properties.
 * @return {string} Label with dash-separated segments in this order: ctrl, option, alt, shift, esc
 */
function getAccessKeyModifiers( nav ) {
	if ( !nav && cachedModifiers ) {
		return cachedModifiers;
	}

	const profile = $.client.profile( nav );
	let accessKeyModifiers;

	switch ( profile.name ) {
		// Historical: Opera 8-13 used shift-esc- (Presto engine, no longer supported).
		// Opera 15+ (Blink engine) matches Chromium.
		// Historical: Konqueror 3-4 (WebKit) behaved the same as Safari (no longer supported).
		// Konqueror 18+ (QtWebEngine/Chromium engine) is profiled as 'chrome',
		// and matches Chromium behaviour.
		case 'opera':
		case 'chrome':
			// Edge is also included here now, as profile reports it as 'chrome'
			if ( profile.platform === 'mac' ) {
				// Chromium on macOS
				accessKeyModifiers = 'ctrl-option';
			} else {
				// Chromium on Windows or Linux
				// Alt works, unless it conflicts with native browser
				// shortcuts (e.g. E, D, F), at which point alt-shift is
				// required. Unfortunately, alt-shift now *only* works if
				// there's a conflict, so we can't just show that. We'll
				// advertise just alt, and hope that Chromium will eventually
				// support accessKeyLabel.
				accessKeyModifiers = 'alt';
			}
			break;
		// Historical: Firefox 1.x used alt- (no longer supported).
		case 'firefox':
		case 'iceweasel':
			if ( profile.platform === 'mac' ) {
				if ( profile.versionNumber < 14 ) {
					accessKeyModifiers = 'ctrl';
				} else {
					// Firefox 14+ on macOS
					accessKeyModifiers = 'ctrl-option';
				}
			} else {
				// Firefox 2+ on Windows or Linux
				accessKeyModifiers = 'alt-shift';
			}
			break;
		// Historical: Safari <= 3 on Windows used alt- (no longer supported).
		// Historical: Safari <= 3 on macOS used ctrl- (no longer supported).
		case 'safari':
			// Safari 4+ (WebKit 526+) on macOS
			accessKeyModifiers = 'ctrl-option';
			break;
		case 'msie':
		case 'edge':
			accessKeyModifiers = 'alt';
			break;
		default:
			accessKeyModifiers = profile.platform === 'mac' ? 'ctrl' : 'alt';
			break;
	}

	if ( !nav ) {
		// If not for a custom UA string, cache and re-use
		cachedModifiers = accessKeyModifiers;
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
	// https://html.spec.whatwg.org/multipage/interaction.html#dom-accesskeylabel
	if ( !testMode && element.accessKeyLabel ) {
		return element.accessKeyLabel;
	}
	return ( testMode ? 'test' : getAccessKeyModifiers() ) + '-' + element.accessKey;
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
	const oldTitle = titleElement.title;
	if ( !oldTitle ) {
		// don't add a title if the element didn't have one before
		return;
	}

	const separatorMsg = mw.message( 'word-separator' ).plain();
	const parts = ( separatorMsg + mw.message( 'brackets' ).plain() ).split( '$1' );

	const regexp = new RegExp( parts.map( mw.util.escapeRegExp ).join( '.*?' ) + '$' );
	let newTitle = oldTitle.replace( regexp, '' );
	const accessKeyLabel = getAccessKeyLabel( element );

	if ( accessKeyLabel ) {
		// Should be build the same as in Linker::titleAttrib
		newTitle += separatorMsg + mw.message( 'brackets', accessKeyLabel ).plain();
	}
	if ( oldTitle !== newTitle ) {
		titleElement.title = newTitle;
	}
}

// HTML elements that can have an associated label
// https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Form-associated_content
const labelable = 'button, input, textarea, keygen, meter, output, progress, select';

/**
 * Update the title for an element to show the correct access key label.
 *
 * @private
 * @param {HTMLElement} element Element with the accesskey
 */
function updateTooltip( element ) {
	updateTooltipOnElement( element, element );

	// update associated label if there is one
	const $element = $( element );
	if ( $element.is( labelable ) ) {
		// Search it using 'for' attribute
		const id = element.id.replace( /"/g, '\\"' );
		if ( id ) {
			const $label = $( 'label[for="' + id + '"]' );
			if ( $label.length === 1 ) {
				updateTooltipOnElement( element, $label[ 0 ] );
			}
		}

		// Search it as parent, because the form control can also be inside the label element itself
		const $labelParent = $element.parents( 'label' );
		if ( $labelParent.length === 1 ) {
			updateTooltipOnElement( element, $labelParent[ 0 ] );
		}
	}
}

/**
 * Update the titles for all elements in a jQuery selection.
 *
 * To use this {@link jQuery} plugin, load the `mediawiki.util` module using {@link mw.loader}.
 *
 * @memberof module:mediawiki.util
 * @method
 * @return {jQuery}
 * @example
 * // Converts tooltip "[z]" to associated browser shortcut key e.g. "[ctrl-option-z]"
 * mw.loader.using( 'mediawiki.util' ).then( () => {
 *     var $a = $('<a href="/wiki/Main_Page" title="Visit the main page [z]" accesskey="z"><span>Main page</span></a>');
 *     $a.updateTooltipAccessKeys();
 * } );
 * @chainable
 */
$.fn.updateTooltipAccessKeys = function () {
	return this.each( function () {
		updateTooltip( this );
	} );
};

$.fn.updateTooltipAccessKeys.getAccessKeyLabel = getAccessKeyLabel;

/**
 * getAccessKeyPrefix
 *
 * @method updateTooltipAccessKeys_getAccessKeyPrefix
 * @param {Object} [nav] An object with a 'userAgent' and 'platform' property.
 * @return {string}
 * @ignore
 */
$.fn.updateTooltipAccessKeys.getAccessKeyPrefix = function ( nav ) {
	return getAccessKeyModifiers( nav ) + '-';
};

/**
 * Switch test mode on and off.
 *
 * @method updateTooltipAccessKeys_setTestMode
 * @param {boolean} mode New mode
 * @ignore
 */
$.fn.updateTooltipAccessKeys.setTestMode = function ( mode ) {
	testMode = mode;
};
