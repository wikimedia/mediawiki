/*!
 * MediaWiki Widgets – CalendarWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
/*global moment */
( function ( $, mw ) {

	/**
	 * Creates an mw.widgets.CalendarWidget object.
	 *
	 * You will most likely want to use mw.widgets.DateInputWidget instead of CalendarWidget directly.
	 *
	 * @class
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.TabIndexedElement
	 * @mixins OO.ui.mixin.FloatableElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {boolean} [lazyInitOnToggle=false] Don't build most of the interface until
	 *     `.toggle( true )` is called. Meant to be used when the calendar is not immediately visible.
	 * @cfg {string} [precision='day'] Date precision to use, 'day' or 'month'
	 * @cfg {string|null} [date=null] Day or month date (depending on `precision`), in the format
	 *     'YYYY-MM-DD' or 'YYYY-MM'. When null, the calendar will show today's date, but not select
	 *     it.
	 */
	mw.widgets.CalendarWidget = function MWWCalendarWidget( config ) {
		// Config initialization
		config = config || {};

		// Parent constructor
		mw.widgets.CalendarWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.TabIndexedElement.call( this, $.extend( {}, config, { $tabIndexed: this.$element } ) );
		OO.ui.mixin.FloatableElement.call( this, config );

		// Properties
		this.lazyInitOnToggle = !!config.lazyInitOnToggle;
		this.precision = config.precision || 'day';
		// Currently selected date (day or month)
		this.date = null;
		// Current UI state (date and precision we're displaying right now)
		this.moment = null;
		this.displayLayer = this.getDisplayLayers()[ 0 ]; // 'month', 'year', 'duodecade'

		this.$header = $( '<div>' ).addClass( 'mw-widget-calendarWidget-header' );
		this.$bodyOuterWrapper = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body-outer-wrapper' );
		this.$bodyWrapper = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body-wrapper' );
		this.$body = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body' );

		// Events
		this.$element.on( {
			focus: this.onFocus.bind( this ),
			mousedown: this.onClick.bind( this ),
			keydown: this.onKeyDown.bind( this )
		} );

		// Initialization
		this.$element
			.addClass( 'mw-widget-calendarWidget' )
			.append( this.$header, this.$bodyOuterWrapper.append( this.$bodyWrapper.append( this.$body ) ) );
		if ( !this.lazyInitOnToggle ) {
			this.buildHeaderButtons();
		}
		this.setDate( config.date !== undefined ? config.date : null );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.CalendarWidget, OO.ui.Widget );
	OO.mixinClass( mw.widgets.CalendarWidget, OO.ui.mixin.TabIndexedElement );
	OO.mixinClass( mw.widgets.CalendarWidget, OO.ui.mixin.FloatableElement );

	/* Events */

	/**
	 * @event change
	 *
	 * A change event is emitted when the chosen date changes.
	 *
	 * @param {string} date Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'
	 */

	/* Methods */

	/**
	 * Get the date format ('YYYY-MM-DD' or 'YYYY-MM', depending on precision), which is used
	 * internally and for dates accepted by #setDate and returned by #getDate.
	 *
	 * @private
	 * @return {string} Format
	 */
	mw.widgets.CalendarWidget.prototype.getDateFormat = function () {
		return {
			day: 'YYYY-MM-DD',
			month: 'YYYY-MM'
		}[ this.precision ];
	};

	/**
	 * Get the date precision this calendar uses, 'day' or 'month'.
	 *
	 * @private
	 * @return {string} Precision, 'day' or 'month'
	 */
	mw.widgets.CalendarWidget.prototype.getPrecision = function () {
		return this.precision;
	};

	/**
	 * Get list of possible display layers.
	 *
	 * @private
	 * @return {string[]} Layers
	 */
	mw.widgets.CalendarWidget.prototype.getDisplayLayers = function () {
		return [ 'month', 'year', 'duodecade' ].slice( this.precision === 'month' ? 1 : 0 );
	};

	/**
	 * Update the calendar.
	 *
	 * @private
	 * @param {string|null} [fade=null] Direction in which to fade out current calendar contents,
	 *     'previous', 'next', 'up' or 'down'; or 'auto', which has the same result as 'previous' or
	 *     'next' depending on whether the current date is later or earlier than the previous.
	 */
	mw.widgets.CalendarWidget.prototype.updateUI = function ( fade ) {
		var items, today, selected, currentMonth, currentYear, currentDay, i, needsFade,
			$bodyWrapper = this.$bodyWrapper;

		if ( this.lazyInitOnToggle ) {
			// We're being called from the constructor and not being shown yet, do nothing
			return;
		}

		if (
			this.displayLayer === this.previousDisplayLayer &&
			this.date === this.previousDate &&
			this.previousMoment &&
			this.previousMoment.isSame( this.moment, this.precision === 'month' ? 'month' : 'day' )
		) {
			// Already displayed
			return;
		}

		if ( fade === 'auto' ) {
			if ( !this.previousMoment ) {
				fade = null;
			} else if ( this.previousMoment.isBefore( this.moment, this.precision === 'month' ? 'month' : 'day' ) ) {
				fade = 'next';
			} else if ( this.previousMoment.isAfter( this.moment, this.precision === 'month' ? 'month' : 'day' ) ) {
				fade = 'previous';
			} else {
				fade = null;
			}
		}

		items = [];
		if ( this.$oldBody ) {
			this.$oldBody.remove();
		}
		this.$oldBody = this.$body.addClass( 'mw-widget-calendarWidget-old-body' );
		// Clone without children
		this.$body = $( this.$body[ 0 ].cloneNode( false ) )
			.removeClass( 'mw-widget-calendarWidget-old-body' )
			.toggleClass( 'mw-widget-calendarWidget-body-month', this.displayLayer === 'month' )
			.toggleClass( 'mw-widget-calendarWidget-body-year', this.displayLayer === 'year' )
			.toggleClass( 'mw-widget-calendarWidget-body-duodecade', this.displayLayer === 'duodecade' );

		today = moment();
		selected = moment( this.getDate(), this.getDateFormat() );

		switch ( this.displayLayer ) {
		case 'month':
			this.labelButton.setLabel( this.moment.format( 'MMMM YYYY' ) );
			this.upButton.toggle( true );

			// First week displayed is the first week spanned by the month, unless it begins on Monday, in
			// which case first week displayed is the previous week. This makes the calendar "balanced"
			// and also neatly handles 28-day February sometimes spanning only 4 weeks.
			currentDay = moment( this.moment ).startOf( 'month' ).subtract( 1, 'day' ).startOf( 'week' );

			// Day-of-week labels. Localisation-independent: works with weeks starting on Saturday, Sunday
			// or Monday.
			for ( i = 0; i < 7; i++ ) {
				items.push(
					$( '<div>' )
						.addClass( 'mw-widget-calendarWidget-day-heading' )
						.text( currentDay.format( 'dd' ) )
				);
				currentDay.add( 1, 'day' );
			}
			currentDay.subtract( 7, 'days' );

			// Actual calendar month. Always displays 6 weeks, for consistency (months can span 4 to 6
			// weeks).
			for ( i = 0; i < 42; i++ ) {
				items.push(
					$( '<div>' )
						.addClass( 'mw-widget-calendarWidget-item mw-widget-calendarWidget-day' )
						.toggleClass( 'mw-widget-calendarWidget-day-additional', !currentDay.isSame( this.moment, 'month' ) )
						.toggleClass( 'mw-widget-calendarWidget-day-today', currentDay.isSame( today, 'day' ) )
						.toggleClass( 'mw-widget-calendarWidget-item-selected', currentDay.isSame( selected, 'day' ) )
						.text( currentDay.format( 'D' ) )
						.data( 'date', currentDay.date() )
						.data( 'month', currentDay.month() )
						.data( 'year', currentDay.year() )
				);
				currentDay.add( 1, 'day' );
			}
			break;

		case 'year':
			this.labelButton.setLabel( this.moment.format( 'YYYY' ) );
			this.upButton.toggle( true );

			currentMonth = moment( this.moment ).startOf( 'year' );
			for ( i = 0; i < 12; i++ ) {
				items.push(
					$( '<div>' )
						.addClass( 'mw-widget-calendarWidget-item mw-widget-calendarWidget-month' )
						.toggleClass( 'mw-widget-calendarWidget-item-selected', currentMonth.isSame( selected, 'month' ) )
						.text( currentMonth.format( 'MMMM' ) )
						.data( 'month', currentMonth.month() )
				);
				currentMonth.add( 1, 'month' );
			}
			// Shuffle the array to display months in columns rather than rows.
			items = [
				items[ 0 ], items[ 6 ],      //  | January  | July      |
				items[ 1 ], items[ 7 ],      //  | February | August    |
				items[ 2 ], items[ 8 ],      //  | March    | September |
				items[ 3 ], items[ 9 ],      //  | April    | October   |
				items[ 4 ], items[ 10 ],     //  | May      | November  |
				items[ 5 ], items[ 11 ]      //  | June     | December  |
			];
			break;

		case 'duodecade':
			this.labelButton.setLabel( null );
			this.upButton.toggle( false );

			currentYear = moment( { year: Math.floor( this.moment.year() / 20 ) * 20 } );
			for ( i = 0; i < 20; i++ ) {
				items.push(
					$( '<div>' )
						.addClass( 'mw-widget-calendarWidget-item mw-widget-calendarWidget-year' )
						.toggleClass( 'mw-widget-calendarWidget-item-selected', currentYear.isSame( selected, 'year' ) )
						.text( currentYear.format( 'YYYY' ) )
						.data( 'year', currentYear.year() )
				);
				currentYear.add( 1, 'year' );
			}
			break;
		}

		this.$body.append.apply( this.$body, items );

		$bodyWrapper
			.removeClass( 'mw-widget-calendarWidget-body-wrapper-fade-up' )
			.removeClass( 'mw-widget-calendarWidget-body-wrapper-fade-down' )
			.removeClass( 'mw-widget-calendarWidget-body-wrapper-fade-previous' )
			.removeClass( 'mw-widget-calendarWidget-body-wrapper-fade-next' );

		needsFade = this.previousDisplayLayer !== this.displayLayer;
		if ( this.displayLayer === 'month' ) {
			needsFade = needsFade || !this.moment.isSame( this.previousMoment, 'month' );
		} else if ( this.displayLayer === 'year' ) {
			needsFade = needsFade || !this.moment.isSame( this.previousMoment, 'year' );
		} else if ( this.displayLayer === 'duodecade' ) {
			needsFade = needsFade || (
				Math.floor( this.moment.year() / 20 ) * 20 !==
					Math.floor( this.previousMoment.year() / 20 ) * 20
			);
		}

		if ( fade && needsFade ) {
			this.$oldBody.find( '.mw-widget-calendarWidget-item-selected' )
				.removeClass( 'mw-widget-calendarWidget-item-selected' );
			if ( fade === 'previous' || fade === 'up' ) {
				this.$body.insertBefore( this.$oldBody );
			} else if ( fade === 'next' || fade === 'down' ) {
				this.$body.insertAfter( this.$oldBody );
			}
			setTimeout( function () {
				$bodyWrapper.addClass( 'mw-widget-calendarWidget-body-wrapper-fade-' + fade );
			}.bind( this ), 0 );
		} else {
			this.$oldBody.replaceWith( this.$body );
		}

		this.previousMoment = moment( this.moment );
		this.previousDisplayLayer = this.displayLayer;
		this.previousDate = this.date;

		this.$body.on( 'click', this.onBodyClick.bind( this ) );
	};

	/**
	 * Construct and display buttons to navigate the calendar.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.buildHeaderButtons = function () {
		this.labelButton = new OO.ui.ButtonWidget( {
			tabIndex: -1,
			label: '',
			framed: false,
			classes: [ 'mw-widget-calendarWidget-labelButton' ]
		} );
		this.upButton = new OO.ui.ButtonWidget( {
			tabIndex: -1,
			framed: false,
			icon: 'collapse',
			classes: [ 'mw-widget-calendarWidget-upButton' ]
		} );
		this.prevButton = new OO.ui.ButtonWidget( {
			tabIndex: -1,
			framed: false,
			icon: 'previous',
			classes: [ 'mw-widget-calendarWidget-prevButton' ]
		} );
		this.nextButton = new OO.ui.ButtonWidget( {
			tabIndex: -1,
			framed: false,
			icon: 'next',
			classes: [ 'mw-widget-calendarWidget-nextButton' ]
		} );

		this.labelButton.connect( this, { click: 'onUpButtonClick' } );
		this.upButton.connect( this, { click: 'onUpButtonClick' } );
		this.prevButton.connect( this, { click: 'onPrevButtonClick' } );
		this.nextButton.connect( this, { click: 'onNextButtonClick' } );

		this.$header.append(
			this.prevButton.$element,
			this.nextButton.$element,
			this.upButton.$element,
			this.labelButton.$element
		);
	};

	/**
	 * Handle click events on the "up" button, switching to less precise view.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.onUpButtonClick = function () {
		var
			layers = this.getDisplayLayers(),
			currentLayer = layers.indexOf( this.displayLayer );
		if ( currentLayer !== layers.length - 1 ) {
			// One layer up
			this.displayLayer = layers[ currentLayer + 1 ];
			this.updateUI( 'up' );
		} else {
			this.updateUI();
		}
	};

	/**
	 * Handle click events on the "previous" button, switching to previous pane.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.onPrevButtonClick = function () {
		switch ( this.displayLayer ) {
		case 'month':
			this.moment.subtract( 1, 'month' );
			break;
		case 'year':
			this.moment.subtract( 1, 'year' );
			break;
		case 'duodecade':
			this.moment.subtract( 20, 'years' );
			break;
		}
		this.updateUI( 'previous' );
	};

	/**
	 * Handle click events on the "next" button, switching to next pane.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.onNextButtonClick = function () {
		switch ( this.displayLayer ) {
		case 'month':
			this.moment.add( 1, 'month' );
			break;
		case 'year':
			this.moment.add( 1, 'year' );
			break;
		case 'duodecade':
			this.moment.add( 20, 'years' );
			break;
		}
		this.updateUI( 'next' );
	};

	/**
	 * Handle click events anywhere in the body of the widget, which contains the matrix of days,
	 * months or years to choose. Maybe change the pane or switch to more precise view, depending on
	 * what gets clicked.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.onBodyClick = function ( e ) {
		var
			$target = $( e.target ),
			layers = this.getDisplayLayers(),
			currentLayer = layers.indexOf( this.displayLayer );
		if ( $target.data( 'year' ) !== undefined ) {
			this.moment.year( $target.data( 'year' ) );
		}
		if ( $target.data( 'month' ) !== undefined ) {
			this.moment.month( $target.data( 'month' ) );
		}
		if ( $target.data( 'date' ) !== undefined ) {
			this.moment.date( $target.data( 'date' ) );
		}
		if ( currentLayer === 0 ) {
			this.setDateFromMoment();
			this.updateUI( 'auto' );
		} else {
			// One layer down
			this.displayLayer = layers[ currentLayer - 1 ];
			this.updateUI( 'down' );
		}
	};

	/**
	 * Set the date.
	 *
	 * @param {string|null} [date=null] Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'.
	 *     When null, the calendar will show today's date, but not select it. When invalid, the date
	 *     is not changed.
	 */
	mw.widgets.CalendarWidget.prototype.setDate = function ( date ) {
		var mom = date !== null ? moment( date, this.getDateFormat() ) : moment();
		if ( mom.isValid() ) {
			this.moment = mom;
			if ( date !== null ) {
				this.setDateFromMoment();
			} else if ( this.date !== null ) {
				this.date = null;
				this.emit( 'change', this.date );
			}
			this.displayLayer = this.getDisplayLayers()[ 0 ];
			this.updateUI();
		}
	};

	/**
	 * Reset the user interface of this widget to reflect selected date.
	 */
	mw.widgets.CalendarWidget.prototype.resetUI = function () {
		this.moment = this.getDate() !== null ? moment( this.getDate(), this.getDateFormat() ) : moment();
		this.displayLayer = this.getDisplayLayers()[ 0 ];
		this.updateUI();
	};

	/**
	 * Set the date from moment object.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.setDateFromMoment = function () {
		// Switch to English locale to avoid number formatting. We want the internal value to be
		// '2015-07-24' and not '٢٠١٥-٠٧-٢٤' even if the UI language is Arabic.
		var newDate = moment( this.moment ).locale( 'en' ).format( this.getDateFormat() );
		if ( this.date !== newDate ) {
			this.date = newDate;
			this.emit( 'change', this.date );
		}
	};

	/**
	 * Get current date, in the format 'YYYY-MM-DD' or 'YYYY-MM', depending on precision. Digits will
	 * not be localised.
	 *
	 * @return {string|null} Date string
	 */
	mw.widgets.CalendarWidget.prototype.getDate = function () {
		return this.date;
	};

	/**
	 * Handle focus events.
	 *
	 * @private
	 */
	mw.widgets.CalendarWidget.prototype.onFocus = function () {
		this.displayLayer = this.getDisplayLayers()[ 0 ];
		this.updateUI( 'down' );
	};

	/**
	 * Handle mouse click events.
	 *
	 * @private
	 * @param {jQuery.Event} e Mouse click event
	 */
	mw.widgets.CalendarWidget.prototype.onClick = function ( e ) {
		if ( !this.isDisabled() && e.which === 1 ) {
			// Prevent unintended focussing
			return false;
		}
	};

	/**
	 * Handle key down events.
	 *
	 * @private
	 * @param {jQuery.Event} e Key down event
	 */
	mw.widgets.CalendarWidget.prototype.onKeyDown = function ( e ) {
		var
			/*jshint -W024*/
			dir = OO.ui.Element.static.getDir( this.$element ),
			/*jshint +W024*/
			nextDirectionKey = dir === 'ltr' ? OO.ui.Keys.RIGHT : OO.ui.Keys.LEFT,
			prevDirectionKey = dir === 'ltr' ? OO.ui.Keys.LEFT : OO.ui.Keys.RIGHT,
			changed = true;

		if ( !this.isDisabled() ) {
			switch ( e.which ) {
			case prevDirectionKey:
				this.moment.subtract( 1, this.precision === 'month' ? 'month' : 'day' );
				break;
			case nextDirectionKey:
				this.moment.add( 1, this.precision === 'month' ? 'month' : 'day' );
				break;
			case OO.ui.Keys.UP:
				this.moment.subtract( 1, this.precision === 'month' ? 'month' : 'week' );
				break;
			case OO.ui.Keys.DOWN:
				this.moment.add( 1, this.precision === 'month' ? 'month' : 'week' );
				break;
			case OO.ui.Keys.PAGEUP:
				this.moment.subtract( 1, this.precision === 'month' ? 'year' : 'month' );
				break;
			case OO.ui.Keys.PAGEDOWN:
				this.moment.add( 1, this.precision === 'month' ? 'year' : 'month' );
				break;
			default:
				changed = false;
				break;
			}

			if ( changed ) {
				this.displayLayer = this.getDisplayLayers()[ 0 ];
				this.setDateFromMoment();
				this.updateUI( 'auto' );
				return false;
			}
		}
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.CalendarWidget.prototype.toggle = function ( visible ) {
		if ( this.lazyInitOnToggle && visible ) {
			this.lazyInitOnToggle = false;
			this.buildHeaderButtons();
			this.updateUI();
		}

		// Parent method
		mw.widgets.CalendarWidget.parent.prototype.toggle.call( this, visible );

		if ( this.$floatableContainer ) {
			this.togglePositioning( this.isVisible() );
		}

		return this;
	};

}( jQuery, mediaWiki ) );
