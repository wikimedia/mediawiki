( function () {
	'use strict';

	/**
	 * Fired after an edit was successfully saved.
	 *
	 * Does not fire for null edits.
	 *
	 * Code that fires the postEdit hook should first set `wgRevisionId` and `wgCurRevisionId`
	 * to the revision associated with the edit that triggered the postEdit hook, then fire
	 * the postEdit hook, e.g.:
	 *
	 *     mw.config.set( {
	 *        wgCurRevisionId: data.newrevid,
	 *        wgRevisionId: data.newrevid
	 *     } );
	 *     // Now fire the hook.
	 *     mw.hook( 'postEdit' ).fire();
	 *
	 * @event postEdit
	 * @member mw.hook
	 * @param {Object} [data] Optional data
	 * @param {string|jQuery|Array} [data.message] Message that listeners
	 *  should use when displaying notifications. String for plain text,
	 *  use array or jQuery object to pass actual nodes.
	 * @param {string|mw.user} [data.user=mw.user] User that made the edit.
	 * @param {boolean} [data.tempUserCreated] Whether a temporary user account
	 *  was created.
	 */

	/**
	 * After the listener for #postEdit removes the notification.
	 *
	 * @deprecated
	 * @event postEdit_afterRemoval
	 * @member mw.hook
	 */

	var config = require( './config.json' );
	var contLangMessages = require( './contLangMessages.json' );
	var storageKey = 'mw-PostEdit' + mw.config.get( 'wgPageName' );

	function showTempUserPopup() {
		var title = mw.message( 'postedit-temp-created-label' ).text();
		var $content = mw.message(
			'postedit-temp-created',
			mw.util.getUrl( 'Special:CreateAccount' ),
			contLangMessages[ 'tempuser-helppage' ]
		).parseDom();

		var $username = $( '.mw-temp-user-banner-username' );
		if ( $username.length ) {
			// If supported by the skin, display a popup anchored to the username in the banner
			var popup = new OO.ui.PopupWidget( {
				padded: true,
				head: true,
				label: title,
				$content: $content,
				$floatableContainer: $username,
				classes: [ 'postedit-tempuserpopup' ],
				// Work around T307062
				position: 'below',
				autoFlip: false
			} );
			$( document.body ).append( popup.$element );
			popup.toggle( true );
		} else {
			// Otherwise display a mw.notify message
			mw.notify( $content, {
				title: title,
				classes: [ 'postedit-tempuserpopup' ],
				autoHide: false
			} );
		}
	}

	function showConfirmation( data ) {
		var label;

		data = data || {};

		label = data.message || new OO.ui.HtmlSnippet( mw.message(
			config.EditSubmitButtonLabelPublish ?
				'postedit-confirmation-published' :
				'postedit-confirmation-saved',
			data.user || mw.user
		).escaped() );

		data.message = new OO.ui.MessageWidget( {
			type: 'success',
			inline: true,
			label: label
		} ).$element[ 0 ];

		mw.notify( data.message, {
			classes: [ 'postedit' ]
		} );

		// Deprecated - use the 'postEdit' hook, and an additional pause if required
		mw.hook( 'postEdit.afterRemoval' ).fire();

		if ( data.tempUserCreated ) {
			showTempUserPopup();
		}
	}

	function init() {
		// JS-only flag that allows another module providing a hook handler to suppress the default one.
		if ( !mw.config.get( 'wgPostEditConfirmationDisabled' ) ) {
			mw.hook( 'postEdit' ).add( showConfirmation );
		}

		// Check storage and cookie (set server-side)
		var action = mw.storage.session.get( storageKey ) || mw.config.get( 'wgPostEdit' );
		if ( action ) {
			var tempUserCreated = false;
			var plusPos = action.indexOf( '+' );
			if ( plusPos > -1 ) {
				action = action.slice( 0, plusPos );
				tempUserCreated = true;
			}

			// Set 'wgPostEdit' when displaying a message requested via storage, to allow CampaignEvents
			// to override post-edit behavior for some page creations performed using VisualEditor, which
			// shows a message via storage when creating new pages (T240041#8148006):
			// https://gerrit.wikimedia.org/g/mediawiki/extensions/CampaignEvents/+/e380af0c69b17ecb05fc3258f92c9df625a35449/resources/ext.campaignEvents.eventpage/index.js#187
			// https://gerrit.wikimedia.org/g/mediawiki/extensions/VisualEditor/+/192c1051120c8dd331f00b9024b5beadab1cb89a/modules/ve-mw/init/targets/ve.init.mw.ArticleTarget.js#656
			// TODO: We should provide a better API for this that doesn't require extensions to parse the
			// 'action' value themselves, and doesn't require accessing 'wgPostEdit' from mw.config.
			mw.config.set( 'wgPostEdit', action );

			module.exports.fireHook( action, tempUserCreated );
		}

		// Clear storage (cookie is cleared server-side)
		mw.storage.session.remove( storageKey );
	}

	/**
	 * Show post-edit messages.
	 *
	 * Usage:
	 *
	 *     var postEdit = require( 'mediawiki.action.view.postEdit' );
	 *     postEdit.fireHook( 'saved' );
	 *
	 * @class mw.plugin.action.view.postEdit
	 * @singleton
	 */
	module.exports = {

		/**
		 * Show a post-edit message now.
		 *
		 * This is just a shortcut for firing mw.hook#postEdit.
		 *
		 * @param {string} [action] One of 'saved', 'created', 'restored'
		 * @param {boolean} [tempUserCreated] Whether a temporary account was created during this edit
		 */
		fireHook: function ( action, tempUserCreated ) {
			if ( !action ) {
				action = 'saved';
			}
			if ( action === 'saved' && config.EditSubmitButtonLabelPublish ) {
				action = 'published';
			}
			mw.hook( 'postEdit' ).fire( {
				// The following messages can be used here:
				// * postedit-confirmation-published
				// * postedit-confirmation-saved
				// * postedit-confirmation-created
				// * postedit-confirmation-restored
				message: mw.msg(
					'postedit-confirmation-' + action,
					mw.user
				),
				tempUserCreated: tempUserCreated
			} );
		},

		/**
		 * Show a post-edit message on the next page load.
		 *
		 * The necessary data is stored in session storage for up to 20 minutes, and cleared when the
		 * page is loaded again.
		 *
		 * @param {string} [action] One of 'saved', 'created', 'restored'
		 * @param {boolean} [tempUserCreated] Whether a temporary account was created during this edit
		 */
		fireHookOnPageReload: function ( action, tempUserCreated ) {
			if ( !action ) {
				action = 'saved';
			}
			if ( tempUserCreated ) {
				action += '+tempuser';
			}
			mw.storage.session.set(
				storageKey,
				action,
				1200 // same duration as EditPage::POST_EDIT_COOKIE_DURATION
			);
		}
	};

	init();

}() );
