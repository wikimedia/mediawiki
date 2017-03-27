( function ( mw ) {
	/**
	 * A floating menu widget for the filter list
	 *
	 * @extends OO.ui.FloatingMenuSelectWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FiltersViewModel}
	 * @param {Object} config Configuration object
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
			this.$element.append( this.$footer );
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.FilterFloatingMenuSelectWidget, OO.ui.FloatingMenuSelectWidget );

	mw.rcfilters.ui.FilterFloatingMenuSelectWidget.prototype.getItemMatcher = function ( s ) {
		var results = this.model.findMatches( s, true );

		return function ( item ) {
			return results.indexOf( item.getModel() ) > -1;
		};
	};
}( mediaWiki ) );
