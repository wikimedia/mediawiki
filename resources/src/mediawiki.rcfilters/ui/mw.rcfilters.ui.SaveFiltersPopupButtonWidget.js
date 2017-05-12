( function ( mw ) {
	/**
	 * Save filters widget. This widget is displayed in the tag area
	 * and allows the user to save the current state of the system
	 * as a new saved filter query they can later load or set as
	 * default.
	 *
	 * @extends OO.ui.PopupButtonWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.SavedQueriesModel} model View model
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget = function MwRcfiltersUiSaveFiltersPopupButtonWidget( controller, model, config ) {
		var layout,
			$popupContent = $( '<div>' );

		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.SaveFiltersPopupButtonWidget.parent.call( this, $.extend( {
			framed: false,
			icon: 'clip',
			$overlay: this.$overlay,
			title: mw.msg( 'rcfilters-savedqueries-add-new-title' ),
			popup: {
				classes: [ 'mw-rcfilters-ui-saveFiltersPopupButtonWidget-popup' ],
				padded: true,
				head: true,
				label: mw.msg( 'rcfilters-savedqueries-add-new-title' ),
				$content: $popupContent
			}
		}, config ) );
		// // HACK: Add an icon to the popup head label
		this.popup.$head.prepend( ( new OO.ui.IconWidget( { icon: 'clip' } ) ).$element );

		this.input = new OO.ui.TextInputWidget( {
			validate: /\S/
		} );
		layout = new OO.ui.FieldLayout( this.input, {
			label: mw.msg( 'rcfilters-savedqueries-new-name-label' ),
			align: 'top'
		} );

		this.applyButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-savedqueries-apply-label' ),
			classes: [ 'mw-rcfilters-ui-saveFiltersPopupButtonWidget-popup-buttons-apply' ],
			flags: [ 'primary', 'progressive' ]
		} );
		this.cancelButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-savedqueries-cancel-label' ),
			classes: [ 'mw-rcfilters-ui-saveFiltersPopupButtonWidget-popup-buttons-cancel' ]
		} );

		$popupContent
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-saveFiltersPopupButtonWidget-popup-layout' )
					.append( layout.$element ),
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-saveFiltersPopupButtonWidget-popup-buttons' )
					.append(
						this.cancelButton.$element,
						this.applyButton.$element
					)
			);

		// Events
		this.popup.connect( this, {
			ready: 'onPopupReady',
			toggle: 'onPopupToggle'
		} );
		this.input.connect( this, { enter: 'onInputEnter' } );
		this.input.$input.on( {
			keyup: this.onInputKeyup.bind( this )
		} );
		this.cancelButton.connect( this, { click: 'onCancelButtonClick' } );
		this.applyButton.connect( this, { click: 'onApplyButtonClick' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-saveFiltersPopupButtonWidget' );
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.SaveFiltersPopupButtonWidget, OO.ui.PopupButtonWidget );

	/**
	 * Respond to input enter event
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onInputEnter = function () {
		this.apply();
	};

	/**
	 * Respond to input keyup event, this is the way to intercept 'escape' key
	 *
	 * @param {jQuery.Event} e Event data
	 * @returns {boolean} false
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onInputKeyup = function ( e ) {
		if ( e.which === OO.ui.Keys.ESCAPE ) {
			this.popup.toggle( false );
			return false;
		}
	};

	/**
	 * Respond to popup toggle event
	 *
	 * @param {boolean} isVisible Popup is visible
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onPopupToggle = function ( isVisible ) {
		this.setIcon( isVisible ? 'unClip' : 'clip' );
	};

	/**
	 * Respond to popup ready event
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onPopupReady = function () {
		this.input.focus();
	};

	/**
	 * Respond to cancel button click event
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onCancelButtonClick = function () {
		this.popup.toggle( false );
	};

	/**
	 * Respond to apply button click event
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.onApplyButtonClick = function () {
		this.apply();
	};

	/**
	 * Apply and add the new quick link
	 */
	mw.rcfilters.ui.SaveFiltersPopupButtonWidget.prototype.apply = function () {
		var widget = this,
			label = this.input.getValue();

		this.input.getValidity()
			.done( function () {
				widget.controller.saveCurrentQuery( label );
				widget.input.setValue( this.input, '' );
				widget.emit( 'saveCurrent' );
				widget.popup.toggle( false );
			} );
	};
}( mediaWiki ) );
