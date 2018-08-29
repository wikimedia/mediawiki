( function () {
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
			// HACK: This widget just pretends to be a checkbox for visual purposes.
			// In reality, all actions - setting to true or false, etc - are
			// decided by the model, and executed by the controller. This means
			// that we want to let the controller and model make the decision
			// of whether to check/uncheck this checkboxInputWidget, and for that,
			// we have to bypass the browser action that checks/unchecks it during
			// click.
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

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.CheckboxInputWidget.prototype.onEdit = function () {
		// Similarly to preventing defaults in 'click' event, we want
		// to prevent this widget from deciding anything about its own
		// state; it emits a change event and the model and controller
		// make a decision about what its select state is.
		// onEdit has a widget.$input.prop( 'checked' ) inside a setTimeout()
		// so we really want to prevent that from messing with what
		// the model decides the state of the widget is.
	};

	/**
	 * Respond to checkbox change by a user and emit 'userChange'.
	 */
	mw.rcfilters.ui.CheckboxInputWidget.prototype.onUserChange = function () {
		this.emit( 'userChange', this.$input.prop( 'checked' ) );
	};
}() );
