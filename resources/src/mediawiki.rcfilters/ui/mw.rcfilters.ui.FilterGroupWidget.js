( function ( mw, $ ) {
	/**
	 * A group of filters
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {string} name Group name
	 * @param {Object} config Configuration object
	 * @cfg {string|jQuery} [label] Title for this filter group
	 */
	mw.rcfilters.ui.FilterGroupWidget = function MwRcfiltersUiFilterGroupWidget( name, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterGroupWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.GroupWidget.call( this, config );
		OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterGroupWidget-title' ),
		} ) );

		this.name = name;

		this.$invalid = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterGroupWidget-invalid-notice' )
			.append( mw.msg( 'rcfilters-filtergroup-invalid-parameters' ) );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterGroupWidget' )
			.append(
				this.$label,
				this.$invalid,
				this.$group
					.addClass( 'mw-rcfilters-ui-filterGroupWidget-group' )
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.GroupWidget );
	OO.mixinClass( mw.rcfilters.ui.FilterGroupWidget, OO.ui.mixin.LabelElement );

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
