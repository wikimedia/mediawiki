( function ( mw, $ ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.GroupWidget
	 * @mixins OO.ui.mixin.LabelElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FiltersListWidget = function MwRcfiltersUiFiltersListWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FiltersListWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filtersListWidget-title' )
		} ) );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;
		this.groups = {};
		this.selected = null;

		this.highlightButton = new OO.ui.ToggleButtonWidget( {
			icon: 'highlight',
			label: mw.message( 'rcfilters-highlightbutton-title' ).text(),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-hightlightButton' ]
		} );

		this.noResultsLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-filterlist-noresults' ),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-noresults' ]
		} );

		// Events
		this.highlightButton.connect( this, { click: 'onHighlightButtonClick' } );
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			highlightChange: 'onModelHighlightChange'
		} );

		// Initialize
		this.showNoResultsMessage( false );
		this.$element
			.addClass( 'mw-rcfilters-ui-filtersListWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.addClass( 'mw-rcfilters-ui-filtersListWidget-header' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filtersListWidget-header-title' )
									.append( this.$label ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filtersListWidget-header-highlight' )
									.append( this.highlightButton.$element )
							)
					),
				// this.$label,
				this.$group
					.addClass( 'mw-rcfilters-ui-filtersListWidget-group' ),
				this.noResultsLabel.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FiltersListWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FiltersListWidget, OO.ui.mixin.GroupWidget );
	OO.mixinClass( mw.rcfilters.ui.FiltersListWidget, OO.ui.mixin.LabelElement );

	/* Methods */

	/**
	 * Respond to initialize event from the model
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.onModelInitialize = function () {
		var widget = this;

		// Reset
		this.clearItems();
		this.groups = {};

		this.addItems(
			Object.keys( this.model.getFilterGroups() ).map( function ( groupName ) {
				var groupWidget = new mw.rcfilters.ui.FilterGroupWidget(
					widget.controller,
					widget.model.getGroup( groupName ),
					{
						$overlay: widget.$overlay
					}
				);

				widget.groups[ groupName ] = groupWidget;
				return groupWidget;
			} )
		);
	};

	/**
	 * Respond to model highlight change event
	 *
	 * @param {boolean} highlightEnabled Highlight is enabled
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.onModelHighlightChange = function ( highlightEnabled ) {
		this.highlightButton.setActive( highlightEnabled );
	};

	/**
	 * Respond to highlight button click
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.onHighlightButtonClick = function () {
		this.controller.toggleHighlight();
	};

	/**
	 * Find the filter item widget that corresponds to the item name
	 *
	 * @param {string} itemName Filter name
	 * @return {mw.rcfilters.ui.FilterItemWidget} Filter widget
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.getItemWidget = function ( itemName ) {
		var filterItem = this.model.getItemByName( itemName ),
			// Find the group
			groupWidget = this.groups[ filterItem.getGroupName() ];

		// Find the item inside the group
		return groupWidget.getItemWidget( itemName );
	};

	/**
	 * Get the current selection
	 *
	 * @return {string|null} Selected filter. Null if none is selected.
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.getSelectedFilter = function () {
		return this.selected;
	};

	/**
	 * Mark an item widget as selected
	 *
	 * @param {string} itemName Filter name
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.select = function ( itemName ) {
		var filterWidget;

		if ( this.selected !== itemName ) {
			// Unselect previous
			if ( this.selected ) {
				filterWidget = this.getItemWidget( this.selected );
				filterWidget.toggleSelected( false );
			}

			// Select new one
			this.selected = itemName;
			if ( this.selected ) {
				filterWidget = this.getItemWidget( this.selected );
				filterWidget.toggleSelected( true );
			}
		}
	};

	/**
	 * Reset selection and remove selected states from all items
	 */
	mw.rcfilters.ui.FiltersListWidget.prototype.resetSelection = function () {
		if ( this.selected !== null ) {
			this.selected = null;
			this.getItems().forEach( function ( groupWidget ) {
				groupWidget.getItems().forEach( function ( filterItemWidget ) {
					filterItemWidget.toggleSelected( false );
				} );
			} );
		}
	};

	/**
	 * Switch between showing the 'no results' message for filtering results or the result list.
	 *
	 * @param {boolean} showNoResults Show no results message
	 */
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
		var i, j, groupName, itemWidgets, topItem, isVisible,
			groupWidgets = this.getItems(),
			hasItemWithName = function ( itemArr, name ) {
				return !!itemArr.filter( function ( item ) {
					return item.getName() === name;
				} ).length;
			};

		this.resetSelection();

		if ( $.isEmptyObject( groupItems ) ) {
			// No results. Hide everything, show only 'no results'
			// message
			this.showNoResultsMessage( true );
			return;
		}

		this.showNoResultsMessage( false );
		for ( i = 0; i < groupWidgets.length; i++ ) {
			groupName = groupWidgets[ i ].getName();

			// If this group widget is in the filtered results,
			// show it - otherwise, hide it
			groupWidgets[ i ].toggle( !!groupItems[ groupName ] );

			if ( !groupItems[ groupName ] ) {
				// Continue to next group
				continue;
			}

			// We have items to show
			itemWidgets = groupWidgets[ i ].getItems();
			for ( j = 0; j < itemWidgets.length; j++ ) {
				isVisible = hasItemWithName( groupItems[ groupName ], itemWidgets[ j ].getName() );
				// Only show items that are in the filtered list
				itemWidgets[ j ].toggle( isVisible );

				if ( !topItem && isVisible ) {
					topItem = itemWidgets[ j ];
				}
			}
		}

		// Select the first item
		if ( topItem ) {
			this.select( topItem.getName() );
		}
	};
}( mediaWiki, jQuery ) );
