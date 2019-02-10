( function () {
	var FilterItemHighlightButton = require( './FilterItemHighlightButton.js' ),
		CheckboxInputWidget = require( './CheckboxInputWidget.js' ),
		ItemMenuOptionWidget;

	/**
	 * A widget representing a base toggle item
	 *
	 * @class mw.rcfilters.ui.ItemMenuOptionWidget
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
	 * @param {mw.rcfilters.dm.ItemModel} invertModel
	 * @param {mw.rcfilters.dm.ItemModel} itemModel Item model
	 * @param {mw.rcfilters.ui.HighlightPopupWidget} highlightPopup Shared highlight color picker
	 * @param {Object} config Configuration object
	 */
	ItemMenuOptionWidget = function MwRcfiltersUiItemMenuOptionWidget(
		controller, filtersViewModel, invertModel, itemModel, highlightPopup, config
	) {
		var layout,
			classes = [],
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-label' );

		config = config || {};

		this.controller = controller;
		this.filtersViewModel = filtersViewModel;
		this.invertModel = invertModel;
		this.itemModel = itemModel;

		// Parent
		ItemMenuOptionWidget.parent.call( this, $.extend( {
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
			this.itemModel.getGroupModel().getView() === 'namespaces' &&
			this.itemModel.isSelected() &&
			this.invertModel.isSelected()
		);

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		// Events
		this.filtersViewModel.connect( this, { highlightChange: 'updateUiBasedOnState' } );
		this.invertModel.connect( this, { update: 'updateUiBasedOnState' } );
		this.itemModel.connect( this, { update: 'updateUiBasedOnState' } );
		// HACK: Prevent defaults on 'click' for the label so it
		// doesn't steal the focus away from the input. This means
		// we can continue arrow-movement after we click the label
		// and is consistent with the checkbox *itself* also preventing
		// defaults on 'click' as well.
		layout.$label.on( 'click', false );

		this.$element
			.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget' )
			.addClass( 'mw-rcfilters-ui-itemMenuOptionWidget-view-' + this.itemModel.getGroupModel().getView() )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-itemCheckbox' )
									.append( layout.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-excludeLabel' )
									.append( this.excludeLabel.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-itemMenuOptionWidget-highlightButton' )
									.append( this.highlightButton.$element )
							)
					)
			);

		if ( this.itemModel.getIdentifiers() ) {
			this.itemModel.getIdentifiers().forEach( function ( ident ) {
				classes.push( 'mw-rcfilters-ui-itemMenuOptionWidget-identifier-' + ident );
			} );

			this.$element.addClass( classes );
		}

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
			this.itemModel.getGroupModel().getView() === 'namespaces' &&
			this.itemModel.isSelected() &&
			this.invertModel.isSelected()
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

}() );
