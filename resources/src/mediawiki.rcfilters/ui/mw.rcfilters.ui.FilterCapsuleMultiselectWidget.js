( function ( mw, $ ) {
	/**
	 * Filter-specific CapsuleMultiselectWidget
	 *
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget = function MwRcfiltersUiFilterCapsuleMultiselectWidget( filterInput, config ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.call( this, config );

		this.filterInput = filterInput;

		this.$content.prepend(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget-content-title' )
				.append( mw.msg( 'rcfilters-activefilters' ) )
		);

		// Events
		// Add the filterInput as trigger
		this.filterInput.$input
			.on( {
				focus: this.onFocusForPopup.bind( this ),
				// blur: this.onPopupFocusOut.bind( this ),
				// 'propertychange change click mouseup keydown keyup input cut paste select focus':
				// 	OO.ui.debounce( this.updateInputSize.bind( this ) ),
				// keydown: this.onKeyDown.bind( this ),
				// keypress: this.onKeyPress.bind( this )
			} );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterCapsuleMultiselectWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterCapsuleMultiselectWidget, OO.ui.CapsuleMultiselectWidget );

	/**
	 * Override this method; we don't want to focus on the popup, and we
	 * don't want to bind the size to the handle.
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onFocusForPopup = function () {
		if ( !this.isDisabled() ) {
			this.popup.toggle( true );
		}
	};

	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.addItems = function ( items ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.prototype.addItems.call( this, items );

		this.emit( 'add', items.map( function ( item ) { return item.getData() } ) );
	};

	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.removeItems = function ( items ) {
		// Parent
		mw.rcfilters.ui.FilterCapsuleMultiselectWidget.parent.prototype.removeItems.call( this, items );

		this.emit( 'remove', items.map( function ( item ) { return item.getData() } ) );
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onKeyDown = function () {};

	/**
	 * Handles popup focus out events.
	 *
	 * @private
	 * @param {jQuery.Event} e Focus out event
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.onPopupFocusOut = function () {};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterCapsuleMultiselectWidget.prototype.clearInput = function () {
		if ( this.filterInput ) {
			this.filterInput.setValue( '' );
			// this.updateInputSize();
		}
		this.menu.toggle( false );
		this.menu.selectItem();
		this.menu.highlightItem();
	};
} )( mediaWiki, jQuery );
