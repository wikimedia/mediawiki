( function () {
	const Vue = require( 'vue' );
	const LabelOnboarding = require( './LabelOnboarding.vue' );
	const mountPoint = document.body.appendChild( document.createElement( 'div' ) );
	Vue.createMwApp( LabelOnboarding ).mount( mountPoint );
}() );
