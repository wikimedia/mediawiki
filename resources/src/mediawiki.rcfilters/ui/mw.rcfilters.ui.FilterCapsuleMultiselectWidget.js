( function ( mw, $ ) {
	/**
	 * Filter-specific CapsuleMultiselectWidget
	 *
	 * @class
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {OO.ui.InputWidget} filterInput A filter input that focuses the capsule widget
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget = function MwRcfiltersUiFilterCapsuleMultiselectWidget( filterInput, config ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: filterInput.$element
		}, config ) );

		this.filterInput = filterInput;

		this.$content.prepend(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content-title' )
				.text( mw.msg( 'rcfilters-activefilters' ) )
		);

		// Events
		// Add the filterInput as trigger
		this.filterInput.$input
			.on( 'focus', this.focus.bind( this ) );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget' );
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
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.createItemWidget = function ( data, label, description ) {
		if ( label === '' ) {
			return null;
		}
		return new mw.rcfilters.ui.CapsuleItemWidget( { data: data, label: label, description: description } );
	};

	/**
	 * Slightly adjust addItemsFromData to allow for item description to be sent
	 * so we can have a popup
	 *
	 * @chainable
	 * @param {Mixed[]} datas
	 * @return {OO.ui.CapsuleMultiselectWidget}
	 */
	OO.ui.CapsuleMultiselectWidget.prototype.addItemsFromData = function ( datas ) {
		var widget = this,
			menu = this.menu,
			items = [];

		$.each( datas, function ( i, data ) {
			var item;

			if ( !widget.getItemFromData( data ) ) {
				item = menu.getItemFromData( data );
				if ( item ) {
					// Allow for description for the popup
					item = widget.createItemWidget( data, item.label, item.getDescription() );
				} else if ( widget.allowArbitrary ) {
					item = widget.createItemWidget( data, String( data ) );
				}
				if ( item ) {
					items.push( item );
				}
			}
		} );

		if ( items.length ) {
			this.addItems( items );
		}

		return this;
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
