/*!
 * JavaScript for Special:Preferences: mobileLayout.
 */
( function () {
	// Define a window manager to control the dialogs
	var dialogFactory = new OO.Factory();
	var windowManager = new OO.ui.WindowManager( { factory: dialogFactory } );
	/*
	 * Add a ToggleSwitchWidget to control each checkboxWidget
	 * Hide each checkboxWidget
	 */
	function insertToggles( checkboxes ) {
		Array.prototype.forEach.call( checkboxes, function ( checkboxWidget ) {
			var checkboxInput = checkboxWidget.querySelector( 'input' );
			var toggleSwitchWidget = new OO.ui.ToggleSwitchWidget( {
				value: checkboxInput.checked,
				disabled: checkboxInput.disabled
			} );
			toggleSwitchWidget.on( 'change', function ( value ) {
				toggleSwitchWidget.setValue( value );
				checkboxInput.checked = value;
			} );
			checkboxWidget.insertAdjacentElement( 'afterend', toggleSwitchWidget.$element[ 0 ] );
			checkboxWidget.classList.add( 'hidden' );
		} );
	}
	/*
	 * Configure and register a dialog for a pref section
	 */
	function sectionDialog( sectionId, sectionHead, sectionBody ) {
		function PrefDialog() {
			var conf = { classes: [ 'overlay-content', 'mw-mobile-pref-window' ] };
			PrefDialog.super.call( this, conf );
		}
		OO.inheritClass( PrefDialog, OO.ui.Dialog );
		PrefDialog.static.name = sectionId;
		PrefDialog.static.escapable = true;
		PrefDialog.static.size = 'larger';
		PrefDialog.prototype.initialize = function () {
			insertToggles( sectionBody.querySelectorAll( 'span.oo-ui-checkboxInputWidget' ) );
			this.name = sectionId;
			PrefDialog.super.prototype.initialize.call( this );
			this.$head.append( sectionHead );
			this.$head[ 0 ].classList.add( 'mw-mobile-pref-dialog-head' );
			this.$body.append( sectionBody );
			this.content = new OO.ui.PanelLayout( { padded: true, expanded: true } );
			this.$body[ 0 ].classList.add( 'mw-mobile-pref-dialog-body' );
		};

		dialogFactory.register( PrefDialog );
	}
	// DOM-dependant code
	$( function () {
		/*
		 * Initialize Dialogs for all pref sections
		 */
		function initDialogs() {
			// Query the document once, then query that returned element afterwards.
			var preferencesForm = document.querySelector( '#mw-prefs-form' );
			var prefButtons = preferencesForm.querySelector( 'div.mw-prefs-buttons' );
			var sections = preferencesForm.querySelectorAll( '.mw-mobile-prefsection' );
			// Move the form buttons (such as save) into the dialog after opening.
			windowManager.on( 'opening', function ( win, opened ) {
				if ( opened ) {
					win.$foot[ 0 ].appendChild( prefButtons );
				}
			} );
			// Move the form buttons (such as save) back to the main form while closing.
			windowManager.on( 'closing', function () {
				preferencesForm.querySelector( '#preferences' ).appendChild( prefButtons );
			} );
			// Add the window manager to the form
			$( preferencesForm ).append( windowManager.$element );
			// add event listeners and register a dialog for each section
			Array.prototype.forEach.call( sections, function ( section ) {
				var sectionContent = preferencesForm.querySelector( '#' + section.id + '-content' );
				var sectionBody = sectionContent.querySelector( 'div > div.oo-ui-widget' );
				var sectionHead = sectionContent.querySelector( '#' + section.id + '-head' );
				sectionHead.querySelector( '#' + section.id + '-back-button' ).addEventListener( 'click', function () {
					windowManager.closeWindow( section.id );
				} );
				preferencesForm.querySelector( '#' + section.id ).addEventListener( 'click', function () {
					windowManager.openWindow( section.id );
				} );
				sectionDialog( section.id, sectionHead, sectionBody );
			} );
		}
		initDialogs();
	} );
}() );
