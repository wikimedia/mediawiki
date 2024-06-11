( function () {

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * @callback mw.Api.EditTransform
		 * @param {Object} revision Current revision
		 * @param {string} revision.content Current revision content
		 * @return {string|Object|jQuery.Promise} New content, object with edit
		 *  API parameters, or promise providing one of those.
		 */

		/**
		 * Post to API with `csrf` token. See [#postWithToken]{@link mw.Api#postWithToken}
		 *
		 * @param {Object} params API parameters
		 * @param {Object} [ajaxOptions]
		 * @return {jQuery.Promise} See [#post]{@link mw.Api#post}
		 */
		postWithEditToken: function ( params, ajaxOptions ) {
			return this.postWithToken( 'csrf', params, ajaxOptions );
		},

		/**
		 * API helper to grab a csrf token.
		 *
		 * @return {jQuery.Promise} Received token.
		 */
		getEditToken: function () {
			return this.getToken( 'csrf' );
		},

		/**
		 * Create a new page.
		 *
		 * @example
		 * new mw.Api().create( 'Sandbox',
		 *     { summary: 'Load sand particles.' },
		 *     'Sand.'
		 * );
		 *
		 * @since 1.28
		 * @param {mw.Title|string} title Page title
		 * @param {Object} params Edit API parameters
		 * @param {string} params.summary Edit summary
		 * @param {string} content
		 * @return {jQuery.Promise} API response
		 */
		create: function ( title, params, content ) {
			return this.postWithEditToken( Object.assign( this.assertCurrentUser( {
				action: 'edit',
				title: String( title ),
				text: content,
				formatversion: '2',
				// Protect against conflicts
				createonly: true
			} ), params ) ).then( ( data ) => data.edit );
		},

		/**
		 * Edit an existing page.
		 *
		 * To create a new page, use #create() instead.
		 *
		 * Simple transformation:
		 * ```
		 * new mw.Api()
		 *     .edit( 'Sandbox', function ( revision ) {
		 *         return revision.content.replace( 'foo', 'bar' );
		 *     } )
		 *     .then( function () {
		 *         console.log( 'Saved!' );
		 *     } );
		 * ```
		 *
		 * Set save parameters by returning an object instead of a string:
		 * ```
		 * new mw.Api().edit(
		 *     'Sandbox',
		 *     function ( revision ) {
		 *         return {
		 *             text: revision.content.replace( 'foo', 'bar' ),
		 *             summary: 'Replace "foo" with "bar".',
		 *             assert: 'bot',
		 *             minor: true
		 *         };
		 *     }
		 * )
		 * .then( function () {
		 *     console.log( 'Saved!' );
		 * } );
		 * ```
		 *
		 * Transform asynchronously by returning a promise.
		 * ```
		 * new mw.Api()
		 *     .edit( 'Sandbox', function ( revision ) {
		 *         return Spelling
		 *             .corrections( revision.content )
		 *             .then( function ( report ) {
		 *                 return {
		 *                     text: report.output,
		 *                     summary: report.changelog
		 *                 };
		 *             } );
		 *     } )
		 *     .then( function () {
		 *         console.log( 'Saved!' );
		 *     } );
		 * ```
		 *
		 * @since 1.28
		 * @param {mw.Title|string} title Page title
		 * @param {mw.Api.EditTransform} transform Callback that prepares the edit
		 * @return {jQuery.Promise} Edit API response
		 */
		edit: function ( title, transform ) {
			var basetimestamp, curtimestamp,
				api = this;

			title = String( title );

			return api.get( {
				action: 'query',
				prop: 'revisions',
				rvprop: [ 'content', 'timestamp' ],
				titles: [ title ],
				formatversion: '2',
				curtimestamp: true
			} )
				.then( ( data ) => {
					var page, revision;
					if ( !data.query || !data.query.pages ) {
						return $.Deferred().reject( 'unknown' );
					}
					page = data.query.pages[ 0 ];
					if ( !page || page.invalid ) {
						return $.Deferred().reject( 'invalidtitle' );
					}
					if ( page.missing ) {
						return $.Deferred().reject( 'nocreate-missing' );
					}
					revision = page.revisions[ 0 ];
					basetimestamp = revision.timestamp;
					curtimestamp = data.curtimestamp;
					return transform( {
						timestamp: revision.timestamp,
						content: revision.content
					} );
				} )
				.then( ( params ) => {
					var editParams = typeof params === 'object' ? params : { text: String( params ) };
					return api.postWithEditToken( Object.assign( {
						action: 'edit',
						title: title,
						formatversion: '2',

						// Protect against errors and conflicts
						assert: mw.config.get( 'wgUserName' ) ? 'user' : undefined,
						basetimestamp: basetimestamp,
						starttimestamp: curtimestamp,
						nocreate: true
					}, editParams ) );
				} )
				.then( ( data ) => data.edit );
		},

		/**
		 * Post a new section to the page.
		 *
		 * @see mw.Api#postWithEditToken
		 * @param {mw.Title|string} title Target page
		 * @param {string} header
		 * @param {string} message wikitext message
		 * @param {Object} [additionalParams] Additional API parameters, e.g. `{ redirect: true }`
		 * @return {jQuery.Promise}
		 */
		newSection: function ( title, header, message, additionalParams ) {
			return this.postWithEditToken( Object.assign( {
				action: 'edit',
				section: 'new',
				title: String( title ),
				summary: header,
				text: message
			}, additionalParams ) );
		}
	} );

}() );
