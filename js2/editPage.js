/*
 * JS2-style replacement for MediaWiki edit.js
 * (right now it just supports the toolbar) 
 */

// Setup configuration vars (if not set already) 
if( !mwAddMediaConfig )
	var mwAddMediaConfig = {};

//The default editPage AMW config
var defaultAddMediaConfig = {
		'profile': 'mediawiki_edit',
		'target_textbox': '#wpTextbox1',
		// Note: selections in the textbox will take over the default query
		'default_query': wgTitle,
		'target_title': wgPageName,
		// Here we can setup the content provider overrides
		'enabled_cps':['wiki_commons'],   
		// The local wiki API URL:
		'local_wiki_api_url': wgServer + wgScriptPath + '/api.php'
};
js2AddOnloadHook( function() {
	mwEditPageHelper.init(
		$j.extend( true, defaultAddMediaConfig, mwAddMediaConfig )
	);
});
var mwEditPageHelper = {
	init: function( amwConf ) {
		var _this = this;		
		// kind of tricky, it would be nice to use run on ready "loader" call here
		if( typeof $j.wikiEditor != 'undefined' ) {
			setTimeout( function() {
				$j( '.wikiEditor-ui [rel=file]' ).unbind().addMediaWiz(
					amwConf
				);
			}, 100 );
		} else {
			// Add the add-media-wizard button for old toolbar:
			$j( '#toolbar' ).append( '<img style="cursor:pointer" id="btn-add-media-wiz" src="' +
				mv_skin_img_path + 'Button_add_media.png">' );
			$j( '#btn-add-media-wiz' ).addMediaWiz(
				amwConf
			);
		}

		// Add to new toolbar (need to use api)
		/*$j( '[rel=insert] tool-file' ).addMediaWiz(
				mwAddMediaConfig
		);*/
	}
}
