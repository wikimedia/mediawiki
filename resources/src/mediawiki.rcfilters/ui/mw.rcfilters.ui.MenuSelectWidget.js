( function ( mw ) {
	/**
	 * A floating menu widget for the filter list
	 *
	 * @extends OO.ui.MenuSelectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 * @cfg {Object[]} [footers] An array of objects defining the footers for
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
	mw.rcfilters.ui.MenuSelectWidget = function MwRcfiltersUiMenuSelectWidget( controller, model, config ) {
		var header;

		config = config || {};

		this.controller = controller;
		this.model = model;
		this.currentView = '';
		this.views = {};
		this.userSelecting = false;

		this.inputValue = '';
		this.$overlay = config.$overlay || this.$element;
		this.$body = $( '<div>' ).addClass( 'mw-rcfilters-ui-menuSelectWidget-body' );
		this.footers = [];

		// Parent
		mw.rcfilters.ui.MenuSelectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: this.$overlay,
			width: 650
		}, config ) );
		this.setGroupElement(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-menuSelectWidget-group' )
		);
		this.setClippableElement( this.$body );
		this.setClippableContainer( this.$element );

		header = new mw.rcfilters.ui.FilterMenuHeaderWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

		this.noResults = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-filterlist-noresults' ),
			classes: [ 'mw-rcfilters-ui-menuSelectWidget-noresults' ]
		} );

		// Events
		this.model.connect( this, {
			update: 'onModelUpdate',
			initialize: 'onModelInitialize'
		} );

		// Initialization
		this.$element
			.addClass( 'mw-rcfilters-ui-menuSelectWidget' )
			.append( header.$element )
			.append(
				this.$body
					.append( this.$group, this.noResults.$element )
			);

		// Append all footers; we will control their visibility
		// based on view
		config.footers = config.footers || [];
		config.footers.forEach( function ( footerData ) {
			var isSticky = footerData.sticky === undefined ? true : !!footerData.sticky,
				adjustedData = {
					// Wrap the element with our own footer wrapper
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
		}.bind( this ) );

		// Switch to the correct view
		this.switchView( this.model.getCurrentView() );
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.MenuSelectWidget, OO.ui.MenuSelectWidget );

	/* Events */

	/**
	 * @event itemVisibilityChange
	 *
	 * Item visibility has changed
	 */

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.onModelUpdate = function () {
		// Change view
		this.switchView( this.model.getCurrentView() );
	};

	/**
	 * Respond to model initialize event. Populate the menu from the model
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.onModelInitialize = function () {
		var widget = this,
			viewGroupCount = {},
			groups = this.model.getFilterGroups();

		// Reset
		this.clearItems();

		// Count groups per view
		$.each( groups, function ( groupName, groupModel ) {
			if ( !groupModel.isHidden() ) {
				viewGroupCount[ groupModel.getView() ] = viewGroupCount[ groupModel.getView() ] || 0;
				viewGroupCount[ groupModel.getView() ]++;
			}
		} );

		$.each( groups, function ( groupName, groupModel ) {
			var currentItems = [],
				view = groupModel.getView();

			if ( !groupModel.isHidden() ) {
				if ( viewGroupCount[ view ] > 1 ) {
					// Only add a section header if there is more than
					// one group
					currentItems.push(
						// Group section
						new mw.rcfilters.ui.FilterMenuSectionOptionWidget(
							widget.controller,
							groupModel,
							{
								$overlay: widget.$overlay
							}
						)
					);
				}

				// Add items
				widget.model.getGroupFilters( groupName ).forEach( function ( filterItem ) {
					currentItems.push(
						new mw.rcfilters.ui.FilterMenuOptionWidget(
							widget.controller,
							filterItem,
							{
								$overlay: widget.$overlay
							}
						)
					);
				} );

				// Cache the items per view, so we can switch between them
				// without rebuilding the widgets each time
				widget.views[ view ] = widget.views[ view ] || [];
				widget.views[ view ] = widget.views[ view ].concat( currentItems );
			}
		} );

		this.switchView( this.model.getCurrentView() );
	};

	/**
	 * Switch view
	 *
	 * @param {string} [viewName] View name. If not given, default is used.
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.switchView = function ( viewName ) {
		viewName = viewName || 'default';

		if ( this.views[ viewName ] && this.currentView !== viewName ) {
			this.clearItems();
			this.addItems( this.views[ viewName ] );
			this.updateFooterVisibility( viewName );

			this.$element
				.data( 'view', viewName )
				.removeClass( 'mw-rcfilters-ui-menuSelectWidget-view-' + this.currentView )
				.addClass( 'mw-rcfilters-ui-menuSelectWidget-view-' + viewName );

			this.currentView = viewName;
			this.scrollToTop();
			this.clip();
		}
	};

	/**
	 * Go over the available footers and decide which should be visible
	 * for this view
	 *
	 * @param {string} [currentView] Current view
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.updateFooterVisibility = function ( currentView ) {
		currentView = currentView || this.model.getCurrentView();

		this.footers.forEach( function ( data ) {
			data.$element.toggle(
				// This footer should only be shown if it is configured
				// for all views or for this specific view
				!data.views || data.views.length === 0 || data.views.indexOf( currentView ) > -1
			);
		} );
	};

	/**
	 * @fires itemVisibilityChange
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.updateItemVisibility = function () {
		var i,
			itemWasSelected = false,
			inputVal = this.$input.val(),
			items = this.getItems();

		// Since the method hides/shows items, we don't want to
		// call it unless the input actually changed
		if (
			!this.userSelecting &&
			this.inputValue !== inputVal
		) {
			// Parent method
			mw.rcfilters.ui.MenuSelectWidget.parent.prototype.updateItemVisibility.call( this );

			// Select the first item in the list
			for ( i = 0; i < items.length; i++ ) {
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

			// Cache value
			this.inputValue = inputVal;

			this.emit( 'itemVisibilityChange' );
		}

		this.noResults.toggle( !this.getItems().some( function ( item ) {
			return item.isVisible();
		} ) );
	};

	/**
	 * Get the option widget that matches the model given
	 *
	 * @param {mw.rcfilters.dm.ItemModel} model Item model
	 * @return {mw.rcfilters.ui.ItemMenuOptionWidget} Option widget
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.getItemFromModel = function ( model ) {
		return this.views[ model.getGroupModel().getView() ].filter( function ( item ) {
			return item.getName() === model.getName();
		} )[ 0 ];
	};

	/**
	 * Override the item matcher to use the model's match process
	 *
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.getItemMatcher = function ( s ) {
		var results = this.model.findMatches( s, true );

		return function ( item ) {
			return results.indexOf( item.getModel() ) > -1;
		};
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.onKeyDown = function ( e ) {
		var nextItem,
			currentItem = this.getHighlightedItem() || this.getSelectedItem();

		// Call parent
		mw.rcfilters.ui.MenuSelectWidget.parent.prototype.onKeyDown.call( this, e );

		// We want to select the item on arrow movement
		// rather than just highlight it, like the menu
		// does by default
		if ( !this.isDisabled() && this.isVisible() ) {
			switch ( e.keyCode ) {
				case OO.ui.Keys.UP:
				case OO.ui.Keys.LEFT:
					// Get the next item
					nextItem = this.getRelativeSelectableItem( currentItem, -1 );
					break;
				case OO.ui.Keys.DOWN:
				case OO.ui.Keys.RIGHT:
					// Get the next item
					nextItem = this.getRelativeSelectableItem( currentItem, 1 );
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
	mw.rcfilters.ui.MenuSelectWidget.prototype.scrollToTop = function () {
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
	mw.rcfilters.ui.MenuSelectWidget.prototype.setUserSelecting = function ( isSelecting ) {
		this.userSelecting = !!isSelecting;
	};
}( mediaWiki ) );
