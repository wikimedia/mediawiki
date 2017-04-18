( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget = function MwRcfiltersUiFilterTagMultiselectWidget( controller, model, config ) {
		var title = new OO.ui.LabelWidget( {
				label: mw.msg( 'rcfilters-activefilters' ),
				classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper-content-title' ]
			} ),
			$contentWrapper = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-wrapper' );

		config = config || {};

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

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

		this.resetButton = new OO.ui.ButtonWidget( {
			framed: false,
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-resetButton' ]
		} );

		this.emptyFilterMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-empty-filter' ),
			classes: [ 'mw-rcfilters-ui-filterTagMultiselectWidget-emptyFilters' ]
		} );
		this.$content.append( this.emptyFilterMessage.$element );

		// Events
		this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		// Stop propagation for mousedown, so that the widget doesn't
		// trigger the focus on the input and scrolls up when we click the reset button
		this.resetButton.$element.on( 'mousedown', function ( e ) { e.stopPropagation(); } );
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			itemUpdate: 'onModelItemUpdate',
			highlightChange: 'onModelHighlightChange'
		} );
		this.menu.connect( this, { toggle: 'onMenuToggle' } );

		// Build the content
		$contentWrapper.append(
			title.$element,
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
								.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget-cell-reset' )
								.append( this.resetButton.$element )
						)
				)
		);

		// Initialize
		this.$handle.append( $contentWrapper );
		this.emptyFilterMessage.toggle( this.isEmpty() );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterTagMultiselectWidget' );

		this.populateFromModel();
		this.reevaluateResetRestoreState();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );

	/* Methods */

	/**
	 * Respond to menu toggle
	 *
	 * @param {boolean} isVisible Menu is visible
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onMenuToggle = function ( isVisible ) {
		if ( isVisible ) {
			mw.hook( 'RcFilters.popup.open' ).fire( this.getMenu().getSelectedItem() );

			if ( !this.getMenu().getSelectedItem() ) {
				// If there are no selected items, scroll menu to top
				// This has to be in a setTimeout so the menu has time
				// to be positioned and fixed
				setTimeout( function () { this.getMenu().scrollToTop(); }.bind( this ), 0 );
			}
		} else {
			// Clear selection
			this.getMenu().selectItem( null );
			this.selectTag( null );
		}
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
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onModelInitialize = function () {
		this.populateFromModel();
	};

	/**
	 * Respond to model itemUpdate event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onModelItemUpdate = function ( item ) {
		if (
			item.isSelected() ||
			(
				this.model.isHighlightEnabled() &&
				item.isHighlightSupported() &&
				item.getHighlightColor()
			)
		) {
			this.addTag( item.getName(), item.getLabel() );
		} else {
			this.removeTagByData( item.getName() );
		}

		// Re-evaluate reset state
		this.reevaluateResetRestoreState();
	};

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
		if ( this.model.areCurrentFiltersEmpty() ) {
			// Reset to default filters
			this.controller.resetToDefaults();
		} else {
			// Reset to have no filters
			this.controller.emptyFilters();
		}
	};

	/**
	 * Reevaluate the restore state for the widget between setting to defaults and clearing all filters
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.reevaluateResetRestoreState = function () {
		var defaultsAreEmpty = this.model.areDefaultFiltersEmpty(),
			currFiltersAreEmpty = this.model.areCurrentFiltersEmpty(),
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
		return new mw.rcfilters.ui.FilterFloatingMenuSelectWidget(
			this.controller,
			this.model,
			$.extend( {
				filterFromInput: true
			}, menuConfig )
		);
	};

	/**
	 * Populate the menu from the model
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.populateFromModel = function () {
		var widget = this,
			items = [];

		// Reset
		this.getMenu().clearItems();

		$.each( this.model.getFilterGroups(), function ( groupName, groupModel ) {
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
			widget.model.getGroupFilters( groupName ).forEach( function ( filterItem ) {
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

		// Add all items to the menu
		this.getMenu().addItems( items );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.createTagItemWidget = function ( data ) {
		var filterItem = this.model.getItemByName( data );

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
