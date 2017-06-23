( function ( mw ) {
	/**
	 * Widget defining the button controlling the popup for the number of results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.ChangesLimitButtonWidget = function MwRcfiltersUiChangesLimitWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitButtonWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.button = null;

		this.model.connect( this, {
			initialize: 'onModelInitialize'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-changesLimitButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesLimitButtonWidget, OO.ui.Widget );

	/**
	 * Respond to model initialize event
	 */
	mw.rcfilters.ui.ChangesLimitButtonWidget.prototype.onModelInitialize = function () {
		var limitGroupModel = this.model.getGroup( 'limit' );
debugger;
		// We need the model to be ready before we populate the button
		// and the widget, because we require the filter items for the
		// limit and their events. This addition is only done after the
		// model is initialized.
		if ( limitGroupModel ) {
			this.button = new OO.ui.PopupButtonWidget( {
				indicator: 'down',
				label: mw.msg( 'rcfilters-limit-shownum', 50 ),
				$overlay: this.$overlay,
				popup: {
					width: 300,
					anchor: false,
					align: 'backwards',
					$autoCloseIgnore: this.$overlay,
					$content: new mw.rcfilters.ui.ChangesLimitPopupWidget( this.controller, limitGroupModel ).$element
				}
			} );

			this.$element.append( this.button.$element );
		}
	};

}( mediaWiki ) );
