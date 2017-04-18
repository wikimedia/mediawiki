( function ( mw ) {
	/**
	 * A floating menu widget for the filter list
	 *
	 * @extends OO.ui.FloatingMenuSelectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 * @cfg {jQuery} [$footer] An optional footer for the menu
	 */
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget = function MwRcfiltersUiFilterFloatingMenuSelectWidget( controller, model, config ) {
		var header;

		config = config || {};

		this.controller = controller;
		this.model = model;

		this.inputValue = '';
		this.$overlay = config.$overlay || this.$element;
		this.$footer = config.$footer;
		this.$body = $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-body' );

		// Parent
		mw.rcfilters.ui.FilterFloatingMenuSelectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: this.$overlay,
			width: 650
		}, config ) );
		this.setGroupElement(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-group' )
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
			classes: [ 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-noresults' ]
		} );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget' )
			.append(
				this.$body
					.append( header.$element, this.$group, this.noResults.$element )
			);

		if ( this.$footer ) {
			this.$element.append(
				this.$footer
					.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-footer' )
			);
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.FilterFloatingMenuSelectWidget, OO.ui.FloatingMenuSelectWidget );

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
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget.prototype.updateItemVisibility = function () {
		var i,
			itemWasHighlighted = false,
			inputVal = this.$input.val(),
			items = this.getItems();

		// Since the method hides/shows items, we don't want to
		// call it unless the input actually changed
		if ( this.inputValue !== inputVal ) {
			// Parent method
			mw.rcfilters.ui.FilterFloatingMenuSelectWidget.parent.prototype.updateItemVisibility.call( this );

			if ( inputVal !== '' ) {
				// Highlight the first item in the list
				for ( i = 0; i < items.length; i++ ) {
					if (
						!( items[ i ] instanceof OO.ui.MenuSectionOptionWidget ) &&
						items[ i ].isVisible()
					) {
						itemWasHighlighted = true;
						this.highlightItem( items[ i ] );
						break;
					}
				}
			}

			if ( !itemWasHighlighted ) {
				this.highlightItem( null );
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
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget.prototype.getItemMatcher = function ( s ) {
		var results = this.model.findMatches( s, true );

		return function ( item ) {
			return results.indexOf( item.getModel() ) > -1;
		};
	};

	/**
	 * Scroll to the top of the menu
	 */
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget.prototype.scrollToTop = function () {
		this.$body.scrollTop( 0 );
	};
}( mediaWiki ) );
