( function ( mw, $ ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterItemWidget = function MwRcfiltersUiFilterItemWidget( controller, model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label' );

		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterItemWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.selected = false;

		this.checkboxWidget = new mw.rcfilters.ui.CheckboxInputWidget( {
			value: this.model.getName(),
			selected: this.model.isSelected()
		} );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterItemWidget-label-title' )
				.text( this.model.getLabel() )
		);
		if ( this.model.getDescription() ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterItemWidget-label-desc' )
					.text( this.model.getDescription() )
			);
		}

		this.highlightButton = new mw.rcfilters.ui.FilterItemHighlightButton(
			this.controller,
			this.model,
			{
				$overlay: config.$overlay || this.$element
			}
		);
		this.highlightButton.toggle( this.model.isHighlightEnabled() );

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );

		// Event
		this.checkboxWidget.connect( this, { userChange: 'onCheckboxChange' } );
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.model.getGroupModel().connect( this, { update: 'onGroupModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterItemWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-filterItemWidget-filterCheckbox' )
									.append( layout.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-filterItemWidget-highlightButton' )
									.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterItemWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Respond to checkbox change.
	 * NOTE: This event is emitted both for deliberate user action and for
	 * a change that the code requests ('setSelected')
	 *
	 * @param {boolean} isSelected The checkbox is selected
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.onCheckboxChange = function ( isSelected ) {
		this.controller.toggleFilterSelect( this.model.getName(), isSelected );
	};

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.onModelUpdate = function () {
		this.checkboxWidget.setSelected( this.model.isSelected() );

		this.setCurrentMuteState();
	};

	/**
	 * Respond to item group model update event
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.onGroupModelUpdate = function () {
		this.setCurrentMuteState();
	};

	/**
	 * Set selected state on this widget
	 *
	 * @param {boolean} [isSelected] Widget is selected
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.toggleSelected = function ( isSelected ) {
		isSelected = isSelected !== undefined ? isSelected : !this.selected;

		if ( this.selected !== isSelected ) {
			this.selected = isSelected;

			this.$element.toggleClass( 'mw-rcfilters-ui-filterItemWidget-selected', this.selected );
		}
	};

	/**
	 * Set the current mute state for this item
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.setCurrentMuteState = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterItemWidget-muted',
			this.model.isConflicted() ||
			this.model.isIncluded() ||
			(
				// Item is also muted when any of the items in its group is active
				this.model.getGroupModel().isActive() &&
				// But it isn't selected
				!this.model.isSelected()
			)
		);

		this.highlightButton.toggle( this.model.isHighlightEnabled() );
	};

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.ui.FilterItemWidget.prototype.getName = function () {
		return this.model.getName();
	};
}( mediaWiki, jQuery ) );
