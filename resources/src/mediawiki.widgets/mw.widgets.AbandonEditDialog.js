/*!
 * MediaWiki Widgets - AbandonEditDialog class.
 *
 * @copyright 2011-2018 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * Confirm dialog shown when the users is leaving an editor without saving
 *
 * @class
 * @extends OO.ui.MessageDialog
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
mw.widgets.AbandonEditDialog = function VeUiAbandonEditDialog( config ) {
	// Parent constructor
	mw.widgets.AbandonEditDialog.super.call( this, config );
};

/* Inheritance */

OO.inheritClass( mw.widgets.AbandonEditDialog, OO.ui.MessageDialog );

/* Static Properties */

mw.widgets.AbandonEditDialog.static.name = 'abandonedit';

mw.widgets.AbandonEditDialog.static.title =
	OO.ui.deferMsg( 'mw-widgets-abandonedit-title' );

mw.widgets.AbandonEditDialog.static.message =
	OO.ui.deferMsg( 'mw-widgets-abandonedit' );

mw.widgets.AbandonEditDialog.static.actions = [
	{ action: 'discard', label: OO.ui.deferMsg( 'mw-widgets-abandonedit-discard' ), flags: 'destructive' },
	{ action: 'keep', label: OO.ui.deferMsg( 'mw-widgets-abandonedit-keep' ), flags: [ 'primary', 'safe' ] }
];
