/*!
 * JavaScript for Special:Preferences: mobileLayout.
 */
( function () {
	var nav = require( './nav.js' );
	// Define a window manager to control the dialogs
	var dialogFactory = new OO.Factory();
	var windowManager = new OO.ui.WindowManager( { factory: dialogFactory } );
	// Navigation callback
	var setSection = function ( sectionName, fieldset ) {
		// strip possible prefixes from the section to normalize it
		var section = sectionName.replace( 'mw-prefsection-', '' ).replace( 'mw-mobile-prefs-', '' );
		var win = windowManager.getCurrentWindow();
		if ( win && win.constructor.static.name !== 'mw-mobile-prefs-' + section ) {
			windowManager.closeWindow( win );
		}
		// Work in the window isn't necessarily done when 'then` fires
		windowManager.openWindow( 'mw-mobile-prefs-' + section ).opened.then( function () {
			// Scroll to a fieldset if provided.
			if ( fieldset ) {
				// setTimout is ie11-compatible and queues up tasks for async exec
				setTimeout( function () {
					fieldset.scrollIntoView( { behavior: 'smooth' } );
				} );
			}
		} );
		if ( nav.switchingNoHash ) {
			return;
		}
		location.hash = '#mw-prefsection-' + section;
	};
	/*
	 * Add a ToggleSwitchWidget to control each checkboxWidget
	 * Hide each checkboxWidget
	 */
	function insertToggles( checkboxes ) {
		Array.prototype.forEach.call( checkboxes, function ( checkboxElement ) {
			var checkboxWidget = OO.ui.infuse( checkboxElement );
			var toggleSwitchWidget = new OO.ui.ToggleSwitchWidget( {
				value: checkboxWidget.selected,
				disabled: checkboxWidget.disabled
			} );
			checkboxWidget.on( 'disable', function ( state ) {
				toggleSwitchWidget.setDisabled( state );
			} );
			toggleSwitchWidget.on( 'change', function ( value ) {
				checkboxWidget.setSelected( value );
			} );
			checkboxElement = checkboxWidget.$element[ 0 ];
			checkboxElement.insertAdjacentElement( 'afterend', toggleSwitchWidget.$element[ 0 ] );
			checkboxElement.classList.add( 'hidden' );
		} );
	}
	/*
	 * Configure and register a dialog for a pref section
	 */
	function createSectionDialog( sectionId, sectionTitle, sectionBody ) {
		function PrefDialog() {
			var conf = { classes: [ 'overlay-content', 'mw-mobile-pref-window' ] };
			PrefDialog.super.call( this, conf );
		}

		OO.inheritClass( PrefDialog, OO.ui.ProcessDialog );
		PrefDialog.static.name = sectionId;
		PrefDialog.static.escapable = true;
		PrefDialog.static.size = 'larger';
		PrefDialog.static.title = sectionTitle;
		PrefDialog.static.actions = [
			{ action: 'cancel', label: mw.msg( 'prefs-back-title' ), flags: [ 'safe', 'close' ] }
		];
		PrefDialog.prototype.initialize = function () {
			// T334260 Mobile format breaks global preferences;
			if ( mw.config.get( 'wgCanonicalSpecialPageName' ) === 'Preferences' ) {
				insertToggles( sectionBody.querySelectorAll( 'span.oo-ui-checkboxInputWidget[data-ooui]' ) );
			}
			this.name = sectionId;
			PrefDialog.super.prototype.initialize.call( this );
			this.$body.append( sectionBody );
			this.content = new OO.ui.PanelLayout( { padded: true, expanded: true } );
			this.$body.addClass( 'mw-mobile-pref-dialog-body' );
		};
		PrefDialog.prototype.getActionProcess = function ( action ) {
			var dialog = this;
			if ( action ) {
				return new OO.ui.Process( function () {
					dialog.close( { action: action } );
				} );
			}
			return PrefDialog.super.prototype.getActionProcess.call( this, action );
		};

		dialogFactory.register( PrefDialog );
	}

	/*
	 * Initialize Dialogs for all pref sections
	 */
	function initDialogs() {
		// Query the document once, then query that returned element afterwards.
		var preferencesForm = document.getElementById( 'mw-prefs-form' );
		var prefButtons = preferencesForm.querySelector( '.mw-htmlform-submit-buttons' );
		var sections = preferencesForm.querySelectorAll( '.mw-mobile-prefsection' );
		// Move the form buttons (such as save) into the dialog after opening.
		windowManager.on( 'opening', function ( win, opened ) {
			if ( opened ) {
				win.$foot[ 0 ].appendChild( prefButtons );
			}
		} );
		// Move the form buttons (such as save) back to the main form while closing.
		windowManager.on( 'closing', function ( _win, closed ) {
			document.getElementById( 'preferences' ).appendChild( prefButtons );
			if ( closed ) {
				location.hash = '';
			}
		} );
		// Add the window manager to the form
		$( preferencesForm ).append( windowManager.$element );
		// Add event listeners and register a dialog for each section
		Array.prototype.forEach.call( sections, function ( section ) {
			var sectionContent = document.getElementById( section.id + '-content' );
			var sectionBody = sectionContent.querySelector( 'div > div.oo-ui-widget' );
			var sectionTitle = document.getElementById( section.id + '-title' ).textContent;
			document.getElementById( section.id ).addEventListener( 'click', function () {
				setSection( section.id );
			} );
			createSectionDialog( section.id, sectionTitle, sectionBody );
		} );
	}
	// DOM-dependant code
	$( function () {
		initDialogs();
		nav.onLoad( setSection );
	} );
}() );
