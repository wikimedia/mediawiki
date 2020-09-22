/*!
 * OOUI v0.40.3
 * https://www.mediawiki.org/wiki/OOUI
 *
 * Copyright 2011–2020 OOUI Team and other contributors.
 * Released under the MIT license
 * http://oojs.mit-license.org
 *
 * Date: 2020-09-02T15:42:49Z
 */
( function ( OO ) {

'use strict';

/**
 * @class
 * @extends OO.ui.Theme
 *
 * @constructor
 */
OO.ui.ApexTheme = function OoUiApexTheme() {
	// Parent constructor
	OO.ui.ApexTheme.super.call( this );
};

/* Setup */

OO.inheritClass( OO.ui.ApexTheme, OO.ui.Theme );

/* Methods */

/**
 * @inheritdoc
 */
OO.ui.ApexTheme.prototype.getDialogTransitionDuration = function () {
	return 250;
};

/* Instantiation */

OO.ui.theme = new OO.ui.ApexTheme();

}( OO ) );

//# sourceMappingURL=oojs-ui-apex.js.map.json