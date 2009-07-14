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


mwAddOnloadHook( function(){
	mwEditPageHelper.init();	
});
var mwEditPageHelper = {	
	init:function(){
		var _this = this;
		//@@todo check for new version of toolbar and add properly:
		if(typeof $j.fn.toolbar == 'undefined'){			
			//add the add-media-wizard button for old toolbar: 
			$j('#toolbar').append('<img style="cursor:pointer" id="btn-add-media-wiz" src="' + mv_skin_img_path + 'Button_add_media.png">');
			$j('#btn-add-media-wiz').addMediaWiz( 
					mwAddMediaConfig 
			);		
		}
	}
}
