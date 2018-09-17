( function () {
	/**
	 * Widget defining the button controlling the popup for the number of results
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
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget = function MwRcfiltersUiChangesLimitWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;

		this.$overlay = config.$overlay || this.$element;

		this.button = null;
		this.limitGroupModel = null;
		this.groupByPageItemModel = null;
		this.daysGroupModel = null;

		this.model.connect( this, {
			initialize: 'onModelInitialize'
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-changesLimitAndDateButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesLimitAndDateButtonWidget, OO.ui.Widget );

	/**
	 * Respond to model initialize event
	 */
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.prototype.onModelInitialize = function () {
		var changesLimitPopupWidget, selectedItem, currentValue, datePopupWidget,
			displayGroupModel = this.model.getGroup( 'display' );

		this.limitGroupModel = this.model.getGroup( 'limit' );
		this.groupByPageItemModel = displayGroupModel.getItemByParamName( 'enhanced' );
		this.daysGroupModel = this.model.getGroup( 'days' );

		// HACK: We need the model to be ready before we populate the button
		// and the widget, because we require the filter items for the
		// limit and their events. This addition is only done after the
		// model is initialized.
		// Note: This will be fixed soon!
		if ( this.limitGroupModel && this.daysGroupModel ) {
			changesLimitPopupWidget = new mw.rcfilters.ui.ChangesLimitPopupWidget(
				this.limitGroupModel,
				this.groupByPageItemModel
			);

			datePopupWidget = new mw.rcfilters.ui.DatePopupWidget(
				this.daysGroupModel,
				{
					label: mw.msg( 'rcfilters-date-popup-title' )
				}
			);

			selectedItem = this.limitGroupModel.findSelectedItems()[ 0 ];
			currentValue = ( selectedItem && selectedItem.getLabel() ) ||
				mw.language.convertNumber( this.limitGroupModel.getDefaultParamValue() );

			this.button = new OO.ui.PopupButtonWidget( {
				icon: 'settings',
				indicator: 'down',
				label: mw.msg( 'rcfilters-limit-and-date-label', currentValue ),
				$overlay: this.$overlay,
				popup: {
					width: 300,
					padded: false,
					anchor: false,
					align: 'backwards',
					$autoCloseIgnore: this.$overlay,
					$content: $( '<div>' ).append(
						// TODO: Merge ChangesLimitPopupWidget with DatePopupWidget into one common widget
						changesLimitPopupWidget.$element,
						datePopupWidget.$element
					)
				}
			} );
			this.updateButtonLabel();

			// Events
			this.limitGroupModel.connect( this, { update: 'updateButtonLabel' } );
			this.daysGroupModel.connect( this, { update: 'updateButtonLabel' } );
			changesLimitPopupWidget.connect( this, {
				limit: 'onPopupLimit',
				groupByPage: 'onPopupGroupByPage'
			} );
			datePopupWidget.connect( this, { days: 'onPopupDays' } );

			this.$element.append( this.button.$element );
		}
	};

	/**
	 * Respond to popup limit change event
	 *
	 * @param {string} filterName Chosen filter name
	 */
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.prototype.onPopupLimit = function ( filterName ) {
		var item = this.limitGroupModel.getItemByName( filterName );

		this.controller.toggleFilterSelect( filterName, true );
		this.controller.updateLimitDefault( item.getParamName() );
		this.button.popup.toggle( false );
	};

	/**
	 * Respond to popup limit change event
	 *
	 * @param {boolean} isGrouped The result set is grouped by page
	 */
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.prototype.onPopupGroupByPage = function ( isGrouped ) {
		this.controller.toggleFilterSelect( this.groupByPageItemModel.getName(), isGrouped );
		this.controller.updateGroupByPageDefault( isGrouped );
		this.button.popup.toggle( false );
	};

	/**
	 * Respond to popup limit change event
	 *
	 * @param {string} filterName Chosen filter name
	 */
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.prototype.onPopupDays = function ( filterName ) {
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
	mw.rcfilters.ui.ChangesLimitAndDateButtonWidget.prototype.updateButtonLabel = function () {
		var message,
			limit = this.limitGroupModel.findSelectedItems()[ 0 ],
			label = limit && limit.getLabel(),
			days = this.daysGroupModel.findSelectedItems()[ 0 ],
			daysParamName = Number( days.getParamName() ) < 1 ?
				'rcfilters-days-show-hours' :
				'rcfilters-days-show-days';

		// Update the label
		if ( label && days ) {
			message = mw.msg( 'rcfilters-limit-and-date-label', label,
				mw.msg( daysParamName, days.getLabel() )
			);
			this.button.setLabel( message );
		}
	};

}() );
