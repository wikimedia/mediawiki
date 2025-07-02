( function () {
	/**
	 * @classdesc A JavaScript version of CheckMatrixWidget.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.CheckMatrixWidget`.
	 * @param {Object} [config] Configuration options
	 * @param {Object} config.columns Required object mapping column labels (as HTML) to
	 *  their tags.
	 * @param {Object} config.rows Required object mapping row labels (as HTML) to their
	 *  tags.
	 * @param {string[]} [config.forcedOn] Array of column-row tags to be displayed as
	 *  enabled but unavailable to change.
	 * @param {string[]} [config.forcedOff] Array of column-row tags to be displayed as
	 *  disabled but unavailable to change.
	 * @param {Object} [config.tooltips] Optional object mapping row labels to tooltips
	 *  (as text, will be escaped).
	 * @param {Object} [config.tooltipsHtml] Optional object mapping row labels to tooltips
	 *  (as HTML). Takes precedence over text tooltips.
	 */
	mw.widgets.CheckMatrixWidget = function MWWCheckMatrixWidget( config = {} ) {
		// Parent constructor
		mw.widgets.CheckMatrixWidget.super.call( this, config );
		this.checkboxes = {};
		this.name = config.name;
		this.id = config.id;
		this.rows = config.rows || {};
		this.columns = config.columns || {};
		this.tooltips = config.tooltips || [];
		this.tooltipsHtml = config.tooltipsHtml || [];
		this.values = config.values || [];
		this.forcedOn = config.forcedOn || [];
		this.forcedOff = config.forcedOff || [];

		// Build header
		const $headRow = $( '<tr>' );
		$headRow.append( $( '<td>' ).text( '\u00A0' ) );

		// Iterate over the columns object (ignore the value)
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.columns, ( columnLabel ) => {
			$headRow.append( $( '<th>' ).html( columnLabel ) );
		} );
		const $thead = $( '<thead>' );
		$thead.append( $headRow );

		const $tbody = $( '<tbody>' );
		// Build table
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.rows, ( rowLabel, rowTag ) => {
			const $row = $( '<tr>' ),
				labelField = new OO.ui.FieldLayout(
					new OO.ui.Widget(), // Empty widget, since we don't have the checkboxes here
					{
						label: new OO.ui.HtmlSnippet( rowLabel ),
						help: this.tooltips[ rowLabel ] ||
							this.tooltipsHtml[ rowLabel ] && new OO.ui.HtmlSnippet( this.tooltipsHtml[ rowLabel ] ),
						align: 'inline'
					}
				);

			// Label
			$row.append( $( '<td>' ).append( labelField.$element ) );

			// Columns
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.columns, ( columnLabel, columnTag ) => {
				const thisTag = columnTag + '-' + rowTag,
					checkbox = new OO.ui.CheckboxInputWidget( {
						value: thisTag,
						name: this.name ? this.name + '[]' : undefined,
						id: this.id ? this.id + '-' + thisTag : undefined,
						selected: this.isTagSelected( thisTag ),
						disabled: this.isTagDisabled( thisTag )
					} );

				this.checkboxes[ thisTag ] = checkbox;
				$row.append( $( '<td>' ).append( checkbox.$element ) );
			} );

			$tbody.append( $row );
		} );
		const $table = $( '<table>' );
		$table
			.addClass( 'mw-htmlform-matrix mw-widget-checkMatrixWidget-matrix' )
			.append( $thead, $tbody );

		this.$element
			.addClass( 'mw-widget-checkMatrixWidget' )
			.append( $table );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.CheckMatrixWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Check whether the given tag is selected.
	 *
	 * @param {string} tagName Tag name
	 * @return {boolean} Tag is selected
	 */
	mw.widgets.CheckMatrixWidget.prototype.isTagSelected = function ( tagName ) {
		return (
			// If tag is not forced off
			!this.forcedOff.includes( tagName ) &&
			(
				// If tag is in values
				this.values.includes( tagName ) ||
				// If tag is forced on
				this.forcedOn.includes( tagName )
			)
		);
	};

	/**
	 * Check whether the given tag is disabled.
	 *
	 * @param {string} tagName Tag name
	 * @return {boolean} Tag is disabled
	 */
	mw.widgets.CheckMatrixWidget.prototype.isTagDisabled = function ( tagName ) {
		return (
			// If the entire widget is disabled
			this.isDisabled() ||
			// If tag is forced off or forced on
			this.forcedOff.includes( tagName ) ||
			this.forcedOn.includes( tagName )
		);
	};
	/**
	 * @inheritdoc
	 */
	mw.widgets.CheckMatrixWidget.prototype.setDisabled = function ( isDisabled ) {
		// Parent method
		mw.widgets.CheckMatrixWidget.super.prototype.setDisabled.call( this, isDisabled );

		// setDisabled sometimes gets called before the widget is ready
		if ( this.checkboxes ) {
			// Propagate to all checkboxes and update their disabled state
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.checkboxes, ( name, checkbox ) => {
				checkbox.setDisabled( this.isTagDisabled( name ) );
			} );
		}
	};
}() );
