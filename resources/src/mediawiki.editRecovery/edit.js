/**
 * In-progress edit recovery for action=edit
 *
 * @ignore
 */
const storage = require( './storage.js' );
const LoadNotification = require( './LoadNotification.js' );

const pageName = mw.config.get( 'wgPageName' );
const wiki = mw.config.get( 'wgDBname' );
const section = $( 'input[name="wpSection"]' ).val() || null;
const inputFields = {};
const fieldNamePrefix = 'field_';
let originalData = {};
let changeDebounceTimer = null;

// Number of miliseconds to debounce form input.
const debounceTime = 5000;

// This module is loaded for every edit form, but not all should have Edit Recovery functioning.
const wasPosted = mw.config.get( 'wgEditRecoveryWasPosted' );
const isUndo = $( 'input[name="wpUndoAfter"]' ).length > 0;
const isOldRevision = $( 'input[name="oldid"]' ).val() > 0;
const isConflict = mw.config.get( 'wgEditMessage' ) === 'editconflict';
const useEditRecovery = !isUndo && !isOldRevision && !isConflict;
if ( useEditRecovery ) {
	mw.hook( 'wikipage.editform' ).add( init );
} else {
	// Always remove the data-saved flag when editing without Edit Recovery.
	// It may have been set by a previous editing session (within 5 minutes) that did use ER.
	mw.storage.session.remove( 'EditRecovery-data-saved' );
}

const windowManager = OO.ui.getWindowManager();
windowManager.addWindows( [ new mw.widgets.AbandonEditDialog() ] );

/**
 * Initialise when the wikipage.editform hook first fires
 *
 * @ignore
 * @param {jQuery} $editForm
 */
