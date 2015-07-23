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
	 * @class
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.TabIndexedElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} [precision='day'] Value precision to use, 'day' or 'month'
	 * @cfg {string|null} [value=null] Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'.
	 *     When null, defaults to current date.
	 */
	mw.widgets.CalendarWidget = function MWWCalendarWidget( config ) {
		// Config initialization
		config = config || {};

		// Parent constructor
		mw.widgets.CalendarWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.TabIndexedElement.call( this, $.extend( {}, config, { $tabIndexed: this.$element } ) );

		// Properties
		this.precision = config.precision || 'day';
		// Current value (selected date)
		this.value = null;
		// Current UI state (date and precision we're displaying right now)
		this.moment = null;
		this.displayLayer = this.getDisplayLayers()[ 0 ]; // 'month', 'year', 'decade'

		this.$header = $( '<div>' ).addClass( 'mw-widget-calendarWidget-header' );
		this.$bodyOuterWrapper = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body-outer-wrapper' );
		this.$bodyWrapper = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body-wrapper' );
		this.$body = $( '<div>' ).addClass( 'mw-widget-calendarWidget-body' );
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

		// Events
		this.labelButton.connect( this, { click: 'onUpButtonClick' } );
		this.upButton.connect( this, { click: 'onUpButtonClick' } );
		this.prevButton.connect( this, { click: 'onPrevButtonClick' } );
		this.nextButton.connect( this, { click: 'onNextButtonClick' } );
		this.$element.on( {
			focus: this.onFocus.bind( this ),
			mousedown: this.onClick.bind( this ),
			keydown: this.onKeyDown.bind( this )
		} );

		// Initialization
		this.$element
			.addClass( 'mw-widget-calendarWidget' )
			.append( this.$header, this.$bodyOuterWrapper.append( this.$bodyWrapper.append( this.$body ) ) );
		this.$header.append(
			this.prevButton.$element,
			this.nextButton.$element,
			this.upButton.$element,
			this.labelButton.$element
		);
		this.setValue( config.value !== undefined ? config.value : null );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.CalendarWidget, OO.ui.Widget );
	OO.mixinClass( mw.widgets.CalendarWidget, OO.ui.mixin.TabIndexedElement );

	/* Events */

	/**
	 * @event change
	 *
	 * A change event is emitted when the chosen date changes.
	 *
	 * @param {string} value Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'
	 */

	/* Methods */

	/**
	 * Get the format 'YYYY-MM-DD' or 'YYYY-MM', depending on precision.
	 *
	 * @private
	 * @returns {string} Format
	 */
	mw.widgets.CalendarWidget.prototype.getFormat = function () {
		return {
			day: 'YYYY-MM-DD',
			month: 'YYYY-MM'
		}[ this.precision ];
	};

	/**
	 * Get list of possible display layers.
	 *
	 * @private
	 * @returns {string[]} Layers
	 */
	mw.widgets.CalendarWidget.prototype.getDisplayLayers = function () {
		return [ 'month', 'year', 'decade' ].slice( this.precision === 'month' ? 1 : 0 );
	};

	/**
	 * Update the calendar.
	 *
	 * @private
	 * @param {string|null} [fade=null] Direction in which to fade out current calendar contents, 'previous',
	 *     'next' or 'up'
	 * @returns {string} Format
	 */
	mw.widgets.CalendarWidget.prototype.updateUI = function ( fade ) {
		var items, today, selected, currentMonth, currentYear, currentDay, i, needsFade,
			$bodyWrapper = this.$bodyWrapper;

		if (
			this.displayLayer === this.previousDisplayLayer &&
			this.previousMoment &&
			this.previousMoment.isSame( this.moment, this.precision === 'month' ? 'month' : 'day' )
		) {
			// Already displayed
			return;
		}

		items = [];
		if ( this.$oldBody ) {
			this.$oldBody.remove();
		}
		this.$oldBody = this.$body.addClass( 'mw-widget-calendarWidget-old-body' );
		// Clone without children
		this.$body = $( this.$body[0].cloneNode( false ) )
			.removeClass( 'mw-widget-calendarWidget-old-body' )
			.toggleClass( 'mw-widget-calendarWidget-body-month', this.displayLayer === 'month' )
			.toggleClass( 'mw-widget-calendarWidget-body-year', this.displayLayer === 'year' )
			.toggleClass( 'mw-widget-calendarWidget-body-decade', this.displayLayer === 'decade' );

		today = moment();
		selected = moment( this.getValue(), this.getFormat() );

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
			// Display in columns rather than rows
			items = [ 0, 6, 1, 7, 2, 8, 3, 9, 4, 10, 5, 11 ].map( function ( i ) {
				return items[ i ];
			} );
			break;

		case 'decade': // actually 20 years
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
		} else if ( this.displayLayer === 'decade' ) {
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

		this.$body.on( 'click', this.onBodyClick.bind( this ) );
	};

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

	mw.widgets.CalendarWidget.prototype.onPrevButtonClick = function () {
		switch ( this.displayLayer ) {
		case 'month':
			this.moment.subtract( 1, 'month' );
			break;
		case 'year':
			this.moment.subtract( 1, 'year' );
			break;
		case 'decade':
			this.moment.subtract( 20, 'years' );
			break;
		}
		this.updateUI( 'previous' );
	};

	mw.widgets.CalendarWidget.prototype.onNextButtonClick = function () {
		switch ( this.displayLayer ) {
		case 'month':
			this.moment.add( 1, 'month' );
			break;
		case 'year':
			this.moment.add( 1, 'year' );
			break;
		case 'decade':
			this.moment.add( 20, 'years' );
			break;
		}
		this.updateUI( 'next' );
	};

	mw.widgets.CalendarWidget.prototype.onBodyClick = function ( e ) {
		var
			previousMoment = moment( this.moment ),
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
			this.setValueFromMoment();
			this.updateUI(
				this.precision === 'day' && this.moment.isBefore( previousMoment, 'month' ) ? 'previous' :
					this.precision === 'day' && this.moment.isAfter( previousMoment, 'month' ) ? 'next' : null
			);
		} else {
			// One layer down
			this.displayLayer = layers[ currentLayer - 1 ];
			this.updateUI( 'down' );
		}
	};

	/**
	 * Set the value.
	 *
	 * @param {string|null} [value=null] Day or month date, in the format 'YYYY-MM-DD' or 'YYYY-MM'.
	 *     When null, defaults to current date. When invalid, the value is not changed.
	 */
	mw.widgets.CalendarWidget.prototype.setValue = function ( value ) {
		var mom = value !== null ? moment( value, this.getFormat() ) : moment();
		if ( mom.isValid() ) {
			this.moment = mom;
			this.setValueFromMoment();
			this.displayLayer = this.getDisplayLayers()[ 0 ];
			this.updateUI();
		}
	};

	/**
	 * Reset the user interface of this widget to reflect selected value.
	 */
	mw.widgets.CalendarWidget.prototype.resetUI = function () {
		this.moment = moment( this.getValue(), this.getFormat() );
		this.displayLayer = this.getDisplayLayers()[ 0 ];
		this.updateUI();
	};

	/**
	 * Set the value from moment object.
	 *
	 * @private
	 * @return {number} -1 is new value is lesser, 1 if new value is greater, 0 if equal
	 */
	mw.widgets.CalendarWidget.prototype.setValueFromMoment = function () {
		var newValue = moment( this.moment ).locale( 'en' ).format( this.getFormat() );
		if ( this.value !== newValue ) {
			this.value = newValue;
			this.emit( 'change', this.value );
		}
	};

	/**
	 * Get current value, in the format 'YYYY-MM-DD' or 'YYYY-MM', depending on precision. Digits will
	 * not be localised.
	 *
	 * @returns {string} Date string
	 */
	mw.widgets.CalendarWidget.prototype.getValue = function () {
		return this.value;
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
		var updateInDirection = false;
		if ( !this.isDisabled() ) {
			switch ( e.which ) {
			case OO.ui.Keys.LEFT:
				this.moment.subtract( 1, this.precision === 'month' ? 'month' : 'day' );
				updateInDirection = 'previous';
				break;
			case OO.ui.Keys.RIGHT:
				this.moment.add( 1, this.precision === 'month' ? 'month' : 'day' );
				updateInDirection = 'next';
				break;
			case OO.ui.Keys.UP:
				this.moment.subtract( 1, this.precision === 'month' ? 'month' : 'week' );
				updateInDirection = 'previous';
				break;
			case OO.ui.Keys.DOWN:
				this.moment.add( 1, this.precision === 'month' ? 'month' : 'week' );
				updateInDirection = 'next';
				break;
			case OO.ui.Keys.PAGEUP:
				this.moment.subtract( 1, this.precision === 'month' ? 'year' : 'month' );
				updateInDirection = 'previous';
				break;
			case OO.ui.Keys.PAGEDOWN:
				this.moment.add( 1, this.precision === 'month' ? 'year' : 'month' );
				updateInDirection = 'next';
				break;
			}

			if ( updateInDirection ) {
				this.displayLayer = this.getDisplayLayers()[ 0 ];
				this.setValueFromMoment();
				this.updateUI( updateInDirection );
				return false;
			}
		}
	};

}( jQuery, mediaWiki ) );
