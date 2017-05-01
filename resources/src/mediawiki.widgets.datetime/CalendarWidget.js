( function ( $, mw ) {

	/**
	 * CalendarWidget displays a calendar that can be used to select a date. It
	 * uses {@link mw.widgets.datetime.DateTimeFormatter DateTimeFormatter} to get the details of
	 * the calendar.
	 *
	 * This widget is mainly intended to be used as a popup from a
	 * {@link mw.widgets.datetime.DateTimeInputWidget DateTimeInputWidget}, but may also be used
	 * standalone.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.TabIndexedElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {Object|mw.widgets.datetime.DateTimeFormatter} [formatter={}] Configuration options for
	 *  mw.widgets.datetime.ProlepticGregorianDateTimeFormatter, or an mw.widgets.datetime.DateTimeFormatter
	 *  instance to use.
	 * @cfg {OO.ui.Widget|null} [widget=null] Widget associated with the calendar.
	 *  Specifying this configures the calendar to be used as a popup from the
	 *  specified widget (e.g. absolute positioning, automatic hiding when clicked
	 *  outside).
	 * @cfg {Date|null} [min=null] Minimum allowed date
	 * @cfg {Date|null} [max=null] Maximum allowed date
	 * @cfg {Date} [focusedDate] Initially focused date.
	 * @cfg {Date|Date[]|null} [selected=null] Selected date(s).
	 */
	mw.widgets.datetime.CalendarWidget = function MwWidgetsDatetimeCalendarWidget( config ) {
		var $colgroup, $headTR, headings, i;

		// Configuration initialization
		config = $.extend( {
			min: null,
			max: null,
			focusedDate: new Date(),
			selected: null,
			formatter: {}
		}, config );

		// Parent constructor
		mw.widgets.datetime.CalendarWidget[ 'super' ].call( this, config );

		// Mixin constructors
		OO.ui.mixin.TabIndexedElement.call( this, $.extend( {}, config, { $tabIndexed: this.$element } ) );

		// Properties
		if ( config.min instanceof Date && config.min.getTime() >= -62167219200000 ) {
			this.min = config.min;
		} else {
			this.min = new Date( -62167219200000 ); // 0000-01-01T00:00:00.000Z
		}
		if ( config.max instanceof Date && config.max.getTime() <= 253402300799999 ) {
			this.max = config.max;
		} else {
			this.max = new Date( 253402300799999 ); // 9999-12-31T12:59:59.999Z
		}

		if ( config.focusedDate instanceof Date ) {
			this.focusedDate = config.focusedDate;
		} else {
			this.focusedDate = new Date();
		}

		this.selected = [];

		if ( config.formatter instanceof mw.widgets.datetime.DateTimeFormatter ) {
			this.formatter = config.formatter;
		} else if ( $.isPlainObject( config.formatter ) ) {
			this.formatter = new mw.widgets.datetime.ProlepticGregorianDateTimeFormatter( config.formatter );
		} else {
			throw new Error( '"formatter" must be an mw.widgets.datetime.DateTimeFormatter or a plain object' );
		}

		this.calendarData = null;

		this.widget = config.widget;
		this.$widget = config.widget ? config.widget.$element : null;
		this.onDocumentMouseDownHandler = this.onDocumentMouseDown.bind( this );

		this.$head = $( '<div>' );
		this.$header = $( '<span>' );
		this.$table = $( '<table>' );
		this.cols = [];
		this.colNullable = [];
		this.headings = [];
		this.$tableBody = $( '<tbody>' );
		this.rows = [];
		this.buttons = {};
		this.minWidth = 1;
		this.daysPerWeek = 0;

		// Events
		this.$element.on( {
			keydown: this.onKeyDown.bind( this )
		} );
		this.formatter.connect( this, {
			local: 'onLocalChange'
		} );
		if ( this.$widget ) {
			this.checkFocusHandler = this.checkFocus.bind( this );
			this.$element.on( {
				focusout: this.onFocusOut.bind( this )
			} );
			this.$widget.on( {
				focusout: this.onFocusOut.bind( this )
			} );
		}

		// Initialization
		this.$head
			.addClass( 'mw-widgets-datetime-calendarWidget-heading' )
			.append(
				new OO.ui.ButtonWidget( {
					icon: 'previous',
					framed: false,
					classes: [ 'mw-widgets-datetime-calendarWidget-previous' ],
					tabIndex: -1
				} ).connect( this, { click: 'onPrevClick' } ).$element,
				new OO.ui.ButtonWidget( {
					icon: 'next',
					framed: false,
					classes: [ 'mw-widgets-datetime-calendarWidget-next' ],
					tabIndex: -1
				} ).connect( this, { click: 'onNextClick' } ).$element,
				this.$header
			);
		$colgroup = $( '<colgroup>' );
		$headTR = $( '<tr>' );
		this.$table
			.addClass( 'mw-widgets-datetime-calendarWidget-grid' )
			.append( $colgroup )
			.append( $( '<thead>' ).append( $headTR ) )
			.append( this.$tableBody );

		headings = this.formatter.getCalendarHeadings();
		for ( i = 0; i < headings.length; i++ ) {
			this.cols[ i ] = $( '<col>' );
			this.headings[ i ] = $( '<th>' );
			this.colNullable[ i ] = headings[ i ] === null;
			if ( headings[ i ] !== null ) {
				this.headings[ i ].text( headings[ i ] );
				this.minWidth = Math.max( this.minWidth, headings[ i ].length );
				this.daysPerWeek++;
			}
			$colgroup.append( this.cols[ i ] );
			$headTR.append( this.headings[ i ] );
		}

		this.setSelected( config.selected );
		this.$element
			.addClass( 'mw-widgets-datetime-calendarWidget' )
			.append( this.$head, this.$table );

		if ( this.widget ) {
			this.$element.addClass( 'mw-widgets-datetime-calendarWidget-dependent' );

			// Initially hidden - using #toggle may cause errors if subclasses override toggle with methods
			// that reference properties not initialized at that time of parent class construction
			// TODO: Find a better way to handle post-constructor setup
			this.visible = false;
			this.$element.addClass( 'oo-ui-element-hidden' );
		} else {
			this.updateUI();
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.datetime.CalendarWidget, OO.ui.Widget );
	OO.mixinClass( mw.widgets.datetime.CalendarWidget, OO.ui.mixin.TabIndexedElement );

	/* Events */

	/**
	 * A `change` event is emitted when the selected dates change
	 *
	 * @event change
	 */

	/**
	 * A `focusChange` event is emitted when the focused date changes
	 *
	 * @event focusChange
	 */

	/**
	 * A `page` event is emitted when the current "month" changes
	 *
	 * @event page
	 */

	/* Methods */

	/**
	 * Return the current selected dates
	 *
	 * @return {Date[]}
	 */
	mw.widgets.datetime.CalendarWidget.prototype.getSelected = function () {
		return this.selected;
	};

	// eslint-disable-next-line valid-jsdoc
	/**
	 * Set the selected dates
	 *
	 * @param {Date|Date[]|null} dates
	 * @fires change
	 * @chainable
	 */
	mw.widgets.datetime.CalendarWidget.prototype.setSelected = function ( dates ) {
		var i, changed = false;

		if ( dates instanceof Date ) {
			dates = [ dates ];
		} else if ( Array.isArray( dates ) ) {
			dates = $.grep( dates, function ( dt ) { return dt instanceof Date; } );
			dates.sort();
		} else {
			dates = [];
		}

		if ( this.selected.length !== dates.length ) {
			changed = true;
		} else {
			for ( i = 0; i < dates.length; i++ ) {
				if ( dates[ i ].getTime() !== this.selected[ i ].getTime() ) {
					changed = true;
					break;
				}
			}
		}

		if ( changed ) {
			this.selected = dates;
			this.emit( 'change', dates );
			this.updateUI();
		}

		return this;
	};

	/**
	 * Return the currently-focused date
	 *
	 * @return {Date}
	 */
	mw.widgets.datetime.CalendarWidget.prototype.getFocusedDate = function () {
		return this.focusedDate;
	};

	// eslint-disable-next-line valid-jsdoc
	/**
	 * Set the currently-focused date
	 *
	 * @param {Date} date
	 * @fires page
	 * @chainable
	 */
	mw.widgets.datetime.CalendarWidget.prototype.setFocusedDate = function ( date ) {
		var changePage = false,
			updateUI = false;

		if ( this.focusedDate.getTime() === date.getTime() ) {
			return this;
		}

		if ( !this.formatter.sameCalendarGrid( this.focusedDate, date ) ) {
			changePage = true;
			updateUI = true;
		} else if (
			!this.formatter.timePartIsEqual( this.focusedDate, date ) ||
			!this.formatter.datePartIsEqual( this.focusedDate, date )
		) {
			updateUI = true;
		}

		this.focusedDate = date;
		this.emit( 'focusChanged', this.focusedDate );
		if ( changePage ) {
			this.emit( 'page', date );
		}
		if ( updateUI ) {
			this.updateUI();
		}

		return this;
	};

	/**
	 * Adjust a date
	 *
	 * @protected
	 * @param {Date} date Date to adjust
	 * @param {string} component Component: 'month', 'week', or 'day'
	 * @param {number} delta Integer, usually -1 or 1
	 * @param {boolean} [enforceRange=true] Whether to enforce this.min and this.max
	 * @return {Date}
	 */
	mw.widgets.datetime.CalendarWidget.prototype.adjustDate = function ( date, component, delta ) {
		var newDate,
			data = this.calendarData;

		if ( !data ) {
			return date;
		}

		switch ( component ) {
			case 'month':
				newDate = this.formatter.adjustComponent( date, data.monthComponent, delta, 'overflow' );
				break;

			case 'week':
				if ( data.weekComponent === undefined ) {
					newDate = this.formatter.adjustComponent(
						date, data.dayComponent, delta * this.daysPerWeek, 'overflow' );
				} else {
					newDate = this.formatter.adjustComponent( date, data.weekComponent, delta, 'overflow' );
				}
				break;

			case 'day':
				newDate = this.formatter.adjustComponent( date, data.dayComponent, delta, 'overflow' );
				break;

			default:
				throw new Error( 'Unknown component' );
		}

		while ( newDate < this.min ) {
			newDate = this.formatter.adjustComponent( newDate, data.dayComponent, 1, 'overflow' );
		}
		while ( newDate > this.max ) {
			newDate = this.formatter.adjustComponent( newDate, data.dayComponent, -1, 'overflow' );
		}

		return newDate;
	};

	/**
	 * Update the user interface
	 *
	 * @protected
	 */
	mw.widgets.datetime.CalendarWidget.prototype.updateUI = function () {
		var r, c, row, day, k, $cell,
			width = this.minWidth,
			nullCols = [],
			focusedDate = this.getFocusedDate(),
			selected = this.getSelected(),
			datePartIsEqual = this.formatter.datePartIsEqual.bind( this.formatter ),
			isSelected = function ( dt ) {
				return datePartIsEqual( this, dt );
			};

		this.calendarData = this.formatter.getCalendarData( focusedDate );

		this.$header.text( this.calendarData.header );

		for ( c = 0; c < this.colNullable.length; c++ ) {
			nullCols[ c ] = this.colNullable[ c ];
			if ( nullCols[ c ] ) {
				for ( r = 0; r < this.calendarData.rows.length; r++ ) {
					if ( this.calendarData.rows[ r ][ c ] ) {
						nullCols[ c ] = false;
						break;
					}
				}
			}
		}

		this.$tableBody.children().detach();
		for ( r = 0; r < this.calendarData.rows.length; r++ ) {
			if ( !this.rows[ r ] ) {
				this.rows[ r ] = $( '<tr>' );
			} else {
				this.rows[ r ].children().detach();
			}
			this.$tableBody.append( this.rows[ r ] );
			row = this.calendarData.rows[ r ];
			for ( c = 0; c < row.length; c++ ) {
				day = row[ c ];
				if ( day === null ) {
					k = 'empty-' + r + '-' + c;
					if ( !this.buttons[ k ] ) {
						this.buttons[ k ] = $( '<td>' );
					}
					$cell = this.buttons[ k ];
					$cell.toggleClass( 'oo-ui-element-hidden', nullCols[ c ] );
				} else {
					k = ( day.extra ? day.extra : '' ) + day.display;
					width = Math.max( width, day.display.length );
					if ( !this.buttons[ k ] ) {
						this.buttons[ k ] = new OO.ui.ButtonWidget( {
							$element: $( '<td>' ),
							classes: [
								'mw-widgets-datetime-calendarWidget-cell',
								day.extra ? 'mw-widgets-datetime-calendarWidget-extra' : ''
							],
							framed: true,
							label: day.display,
							tabIndex: -1
						} );
						this.buttons[ k ].connect( this, { click: [ 'onDayClick', this.buttons[ k ] ] } );
					}
					this.buttons[ k ]
						.setData( day.date )
						.setDisabled( day.date < this.min || day.date > this.max );
					$cell = this.buttons[ k ].$element;
					$cell.toggleClass( 'mw-widgets-datetime-calendarWidget-focused',
						this.formatter.datePartIsEqual( focusedDate, day.date ) );
					$cell.toggleClass( 'mw-widgets-datetime-calendarWidget-selected',
						selected.some( isSelected, day.date ) );
				}
				this.rows[ r ].append( $cell );
			}
		}

		for ( c = 0; c < this.cols.length; c++ ) {
			if ( nullCols[ c ] ) {
				this.cols[ c ].width( 0 );
			} else {
				this.cols[ c ].width( width + 'em' );
			}
			this.cols[ c ].toggleClass( 'oo-ui-element-hidden', nullCols[ c ] );
			this.headings[ c ].toggleClass( 'oo-ui-element-hidden', nullCols[ c ] );
		}
	};

	/**
	 * Handles formatter 'local' flag changing
	 *
	 * @protected
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onLocalChange = function () {
		if ( this.formatter.localChangesDatePart( this.getFocusedDate() ) ) {
			this.emit( 'page', this.getFocusedDate() );
		}

		this.updateUI();
	};

	/**
	 * Handles previous button click
	 *
	 * @protected
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onPrevClick = function () {
		this.setFocusedDate( this.adjustDate( this.getFocusedDate(), 'month', -1 ) );
		if ( !this.$widget || OO.ui.contains( this.$element[ 0 ], document.activeElement, true ) ) {
			this.$element.focus();
		}
	};

	/**
	 * Handles next button click
	 *
	 * @protected
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onNextClick = function () {
		this.setFocusedDate( this.adjustDate( this.getFocusedDate(), 'month', 1 ) );
		if ( !this.$widget || OO.ui.contains( this.$element[ 0 ], document.activeElement, true ) ) {
			this.$element.focus();
		}
	};

	/**
	 * Handles day button click
	 *
	 * @protected
	 * @param {OO.ui.ButtonWidget} $button
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onDayClick = function ( $button ) {
		this.setFocusedDate( $button.getData() );
		this.setSelected( [ $button.getData() ] );
		if ( !this.$widget || OO.ui.contains( this.$element[ 0 ], document.activeElement, true ) ) {
			this.$element.focus();
		}
	};

	/**
	 * Handles document mouse down events.
	 *
	 * @protected
	 * @param {jQuery.Event} e Mouse down event
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onDocumentMouseDown = function ( e ) {
		if ( this.$widget &&
			!OO.ui.contains( this.$element[ 0 ], e.target, true ) &&
			!OO.ui.contains( this.$widget[ 0 ], e.target, true )
		) {
			this.toggle( false );
		}
	};

	/**
	 * Handles key presses.
	 *
	 * @protected
	 * @param {jQuery.Event} e Key down event
	 * @return {boolean} False to cancel the default event
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onKeyDown = function ( e ) {
		var focusedDate = this.getFocusedDate();

		if ( !this.isDisabled() ) {
			switch ( e.which ) {
				case OO.ui.Keys.ENTER:
				case OO.ui.Keys.SPACE:
					this.setSelected( [ focusedDate ] );
					return false;

				case OO.ui.Keys.LEFT:
					this.setFocusedDate( this.adjustDate( focusedDate, 'day', -1 ) );
					return false;

				case OO.ui.Keys.RIGHT:
					this.setFocusedDate( this.adjustDate( focusedDate, 'day', 1 ) );
					return false;

				case OO.ui.Keys.UP:
					this.setFocusedDate( this.adjustDate( focusedDate, 'week', -1 ) );
					return false;

				case OO.ui.Keys.DOWN:
					this.setFocusedDate( this.adjustDate( focusedDate, 'week', 1 ) );
					return false;

				case OO.ui.Keys.PAGEUP:
					this.setFocusedDate( this.adjustDate( focusedDate, 'month', -1 ) );
					return false;

				case OO.ui.Keys.PAGEDOWN:
					this.setFocusedDate( this.adjustDate( focusedDate, 'month', 1 ) );
					return false;
			}
		}
	};

	/**
	 * Handles focusout events in dependent mode
	 *
	 * @private
	 */
	mw.widgets.datetime.CalendarWidget.prototype.onFocusOut = function () {
		setTimeout( this.checkFocusHandler );
	};

	/**
	 * When we or our widget lost focus, check if the calendar should be hidden.
	 *
	 * @private
	 */
	mw.widgets.datetime.CalendarWidget.prototype.checkFocus = function () {
		var containers = [ this.$element[ 0 ], this.$widget[ 0 ] ],
			activeElement = document.activeElement;

		if ( !activeElement || !OO.ui.contains( containers, activeElement, true ) ) {
			this.toggle( false );
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.datetime.CalendarWidget.prototype.toggle = function ( visible ) {
		var change;

		visible = ( visible === undefined ? !this.visible : !!visible );
		change = visible !== this.isVisible();

		// Parent method
		mw.widgets.datetime.CalendarWidget[ 'super' ].prototype.toggle.call( this, visible );

		if ( change ) {
			if ( visible ) {
				// Auto-hide
				if ( this.$widget ) {
					this.getElementDocument().addEventListener(
						'mousedown', this.onDocumentMouseDownHandler, true
					);
				}
				this.updateUI();
			} else {
				this.getElementDocument().removeEventListener(
					'mousedown', this.onDocumentMouseDownHandler, true
				);
			}
		}

		return this;
	};

}( jQuery, mediaWiki ) );
