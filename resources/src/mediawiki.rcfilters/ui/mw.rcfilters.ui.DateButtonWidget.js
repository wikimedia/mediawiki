( function ( mw ) {
	/**
	 * Widget defining the button controlling the popup for the date range for the results
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.DateButtonWidget = function MwRcfiltersUiDateButtonWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitButtonWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.$overlay = config.$overlay || this.$element;

		this.button = null;
		this.daysGroupModel = null;

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
		var datePopupWidget;

		this.daysGroupModel = this.model.getGroup( 'days' );

		// HACK: We need the model to be ready before we populate the button
		// and the widget, because we require the filter items for the
		// limit and their events. This addition is only done after the
		// model is initialized.
		// Note: This will be fixed soon!
		if ( this.daysGroupModel ) {
			datePopupWidget = new mw.rcfilters.ui.DatePopupWidget(
				this.daysGroupModel
			);

			this.button = new OO.ui.PopupButtonWidget( {
				indicator: 'down',
				icon: 'calendar',
				$overlay: this.$overlay,
				popup: {
					width: 300,
					padded: true,
					anchor: false,
					align: 'forwards',
					$autoCloseIgnore: this.$overlay,
					$content: datePopupWidget.$element
				}
			} );
			this.updateButtonLabel();

			// Events
			this.daysGroupModel.connect( this, { update: 'onDaysGroupModelUpdate' } );
			datePopupWidget.connect( this, { days: 'onPopupDays' } );

			this.$element.append( this.button.$element );
		}
	};

	/**
	 * Respond to popup limit change event
	 *
	 * @param {string} filterName Chosen filter name
	 */
	mw.rcfilters.ui.DateButtonWidget.prototype.onPopupDays = function ( filterName ) {
		var item = this.daysGroupModel.getItemByName( filterName );

		this.controller.toggleFilterSelect( filterName, true );
		this.controller.updateDaysDefault( item.getParamName() );
		this.button.popup.toggle( false );
	};

	/**
	 * Respond to limit choose event
	 *
	 * @param {string} filterName Filter name
	 */
	mw.rcfilters.ui.DateButtonWidget.prototype.onDaysGroupModelUpdate = function () {
		this.updateButtonLabel();
	};

	/**
	 * Update the button label
	 */
	mw.rcfilters.ui.DateButtonWidget.prototype.updateButtonLabel = function () {
		var item = this.daysGroupModel.getSelectedItems()[ 0 ];

		// Update the label
		if ( item ) {
			this.button.setLabel(
				mw.msg(
					Number( item.getParamName() ) < 1 ?
						'rcfilters-days-show-hours' : 'rcfilters-days-show-days',
					item.getLabel()
				)
			);
		}
	};
}( mediaWiki ) );
