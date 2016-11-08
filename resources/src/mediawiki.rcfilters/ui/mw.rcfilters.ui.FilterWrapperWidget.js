( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 */
	mw.rcfilters.ui.FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.model = model;

		this.filterPopup = new mw.rcfilters.ui.FiltersListWidget( this.model, {
			title: mw.msg( 'rcfilters-filterlist-title' )
		} );

		this.capsule = new OO.ui.CapsuleMultiselectWidget( {
			popup: { $content: this.filterPopup.$element },
		} );

		// Events
		this.model.connect( this, {
			update: 'onModelUpdate',
			itemUpdate: 'onModelItemUpdate'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' )
			.append( this.capsule.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/**
	 * Respond to model update event and set up the available filters to choose
	 * from.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelUpdate = function () {
		var i,
			items = [],
			filters = this.model.getItems();

		// Insert hidden options for the capsule to get its item data from
		for ( i = 0; i < filters.length; i++ ) {
			items.push(
				new OO.ui.MenuOptionWidget( {
					data: filters[ i ].getName(),
					label: filters[ i ].getLabel()
				} )
			);
		}

		this.capsule.getMenu().addItems( items );
	};

	/**
	 * Respond to model item update
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item that was updated
	 * @param {boolean} isSelected State of the filter
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelItemUpdate = function ( item, isSelected ) {
		if ( isSelected ) {
			this.capsule.addItemsFromData( [ item.getName() ] );
		} else {
			this.capsule.removeItemsFromData( [ item.getName() ] );
		}
	};
} )( mediaWiki );
