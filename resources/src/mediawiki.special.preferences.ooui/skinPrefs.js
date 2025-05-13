/*!
 * Special:Preferences skin section enhancements.
 * When the skin tab is infused by OOUI, toggle skin preferences based on skin state.
 */

function invalidateSkinPrefsDisplay( $root ) {
	const
		// The skin preferences section. Skins with preferences should use the
		// section `rendering/skin/skin-prefs` toa dd skin specific preferences
		// in their onGetPreferences() hook.
		// At time of writing, this functionality is only used by Vector and Monobook.
		$skinPrefs = $root.find( '#mw-prefsection-rendering-skin-skin-prefs' ),
		// oo-ui-fieldLayout is the wrapper class name for each preference. When all wrappers are
		// disabled by hide-if (oo-ui-fieldLayout-disabled), the whole section should be hidden.
		show = !!$skinPrefs.find( '.oo-ui-fieldLayout' ).not( '.oo-ui-fieldLayout-disabled' ).length;

	$skinPrefs.parent().css( 'display', show ? '' : 'none' );
}

function onHTMLFormEnhance( $root ) {
	const $skins = $root.find( '#mw-input-wpskin' );
	// Don't kick in before this tab panel is infused and hide-if executed (T352358).
	if ( !$skins.length || $skins.closest( '.mw-htmlform-autoinfuse-lazy' ).length ) {
		return;
	}

	// No need to listen for subsequent events.
	mw.hook( 'htmlform.enhance' ).remove( onHTMLFormEnhance );

	// Set the initial skin preference display state based on the skin selected.
	invalidateSkinPrefsDisplay( $root );

	// Observe skin radio state selection changes.
	OO.ui.infuse( $skins ).on( 'change', () => {
		invalidateSkinPrefsDisplay( $root );
	} );
}

// Wait for the tab to be infused by OOUI before manipulating it further. htmlform.enhance is
// emitted on page load by resources/src/mediawiki.htmlform/htmlform.js, again for each tab
// infused by resources/src/mediawiki.special.preferences.ooui/tabs.js, and possibly other
// scenarios.
mw.hook( 'htmlform.enhance' ).add( onHTMLFormEnhance );
