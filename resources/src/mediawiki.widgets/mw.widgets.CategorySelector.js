/*!
 * MediaWiki Widgets - CategorySelector class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * Category selector widget. Displays an OO.ui.CapsuleMultiSelectWidget
	 * and autocompletes with available categories.
	 *
	 * @class
	 * @uses mw.Api
	 * @extends OO.ui.CapsuleMultiSelectWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.CategorySelector = function ( config ) {
		// Parent constructor
		mw.widgets.CategorySelector.parent.call( this, config );

		// Event handler to call the autocomplete methods
		this.$input.on( 'change input cut paste', this.updateMenuItems.bind( this ) );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.CategorySelector, OO.ui.CapsuleMultiSelectWidget );

	/* Methods */

	/**
	 * Gets new items based on the input by calling
	 * {@link #getNewMenuItems getNewItems} and updates the menu
	 * after removing duplicates based on the data value.
	 *
	 * @private
	 * @method
	 */
	mw.widgets.CategorySelector.prototype.updateMenuItems = function () {
		this.getNewMenuItems( this.$input.val() ).then( function ( items ) {
			var existingItems, filteredItems,
				menu = this.getMenu();

			// Array of strings of the data of OO.ui.MenuOptionsWidgets
			existingItems = menu.getItems().map( function ( item ) {
				return item.data;
			} );

			// Remove if items' data already exists
			filteredItems = items.filter( function ( item ) {
				return existingItems.indexOf( item.data ) === -1;
			} );

			// Map to an array of OO.ui.MenuOptionWidgets
			filteredItems = filteredItems.map( function ( item ) {
				return new OO.ui.MenuOptionWidget( {
					data: item.data,
					label: item.label
				} );
			} );

			menu.addItems( filteredItems );
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
	mw.widgets.CategorySelector.prototype.getNewMenuItems = function ( input ) {
		var deferred = new $.Deferred(),
			catNsId = mw.config.get( 'wgNamespaceIds' ).category,
			api = new mw.Api();

		api.get( {
			action: 'opensearch',
			namespace: catNsId,
			limit: 10,
			search: input
		} ).done( function ( res ) {
			var categoryNames = res[ 1 ].map( function ( name ) {
				return mw.Title.newFromText( name, catNsId ).getMainText();
			} );

			deferred.resolve( categoryNames.map( function ( category ) {
				return {
					data: category,
					label: category
				};
			} ) );
		} );

		return deferred.promise();
	};
}( jQuery, mediaWiki ) );
