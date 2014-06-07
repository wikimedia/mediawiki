/**
 * @class mw.Api.plugin.category
 */
( function ( mw, $ ) {

	var msg = 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.';
	$.extend( mw.Api.prototype, {
		/**
		 * Determine if a category exists.
		 *
		 * @param {mw.Title|string} title
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean} return.done.isCategory Whether the category exists.
		 */
		isCategory: function ( title, ok, err ) {
			var apiPromise = this.get( {
				prop: 'categoryinfo',
				titles: String( title )
			} );

			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}

			return apiPromise
				.then( function ( data ) {
					var exists = false;
					if ( data.query && data.query.pages ) {
						$.each( data.query.pages, function ( id, page ) {
							if ( page.categoryinfo ) {
								exists = true;
							}
						} );
					}
					return exists;
				} )
				.done( ok )
				.fail( err )
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get a list of categories that match a certain prefix.
		 *
		 * E.g. given "Foo", return "Food", "Foolish people", "Foosball tables"...
		 *
		 * @param {string} prefix Prefix to match.
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string[]} return.done.categories Matched categories
		 */
		getCategoriesByPrefix: function ( prefix, ok, err ) {
			// Fetch with allpages to only get categories that have a corresponding description page.
			var apiPromise = this.get( {
				list: 'allpages',
				apprefix: prefix,
				apnamespace: mw.config.get( 'wgNamespaceIds' ).category
			} );

			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}

			return apiPromise
				.then( function ( data ) {
					var texts = [];
					if ( data.query && data.query.allpages ) {
						$.each( data.query.allpages, function ( i, category ) {
							texts.push( new mw.Title( category.title ).getNameText() );
						} );
					}
					return texts;
				} )
				.done( ok )
				.fail( err )
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get the categories that a particular page on the wiki belongs to.
		 *
		 * @param {mw.Title|string} title
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @param {boolean} [async=true] Asynchronousness (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean|mw.Title[]} return.done.categories List of category titles or false
		 *  if title was not found.
		 */
		getCategories: function ( title, ok, err, async ) {
			var apiPromise = this.get( {
				prop: 'categories',
				titles: String( title )
			}, {
				async: async === undefined ? true : async
			} );

			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			if ( async !== undefined ) {
				mw.track( 'mw.deprecate', 'api.async' );
				mw.log.warn(
					'Use of mediawiki.api async=false param is deprecated. ' +
					'The sychronous mode will be removed in the future.'
				);
			}

			return apiPromise
				.then( function ( data ) {
					var titles = false;
					if ( data.query && data.query.pages ) {
						$.each( data.query.pages, function ( id, page ) {
							if ( page.categories ) {
								if ( titles === false ) {
									titles = [];
								}
								$.each( page.categories, function ( i, cat ) {
									titles.push( new mw.Title( cat.title ) );
								} );
							}
						} );
					}
					return titles;
				} )
				.done( ok )
				.fail( err )
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.category
	 */

}( mediaWiki, jQuery ) );
