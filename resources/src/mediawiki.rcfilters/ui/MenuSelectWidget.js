const FilterMenuHeaderWidget = require( './FilterMenuHeaderWidget.js' ),
	HighlightPopupWidget = require( './HighlightPopupWidget.js' ),
	FilterMenuSectionOptionWidget = require( './FilterMenuSectionOptionWidget.js' ),
	FilterMenuOptionWidget = require( './FilterMenuOptionWidget.js' );

/**
 * A floating menu widget for the filter list.
 *
 * @class mw.rcfilters.ui.MenuSelectWidget
 * @ignore
 * @extends OO.ui.MenuSelectWidget
 *
 * @param {mw.rcfilters.Controller} controller Controller
 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
 * @param {Object} [config] Configuration object
 * @param {boolean} [config.isMobile] a boolean flag that determines whether some
 * elements should be displayed based on whether the UI is mobile or not.
 * @param {jQuery} [config.$overlay] A jQuery object serving as overlay for popups
 * @param {Object[]} [config.footers] An array of objects defining the footers for
 *  this menu, with a definition whether they appear per specific views.
 *  The expected structure is:
 *  [
 *     {
 *        name: {string} A unique name for the footer object
 *        $element: {jQuery} A jQuery object for the content of the footer
 *        views: {string[]} Optional. An array stating which views this footer is
 *               active on. Use null or omit to display this on all views.
 *     }
 *  ]
 */
const MenuSelectWidget = function MwRcfiltersUiMenuSelectWidget( controller, model, config ) {
	config = config || {};

	this.controller = controller;
	this.model = model;
	this.currentView = '';
	this.views = {};
	this.userSelecting = false;

	this.menuInitialized = false;
	this.$overlay = config.$overlay || this.$element;
	this.$body = $( '<div>' ).addClass( 'mw-rcfilters-ui-menuSelectWidget-body' );
	this.footers = [];

	// Parent
	MenuSelectWidget.super.call( this, Object.assign( config, {
		$autoCloseIgnore: this.$overlay,
		width: 650,
		// Our filtering is done through the model
		filterFromInput: false
	} ) );
	this.setGroupElement(
		$( '<div>' )
			.addClass( 'mw-rcfilters-ui-menuSelectWidget-group' )
	);

	this.setClippableElement( this.$body );
	this.setClippableContainer( this.$element );

	const header = new FilterMenuHeaderWidget(
		this.controller,
		this.model,
		{
			$overlay: this.$overlay,
			isMobile: config.isMobile
		}
	);

	this.noResults = new OO.ui.LabelWidget( {
		label: mw.msg( 'rcfilters-filterlist-noresults' ),
		classes: [ 'mw-rcfilters-ui-menuSelectWidget-noresults' ]
	} );

	// Events
	this.model.connect( this, {
		initialize: 'onModelInitialize',
		searchChange: 'onModelSearchChange'
	} );

	// Initialization
	this.$element
		.addClass( 'mw-rcfilters-ui-menuSelectWidget' )
		.attr( 'aria-label', mw.msg( 'rcfilters-filterlist-title' ) )
		.append( header.$element )
		.append(
			this.$body
				.append( this.$group, this.noResults.$element )
		);

	// Append all footers; we will control their visibility
	// based on view
	config.footers = config.isMobile ? [] : config.footers || [];
	config.footers.forEach( ( footerData ) => {
		const isSticky = footerData.sticky === undefined ? true : !!footerData.sticky,
			adjustedData = {
				// Wrap the element with our own footer wrapper
				// The following classes are used here:
				// * mw-rcfilters-ui-menuSelectWidget-footer-viewSelect
				// * and no others (currently)
				$element: $( '<div>' )
					.addClass( 'mw-rcfilters-ui-menuSelectWidget-footer' )
					.addClass( 'mw-rcfilters-ui-menuSelectWidget-footer-' + footerData.name )
					.append( footerData.$element ),
				views: footerData.views
			};

		if ( !footerData.disabled ) {
			this.footers.push( adjustedData );

			if ( isSticky ) {
				this.$element.append( adjustedData.$element );
			} else {
				this.$body.append( adjustedData.$element );
			}
		}
	} );

	// Switch to the correct view
	this.updateView();
};

/* Initialize */

OO.inheritClass( MenuSelectWidget, OO.ui.MenuSelectWidget );

/* Events */

/* Methods */
MenuSelectWidget.prototype.onModelSearchChange = function () {
	this.updateView();
};

/**
 * @inheritdoc
 */
MenuSelectWidget.prototype.toggle = function ( show ) {
	this.lazyMenuCreation();
	MenuSelectWidget.super.prototype.toggle.call( this, show );
	// Always open this menu downwards. FilterTagMultiselectWidget scrolls it into view.
	this.setVerticalPosition( 'below' );
};

/**
 * lazy creation of the menu
 */
