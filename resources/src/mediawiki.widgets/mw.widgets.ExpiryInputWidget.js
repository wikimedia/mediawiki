/*!
 * MediaWiki Widgets - ExpiryWidget class.
 *
 * @copyright 2018 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
/* global moment */
( function () {

	/**
	 * Creates a mw.widgets.ExpiryWidget object.
	 *
	 * @class mw.widgets.ExpiryWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 */
	mw.widgets.ExpiryWidget = function ( config ) {
		var RFC2822 = 'ddd, DD MMM YYYY HH:mm:ss [GMT]';

		// Config initialization
		config = $.extend( {}, config );

		this.relativeField = new config.RelativeInputClass( config.relativeInput );
		this.relativeField.$element.addClass( 'mw-widget-ExpiryWidget-relative' );

		// Parent constructor
		mw.widgets.ExpiryWidget.parent.call( this, config );

		// Properties
		this.inputSwitch = new OO.ui.ButtonSelectWidget( {
			tabIndex: -1,
			items: [
				new OO.ui.ButtonOptionWidget( {
					data: 'relative',
					icon: 'edit'
				} ),
				new OO.ui.ButtonOptionWidget( {
					data: 'date',
					icon: 'calendar'
				} )
			]
		} );
		this.dateTimeField = new mw.widgets.datetime.DateTimeInputWidget( {
			min: new Date(), // The selected date must at least be now.
			required: config.required
		} );

		// Initially hide the dateTime field.
		this.dateTimeField.toggle( false );
		// Initially set the relative input.
		this.inputSwitch.selectItemByData( 'relative' );

		// Events

		// Toggle the visible inputs.
		this.inputSwitch.on( 'choose', function ( event ) {
			switch ( event.getData() ) {
				case 'date':
					this.dateTimeField.toggle( true );
					this.relativeField.toggle( false );
					break;
				case 'relative':
					this.dateTimeField.toggle( false );
					this.relativeField.toggle( true );
					break;
			}
		}.bind( this ) );

		// When the date time field update, update the relative
		// field.
		this.dateTimeField.on( 'change', function ( value ) {
			var datetime;

			// Do not alter the visible input.
			if ( this.relativeField.isVisible() ) {
				return;
			}

			// If the value was cleared, do not attempt to parse it.
			if ( !value ) {
				this.relativeField.setValue( value );
				return;
			}

			datetime = moment( value );

			// If the datetime is invlaid for some reason, reset the relative field.
			if ( !datetime.isValid() ) {
				this.relativeField.setValue( undefined );
			}

			// Set the relative field value. The field only accepts English strings.
			this.relativeField.setValue( datetime.utc().locale( 'en' ).format( RFC2822 ) );
		}.bind( this ) );

		// When the relative field update, update the date time field if it's a
		// value that moment understands.
		this.relativeField.on( 'change', function ( event ) {
			var datetime;

			// Emit a change event for this widget.
			this.emit( 'change', event );

			// Do not alter the visible input.
			if ( this.dateTimeField.isVisible() ) {
				return;
			}

			// Parsing of free text field may fail, so always check if the date is
			// valid.
			datetime = moment( event );

			if ( datetime.isValid() ) {
				this.dateTimeField.setValue( datetime.utc().toISOString() );
			} else {
				this.dateTimeField.setValue( undefined );
			}
		}.bind( this ) );

		// Initialization
		this.$element
			.addClass( 'mw-widget-ExpiryWidget' )
			.addClass( 'mw-widget-ExpiryWidget-hasDatePicker' )
			.append(
				this.inputSwitch.$element,
				this.dateTimeField.$element,
				this.relativeField.$element
			);

		// Trigger an initial onChange.
		this.relativeField.emit( 'change', this.relativeField.getValue() );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.ExpiryWidget, OO.ui.Widget );

	/**
	 * @inheritdoc
	 */
	mw.widgets.ExpiryWidget.static.reusePreInfuseDOM = function ( node, config ) {
		var $relativeElement = $( node ).find( '.mw-widget-ExpiryWidget-relative' );

		config = mw.widgets.ExpiryWidget.parent.static.reusePreInfuseDOM( node, config );

		// eslint-disable-next-line no-jquery/no-class-state
		if ( $relativeElement.hasClass( 'oo-ui-textInputWidget' ) ) {
			config.RelativeInputClass = OO.ui.TextInputWidget;
		// eslint-disable-next-line no-jquery/no-class-state
		} else if ( $relativeElement.hasClass( 'mw-widget-selectWithInputWidget' ) ) {
			config.RelativeInputClass = mw.widgets.SelectWithInputWidget;
		}

		config.relativeInput = config.RelativeInputClass.static.reusePreInfuseDOM(
			$relativeElement,
			config.relativeInput
		);

		return config;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ExpiryWidget.static.gatherPreInfuseState = function ( node, config ) {
		var state = mw.widgets.ExpiryWidget.parent.static.gatherPreInfuseState( node, config );

		state.relativeInput = config.RelativeInputClass.static.gatherPreInfuseState(
			$( node ).find( '.mw-widget-ExpiryWidget-relative' ),
			config.relativeInput
		);

		return state;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ExpiryWidget.prototype.restorePreInfuseState = function ( state ) {
		mw.widgets.ExpiryWidget.parent.prototype.restorePreInfuseState.call( this, state );
		this.relativeField.restorePreInfuseState( state.relativeInput );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.ExpiryWidget.prototype.setDisabled = function ( disabled ) {
		mw.widgets.ExpiryWidget.parent.prototype.setDisabled.call( this, disabled );
		this.relativeField.setDisabled( disabled );

		if ( this.inputSwitch ) {
			this.inputSwitch.setDisabled( disabled );
		}

		if ( this.dateTimeField ) {
			this.dateTimeField.setDisabled( disabled );
		}
	};

	/**
	 * Gets the value of the widget.
	 *
	 * @return {string}
	 */
	mw.widgets.ExpiryWidget.prototype.getValue = function () {
		return this.relativeField.getValue();
	};

}() );
