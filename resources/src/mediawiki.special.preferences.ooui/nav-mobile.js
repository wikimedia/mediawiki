/*!
 * JavaScript for Special:Preferences: mobileLayout.
 */
const nav = require( './nav.js' );

nav.insertHints( mw.msg( 'prefs-sections-navigation-hint' ) );

// Define a window manager to control the dialogs
const dialogFactory = new OO.Factory();
const windowManager = new OO.ui.WindowManager( { factory: dialogFactory } );
windowManager.on( 'opening', ( win ) => {
	if ( !win.$body.data( 'mw-section-infused' ) ) {
		win.$body.removeClass( 'mw-htmlform-autoinfuse-lazy' );
		mw.hook( 'htmlform.enhance' ).fire( win.$body );
		win.$body.data( 'mw-section-infused', true );
	}
} );

// Navigation callback
const setSection = function ( sectionName, fieldset ) {
	// strip possible prefixes from the section to normalize it
	const section = sectionName.replace( 'mw-prefsection-', '' ).replace( 'mw-mobile-prefs-', '' );
	const win = windowManager.getCurrentWindow();
	if ( win && win.constructor.static.name !== 'mw-mobile-prefs-' + section ) {
		windowManager.closeWindow( win );
	}
	// Work in the window isn't necessarily done when 'then` fires
	windowManager.openWindow( 'mw-mobile-prefs-' + section ).opened.then( () => {
		// Scroll to a fieldset if provided.
		if ( fieldset ) {
			// setTimout is ie11-compatible and queues up tasks for async exec
			setTimeout( () => {
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
 * Configure and register a dialog for a pref section
 */
function createSectionDialog( sectionId, sectionTitle, sectionBody ) {
	function PrefDialog() {
		const conf = { classes: [ 'overlay-content', 'mw-mobile-pref-window' ] };
		PrefDialog.super.call( this, conf );
	}

	OO.inheritClass( PrefDialog, OO.ui.ProcessDialog );
	PrefDialog.static.name = sectionId;
	PrefDialog.static.size = 'larger';
	PrefDialog.static.title = sectionTitle;
	PrefDialog.static.actions = [
		{ action: 'cancel', label: mw.msg( 'prefs-back-title' ), flags: [ 'safe', 'close' ] }
	];
	PrefDialog.prototype.initialize = function () {
		this.name = sectionId;
		PrefDialog.super.prototype.initialize.call( this );
		this.$body.append( sectionBody );
		this.content = new OO.ui.PanelLayout( { padded: true, expanded: true } );
		this.$body.addClass( 'mw-mobile-pref-dialog-body' );
	};
	PrefDialog.prototype.getActionProcess = function ( action ) {
		if ( action ) {
			return new OO.ui.Process( () => {
				this.close( { action: action } );
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
	const preferencesForm = document.getElementById( 'mw-prefs-form' );
	const prefButtons = preferencesForm.querySelector( '.mw-htmlform-submit-buttons' );
	const sections = preferencesForm.querySelectorAll( '.mw-mobile-prefsection' );

	// Move the form buttons (such as save) into the dialog after opening.
	windowManager.on( 'opening', ( win, opened ) => {
		if ( opened ) {
			win.$foot[ 0 ].appendChild( prefButtons );
		}
	} );
	// Move the form buttons (such as save) back to the main form while closing.
	windowManager.on( 'closing', ( _win, closed ) => {
		document.getElementById( 'preferences' ).appendChild( prefButtons );
		if ( closed ) {
			location.hash = '';
		}
	} );
	// Add the window manager to the form
	$( preferencesForm ).append( windowManager.$element );
	// Add event listeners and register a dialog for each section
	Array.prototype.forEach.call( sections, ( section ) => {
		const sectionContent = document.getElementById( section.id + '-content' );
		const sectionBody = sectionContent.querySelector( 'div > div.oo-ui-widget' );
		const sectionText = sectionContent.querySelector( '.mw-prefs-title' ).textContent;
		createSectionDialog( section.id, sectionText, sectionBody );
	} );
	const prefSelect = OO.ui.infuse( $( '.mw-mobile-prefs-sections' ) );
	prefSelect.aggregate( {
		click: 'itemClick'
	} );
	prefSelect.on( 'itemClick', ( button ) => {
		setSection( button.getData() );
	} );

}

// DOM-dependant code
$( () => {
	initDialogs();
	nav.onLoad( setSection );
} );
