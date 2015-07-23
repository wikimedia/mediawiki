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
	 * @class
	 * @extends OO.ui.InputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [precision='day'] Value precision to use, 'day' or 'month'
	 * @cfg {string|null} [value=null] Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'.
	 *     When null, defaults to current date.
	 */
	mw.widgets.DateInputWidget = function MWWDateInputWidget( config ) {
		// Config initialization
		config = config || {};

		// Properties (must be set before parent constructor, which calls #setValue)
		this.$handle = $( '<span>' );
		this.textInput = new OO.ui.TextInputWidget( {
			validate: this.validateDate.bind( this )
		} );
		this.calendar = new mw.widgets.CalendarWidget( {
			precision: config.precision
		} );
		this.inCalendar = 0;
		this.inTextInput = 0;

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
		this.$handle.on( {
			click: this.onClick.bind( this ),
			keypress: this.onKeyPress.bind( this )
		} );

		// Initialization
		// Move 'tabindex' from this.$input (which is invisible) to visible handle
		this.setTabIndexedElement( this.$handle );
		this.$handle
			.addClass( 'mw-widget-dateInputWidget-handle' )
			.text( '...' );
		this.$element
			.addClass( 'mw-widget-dateInputWidget' )
			.append( this.$handle, this.textInput.$element, this.calendar.$element );

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
	 * Respond to calendar value change events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onCalendarChange = function () {
		this.inCalendar++;
		if ( !this.inTextInput ) {
			// If this is caused by user typing in the input field, do not set anything.
			// The value may be invalid (see #onTextInputChange), but displayable on the calendar.
			this.setValue( this.calendar.getValue() );
		}
		this.inCalendar--;
	};

	/**
	 * Respond to text input value change events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onTextInputChange = function () {
		var widget = this;
		this.inTextInput++;
		this.textInput.isValid().done( function ( valid ) {
			if ( valid ) {
				// Well-formed date value, set it
				widget.setValue( widget.textInput.getValue() );
			} else {
				// Not well-formed, but possibly partial? Try updating the calendar, but do not set the
				// internal value.
				widget.calendar.setValue( widget.textInput.getValue() );
			}
			widget.inTextInput--;
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.DateInputWidget.prototype.setValue = function ( value ) {
		if ( value === undefined || value === null ) {
			// Default to today
			value = this.calendar.getValue();
		}

		var oldValue = this.value;

		mw.widgets.DateInputWidget.parent.prototype.setValue.call( this, value );

		if ( this.value !== oldValue ) {
			if ( !this.inCalendar ) {
				this.calendar.setValue( value );
			}
			if ( !this.inTextInput ) {
				this.textInput.setValue( value );
			}
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
	 * Deactivate this input field for data entry. Opens the calendar and shows the text field.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.deactivate = function () {
		this.textInput.setValue( this.getValue() );
		this.calendar.setValue( this.getValue() );
		this.$handle.text(
			moment( this.getValue(), this.calendar.getFormat() )
				.format( this.getHandleFormat() )
		);

		this.$handle.removeClass( 'oo-ui-element-hidden' );
		this.$element.removeClass( 'mw-widget-dateInputWidget-active' );
		this.textInput.toggle( false );
		this.calendar.toggle( false );
	};

	/**
	 * Activate this input field for data entry. Closes the calendar and hides the text field.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.activate = function () {
		this.setValue( this.getValue() );
		this.$element.addClass( 'mw-widget-dateInputWidget-active' );
		this.$handle.addClass( 'oo-ui-element-hidden' );
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
	mw.widgets.DateInputWidget.prototype.getHandleFormat = function () {
		if ( this.calendar.getFormat() === 'YYYY-MM' ) {
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
	 * Handle text input enter events.
	 *
	 * @private
	 */
	mw.widgets.DateInputWidget.prototype.onEnter = function () {
		this.deactivate();
		this.$handle.focus();
	};

	/**
	 * @private
	 * @param {string} value Date value
	 */
	mw.widgets.DateInputWidget.prototype.validateDate = function ( value ) {
		// Half-strict mode: for the format 'YYYY-MM-DD', 2015-1-3 instead of 2015-01-03 is okay, but
		// 2015-01 isn't, and neither is 2015-01-foo. Use "fuzzy" mode and check parsing flags for
		// the details (stoled from implementation of #isValid).
		var
			mom = moment( value, this.calendar.getFormat() ),
			flags = mom.parsingFlags();

		return mom.isValid() && flags.charsLeftOver === 0 && flags.unusedTokens.length === 0;
	};

}( jQuery, mediaWiki ) );
