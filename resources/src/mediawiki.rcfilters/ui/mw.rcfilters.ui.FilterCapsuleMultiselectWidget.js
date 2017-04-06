( function ( mw, $ ) {
	/**
	 * Filter-specific CapsuleMultiselectWidget
	 *
	 * @class
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model RCFilters view model
	 * @param {OO.ui.InputWidget} filterInput A filter input that focuses the capsule widget
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget = function MwRcfiltersUiFilterCapsuleMultiselectWidget( controller, model, filterInput, config ) {
		var title = new OO.ui.LabelWidget( {
				label: mw.msg( 'rcfilters-activefilters' ),
				classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-wrapper-content-title' ]
			} ),
			$contentWrapper = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-wrapper' );

		this.$overlay = config.$overlay || this.$element;

		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.call( this, $.extend( true, {
			popup: {
				$autoCloseIgnore: filterInput.$element.add( this.$overlay ),
				$floatableContainer: filterInput.$element
			}
		}, config ) );

		this.controller = controller;
		this.model = model;
		this.filterInput = filterInput;
		this.isSelecting = false;
		this.selected = null;

		this.resetButton = new OO.ui.ButtonWidget( {
			framed: false,
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-resetButton' ]
		} );

		this.saveFiltersButton = new OO.ui.ButtonWidget( {
			icon: 'bookmark',
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-saveFilters' ],
			label: mw.msg( 'rcfilters-savedqueries-save' )
		} );

		this.emptyFilterMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-empty-filter' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-emptyFilters' ]
		} );
		this.$content.append( this.emptyFilterMessage.$element );

		// Events
		this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		this.saveFiltersButton.connect( this, { click: 'onSaveFiltersButtonClick' } );
		this.resetButton.$element.on( 'mousedown', this.onResetButtonMouseDown.bind( this ) );
		this.saveFiltersButton.$element.on( 'mousedown', this.onResetButtonMouseDown.bind( this ) );
		this.model.connect( this, {
			itemUpdate: 'onModelItemUpdate',
			highlightChange: 'onModelHighlightChange'
		} );
		this.aggregate( { click: 'capsuleItemClick' } );

		// Add the filterInput as trigger
		this.filterInput.$input
			.on( 'focus', this.focus.bind( this ) );

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
								.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell-filters' ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell-options' )
								.append(
									$( '<div>' )
										.addClass( 'mw-rcfilters-ui-table' )
										.append(
											$( '<div>' )
												.addClass( 'mw-rcfilters-ui-row' )
												.append(
													$( '<div>' )
														.addClass( 'mw-rcfilters-ui-cell' )
														.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell-reset' )
														.append( this.resetButton.$element ),
													$( '<div>' )
														.addClass( 'mw-rcfilters-ui-cell' )
														.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell-save' )
														.append( this.saveFiltersButton.$element )
												)
										)
								)
						)
				)
		);

		// Initialize
		this.$handle.append( $contentWrapper );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget' );

		this.reevaluateResetRestoreState();
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterCapsuleMultiselectWidget, OO.ui.CapsuleMultiselectWidget );

	/* Events */

	/**
	 * @event remove
	 * @param {string[]} filters Array of names of removed filters
	 *
	 * Filters were removed
	 */

	/* Methods */

	/**
	 * Respond to model itemUpdate event
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item model
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onModelItemUpdate = function ( item ) {
		if (
			item.isSelected() ||
			(
				this.model.isHighlightEnabled() &&
				item.isHighlightSupported() &&
				item.getHighlightColor()
			)
		) {
			this.addItemByName( item.getName() );
		} else {
			this.removeItemByName( item.getName() );
		}

		// Re-evaluate reset state
		this.reevaluateResetRestoreState();
	};

	/**
	 * Respond to highlightChange event
	 *
	 * @param {boolean} isHighlightEnabled Highlight is enabled
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onModelHighlightChange = function ( isHighlightEnabled ) {
		var highlightedItems = this.model.getHighlightedItems();

		if ( isHighlightEnabled ) {
			// Add capsule widgets
			highlightedItems.forEach( function ( filterItem ) {
				this.addItemByName( filterItem.getName() );
			}.bind( this ) );
		} else {
			// Remove capsule widgets if they're not selected
			highlightedItems.forEach( function ( filterItem ) {
				if ( !filterItem.isSelected() ) {
					this.removeItemByName( filterItem.getName() );
				}
			}.bind( this ) );
		}
	};

	/**
	 * Respond to click event on the save filters button
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onSaveFiltersButtonClick = function () {
		this.controller.saveCurrentFiltersQuery();
	};

	/**
	 * Respond to click event on the reset button
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onResetButtonClick = function () {
		if ( this.model.areCurrentFiltersEmpty() ) {
			// Reset to default filters
			this.controller.resetToDefaults();
		} else {
			// Reset to have no filters
			this.controller.emptyFilters();
		}
	};

	/**
	 * Respond to mouse down event on the reset button to prevent the popup from opening
	 *
	 * @param {jQuery.Event} e Event
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onResetButtonMouseDown = function ( e ) {
		e.stopPropagation();
	};

	/**
	 * Reevaluate the restore state for the widget between setting to defaults and clearing all filters
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.reevaluateResetRestoreState = function () {
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
	 * Mark an item widget as selected
	 *
	 * @param {mw.rcfilters.ui.CapsuleItemWidget} item Capsule widget
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.select = function ( item ) {
		if ( this.selected !== item ) {
			// Unselect previous
			if ( this.selected ) {
				this.selected.toggleSelected( false );
			}

			// Select new one
			this.selected = item;
			if ( this.selected ) {
				item.toggleSelected( true );
			}
		}
	};

	/**
	 * Reset selection and remove selected states from all items
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.resetSelection = function () {
		if ( this.selected !== null ) {
			this.selected = null;
			this.getItems().forEach( function ( capsuleWidget ) {
				capsuleWidget.toggleSelected( false );
			} );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.createItemWidget = function ( data ) {
		var item = this.model.getItemByName( data );

		if ( !item ) {
			return;
		}

		return new mw.rcfilters.ui.CapsuleItemWidget(
			this.controller,
			item,
			{ $overlay: this.$overlay }
		);
	};

	/**
	 * Add items by their filter name
	 *
	 * @param {string} name Filter name
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.addItemByName = function ( name ) {
		var item = this.model.getItemByName( name );

		if ( !item ) {
			return;
		}

		// Check that the item isn't already added
		if ( !this.getItemFromData( name ) ) {
			this.addItems( [ this.createItemWidget( name ) ] );
		}
	};

	/**
	 * Remove items by their filter name
	 *
	 * @param {string} name Filter name
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.removeItemByName = function ( name ) {
		this.removeItemsFromData( [ name ] );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.focus = function () {
		// Override this method; we don't want to focus on the popup, and we
		// don't want to bind the size to the handle.
		if ( !this.isDisabled() ) {
			this.popup.toggle( true );
			this.filterInput.$input.get( 0 ).focus();
		}
		return this;
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onFocusForPopup = function () {
		// HACK can be removed once I21b8cff4048 is merged in oojs-ui
		this.focus();
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onKeyDown = function () {};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onPopupFocusOut = function () {};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.clearInput = function () {
		if ( this.filterInput ) {
			this.filterInput.setValue( '' );
		}
		this.menu.toggle( false );
		this.menu.selectItem();
		this.menu.highlightItem();
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.removeItems = function ( items ) {
		// Parent call
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.prototype.removeItems.call( this, items );

		// Destroy the item widget when it is removed
		// This is done because we re-add items by recreating them, rather than hiding them
		// and items include popups, that will just continue to be created and appended
		// unnecessarily.
		items.forEach( function ( widget ) {
			widget.destroy();
		} );
	};

	/**
	 * Override 'editItem' since it tries to use $input which does
	 * not exist when a popup is available.
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.editItem = function () {};
}( mediaWiki, jQuery ) );
