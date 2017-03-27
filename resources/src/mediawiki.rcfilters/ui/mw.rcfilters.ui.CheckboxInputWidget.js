( function ( mw ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends OO.ui.CheckboxInputWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.CheckboxInputWidget = function MwRcfiltersUiCheckboxInputWidget( config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.CheckboxInputWidget.parent.call( this, config );

		// Event
		this.$input
			.on( 'click', false )
			.on( 'change', this.onUserChange.bind( this ) );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.CheckboxInputWidget, OO.ui.CheckboxInputWidget );

	/* Events */

	/**
	 * @event userChange
	 * @param {boolean} Current state of the checkbox
	 *
	 * The user has checked or unchecked this checkbox
	 */

	/* Methods */

	mw.rcfilters.ui.CheckboxInputWidget.prototype.onEdit = function () {};

	/**
	 * Respond to checkbox change by a user and emit 'userChange'.
	 */
	mw.rcfilters.ui.CheckboxInputWidget.prototype.onUserChange = function () {
		this.emit( 'userChange', this.$input.prop( 'checked' ) );
	};
}( mediaWiki ) );