function init( $editForm ) {
	mw.hook( 'wikipage.editform' ).remove( init );

	// Monitor all text-entry inputs for changes/typing.
	const inputsToMonitorSelector = 'textarea, select, input:not([type="hidden"], [type="submit"])';
	const $inputsToMonitor = $editForm.find( inputsToMonitorSelector );
	$inputsToMonitor.each( ( _i, field ) => {
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
	const inputsToSaveNames = [ 'wpSection', 'editRevId', 'oldid', 'parentRevId', 'format', 'model' ];
	const $inputsToSave = $editForm.find( '[name="' + inputsToSaveNames.join( '"], [name="' ) + '"]' );
	$inputsToSave.each( ( _i, field ) => {
		inputFields[ field.name ] = field;
	} );

	// Store the original data for later comparing to the data-to-save. Use the defaultValue/defaultChecked in order to
	// avoid using any data remembered by the browser. Note that we have to be careful to store with the same types as
	// it will be done later, in order to correctly compare it (e.g. checkboxes as booleans).
	for ( const fieldName in inputFields ) {
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
	}

	// Set a short-lived (5m / see postEdit.js) localStorage item to indicate which section is being edited.
	if ( section ) {
		mw.storage.session.set( pageName + '-editRecoverySection', section, 300 );
	}
	// Open indexedDB database and load any saved data that might be there.
	storage.openDatabase().then( () => {
		// Check for and delete any expired data for any page, before loading any saved data for the current page.
		storage.deleteExpiredData().then( () => {
			storage.loadData( pageName, section ).then( loadDataSuccess );
		} );
	} );

	// Set up cancel handler to delete data.
	const cancelButton = OO.ui.infuse( $editForm.find( '#mw-editform-cancel' )[ 0 ] );
	cancelButton.on( 'click', () => {
		windowManager.openWindow( 'abandonedit' ).closed.then( ( data ) => {
			if ( data && data.action === 'discard' ) {
				// Note that originalData is used below in loadDataSuccess() but that's always called before this method.
				// Here we set originalData to null in order to signal to saveFormData() to deleted the stored data.
				originalData = null;
				storage.deleteData( pageName, section ).finally( () => {
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

/**
 * loadData promise resolved successfully
 *
 * @ignore
 * @param {Object|undefined} pageData Page data, undefined if none found
 */
function loadDataSuccess( pageData ) {
	if ( wasPosted ) {
		// If this is a POST request, save the current data (e.g. from a preview).
		saveFormData();
	}
	// If there is data stored, load it into the form.
	if ( !wasPosted && pageData !== undefined && !isSameAsOriginal( pageData, true ) ) {
		const loadNotification = new LoadNotification( {
			differentRev: originalData.field_parentRevId !== pageData.field_parentRevId
		} );

		// statsv: Track the number of times the edit recovery notification is shown.
		mw.track( `counter.MediaWiki.edit_recovery.show.by_wiki.${ wiki }` );
		mw.track( 'stats.mediawiki_editrecovery_prompt_total', 1, { action: 'show', wiki } );

		const notification = loadNotification.getNotification();
		// On 'restore changes'.
		loadNotification.getRecoverButton().on( 'click', () => {
			recover( pageData );
			notification.close();
			// statsv: Track the number of times the edit recovery data is recovered.
			mw.track( `counter.MediaWiki.edit_recovery.recover.by_wiki.${ wiki }` );
			mw.track( 'stats.mediawiki_editrecovery_prompt_total', 1, { action: 'recover', wiki } );
		} );
		// On 'discard changes'.
		loadNotification.getDiscardButton().on( 'click', () => {
			storage.deleteData( pageName, section ).then( () => {
				notification.close();
			} );
			// statsv: Track the number of times the edit recovery data is discarded.
			mw.track( `counter.MediaWiki.edit_recovery.discard.by_wiki.${ wiki }` );
			mw.track( 'stats.mediawiki_editrecovery_prompt_total', 1, { action: 'discard', wiki } );
		} );
	}

	// Add change handlers.
	for ( const fieldName in inputFields ) {
		const field = inputFields[ fieldName ];
		if ( field.nodeName !== undefined && field.nodeName === 'TEXTAREA' ) {
			field.addEventListener( 'input', fieldChangeHandler );
		} else if ( field instanceof OO.ui.Widget ) {
			field.on( 'change', fieldChangeHandler );
		} else {
			field.addEventListener( 'change', fieldChangeHandler );
		}
	}
	// Also add handlers for when the window is closed or hidden. Saving the data at these points is not guaranteed to
	// work, but it often does and the save operation is atomic so there's no harm in trying.
	window.addEventListener( 'beforeunload', saveFormData );
	window.addEventListener( 'blur', saveFormData );

	/**
	 * Fired after EditRecovery has loaded any recovery data, added event handlers, etc.
	 *
	 * @event ~'editRecovery.loadEnd'
	 * @memberof Hooks
	 * @param {Object} editRecovery
	 * @param {Function} editRecovery.fieldChangeHandler
	 */
	mw.hook( 'editRecovery.loadEnd' ).fire( { fieldChangeHandler: fieldChangeHandler } );
}

/**
 * Recover specified page data
 *
 * @ignore
 * @param {Object} pageData Page data
 */
function recover( pageData ) {
	for ( const fieldName in inputFields ) {
		if ( pageData[ fieldNamePrefix + fieldName ] === undefined ) {
			continue;
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
	}
}

/**
 * Handle an edit form field changing
 *
 * @ignore
 */
function fieldChangeHandler() {
	clearTimeout( changeDebounceTimer );
	changeDebounceTimer = setTimeout( saveFormData, debounceTime );
}

/**
 * Compare a set of form field values to their original values (as at page load time).
 *
 * @ignore
 * @param {Object} pageData The page data to compare to the original.
 * @param {boolean} [ignoreRevIds=false] Do not use parent revision info when determining similarity.
 * @return {boolean}
 */
function isSameAsOriginal( pageData, ignoreRevIds = false ) {
	for ( const fieldName in inputFields ) {
		if ( ignoreRevIds && ( fieldName === 'editRevId' || fieldName === 'parentRevId' ) ) {
			continue;
		}
		// Trim trailing whitespace from string fields, to approximate what PHP does when saving.
		let currentVal = pageData[ fieldNamePrefix + fieldName ];
		if ( typeof currentVal === 'string' ) {
			currentVal = currentVal.replace( /\s+$/, '' );
		}
		let originalVal = originalData[ fieldNamePrefix + fieldName ];
		if ( typeof originalVal === 'string' ) {
			originalVal = originalVal.replace( /\s+$/, '' );
		}
		if ( currentVal !== originalVal ) {
			return false;
		}
	}
	return true;
}

/**
 * Save the current edit form state in the storage backend
 *
 * @ignore
 */
function saveFormData() {
	const pageData = getFormData();
	if ( ( originalData === null || isSameAsOriginal( pageData ) ) && !wasPosted ) {
		// Delete the stored data if there's no change,
		// or if we've flagged originalData as irrelevant,
		// or if we can't determine this because this page was POSTed.
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
	for ( const fieldName in inputFields ) {
		const field = inputFields[ fieldName ];
		let newValue = null;
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
	}
	return formData;
}
