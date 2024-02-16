( function () {

	/**
	 * Provides access to HTMLForm OOUI classes.
	 *
	 * @namespace mw.htmlform
	 */
	mw.htmlform = {};

	/**
	 * Allows custom data specific to HTMLFormField to be set for OOUI forms. This picks up the
	 * extra config from a matching PHP widget (defined in HTMLFormElement.php) when constructed using
	 * OO.ui.infuse().
	 *
	 * Currently only supports passing 'cond-state' data.
	 *
	 * @classdesc HTMLForm Element class.
	 * @class
	 * @param {Object} [config] Configuration options
	 * @param {Object<string,string[]>} [config.condState] typically corresponds to a data-cond-state attribute
	 * that is found on HTMLForm elements and used during
	 * {@link Hooks~'htmlform.enhance' htmlform.enhance}. For more information on the format see
	 * the private function conditionParse in resources/src/mediawiki.htmlform/cond-state.js.
	 */
	mw.htmlform.Element = function ( config ) {
		// Configuration initialization
		config = config || {};

		// Properties
		this.condState = config.condState;
	};

	/**
	 * Create a FieldLayout class.
	 *
	 * @class
	 * @extends {OO.ui.FieldLayout}
	 * @classdesc FieldLayout class. Mixes in HTMLForm Element class.
	 * @memberof mw.htmlform
	 * @param {Object} config
	 */
	mw.htmlform.FieldLayout = function ( config ) {
		// Parent constructor
		mw.htmlform.FieldLayout.super.call( this, config );
		// Mixin constructors
		mw.htmlform.Element.call( this, config );
	};
	OO.inheritClass( mw.htmlform.FieldLayout, OO.ui.FieldLayout );
	OO.mixinClass( mw.htmlform.FieldLayout, mw.htmlform.Element );

	/**
	 * Create an ActionFieldLayout class.
	 *
	 * @class
	 * @extends {OO.ui.ActionFieldLayout}
	 * @classdesc FieldLayout class. Mixes in HTMLForm Element class.
	 * @memberof mw.htmlform
	 * @param {Object} config
	 */
	mw.htmlform.ActionFieldLayout = function ( config ) {
		// Parent constructor
		mw.htmlform.ActionFieldLayout.super.call( this, config );
		// Mixin constructors
		mw.htmlform.Element.call( this, config );
	};
	OO.inheritClass( mw.htmlform.ActionFieldLayout, OO.ui.ActionFieldLayout );
	OO.mixinClass( mw.htmlform.ActionFieldLayout, mw.htmlform.Element );

}() );
