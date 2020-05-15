var ViewSwitchWidget = require( './ViewSwitchWidget.js' ),
	SaveFiltersPopupButtonWidget = require( './SaveFiltersPopupButtonWidget.js' ),
	MenuSelectWidget = require( './MenuSelectWidget.js' ),
	FilterTagItemWidget = require( './FilterTagItemWidget.js' ),
	FilterTagMultiselectWidget;

/**
 * List displaying all filter groups
 *
 * @class mw.rcfilters.ui.FilterTagMultiselectWidget
 * @extends OO.ui.MenuTagMultiselectWidget
 * @mixins OO.ui.mixin.PendingElement
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller Controller
 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
 * @param {Object} config Configuration object
 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
 * @cfg {jQuery} [$wrapper] A jQuery object for the wrapper of the general
 *  system. If not given, falls back to this widget's $element
 * @cfg {boolean} [collapsed] Filter area is collapsed
 */
FilterTagMultiselectWidget = function MwRcfiltersUiFilterTagMultiselectWidget( controller, model, savedQueriesModel, config ) {
	var $rcFiltersRow,
		title = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-activefilters' ),
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-content-title' ]
		} ),
		$contentWrapper = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper' );

	config = config || {};

	this.controller = controller;
	this.model = model;
	this.queriesModel = savedQueriesModel;
	this.$overlay = config.$overlay || this.$element;
	this.$wrapper = config.$wrapper || this.$element;
	this.matchingQuery = null;
	this.currentView = this.model.getCurrentView();
	this.collapsed = false;
	this.isMobile = config.isMobile;

	// Has to be before the parent constructor, because the parent constructor may call setValue()
	// which causes the onChangeTags handler to run (T245073)
	this.emptyFilterMessage = new OO.ui.LabelWidget( {
		label: mw.msg( 'rcfilters-empty-filter' ),
		classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-emptyFilters' ]
	} );

	// Parent
	FilterTagMultiselectWidget.parent.call( this, $.extend( true, {
		label: mw.msg( 'rcfilters-filterlist-title' ),
		placeholder: mw.msg( 'rcfilters-empty-filter' ),
		inputPosition: 'outline',
		allowArbitrary: false,
		allowDisplayInvalidTags: false,
		allowReordering: false,
		$overlay: this.$overlay,
		menu: {
			// Our filtering is done through the model
			filterFromInput: false,
			hideWhenOutOfView: false,
			hideOnChoose: false,
			// Only set width and footers for desktop
			isMobile: this.isMobile,
			width: 650,
			footers: [
				{
					name: 'viewSelect',
					sticky: false,
					// View select menu, appears on default view only
					$element: $( '<div>' )
						.append( new ViewSwitchWidget( this.controller, this.model ).$element ),
					views: [ 'default' ]
				},
				{
					name: 'feedback',
					// Feedback footer, appears on all views
					$element: $( '<div>' )
						.append(
							new OO.ui.ButtonWidget( {
								framed: false,
								icon: 'feedback',
								flags: [ 'progressive' ],
								label: mw.msg( 'rcfilters-filterlist-feedbacklink' ),
								href: 'https://www.mediawiki.org/wiki/Help_talk:New_filters_for_edit_review'
							} ).$element
						)
				}
			]
		},
		/**
		 * In the presence of an onscreen keyboard (i.e. isMobile) the filter input should act as a button
		 * rather than a text input. Mobile screens are too small to accommodate both an
		 * onscreen keyboard and a popup-menu, so readyOnly is set to disable the keyboard.
		 * A different icon and shorter message is used for mobile as well. (See T224655 for details).
		 */
		input: {
			icon: this.isMobile ? 'funnel' : 'menu',
			placeholder: this.isMobile ? mw.msg( 'rcfilters-search-placeholder-mobile' ) : mw.msg( 'rcfilters-search-placeholder' ),
			readOnly: !!this.isMobile,
			classes: [ 'oo-ui-tagMultiselectWidget-input' ]
		}
	}, config ) );

	this.input.$input.attr( 'aria-label', mw.msg( 'rcfilters-search-placeholder' ) );

	this.savedQueryTitle = new OO.ui.LabelWidget( {
		label: '',
		classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-content-savedQueryTitle' ]
	} );

	this.resetButton = new OO.ui.ButtonWidget( {
		framed: false,
		classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-resetButton' ]
	} );

	this.hideShowButton = new OO.ui.ButtonWidget( {
		framed: false,
		flags: [ 'progressive' ],
		classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-hideshowButton' ]
	} );
	this.toggleCollapsed( !!config.collapsed );

	if ( !mw.user.isAnon() ) {
		this.saveQueryButton = new SaveFiltersPopupButtonWidget(
			this.controller,
			this.queriesModel,
			{
				$overlay: this.$overlay
			}
		);

		this.saveQueryButton.$element.on( 'mousedown', function ( e ) {
			e.stopPropagation();
		} );

		this.saveQueryButton.connect( this, {
			click: 'onSaveQueryButtonClick',
			saveCurrent: 'setSavedQueryVisibility'
		} );
		this.queriesModel.connect( this, {
			itemUpdate: 'onSavedQueriesItemUpdate',
			initialize: 'onSavedQueriesInitialize',
			default: 'reevaluateResetRestoreState'
		} );
	}

	this.$content.append( this.emptyFilterMessage.$element );

	// Events
	this.resetButton.connect( this, { click: 'onResetButtonClick' } );
	this.hideShowButton.connect( this, { click: 'onHideShowButtonClick' } );
	// Stop propagation for mousedown, so that the widget doesn't
	// trigger the focus on the input and scrolls up when we click the reset button
	this.resetButton.$element.on( 'mousedown', function ( e ) {
		e.stopPropagation();
	} );
	this.hideShowButton.$element.on( 'mousedown', function ( e ) {
		e.stopPropagation();
	} );
	this.model.connect( this, {
		initialize: 'onModelInitialize',
		update: 'onModelUpdate',
		searchChange: this.isMobile ? function () {} : 'onModelSearchChange',
		itemUpdate: 'onModelItemUpdate',
		highlightChange: 'onModelHighlightChange'
	} );

	if ( !this.isMobile ) {
		this.input.connect( this, { change: 'onInputChange' } );
	}

	// The filter list and button should appear side by side regardless of how
	// wide the button is; the button also changes its width depending
	// on language and its state, so the safest way to present both side
	// by side is with a table layout
	$rcFiltersRow = $( '<div>' )
		.addClass( 'mw-rcfilters-ui-row' )
		.append(
			this.$content
				.addClass( 'mw-rcfilters-ui-cell' )
				.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-filters' )
		);

	if ( !mw.user.isAnon() ) {
		$rcFiltersRow.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-cell' )
				.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-save' )
				.append( this.saveQueryButton.$element )
		);
	}

	// Add a selector at the right of the input
	this.viewsSelectWidget = this.createViewsSelectWidget();

	// change the layout of the viewsSelectWidget
	this.restructureViewsSelectWidget();

	// Event
	this.viewsSelectWidget.aggregate( { click: 'buttonClick' } );
	this.viewsSelectWidget.connect( this, { buttonClick: 'onViewsSelectWidgetButtonClick' } );

	$rcFiltersRow.append(
		$( '<div>' )
			.addClass( 'mw-rcfilters-ui-cell' )
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-reset' )
			.append( this.resetButton.$element )
	);

	// Build the content
	$contentWrapper.append(
		$( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-top' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-top-title' )
					.append( title.$element ),
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-top-queryName' )
					.append( this.savedQueryTitle.$element ),
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-top-hideshow' )
					.append(
						this.hideShowButton.$element
					)
			),
		$( '<div>' )
			.addClass( 'mw-rcfilters-ui-table' )
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-filters' )
			.append( $rcFiltersRow )
	);

	// Initialize
	this.$handle.append( $contentWrapper );
	this.emptyFilterMessage.toggle( this.isEmpty() );
	this.savedQueryTitle.toggle( false );

	this.$element
		.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget' );

	if ( this.isMobile ) {
		this.$element
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-mobile' );
	}

	this.reevaluateResetRestoreState();
};

