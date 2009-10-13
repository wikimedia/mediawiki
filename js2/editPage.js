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
	var amwConf = $j.extend( true, defaultAddMediaConfig, mwAddMediaConfig );
	// kind of tricky, it would be nice to use run on ready "loader" call here
	if( typeof $j.wikiEditor != 'undefined' ) {		
			$j( 'textarea#wpTextbox1' ).bind( 'wikiEditor-toolbar-buildSection-main',
		    function( e, section ) {
		        if ( typeof section.groups.insert.tools.file !== 'undefined' ) {
		            section.groups.insert.tools.file.action = {
		                'type': 'callback',
		                'execute': function() { 
		                	js_log('click add media wiz');
		                	$j.addMediaWiz( amwConf );
		                }
		            };
		        }
		    }
		);
	}else{
		//add to the old-toolbar
		if( $j('#btn-add-media-wiz').length == 0 ){
			$j( '#toolbar' ).append( '<img style="cursor:pointer" id="btn-add-media-wiz" src="' +
				mv_skin_img_path + 'Button_add_media.png">' );
			$j( '#btn-add-media-wiz' ).addMediaWiz(
				amwConf
			);
		}
	}
});
