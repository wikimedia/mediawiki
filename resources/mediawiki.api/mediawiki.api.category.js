/**
 * @class mw.Api.plugin.category
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Determine if a category exists.
		 * @param {mw.Title} title
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean} return.done.isCategory Whether the category exists.
		 */
		isCategory: function ( title, ok, err ) {
			var d = $.Deferred(),
				apiPromise;

			// Backwards compatibility (< MW 1.20)
			d.done( ok ).fail( err );

			apiPromise = this.get( {
					prop: 'categoryinfo',
					titles: title.toString()
				} )
				.done( function ( data ) {
					var exists = false;
					if ( data.query && data.query.pages ) {
						$.each( data.query.pages, function ( id, page ) {
							if ( page.categoryinfo ) {
								exists = true;
							}
						} );
					}
					d.resolve( exists );
				})
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get a list of categories that match a certain prefix.
		 *   e.g. given "Foo", return "Food", "Foolish people", "Foosball tables" ...
		 * @param {string} prefix Prefix to match.
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {String[]} return.done.categories Matched categories
		 */
		getCategoriesByPrefix: function ( prefix, ok, err ) {
			var d = $.Deferred(),
				apiPromise;

			// Backwards compatibility (< MW 1.20)
			d.done( ok ).fail( err );

			// Fetch with allpages to only get categories that have a corresponding description page.
			apiPromise = this.get( {
					list: 'allpages',
					apprefix: prefix,
					apnamespace: mw.config.get('wgNamespaceIds').category
				} )
				.done( function ( data ) {
					var texts = [];
					if ( data.query && data.query.allpages ) {
						$.each( data.query.allpages, function ( i, category ) {
							texts.push( new mw.Title( category.title ).getNameText() );
						} );
					}
					d.resolve( texts );
				})
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		},


		/**
		 * Get the categories that a particular page on the wiki belongs to
		 * @param {mw.Title} title
		 * @param {Function} [ok] Success callback (deprecated)
		 * @param {Function} [err] Error callback (deprecated)
		 * @param {boolean} [async=true] Asynchronousness
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean|mw.Title[]} return.done.categories List of category titles or false
		 *  if title was not found.
		 */
		getCategories: function ( title, ok, err, async ) {
			var d = $.Deferred(),
				apiPromise;

			// Backwards compatibility (< MW 1.20)
			d.done( ok ).fail( err );

			apiPromise = this.get( {
					prop: 'categories',
					titles: title.toString()
				}, {
					async: async === undefined ? true : async
				} )
				.done( function ( data ) {
					var ret = false;
					if ( data.query && data.query.pages ) {
						$.each( data.query.pages, function ( id, page ) {
							if ( page.categories ) {
								if ( typeof ret !== 'object' ) {
									ret = [];
								}
								$.each( page.categories, function ( i, cat ) {
									ret.push( new mw.Title( cat.title ) );
								} );
							}
						} );
					}
					d.resolve( ret );
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.category
	 */

}( mediaWiki, jQuery ) );
