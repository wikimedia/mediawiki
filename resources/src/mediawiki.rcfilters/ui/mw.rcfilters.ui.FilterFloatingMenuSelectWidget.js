( function ( mw ) {
	/**
	 * A floating menu widget for the filter list
	 *
	 * @extends OO.ui.FloatingMenuSelectWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterFloatingMenuSelectWidget = function MwRcfiltersUiFilterFloatingMenuSelectWidget( config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterFloatingMenuSelectWidget.parent.call( this, config );
		this.setGroupElement(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget-group' )
		);
		this.setClippableElement( this.$group );

		this.$footer = config.$footer;
		this.width = config.width || this.$container.width();

		this.$element
			.addClass( 'mw-rcfilters-ui-filterFloatingMenuSelectWidget' )
			.append( this.$group );

		if ( config.$footer ) {
			// TODO: Actually style this to be sticky
			this.menu.$element.append( $footer );
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.FilterFloatingMenuSelectWidget, OO.ui.FloatingMenuSelectWidget );
}( mediaWiki ) );
