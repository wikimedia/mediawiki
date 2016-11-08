( function ( mw, $ ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {string|jQuery} [title] Title for the list
	 */
	mw.rcfilters.ui.FiltersListWidget = function MwRcfiltersUiFiltersListWidget( controller, model, config ) {
		var titleWidget;

		config = config || {};

		// Parent
		mw.rcfilters.ui.FiltersListWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );

		this.controller = controller;
		this.model = model;

		this.noResultsLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-filterlist-noresults' ),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-noresults' ]
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

		// Events
		this.model.connect( this, {
			update: 'onModelUpdate'
		} );

		// Initialize
		this.showNoResultsMessage( false );
		this.$element
			.addClass( 'mw-rcfilters-ui-filtersListWidget' )
			.append(
				this.$group
					.addClass( 'mw-rcfilters-ui-filtersListWidget-group' ),
				this.noResultsLabel.$element
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
			groupWidget = new mw.rcfilters.ui.FilterGroupWidget( group, {
				title: groups[ group ].title
			} );
			groupWidgets.push( groupWidget );

			itemWidgets = [];
			if ( groups[ group ].filters ) {
				for ( i = 0; i < groups[ group ].filters.length; i++ ) {
					itemWidgets.push(
						new mw.rcfilters.ui.FilterItemWidget(
							this.controller,
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

	mw.rcfilters.ui.FiltersListWidget.prototype.showNoResultsMessage = function ( showNoResults ) {
		this.noResultsLabel.toggle( !!showNoResults );
		this.$group.toggleClass( 'oo-ui-element-hidden', !!showNoResults );
	};

	/**
	 * Show only the items matching with the models in the given list
	 *
	 * @param {Object} groupItems An object of items to show
	 *  arranged by their group names
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.filter = function ( groupItems ) {
		var i, j, groupName, itemWidgets,
			groupWidgets = this.getItems(),
			hasItemWithName = function ( itemArr, name ) {
				return !!itemArr.filter( function ( item ) {
					return item.getName() === name;
				} ).length;
			};

		if ( $.isEmptyObject( groupItems ) ) {
			// No results. Hide everything, show only 'no results'
			// message
			this.showNoResultsMessage( true );
			return;
		}

		this.showNoResultsMessage( false );
		for ( i = 0; i < groupWidgets.length; i++ ) {
			groupName = groupWidgets[ i ].getName();

			// Hide or show the entire group if it is in
			// the filtered groups
			groupWidgets[ i ].toggle( groupItems[ groupName ] );

			if ( !groupItems[ groupName ] ) {
				// Continue to next group
				continue;
			}

			// We have items to show
			itemWidgets = groupWidgets[ i ].getItems();
			for ( j = 0; j < itemWidgets.length; j++ ) {
				// Only show items that are in the filtered list
				itemWidgets[ j ].toggle(
					hasItemWithName( groupItems[ groupName ], itemWidgets[ j ].getName() )
				);
			}
		}
	};

} )( mediaWiki, jQuery );
