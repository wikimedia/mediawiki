/*
 * js2 style replacement for mediaWiki edit.js 
 */
//setup configuration vars: 
if(!mwAddMediaConfig)
	var mwAddMediaConfig = {
			'profile':'mediawiki_edit',			
			'target_textbox': '#wpTextbox1',			
			//note selections in the textbox will take over the default query
			'default_query': wgTitle,
			'target_title':wgPageName,			
			//here we can setup the conten provider overides
			'cpconfig': {},					
			
			//the local wiki api url: 
			'local_wiki_api_url': wgServer + wgScriptPath + '/api.php'
		};


js2AddOnloadHook( function(){
	mwEditPageHelper.init();	
});
var mwEditPageHelper = {	
	init:function(){
		var _this = this;
		//@@todo check for new version of toolbar and via toolbar api:
		
		//kind of tricky would be nice to use a "loader" call here to avoid concurancy issues. 
		if( typeof $j.wikiEditor != 'undefined' ){
			setTimeout(function(){
				$j('.wikiEditor-ui [rel=file]').addMediaWiz( 
					mwAddMediaConfig 
				);		
			},100 );
		}else{			
			//add the add-media-wizard button for old toolbar: 
			$j('#toolbar').append('<img style="cursor:pointer" id="btn-add-media-wiz" src="' + mv_skin_img_path + 'Button_add_media.png">');
			$j('#btn-add-media-wiz').addMediaWiz( 
					mwAddMediaConfig 
			);		
		}
				
		//add to new toolbar (need to use api) 
		/*$j('[rel=insert] tool-file').addMediaWiz( 
				mwAddMediaConfig 
		);*/				
	}
}
