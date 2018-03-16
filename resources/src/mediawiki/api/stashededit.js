/**
 * @class mw.Api.plugin.stashededit
 */
( function ( mw, $ ) {
	var PRIORITY_LOW = 1,
		PRIORITY_HIGH = 2,
		defaults = {
			title: mw.config.get( 'wgPageName' ),
			baserevid: mw.config.get( 'wgCurRevisionId' ),
			contentformat: 'text/x-wiki',
			contentmodel: 'wikitext'
		};

	$.extend( mw.Api.prototype, {
		/**
		 * Send a request to stash the edit to the API.
		 *
		 * For each API instance, will check if the provided edit has already been stashed.
		 * If a request is in progress, abort it since its payload is stale and the API
		 * may limit concurrent stash parses.
		 *
		 * @param {string} text Wikitext to stash
		 * @param {string} summary Edit summary
		 * @param {Object} [params] Additional params
		 */
		stashEdit: function ( text, summary, params ) {
			var req,
				api = this,
				textChanged = text !== this.lastStashEditText,
				priority = textChanged ? PRIORITY_HIGH : PRIORITY_LOW;

			params = params || {};

			if ( params.section === 'new' ) {
				// We don't attempt to stash new section edits because in such cases the parser output
				// varies on the edit summary (since it determines the new section's name).
				return;
			}

			if ( !textChanged && summary === this.lastStashEditSummary ) {
				// No changes
				return;
			}

			if ( this.stashEditReq ) {
				if ( this.lastStashEditPriority > priority ) {
					// Stash request for summary change should wait on pending text change stash
					this.stashEditReq.then( this.stashEdit.bind( this, text, summary, params ) );
					return;
				}
				this.stashEditReq.abort();
			}

			// Update the "last" tracking variables
			this.lastStashEditSummary = summary;
			this.lastStashEditPriority = priority;
			if ( textChanged ) {
				this.lastStashEditText = text;
				// Reset hash
				this.lastStashEditTextHash = null;
			}

			params = $.extend( {
				formatversion: 2,
				action: 'stashedit'
			}, defaults, params, {
				summary: summary
			} );

			if ( this.lastStashEditTextHash ) {
				params.stashedtexthash = this.lastStashEditTextHash;
			} else {
				params.text = text;
			}

			req = this.postWithToken( 'csrf', params );
			this.stashEditReq = req;
			req.then( function ( data ) {
				if ( req === api.stashEditReq ) {
					api.stashEditReq = null;
				}
				if ( data.stashedit && data.stashedit.texthash ) {
					api.lastStashEditTextHash = data.stashedit.texthash;
				} else {
					// Request failed or text hash expired;
					// include the text in a future stash request.
					api.lastStashEditTextHash = null;
				}
			} );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.stashededit
	 */

}( mediaWiki, jQuery ) );
