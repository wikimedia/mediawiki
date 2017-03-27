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

		// Parent
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.call( this, $.extend( true, {
			placeholder: mw.msg( 'rcfilters-empty-filter' ),
			inputPosition: 'outline',
			allowArbitrary: false,
			allowDisplayInvalidTags: false,
			allowReordering: false,
			menu: {
				hideOnChoose: false,
				width: 450
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
		// this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			itemUpdate: 'onModelItemUpdate',
			highlightChange: 'onModelHighlightChange'
		} );
		// this.aggregate( { click: 'capsuleItemClick' } );

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
		// this.reevaluateResetRestoreState();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterTagMultiselectWidget, OO.ui.MenuTagMultiselectWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onInputFocus = function () {
		// Parent
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onInputFocus.call( this );

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
		// this.reevaluateResetRestoreState();
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onMenuChoose = function ( item ) {
		this.controller.toggleFilterSelect( item.model.getName() );
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
		var menuOption = this.menu.getItemFromData( tagItem.getData() );

		// Parent method
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onTagSelect.call( this, tagItem );

		// Scroll to the item
		this.scrollToTop( menuOption.$element );
	};
	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.onTagRemove = function ( tagItem ) {
		// Parent method
		mw.rcfilters.ui.FilterTagMultiselectWidget.parent.prototype.onTagRemove.call( this, tagItem );

		this.controller.clearFilter( tagItem.getName() );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.createMenuWidget = function ( menuConfig ) {
		return new mw.rcfilters.ui.FilterFloatingMenuSelectWidget( this.model, $.extend( {
			filterFromInput: true
		}, menuConfig ) );
	};

	/**
	 * Populate the menu from the model
	 */
	mw.rcfilters.ui.FilterTagMultiselectWidget.prototype.populateFromModel = function () {
		var widget = this,
			items = [];

		// Reset
		this.getMenu().clearItems();

		// Add header
		items.push(
			new mw.rcfilters.ui.FilterMenuHeaderSectionWidget(
				this.controller,
				this.model,
				{
					$overlay: this.$overlay
				}
			)
		);

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
