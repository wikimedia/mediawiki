( function ( mw, $ ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {string|jQuery} [title] Title for the list
	 */
	mw.rcfilters.ui.FiltersListWidget = function MwRcfiltersUiFiltersListWidget( model, config ) {
		var titleWidget;

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

		if ( config.title ) {
			titleWidget = new OO.ui.LabelWidget( {
				label: config.title
			} );
			this.$element.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filtersListWidget-title' )
					.append( titleWidget.$element )
			);
		}

		this.$element
			.addClass( 'mw-rcfilters-ui-filtersListWidget' )
			.append(
				this.$group
					.addClass( 'mw-rcfilters-ui-filtersListWidget-group' )
			);
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
				title: groups[ group ].title
			} );
			groupWidgets.push( groupWidget );

			itemWidgets = [];
			if ( groups[ group ].filters ) {
				for ( i = 0; i < groups[ group ].filters.length; i++ ) {
					itemWidgets.push(
						new mw.rcfilters.ui.FilterItemWidget(
							groups[ group ].filters[ i ],
							{
								label: groups[ group ].filters[ i ].getLabel(),
								description: groups[ group ].filters[ i ].getDescription()
							}
						)
					);
				}

				groupWidget.addItems( itemWidgets );
			}
		}

		this.addItems( groupWidgets );
	};
} )( mediaWiki, jQuery );
