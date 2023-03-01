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
			// Scroll to a fieldset if provided.
			if ( fieldset ) {
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

		// Search index
		var index, texts;
		function buildIndex() {
			index = {};
			var $fields = tabs.contentPanel.$element.find( '[class^=mw-htmlform-field-]:not( #mw-prefsection-betafeatures .mw-htmlform-field-HTMLInfoField )' );
			$fields.each( function () {
				var $field = $( this );
				var $wrapper = $field.parents( '.mw-prefs-fieldset-wrapper' );
				var $tabPanel = $field.closest( '.oo-ui-tabPanelLayout' );
				$field.find( '.oo-ui-labelElement-label, .oo-ui-textInputWidget .oo-ui-inputWidget-input, p' ).add(
					$wrapper.find( '> .oo-ui-fieldsetLayout > .oo-ui-fieldsetLayout-header .oo-ui-labelElement-label' )
				).each( function () {

					function addToIndex( $label, $highlight ) {
						var text = $label.val() || $label[ 0 ].innerText.toLowerCase().trim().replace( /\s+/, ' ' );
						if ( text ) {
							index[ text ] = index[ text ] || [];
							index[ text ].push( {
								$highlight: $highlight || $label,
								$field: $field,
								$wrapper: $wrapper,
								$tabPanel: $tabPanel
							} );
						}
					}

					addToIndex( $( this ) );

					// Check if there we are in an infusable dropdown and collect other options
					var $dropdown = $( this ).closest( '.oo-ui-dropdownInputWidget[data-ooui],.mw-widget-selectWithInputWidget[data-ooui]' );
					if ( $dropdown.length ) {
						var dropdown = OO.ui.infuse( $dropdown[ 0 ] );
						var dropdownWidget = ( dropdown.dropdowninput || dropdown ).dropdownWidget;
						if ( dropdownWidget ) {
							dropdownWidget.getMenu().getItems().forEach( function ( option ) {
								// Highlight the dropdown handle and the matched label, for when the dropdown is opened
								addToIndex( option.$label, dropdownWidget.$handle );
								addToIndex( option.$label, option.$label );
							} );
						}
					}
				} );
			} );
			texts = Object.keys( index );
		}

		function infuseAllPanels() {
			tabs.stackLayout.items.forEach( function ( tabPanel ) {
				var wasVisible = tabPanel.isVisible();
				// Force panel to be visible while infusing
				tabPanel.toggle( true );

				enhancePanel( tabPanel );

				// Restore visibility
				tabPanel.toggle( wasVisible );
			} );
		}

		var search = OO.ui.infuse( $( '.mw-prefs-search' ) ).fieldWidget;
		search.$input.on( 'focus', function () {
			if ( !index ) {
				// Lazy-build index on first focus
				// Infuse all widgets as we may end up showing a large subset of them
				infuseAllPanels();
				buildIndex();
			}
		} );
		var $noResults = $( '<div>' ).addClass( 'mw-prefs-noresults' ).text( mw.msg( 'searchprefs-noresults' ) );
		search.on( 'change', function ( val ) {
			if ( !index ) {
				// In case 'focus' hasn't fired yet
				infuseAllPanels();
				buildIndex();
			}
			var isSearching = !!val;
			tabs.$element.toggleClass( 'mw-prefs-tabs-searching', isSearching );
			tabs.tabSelectWidget.toggle( !isSearching );
			$( '.mw-prefs-search-matched' ).removeClass( 'mw-prefs-search-matched' );
			$( '.mw-prefs-search-highlight' ).removeClass( 'mw-prefs-search-highlight' );
			var hasResults = false;
			if ( isSearching ) {
				val = val.toLowerCase();
				texts.forEach( function ( text ) {
					// TODO: Could use Intl.Collator.prototype.compare like OO.ui.mixin.LabelElement.static.highlightQuery
					// but might be too slow.
					if ( text.indexOf( val ) !== -1 ) {
						index[ text ].forEach( function ( item ) {
							item.$highlight.addClass( 'mw-prefs-search-highlight' );
							item.$field.addClass( 'mw-prefs-search-matched' );
							item.$wrapper.addClass( 'mw-prefs-search-matched' );
							item.$tabPanel.addClass( 'mw-prefs-search-matched' );
						} );
						hasResults = true;
					}
				} );
			}
			if ( isSearching && !hasResults ) {
				tabs.$element.append( $noResults );
			} else {
				$noResults.detach();
			}
		} );

		// Handle the initial value in case the user started typing before this JS code loaded,
		// or the browser restored the value for a closed tab
		if ( search.getValue() ) {
			search.emit( 'change', search.getValue() );
		}

	} );
}() );
