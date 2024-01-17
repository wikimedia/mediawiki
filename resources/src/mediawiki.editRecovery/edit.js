/**
 * In-progress edit recovery for action=edit
 */
'use strict';

const storage = require( './storage.js' );
const LoadNotification = require( './LoadNotification.js' );

const inputFields = {};
const fieldNamePrefix = 'field_';
var originalData = {};
var changeDebounceTimer = null;

// Number of miliseconds to debounce form input.
const debounceTime = 5000;

mw.hook( 'wikipage.editform' ).add( onLoadHandler );

const windowManager = OO.ui.getWindowManager();
windowManager.addWindows( [ new mw.widgets.AbandonEditDialog() ] );

function onLoadHandler( $editForm ) {
	mw.hook( 'wikipage.editform' ).remove( onLoadHandler );

	// Monitor all text-entry inputs for changes/typing.
	const inputsToMonitorSelector = 'textarea, select, input:not([type="hidden"], [type="submit"])';
	const $inputsToMonitor = $editForm.find( inputsToMonitorSelector );
	$inputsToMonitor.each( function ( _i, field ) {
		if ( field.classList.contains( 'oo-ui-inputWidget-input' ) ) {
			try {
				inputFields[ field.name ] = OO.ui.infuse( field.closest( '.oo-ui-widget' ) );
			} catch ( e ) {
				// Ignore any non-infusable widget because we won't be able to set its value.
			}
		} else {
			inputFields[ field.name ] = field;
		}
	} );
	// Save the contents of all of those, as well as the following hidden inputs.
	const inputsToSaveNames = [ 'wpSection', 'editRevId', 'oldid', 'parentRevId', 'format', 'model', 'mode' ];
	const $inputsToSave = $editForm.find( '[name="' + inputsToSaveNames.join( '"], [name="' ) + '"]' );
	$inputsToSave.each( function ( _i, field ) {
		inputFields[ field.name ] = field;
	} );

	// Store the original data for later comparing to the data-to-save. Use the defaultValue/defaultChecked in order to
	// avoid using any data remembered by the browser. Note that we have to be careful to store with the same types as
	// it will be done later, in order to correctly compare it (e.g. checkboxes as booleans).
	Object.keys( inputFields ).forEach( function ( fieldName ) {
		const field = inputFields[ fieldName ];
		if ( field.nodeName === 'INPUT' || field.nodeName === 'TEXTAREA' ) {
			if ( field.type === 'checkbox' ) {
				// Checkboxes (Minoredit and Watchthis are handled below as they are OOUI widgets).
				originalData[ fieldNamePrefix + fieldName ] = field.defaultChecked;
			} else {
				// Other HTMLInputElements.
				originalData[ fieldNamePrefix + fieldName ] = field.defaultValue;
			}
		} else if ( field.$input !== undefined ) {
			// OOUI widgets, which may not have been infused by this point.
			if ( field.$input[ 0 ].type === 'checkbox' ) {
				// Checkboxes.
				originalData[ fieldNamePrefix + fieldName ] = field.$input[ 0 ].defaultChecked;
			} else {
				// Other OOUI widgets.
				originalData[ fieldNamePrefix + fieldName ] = field.$input[ 0 ].defaultValue;
			}
		}
	} );

	// Open indexedDB database and load any saved data that might be there.
	const pageName = mw.config.get( 'wgPageName' );
	const section = inputFields.wpSection.value || null;
	// Set a short-lived (5m / see postEdit.js) localStorage item to indicate which section is being edited.
	if ( section ) {
		mw.storage.session.set( pageName + '-editRecoverySection', section, 300 );
	}
	storage.openDatabase().then( function () {
		// Check for and delete any expired data for any page, before loading any saved data for the current page.
		storage.deleteExpiredData().then( () => {
			storage.loadData( pageName, section ).then( onLoadData );
		} );
	} );

	// Set up cancel handler to delete data.
	const cancelButton = OO.ui.infuse( $editForm.find( '#mw-editform-cancel' )[ 0 ] );
	cancelButton.on( 'click', function () {
		windowManager.openWindow( 'abandonedit' ).closed.then( function ( data ) {
			if ( data && data.action === 'discard' ) {
				originalData = null;
				storage.deleteData( pageName, section ).finally( function () {
					mw.storage.session.remove( pageName + '-editRecoverySection' );
					// Release the beforeunload handler from mediawiki.action.edit.editWarning,
					// per the documentation there
					$( window ).off( 'beforeunload.editwarning' );
					location.href = cancelButton.getHref();
				} );
			}
		} );
	} );
}

