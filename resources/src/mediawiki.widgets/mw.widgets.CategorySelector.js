/*!
 * MediaWiki Widgets - CategorySelector class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	var CSP,
		NS_CATEGORY = mw.config.get( 'wgNamespaceIds' ).category;

	/**
	 * Category selector widget. Displays an OO.ui.CapsuleMultiSelectWidget
	 * and autocompletes with available categories.
	 *
	 *     var selector = new mw.widgets.CategorySelector( {
	 *       searchTypes: [
	 *         mw.widgets.CategorySelector.SearchType.OpenSearch,
	 *         mw.widgets.CategorySelector.SearchType.InternalSearch
	 *       ]
	 *     } );
	 *
	 *     $( '#content' ).append( selector.$element );
	 *
	 *     selector.setSearchType( [ mw.widgets.CategorySelector.SearchType.SubCategories ] );
	 *
	 * @class mw.widgets.CategorySelector
	 * @uses mw.Api
	 * @extends OO.ui.CapsuleMultiSelectWidget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {mw.Api} [api] Instance of mw.Api (or subclass thereof) to use for queries
	 * @cfg {number} [limit=10] Maximum number of results to load
	 * @cfg {mw.widgets.CategorySelector.SearchType[]} [searchTypes=[mw.widgets.CategorySelector.SearchType.OpenSearch]]
	 *   Default search API to use when searching.
	 */
	function CategorySelector( config ) {
		// Config initialization
		config = $.extend( {
			limit: 10,
			searchTypes: [ CategorySelector.SearchType.OpenSearch ]
		}, config );
		this.limit = config.limit;
		this.searchTypes = config.searchTypes;
		this.validateSearchTypes();

		// Parent constructor
		mw.widgets.CategorySelector.parent.call( this, $.extend( true, {}, config, {
			menu: {
				filterFromInput: false
			},
			// This allows the user to both select non-existent categories, and prevents the selector from
			// being wiped from #onMenuItemsChange when we change the available options in the dropdown
			allowArbitrary: true
		} ) );

		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, $.extend( {}, config, { $pending: this.$handle } ) );

		// Event handler to call the autocomplete methods
		this.$input.on( 'change input cut paste', OO.ui.debounce( this.updateMenuItems.bind( this ), 100 ) );

		// Initialize
		this.api = config.api || new mw.Api();
	}

	/* Setup */

	OO.inheritClass( CategorySelector, OO.ui.CapsuleMultiSelectWidget );
	OO.mixinClass( CategorySelector, OO.ui.mixin.PendingElement );
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

			// Never show the menu if the input lost focus in the meantime
			if ( !this.$input.is( ':focus' ) ) {
				return;
			}

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

			menu.addItems( filteredItems ).toggle( true );
		}.bind( this ) );
	};

	/**
	 * @inheritdoc
	 */
	CSP.clearInput = function () {
		CategorySelector.parent.prototype.clearInput.call( this );
		// Abort all pending requests, we won't need their results
		this.api.abort();
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

		if ( $.trim( input ) === '' ) {
			deferred.resolve( [] );
			return deferred.promise();
		}

		// Abort all pending requests, we won't need their results
		this.api.abort();
		for ( i = 0; i < this.searchTypes.length; i++ ) {
			promises.push( this.searchCategories( input, this.searchTypes[ i ] ) );
		}

		this.pushPending();

		$.when.apply( $, promises ).done( function () {
			var categories, categoryNames,
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
				return mw.Title.newFromText( name, NS_CATEGORY ).getMainText();
			} );

			deferred.resolve( categoryNames );

		} ).always( this.popPending.bind( this ) );

		return deferred.promise();
	};

	/**
	 * @inheritdoc
	 */
	CSP.createItemWidget = function ( data ) {
		return new mw.widgets.CategoryCapsuleItemWidget( {
			apiUrl: this.api.apiUrl || undefined,
			title: mw.Title.newFromText( data, NS_CATEGORY )
		} );
	};

	/**
	 * Validates the values in `this.searchType`.
	 *
	 * @private
	 * @return {boolean}
	 */
	CSP.validateSearchTypes = function () {
		var validSearchTypes = false,
			searchTypeEnumCount = Object.keys( CategorySelector.SearchType ).length;

		// Check if all values are in the SearchType enum
		validSearchTypes = this.searchTypes.every( function ( searchType ) {
			return searchType > -1 && searchType < searchTypeEnumCount;
		} );

		if ( validSearchTypes === false ) {
			throw new Error( 'Unknown searchType in searchTypes' );
		}

		// If the searchTypes has CategorySelector.SearchType.SubCategories
		// it can be the only search type.
		if ( this.searchTypes.indexOf( CategorySelector.SearchType.SubCategories ) > -1 &&
			this.searchTypes.length > 1
		) {
			throw new Error( 'Can\'t have additional search types with CategorySelector.SearchType.SubCategories' );
		}

		// If the searchTypes has CategorySelector.SearchType.ParentCategories
		// it can be the only search type.
		if ( this.searchTypes.indexOf( CategorySelector.SearchType.ParentCategories ) > -1 &&
			this.searchTypes.length > 1
		) {
			throw new Error( 'Can\'t have additional search types with CategorySelector.SearchType.ParentCategories' );
		}

		return true;
	};

	/**
	 * Sets and validates the value of `this.searchType`.
	 *
	 * @param {mw.widgets.CategorySelector.SearchType[]} searchTypes
	 */
	CSP.setSearchTypes = function ( searchTypes ) {
		this.searchTypes = searchTypes;
		this.validateSearchTypes();
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
					namespace: NS_CATEGORY,
					limit: this.limit,
					search: input
				} ).done( function ( res ) {
					var categories = res[ 1 ];
					deferred.resolve( categories );
				} ).fail( deferred.reject.bind( deferred ) );
				break;

			case CategorySelector.SearchType.InternalSearch:
				this.api.get( {
					action: 'query',
					list: 'allpages',
					apnamespace: NS_CATEGORY,
					aplimit: this.limit,
					apfrom: input,
					apprefix: input
				} ).done( function ( res ) {
					var categories = res.query.allpages.map( function ( page ) {
						return page.title;
					} );
					deferred.resolve( categories );
				} ).fail( deferred.reject.bind( deferred ) );
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
						if ( parseInt( page, 10 ) > -1 ) {
							categories.push( res.query.pages[ page ].title );
						}
					}

					deferred.resolve( categories );
				} ).fail( deferred.reject.bind( deferred ) );
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
				} ).fail( deferred.reject.bind( deferred ) );
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
						if ( parseInt( page, 10 ) > -1 ) {
							if ( $.isArray( res.query.pages[ page ].categories ) ) {
								categories.push.apply( categories, res.query.pages[ page ].categories.map( function ( category ) {
									return category.title;
								} ) );
							}
						}
					}

					deferred.resolve( categories );
				} ).fail( deferred.reject.bind( deferred ) );
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
		/** Search using action=opensearch */
		OpenSearch: 0,

		/** Search using action=query */
		InternalSearch: 1,

		/** Search for existing categories with the exact title */
		Exists: 2,

		/** Search only subcategories  */
		SubCategories: 3,

		/** Search only parent categories */
		ParentCategories: 4
	};

	mw.widgets.CategorySelector = CategorySelector;
}( jQuery, mediaWiki ) );
