( function () {

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Determine if a category exists.
		 *
		 * @param {mw.Title|string} title
		 * @return {jQuery.Promise<boolean>} Promise that resolves with a boolean indicating
		 *  whether the category exists.
		 */
		isCategory: function ( title ) {
			var apiPromise = this.get( {
				formatversion: 2,
				prop: 'categoryinfo',
				titles: [ String( title ) ]
			} );

			return apiPromise
				.then( function ( data ) {
					return !!(
						data.query && // query is missing on title=""
						data.query.pages && // query.pages is missing on title="#" or title="mw:"
						data.query.pages[ 0 ].categoryinfo
					);
				} )
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get a list of categories that match a certain prefix.
		 *
		 * E.g. given "Foo", return "Food", "Foolish people", "Foosball tables"...
		 *
		 * @param {string} prefix Prefix to match.
		 * @return {jQuery.Promise<string[]>} Promise that resolves with an array of matched categories
		 */
		getCategoriesByPrefix: function ( prefix ) {
			// Fetch with allpages to only get categories that have a corresponding description page.
			var apiPromise = this.get( {
				formatversion: 2,
				list: 'allpages',
				apprefix: prefix,
				apnamespace: mw.config.get( 'wgNamespaceIds' ).category
			} );

			return apiPromise
				.then( function ( data ) {
					return data.query.allpages.map( function ( category ) {
						return new mw.Title( category.title ).getMainText();
					} );
				} )
				.promise( { abort: apiPromise.abort } );
		},

		/**
		 * Get the categories that a particular page on the wiki belongs to.
		 *
		 * @param {mw.Title|string} title
		 * @return {jQuery.Promise<mw.Title[]|false>} Promise that resolves with an array of
		 *  category titles, or with false if the title was not found.
		 */
		getCategories: function ( title ) {
			var apiPromise = this.get( {
				formatversion: 2,
				prop: 'categories',
				titles: [ String( title ) ]
			} );

			return apiPromise
				.then( function ( data ) {
					var page;

					if ( !data.query || !data.query.pages ) {
						return false;
					}
					page = data.query.pages[ 0 ];
					if ( !page.categories ) {
						return false;
					}
					return page.categories.map( function ( cat ) {
						return new mw.Title( cat.title );
					} );
				} )
				.promise( { abort: apiPromise.abort } );
		}
	} );

}() );
