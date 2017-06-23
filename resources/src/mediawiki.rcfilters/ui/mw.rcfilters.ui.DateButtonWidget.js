( function ( mw ) {
	/**
	 * Widget defining the button controlling the popup for the date range for the results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.DateButtonWidget = function MwRcfiltersUiDateButtonWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.DateButtonWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.$overlay = config.$overlay || this.$element;

		this.button = null;

		this.model.connect( this, {
			initialize: 'onModelInitialize'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-dateButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.DateButtonWidget, OO.ui.Widget );

	/**
	 * Respond to model initialize event
	 */
	mw.rcfilters.ui.DateButtonWidget.prototype.onModelInitialize = function () {
		var datePopupWidget,
			limitGroupModel = this.model.getGroup( 'days' );

		// We need the model to be ready before we populate the button
		// and the widget, because we require the filter items for the
		// limit and their events. This addition is only done after the
		// model is initialized.
		if ( limitGroupModel ) {
			datePopupWidget = new mw.rcfilters.ui.DatePopupWidget(
				this.controller,
				limitGroupModel
			);

			this.button = new OO.ui.PopupButtonWidget( {
				indicator: 'down',
				icon: 'calendar',
				label: mw.msg( 'rcfilters-days-show-days', 7 ),
				$overlay: this.$overlay,
				popup: {
					width: 300,
					padded: true,
					anchor: false,
					align: 'backwards',
					$autoCloseIgnore: this.$overlay,
					$content: datePopupWidget.$element
				}
			} );

			datePopupWidget.connect( this, { choose: 'onDateChoose' } );

			this.$element.append( this.button.$element );
		}
	};

	/**
	 * Respond to limit choose event
	 *
	 * @param {string} filterName Filter name
	 */
	mw.rcfilters.ui.DateButtonWidget.prototype.onDateChoose = function ( filterName ) {
		var item = this.model.getItemByName( filterName ),
			label = item && item.getLabel();

		// Update the label
		if ( label ) {
			this.button.setLabel( mw.msg( 'rcfilters-days-show-days', label ) );
		}
	};
}( mediaWiki ) );
