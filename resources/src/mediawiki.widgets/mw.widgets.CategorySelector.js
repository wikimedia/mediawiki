( function ( $, mw ) {

	mw.widgets.CategorySelector = function ( config ) {
		mw.widgets.CategorySelector.parent.call( this, config );
		this.$input.on( 'change input cut paste', this.updateMenuItems.bind( this ) );
	};

	OO.inheritClass( mw.widgets.CategorySelector, OO.ui.CapsuleMultiSelectWidget );

	// could this just be part of OO.ui.CapsuleMultiSelectWidget?
	mw.widgets.CategorySelector.prototype.updateMenuItems = function () {
		this.getNewMenuItems( this.$input.val() ).then( function ( items ) {
			var existingItems, filteredItems,
				menu = this.getMenu();

			// Array of strings of the data of OO.ui.MenuOptionsWidgets
			existingItems = menu.getItems().map( function ( item ) {
				return item.data
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

	// should be abstract
	mw.widgets.CategorySelector.prototype.getNewMenuItems = function ( input ) {
		var d = new $.Deferred(),
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

			d.resolve( categoryNames.map( function ( category ) {
				return {
					data: category,
					label: category
				}
			} ) );
		} );

		return d;
	}

}( jQuery, mediaWiki ) );
