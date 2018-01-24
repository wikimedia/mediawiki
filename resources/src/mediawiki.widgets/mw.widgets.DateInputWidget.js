/*!
 * MediaWiki Widgets – DateInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
/* global moment */
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
	 * @extends OO.ui.TextInputWidget
	 * @mixins OO.ui.mixin.IndicatorElement
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
	 * @cfg {boolean} [longDisplayFormat=false] If a custom displayFormat is not specified, use
	 *     unabbreviated day of the week and month names in the default language-specific displayFormat.
	 * @cfg {string} [placeholderLabel=No date selected] Placeholder text shown when the widget is not
	 *     selected. Default text taken from message `mw-widgets-dateinput-no-date`.
	 * @cfg {string} [placeholderDateFormat] User-visible date format string displayed in the textual input
	 *     field when it's empty. Should be the same as `inputFormat`, but translated to the user's
	 *     language. When not given, defaults to a translated version of 'YYYY-MM-DD' or 'YYYY-MM',
	 *     depending on `precision`.
	 * @cfg {boolean} [required=false] Mark the field as required. Implies `indicator: 'required'`.
	 * @cfg {string} [mustBeAfter] Validates the date to be after this. In the 'YYYY-MM-DD' format.
	 * @cfg {string} [mustBeBefore] Validates the date to be before this. In the 'YYYY-MM-DD' format.
	 * @cfg {jQuery} [$overlay] Render the calendar into a separate layer. This configuration is
	 *     useful in cases where the expanded calendar is larger than its container. The specified
	 *     overlay layer is usually on top of the container and has a larger area. By default, the
	 *     calendar uses relative positioning.
	 */
	mw.widgets.DateInputWidget = function MWWDateInputWidget( config ) {
		var placeholderDateFormat, mustBeAfter, mustBeBefore, $overlay;

		// Config initialization
		config = $.extend( {
			precision: 'day',
			longDisplayFormat: false,
			required: false,
			placeholderLabel: mw.msg( 'mw-widgets-dateinput-no-date' )
		}, config );
		if ( config.required ) {
			if ( config.indicator === undefined ) {
				config.indicator = 'required';
			}
		}

		if ( config.placeholderDateFormat ) {
			placeholderDateFormat = config.placeholderDateFormat;
		} else if ( config.inputFormat ) {
			// We have no way to display a translated placeholder for custom formats
			placeholderDateFormat = '';
		} else {
			// Messages: mw-widgets-dateinput-placeholder-day, mw-widgets-dateinput-placeholder-month
			placeholderDateFormat = mw.msg( 'mw-widgets-dateinput-placeholder-' + config.precision );
		}

		// Properties (must be set before parent constructor, which calls #setValue)
		this.$handle = $( '<div>' );
		this.innerLabel = new OO.ui.LabelWidget();
		this.textInput = new OO.ui.TextInputWidget( {
			required: config.required,
			placeholder: placeholderDateFormat,
			validate: this.validateDate.bind( this )
		} );
		this.calendar = new mw.widgets.CalendarWidget( {
			lazyInitOnToggle: true,
			// Can't pass `$floatableContainer: this.$element` here, the latter is not set yet.
			// Instead we call setFloatableContainer() below.
			precision: config.precision
		} );
		this.inCalendar = 0;
		this.inTextInput = 0;
		this.closing = false;
		this.inputFormat = config.inputFormat;
		this.displayFormat = config.displayFormat;
		this.longDisplayFormat = config.longDisplayFormat;
		this.required = config.required;
		this.placeholderLabel = config.placeholderLabel;
		// Validate and set min and max dates as properties

		if ( config.mustBeAfter !== undefined ) {
			mustBeAfter = moment( config.mustBeAfter, 'YYYY-MM-DD' );
			if ( mustBeAfter.isValid() ) {
				this.mustBeAfter = mustBeAfter;
			}
		}
		if ( config.mustBeBefore !== undefined ) {
			mustBeBefore = moment( config.mustBeBefore, 'YYYY-MM-DD' );
			if ( mustBeBefore.isValid() ) {
				this.mustBeBefore = mustBeBefore;
			}
		}
		// Parent constructor
		mw.widgets.DateInputWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.IndicatorElement.call( this, config );

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
			click: this.onCalendarClick.bind( this ),
			keypress: this.onCalendarKeyPress.bind( this )
		} );
		this.$handle.on( {
			click: this.onClick.bind( this ),
			keypress: this.onKeyPress.bind( this ),
			focus: this.onFocus.bind( this )
		} );

		// Initialization
		// Move 'tabindex' from this.$input (which is invisible) to the visible handle
		this.setTabIndexedElement( this.$handle );
		this.$handle
			.append( this.innerLabel.$element, this.$indicator )
			.addClass( 'mw-widget-dateInputWidget-handle' );
		this.calendar.$element
			.addClass( 'mw-widget-dateInputWidget-calendar' );
		this.$element
			.addClass( 'mw-widget-dateInputWidget' )
			.append( this.$handle, this.textInput.$element, this.calendar.$element );

		$overlay = config.$overlay === true ? OO.ui.getDefaultOverlay() : config.$overlay;

		if ( $overlay ) {
			this.calendar.setFloatableContainer( this.$element );
			$overlay.append( this.calendar.$element );

			// The text input and calendar are not in DOM order, so fix up focus transitions.
			this.textInput.$input.on( 'keydown', function ( e ) {
				if ( e.which === OO.ui.Keys.TAB ) {
					if ( e.shiftKey ) {
						// Tabbing backward from text input: normal browser behavior
						$.noop();
					} else {
						// Tabbing forward from text input: just focus the calendar
						this.calendar.$element.focus();
						return false;
					}
				}
			}.bind( this ) );
			this.calendar.$element.on( 'keydown', function ( e ) {
				if ( e.which === OO.ui.Keys.TAB ) {
					if ( e.shiftKey ) {
						// Tabbing backward from calendar: just focus the text input
						this.textInput.$input.focus();
						return false;
					} else {
						// Tabbing forward from calendar: focus the text input, then allow normal browser
						// behavior to move focus to next focusable after it
						this.textInput.$input.focus();
					}
				}
			}.bind( this ) );
		}

		// Set handle label and hide stuff
		this.updateUI();
		this.textInput.toggle( false );
		this.calendar.toggle( false );

		// Hide unused <input> from PHP after infusion is done
		// See InputWidget#reusePreInfuseDOM about config.$input
		if ( config.$input ) {
			config.$input.addClass( 'oo-ui-element-hidden' );
		}
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.DateInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( mw.widgets.DateInputWidget, OO.ui.mixin.IndicatorElement );

	/* Events */

	/**
	 * Fired when the widget is deactivated (i.e. the calendar is closed). This can happen because
	 * the user selected a value, or because the user blurred the widget.
	 *
	 * @event deactivate
	 * @param {boolean} userSelected Whether the deactivation happened because the user selected a value
	 */

	/* Methods */

	/**
	 * @inheritdoc
	 * @protected
	 */
	mw.widgets.DateInputWidget.prototype.getInputElement = function () {
		return $( '<input>' ).attr( 'type', 'hidden' );
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
		var mom,
			widget = this,
			value = this.textInput.getValue(),
			valid = this.isValidDate( value );
		this.inTextInput++;

		if ( value === '' ) {
			// No date selected
			widget.setValue( '' );
		} else if ( valid ) {
			// Well-formed date value, parse and set it
			mom = moment( value, widget.getInputFormat() );
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
			this.setValidityFlag();
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
			if (
				!OO.ui.contains( widget.$element[ 0 ], $focussed[ 0 ], true ) &&
				// Calendar might be in an $overlay
				!OO.ui.contains( widget.calendar.$element[ 0 ], $focussed[ 0 ], true )
			) {
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
		var moment;
		if ( this.getValue() === '' ) {
			this.textInput.setValue( '' );
			this.calendar.setDate( null );
			this.innerLabel.setLabel( this.placeholderLabel );
			this.$element.addClass( 'mw-widget-dateInputWidget-empty' );
		} else {
			moment = this.getMoment();
			if ( !this.inTextInput ) {
				this.textInput.setValue( moment.format( this.getInputFormat() ) );
			}
			if ( !this.inCalendar ) {
				this.calendar.setDate( this.getValue() );
			}
			this.innerLabel.setLabel( moment.format( this.getDisplayFormat() ) );
			this.$element.removeClass( 'mw-widget-dateInputWidget-empty' );
		}
	};

	/**
	 * Deactivate this input field for data entry. Closes the calendar and hides the text field.
	 *
	 * @private
	 * @param {boolean} [userSelected] Whether we are deactivating because the user selected a value
	 */
	mw.widgets.DateInputWidget.prototype.deactivate = function ( userSelected ) {
		this.$element.removeClass( 'mw-widget-dateInputWidget-active' );
		this.$handle.show();
		this.textInput.toggle( false );
		this.calendar.toggle( false );
		this.setValidityFlag();

		if ( userSelected ) {
			// Prevent focusing the handle from reopening the calendar
			this.closing = true;
			this.$handle.focus();
			this.closing = false;
		}

		this.emit( 'deactivate', !!userSelected );
	};

	/**
	 * Activate this input field for data entry. Opens the calendar and shows the text field.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.activate = function () {
		this.calendar.resetUI();
		this.$element.addClass( 'mw-widget-dateInputWidget-active' );
		this.$handle.hide();
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
		var localeData, llll, lll, ll, format;

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

			localeData = moment.localeData( moment.locale() );
			llll = localeData.longDateFormat( 'llll' );
			lll = localeData.longDateFormat( 'lll' );
			ll = localeData.longDateFormat( 'll' );
			format = llll.replace( lll.replace( ll, '' ), '' );

			if ( this.longDisplayFormat ) {
				// Replace MMM to MMMM and ddd to dddd but don't change MMMM and dddd
				format = format.replace( /MMMM?/, 'MMMM' ).replace( /dddd?/, 'dddd' );
			}

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
	 * @return {boolean} False to cancel the default event
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
	 * @return {boolean} False to cancel the default event
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
	 * Handle focus events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onFocus = function () {
		if ( !this.closing ) {
			this.activate();
		}
	};

	/**
	 * Handle calendar key press events.
	 *
	 * @private
	 * @param {jQuery.Event} e Key press event
	 * @return {boolean} False to cancel the default event
	 */
	mw.widgets.DateInputWidget.prototype.onCalendarKeyPress = function ( e ) {
		if ( !this.isDisabled() && e.which === OO.ui.Keys.ENTER ) {
			this.deactivate( true );
			return false;
		}
	};

	/**
	 * Handle calendar click events.
	 *
	 * @private
	 * @param {jQuery.Event} e Mouse click event
	 * @return {boolean} False to cancel the default event
	 */
	mw.widgets.DateInputWidget.prototype.onCalendarClick = function ( e ) {
		var targetClass = this.calendar.getPrecision() === 'month' ?
			'mw-widget-calendarWidget-month' :
			'mw-widget-calendarWidget-day';
		if (
			!this.isDisabled() &&
			e.which === 1 &&
			$( e.target ).hasClass( targetClass )
		) {
			this.deactivate( true );
			return false;
		}
	};

	/**
	 * Handle text input enter events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onEnter = function () {
		this.deactivate( true );
	};

	/**
	 * @private
	 * @param {string} date Date string, to be valid, must be in 'YYYY-MM-DD' or 'YYYY-MM' format or
	 *     (unless the field is required) empty
	 * @return {boolean}
	 */
	mw.widgets.DateInputWidget.prototype.validateDate = function ( date ) {
		var isValid;
		if ( date === '' ) {
			isValid = !this.required;
		} else {
			isValid = this.isValidDate( date ) && this.isInRange( date );
		}
		return isValid;
	};

	/**
	 * @private
	 * @param {string} date Date string, to be valid, must be in 'YYYY-MM-DD' or 'YYYY-MM' format
	 * @return {boolean}
	 */
	mw.widgets.DateInputWidget.prototype.isValidDate = function ( date ) {
		// "Half-strict mode": for example, for the format 'YYYY-MM-DD', 2015-1-3 instead of 2015-01-03
		// is okay, but 2015-01 isn't, and neither is 2015-01-foo. Use Moment's "fuzzy" mode and check
		// parsing flags for the details (stolen from implementation of moment#isValid).
		var
			mom = moment( date, this.getInputFormat() ),
			flags = mom.parsingFlags();

		return mom.isValid() && flags.charsLeftOver === 0 && flags.unusedTokens.length === 0;
	};

	/**
	 * Validates if the date is within the range configured with {@link #cfg-mustBeAfter}
	 * and {@link #cfg-mustBeBefore}.
	 *
	 * @private
	 * @param {string} date Date string, to be valid, must be empty (no date selected) or in
	 *     'YYYY-MM-DD' or 'YYYY-MM' format to be valid
	 * @return {boolean}
	 */
	mw.widgets.DateInputWidget.prototype.isInRange = function ( date ) {
		var momentDate, isAfter, isBefore;
		if ( this.mustBeAfter === undefined && this.mustBeBefore === undefined ) {
			return true;
		}
		momentDate = moment( date, 'YYYY-MM-DD' );
		isAfter = ( this.mustBeAfter === undefined || momentDate.isAfter( this.mustBeAfter ) );
		isBefore = ( this.mustBeBefore === undefined || momentDate.isBefore( this.mustBeBefore ) );
		return isAfter && isBefore;
	};

	/**
	 * Get the validity of current value.
	 *
	 * This method returns a promise that resolves if the value is valid and rejects if
	 * it isn't. Uses {@link #validateDate}.
	 *
	 * @return {jQuery.Promise} A promise that resolves if the value is valid, rejects if not.
	 */
	mw.widgets.DateInputWidget.prototype.getValidity = function () {
		var isValid = this.validateDate( this.getValue() );

		if ( isValid ) {
			return $.Deferred().resolve().promise();
		} else {
			return $.Deferred().reject().promise();
		}
	};

	/**
	 * Sets the 'invalid' flag appropriately.
	 *
	 * @param {boolean} [isValid] Optionally override validation result
	 */
	mw.widgets.DateInputWidget.prototype.setValidityFlag = function ( isValid ) {
		var widget = this,
			setFlag = function ( valid ) {
				if ( !valid ) {
					widget.$input.attr( 'aria-invalid', 'true' );
				} else {
					widget.$input.removeAttr( 'aria-invalid' );
				}
				widget.setFlags( { invalid: !valid } );
			};

		if ( isValid !== undefined ) {
			setFlag( isValid );
		} else {
			this.getValidity().then( function () {
				setFlag( true );
			}, function () {
				setFlag( false );
			} );
		}
	};

}( jQuery, mediaWiki ) );
