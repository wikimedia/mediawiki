( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 */
	mw.rcfilters.ui.FiltersListWidget = function MwRcfiltersUiFiltersListWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FiltersListWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );

		this.model = model;

		// Events
		this.model.connect( this, {
			update: 'onModelUpdate'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-filtersListWidget' )
			.append( this.$group );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FiltersListWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FiltersListWidget, OO.ui.mixin.GroupWidget );

	/* Methods */
	mw.rcfilters.ui.FiltersListWidget.prototype.onModelUpdate = function () {
		var i, group, groupWidget,
			itemWidgets = [],
			groupWidgets = [],
			groups = this.model.getFilterGroups();

		for ( group in groups ) {
			groupWidget = new mw.rcfilters.ui.FilterGroupWidget( {
				title: mw.msg( 'rcfilters-filtergroup-' + group )
			} );
			groupWidgets.push( groupWidget );

			itemWidgets = [];
			for ( i = 0; i < groups[ group ].length; i++ ) {
				itemWidgets.push(
					new mw.rcfilters.ui.FilterItemWidget(
						groups[ group ][ i ],
						{
							label: groups[ group ][ i ].getLabel(),
							description: groups[ group ][ i ].getDescription()
						}
					)
				);
			}

			groupWidget.addItems( itemWidgets );
		}

		this.addItems( groupWidgets );
	};
} )( mediaWiki );
