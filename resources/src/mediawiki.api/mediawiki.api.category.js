/**
 * @class mw.Api.plugin.category
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Determine if a category exists.
		 *
		 * @param {mw.Title|string} title
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean} return.done.isCategory Whether the category exists.
		 */
		isCategory: function ( title ) {
			var apiPromise = this.get( {
				prop: 'categoryinfo',
				titles: String( title )
			} );

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
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get a list of categories that match a certain prefix.
		 *
		 * E.g. given "Foo", return "Food", "Foolish people", "Foosball tables"...
		 *
		 * @param {string} prefix Prefix to match.
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {string[]} return.done.categories Matched categories
		 */
		getCategoriesByPrefix: function ( prefix ) {
			// Fetch with allpages to only get categories that have a corresponding description page.
			var apiPromise = this.get( {
				list: 'allpages',
				apprefix: prefix,
				apnamespace: mw.config.get( 'wgNamespaceIds' ).category
			} );

			return apiPromise
				.then( function ( data ) {
					var texts = [];
					if ( data.query && data.query.allpages ) {
						$.each( data.query.allpages, function ( i, category ) {
							texts.push( new mw.Title( category.title ).getMainText() );
						} );
					}
					return texts;
				} )
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get the categories that a particular page on the wiki belongs to.
		 *
		 * @param {mw.Title|string} title
		 * @return {jQuery.Promise}
		 * @return {Function} return.done
		 * @return {boolean|mw.Title[]} return.done.categories List of category titles or false
		 *  if title was not found.
		 */
		getCategories: function ( title ) {
			var apiPromise = this.get( {
				prop: 'categories',
				titles: String( title )
			} );

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
				.promise( { abort: apiPromise.abort } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.category
	 */

}( mediaWiki, jQuery ) );
