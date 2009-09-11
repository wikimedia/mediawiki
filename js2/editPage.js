/*
 * JS2-style replacement for MediaWiki edit.js
 * @todo port the rest of it to here
 */

// Setup configuration vars
if( !mwAddMediaConfig )
	var mwAddMediaConfig = {
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
	mwEditPageHelper.init();
});
var mwEditPageHelper = {
	init: function() {
		var _this = this;
		//@@todo check for a new version of the toolbar and via toolbar API

		// Kind of tricky, it would be nice to use a "loader" call here to avoid concurrency issues.
		if( typeof $j.wikiEditor != 'undefined' ) {
			setTimeout( function() {
				$j( '.wikiEditor-ui [rel=file]' ).unbind().addMediaWiz(
					mwAddMediaConfig
				);
			}, 100 );
		} else {
			// Add the add-media-wizard button for old toolbar:
			$j( '#toolbar' ).append( '<img style="cursor:pointer" id="btn-add-media-wiz" src="' +
				mv_skin_img_path + 'Button_add_media.png">' );
			$j( '#btn-add-media-wiz' ).addMediaWiz(
				mwAddMediaConfig
			);
		}

		// Add to new toolbar (need to use api)
		/*$j( '[rel=insert] tool-file' ).addMediaWiz(
				mwAddMediaConfig
		);*/
	}
}
