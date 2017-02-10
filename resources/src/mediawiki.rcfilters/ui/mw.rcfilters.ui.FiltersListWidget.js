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

		this.highlightButton = new OO.ui.ButtonWidget( {
			label: mw.message( 'rcfilters-highlightbutton-title' ).text(),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-hightlightButton' ]
		} );
		this.highlightButton.on( 'click', this.onHighlightButtonClick.bind( this ) );

		this.$label.append( this.highlightButton.$element );

		this.noResultsLabel = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-filterlist-noresults' ),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-noresults' ]
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			highlightChange: 'onHighlightChange'
		} );

		// Initialize
		this.showNoResultsMessage( false );
		this.$element
			.addClass( 'mw-rcfilters-ui-filtersListWidget' )
			.append(
				this.$label,
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

		this.addItems(
			Object.keys( this.model.getFilterGroups() ).map( function ( groupName ) {
				return new mw.rcfilters.ui.FilterGroupWidget(
					widget.controller,
					widget.model.getGroup( groupName )
				);
			} )
		);
	};

	mw.rcfilters.ui.FiltersListWidget.prototype.onHighlightChange = function ( highlightEnabled ) {
		this.highlightButton.setActive( highlightEnabled );
	};

	mw.rcfilters.ui.FiltersListWidget.prototype.onHighlightButtonClick = function () {
		this.controller.toggleHighlight();
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
				// Only show items that are in the filtered list
				itemWidgets[ j ].toggle(
					hasItemWithName( groupItems[ groupName ], itemWidgets[ j ].getName() )
				);
			}
		}
	};
}( mediaWiki, jQuery ) );
