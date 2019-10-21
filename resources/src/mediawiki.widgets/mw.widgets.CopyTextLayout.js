/*!
 * MediaWiki Widgets - CopyTextLayout class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * An action field layout containing some readonly text and a button to copy
	 * it to the clipboard.
	 *
	 * @class
	 * @extends OO.ui.ActionFieldLayout
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string} copyText Text to copy, can also be provided as textInput.value
	 * @cfg {Object} textInput Config for text input
	 * @cfg {Object} button Config for button
	 * @cfg {string} successMessage Success message,
	 *  defaults to 'mw-widgets-copytextlayout-copy-success'.
	 * @cfg {string} failMessage Failure message,
	 *  defaults to 'mw-widgets-copytextlayout-copy-fail'.
	 */
	mw.widgets.CopyTextLayout = function MwWidgetsCopyTextLayout( config ) {
		var TextClass;
		config = config || {};

		// Properties
		TextClass = config.multiline ? OO.ui.MultilineTextInputWidget : OO.ui.TextInputWidget;
		this.textInput = new TextClass( $.extend( {
			value: config.copyText,
			readOnly: true
		}, config.textInput ) );
		this.button = new OO.ui.ButtonWidget( $.extend( {
			label: mw.msg( 'mw-widgets-copytextlayout-copy' ),
			icon: 'articles'
		}, config.button ) );
		this.successMessage = config.successMessage || mw.msg( 'mw-widgets-copytextlayout-copy-success' );
		this.failMessage = config.failMessage || mw.msg( 'mw-widgets-copytextlayout-copy-fail' );

		// Parent constructor
		mw.widgets.CopyTextLayout.super.call( this, this.textInput, this.button, config );

		// HACK: Remove classes which connect widgets when using
		// a multiline text input. TODO: This should be handled in OOUI.
		if ( config.multiline ) {
			this.$input.removeClass( 'oo-ui-actionFieldLayout-input' );
			this.$button
				.removeClass( 'oo-ui-actionFieldLayout-button' )
				.addClass( 'mw-widget-copyTextLayout-multiline-button' );
		}

		// Events
		this.button.connect( this, { click: 'onButtonClick' } );
		this.textInput.$input.on( 'focus', this.onInputFocus.bind( this ) );

		this.$element.addClass( 'mw-widget-copyTextLayout' );
	};

	/* Inheritence */

	OO.inheritClass( mw.widgets.CopyTextLayout, OO.ui.ActionFieldLayout );

	/* Methods */

	/**
	 * Handle button click events
	 *
	 * @fires copy
	 */
	mw.widgets.CopyTextLayout.prototype.onButtonClick = function () {
		var copied;

		this.selectText();

		try {
			copied = document.execCommand( 'copy' );
		} catch ( e ) {
			copied = false;
		}
		if ( copied ) {
			mw.notify( this.successMessage );
		} else {
			mw.notify( this.failMessage, { type: 'error' } );
		}

		this.emit( 'copy', copied );
	};

	/**
	 * Handle text widget focus events
	 */
	mw.widgets.CopyTextLayout.prototype.onInputFocus = function () {
		if ( !this.selecting ) {
			this.selectText();
		}
	};

	/**
	 * Select the text to copy
	 */
	mw.widgets.CopyTextLayout.prototype.selectText = function () {
		var input = this.textInput.$input[ 0 ],
			scrollTop = input.scrollTop,
			scrollLeft = input.scrollLeft;

		this.selecting = true;
		this.textInput.select();
		this.selecting = false;

		// Restore scroll position
		input.scrollTop = scrollTop;
		input.scrollLeft = scrollLeft;
	};

}() );
