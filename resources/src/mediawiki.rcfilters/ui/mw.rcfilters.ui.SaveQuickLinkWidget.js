( function ( mw ) {
	/**
	 * Save quick link widget
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.SavedQueriesModel} model View model
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget = function MwRcfiltersUiSaveQuickLinkWidget( controller, model, config ) {
		var layout,
			$popupContent = $( '<div>' );

		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.SaveQuickLinkWidget.parent.call( this, $.extend( {
			framed: false,
			icon: 'clip',
			$overlay: this.$overlay,
			popup: {
				classes: [ 'mw-rcfilters-ui-saveQuickLinkWidget-popup' ],
				padded: true,
				head: true,
				label: mw.msg( 'rcfilters-savedqueries-add-new-title' ),
				$content: $popupContent
			}
		}, config ) );
		// // HACK: Add an icon to the popup head label
		this.popup.$head.prepend( ( new OO.ui.IconWidget( { icon: 'clip' } ) ).$element );

		this.input = new OO.ui.TextInputWidget( {
			validate: 'non-empty'
		} );
		layout = new OO.ui.FieldLayout( this.input, {
			label: mw.msg( 'rcfilters-savedqueries-new-name-label' ),
			align: 'top'
		} );

		this.applyButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-savedqueries-apply-label' ),
			classes: [ 'mw-rcfilters-ui-saveQuickLinkWidget-popup-buttons-apply' ],
			flags: [ 'primary', 'progressive' ]
		} );
		this.cancelButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-savedqueries-cancel-label' ),
			classes: [ 'mw-rcfilters-ui-saveQuickLinkWidget-popup-buttons-cancel' ]
		} );

		$popupContent
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-saveQuickLinkWidget-popup-layout' )
					.append( layout.$element ),
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-saveQuickLinkWidget-popup-buttons' )
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
		this.input.$input.on( {
			keypress: this.onInputKeypress.bind( this ),
			keyup: this.onInputKeyup.bind( this )
		} );
		this.cancelButton.connect( this, { click: 'onCancelButtonClick' } );
		this.applyButton.connect( this, { click: 'onApplyButtonClick' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-saveQuickLinkWidget' );
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.SaveQuickLinkWidget, OO.ui.PopupButtonWidget );

	/**
	 * Respond to input keypress event
	 *
	 * @param {jQuery.Event} e Event data
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onInputKeypress = function ( e ) {
		if ( e.which === OO.ui.Keys.ENTER ) {
			this.apply();
		}
	};

	/**
	 * Respond to input keyup event, this is the way to intercept 'escape' key
	 *
	 * @param {jQuery.Event} e Event data
	 * @returns {boolean} false
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onInputKeyup = function ( e ) {
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
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onPopupToggle = function ( isVisible ) {
		this.setIcon( isVisible ? 'unClip' : 'clip' );
	};

	/**
	 * Respond to popup ready event
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onPopupReady = function () {
		this.input.focus();
	};

	/**
	 * Respond to cancel button click event
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onCancelButtonClick = function () {
		this.popup.toggle( false );
	};

	/**
	 * Respond to apply button click event
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onApplyButtonClick = function () {
		this.apply();
	};

	/**
	 * Apply and add the new quick link
	 */
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.apply = function () {
		var widget = this,
			label = this.input.getValue();

		this.input.getValidity()
			.then( this.controller.saveCurrentQuery.bind( this.controller, label ) )
			.then( this.input.setValue.bind( this.input, '' ) )
			.always( function () {
				widget.popup.toggle( false );
			} );
	};
}( mediaWiki ) );
