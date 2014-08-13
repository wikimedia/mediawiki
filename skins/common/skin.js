(function( $, mw ) {
var config = mw.config.get( 'wgSkinConfig' );

function Skin( config ) {
	this.config = config;
}

Skin.prototype = {
	getWatchstar: function() {
		if ( this.config.watchstar ) {
			return $( this.config.watchstar );
		} else {
			mw.log( 'Skin needs to define watchstar config property' );
			// Fallback for all the skins
			return $( '.mw-watchlink a, a.mw-watchlink, ' +
					'#ca-watch a, #ca-unwatch a, #mw-unwatch-link1, ' +
					'#mw-unwatch-link2, #mw-watch-link2, #mw-watch-link1' )
					// Allowing people to add inline animated links is a little scary
					.filter( ':not( #bodyContent *, #content * )' );
		}
	}
};

if ( !config ) {
	mw.log( 'Skin needs to use wgSkinConfig' );
	config = {};
}
mw.skin = new Skin( config );
} ( jQuery, mediaWiki ) );

