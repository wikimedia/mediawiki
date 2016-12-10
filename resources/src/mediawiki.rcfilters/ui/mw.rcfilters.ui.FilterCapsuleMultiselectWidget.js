( function ( mw, $ ) {
	/**
	 * Filter-specific CapsuleMultiselectWidget
	 *
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel} RCFilters view model
	 * @param {mw.rcfilters.Controller} RCFilters controller
	 * @param {OO.ui.InputWidget} filterInput A filter input that focuses the capsule widget
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget = function MwRcfiltersUiFilterCapsuleMultiselectWidget( controller, model, filterInput, config ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: filterInput.$element
		}, config ) );

		this.controller = controller;
		this.model = model;
		this.filterInput = filterInput;

		this.$content.prepend(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content-title' )
				.text( mw.msg( 'rcfilters-activefilters' ) )
		);

		this.resetIcon = new OO.ui.ButtonWidget( {
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
		this.resetIcon.connect( this, { click: 'onResetButtonClick' } );
		this.model.connect( this, { itemUpdate: 'onModelItemUpdate' } );
		// Add the filterInput as trigger
		this.filterInput.$input
			.on( 'focus', this.onFocusForPopup.bind( this ) );

		// Initialize
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
									.append( this.resetIcon.$element )
							)
					),
				this.emptyFilterMessage.$element
			);

		this.$element
			.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget' );

		this.toggleResetRestoreState();
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

	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onModelItemUpdate = function () {
		// Re-evaluate reset state
		this.toggleResetRestoreState();
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
	 * Toggle the restore state for the widget between setting to defaults and clearing all filters
	 *
	 * @param {boolean} isReset This is a reset filters operation
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.toggleResetRestoreState = function ( isReset ) {
		var defaultsAreEmpty = this.model.areDefaultFiltersEmpty(),
			hideResetButtons = this.model.areCurrentFiltersEmpty() && defaultsAreEmpty;
		isReset = isReset === undefined ? this.model.areCurrentFiltersEmpty() : isReset;

		this.resetIcon.setIcon(
			isReset ? 'history' : 'trash'
		);

		this.resetIcon.setLabel(
			isReset ? mw.msg( 'rcfilters-restore-default-filters' ) : ''
		);

		this.resetIcon.toggle( !hideResetButtons );
		this.emptyFilterMessage.toggle( isReset );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onFocusForPopup = function () {
		// Override this method; we don't want to focus on the popup, and we
		// don't want to bind the size to the handle.
		if ( !this.isDisabled() ) {
			this.popup.toggle( true );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.removeItems = function ( items ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.prototype.removeItems.call( this, items );

		this.emit( 'remove', items.map( function ( item ) { return item.getData(); } ) );
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
