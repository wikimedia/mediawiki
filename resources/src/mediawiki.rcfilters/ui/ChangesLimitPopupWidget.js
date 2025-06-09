const ValuePickerWidget = require( './ValuePickerWidget.js' );

/**
 * Widget defining the popup to choose number of results.
 *
 * @class mw.rcfilters.ui.ChangesLimitPopupWidget
 * @ignore
 * @extends OO.ui.Widget
 *
 * @param {mw.rcfilters.dm.FilterGroup} limitModel Group model for 'limit'
 * @param {mw.rcfilters.dm.FilterItem} groupByPageItemModel Group model for 'limit'
 * @param {Object} [config] Configuration object
 */
const ChangesLimitPopupWidget = function MwRcfiltersUiChangesLimitPopupWidget( limitModel, groupByPageItemModel, config ) {
	config = config || {};

	// Parent
	ChangesLimitPopupWidget.super.call( this, config );

	this.limitModel = limitModel;
	this.groupByPageItemModel = groupByPageItemModel;

	this.valuePicker = new ValuePickerWidget(
		this.limitModel,
		{
			label: mw.msg( 'rcfilters-limit-title' )
		}
	);

	this.groupByPageCheckbox = new OO.ui.CheckboxInputWidget( {
		selected: this.groupByPageItemModel.isSelected()
	} );

	// Events
	this.valuePicker.connect( this, { choose: [ 'emit', 'limit' ] } );
	this.groupByPageCheckbox.connect( this, { change: [ 'emit', 'groupByPage' ] } );
	this.groupByPageItemModel.connect( this, { update: 'onGroupByPageModelUpdate' } );
	// HACK: Directly connect to the checkbox click event so that we can save the preference
	// when the user explicitly interacts with the checkbox rather than when the checkbox changes
	// state. This is to make sure that we only save preference when the user explicitly interacts
	// with the UI.
	this.groupByPageCheckbox.$element.on( 'click', this.onGroupByPageUserClick.bind( this ) );

	// Initialize
	this.$element
		.addClass( 'mw-rcfilters-ui-changesLimitPopupWidget' )
		.append(
			this.valuePicker.$element,
			OO.ui.isMobile() ? undefined :
				new OO.ui.FieldLayout(
					this.groupByPageCheckbox,
					{
						align: 'inline',
						label: mw.msg( 'rcfilters-group-results-by-page' )
					}
				).$element
		);

	this.valuePicker.selectWidget.$element.attr( 'aria-label', mw.msg( 'rcfilters-limit-title' ) );
};

/* Initialization */

OO.inheritClass( ChangesLimitPopupWidget, OO.ui.Widget );

/* Events */

/**
 * A limit item was chosen.
 *
 * @event limit
 * @param {string} name Item name
 * @ignore
 */

/**
 * Results are grouped by page
 *
 * @event groupByPage
 * @param {boolean} isGrouped The results are grouped by page
 * @ignore
 */

/**
 * Respond to group by page model update
 */
ChangesLimitPopupWidget.prototype.onGroupByPageModelUpdate = function () {
	this.groupByPageCheckbox.setSelected( this.groupByPageItemModel.isSelected() );
};

/**
 * Respond to user explicitly clicking the Group by page checkbox
 */
ChangesLimitPopupWidget.prototype.onGroupByPageUserClick = function () {
	this.emit( 'groupByPageUserClick', this.groupByPageCheckbox.isSelected() );
	mw.hook( 'rcfilters.groupbycheckboxwidget.click' ).fire( this );
};

module.exports = ChangesLimitPopupWidget;
