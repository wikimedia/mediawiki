( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} config Configuration object
	 * @param {string|jQuery} [title] Title for this filter group
	 */
	mw.rcfilters.ui.FilterGroupWidget = function MwRcfiltersUiFilterGroupWidget( config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterGroupWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );

		this.$title = $( '<div>' )
			.addClass( 'mw-rcfilters-filterGroupWidget-title' );

		if ( config.title ) {
			this.$element
				.append(
					this.$title
						.text( config.title )
				);
		}

		this.$element
			.addClass( 'mw-rcfilters-filterGroupWidget' )
			.append(
				this.$group
					.addClass( 'mw-rcfilters-filterGroupWidget-group' )
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.GroupWidget );

} )( mediaWiki, jQuery );
