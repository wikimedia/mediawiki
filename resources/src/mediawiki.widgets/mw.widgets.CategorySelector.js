/*!
 * MediaWiki Widgets - CategorySelector class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	var CSP,
		NS_CATEGORY = mw.config.get( 'wgNamespaceIds' ).category,
		categorySearch = require( 'mediawiki.CategorySearchType' );

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
	 *     selector.setSearchTypes( [ mw.widgets.CategorySelector.SearchType.SubCategories ] );
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
			deferred = $.Deferred();

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
			var categoryNames,
				allData = [],
				dataSets = Array.prototype.slice.apply( arguments );

			// Collect values from all results
			allData = allData.concat.apply( allData, dataSets );

			categoryNames = allData
				// Remove duplicates
				.filter( function ( value, index, self ) {
					return self.indexOf( value ) === index;
				} )
				// Get Title objects
				.map( function ( name ) {
					return mw.Title.newFromText( name );
				} )
				// Keep only titles from 'Category' namespace
				.filter( function ( title ) {
					return title && title.getNamespaceId() === NS_CATEGORY;
				} )
				// Convert back to strings, strip 'Category:' prefix
				.map( function ( title ) {
					return title.getMainText();
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
			title: mw.Title.makeTitle( NS_CATEGORY, data )
		} );
	};

	/**
	 * @inheritdoc
	 */
	CSP.getItemFromData = function ( data ) {
		// This is a bit of a hack... We have to canonicalize the data in the same way that
		// #createItemWidget and CategoryCapsuleItemWidget will do, otherwise we won't find duplicates.
		data = mw.Title.makeTitle( NS_CATEGORY, data ).getMainText();
		return OO.ui.mixin.GroupElement.prototype.getItemFromData.call( this, data );
	};

	/**
	 * Validates the values in `this.searchType`.
	 *
	 * @private
	 * @return {boolean}
	 */
	CSP.validateSearchTypes = function () {
		var validSearchTypes = false;

		// Check if all values are instances of CategorySearchType
		validSearchTypes = this.searchTypes.every( function ( searchType ) {
			return searchType instanceof CategorySearchType;
		} );

		if ( validSearchTypes === false ) {
			throw new Error( 'Unknown searchType in searchTypes' );
		}

		// Check whether it can be the only search type.
		if ( this.searchTypes.length > 1 ) {
			this.searchTypes.forEach( function ( searchType ) {
				if ( searchType.mustBeTheOnlySearchType === true ) {
					throw new Error( 'Can\'t have additional search types with ' + searchType.constructor.name );
				}
			} );
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
		return searchType.searchCategories( input, this.limit ).promise();
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