MenuSelectWidget.prototype.lazyMenuCreation = function () {
	const items = [],
		viewGroupCount = {},
		groups = this.model.getFilterGroups();

	if ( this.menuInitialized ) {
		return;
	}

	this.menuInitialized = true;

	// Create shared popup for highlight buttons
	this.highlightPopup = new HighlightPopupWidget( this.controller );
	this.$overlay.append( this.highlightPopup.$element );

	// Count groups per view
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( groups, ( groupName, groupModel ) => {
		if ( !groupModel.isHidden() ) {
			viewGroupCount[ groupModel.getView() ] = viewGroupCount[ groupModel.getView() ] || 0;
			viewGroupCount[ groupModel.getView() ]++;
		}
	} );

	// eslint-disable-next-line no-jquery/no-each-util
	$.each( groups, ( groupName, groupModel ) => {
		const currentItems = [],
			view = groupModel.getView();

		if ( !groupModel.isHidden() ) {
			if ( viewGroupCount[ view ] > 1 ) {
				// Only add a section header if there is more than
				// one group
				currentItems.push(
					// Group section
					new FilterMenuSectionOptionWidget(
						this.controller,
						groupModel,
						{
							$overlay: this.$overlay
						}
					)
				);
			}

			// Add items
			this.model.getGroupFilters( groupName ).forEach( ( filterItem ) => {
				currentItems.push(
					new FilterMenuOptionWidget(
						this.controller,
						this.model,
						this.model.getInvertModel( view ),
						filterItem,
						this.highlightPopup,
						{
							$overlay: this.$overlay
						}
					)
				);
			} );

			// Cache the items per view, so we can switch between them
			// without rebuilding the widgets each time
			this.views[ view ] = this.views[ view ] || [];
			this.views[ view ] = this.views[ view ].concat( currentItems );
			items.push( ...currentItems );
		}
	} );

	this.addItems( items );
	this.updateView();
};

/**
 * Respond to model initialize event. Populate the menu from the model
 */
MenuSelectWidget.prototype.onModelInitialize = function () {
	this.menuInitialized = false;
	// Set timeout for the menu to lazy build.
	setTimeout( this.lazyMenuCreation.bind( this ) );
};

/**
 * Update view
 */
MenuSelectWidget.prototype.updateView = function () {
	const viewName = this.model.getCurrentView();

	if ( this.views[ viewName ] && this.currentView !== viewName ) {
		this.updateFooterVisibility( viewName );

		// The following classes are used here:
		// * mw-rcfilters-ui-menuSelectWidget-view-default
		// * mw-rcfilters-ui-menuSelectWidget-view-namespaces
		// * mw-rcfilters-ui-menuSelectWidget-view-tags
		this.$element
			.data( 'view', viewName )
			.removeClass( 'mw-rcfilters-ui-menuSelectWidget-view-' + this.currentView )
			.addClass( 'mw-rcfilters-ui-menuSelectWidget-view-' + viewName );

		this.currentView = viewName;
		this.scrollToTop();
	}

	this.postProcessItems();
	this.clip();
};

/**
 * Go over the available footers and decide which should be visible
 * for this view
 *
 * @param {string} [currentView] Current view
 */
MenuSelectWidget.prototype.updateFooterVisibility = function ( currentView ) {
	currentView = currentView || this.model.getCurrentView();

	this.footers.forEach( ( data ) => {
		data.$element.toggle(
			// This footer should only be shown if it is configured
			// for all views or for this specific view
			!data.views || data.views.length === 0 || data.views.includes( currentView )
		);
	} );
};

/**
 * Post-process items after the visibility changed. Make sure
 * that we always have an item selected, and that the no-results
 * widget appears if the menu is empty.
 */
MenuSelectWidget.prototype.postProcessItems = function () {
	let itemWasSelected = false;
	const items = this.getItems();

	// If we are not already selecting an item, always make sure
	// that the top item is selected
	if ( !this.userSelecting ) {
		// Select the first item in the list
		for ( let i = 0; i < items.length; i++ ) {
			if (
				!( items[ i ] instanceof OO.ui.MenuSectionOptionWidget ) &&
				items[ i ].isVisible()
			) {
				itemWasSelected = true;
				this.selectItem( items[ i ] );
				break;
			}
		}

		if ( !itemWasSelected ) {
			this.selectItem( null );
		}
	}

	this.noResults.toggle( !this.getItems().some( ( item ) => item.isVisible() ) );
};

/**
 * Get the option widget that matches the model given
 *
 * @ignore
 * @param {mw.rcfilters.dm.ItemModel} model Item model
 * @return {mw.rcfilters.ui.ItemMenuOptionWidget} Option widget
 */
MenuSelectWidget.prototype.getItemFromModel = function ( model ) {
	this.lazyMenuCreation();
	return this.views[ model.getGroupModel().getView() ].filter( ( item ) => item.getName() === model.getName() )[ 0 ];
};

/**
 * @inheritdoc
 */
MenuSelectWidget.prototype.onDocumentKeyDown = function ( e ) {
	const currentItem = this.findHighlightedItem() || this.findSelectedItem();
	let nextItem;

	// Call parent
	MenuSelectWidget.super.prototype.onDocumentKeyDown.call( this, e );

	// We want to select the item on arrow movement
	// rather than just highlight it, like the menu
	// does by default
	if ( !this.isDisabled() && this.isVisible() ) {
		switch ( e.keyCode ) {
			case OO.ui.Keys.UP:
			case OO.ui.Keys.LEFT:
				// Get the next item
				nextItem = this.findRelativeSelectableItem( currentItem, -1 );
				break;
			case OO.ui.Keys.DOWN:
			case OO.ui.Keys.RIGHT:
				// Get the next item
				nextItem = this.findRelativeSelectableItem( currentItem, 1 );
				break;
		}

		nextItem = nextItem && nextItem.constructor.static.selectable ?
			nextItem : null;

		// Select the next item
		this.selectItem( nextItem );
	}
};

/**
 * Scroll to the top of the menu
 */
MenuSelectWidget.prototype.scrollToTop = function () {
	this.$body.scrollTop( 0 );
};

/**
 * Set whether the user is currently selecting an item.
 * This is important when the user selects an item that is in between
 * different views, and makes sure we do not re-select a different
 * item (like the item on top) when this is happening.
 *
 * @param {boolean} isSelecting User is selecting
 */
MenuSelectWidget.prototype.setUserSelecting = function ( isSelecting ) {
	this.userSelecting = !!isSelecting;
};

module.exports = MenuSelectWidget;
