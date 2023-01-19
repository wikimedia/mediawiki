/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function () {
	var nav = require( './nav.js' );
	$( function () {
		nav.insertHints();

		var tabs = OO.ui.infuse( $( '.mw-prefs-tabs' ) );

		// Support: Chrome
		// https://bugs.chromium.org/p/chromium/issues/detail?id=1252507
		//
		// Infusing the tabs above involves detaching all the tabs' content from the DOM momentarily,
		// which causes the :target selector (used in mediawiki.special.preferences.styles.ooui.less)
		// not to match anything inside the tabs in Chrome. Twiddling location.href makes it work.
		// Only do it when a fragment is present, otherwise the page would be reloaded.
		if ( location.href.indexOf( '#' ) !== -1 ) {
			// eslint-disable-next-line no-self-assign
			location.href = location.href;
		}

		tabs.$element.addClass( 'mw-prefs-tabs-infused' );

		function enhancePanel( panel ) {
			if ( !panel.$element.data( 'mw-section-infused' ) ) {
				panel.$element.removeClass( 'mw-htmlform-autoinfuse-lazy' );
				mw.hook( 'htmlform.enhance' ).fire( panel.$element );
				panel.$element.data( 'mw-section-infused', true );
			}
		}

		function onTabPanelSet( panel ) {
			if ( nav.switchingNoHash ) {
				return;
			}
			// Handle hash manually to prevent jumping,
			// therefore save and restore scrollTop to prevent jumping.
			var scrollTop = $( window ).scrollTop();
			// Changing the hash apparently causes keyboard focus to be lost?
			// Save and restore it. This makes no sense though.
			var active = document.activeElement;
			location.hash = '#' + panel.getName();
			if ( active ) {
				active.focus();
			}
			$( window ).scrollTop( scrollTop );
		}

		tabs.on( 'set', onTabPanelSet );

		// Hash navigation callback
		var setSection = function ( sectionName, fieldset ) {
			tabs.setTabPanel( sectionName );
			enhancePanel( tabs.getCurrentTabPanel() );
			if ( fieldset instanceof HTMLElement ) {
				fieldset.scrollIntoView();
			}
		};

		// onSubmit callback
		var onSubmit = function () {
			var value = tabs.getCurrentTabPanelName();
			mw.storage.session.set( 'mwpreferences-prevTab', value );
		};

		nav.onLoad( setSection, 'mw-prefsection-personal' );

		nav.restorePrevSection( setSection, onSubmit );
	} );
}() );
