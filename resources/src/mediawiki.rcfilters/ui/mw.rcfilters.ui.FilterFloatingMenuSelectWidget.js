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
		this.width = config.width || 650;

		// Parent
		mw.rcfilters.ui.FilterFloatingMenuSelectWidget.parent.call( this, $.extend( {
			$autoCloseIgnore: this.$overlay
		}, config ) );
		this.setGroupElement(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-group' )
		);
		this.setClippableElement( this.$group );
		this.setClippableContainer( this.$element );

		header = new mw.rcfilters.ui.FilterMenuHeaderWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

		this.$footer = config.$footer;
		this.width = config.width || this.$container.width();

		this.$element
			.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget' )
			.css( 'width', this.width )
			.append( header.$element, this.$group );

		if ( config.$footer ) {
			// TODO: Actually style this to be sticky
			this.$element.append(
				this.$footer
					.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-footer' )
			);
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.FilterFloatingMenuSelectWidget, OO.ui.FloatingMenuSelectWidget );

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget.prototype.updateItemVisibility = function () {
		var i,
			items = this.getItems();

		this.selectItem( null );
		mw.rcfilters.ui.FilterFloatingMenuSelectWidget.parent.prototype.updateItemVisibility.call( this );

		if ( !this.$input.val() ) {
			return;
		}

		// Select the first item in the list
		for ( i = 0; i < items.length; i++ ) {
			if (
				!( items[ i ] instanceof OO.ui.MenuSectionOptionWidget ) &&
				items[ i ].isVisible()
			) {
				this.highlightItem( items[ i ] );
				return;
			}
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
}( mediaWiki ) );
