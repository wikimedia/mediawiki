( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.MenuTagMultiselectWidget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model Filters view model
	 * @param {mw.rcfilters.dm.NamespacesViewModel} namepacesModel Namespaces view model
	 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget = function MwRcfiltersUiFilterTagMultiselectWidget( controller, model, namespacesModel, savedQueriesModel, config ) {
		var title = new OO.ui.LabelWidget( {
				label: mw.msg( 'rcfilters-activefilters' ),
				classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-content-title' ]
			} ),
			$contentWrapper = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper' );

		config = config || {};

		this.controller = controller;
		this.filtersModel = model;
		this.namespacesModel = namespacesModel;
		this.queriesModel = savedQueriesModel;
		this.$overlay = config.$overlay || this.$element;
		this.menuTypes = {};
		this.menuMode = 'filters';

		// Parent
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.call( this, $.extend( true, {
			label: mw.msg( 'rcfilters-filterlist-title' ),
			placeholder: mw.msg( 'rcfilters-empty-filter' ),
			inputPosition: 'outline',
			allowArbitrary: false,
			allowDisplayInvalidTags: false,
			allowReordering: false,
			$overlay: this.$overlay,
			menu: {
				hideWhenOutOfView: false,
				hideOnChoose: false,
				width: 650,
				$footer: $( '<div>' )
					.append(
						new OO.ui.ButtonWidget( {
							framed: false,
							icon: 'feedback',
							flags: [ 'progressive' ],
							label: mw.msg( 'rcfilters-filterlist-feedbacklink' ),
							href: 'https://www.mediawiki.org/wiki/Help_talk:New_filters_for_edit_review'
						} ).$element
					)
			},
			input: {
				icon: 'search',
				placeholder: mw.msg( 'rcfilters-search-placeholder' )
			}
		}, config ) );

		this.savedQueryTitle = new OO.ui.LabelWidget( {
			label: '',
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-content-savedQueryTitle' ]
		} );

		this.resetButton = new OO.ui.ButtonWidget( {
			framed: false,
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-resetButton' ]
		} );

		this.saveQueryButton = new mw.rcfilters.ui.SaveFiltersPopupButtonWidget(
			this.controller,
			this.queriesModel
		);

		this.emptyFilterMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-empty-filter' ),
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-emptyFilters' ]
		} );
		this.$content.append( this.emptyFilterMessage.$element );

		// Extended filter buttons
		this.namespaceToggleButton = new OO.ui.ToggleButtonWidget( {
			icon: 'article',
			label: mw.msg( 'rcfilters-extended-button-namespace-label' ),
			title: mw.msg( 'rcfilters-extended-button-namespace-tooltip' ),
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-extended-namespaceButton' ]
		} );

		// Events
		this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		// Stop propagation for mousedown, so that the widget doesn't
		// trigger the focus on the input and scrolls up when we click the reset button
		this.resetButton.$element.on( 'mousedown', function ( e ) { e.stopPropagation(); } );
		this.saveQueryButton.$element.on( 'mousedown', function ( e ) { e.stopPropagation(); } );
		this.namespaceToggleButton.$element.on( 'mousedown', function ( e ) { e.stopPropagation(); } );
		this.filtersModel.connect( this, {
			initialize: 'onFiltersModelInitialize',
			itemUpdate: 'onFiltersModelItemUpdate',
			highlightChange: 'onModelHighlightChange'
		} );
		this.namespacesModel.connect( this, {
			initialize: 'onNamespacesModelInitialize',
			itemUpdate: 'onNamespacesModelItemUpdate',
		} );
		this.saveQueryButton.connect( this, { click: 'onSaveQueryButtonClick' } );
		this.namespaceToggleButton.connect( this, { change: 'onNamespaceToggleChange' } );

		// Build the content
		$contentWrapper.append(
			title.$element,
			this.savedQueryTitle.$element,
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-table' )
				.append(
					// The filter list and button should appear side by side regardless of how
					// wide the button is; the button also changes its width depending
					// on language and its state, so the safest way to present both side
					// by side is with a table layout
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.append(
							this.$content
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-filters' ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-save' )
								.append( this.saveQueryButton.$element ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-reset' )
								.append( this.resetButton.$element )
						)
				)
		);

		// Initialize
		this.$handle.append( $contentWrapper );
		this.emptyFilterMessage.toggle( this.isEmpty() );
		this.savedQueryTitle.toggle( false );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget' )
			.append( this.namespaceToggleButton.$element );

		this.populateFromModel( 'filters' );
		this.populateFromModel( 'namespaces' );
		this.changeMenuMode( 'filters' );
		this.reevaluateResetRestoreState();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );

	/* Methods */

	/**
	 * Respond to query button click
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onSaveQueryButtonClick = function () {
		this.getMenu().toggle( false );
	};

	/**
	 * Respond to menu toggle
	 *
	 * @param {boolean} isVisible Menu is visible
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onMenuToggle = function ( isVisible ) {
		// Parent
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onMenuToggle.call( this );

		if ( isVisible ) {
			mw.hook( 'RcFilters.popup.open' ).fire();

			if ( !this.getMenu().getSelectedItem() ) {
				// If there are no selected items, scroll menu to top
				// This has to be in a setTimeout so the menu has time
				// to be positioned and fixed
				setTimeout( function () { this.getMenu().scrollToTop(); }.bind( this ), 0 );
			}
		} else {
			// Clear selection
			this.selectTag( null );
		}
	};

	/**
	 * Respond to namespace toggle button change event
	 *
	 * @param {boolean} isActive The toggle state is on
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onNamespaceToggleChange = function ( isActive ) {
		this.changeMenuMode( isActive ? 'namespaces' : 'filters' );
		this.focus();
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onInputFocus = function () {
		// Parent
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onInputFocus.call( this );

		// Scroll to top
		this.scrollToTop( this.$element );
	};

	/**
	 * @inheridoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onChangeTags = function () {
		// Parent method
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onChangeTags.call( this );

		this.emptyFilterMessage.toggle( this.isEmpty() );
	};

	/**
	 * Respond to model initialize event
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onFiltersModelInitialize = function () {
		this.populateFromModel( 'filters' );
		this.changeMenuMode( 'filters' );

		this.setSavedQueryVisibility();
	};

	/**
	 * Respond to model initialize event
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onNamespacesModelInitialize = function () {
		this.populateFromModel( 'namespaces' );

		// this.setSavedQueryVisibility();
	};

	/**
	 * Set the visibility of the saved query button
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.setSavedQueryVisibility = function () {
		var matchingQuery = this.controller.findQueryMatchingCurrentState();

		this.savedQueryTitle.setLabel(
			matchingQuery ? matchingQuery.getLabel() : ''
		);
		this.savedQueryTitle.toggle( !!matchingQuery );
		this.saveQueryButton.toggle(
			!this.isEmpty() &&
			!matchingQuery
		);
	};

	/**
	 * Respond to model itemUpdate event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onFiltersModelItemUpdate = function ( item ) {
		if (
			item.isSelected() ||
			(
				this.filtersModel.isHighlightEnabled() &&
				item.isHighlightSupported() &&
				item.getHighlightColor()
			)
		) {
			this.addTag( item.getName(), item.getLabel() );
		} else {
			this.removeTagByData( item.getName() );
		}

		this.setSavedQueryVisibility();

		// Re-evaluate reset state
		this.reevaluateResetRestoreState();
	};

	/**
	 * Respond to model itemUpdate event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onNamespacesModelItemUpdate = function ( item ) {};
	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.isAllowedData = function ( data ) {
		return (
			this.menu.getItemFromData( data ) &&
			!this.isDuplicateData( data )
		);
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onMenuChoose = function ( item ) {
		this.controller.toggleFilterSelect( item.model.getName() );

		// Select the tag if it exists, or reset selection otherwise
		this.selectTag( this.getItemFromData( item.model.getName() ) );

		this.focus();
	};

	/**
	 * Respond to highlightChange event
	 *
	 * @param {boolean} isHighlightEnabled Highlight is enabled
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onModelHighlightChange = function ( isHighlightEnabled ) {
		var highlightedItems = this.filtersModel.getHighlightedItems();

		if ( isHighlightEnabled ) {
			// Add capsule widgets
			highlightedItems.forEach( function ( filterItem ) {
				this.addTag( filterItem.getName(), filterItem.getLabel() );
			}.bind( this ) );
		} else {
			// Remove capsule widgets if they're not selected
			highlightedItems.forEach( function ( filterItem ) {
				if ( !filterItem.isSelected() ) {
					this.removeTagByData( filterItem.getName() );
				}
			}.bind( this ) );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onTagSelect = function ( tagItem ) {
		var widget = this,
			menuOption = this.menu.getItemFromData( tagItem.getData() ),
			oldInputValue = this.input.getValue();

		// Reset input
		this.input.setValue( '' );

		// Parent method
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onTagSelect.call( this, tagItem );

		this.menu.selectItem( menuOption );
		this.selectTag( tagItem );

		// Scroll to the item
		if ( oldInputValue ) {
			// We're binding a 'once' to the itemVisibilityChange event
			// so this happens when the menu is ready after the items
			// are visible again, in case this is done right after the
			// user filtered the results
			this.getMenu().once(
				'itemVisibilityChange',
				function () { widget.scrollToTop( menuOption.$element ); }
			);
		} else {
			this.scrollToTop( menuOption.$element );
		}
	};

	/**
	 * Select a tag by reference. This is what OO.ui.SelectWidget is doing.
	 * If no items are given, reset selection from all.
	 *
	 * @param {mw.rcfilters.ui.FilterTagItemWidget} [item] Tag to select,
	 *  omit to deselect all
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.selectTag = function ( item ) {
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
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onTagRemove = function ( tagItem ) {
		// Parent method
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onTagRemove.call( this, tagItem );

		this.controller.clearFilter( tagItem.getName() );

		tagItem.destroy();
	};

	/**
	 * Respond to click event on the reset button
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onResetButtonClick = function () {
		if ( this.filtersModel.areCurrentFiltersEmpty() ) {
			// Reset to default filters
			this.controller.resetToDefaults();
		} else {
			// Reset to have no filters
			this.controller.emptyFilters();
		}
	};

	/**
	 * Change the menu type visible.
	 *
	 * @param {string} type Menu type. Available types are 'filters' and 'namespaces'
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.changeMenuMode = function ( type ) {
		if ( this.menuMode !== type && this.menuTypes[ type ] ) {
			this.menu.clearItems();
			this.menu.addItems( this.menuTypes[ type ] );
			this.menuMode = type;
		}
	};

	/**
	 * Reevaluate the restore state for the widget between setting to defaults and clearing all filters
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.reevaluateResetRestoreState = function () {
		var defaultsAreEmpty = this.filtersModel.areDefaultFiltersEmpty(),
			currFiltersAreEmpty = this.filtersModel.areCurrentFiltersEmpty(),
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
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.createMenuWidget = function ( menuConfig ) {
		return new mw.rcfilters.ui.FloatingMenuSelectWidget(
			this.controller,
			this.filtersModel,
			$.extend( {
				filterFromInput: true
			}, menuConfig )
		);
	};

	/**
	 * Populate the menu from the model
	 *
	 * @param {string} type Model type
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.populateFromModel = function ( type ) {
		var widget = this,
			items = [];

		if ( type === 'filters' ) {
			$.each( this.filtersModel.getFilterGroups(), function ( groupName, groupModel ) {
				items.push(
					// Group section
					new mw.rcfilters.ui.FilterMenuSectionOptionWidget(
						widget.controller,
						groupModel,
						{
							$overlay: widget.$overlay
						}
					)
				);

				// Add items
				widget.filtersModel.getGroupFilters( groupName ).forEach( function ( filterItem ) {
					items.push(
						new mw.rcfilters.ui.FilterMenuOptionWidget(
							widget.controller,
							filterItem,
							{
								$overlay: widget.$overlay
							}
						)
					);
				} );
			} );

		} else if ( type === 'namespaces' ) {
			$.each( this.namespacesModel.getItems(), function ( namespaceItem ) {
				items.push(
					new mw.rcfilters.ui.NamespaceMenuOptionWidget(
						widget.controller,
						namespaceItem,
						{
							$overlay: widget.$overlay
						}
					)
				);
			} );
		}

		// Add to reference
		this.menuTypes[ type ] = items;
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.createTagItemWidget = function ( data ) {
		var filterItem = this.filtersModel.getItemByName( data );

		if ( filterItem ) {
			return new mw.rcfilters.ui.FilterTagItemWidget(
				this.controller,
				filterItem,
				{
					$overlay: this.$overlay
				}
			);
		}
	};

	/**
	 * Scroll the element to top within its container
	 *
	 * @private
	 * @param {jQuery} $element Element to position
	 * @param {number} [marginFromTop] When scrolling the entire widget to the top, leave this
	 *  much space (in pixels) above the widget.
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.scrollToTop = function ( $element, marginFromTop ) {
		var container = OO.ui.Element.static.getClosestScrollableContainer( $element[ 0 ], 'y' ),
			pos = OO.ui.Element.static.getRelativePosition( $element, $( container ) ),
			containerScrollTop = $( container ).is( 'body, html' ) ? 0 : $( container ).scrollTop();

		// Scroll to item
		$( container ).animate( {
			scrollTop: containerScrollTop + pos.top - ( marginFromTop || 0 )
		} );
	};
}( mediaWiki ) );
