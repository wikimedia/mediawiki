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
				href: 'https://www.mediawiki.org/wiki/Help_talk:New_filters_for_edit_review'
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
				width: 650,
				hideWhenOutOfView: false
			}
		} );

		// Events
		this.model.connect( this, {
			initialize: 'onModelInitialize',
			itemUpdate: 'onModelItemUpdate'
		} );
		this.textInput.connect( this, {
			change: 'onTextInputChange',
			enter: 'onTextInputEnter'
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
		this.capsule.select( item );

		this.scrollToTop( filterWidget.$element );
	};

	/**
	 * Respond to popup toggle event. Reset selection in the list when the popup is closed.
	 *
	 * @param {boolean} isVisible Popup is visible
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onCapsulePopupToggle = function ( isVisible ) {
		if ( !isVisible ) {
			if ( !this.textInput.getValue() ) {
				// Only reset selection if we are not filtering
				this.filterPopup.resetSelection();
				this.capsule.resetSelection();
			}
		} else {
			this.scrollToTop( this.capsule.$element, 10 );
			if ( !this.filterPopup.getSelectedFilter() ) {
				// No selection, scroll the popup list to top
				setTimeout( function () { this.capsule.popup.$body.scrollTop( 0 ); }.bind( this ), 0 );
			}
		}
	};

	/**
	 * Respond to text input change
	 *
	 * @param {string} newValue Current value
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onTextInputChange = function ( newValue ) {
		// Filter the results
		this.filterPopup.filter( this.model.findMatches( newValue ) );

		if ( !newValue ) {
			// If the value is empty, we didn't actually
			// filter anything. the filter method will run
			// and show all, but then will select the
			// top item - but in this case, no selection
			// should be made.
			this.filterPopup.resetSelection();
		}
		this.capsule.popup.clip();
	};

	/**
	 * Respond to text input enter event
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onTextInputEnter = function () {
		var filter = this.filterPopup.getSelectedFilter();

		// Toggle the filter
		this.controller.toggleFilterSelect( filter );
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
		if ( !this.textInput.getValue() ) {
			this.filterPopup.resetSelection();
		}
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
			pos = OO.ui.Element.static.getRelativePosition( $element, $( container ) ),
			containerScrollTop = $( container ).is( 'body, html' ) ? 0 : $( container ).scrollTop();

		// Scroll to item
		$( container ).animate( {
			scrollTop: containerScrollTop + pos.top - ( marginFromTop || 0 )
		} );
	};
}( mediaWiki ) );
