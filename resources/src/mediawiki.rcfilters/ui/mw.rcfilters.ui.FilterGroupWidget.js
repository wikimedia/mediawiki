( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} config Configuration object
	 * @cfg {string|jQuery} [title] Title for this filter group
	 */
	mw.rcfilters.ui.FilterGroupWidget = function MwRcfiltersUiFilterGroupWidget( name, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterGroupWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );

		this.name = name;

		this.$title = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterGroupWidget-title' );

		this.$invalid = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterGroupWidget-invalid-notice' )
			.append( mw.msg( 'rcfilters-filtergroup-invalid-parameters' ) );

		if ( config.title ) {
			this.$element
				.append(
					this.$title
						.text( config.title )
				);
		}

		this.$element
			.addClass( 'mw-rcfilters-ui-filterGroupWidget' )
			.append(
				this.$invalid,
				this.$group
					.addClass( 'mw-rcfilters-ui-filterGroupWidget-group' )
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.GroupWidget );

	/**
	 * Get the group name
	 *
	 * @return {string} Group name
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.getName = function () {
		return this.name;
	};

	/**
	 * Toggle the validity state of this group
	 *
	 * @param {boolean} isValid Group is valid
	 */
	mw.rcfilters.ui.FilterGroupWidget.prototype.toggleValidState = function ( isValid ) {
		this.$element.toggleClass( 'mw-rcfilters-ui-filterGroupWidget-invalid', !isValid );
	};

} )( mediaWiki, jQuery );