/* Initialization */

OO.inheritClass( FilterTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );

/* Methods */

/**
 * Create a OOUI ButtonGroupWidget. The buttons are framed and have additional CSS
 * classes applied on mobile.
 *
 * @return {OO.ui.ButtonGroupWidget}
 */
FilterTagMultiselectWidget.prototype.createViewsSelectWidget = function () {
	var viewsSelectWidget = new OO.ui.ButtonGroupWidget( {
		classes: this.isMobile ?
			[
				'mw-rcfilters-ui-table',
				'mw-rcfilters-ui-filterTagMultiselectWidget-mobile-view'
			] :
			[
				'mw-rcfilters-ui-filterTagMultiselectWidget-views-select-widget'
			],
		items: [
			new OO.ui.ButtonWidget( {
				framed: !!this.isMobile,
				data: 'namespaces',
				icon: 'article',
				label: mw.msg( 'namespaces' ),
				title: mw.msg( 'rcfilters-view-namespaces-tooltip' ),
				classes: this.isMobile ? [ 'mw-rcfilters-ui-cell' ] : []
			} ),
			new OO.ui.ButtonWidget( {
				framed: !!this.isMobile,
				data: 'tags',
				icon: 'tag',
				label: mw.msg( 'tags-title' ),
				title: mw.msg( 'rcfilters-view-tags-tooltip' ),
				classes: this.isMobile ? [ 'mw-rcfilters-ui-cell' ] : []
			} )
		]
	} );

	viewsSelectWidget.items.forEach( function ( item ) {
		item.$button.attr( 'aria-label', item.title );
	} );

	return viewsSelectWidget;
};

