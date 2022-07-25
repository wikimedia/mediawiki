/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function () {
	$( function () {
		// Make sure the accessibility tip is focussable so that keyboard users take notice,
		// but hide it by default to reduce visual clutter.
		// Make sure it becomes visible when focused.
		$( '<div>' ).addClass( 'mw-navigation-hint' )
			.text( mw.msg( 'prefs-tabs-navigation-hint' ) )
			.attr( {
				tabIndex: 0
			} )
			.insertBefore( '.mw-htmlform-ooui-wrapper' );

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

		var switchingNoHash;

		function onTabPanelSet( panel ) {
			if ( switchingNoHash ) {
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

		/**
		 * @ignore
		 * @param {string} name The name of a tab
		 * @param {boolean} [noHash] A hash will be set according to the current
		 *  open section. Use this flag to suppress this.
		 */
		function switchPrefTab( name, noHash ) {
			if ( noHash ) {
				switchingNoHash = true;
			}
			tabs.setTabPanel( name );
			enhancePanel( tabs.getCurrentTabPanel() );
			if ( noHash ) {
				switchingNoHash = false;
			}
		}

		// Jump to correct section as indicated by the hash.
		// This function is called onload and onhashchange.
		function detectHash() {
			var hash = location.hash;
			if ( /^#mw-prefsection-[\w]+$/.test( hash ) ) {
				mw.storage.session.remove( 'mwpreferences-prevTab' );
				switchPrefTab( hash.slice( 1 ) );
			} else if ( /^#mw-[\w-]+$/.test( hash ) ) {
				var matchedElement = document.getElementById( hash.slice( 1 ) );
				var $parentSection = $( matchedElement ).closest( '.mw-prefs-section-fieldset' );
				if ( $parentSection.length ) {
					mw.storage.session.remove( 'mwpreferences-prevTab' );
					// Switch to proper tab and scroll to selected item.
					switchPrefTab( $parentSection.attr( 'id' ), true );
					matchedElement.scrollIntoView();
				}
			}
		}

		$( window ).on( 'hashchange', function () {
			var hash = location.hash;
			if ( /^#mw-[\w-]+/.test( hash ) ) {
				detectHash();
			} else if ( hash === '' ) {
				switchPrefTab( 'mw-prefsection-personal', true );
			}
		} )
			// Run the function immediately to select the proper tab on startup.
			.trigger( 'hashchange' );

		// Restore the active tab after saving the preferences
		var previousTab = mw.storage.session.get( 'mwpreferences-prevTab' );
		if ( previousTab ) {
			switchPrefTab( previousTab, true );
			// Deleting the key, the tab states should be reset until we press Save
			mw.storage.session.remove( 'mwpreferences-prevTab' );
		}

		$( '#mw-prefs-form' ).on( 'submit', function () {
			var value = tabs.getCurrentTabPanelName();
			mw.storage.session.set( 'mwpreferences-prevTab', value );
		} );

	} );
}() );
