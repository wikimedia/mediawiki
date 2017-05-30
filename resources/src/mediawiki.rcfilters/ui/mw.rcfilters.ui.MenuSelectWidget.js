( function ( mw ) {
	/**
	 * A floating menu widget for the filter list
	 *
	 * @extends OO.ui.MenuSelectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 * @cfg {jQuery} [$footer] An optional footer for the menu
	 */
	mw.rcfilters.ui.MenuSelectWidget = function MwRcfiltersUiMenuSelectWidget( controller, model, config ) {
		var header;

		config = config || {};

		this.controller = controller;
		this.model = model;

		this.inputValue = '';
		this.$overlay = config.$overlay || this.$element;
		this.$footer = config.$footer;
		this.$body = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-menuSelectWidget-body' );

		// Parent
		mw.rcfilters.ui.MenuSelectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: this.$overlay,
			width: 650
		}, config ) );
		this.setGroupElement(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-menuSelectWidget-group' )
		);
		this.setClippableElement( this.$body );
		this.setClippableContainer( this.$element );

		header = new mw.rcfilters.ui.FilterMenuHeaderWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

		this.noResults = new OO.ui.LabelWidget( {
			label: mw.msg( 'rcfilters-filterlist-noresults' ),
			classes: [ 'mw-rcfilters-ui-menuSelectWidget-noresults' ]
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-menuSelectWidget' )
			.append( header.$element )
			.append(
				this.$body
					.append( this.$group, this.noResults.$element )
			);

		if ( this.$footer ) {
			this.$element.append(
				this.$footer
					.addClass( 'mw-rcfilters-ui-menuSelectWidget-footer' )
			);
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.MenuSelectWidget, OO.ui.MenuSelectWidget );

	/* Events */

	/**
	 * @event itemVisibilityChange
	 *
	 * Item visibility has changed
	 */

	/* Methods */

	/**
	 * @fires itemVisibilityChange
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.updateItemVisibility = function () {
		var i,
			itemWasSelected = false,
			inputVal = this.$input.val(),
			items = this.getItems();

		// Since the method hides/shows items, we don't want to
		// call it unless the input actually changed
		if ( this.inputValue !== inputVal ) {
			// Parent method
			mw.rcfilters.ui.MenuSelectWidget.parent.prototype.updateItemVisibility.call( this );

			// Select the first item in the list
			for ( i = 0; i < items.length; i++ ) {
				if (
					!( items[ i ] instanceof OO.ui.MenuSectionOptionWidget ) &&
					items[ i ].isVisible()
				) {
					itemWasSelected = true;
					this.selectItem( items[ i ] );
					break;
				}
			}

			if ( !itemWasSelected ) {
				this.selectItem( null );
			}

			// Cache value
			this.inputValue = inputVal;

			this.emit( 'itemVisibilityChange' );
		}
	};

	/**
	 * Override the item matcher to use the model's match process
	 *
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.getItemMatcher = function ( s ) {
		var results = this.model.findMatches( s, true );

		return function ( item ) {
			return results.indexOf( item.getModel() ) > -1;
		};
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.onKeyDown = function ( e ) {
		var nextItem,
			currentItem = this.getHighlightedItem() || this.getSelectedItem();

		// Call parent
		mw.rcfilters.ui.MenuSelectWidget.parent.prototype.onKeyDown.call( this, e );

		// We want to select the item on arrow movement
		// rather than just highlight it, like the menu
		// does by default
		if ( !this.isDisabled() && this.isVisible() ) {
			switch ( e.keyCode ) {
				case OO.ui.Keys.UP:
				case OO.ui.Keys.LEFT:
					// Get the next item
					nextItem = this.getRelativeSelectableItem( currentItem, -1 );
					break;
				case OO.ui.Keys.DOWN:
				case OO.ui.Keys.RIGHT:
					// Get the next item
					nextItem = this.getRelativeSelectableItem( currentItem, 1 );
					break;
			}

			nextItem = nextItem && nextItem.constructor.static.selectable ?
				nextItem : null;

			// Select the next item
			this.selectItem( nextItem );
		}
	};

	/**
	 * Scroll to the top of the menu
	 */
	mw.rcfilters.ui.MenuSelectWidget.prototype.scrollToTop = function () {
		this.$body.scrollTop( 0 );
	};
}( mediaWiki ) );