/**
 * Rearrange the DOM structure of the viewsSelectWiget so that on the namespace & tags buttons
 * are at the right of the input on desktop, and below the input on mobile.
 */
FilterTagMultiselectWidget.prototype.restructureViewsSelectWidget = function () {
	if ( this.isMobile ) {
		// On mobile, append the search input and the extra buttons below the search input.
		this.$element.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-views-input' )
				.append( this.input.$element )
				.append( this.viewsSelectWidget.$element )
		);
	} else {
		// On desktop, rearrange the UI so the select widget is at the right of the input
		this.$element.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-table' )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-views' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-views-input' )
								.append( this.input.$element ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-views-select' )
								.append( this.viewsSelectWidget.$element )
						)
				)
		);
	}
};

/**
 * Respond to button click event
 *
 * @param {OO.ui.ButtonWidget} buttonWidget Clicked widget
 */
FilterTagMultiselectWidget.prototype.onViewsSelectWidgetButtonClick = function ( buttonWidget ) {
	this.controller.switchView( buttonWidget.getData() );
	this.focus();
};

/**
 * Respond to model search change event
 *
 * @param {string} value Search value
 */
FilterTagMultiselectWidget.prototype.onModelSearchChange = function ( value ) {
	this.input.setValue( value );
};

/**
 * Respond to input change event
 *
 * @param {string} value Value of the input
 */
FilterTagMultiselectWidget.prototype.onInputChange = function ( value ) {
	this.controller.setSearch( value );
};

/**
 * Respond to query button click
 */
FilterTagMultiselectWidget.prototype.onSaveQueryButtonClick = function () {
	this.getMenu().toggle( false );
};

/**
 * Respond to save query model initialization
 */
FilterTagMultiselectWidget.prototype.onSavedQueriesInitialize = function () {
	this.setSavedQueryVisibility();
};

/**
 * Respond to save query item change. Mainly this is done to update the label in case
 * a query item has been edited
 *
 * @param {mw.rcfilters.dm.SavedQueryItemModel} item Saved query item
 */
FilterTagMultiselectWidget.prototype.onSavedQueriesItemUpdate = function ( item ) {
	if ( this.matchingQuery === item ) {
		// This means we just edited the item that is currently matched
		this.savedQueryTitle.setLabel( item.getLabel() );
	}
};

/**
 * Respond to menu toggle
 *
 * @param {boolean} isVisible Menu is visible
 */
