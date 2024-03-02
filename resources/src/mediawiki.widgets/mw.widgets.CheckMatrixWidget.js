( function () {
	/**
	 * A JavaScript version of CheckMatrixWidget.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
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
	mw.widgets.CheckMatrixWidget = function MWWCheckMatrixWidget( config ) {
		var widget = this;
		config = config || {};

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
		var $headRow = $( '<tr>' );
		$headRow.append( $( '<td>' ).text( '\u00A0' ) );

		// Iterate over the columns object (ignore the value)
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.columns, function ( columnLabel ) {
			$headRow.append( $( '<th>' ).html( columnLabel ) );
		} );
		var $thead = $( '<thead>' );
		$thead.append( $headRow );

		var $tbody = $( '<tbody>' );
		// Build table
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( this.rows, function ( rowLabel, rowTag ) {
			var $row = $( '<tr>' ),
				labelField = new OO.ui.FieldLayout(
					new OO.ui.Widget(), // Empty widget, since we don't have the checkboxes here
					{
						label: new OO.ui.HtmlSnippet( rowLabel ),
						help: widget.tooltips[ rowLabel ] ||
							widget.tooltipsHtml[ rowLabel ] && new OO.ui.HtmlSnippet( widget.tooltipsHtml[ rowLabel ] ),
						align: 'inline'
					}
				);

			// Label
			$row.append( $( '<td>' ).append( labelField.$element ) );

			// Columns
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( widget.columns, function ( columnLabel, columnTag ) {
				var thisTag = columnTag + '-' + rowTag,
					checkbox = new OO.ui.CheckboxInputWidget( {
						value: thisTag,
						name: widget.name ? widget.name + '[]' : undefined,
						id: widget.id ? widget.id + '-' + thisTag : undefined,
						selected: widget.isTagSelected( thisTag ),
						disabled: widget.isTagDisabled( thisTag )
					} );

				widget.checkboxes[ thisTag ] = checkbox;
				$row.append( $( '<td>' ).append( checkbox.$element ) );
			} );

			$tbody.append( $row );
		} );
		var $table = $( '<table>' );
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
			this.forcedOff.indexOf( tagName ) === -1 &&
			(
				// If tag is in values
				this.values.indexOf( tagName ) > -1 ||
				// If tag is forced on
				this.forcedOn.indexOf( tagName ) > -1
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
			this.forcedOff.indexOf( tagName ) > -1 ||
			this.forcedOn.indexOf( tagName ) > -1
		);
	};
	/**
	 * @inheritdoc
	 */
	mw.widgets.CheckMatrixWidget.prototype.setDisabled = function ( isDisabled ) {
		var widget = this;

		// Parent method
		mw.widgets.CheckMatrixWidget.super.prototype.setDisabled.call( this, isDisabled );

		// setDisabled sometimes gets called before the widget is ready
		if ( this.checkboxes ) {
			// Propagate to all checkboxes and update their disabled state
			// eslint-disable-next-line no-jquery/no-each-util
			$.each( this.checkboxes, function ( name, checkbox ) {
				checkbox.setDisabled( widget.isTagDisabled( name ) );
			} );
		}
	};
}() );
