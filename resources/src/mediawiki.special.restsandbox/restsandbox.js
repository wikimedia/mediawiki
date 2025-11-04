/*!
 * JavaScript for Special:RestSandbox
 */
( function () {
	const SwaggerUI = require( './../../lib/swagger-ui/swagger-ui-bundle.js' );
	const SwaggerUIStandalonePreset = require( './../../lib/swagger-ui/swagger-ui-standalone-preset.js' );

	const uiConfig = {
		// eslint-disable-next-line camelcase
		dom_id: '#mw-restsandbox-swagger-ui',
		presets: [
			SwaggerUI.presets.apis,
			SwaggerUIStandalonePreset
		],
		// specUrl is set dynamically by SpecialRestSandbox
		url: mw.config.get( 'specUrl' ),
		deepLinking: true
	};

	window.ui = SwaggerUI( uiConfig );
}() );
