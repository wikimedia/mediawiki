var FilterItemHighlightButton = require( './FilterItemHighlightButton.js' ),
	CheckboxInputWidget = require( './CheckboxInputWidget.js' ),
	ItemMenuOptionWidget;

/**
 * A widget representing a base toggle item.
 *
 * @class mw.rcfilters.ui.ItemMenuOptionWidget
 * @ignore
 * @extends OO.ui.MenuOptionWidget
 *
 * @param {mw.rcfilters.Controller} controller RCFilters controller
 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
 * @param {mw.rcfilters.dm.ItemModel|null} invertModel
 * @param {mw.rcfilters.dm.ItemModel} itemModel Item model
 * @param {mw.rcfilters.ui.HighlightPopupWidget} highlightPopup Shared highlight color picker
 * @param {Object} config Configuration object
 */
ItemMenuOptionWidget = function MwRcfiltersUiItemMenuOptionWidget(
	controller, filtersViewModel, invertModel, itemModel, highlightPopup, config
) {
	var layout,
		$widgetRow,
		classes,
		$label = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label' );

	config = config || {};

	this.controller = controller;
	this.filtersViewModel = filtersViewModel;
	this.invertModel = invertModel;
	this.itemModel = itemModel;

	// Parent
	ItemMenuOptionWidget.super.call( this, Object.assign( {
		// Override the 'check' icon that OOUI defines
		icon: '',
		data: this.itemModel.getName(),
		label: this.itemModel.getLabel()
	}, config ) );

	this.checkboxWidget = new CheckboxInputWidget( {
		value: this.itemModel.getName(),
		selected: this.itemModel.isSelected()
	} );

	$label.append(
		$( '<div>' )
			.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label-title' )
			.append( $( '<bdi>' ).append( this.$label ) )
	);
	if ( this.itemModel.getDescription() ) {
		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label-desc' )
				.append( $( '<bdi>' ).text( this.itemModel.getDescription() ) )
		);
	}

	this.highlightButton = new FilterItemHighlightButton(
		this.controller,
		this.itemModel,
		highlightPopup,
		{
			$overlay: config.$overlay || this.$element,
			title: mw.msg( 'rcfilters-highlightmenu-help' )
		}
	);
	this.highlightButton.toggle( this.filtersViewModel.isHighlightEnabled() );

	this.excludeLabel = new OO.ui.LabelWidget( {
		label: mw.msg( 'rcfilters-filter-excluded' )
	} );
	this.excludeLabel.toggle(
		this.invertModel &&
		this.invertModel.isSelected() &&
		this.itemModel.isSelected()
	);

	layout = new OO.ui.FieldLayout( this.checkboxWidget, {
		label: $label,
		align: 'inline'
	} );

	// Events
	this.filtersViewModel.connect( this, { highlightChange: 'updateUiBasedOnState' } );
	if ( this.invertModel ) {
		this.invertModel.connect( this, { update: 'updateUiBasedOnState' } );
	}
	this.itemModel.connect( this, { update: 'updateUiBasedOnState' } );
	// HACK: Prevent defaults on 'click' for the label so it
	// doesn't steal the focus away from the input. This means
	// we can continue arrow-movement after we click the label
	// and is consistent with the checkbox *itself* also preventing
	// defaults on 'click' as well.
	layout.$label.on( 'click', false );

	$widgetRow = $( '<div>' )
		.addClass( 'mw-rcfilters-ui-table' )
		.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-row' )
				.append(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-itemCheckbox' )
						.append( layout.$element )
				)
		);

	if ( !OO.ui.isMobile() ) {
		$widgetRow.find( '.mw-rcfilters-ui-row' ).append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-excludeLabel' )
				.append( this.excludeLabel.$element ),
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-highlightButton' )
				.append( this.highlightButton.$element )
		);
	}

	classes = this.itemModel.getIdentifiers().map( ( ident ) => 'mw-rcfilters-ui-itemMenuOptionWidget-identifier-' + ident ).concat(
		'mw-rcfilters-ui-itemMenuOptionWidget',
		'mw-rcfilters-ui-itemMenuOptionWidget-view-' + this.itemModel.getGroupModel().getView()
	);

	// The following classes are used here:
	// * mw-rcfilters-ui-itemMenuOptionWidget-identifier-subject
	// * mw-rcfilters-ui-itemMenuOptionWidget-identifier-talk
	// * mw-rcfilters-ui-itemMenuOptionWidget
	// * mw-rcfilters-ui-itemMenuOptionWidget-view-default
	// * mw-rcfilters-ui-itemMenuOptionWidget-view-namespaces
	// * mw-rcfilters-ui-itemMenuOptionWidget-view-tags
	this.$element
		.addClass( classes )
		.append( $widgetRow );

	this.updateUiBasedOnState();
};

/* Initialization */

OO.inheritClass( ItemMenuOptionWidget, OO.ui.MenuOptionWidget );

/* Static properties */

// We do our own scrolling to top
ItemMenuOptionWidget.static.scrollIntoViewOnSelect = false;

/* Methods */

/**
 * Respond to item model update event
 */
ItemMenuOptionWidget.prototype.updateUiBasedOnState = function () {
	this.checkboxWidget.setSelected( this.itemModel.isSelected() );

	this.highlightButton.toggle( this.filtersViewModel.isHighlightEnabled() );
	this.excludeLabel.toggle(
		this.invertModel &&
		this.invertModel.isSelected() &&
		this.itemModel.isSelected()
	);
	this.toggle( this.itemModel.isVisible() );
};

/**
 * Get the name of this filter
 *
 * @return {string} Filter name
 */
ItemMenuOptionWidget.prototype.getName = function () {
	return this.itemModel.getName();
};

ItemMenuOptionWidget.prototype.getModel = function () {
	return this.itemModel;
};

module.exports = ItemMenuOptionWidget;