FilterTagMultiselectWidget.prototype.onMenuToggle = function ( isVisible ) {

	var scrollToElement = this.isMobile ? this.input.$input : this.$element;

	// Parent
	FilterTagMultiselectWidget.parent.prototype.onMenuToggle.call( this );

	if ( isVisible ) {
		if ( !this.isMobile ) {
			this.focus();
		}

		mw.hook( 'RcFilters.popup.open' ).fire();

		if ( !this.getMenu().findSelectedItem() ) {
			// If there are no selected items, scroll menu to top
			// This has to be in a setTimeout so the menu has time
			// to be positioned and fixed
			setTimeout(
				function () {
					this.getMenu().scrollToTop();
				}.bind( this )
			);
		}

		// Only scroll to top of the viewport if:
		// - The widget is more than 20px from the top
		// - The widget is not above the top of the viewport (do not scroll downwards)
		//   (This isn't represented because >20 is, anyways and always, bigger than 0)
		this.scrollToTop( scrollToElement, 0, { min: 20, max: Infinity } );

	} else {
		// Clear selection
		this.selectTag( null );

		// Clear the search
		this.controller.setSearch( '' );

		// Log filter grouping
		this.controller.trackFilterGroupings( 'filtermenu' );

		this.blur();
	}

	if ( this.isMobile ) {
		this.input.setIcon( isVisible ? 'close' : 'funnel' );
	} else {
		this.input.setIcon( isVisible ? 'search' : 'menu' );
	}
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onInputFocus = function () {

	// treat the input as a menu toggle rather than a text field on mobile
	if ( this.isMobile ) {
		this.input.$input.trigger( 'blur' );
		this.getMenu().toggle();
	} else {
		// Parent
		FilterTagMultiselectWidget.parent.prototype.onInputFocus.call( this );
	}
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.doInputEscape = function () {
	// Parent
	FilterTagMultiselectWidget.parent.prototype.doInputEscape.call( this );

	// Blur the input
	this.input.$input.trigger( 'blur' );
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onMouseDown = function ( e ) {
	if ( !this.collapsed && !this.isDisabled() && e.which === OO.ui.MouseButtons.LEFT ) {
		this.menu.toggle();

		return false;
	}
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onChangeTags = function () {
	// If initialized, call parent method.
	if ( this.controller.isInitialized() ) {
		FilterTagMultiselectWidget.parent.prototype.onChangeTags.call( this );
	}

	this.emptyFilterMessage.toggle( this.isEmpty() );
};

/**
 * Respond to model initialize event
 */
FilterTagMultiselectWidget.prototype.onModelInitialize = function () {
	this.setSavedQueryVisibility();
};

/**
 * Respond to model update event
 */
FilterTagMultiselectWidget.prototype.onModelUpdate = function () {
	this.updateElementsForView();
};

/**
 * Update the elements in the widget to the current view
 */
FilterTagMultiselectWidget.prototype.updateElementsForView = function () {
	var view = this.model.getCurrentView(),
		inputValue = this.input.getValue().trim(),
		inputView = this.model.getViewByTrigger( inputValue.substr( 0, 1 ) );

	if ( inputView !== 'default' ) {
		// We have a prefix already, remove it
		inputValue = inputValue.substr( 1 );
	}

	if ( inputView !== view ) {
		// Add the correct prefix
		inputValue = this.model.getViewTrigger( view ) + inputValue;
	}

	// Update input
	this.input.setValue( inputValue );

	if ( this.currentView !== view ) {
		this.scrollToTop( this.$element );
		this.currentView = view;
	}
};

/**
 * Set the visibility of the saved query button
 */
FilterTagMultiselectWidget.prototype.setSavedQueryVisibility = function () {
	if ( mw.user.isAnon() ) {
		return;
	}

	this.matchingQuery = this.controller.findQueryMatchingCurrentState();

	this.savedQueryTitle.setLabel(
		this.matchingQuery ? this.matchingQuery.getLabel() : ''
	);
	this.savedQueryTitle.toggle( !!this.matchingQuery );
	this.saveQueryButton.setDisabled( !!this.matchingQuery );
	this.saveQueryButton.setTitle( !this.matchingQuery ?
		mw.msg( 'rcfilters-savedqueries-add-new-title' ) :
		mw.msg( 'rcfilters-savedqueries-already-saved' ) );

	if ( this.matchingQuery ) {
		this.emphasize();
	}
};

/**
 * Respond to model itemUpdate event
 * fixme: when a new state is applied to the model this function is called 60+ times in a row
 *
 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
 */
FilterTagMultiselectWidget.prototype.onModelItemUpdate = function ( item ) {
	if ( !item.getGroupModel().isHidden() ) {
		if (
			item.isSelected() ||
			(
				this.model.isHighlightEnabled() &&
				item.getHighlightColor()
			)
		) {
			this.addTag( item.getName(), item.getLabel() );
		} else {
			// Only attempt to remove the tag if we can find an item for it (T198140, T198231)
			if ( this.findItemFromData( item.getName() ) !== null ) {
				this.removeTagByData( item.getName() );
			}
		}
	}

	this.setSavedQueryVisibility();

	// Re-evaluate reset state
	this.reevaluateResetRestoreState();
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.isAllowedData = function ( data ) {
	return (
		this.model.getItemByName( data ) &&
		!this.isDuplicateData( data )
	);
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onMenuChoose = function ( item ) {
	this.controller.toggleFilterSelect( item.model.getName() );

	// Select the tag if it exists, or reset selection otherwise
	this.selectTag( this.findItemFromData( item.model.getName() ) );

	if ( !this.isMobile ) {
		this.focus();
	}

};

/**
 * Respond to highlightChange event
 *
 * @param {boolean} isHighlightEnabled Highlight is enabled
 */
FilterTagMultiselectWidget.prototype.onModelHighlightChange = function ( isHighlightEnabled ) {
	var highlightedItems = this.model.getHighlightedItems();

	if ( isHighlightEnabled ) {
		// Add capsule widgets
		highlightedItems.forEach( function ( filterItem ) {
			this.addTag( filterItem.getName(), filterItem.getLabel() );
		}.bind( this ) );
	} else {
		// Remove capsule widgets if they're not selected
		highlightedItems.forEach( function ( filterItem ) {
			if ( !filterItem.isSelected() ) {
				// Only attempt to remove the tag if we can find an item for it (T198140, T198231)
				if ( this.findItemFromData( filterItem.getName() ) !== null ) {
					this.removeTagByData( filterItem.getName() );
				}
			}
		}.bind( this ) );
	}

	this.setSavedQueryVisibility();
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onTagSelect = function ( tagItem ) {
	var menuOption = this.menu.getItemFromModel( tagItem.getModel() );

	this.menu.setUserSelecting( true );
	// Parent method
	FilterTagMultiselectWidget.parent.prototype.onTagSelect.call( this, tagItem );

	// Switch view
	this.controller.resetSearchForView( tagItem.getView() );

	this.selectTag( tagItem );
	this.scrollToTop( menuOption.$element );

	this.menu.setUserSelecting( false );
};

/**
 * Select a tag by reference. This is what OO.ui.SelectWidget is doing.
 * If no items are given, reset selection from all.
 *
 * @param {mw.rcfilters.ui.FilterTagItemWidget} [item] Tag to select,
 *  omit to deselect all
 */
FilterTagMultiselectWidget.prototype.selectTag = function ( item ) {
	var i, len, selected;

	for ( i = 0, len = this.items.length; i < len; i++ ) {
		selected = this.items[ i ] === item;
		if ( this.items[ i ].isSelected() !== selected ) {
			this.items[ i ].toggleSelected( selected );
		}
	}
};
/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.onTagRemove = function ( tagItem ) {
	// Parent method
	FilterTagMultiselectWidget.parent.prototype.onTagRemove.call( this, tagItem );

	this.controller.clearFilter( tagItem.getName() );

	tagItem.destroy();
};

/**
 * Respond to click event on the reset button
 */
FilterTagMultiselectWidget.prototype.onResetButtonClick = function () {
	if ( this.model.areVisibleFiltersEmpty() ) {
		// Reset to default filters
		this.controller.resetToDefaults();
	} else {
		// Reset to have no filters
		this.controller.emptyFilters();
	}
};

/**
 * Respond to hide/show button click
 */
FilterTagMultiselectWidget.prototype.onHideShowButtonClick = function () {
	this.toggleCollapsed();
};

/**
 * Toggle the collapsed state of the filters widget
 *
 * @param {boolean} isCollapsed Widget is collapsed
 */
FilterTagMultiselectWidget.prototype.toggleCollapsed = function ( isCollapsed ) {
	isCollapsed = isCollapsed === undefined ? !this.collapsed : !!isCollapsed;

	this.collapsed = isCollapsed;

	if ( isCollapsed ) {
		// If we are collapsing, close the menu, in case it was open
		// We should make sure the menu closes before the rest of the elements
		// are hidden, otherwise there is an unknown error in jQuery as ooui
		// sets and unsets properties on the input (which is hidden at that point)
		this.menu.toggle( false );
	}
	this.input.setDisabled( isCollapsed );
	this.hideShowButton.setLabel( mw.msg(
		isCollapsed ? 'rcfilters-activefilters-show' : 'rcfilters-activefilters-hide'
	) );
	this.hideShowButton.setTitle( mw.msg(
		isCollapsed ? 'rcfilters-activefilters-show-tooltip' : 'rcfilters-activefilters-hide-tooltip'
	) );

	// Toggle the wrapper class, so we have min height values correctly throughout
	this.$wrapper.toggleClass( 'mw-rcfilters-collapsed', isCollapsed );

	// Save the state
	this.controller.updateCollapsedState( isCollapsed );
};

/**
 * Reevaluate the restore state for the widget between setting to defaults and clearing all filters
 */
FilterTagMultiselectWidget.prototype.reevaluateResetRestoreState = function () {
	var defaultsAreEmpty = this.controller.areDefaultsEmpty(),
		currFiltersAreEmpty = this.model.areVisibleFiltersEmpty(),
		hideResetButton = currFiltersAreEmpty && defaultsAreEmpty;

	this.resetButton.setIcon(
		currFiltersAreEmpty ? 'history' : 'trash'
	);

	this.resetButton.setLabel(
		currFiltersAreEmpty ? mw.msg( 'rcfilters-restore-default-filters' ) : ''
	);
	this.resetButton.setTitle(
		currFiltersAreEmpty ? null : mw.msg( 'rcfilters-clear-all-filters' )
	);

	this.resetButton.toggle( !hideResetButton );
	this.emptyFilterMessage.toggle( currFiltersAreEmpty );
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.createMenuWidget = function ( menuConfig ) {
	return new MenuSelectWidget(
		this.controller,
		this.model,
		menuConfig
	);
};

/**
 * @inheritdoc
 */
FilterTagMultiselectWidget.prototype.createTagItemWidget = function ( data ) {
	var filterItem = this.model.getItemByName( data );

	if ( filterItem ) {
		return new FilterTagItemWidget(
			this.controller,
			this.model,
			this.model.getInvertModel(),
			filterItem,
			{
				$overlay: this.$overlay
			}
		);
	}
};

FilterTagMultiselectWidget.prototype.emphasize = function () {
	if (
		// eslint-disable-next-line no-jquery/no-class-state
		!this.$handle.hasClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-animate' )
	) {
		this.$handle
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-emphasize' )
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-animate' );

		setTimeout( function () {
			this.$handle
				.removeClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-emphasize' );

			setTimeout( function () {
				this.$handle
					.removeClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-animate' );
			}.bind( this ), 1000 );
		}.bind( this ), 500 );

	}
};
/**
 * Scroll the element to top within its container
 *
 * @private
 * @param {jQuery} $element Element to position
 * @param {number} [marginFromTop=0] When scrolling the entire widget to the top, leave this
 *  much space (in pixels) above the widget.
 * @param {Object} [threshold] Minimum distance from the top of the element to scroll at all
 * @param {number} [threshold.min] Minimum distance above the element
 * @param {number} [threshold.max] Minimum distance below the element
 */
FilterTagMultiselectWidget.prototype.scrollToTop = function ( $element, marginFromTop, threshold ) {
	var container = OO.ui.Element.static.getClosestScrollableContainer( $element[ 0 ], 'y' ),
		pos = OO.ui.Element.static.getRelativePosition( $element, $( container ) ),
		containerScrollTop = $( container ).scrollTop(),
		effectiveScrollTop = $( container ).is( 'body, html' ) ? 0 : containerScrollTop,
		newScrollTop = effectiveScrollTop + pos.top - ( marginFromTop || 0 );

	// Scroll to item
	if (
		threshold === undefined ||
		(
			(
				threshold.min === undefined ||
				newScrollTop - containerScrollTop >= threshold.min
			) &&
			(
				threshold.max === undefined ||
				newScrollTop - containerScrollTop <= threshold.max
			)
		)
	) {
		$( container ).animate( {
			scrollTop: newScrollTop
		} );
	}
};

module.exports = FilterTagMultiselectWidget;
