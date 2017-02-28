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
		var $footer = $( '<div>' );
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

		$footer.append(
			new OO.ui.ButtonWidget( {
				framed: false,
				icon: 'feedback',
				flags: [ 'progressive' ],
				label: mw.msg( 'rcfilters-filterlist-feedbacklink' ),
				href: 'https://www.mediawiki.org/wiki/Help_talk:Edit_Review_Improvements/RC_filters'
			} ).$element
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
				$footer: $footer,
				classes: [ 'mw-rcfilters-ui-filterWrapperWidget-popup' ],
				width: 650
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
			filterWidget = this.filterPopup.getItemWidget( filterName );

		// Highlight item
		this.filterPopup.select( filterName );

		this.scrollToTop( filterWidget.$element );
	};

	/**
	 * Respond to popup toggle event. Reset selection in the list when the popup is closed.
	 *
	 * @param {boolean} isVisible Popup is visible
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsulePopupToggle = function ( isVisible ) {
		if ( !isVisible ) {
			this.filterPopup.resetSelection();
		} else {
			this.scrollToTop( this.capsule.$element, 10 );
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
		this.capsule.popup.clip();
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
	 * Respond to item update and reset the selection. This will make it so that
	 * any actual interaction with the system resets the selection state of any item.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelItemUpdate = function () {
		this.filterPopup.resetSelection();
	};

	/**
	 * Scroll the element to top within its container
	 *
	 * @private
	 * @param {jQuery} $element Element to position
	 * @param {number} [marginFromTop] When scrolling the entire widget to the top, leave this
	 *  much space (in pixels) above the widget.
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.scrollToTop = function ( $element, marginFromTop ) {
		var container = OO.ui.Element.static.getClosestScrollableContainer( $element[ 0 ], 'y' ),
			pos = OO.ui.Element.static.getRelativePosition( $element, $( container ) );

		// Scroll to item
		$( container ).animate( {
			scrollTop: $( container ).scrollTop() + pos.top + ( marginFromTop || 0 )
		} );
	};
}( mediaWiki ) );
