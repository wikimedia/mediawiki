/**
 * Additional mw.Api methods to assist with API calls related to categories.
 */

( function( $, mw, undefined ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Determine if a category exists.
		 * @param title {mw.Title}
		 * @param success {Function} callback to pass boolean of category's existence
		 * @param err {Function} optional callback to run if api error
		 * @return ajax call object
		 */
		isCategory: function( title, success, err ) {
			var params = {
					prop: 'categoryinfo',
					titles: title.toString()
				},
				ok = function( data ) {
					var exists = false;
					if ( data.query && data.query.pages ) {
						$.each( data.query.pages, function( id, page ) {
							if ( page.categoryinfo ) {
								exists = true;
							}
						} );
					}
					success( exists );
				};

			return this.get( params, { ok: ok, err: err } );
		},

		/**
		 * Get a list of categories that match a certain prefix.
		 *   e.g. given "Foo", return "Food", "Foolish people", "Foosball tables" ...
		 * @param prefix {String} prefix to match
		 * @param success {Function} callback to pass matched categories to
		 * @param err {Function} optional callback to run if api error
		 * @return {jqXHR}
		 */
		getCategoriesByPrefix: function( prefix, success, err ) {

			// fetch with allpages to only get categories that have a corresponding description page.
			var params = {
				'list': 'allpages',
				'apprefix': prefix,
				'apnamespace': mw.config.get('wgNamespaceIds').category
			};

			var ok = function( data ) {
				var texts = [];
				if ( data.query && data.query.allpages ) {
					$.each( data.query.allpages, function( i, category ) {
						texts.push( new mw.Title( category.title ).getNameText() );
					} );
				}
				success( texts );
			};

			return this.get( params, { ok: ok, err: err } );
		},


		/**
		 * Get the categories that a particular page on the wiki belongs to
		 * @param title {mw.Title}
		 * @param success {Function} callback to pass categories to (or false, if title not found)
		 * @param err {Function} optional callback to run if api error
		 * @param async {Boolean} optional asynchronousness (default = true = async)
		 * @return {jqXHR}
		 */
		getCategories: function( title, success, err, async ) {
			var params, ok;
			params = {
				prop: 'categories',
				titles: title.toString()
			};
			if ( async === undefined ) {
				async = true;
			}
			ok = function( data ) {
				var ret = false;
				if ( data.query && data.query.pages ) {
					$.each( data.query.pages, function( id, page ) {
						if ( page.categories ) {
							if ( typeof ret !== 'object' ) {
								ret = [];
							}
							$.each( page.categories, function( i, cat ) {
								ret.push( new mw.Title( cat.title ) );
							} );
						}
					} );
				}
				success( ret );
			};

			return this.get( params, { ok: ok, err: err, async: async } );
		}

	} );

} )( jQuery, mediaWiki );