function onLoadData( pageData ) {
	// If there is data stored, load it into the form.
	if ( pageData !== undefined ) {
		const oldPageData = getFormData();
		loadData( pageData );
		const loadNotification = new LoadNotification();
		const notification = loadNotification.getNotification();
		// On 'show changes'.
		loadNotification.getDiffButton().on( 'click', function () {
			$( '#wpDiff' ).trigger( 'click' );
		} );
		// On 'discard changes'.
		loadNotification.getDiscardButton().on( 'click', function () {
			loadData( oldPageData );
			storage.deleteData( mw.config.get( 'wgPageName' ) ).then( function () {
				notification.close();
			} );
		} );
	}

	// Add change handlers.
	Object.keys( inputFields ).forEach( function ( fieldName ) {
		const field = inputFields[ fieldName ];
		if ( field.nodeName !== undefined && field.nodeName === 'TEXTAREA' ) {
			field.addEventListener( 'input', fieldChangeHandler );
		} else if ( field instanceof OO.ui.Widget ) {
			field.on( 'change', fieldChangeHandler );
		} else {
			field.addEventListener( 'change', fieldChangeHandler );
		}
	} );
	// Also add handlers for when the window is closed or hidden. Saving the data at these points is not guaranteed to
	// work, but it often does and the save operation is atomic so there's no harm in trying.
	window.addEventListener( 'beforeunload', saveFormData );
	window.addEventListener( 'visibilitychange', saveFormData );

	/**
	 * Fired after EditRecovery has loaded any recovery data, added event handlers, etc.
	 *
	 * @event editRecovery_loadEnd
	 * @member mw.hook
	 * @param {Object} editRecovery
	 * @param {Function} editRecovery.fieldChangeHandler
	 */
	mw.hook( 'editRecovery.loadEnd' ).fire( { fieldChangeHandler: fieldChangeHandler } );
}

function loadData( pageData ) {
	Object.keys( inputFields ).forEach( function ( fieldName ) {
		if ( pageData[ fieldNamePrefix + fieldName ] === undefined ) {
			return;
		}
		const field = inputFields[ fieldName ];
		const $field = $( field );
		// Set the field value depending on what type of field it is.
		if ( field instanceof OO.ui.CheckboxInputWidget ) {
			// OOUI checkbox widgets.
			field.setSelected( pageData[ fieldNamePrefix + fieldName ] );
		} else if ( field instanceof OO.ui.Widget ) {
			// Other OOUI widgets.
			field.setValue( pageData[ fieldNamePrefix + fieldName ], field );
		} else if ( field.nodeName === 'TEXTAREA' ) {
			// Textareas (also reset caret location to top).
			$field.textSelection( 'setContents', pageData[ fieldNamePrefix + fieldName ] );
			$field.textSelection( 'setSelection', { start: 0 } );
		} else {
			// Anything else.
			field.value = pageData[ fieldNamePrefix + fieldName ];
		}
	} );
}

function fieldChangeHandler() {
	clearTimeout( changeDebounceTimer );
	changeDebounceTimer = setTimeout( saveFormData, debounceTime );
}

function saveFormData() {
	const pageName = mw.config.get( 'wgPageName' );
	const section = inputFields.wpSection.value !== undefined ? inputFields.wpSection.value : null;
	const pageData = getFormData();
	if ( originalData === null || JSON.stringify( pageData ) === JSON.stringify( originalData ) ) {
		// Delete the stored data if there's no change, or if we've flagged originalData as irrelevant.
		storage.deleteData( pageName, section );
		mw.storage.session.remove( 'EditRecovery-data-saved' );
	} else {
		storage.saveData( pageName, section, pageData );
		// Flag the data for deletion in the postEdit handler in ./postEdit.js
		mw.storage.session.set( 'EditRecovery-data-saved', true, 300 );
	}
}

/**
 * Get the current form data.
 *
 * @ignore
 * @return {Object}
 */
function getFormData() {
	const formData = {};
	Object.keys( inputFields ).forEach( function ( fieldName ) {
		const field = inputFields[ fieldName ];
		var newValue = null;
		if ( !( field instanceof OO.ui.Widget ) && field.nodeName !== undefined && field.nodeName === 'TEXTAREA' ) {
			// Text areas.
			newValue = $( field ).textSelection( 'getContents' );
		} else if ( field instanceof OO.ui.CheckboxInputWidget ) {
			// OOUI checkbox widgets.
			newValue = field.isSelected();
		} else if ( field instanceof OO.ui.Widget ) {
			// Other OOUI widgets.
			newValue = field.getValue();
		} else {
			// Anything else.
			newValue = field.value;
		}
		formData[ fieldNamePrefix + fieldName ] = newValue;
	} );
	return formData;
}
