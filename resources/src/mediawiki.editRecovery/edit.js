/**
 * In-progress edit recovery for action=edit
 */
'use strict';

const storage = require( './storage.js' );

const inputFields = {};
const fieldNamePrefix = 'field_';
var hasLoaded = false;
var changeDebounceTimer = null;

// Number of miliseconds to debounce form input.
const debounceTime = 5000;

mw.hook( 'wikipage.editform' ).add( onLoadHandler );

const windowManager = OO.ui.getWindowManager();
windowManager.addWindows( [ new mw.widgets.AbandonEditDialog() ] );

function onLoadHandler( $editForm ) {
	if ( hasLoaded ) {
		return;
	}
	hasLoaded = true;
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

	// Open indexedDB database and load any saved data that might be there.
	const pageName = mw.config.get( 'wgPageName' );
	const section = inputFields.wpSection.value || null;
	storage.openDatabase().then( function () {
		// Check for, and delete, any expired data.
		storage.deleteExpiredData();
		storage.loadData( pageName, section ).then( onLoadData );
	} );

	// Set up cancel handler to delete data.
	const cancelButton = OO.ui.infuse( $editForm.find( '#mw-editform-cancel' )[ 0 ] );
	cancelButton.on( 'click', function () {
		windowManager.openWindow( 'abandonedit' ).closed.then( function ( data ) {
			if ( data && data.action === 'discard' ) {
				storage.deleteData( pageName, section ).finally( function () {
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
	// If there is data stored, and we're not on an edit conflict resolution form, load the data into the form.
	if ( pageData !== undefined && mw.config.get( 'wgEditMessage' ) !== 'editconflict' ) {
		loadData( pageData );
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
			$field.textSelection( 'setSelection' );
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
	const pageData = {};
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
		pageData[ fieldNamePrefix + fieldName ] = newValue;
	} );
	const pageName = mw.config.get( 'wgPageName' );
	const section = inputFields.wpSection.value !== undefined ? inputFields.wpSection.value : null;
	storage.saveData( pageName, section, pageData );
}
