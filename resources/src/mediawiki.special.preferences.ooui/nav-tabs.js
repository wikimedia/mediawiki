/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
const nav = require( './nav.js' );

$( () => {
	const $tabNavigationHint = nav.insertHints( mw.msg( 'prefs-tabs-navigation-hint' ) );

	const tabs = OO.ui.infuse( $( '.mw-prefs-tabs' ) );

	// Support: Chrome
	// https://bugs.chromium.org/p/chromium/issues/detail?id=1252507
	//
	// Infusing the tabs above involves detaching all the tabs' content from the DOM momentarily,
	// which causes the :target selector (used in mediawiki.special.preferences.styles.ooui.less)
	// not to match anything inside the tabs in Chrome. Twiddling location.href makes it work.
	// Only do it when a fragment is present, otherwise the page would be reloaded.
	if ( location.href.includes( '#' ) ) {
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
		const scrollTop = $( window ).scrollTop();
		// Changing the hash apparently causes keyboard focus to be lost?
		// Save and restore it. This makes no sense though.
		const active = document.activeElement;
		location.hash = '#' + panel.getName();
		if ( active ) {
			active.focus();
		}
		$( window ).scrollTop( scrollTop );
	}

	tabs.on( 'set', onTabPanelSet );

	// Hash navigation callback
	const setSection = function ( sectionName, fieldset ) {
		tabs.setTabPanel( sectionName );
		enhancePanel( tabs.getCurrentTabPanel() );
		// Scroll to a fieldset if provided.
		if ( fieldset ) {
			fieldset.scrollIntoView();
		}
	};

	// onSubmit callback
	const onSubmit = function () {
		const value = tabs.getCurrentTabPanelName();
		mw.storage.session.set( 'mwpreferences-prevTab', value );
	};

	nav.onLoad( setSection, 'mw-prefsection-personal' );

	nav.restorePrevSection( setSection, onSubmit );

	// Search index
	let index, texts;
	function buildIndex() {
		index = {};
		const $fields = tabs.contentPanel.$element.find( '[class^=mw-htmlform-field-]:not( .mw-prefs-search-noindex )' );
		const $descFields = $fields.filter(
			'.oo-ui-fieldsetLayout-group > .oo-ui-widget > .mw-htmlform-field-HTMLInfoField'
		);
		$fields.not( $descFields ).each( function () {
			let $field = $( this );
			const $wrapper = $field.parents( '.mw-prefs-fieldset-wrapper' );
			const $tabPanel = $field.closest( '.oo-ui-tabPanelLayout' );
			const $labels = $field.find(
				'.oo-ui-labelElement-label, .oo-ui-textInputWidget .oo-ui-inputWidget-input, p'
			).add(
				$wrapper.find( '> .oo-ui-fieldsetLayout > .oo-ui-fieldsetLayout-header .oo-ui-labelElement-label' )
			);
			$field = $field.add( $tabPanel.find( $descFields ) );

			function addToIndex( $label, $highlight ) {
				const text = $label.val() || $label[ 0 ].textContent.toLowerCase().trim().replace( /\s+/, ' ' );
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

			$labels.each( function () {
				addToIndex( $( this ) );

				// Check if there we are in an infusable dropdown and collect other options
				const $dropdown = $( this ).closest( '.oo-ui-dropdownInputWidget[data-ooui],.mw-widget-selectWithInputWidget[data-ooui]' );
				if ( $dropdown.length ) {
					const dropdown = OO.ui.infuse( $dropdown[ 0 ] );
					const dropdownWidget = ( dropdown.dropdowninput || dropdown ).dropdownWidget;
					if ( dropdownWidget ) {
						dropdownWidget.getMenu().getItems().forEach( ( option ) => {
							// Highlight the dropdown handle and the matched label, for when the dropdown is opened
							addToIndex( option.$label, dropdownWidget.$handle );
							addToIndex( option.$label, option.$label );
						} );
					}
				}
			} );
		} );
		mw.hook( 'prefs.search.buildIndex' ).fire( index );
		texts = Object.keys( index );
	}

	function infuseAllPanels() {
		tabs.stackLayout.items.forEach( ( tabPanel ) => {
			const wasVisible = tabPanel.isVisible();
			// Force panel to be visible while infusing
			tabPanel.toggle( true );

			enhancePanel( tabPanel );

			// Restore visibility
			tabPanel.toggle( wasVisible );
		} );
	}

	const searchWrapper = OO.ui.infuse( $( '.mw-prefs-search' ) );
	const search = searchWrapper.fieldWidget;
	search.$input.on( 'focus', () => {
		if ( !index ) {
			// Lazy-build index on first focus
			// Infuse all widgets as we may end up showing a large subset of them
			infuseAllPanels();
			buildIndex();
		}
	} );
	const $noResults = $( '<div>' ).addClass( 'mw-prefs-noresults' ).text( mw.msg( 'searchprefs-noresults' ) );
	search.on( 'change', ( val ) => {
		if ( !index ) {
			// In case 'focus' hasn't fired yet
			infuseAllPanels();
			buildIndex();
		}
		const isSearching = !!val;
		tabs.$element.toggleClass( 'mw-prefs-tabs-searching', isSearching );
		tabs.tabSelectWidget.toggle( !isSearching );
		tabs.contentPanel.setContinuous( isSearching );

		$( '.mw-prefs-search-matched' ).removeClass( 'mw-prefs-search-matched' );
		$( '.mw-prefs-search-highlight' ).removeClass( 'mw-prefs-search-highlight' );
		let countResults = 0;
		if ( isSearching ) {
			val = val.toLowerCase();
			texts.forEach( ( text ) => {
				// TODO: Could use Intl.Collator.prototype.compare like OO.ui.mixin.LabelElement.static.highlightQuery
				// but might be too slow.
				if ( text.includes( val ) ) {
					index[ text ].forEach( ( item ) => {
						// eslint-disable-next-line no-jquery/no-class-state
						if ( !item.$field.hasClass( 'mw-prefs-search-matched' ) ) {
							// Count each matched preference as one result, not the number of matches in the text
							countResults++;
						}
						item.$highlight.addClass( 'mw-prefs-search-highlight' );
						item.$field.addClass( 'mw-prefs-search-matched' );
						item.$wrapper.addClass( 'mw-prefs-search-matched' );
						item.$tabPanel.addClass( 'mw-prefs-search-matched' );
					} );
				}
			} );
		}

		// We hide the tabs when searching, so hide this tip about them as well
		$tabNavigationHint.toggle( !isSearching );
		// Update invisible label to give screenreader users live feedback while they're typing
		if ( !isSearching ) {
			searchWrapper.setLabel( mw.msg( 'searchprefs' ) );
		} else if ( countResults === 0 ) {
			searchWrapper.setLabel( mw.msg( 'searchprefs-noresults' ) );
		} else {
			searchWrapper.setLabel( mw.msg( 'searchprefs-results', countResults ) );
		}

		// Update visible label
		if ( isSearching && countResults === 0 ) {
			tabs.$element.append( $noResults );
		} else {
			$noResults.detach();
		}

		// Make Enter jump to the results, if there are any
		if ( isSearching && countResults !== 0 ) {
			search.on( 'enter', () => {
				tabs.focusFirstFocusable();
			} );
		} else {
			search.off( 'enter' );
		}

	} );

	// Handle the initial value in case the user started typing before this JS code loaded,
	// or the browser restored the value for a closed tab
	if ( search.getValue() ) {
		search.emit( 'change', search.getValue() );
	}

} );
