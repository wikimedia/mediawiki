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
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: filterInput.$element
		}, config ) );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.filterInput = filterInput;

		this.$content.prepend(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content-title' )
				.text( mw.msg( 'rcfilters-activefilters' ) )
		);

		this.resetButton = new OO.ui.ButtonWidget( {
			icon: 'trash',
			framed: false,
			title: mw.msg( 'rcfilters-clear-all-filters' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-resetButton' ]
		} );

		this.emptyFilterMessage = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-empty-filter' ),
			classes: [ 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-emptyFilters' ]
		} );

		// Events
		this.resetButton.connect( this, { click: 'onResetButtonClick' } );
		this.model.connect( this, { itemUpdate: 'onModelItemUpdate' } );
		// Add the filterInput as trigger
		this.filterInput.$input
			.on( 'focus', this.focus.bind( this ) );

		// Initialize
		this.$content.append( this.emptyFilterMessage.$element );
		this.$handle
			.append(
				// The content and button should appear side by side regardless of how
				// wide the button is; the button also changes its width depending
				// on language and its state, so the safest way to present both side
				// by side is with a table layout
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content' )
									.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell' )
									.append( this.$content ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-cell' )
									.append( this.resetButton.$element )
							)
					)
			);

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
		if ( item.isSelected() ) {
			this.addItemByName( item.getName() );
		} else {
			this.removeItemByName( item.getName() );
		}

		// Re-evaluate reset state
		this.reevaluateResetRestoreState();
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

		this.resetButton.toggle( !hideResetButton );
		this.emptyFilterMessage.toggle( currFiltersAreEmpty );
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
		var pos;

		// Override this method; we don't want to focus on the popup, and we
		// don't want to bind the size to the handle.
		if ( !this.isDisabled() ) {
			this.popup.toggle( true );

			// Fix for RTL
			// TODO: This should be fixed upstream; now that PopupWidget is also
			// a FloatableElement, the positionings of both elements are interfering
			// with one another, and neither one of them corrects itself for RTL
			if ( OO.ui.Element.static.getDir( this.$element ) === 'rtl' ) {
				pos = this.popup.$element.offset();
				this.popup.$element.css(
					'left',
					pos.left + this.filterInput.$element.width() - this.popup.$element.width()
				);
			}

			// The 'margin-left' correction that arrives from PopupWidget
			// is wrong here. It makes the popup shift to the left, aligning
			// the scrollbar outside the bounds of the input.
			// TODO: This should be fixed upstream in OOUI
			this.popup.$element.css( 'margin-left', 'auto' );

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
}( mediaWiki, jQuery ) );
