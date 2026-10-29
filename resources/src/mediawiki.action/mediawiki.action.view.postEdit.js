( function () {
	'use strict';

	/**
	 * @module ~mediawiki.action.view.postEdit
	 */

	/**
	 * @enum {string}
	 * @memberof module:mediawiki.action.view.postEdit
	 */
	const ACTION = {
		SAVED: 'saved',
		CREATED: 'created',
		RESTORED: 'restored',
		PUBLISHED: 'published'
	};

	/**
	 * @typedef {Object} Context Optional context that can be passed to
	 *  {@link module:mediawiki.action.view.postEdit.fireHook} or
	 *  {@link module:mediawiki.action.view.postEdit.fireHookOnPageReload}
	 * @property {string|jQuery|Array} [message] The message that listeners should use when
	 *  displaying post-edit notifications. String for plain text, or use array or jQuery object to
	 *  pass DOM nodes
	 * @property {boolean} [tempUserCreated=false] Whether a temporary account was created during
	 *  the edit
	 * @property {string} [editorName] The machine-readable name of the editor that was used to make
	 *  the edit
	 * @property {number} [revisionId=wgRevisionId] The revision ID of the page being edited
	 * @property {number} [curRevisionId=wgCurRevisionId] The latest revision ID of the page being
	 *  edited
	 * @property {boolean} [isRollback=false] Whether the edit was a rollback
	 *
	 * @memberof module:mediawiki.action.view.postEdit
	 */

	/**
	 * @typedef {Object} Payload Optional payload that can be passed when firing the `postEdit` hook
	 * @property {string|jQuery|Array} message The message that listeners should use when
	 *  displaying post-edit notifications. String for plain text, or use array or jQuery object to
	 *  pass DOM nodes
	 * @property {boolean} tempUserCreated Whether a temporary account was created during
	 *  the edit
	 * @property {number} revisionId The revision ID of the page being edited
	 * @property {number} curRevisionId The latest revision ID of the page being
	 *  edited
	 * @property {string} [editorName] The machine-readable name of the editor that was used to make
	 *  the edit
	 * @property {boolean} isRollback Whether the edit was a rollback
	 *
	 * @memberof module:mediawiki.action.view.postEdit
	 */

	const VALID_ACTIONS = Object.values( ACTION );

	/**
	 * Fired after an edit was successfully saved.
	 *
	 * This hook should not be fired for null edits.
	 *
	 * This hook shouldn't be fired directly. Instead, it should be fired using
	 * {@link module:mediawiki.action.view.postEdit.fireHook} or
	 * {@link module:mediawiki.action.view.postEdit.fireHookOnPageReload}.
	 *
	 * @example
	 * const { fireHook, SAVED } = require( 'mediawiki.action.view.postEdit' );
	 * fireHook( SAVED );
	 *
	 * @event ~'postEdit'
	 * @memberof Hooks
	 * @param {module:mediawiki.action.view.postEdit.Payload} [payload]
	 */

	/**
	 * After the listener for #postEdit removes the notification.
	 *
	 * @deprecated
	 * @event ~'postEdit.afterRemoval'
	 * @memberof Hooks
	 */

	/**
	 * @typedef {Object} Config
	 * @property {boolean} EditSubmitButtonLabelPublish
	 *
	 * @memberof module:mediawiki.action.view.postEdit
	 */

	/** @type {module:mediawiki.action.view.postEdit.Config} */
	let config = require( './config.json' );

	const storageKey = 'mw-PostEdit' + mw.config.get( 'wgPageName' );

	/**
	 * Flatten a message from the 'postEdit' hook into plain text.
	 *
	 * Listeners may pass 'data.message' as a string, a jQuery object or an array of nodes,
	 * but the popover title only renders a string.
	 *
	 * @param {string|jQuery|Array|Node} message
	 * @return {string}
	 */
	function getPlainText( message ) {
		if ( typeof message === 'string' ) {
			return message;
		}
		if ( Array.isArray( message ) ) {
			return message.map( getPlainText ).join( '' );
		}
		return $( message ).text();
	}

	function showConfirmation( data ) {
		data = data || {};

		const label = data.message || mw.message(
			config.EditSubmitButtonLabelPublish ?
				'postedit-confirmation-published' :
				'postedit-confirmation-saved',
			data.user || mw.user,
			mw.config.get( 'wgRevisionId' )
		);
		// Only set when 'label' is an mw.Message; listeners may pass a plain string, jQuery
		// object or array of nodes as 'data.message' instead.
		const { key } = label;

		// Show a bottom sheet popover with benefits for temp users
		if ( data.tempUserCreated ) {
			// mediawiki.action.view.postEdit is loaded two times on temporary account auto-creation, avoid
			// displaying the confirmation until the temp account is attached (user menu is present in nav).
			if ( !mw.user.isTemp() ) {
				return;
			}
			mw.tempUserCreated.showCondensedPopup( {
				classes: [ 'postedit-tempusercreated' ],
				// The following messages can be used here:
				// * postedit-confirmation-published-title
				// * postedit-confirmation-saved-title
				// * postedit-confirmation-created-title
				// * postedit-confirmation-restored-title
				title: key ? mw.msg( key + '-title' ) : getPlainText( label ),
				content: [
					mw.message( 'postedit-temp-created-createaccount-benefits' ).text(),
					mw.message( 'postedit-temp-created-createaccount-benefit-1' ).text(),
					mw.message( 'postedit-temp-created-createaccount-benefit-2' ).text(),
					mw.message( 'postedit-temp-created-createaccount-benefit-3' ).text()
				],
				primaryActionLabel: mw.message( 'createaccount' ).text(),
				primaryActionUrl: mw.util.getUrl( 'Special:CreateAccount' )
			} );
		} else {
			data.message = new OO.ui.MessageWidget( {
				type: 'success',
				inline: true,
				label: key ? label.parseDom() : label
			} ).$element[ 0 ];

			mw.notify( data.message, {
				classes: [ 'postedit' ]
			} );
		}

		// Deprecated - use the 'postEdit' hook, and an additional pause if required
		mw.hook( 'postEdit.afterRemoval' ).fire();
	}

	function init() {
		// JS-only flag that allows another module providing a hook handler to suppress the default one.
		if ( !mw.config.get( 'wgPostEditConfirmationDisabled' ) ) {
			mw.hook( 'postEdit' ).add( showConfirmation );
		}

		// Check storage and cookie (set server-side)
		let action = mw.storage.session.get( storageKey ) || mw.config.get( 'wgPostEdit' );

		if ( !action ) {
			return;
		}

		let context;

		try {
			context = JSON.parse( action );
		} catch ( e ) {
			// Old-style value? It's either $action or $action+tempuser
			const plusPos = action.indexOf( '+' );
			let tempUserCreated = false;
			if ( plusPos > -1 ) {
				action = action.slice( 0, plusPos );
				tempUserCreated = true;
			}

			context = {
				action,
				tempUserCreated
			};
		}

		// Set wgPostEdit when displaying a message requested via fireHookOnPageReload() to
		// maintain compatibility with existing extensions. See
		// https://codesearch.wmcloud.org/things/?q=get%5C%28+%5B%27%22%5DwgPostEdit%5B%22%27%5D&files=%5C.js%24
		// for a list of extensions that expect wgPostEdit to be set. See also T240041#8148006 for
		// some context as to why those extensions require it to be set.
		//
		// TODO: T437414: Tidy up the uses of wgPostEdit in extensions.
		mw.config.set( 'wgPostEdit', context.action );

		module.exports.fireHook( context.action, context );

		// Clear storage (cookie is cleared server-side)
		mw.storage.session.remove( storageKey );
	}

	/**
	 * @param {Object} context
	 * @return {module:mediawiki.action.view.postEdit.Context}
	 *
	 * @ignore
	 */
	function getAllowedContext( context ) {
		const result = {};

		[
			'message',
			'tempUserCreated',
			'editorName',
			'revisionId',
			'curRevisionId',
			'isRollback'
		].forEach( ( prop ) => {
			if ( Object.prototype.hasOwnProperty.call( context, prop ) ) {
				result[ prop ] = context[ prop ];
			}
		} );

		return result;
	}

	/**
	 * Show post-edit messages and notify other modules that the user made an edit.
	 *
	 * @example
	 * const { fireHook, SAVED } = require( 'mediawiki.action.view.postEdit' );
	 * fireHook( SAVED );
	 *
	 * @property {module:mediawiki.action.view.postEdit.ACTION} SAVED
	 * @property {module:mediawiki.action.view.postEdit.ACTION} CREATED
	 * @property {module:mediawiki.action.view.postEdit.ACTION} RESTORED
	 * @property {module:mediawiki.action.view.postEdit.ACTION} PUBLISHED
	 */
	module.exports = {
		/**
		 * Fire the `postEdit` hook and show a post-edit message now.
		 *
		 * @param {module:mediawiki.action.view.postEdit.ACTION} [action="saved"]
		 * @param {boolean|module:mediawiki.action.view.postEdit.Context} [context] Either a boolean
		 *  representing whether a temporary account was created during this edit or a context
		 *  object that describes the edit
		 *
		 * @memberof module:mediawiki.action.view.postEdit
		 */
		fireHook( action, context ) {
			if ( !action ) {
				action = ACTION.SAVED;
			}
			if ( action === ACTION.SAVED && config.EditSubmitButtonLabelPublish ) {
				action = ACTION.PUBLISHED;
			}

			let data = {
				tempUserCreated: undefined,
				action,
				revisionId: mw.config.get( 'wgRevisionId' ),
				curRevisionId: mw.config.get( 'wgCurRevisionId' ),
				isRollback: false,
				editorName: 'unknown'
			};

			if ( typeof context === 'boolean' ) {
				data.tempUserCreated = context;
			} else if ( typeof context === 'object' ) {
				data = Object.assign( data, getAllowedContext( context ) );
			}

			if ( !VALID_ACTIONS.includes( data.action ) ) {
				mw.log.warn( `postEdit.fireHook: Invalid action ${ data.action }` );

				return;
			}

			if ( !data.message ) {
				// The following messages can be used here:
				// * postedit-confirmation-published
				// * postedit-confirmation-saved
				// * postedit-confirmation-created
				// * postedit-confirmation-restored
				data.message = mw.message(
					'postedit-confirmation-' + data.action,
					mw.user,
					data.revisionId
				);
			}

			mw.hook( 'postEdit' ).fire( data );
		},

		/**
		 * Fire the `postEdit` hook and show a post-edit message on the next page load.
		 *
		 * The necessary data is stored in session storage for up to 20 minutes, and cleared on the
		 * next page load.
		 *
		 * @param {module:mediawiki.action.view.postEdit.ACTION} [action="saved"]
		 * @param {boolean|module:mediawiki.action.view.postEdit.Context} [context] Either a boolean
		 *  representing whether a temporary account was created during this edit or a context
		 *  object that describes the edit
		 *
		 * @memberof module:mediawiki.action.view.postEdit
		 */
		fireHookOnPageReload( action, context ) {
			if ( !action ) {
				action = ACTION.SAVED;
			}

			let data = {
				action,
				isRollback: false
			};

			if ( typeof context === 'boolean' ) {
				data.tempUserCreated = context;
			} else if ( typeof context === 'object' ) {
				data = Object.assign( data, getAllowedContext( context ) );
			}

			if ( !VALID_ACTIONS.includes( data.action ) ) {
				mw.log.warn( `postEdit.fireHookOnPageReload: Invalid action ${ data.action }` );

				return;
			}

			// TODO: Add support to show custom post-edit messages on the next page load.
			delete data.message;

			mw.storage.session.set(
				storageKey,
				JSON.stringify( data ),
				1200 // same duration as EditPage::POST_EDIT_COOKIE_DURATION
			);
		},

		SAVED: ACTION.SAVED,
		CREATED: ACTION.CREATED,
		RESTORED: ACTION.RESTORED,
		PUBLISHED: ACTION.PUBLISHED
	};

	if ( window.QUnit ) {
		const oldConfig = Object.assign( {}, config );

		module.exports.init = init;

		/**
		 * @param {module:mediawiki.action.view.postEdit.Config} newConfig
		 */
		module.exports.setConfig = function ( newConfig ) {
			config = newConfig;
		};

		module.exports.resetConfig = function () {
			config = oldConfig;
		};
	} else {
		init();
	}
}() );
