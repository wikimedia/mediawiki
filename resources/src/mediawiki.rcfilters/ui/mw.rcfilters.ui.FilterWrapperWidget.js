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
	 * @param {Object} config Configuration object
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

		this.filtersInCapsule = [];

		this.filterPopup = new mw.rcfilters.ui.FiltersListWidget(
			this.controller,
			this.model,
			{
				label: mw.msg( 'rcfilters-filterlist-title' )
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
				classes: [ 'mw-rcfilters-ui-filterWrapperWidget-popup' ]
			}
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			itemUpdate: 'onModelItemUpdate'
		} );
		this.textInput.connect( this, {
			change: 'onTextInputChange'
		} );
		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' )
			.append( this.capsule.$element, this.textInput.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/**
	 * Respond to text input change
	 *
	 * @param {string} newValue Current value
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onTextInputChange = function ( newValue ) {
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

	/**
	 * Respond to model item update
	 *
	 * @param {mw.rcfilters.dm.FilterItem} item Filter item that was updated
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelItemUpdate = function ( item ) {
		var widget = this;

		// Toggle the active state of the group
		this.filterPopup.getItems().forEach( function ( groupWidget ) {
			if ( groupWidget.getName() === item.getGroup() ) {
				groupWidget.toggleActiveState( widget.model.isFilterGroupActive( groupWidget.getName() ) );
			}
		} );
	};

	/**
	 * Add a capsule item by its filter name
	 *
	 * @param {string} itemName Filter name
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.addCapsuleItemFromName = function ( itemName ) {
		var item = this.model.getItemByName( itemName );

		this.capsule.addItemByName( [ itemName ] );
	};
}( mediaWiki ) );
