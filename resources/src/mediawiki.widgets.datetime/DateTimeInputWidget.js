( function () {

	/**
	 * DateTimeInputWidgets can be used to input a date, a time, or a date and
	 * time, in either UTC or the user's local timezone.
	 * Please see the [OOUI documentation on MediaWiki] [1] for more information and examples.
	 *
	 * This widget can be used inside a HTML form, such as a OO.ui.FormLayout.
	 *
	 *     @example
	 *     // Example of a text input widget
	 *     var dateTimeInput = new mw.widgets.datetime.DateTimeInputWidget( {} )
	 *     $( 'body' ).append( dateTimeInput.$element );
	 *
	 * [1]: https://www.mediawiki.org/wiki/OOUI/Widgets/Inputs
	 *
	 * @class
	 * @extends OO.ui.InputWidget
	 * @mixins OO.ui.mixin.IconElement
	 * @mixins OO.ui.mixin.IndicatorElement
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [type='datetime'] Whether to act like a 'date', 'time', or 'datetime' input.
	 *  Affects values stored in the relevant <input> and the formatting and
	 *  interpretation of values passed to/from getValue() and setValue(). It's up
	 *  to the user to configure the DateTimeFormatter correctly.
	 * @cfg {Object|mw.widgets.datetime.DateTimeFormatter} [formatter={}] Configuration options for
	 *  mw.widgets.datetime.ProlepticGregorianDateTimeFormatter (with 'format' defaulting to
	 *  '@date', '@time', or '@datetime' depending on 'type'), or an
	 *  mw.widgets.datetime.DateTimeFormatter instance to use.
	 * @cfg {Object|null} [calendar={}] Configuration options for
	 *  mw.widgets.datetime.CalendarWidget; note certain settings will be forced based on the
	 *  settings passed to this widget. Set null to disable the calendar.
	 * @cfg {boolean} [required=false] Whether a value is required.
	 * @cfg {boolean} [clearable=true] Whether to provide for blanking the value.
	 * @cfg {Date|null} [value=null] Default value for the widget
	 * @cfg {Date|string|null} [min=null] Minimum allowed date
	 * @cfg {Date|string|null} [max=null] Maximum allowed date
	 */
	mw.widgets.datetime.DateTimeInputWidget = function MwWidgetsDatetimeDateTimeInputWidget( config ) {
		// Configuration initialization
		config = $.extend( {
			type: 'datetime',
			clearable: true,
			required: false,
			min: null,
			max: null,
			formatter: {},
			calendar: {}
		}, config );

		// See InputWidget#reusePreInfuseDOM about config.$input
		if ( config.$input ) {
			config.$input.addClass( 'oo-ui-element-hidden' );
		}

		if ( $.isPlainObject( config.formatter ) && config.formatter.format === undefined ) {
			config.formatter.format = '@' + config.type;
		}

		// Early properties
		this.type = config.type;

		// Parent constructor
		mw.widgets.datetime.DateTimeInputWidget[ 'super' ].call( this, config );

		// Mixin constructors
		OO.ui.mixin.IconElement.call( this, config );
		OO.ui.mixin.IndicatorElement.call( this, config );
		OO.ui.mixin.PendingElement.call( this, config );

		// Properties
		this.$handle = $( '<span>' );
		this.$fields = $( '<span>' );
		this.fields = [];
		this.clearable = !!config.clearable;
		this.required = !!config.required;

		if ( typeof config.min === 'string' ) {
			config.min = this.parseDateValue( config.min );
		}
		if ( config.min instanceof Date && config.min.getTime() >= -62167219200000 ) {
			this.min = config.min;
		} else {
			this.min = new Date( -62167219200000 ); // 0000-01-01T00:00:00.000Z
		}

		if ( typeof config.max === 'string' ) {
			config.max = this.parseDateValue( config.max );
		}
		if ( config.max instanceof Date && config.max.getTime() <= 253402300799999 ) {
			this.max = config.max;
		} else {
			this.max = new Date( 253402300799999 ); // 9999-12-31T12:59:59.999Z
		}

		switch ( this.type ) {
			case 'date':
				this.min.setUTCHours( 0, 0, 0, 0 );
				this.max.setUTCHours( 23, 59, 59, 999 );
				break;
			case 'time':
				this.min.setUTCFullYear( 1970, 0, 1 );
				this.max.setUTCFullYear( 1970, 0, 1 );
				break;
		}
		if ( this.min > this.max ) {
			throw new Error(
				'"min" (' + this.min.toISOString() + ') must not be greater than ' +
				'"max" (' + this.max.toISOString() + ')'
			);
		}

		if ( config.formatter instanceof mw.widgets.datetime.DateTimeFormatter ) {
			this.formatter = config.formatter;
		} else if ( $.isPlainObject( config.formatter ) ) {
			this.formatter = new mw.widgets.datetime.ProlepticGregorianDateTimeFormatter( config.formatter );
		} else {
			throw new Error( '"formatter" must be an mw.widgets.datetime.DateTimeFormatter or a plain object' );
		}

		if ( this.type === 'time' || config.calendar === null ) {
			this.calendar = null;
		} else {
			config.calendar = $.extend( {}, config.calendar, {
				formatter: this.formatter,
				widget: this,
				min: this.min,
				max: this.max
			} );
			this.calendar = new mw.widgets.datetime.CalendarWidget( config.calendar );
		}

		// Events
		this.$handle.on( {
			click: this.onHandleClick.bind( this )
		} );
		this.connect( this, {
			change: 'onChange'
		} );
		this.formatter.connect( this, {
			local: 'onChange'
		} );
		if ( this.calendar ) {
			this.calendar.connect( this, {
				change: 'onCalendarChange'
			} );
		}

		// Initialization
		this.setTabIndex( -1 );

		this.$fields.addClass( 'mw-widgets-datetime-dateTimeInputWidget-fields' );
		this.setupFields();

		this.$handle
			.addClass( 'mw-widgets-datetime-dateTimeInputWidget-handle' )
			.append( this.$icon, this.$indicator, this.$fields );

		this.$element
			.addClass( 'mw-widgets-datetime-dateTimeInputWidget' )
			.append( this.$handle );

		if ( this.calendar ) {
			this.$element.append( this.calendar.$element );
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.datetime.DateTimeInputWidget, OO.ui.InputWidget );
	OO.mixinClass( mw.widgets.datetime.DateTimeInputWidget, OO.ui.mixin.IconElement );
	OO.mixinClass( mw.widgets.datetime.DateTimeInputWidget, OO.ui.mixin.IndicatorElement );
	OO.mixinClass( mw.widgets.datetime.DateTimeInputWidget, OO.ui.mixin.PendingElement );

	/* Static properties */

	mw.widgets.datetime.DateTimeInputWidget.static.supportsSimpleLabel = false;

	/* Events */

	/* Methods */

	/**
	 * Get the currently focused field, if any
	 *
	 * @private
	 * @return {jQuery}
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.getFocusedField = function () {
		return this.$fields.find( this.getElementDocument().activeElement );
	};

	/**
	 * Convert a date string to a Date
	 *
	 * @private
	 * @param {string} value
	 * @return {Date|null}
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.parseDateValue = function ( value ) {
		var date, m;

		value = String( value );
		switch ( this.type ) {
			case 'date':
				value = value + 'T00:00:00Z';
				break;
			case 'time':
				value = '1970-01-01T' + value + 'Z';
				break;
		}
		m = /^(\d{4,})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.(\d{1,3}))?Z$/.exec( value );
		if ( m ) {
			if ( m[ 7 ] ) {
				while ( m[ 7 ].length < 3 ) {
					m[ 7 ] += '0';
				}
			} else {
				m[ 7 ] = 0;
			}
			date = new Date();
			date.setUTCFullYear( m[ 1 ], m[ 2 ] - 1, m[ 3 ] );
			date.setUTCHours( m[ 4 ], m[ 5 ], m[ 6 ], m[ 7 ] );
			if ( date.getTime() < -62167219200000 || date.getTime() > 253402300799999 ||
				date.getUTCFullYear() !== +m[ 1 ] ||
				date.getUTCMonth() + 1 !== +m[ 2 ] ||
				date.getUTCDate() !== +m[ 3 ] ||
				date.getUTCHours() !== +m[ 4 ] ||
				date.getUTCMinutes() !== +m[ 5 ] ||
				date.getUTCSeconds() !== +m[ 6 ] ||
				date.getUTCMilliseconds() !== +m[ 7 ]
			) {
				date = null;
			}
		} else {
			date = null;
		}

		return date;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.cleanUpValue = function ( value ) {
		var date, pad;

		if ( value === '' ) {
			return '';
		}

		if ( value instanceof Date ) {
			date = value;
		} else {
			date = this.parseDateValue( value );
		}

		if ( date instanceof Date ) {
			pad = function ( v, l ) {
				v = String( v );
				while ( v.length < l ) {
					v = '0' + v;
				}
				return v;
			};

			switch ( this.type ) {
				case 'date':
					value = pad( date.getUTCFullYear(), 4 ) +
						'-' + pad( date.getUTCMonth() + 1, 2 ) +
						'-' + pad( date.getUTCDate(), 2 );
					break;

				case 'time':
					value = pad( date.getUTCHours(), 2 ) +
						':' + pad( date.getUTCMinutes(), 2 ) +
						':' + pad( date.getUTCSeconds(), 2 ) +
						'.' + pad( date.getUTCMilliseconds(), 3 );
					value = value.replace( /\.?0+$/, '' );
					break;

				default:
					value = date.toISOString();
					break;
			}
		} else {
			value = '';
		}

		return value;
	};

	/**
	 * Get the value of the input as a Date object
	 *
	 * @return {Date|null}
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.getValueAsDate = function () {
		return this.parseDateValue( this.getValue() );
	};

	/**
	 * Set up the UI fields
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.setupFields = function () {
		var i, $field, spec, placeholder, sz, maxlength,
			spanValFunc = function ( v ) {
				if ( v === undefined ) {
					return this.data( 'mw-widgets-datetime-dateTimeInputWidget-value' );
				} else {
					v = String( v );
					this.data( 'mw-widgets-datetime-dateTimeInputWidget-value', v );
					if ( v === '' ) {
						v = this.data( 'mw-widgets-datetime-dateTimeInputWidget-placeholder' );
					}
					this.text( v );
					return this;
				}
			},
			reduceFunc = function ( k, v ) {
				maxlength = Math.max( maxlength, v );
			},
			disabled = this.isDisabled(),
			specs = this.formatter.getFieldSpec();

		this.$fields.empty();
		this.clearButton = null;
		this.fields = [];

		for ( i = 0; i < specs.length; i++ ) {
			spec = specs[ i ];
			if ( typeof spec === 'string' ) {
				$( '<span>' )
					.addClass( 'mw-widgets-datetime-dateTimeInputWidget-field' )
					.text( spec )
					.appendTo( this.$fields );
				continue;
			}

			placeholder = '';
			while ( placeholder.length < spec.size ) {
				placeholder += '_';
			}

			if ( spec.type === 'number' ) {
				// Numbers ''should'' be the same width. But we need some extra for
				// IE, apparently.
				sz = ( spec.size * 1.15 ) + 'ch';
			} else {
				// Add a little for padding
				sz = ( spec.size * 1.25 ) + 'ch';
			}
			if ( spec.editable && spec.type !== 'static' ) {
				if ( spec.type === 'boolean' || spec.type === 'toggleLocal' ) {
					$field = $( '<span>' )
						.attr( {
							tabindex: disabled ? -1 : 0
						} )
						.width( sz )
						.data( 'mw-widgets-datetime-dateTimeInputWidget-placeholder', placeholder );
					$field.on( {
						keydown: this.onFieldKeyDown.bind( this, $field ),
						focus: this.onFieldFocus.bind( this, $field ),
						click: this.onFieldClick.bind( this, $field ),
						'wheel mousewheel DOMMouseScroll': this.onFieldWheel.bind( this, $field )
					} );
					$field.val = spanValFunc;
				} else {
					maxlength = spec.size;
					if ( spec.intercalarySize ) {
						// eslint-disable-next-line no-restricted-properties
						$.each( spec.intercalarySize, reduceFunc );
					}
					$field = $( '<input>' ).attr( 'type', 'text' )
						.attr( {
							tabindex: disabled ? -1 : 0,
							size: spec.size,
							maxlength: maxlength
						} )
						.prop( {
							disabled: disabled,
							placeholder: placeholder
						} )
						.width( sz );
					$field.on( {
						keydown: this.onFieldKeyDown.bind( this, $field ),
						click: this.onFieldClick.bind( this, $field ),
						focus: this.onFieldFocus.bind( this, $field ),
						blur: this.onFieldBlur.bind( this, $field ),
						change: this.onFieldChange.bind( this, $field ),
						'wheel mousewheel DOMMouseScroll': this.onFieldWheel.bind( this, $field )
					} );
				}
				$field.addClass( 'mw-widgets-datetime-dateTimeInputWidget-editField' );
			} else {
				$field = $( '<span>' )
					.width( sz )
					.data( 'mw-widgets-datetime-dateTimeInputWidget-placeholder', placeholder );
				if ( spec.type !== 'static' ) {
					$field.prop( 'tabIndex', -1 );
					$field.on( 'focus', this.onFieldFocus.bind( this, $field ) );
				}
				if ( spec.type === 'static' ) {
					$field.text( spec.value );
				} else {
					$field.val = spanValFunc;
				}
			}

			this.fields.push( $field );
			$field
				.addClass( 'mw-widgets-datetime-dateTimeInputWidget-field' )
				.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec', spec )
				.appendTo( this.$fields );
		}

		if ( this.clearable ) {
			this.clearButton = new OO.ui.ButtonWidget( {
				classes: [ 'mw-widgets-datetime-dateTimeInputWidget-field', 'mw-widgets-datetime-dateTimeInputWidget-clearButton' ],
				framed: false,
				icon: 'trash',
				disabled: disabled
			} ).connect( this, {
				click: 'onClearClick'
			} );
			this.$fields.append( this.clearButton.$element );
		}

		this.updateFieldsFromValue();
	};

	/**
	 * Update the UI fields from the current value
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.updateFieldsFromValue = function () {
		var i, $field, spec, intercalary, sz,
			date = this.getValueAsDate();

		if ( date === null ) {
			this.components = null;

			for ( i = 0; i < this.fields.length; i++ ) {
				$field = this.fields[ i ];
				spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

				$field
					.removeClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid oo-ui-element-hidden' )
					.val( '' );

				if ( spec.intercalarySize ) {
					if ( spec.type === 'number' ) {
						// Numbers ''should'' be the same width. But we need some extra for
						// IE, apparently.
						$field.width( ( spec.size * 1.15 ) + 'ch' );
					} else {
						// Add a little for padding
						$field.width( ( spec.size * 1.15 ) + 'ch' );
					}
				}
			}

			this.setFlags( { invalid: this.required } );
		} else {
			this.components = this.formatter.getComponentsFromDate( date );
			intercalary = this.components.intercalary;

			for ( i = 0; i < this.fields.length; i++ ) {
				$field = this.fields[ i ];
				$field.removeClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid' );
				spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );
				if ( spec.type !== 'static' ) {
					$field.val( spec.formatValue( this.components[ spec.component ] ) );
				}
				if ( spec.intercalarySize ) {
					if ( intercalary && spec.intercalarySize[ intercalary ] !== undefined ) {
						sz = spec.intercalarySize[ intercalary ];
					} else {
						sz = spec.size;
					}
					$field.toggleClass( 'oo-ui-element-hidden', sz <= 0 );
					if ( spec.type === 'number' ) {
						// Numbers ''should'' be the same width. But we need some extra for
						// IE, apparently.
						this.fields[ i ].width( ( sz * 1.15 ) + 'ch' );
					} else {
						// Add a little for padding
						this.fields[ i ].width( ( sz * 1.15 ) + 'ch' );
					}
				}
			}

			this.setFlags( { invalid: date < this.min || date > this.max } );
		}

		this.$element.toggleClass( 'mw-widgets-datetime-dateTimeInputWidget-empty', date === null );
	};

	/**
	 * Update the value with data from the UI fields
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.updateValueFromFields = function () {
		var i, v, $field, spec, curDate, newDate,
			components = {},
			anyInvalid = false,
			anyEmpty = false,
			allEmpty = true;

		for ( i = 0; i < this.fields.length; i++ ) {
			$field = this.fields[ i ];
			spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );
			if ( spec.editable ) {
				$field.removeClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid' );
				v = $field.val();
				if ( v === '' ) {
					$field.addClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid' );
					anyEmpty = true;
				} else {
					allEmpty = false;
					v = spec.parseValue( v );
					if ( v === undefined ) {
						$field.addClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid' );
						anyInvalid = true;
					} else {
						components[ spec.component ] = v;
					}
				}
			}
		}

		if ( allEmpty ) {
			for ( i = 0; i < this.fields.length; i++ ) {
				this.fields[ i ].removeClass( 'mw-widgets-datetime-dateTimeInputWidget-invalid' );
			}
		} else if ( anyEmpty ) {
			anyInvalid = true;
		}

		if ( !anyInvalid ) {
			curDate = this.getValueAsDate();
			newDate = this.formatter.getDateFromComponents( components );
			if ( !curDate || !newDate || curDate.getTime() !== newDate.getTime() ) {
				this.setValue( newDate );
			}
		}
	};

	/**
	 * Handle change event
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onChange = function () {
		var date;

		this.updateFieldsFromValue();

		if ( this.calendar ) {
			date = this.getValueAsDate();
			this.calendar.setSelected( date );
			if ( date ) {
				this.calendar.setFocusedDate( date );
			}
		}
	};

	/**
	 * Handle clear button click event
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onClearClick = function () {
		this.blur();
		this.setValue( '' );
	};

	/**
	 * Handle click on the widget background
	 *
	 * @private
	 * @param {jQuery.Event} e Click event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onHandleClick = function () {
		this.focus();
	};

	/**
	 * Handle key down events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Key down event
	 * @return {boolean} False to cancel the default event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldKeyDown = function ( $field, e ) {
		var spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

		if ( !this.isDisabled() ) {
			switch ( e.which ) {
				case OO.ui.Keys.ENTER:
				case OO.ui.Keys.SPACE:
					if ( spec.type === 'boolean' ) {
						this.setValue(
							this.formatter.adjustComponent( this.getValueAsDate(), spec.component, 1, 'wrap' )
						);
						return false;
					} else if ( spec.type === 'toggleLocal' ) {
						this.formatter.toggleLocal();
					}
					break;

				case OO.ui.Keys.UP:
				case OO.ui.Keys.DOWN:
					if ( spec.type === 'toggleLocal' ) {
						this.formatter.toggleLocal();
					} else {
						this.setValue(
							this.formatter.adjustComponent( this.getValueAsDate(), spec.component,
								e.keyCode === OO.ui.Keys.UP ? -1 : 1, 'wrap' )
						);
					}
					if ( $field.is( ':input' ) ) {
						$field.select();
					}
					return false;
			}
		}
	};

	/**
	 * Handle focus events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Focus event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldFocus = function ( $field ) {
		var spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

		if ( !this.isDisabled() ) {
			if ( this.getValueAsDate() === null ) {
				this.setValue( this.formatter.getDefaultDate() );
			}
			if ( $field.is( ':input' ) ) {
				$field.select();
			}

			if ( this.calendar ) {
				this.calendar.toggle( !!spec.calendarComponent );
			}
		}
	};

	/**
	 * Handle click events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Click event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldClick = function ( $field ) {
		var spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

		if ( !this.isDisabled() ) {
			if ( spec.type === 'boolean' ) {
				this.setValue(
					this.formatter.adjustComponent( this.getValueAsDate(), spec.component, 1, 'wrap' )
				);
			} else if ( spec.type === 'toggleLocal' ) {
				this.formatter.toggleLocal();
			}
		}
	};

	/**
	 * Handle blur events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Blur event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldBlur = function ( $field ) {
		var v, date,
			spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

		this.updateValueFromFields();

		// Normalize
		date = this.getValueAsDate();
		if ( !date ) {
			$field.val( '' );
		} else {
			v = spec.formatValue( this.formatter.getComponentsFromDate( date )[ spec.component ] );
			if ( v !== $field.val() ) {
				$field.val( v );
			}
		}
	};

	/**
	 * Handle change events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Change event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldChange = function () {
		this.updateValueFromFields();
	};

	/**
	 * Handle wheel events on our field inputs.
	 *
	 * @private
	 * @param {jQuery} $field
	 * @param {jQuery.Event} e Change event
	 * @return {boolean} False to cancel the default event
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onFieldWheel = function ( $field, e ) {
		var delta = 0,
			spec = $field.data( 'mw-widgets-datetime-dateTimeInputWidget-fieldSpec' );

		if ( this.isDisabled() || !this.getFocusedField().length ) {
			return;
		}

		// Standard 'wheel' event
		if ( e.originalEvent.deltaMode !== undefined ) {
			this.sawWheelEvent = true;
		}
		if ( e.originalEvent.deltaY ) {
			delta = -e.originalEvent.deltaY;
		} else if ( e.originalEvent.deltaX ) {
			delta = e.originalEvent.deltaX;
		}

		// Non-standard events
		if ( !this.sawWheelEvent ) {
			if ( e.originalEvent.wheelDeltaX ) {
				delta = -e.originalEvent.wheelDeltaX;
			} else if ( e.originalEvent.wheelDeltaY ) {
				delta = e.originalEvent.wheelDeltaY;
			} else if ( e.originalEvent.wheelDelta ) {
				delta = e.originalEvent.wheelDelta;
			} else if ( e.originalEvent.detail ) {
				delta = -e.originalEvent.detail;
			}
		}

		if ( delta && spec ) {
			if ( spec.type === 'toggleLocal' ) {
				this.formatter.toggleLocal();
			} else {
				this.setValue(
					this.formatter.adjustComponent( this.getValueAsDate(), spec.component, delta < 0 ? -1 : 1, 'wrap' )
				);
			}
			return false;
		}
	};

	/**
	 * Handle calendar change event
	 *
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.onCalendarChange = function () {
		var curDate = this.getValueAsDate(),
			newDate = this.calendar.getSelected()[ 0 ];

		if ( newDate ) {
			if ( !curDate || newDate.getTime() !== curDate.getTime() ) {
				this.setValue( newDate );
			}
		}
	};

	/**
	 * @inheritdoc
	 * @private
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.getInputElement = function () {
		return $( '<input>' ).attr( 'type', 'hidden' );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.setDisabled = function ( disabled ) {
		mw.widgets.datetime.DateTimeInputWidget[ 'super' ].prototype.setDisabled.call( this, disabled );

		// Flag all our fields as disabled
		if ( this.$fields ) {
			this.$fields.find( 'input' ).prop( 'disabled', this.isDisabled() );
			this.$fields.find( '[tabindex]' ).attr( 'tabindex', this.isDisabled() ? -1 : 0 );
		}

		if ( this.clearButton ) {
			this.clearButton.setDisabled( disabled );
		}

		return this;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.focus = function () {
		if ( !this.getFocusedField().length ) {
			this.$fields.find( '.mw-widgets-datetime-dateTimeInputWidget-editField' ).first().focus();
		}
		return this;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.blur = function () {
		this.getFocusedField().blur();
		return this;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.DateTimeInputWidget.prototype.simulateLabelClick = function () {
		this.focus();
	};

}() );
