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
			icon: 'bookmark',
			$overlay: this.$overlay,
			popup: {
				classes: [ 'mw-rcfilters-ui-saveQuickLinkWidget-popup' ],
				padded: true,
				head: true,
				label: 'Add as quick link',
				$content: $popupContent
			}
		}, config ) );
		// // HACK: Add an icon to the popup head label
		this.popup.$head.prepend( ( new OO.ui.IconWidget( { icon: 'bookmark' } ) ).$element );

		this.input = new OO.ui.TextInputWidget( {
			validate: 'non-empty'
		} );
		layout = new OO.ui.FieldLayout( this.input, {
			label: 'Name',
			align: 'top'
		} );

		this.applyButton = new OO.ui.ButtonWidget( {
			label: 'Create quick link',
			classes: [ 'mw-rcfilters-ui-saveQuickLinkWidget-popup-buttons-apply' ],
			flags: [ 'primary', 'progressive' ]
		} );
		this.cancelButton = new OO.ui.ButtonWidget( {
			label: 'Cancel',
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
		this.popup.connect( this, { ready: 'onPopupReady' } );
		this.cancelButton.connect( this, { click: 'onCancelButtonClick' } );
		this.applyButton.connect( this, { click: 'onApplyButtonClick' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-saveQuickLinkWidget' );
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.SaveQuickLinkWidget, OO.ui.PopupButtonWidget );

	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onPopupReady = function () {
		this.input.focus();
	};

	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onCancelButtonClick = function () {
		this.popup.toggle( false );
	};
	mw.rcfilters.ui.SaveQuickLinkWidget.prototype.onApplyButtonClick = function () {
		var widget = this,
			label = this.input.getValue();

		this.input.getValidity()
			.then(
				function () {
					widget.controller.saveCurrentQuery( label );
					widget.input.setValue( '' );
					widget.popup.toggle( false );
				}
			);
	};
}( mediaWiki ) );
