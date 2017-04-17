( function ( mw ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget = function MwRcfiltersUiFilterMenuOptionWidget( controller, model, config ) {
		var layout,
			$label = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget-label' );

		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.FilterMenuOptionWidget.parent.call( this, $.extend( {
			// Override the 'check' icon that OOUI defines
			icon: '',
			data: this.model.getName(),
			label: this.model.getLabel()
		}, config ) );

		this.checkboxWidget = new mw.rcfilters.ui.CheckboxInputWidget( {
			value: this.model.getName(),
			selected: this.model.isSelected()
		} );

		$label.append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget-label-title' )
				.append( this.$label )
		);
		if ( this.model.getDescription() ) {
			$label.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget-label-desc' )
					.text( this.model.getDescription() )
			);
		}

		this.highlightButton = new mw.rcfilters.ui.FilterItemHighlightButton(
			this.controller,
			this.model,
			{
				$overlay: config.$overlay || this.$element,
				title: mw.msg( 'rcfilters-highlightmenu-help' )
			}
		);
		this.highlightButton.toggle( this.model.isHighlightEnabled() );

		layout = new OO.ui.FieldLayout( this.checkboxWidget, {
			label: $label,
			align: 'inline'
		} );
		// Event
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.model.getGroupModel().connect( this, { update: 'onGroupModelUpdate' } );
		// HACK: Prevent defaults on 'click' for the label so it
		// doesn't steal the focus away from the input. This means
		// we can continue arrow-movement after we click the label
		// and is consistent with the checkbox *itself* also preventing
		// defaults on 'click' as well.
		layout.$label.on( 'click', false );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-filterMenuOptionWidget-filterCheckbox' )
									.append( layout.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell mw-rcfilters-ui-filterMenuOptionWidget-highlightButton' )
									.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterMenuOptionWidget, OO.ui.MenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	mw.rcfilters.ui.FilterMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * Respond to item model update event
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.onModelUpdate = function () {
		this.checkboxWidget.setSelected( this.model.isSelected() );

		this.setCurrentMuteState();
	};

	/**
	 * Respond to item group model update event
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.onGroupModelUpdate = function () {
		this.setCurrentMuteState();
	};

	/**
	 * Set the current mute state for this item
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.setCurrentMuteState = function () {
		this.$element.toggleClass(
			'mw-rcfilters-ui-filterMenuOptionWidget-muted',
			this.model.isConflicted() ||
			(
				// Item is also muted when any of the items in its group is active
				this.model.getGroupModel().isActive() &&
				// But it isn't selected
				!this.model.isSelected() &&
				// And also not included
				!this.model.isIncluded()
			)
		);

		this.highlightButton.toggle( this.model.isHighlightEnabled() );
	};

	/**
	 * Get the name of this filter
	 *
	 * @return {string} Filter name
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.getName = function () {
		return this.model.getName();
	};

	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.getModel = function () {
		return this.model;
	};

}( mediaWiki ) );
