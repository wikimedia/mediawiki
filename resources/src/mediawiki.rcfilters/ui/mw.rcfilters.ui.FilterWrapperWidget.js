( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.filterPopup = new mw.rcfilters.ui.FiltersListWidget(
			this.controller,
			this.model,
			{
				label: mw.msg( 'rcfilters-filterlist-title' ),
				$overlay: this.$overlay
			}
		);

		this.textInput = new OO.ui.TextInputWidget( {
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-search' ],
			icon: 'search',
			placeholder: mw.msg( 'rcfilters-search-placeholder' )
		} );

		this.capsule = new mw.rcfilters.ui.FilterCapsuleMultiselectWidget( controller, this.model, this.textInput, {
			$overlay: this.$overlay,
			popup: {
				$content: this.filterPopup.$element,
				classes: [ 'mw-rcfilters-ui-filterWrapperWidget-popup' ],
				width: 650
			}
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize'
		} );
		this.textInput.connect( this, {
			change: 'onTextInputChange'
		} );
		this.capsule.connect( this, { capsuleItemClick: 'onCapsuleItemClick' } );
		this.capsule.popup.connect( this, { toggle: 'onCapsulePopupToggle' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' )
			.append( this.capsule.$element, this.textInput.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/**
	 * Respond to capsule item click and make the popup scroll down to the requested item
	 *
	 * @param {mw.rcfilters.ui.CapsuleItemWidget} item Clicked item
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsuleItemClick = function ( item ) {
		var filterName = item.getData(),
			// Find the item in the popup
			filterWidget = this.filterPopup.getItemWidget( filterName ),
			container = OO.ui.Element.static.getClosestScrollableContainer( this.filterPopup.$element[ 0 ], 'y' ),
			pos = OO.ui.Element.static.getRelativePosition( filterWidget.$element, $( container ) );

		// Highlight item
		this.filterPopup.select( filterName );

		// Scroll to item
		$( container ).animate( {
			scrollTop: pos.top
		} );
	};

	/**
	 * Respond to popup toggle event. Reset selection in the list when the popup is closed.
	 *
	 * @param {boolean} isVisible Popup is visible
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsulePopupToggle = function ( isVisible ) {
		if ( !isVisible ) {
			this.filterPopup.resetSelection();
		}
	};

	/**
	 * Respond to text input change
	 *
	 * @param {string} newValue Current value
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onTextInputChange = function ( newValue ) {
		this.filterPopup.resetSelection();

		// Filter the results
		this.filterPopup.filter( this.model.findMatches( newValue ) );
	};

	/**
	 * Respond to model update event and set up the available filters to choose
	 * from.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelInitialize = function () {
		var wrapper = this;

		// Add defaults to capsule. We have to do this
		// after we added to the capsule menu, since that's
		// how the capsule multiselect widget knows which
		// object to add
		this.model.getItems().forEach( function ( filterItem ) {
			if ( filterItem.isSelected() ) {
				wrapper.capsule.addItemByName( filterItem.getName() );
			}
		} );
	};
}( mediaWiki ) );
