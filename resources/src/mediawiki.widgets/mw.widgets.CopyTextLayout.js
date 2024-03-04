/*!
 * MediaWiki Widgets - CopyTextLayout class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * Extends CopyTextLayout with MediaWiki notifications.
 *
 * @class
 * @extends OO.ui.CopyTextLayout
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @param {string} [config.successMessage] Success message,
 *  defaults to the {@link mw.Message} 'mw-widgets-copytextlayout-copy-success'.
 * @param {string} [config.failMessage] Failure message,
 *  defaults to the {@link mw.Message} 'mw-widgets-copytextlayout-copy-fail'.
 */
mw.widgets.CopyTextLayout = function MwWidgetsCopyTextLayout( config ) {
	// Parent constructor
	mw.widgets.CopyTextLayout.super.apply( this, arguments );

	this.successMessage = config.successMessage || mw.msg( 'mw-widgets-copytextlayout-copy-success' );
	this.failMessage = config.failMessage || mw.msg( 'mw-widgets-copytextlayout-copy-fail' );

	this.connect( this, { copy: 'onMwCopy' } );
};

/* Inheritence */

OO.inheritClass( mw.widgets.CopyTextLayout, OO.ui.CopyTextLayout );

/* Methods */

/**
 * Handle copy events.
 *
 * @param {boolean} copied
 */
mw.widgets.CopyTextLayout.prototype.onMwCopy = function ( copied ) {
	if ( copied ) {
		mw.notify( this.successMessage );
	} else {
		mw.notify( this.failMessage, { type: 'error' } );
	}
};
