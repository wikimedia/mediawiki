/*!
 * MediaWiki Widgets - CategorySelector class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	var CSP;

	/**
	 * Category selector widget. Displays an OO.ui.CapsuleMultiSelectWidget
	 * and autocompletes with available categories.
	 *
	 * @class mw.widgets.CategorySelector
	 * @uses mw.Api
	 * @extends OO.ui.CapsuleMultiSelectWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [limit=10] Maximum number of results to load
	 * @cfg {mw.widgets.CategorySelector.SearchType[]} [searchTypes=[mw.widgets.CategorySelector.SearchType.OpenSearch]]
	 * 	Default search API to use when searching.
	 */
	function CategorySelector( config ) {
		// Config initialization
		config = $.extend( {
			limit: 10,
			searchTypes: [ CategorySelector.SearchType.OpenSearch ]
		}, config );
		this.limit = config.limit;
		this.searchTypes = config.searchTypes;

		// Parent constructor
		mw.widgets.CategorySelector.parent.call( this, config );

		// Event handler to call the autocomplete methods
		this.$input.on( 'change input cut paste', OO.ui.debounce( this.updateMenuItems.bind( this ), 100 ) );

		// Initialize
		this.getMenu().filterFromInput = false;
		this.catNsId = mw.config.get( 'wgNamespaceIds' ).category;
		this.api = new mw.Api();

	}

	/* Setup */

	OO.inheritClass( CategorySelector, OO.ui.CapsuleMultiSelectWidget );
	CSP = CategorySelector.prototype;

	/* Methods */

	/**
	 * Gets new items based on the input by calling
	 * {@link #getNewMenuItems getNewItems} and updates the menu
	 * after removing duplicates based on the data value.
	 *
	 * @private
	 * @method
	 */
	CSP.updateMenuItems = function () {
		this.getMenu().clearItems();
		this.getNewMenuItems( this.$input.val() ).then( function ( items ) {
			var existingItems, filteredItems,
				menu = this.getMenu();

			// Array of strings of the data of OO.ui.MenuOptionsWidgets
			existingItems = menu.getItems().map( function ( item ) {
				return item.data;
			} );

			// Remove if items' data already exists
			filteredItems = items.filter( function ( item ) {
				return existingItems.indexOf( item ) === -1;
			} );

			// Map to an array of OO.ui.MenuOptionWidgets
			filteredItems = filteredItems.map( function ( item ) {
				return new OO.ui.MenuOptionWidget( {
					data: item,
					label: item
				} );
			} );

			menu.addItems( filteredItems ).updateItemVisibility();
		}.bind( this ) );
	};

	/**
	 * Searches for categories based on the input.
	 *
	 * @private
	 * @method
	 * @param {string} input The input used to prefix search categories
	 * @return {jQuery.Promise} Resolves with an array of categories
	 */
	CSP.getNewMenuItems = function ( input ) {
		var i,
			promises = [],
			deferred = new $.Deferred();

		for ( i = 0; i < this.searchTypes.length; i++ ) {
			promises.push( this.searchCategories( input, this.searchTypes[ i ] ) );
		}

		$.when.apply( $, promises ).done( function () {
			var i, categories, categoryNames,
				allData = [],
				dataSets = Array.prototype.slice.apply( arguments );

			// Collect values from all results
			allData = allData.concat.apply( allData, dataSets );

			// Remove duplicates
			categories = allData.filter( function ( value, index, self ) {
				return self.indexOf( value ) === index;
			} );

			// Get titles
			categoryNames = categories.map( function ( name ) {
				return mw.Title.newFromText( name, this.catNsId ).getMainText();
			} );

			deferred.resolve( categoryNames );

		} );

		return deferred.promise();
	};

	/**
	 * Searches categories based on input and searchType.
	 *
	 * @private
	 * @method
	 * @param {string} input The input used to prefix search categories
	 * @param {mw.widgets.CategorySelector.SearchType} searchType
	 * @return {jQuery.Promise} Resolves with an array of categories
	 */
	CSP.searchCategories = function ( input, searchType ) {
		var deferred = new $.Deferred();

		switch ( searchType ) {
			case CategorySelector.SearchType.OpenSearch:
				this.api.get( {
					action: 'opensearch',
					namespace: this.catNsId,
					limit: this.limit,
					search: input
				} ).done( function ( res ) {
					var categories = res[ 1 ];
					deferred.resolve( categories );
				} );
				break;

			case CategorySelector.SearchType.InternalSearch:
				this.api.get( {
					action: 'query',
					list: 'allpages',
					apnamespace: this.catNsId,
					aplimit: this.limit,
					apfrom: input,
					apprefix: input
				} ).done( function ( res ) {
					var categories = res.query.allpages.map( function ( page ) {
						return page.title;
					} );
					deferred.resolve( categories );
				} );
				break;

			case CategorySelector.SearchType.Exists:
				if ( input.indexOf( '|' ) > -1 ) {
					deferred.resolve( [] );
					break;
				}

				this.api.get( {
					action: 'query',
					prop: 'info',
					titles: 'Category:' + input
				} ).done( function ( res ) {
					var page,
						categories = [];

					for ( page in res.query.pages ) {
						if ( parseInt( page ) > -1 ) {
							categories.push( res.query.pages[ page ].title );
						}
					}

					deferred.resolve( categories );
				} );
				break;

			case CategorySelector.SearchType.SubCategories:
				if ( input.indexOf( '|' ) > -1 ) {
					deferred.resolve( [] );
					break;
				}

				this.api.get( {
					action: 'query',
					list: 'categorymembers',
					cmtype: 'subcat',
					cmlimit: this.limit,
					cmtitle: 'Category:' + input
				} ).done( function ( res ) {
					var categories = res.query.categorymembers.map( function ( category ) {
						return category.title;
					} );
					deferred.resolve( categories );
				} );
				break;

			case CategorySelector.SearchType.ParentCategories:
				if ( input.indexOf( '|' ) > -1 ) {
					deferred.resolve( [] );
					break;
				}

				this.api.get( {
					action: 'query',
					prop: 'categories',
					cllimit: this.limit,
					titles: 'Category:' + input
				} ).done( function ( res )  {
					var page,
						categories = [];

					for ( page in res.query.pages ) {
						if ( parseInt( page ) > -1 ) {
							if ( $.isArray( res.query.pages[ page ].categories ) ) {
								categories.push.apply( categories, res.query.pages[ page ].categories.map( function ( category ) {
									return category.title;
								} ) );
							}
						}
					}

					deferred.resolve( categories );
				} );
				break;

			default:
				throw new Error( 'Unknown searchType' );
		}

		return deferred.promise();
	};

	/**
	 * @enum mw.widgets.CategorySelector.SearchType
	 * Types of search available.
	 */
	CategorySelector.SearchType = {
		/** TODO: Write something here */
		OpenSearch: 0,

		/** TODO: Write something here */
		InternalSearch: 1,

		/** TODO: Write something here */
		Exists: 2,

		/** TODO: Write something here */
		SubCategories: 3,

		/** TODO: Write something here */
		ParentCategories: 4
	};

	mw.widgets.CategorySelector = CategorySelector;
}( jQuery, mediaWiki ) );
