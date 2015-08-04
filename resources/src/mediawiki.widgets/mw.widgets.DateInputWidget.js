/*!
 * MediaWiki Widgets – DateInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
/*global moment */
( function ( $, mw ) {

	/**
	 * Creates an mw.widgets.DateInputWidget object.
	 *
	 *     @example
	 *     // Date input widget showcase
	 *     var fieldset = new OO.ui.FieldsetLayout( {
	 *       items: [
	 *         new OO.ui.FieldLayout(
	 *           new mw.widgets.DateInputWidget(),
	 *           {
	 *             align: 'top',
	 *             label: 'Select date'
	 *           }
	 *         ),
	 *         new OO.ui.FieldLayout(
	 *           new mw.widgets.DateInputWidget( { precision: 'month' } ),
	 *           {
	 *             align: 'top',
	 *             label: 'Select month'
	 *           }
	 *         ),
	 *         new OO.ui.FieldLayout(
	 *           new mw.widgets.DateInputWidget( {
	 *             inputFormat: 'DD.MM.YYYY',
	 *             displayFormat: 'Do [of] MMMM [anno Domini] YYYY'
	 *           } ),
	 *           {
	 *             align: 'top',
	 *             label: 'Select date (custom formats)'
	 *           }
	 *         )
	 *       ]
	 *     } );
	 *     $( 'body' ).append( fieldset.$element );
	 *
	 * The value is stored in 'YYYY-MM-DD' or 'YYYY-MM' format:
	 *
	 *     @example
	 *     // Accessing values in a date input widget
	 *     var dateInput = new mw.widgets.DateInputWidget();
	 *     var $label = $( '<p>' );
	 *     $( 'body' ).append( $label, dateInput.$element );
	 *     dateInput.on( 'change', function () {
	 *       // The value will always be a valid date or empty string, malformed input is ignored
	 *       var date = dateInput.getValue();
	 *       $label.text( 'Selected date: ' + ( date || '(none)' ) );
	 *     } );
	 *
	 * @class
	 * @extends OO.ui.InputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [precision='day'] Date precision to use, 'day' or 'month'
	 * @cfg {string} [value] Day or month date (depending on `precision`), in the format 'YYYY-MM-DD'
	 *     or 'YYYY-MM'. If not given or empty string, no date is selected.
	 * @cfg {string} [inputFormat] Date format string to use for the textual input field. Displayed
	 *     while the widget is active, and the user can type in a date in this format. Should be short
	 *     and easy to type. When not given, defaults to 'YYYY-MM-DD' or 'YYYY-MM', depending on
	 *     `precision`.
	 * @cfg {string} [displayFormat] Date format string to use for the clickable label. Displayed
	 *     while the widget is inactive. Should be as unambiguous as possible (for example, prefer to
	 *     spell out the month, rather than rely on the order), even if that makes it longer. When not
	 *     given, the default is language-specific.
	 * @cfg {string} [placeholder] User-visible date format string displayed in the textual input
	 *     field when it's empty. Should be the same as `inputFormat`, but translated to the user's
	 *     language. When not given, defaults to a translated version of 'YYYY-MM-DD' or 'YYYY-MM',
	 *     depending on `precision`.
	 */
	mw.widgets.DateInputWidget = function MWWDateInputWidget( config ) {
		// Config initialization
		config = $.extend( { precision: 'day' }, config );

		var placeholder;
		if ( config.placeholder ) {
			placeholder = config.placeholder;
		} else if ( config.inputFormat ) {
			// We have no way to display a translated placeholder for custom formats
			placeholder = '';
		} else {
			// Messages: mw-widgets-dateinput-placeholder-day, mw-widgets-dateinput-placeholder-month
			placeholder = mw.msg( 'mw-widgets-dateinput-placeholder-' + config.precision );
		}

		// Properties (must be set before parent constructor, which calls #setValue)
		this.handle = new OO.ui.LabelWidget();
		this.textInput = new OO.ui.TextInputWidget( {
			placeholder: placeholder,
			validate: this.validateDate.bind( this )
		} );
		this.calendar = new mw.widgets.CalendarWidget( {
			precision: config.precision
		} );
		this.inCalendar = 0;
		this.inTextInput = 0;
		this.inputFormat = config.inputFormat;
		this.displayFormat = config.displayFormat;

		// Parent constructor
		mw.widgets.DateInputWidget.parent.call( this, config );

		// Events
		this.calendar.connect( this, {
			change: 'onCalendarChange'
		} );
		this.textInput.connect( this, {
			enter: 'onEnter',
			change: 'onTextInputChange'
		} );
		this.$element.on( {
			focusout: this.onBlur.bind( this )
		} );
		this.calendar.$element.on( {
			keypress: this.onCalendarKeyPress.bind( this )
		} );
		this.handle.$element.on( {
			click: this.onClick.bind( this ),
			keypress: this.onKeyPress.bind( this )
		} );

		// Initialization
		// Move 'tabindex' from this.$input (which is invisible) to the visible handle
		this.setTabIndexedElement( this.handle.$element );
		this.handle.$element
			.addClass( 'mw-widget-dateInputWidget-handle' );
		this.$element
			.addClass( 'mw-widget-dateInputWidget' )
			.append( this.handle.$element, this.textInput.$element, this.calendar.$element );
		// Set handle label and hide stuff
		this.updateUI();
		this.deactivate();
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.DateInputWidget, OO.ui.InputWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 * @protected
	 */
	mw.widgets.DateInputWidget.prototype.getInputElement = function () {
		return $( '<input type="hidden">' );
	};

	/**
	 * Respond to calendar date change events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onCalendarChange = function () {
		this.inCalendar++;
		if ( !this.inTextInput ) {
			// If this is caused by user typing in the input field, do not set anything.
			// The value may be invalid (see #onTextInputChange), but displayable on the calendar.
			this.setValue( this.calendar.getDate() );
		}
		this.inCalendar--;
	};

	/**
	 * Respond to text input value change events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onTextInputChange = function () {
		var
			widget = this,
			value = this.textInput.getValue();
		this.inTextInput++;
		this.textInput.isValid().done( function ( valid ) {
			if ( value === '' ) {
				// No date selected
				widget.setValue( '' );
			} else if ( valid ) {
				// Well-formed date value, parse and set it
				var mom = moment( value, widget.getInputFormat() );
				// Use English locale to avoid number formatting
				widget.setValue( mom.locale( 'en' ).format( widget.getInternalFormat() ) );
			} else {
				// Not well-formed, but possibly partial? Try updating the calendar, but do not set the
				// internal value. Generally this only makes sense when 'inputFormat' is little-endian (e.g.
				// 'YYYY-MM-DD'), but that's hard to check for, and might be difficult to handle the parsing
				// right for weird formats. So limit this trick to only when we're using the default
				// 'inputFormat', which is the same as the internal format, 'YYYY-MM-DD'.
				if ( widget.getInputFormat() === widget.getInternalFormat() ) {
					widget.calendar.setDate( widget.textInput.getValue() );
				}
			}
			widget.inTextInput--;
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.setValue = function ( value ) {
		var oldValue = this.value;

		if ( !moment( value, this.getInternalFormat() ).isValid() ) {
			value = '';
		}

		mw.widgets.DateInputWidget.parent.prototype.setValue.call( this, value );

		if ( this.value !== oldValue ) {
			this.updateUI();
		}

		return this;
	};

	/**
	 * Handle text input and calendar blur events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onBlur = function () {
		var widget = this;
		setTimeout( function () {
			var $focussed = $( ':focus' );
			// Deactivate unless the focus moved to something else inside this widget
			if ( !OO.ui.contains( widget.$element[ 0 ], $focussed[0], true ) ) {
				widget.deactivate();
			}
		}, 0 );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.focus = function () {
		this.activate();
		return this;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.blur = function () {
		this.deactivate();
		return this;
	};

	/**
	 * Update the contents of the label, text input and status of calendar to reflect selected value.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.updateUI = function () {
		if ( this.getValue() === '' ) {
			this.textInput.setValue( '' );
			this.calendar.setDate( null );
			this.handle.setLabel( mw.msg( 'mw-widgets-dateinput-no-date' ) );
			this.$element.addClass( 'mw-widget-dateInputWidget-empty' );
		} else {
			if ( !this.inTextInput ) {
				this.textInput.setValue( this.getMoment().format( this.getInputFormat() ) );
			}
			if ( !this.inCalendar ) {
				this.calendar.setDate( this.getValue() );
			}
			this.handle.setLabel( this.getMoment().format( this.getDisplayFormat() ) );
			this.$element.removeClass( 'mw-widget-dateInputWidget-empty' );
		}
	};

	/**
	 * Deactivate this input field for data entry. Closes the calendar and hides the text field.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.deactivate = function () {
		this.$element.removeClass( 'mw-widget-dateInputWidget-active' );
		this.handle.toggle( true );
		this.textInput.toggle( false );
		this.calendar.toggle( false );
	};

	/**
	 * Activate this input field for data entry. Opens the calendar and shows the text field.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.activate = function () {
		this.$element.addClass( 'mw-widget-dateInputWidget-active' );
		this.handle.toggle( false );
		this.textInput.toggle( true );
		this.calendar.toggle( true );

		this.textInput.$input.focus();
	};

	/**
	 * Get the date format to be used for handle label when the input is inactive.
	 *
	 * @private
	 * @return {string} Format string
	 */
	mw.widgets.DateInputWidget.prototype.getDisplayFormat = function () {
		if ( this.displayFormat !== undefined ) {
			return this.displayFormat;
		}

		if ( this.calendar.getPrecision() === 'month' ) {
			return 'MMMM YYYY';
		} else {
			// The formats Moment.js provides:
			// * ll:   Month name, day of month, year
			// * lll:  Month name, day of month, year, time
			// * llll: Month name, day of month, day of week, year, time
			//
			// The format we want:
			// * ????: Month name, day of month, day of week, year
			//
			// We try to construct it as 'llll - (lll - ll)' and hope for the best.
			// This seems to work well for many languages (maybe even all?).

			var localeData = moment.localeData( moment.locale() ),
				llll = localeData.longDateFormat( 'llll' ),
				lll = localeData.longDateFormat( 'lll' ),
				ll = localeData.longDateFormat( 'll' ),
				format = llll.replace( lll.replace( ll, '' ), '' );

			return format;
		}
	};

	/**
	 * Get the date format to be used for the text field when the input is active.
	 *
	 * @private
	 * @return {string} Format string
	 */
	mw.widgets.DateInputWidget.prototype.getInputFormat = function () {
		if ( this.inputFormat !== undefined ) {
			return this.inputFormat;
		}

		return {
			day: 'YYYY-MM-DD',
			month: 'YYYY-MM'
		}[ this.calendar.getPrecision() ];
	};

	/**
	 * Get the date format to be used internally for the value. This is not configurable in any way,
	 * and always either 'YYYY-MM-DD' or 'YYYY-MM'.
	 *
	 * @private
	 * @return {string} Format string
	 */
	mw.widgets.DateInputWidget.prototype.getInternalFormat = function () {
		return {
			day: 'YYYY-MM-DD',
			month: 'YYYY-MM'
		}[ this.calendar.getPrecision() ];
	};

	/**
	 * Get the Moment object for current value.
	 *
	 * @return {Object} Moment object
	 */
	mw.widgets.DateInputWidget.prototype.getMoment = function () {
		return moment( this.getValue(), this.getInternalFormat() );
	};

	/**
	 * Handle mouse click events.
	 *
	 * @private
	 * @param {jQuery.Event} e Mouse click event
	 */
	mw.widgets.DateInputWidget.prototype.onClick = function ( e ) {
		if ( !this.isDisabled() && e.which === 1 ) {
			this.activate();
		}
		return false;
	};

	/**
	 * Handle key press events.
	 *
	 * @private
	 * @param {jQuery.Event} e Key press event
	 */
	mw.widgets.DateInputWidget.prototype.onKeyPress = function ( e ) {
		if ( !this.isDisabled() &&
			( e.which === OO.ui.Keys.SPACE || e.which === OO.ui.Keys.ENTER )
		) {
			this.activate();
			return false;
		}
	};

	/**
	 * Handle calendar key press events.
	 *
	 * @private
	 * @param {jQuery.Event} e Key press event
	 */
	mw.widgets.DateInputWidget.prototype.onCalendarKeyPress = function ( e ) {
		if ( !this.isDisabled() && e.which === OO.ui.Keys.ENTER ) {
			this.deactivate();
			this.handle.$element.focus();
			return false;
		}
	};

	/**
	 * Handle text input enter events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onEnter = function () {
		this.deactivate();
		this.handle.$element.focus();
	};

	/**
	 * @private
	 * @param {string} date Date string, to be valid, must be empty (no date selected) or in
	 *     'YYYY-MM-DD' or 'YYYY-MM' format to be valid
	 */
	mw.widgets.DateInputWidget.prototype.validateDate = function ( date ) {
		if ( date === '' ) {
			return true;
		}

		// "Half-strict mode": for example, for the format 'YYYY-MM-DD', 2015-1-3 instead of 2015-01-03
		// is okay, but 2015-01 isn't, and neither is 2015-01-foo. Use Moment's "fuzzy" mode and check
		// parsing flags for the details (stoled from implementation of #isValid).
		var
			mom = moment( date, this.getInputFormat() ),
			flags = mom.parsingFlags();

		return mom.isValid() && flags.charsLeftOver === 0 && flags.unusedTokens.length === 0;
	};

}( jQuery, mediaWiki ) );
