/*
 * a library for doing remote media searches
 *
 * initial targeted archives are:
	the local wiki
	wikimedia commons
	metavid
	and archive.org
 */

loadGM({
	"mwe-add_media_wizard" : "Add media wizard",
	"mwe-media_search" : "Media search",
	"rsd_box_layout" : "Box layout",
	"rsd_list_layout" : "List layout",
	"rsd_results_desc" : "Results $1 to $2",
	"rsd_results_desc_total" : "Results $1 to $2 of $3",
	"rsd_results_next" : "next",
	"rsd_results_prev" : "previous",
	"rsd_no_results" : "No search results for <b>$1<\/b>",
	"mwe-upload_tab" : "Upload",
	"rsd_layout" : "Layout:",
	"rsd_resource_edit" : "Edit resource: $1",
	"mwe-resource_description_page" : "Resource description page",
	"mwe-link" : "link",
	"rsd_local_resource_title" : "Local resource title",
	"rsd_do_insert" : "Do insert",
	"mwe-cc_title" : "Creative Commons",
	"mwe-cc_by_title" : "Attribution",
	"mwe-cc_nc_title" : "Noncommercial",
	"mwe-cc_nd_title" : "No Derivative Works",
	"mwe-cc_sa_title" : "Share Alike",
	"mwe-cc_pd_title" : "Public Domain",
	"mwe-unknown_license" : "Unknown license",
	"mwe-no_import_by_url" : "This user or wiki <b>cannot<\/b> import assets from remote URLs.<p>Do you need to login?<\/p><p>Is upload_by_url permission set for you?<br \/>Does the wiki have <a href=\"http:\/\/www.mediawiki.org\/wiki\/Manual:$wgAllowCopyUploads\">$wgAllowCopyUploads<\/a> enabled?<\/p>",
	"mwe-results_from" : "Results from <a href=\"$1\" target=\"_new\" >$2<\/a>",
	"mwe-missing_desc_see_source" : "This asset is missing a description. Please see the [$1 original source] and help describe it.",
	"rsd_config_error" : "Add media wizard configuration error: $1",
	"mwe-your_recent_uploads" : "Your recent uploads to $1",
	"mwe-upload_a_file" : "Upload a new file to $1",
	"mwe-resource_page_desc" : "Resource page description:",
	"mwe-edit_resource_desc" : "Edit wiki text resource description:",
	"mwe-local_resource_title" : "Local resource title:",
	"mwe-watch_this_page" : "Watch this page",
	"mwe-do_import_resource" : "Import resource",
	"mwe-update_preview" : "Update preview",
	"mwe-cancel_import" : "Cancel import",
	"mwe-importing_asset" : "Importing asset",
	"mwe-preview_insert_resource" : "Preview insert of resource: $1",
	"mwe-checking-resource" : "Checking for resource",
	"mwe-resource-needs-import" : "Resource $1 needs to be imported to $2",
	"mwe-ftype-svg" : "SVG vector file",
	"mwe-ftype-jpg" : "JPEG image file",
	"mwe-ftype-png" : "PNG image file",
	"mwe-ftype-oga" : "Ogg audio file",
	"mwe-ftype-ogg" : "Ogg video file",
	"mwe-ftype-unk" : "Unknown file format"
});

var default_remote_search_options = {
	'profile':'mediawiki_edit',
	'target_container':null, //the div that will hold the search interface

	'target_invocation': null, //the button or link that will invoke the search interface

	'default_provider_id':'all', //all or one of the content_providers ids

	'caret_pos':null,
	'local_wiki_api_url':null,

	//can be 'api', 'autodetect', 'remote_link'
	'import_url_mode': 'api',

	'target_title':null,
	
	//edit tools (can be an array of tools or keyword 'all')
	'enabled_tools' : 'all',
	

	'target_textbox':null,
	'target_render_area': null, //where output render should go:
	'instance_name': null, //a globally accessible callback instance name
	'default_query':null, //default search query
	//specific to sequence profile
	'p_seq':null,
	'cFileNS':'File', //What is the canonical namespace prefix for images
					  //@@todo (should get that from the api or in-page vars)
	
	'upload_api_target': 'local', // can be local or the url or remote
	'upload_api_name' : null,
	'upload_api_proxy_frame': null, //a page that will request mw.proxy.server
	
	'enabled_cps':'all', //can be keyword 'all' or an array of enabled content provider keys
		
	'disp_item':null //sets the default display item:
}

if(typeof wgServer == 'undefined')
	wgServer = '';
if(typeof wgScriptPath == 'undefined')
	wgScriptPath = '';
if(typeof stylepath == 'undefined')
	stylepath = '';

/*
 *	base remoteSearch Driver interface
 */
var remoteSearchDriver = function(iObj){
	return this.init( iObj );
}
remoteSearchDriver.prototype = {
	results_cleared:false,
	//here we define the set of possible media content providers:
	main_search_options:{
		'selprovider':{
			'title': 'Select Providers'
		},
		'advanced_search':{
			'title': 'Advanced Options'
		}
	},	
	/** the default content providers list.
	 *
	 * (should be note that special tabs like "upload" and "combined" don't go into the content providers list:
	 * @note do not use double underscore in content providers names (used for id lookup)
	 *
	 * @@todo we will want to load more per user-preference and per category lookup
	 */
	content_providers:{
		/*content_providers documentation:
		 *  @@todo we should move the bulk of the configuration to each file
		 *

			@enabled: whether the search provider can be selected
			@checked: whether the search provider will show up as seleatable tab (todo: user preference)
			@d:	   default: if the current cp should be displayed (only one should be the default)
			@title:   the title of the search provider
			@desc:	   can use html... todo: need to localize
			@api_url: the url to query against given the library type:
			@lib:	   the search library to use corresponding to the
						search object ie: 'mediaWiki' = new mediaWikiSearchSearch()
			@tab_img: the tab image (if set to false use title text)
						if === "ture" use standard location skin/images/{cp_id}_tab.png
						if === string use as url for image

			@linkback_icon default is: /wiki/skins/common/images/magnify-clip.png

			//domain insert: two modes: simple config or domain list:
			@local : if the content provider assets need to be imported or not.
			@local_domains : sets of domains for which the content is local
			//@@todo should query wgForeignFileRepos setting maybe interwikimap from the api
		 */
		'this_wiki':{
			'enabled': 1,
			'checked': 1,
			'title'	 : 'This Wiki',
			'desc'	 : '(should be updated with the proper text) maybe import from some config value',
			'api_url':  ( wgServer && wgScriptPath )? wgServer + wgScriptPath+ '/api.php': null,
			'lib'	 : 'mediaWiki',
			'local'	 : true,
			'tab_img': false
		},
		'wiki_commons':{
			'enabled': 1,
			'checked': 1,
			'title'	 :'Wikimedia Commons',
			'desc'	 : 'Wikimedia Commons is a media file repository making available public domain '+
					 'and freely-licensed educational media content (images, sound and video clips) to all.',
			'homepage': 'http://commons.wikimedia.org/wiki/Main_Page',
			'api_url':'http://commons.wikimedia.org/w/api.php',
			'lib'	:'mediaWiki',
			'resource_prefix': 'WC_', //prefix on imported resources (not applicable if the repository is local)
			
			//if we should check for shared repository asset ( generally only applicable to commons )
			'check_shared':true,

			//list all the domains where commons is local?
			// probably should set this some other way by doing an api query
			// or by seeding this config when calling the remote search?
			'local_domains': ['wikimedia','wikipedia','wikibooks'],
			//specific to wiki commons config:
			'search_title':false, //disable title search
			'tab_img':true
		},
		'archive_org':{
			'enabled':1,
			'checked':1,
			'title' : 'Archive.org',
			'desc'	: 'The Internet Archive, a digital library of cultural artifacts',
			'homepage':'http://www.archive.org/about/about.php',

			'api_url':'http://homeserver7.us.archive.org:8983/solr/select',
			'lib'	: 'archiveOrg',
			'local'	: false,
			'resource_prefix': 'AO_',
			'tab_img':true
		},
		'flickr':{
			'enabled':1,
			'checked':1,
			'title' : 'flickr.com',
			'desc'	: 'flickr.com, a online photo sharing site',
			'homepage':'http://www.flickr.com/about/',

			'api_url':'http://www.flickr.com/services/rest/',
			'lib'	: 'flickr',
			'local'	: false,
			//resources from fliker don't have a human parsable identieifer/title
			'resource_prefix': '',
			'tab_img':true
		},
		'metavid':{
			'enabled' : 1,
			'checked' : 1,
			'title'	: 'Metavid.org',
			'homepage':'http://metavid.org/wiki/Metavid_Overview',
			'desc'	: 'Metavid hosts thousands of hours of US house and senate floor proceedings',
			'api_url':'http://metavid.org/w/index.php?title=Special:MvExportSearch',
			'lib'	: 'metavid',
			'local'	:false,			//if local set to true we can use local
			'resource_prefix': 'MV_', //what prefix to use on imported resources

			'local_domains': ['metavid'], // if the domain name contains metavid
									   // no need to import metavid content to metavid sites

			'stream_import_key': 'mv_ogg_low_quality', // which stream to import, could be mv_ogg_high_quality
													  //or flash stream, see ROE xml for keys

			'remote_embed_ext': false, //if running the remoteEmbed extension no need to copy local
									   //syntax will be [remoteEmbed:roe_url link title]
			'tab_img':true
		},
		//special cp "upload"
		'upload':{
			'enabled':1,
			'checked':1,
			'title'	:'Upload'
		}
	},	
	//some default layout values:
	thumb_width		 	: 80,
	image_edit_width	: 400,
	video_edit_width	: 400,
	insert_text_pos		: 0,	  //insert at the start (will be overwritten by the user cursor pos)
	result_display_mode : 'box', //box or list

	cUpLoader			: null,
	cEdit				: null,
	proxySetupDone		: null,
	dmodalCss			: {},

	init: function( options ){
		var _this = this;
		js_log('remoteSearchDriver:init');
		
		//merge in the options:  
		//@@todo for cleaner config we should set _this.opt to the provided options) 
		$j.extend( _this, default_remote_search_options, options);			
		
		//update the base text:
		if(_this.target_textbox)
		   _this.getTexboxSelection();

		//modify the content provider config based on options: 		
		for(var i in this.content_providers){		
			if(	_this.enabled_cps == 'all' && !this.disp_item  ){	
				this.disp_item = i; 
			}else{
				if( $j.inArray(i, _this.enabled_cps) != -1 ){
					//if no default display set to first enabled cp: 
					if( !this.disp_item )
						this.disp_item = i;
					this.content_providers[i].enabled = true;
				}else{
					if( _this.enabled_cps != 'all' ){
						this.content_providers[i].enabled = false;
					}
				}		
			}
		}
			
		//set the upload target name if unset
		if( _this.upload_api_target == 'local' &&  ! _this.upload_api_name && typeof wgSiteName != 'undefined')
			_this.upload_api_name =  wgSiteName;
			
		//if the upload_api_proxy_frame is set _this.upload_api_target to "proxy"
		if( _this.upload_api_proxy_frame )
			_this.upload_api_target = 'proxy';
		
		//map "local" to the local api 			
		if( _this.upload_api_target == 'local' ){
			if( ! _this.local_wiki_api_url ){
				$j('#tab-upload').html( gM( 'rsd_config_error', 'missing_local_api_url' ) );
				return false;
			}else{
				_this.upload_api_target = _this.local_wiki_api_url;
			}
		}		
						
		//set up the target invocation:
		if( $j( this.target_invocation ).length==0 ){
			js_log("RemoteSearchDriver:: no target invocation provided (will have to run your own doInitDisplay() )");
		}else{
			if( this.target_invocation ){
				$j(this.target_invocation).css('cursor','pointer').attr('title', gM('mwe-add_media_wizard')).click(function(){
					_this.doInitDisplay();
				});
			}
		}
	},
	//define the licenses
	// ... this will get complicated quick...
	// (just look at complexity for creative commons without excessive "duplicate data")
	// ie cc_by could be "by/3.0/us/" or "by/2.1/jp/" to infinitum...
	// some complexity should be negated by license equivalences.

	// but we will have to abstract into another class let content providers provide license urls
	// and we have to clone the license object and allow local overrides

	licenses:{
		//for now only support creative commons type licenses
		//used page: http://creativecommons.org/licenses/
		'cc':{
			'base_img_url':'http://upload.wikimedia.org/wikipedia/commons/thumb/',
			'base_license_url': 'http://creativecommons.org/licenses/',
			'licenses':[
				'by',
				'by-sa',
				'by-nc-nd',
				'by-nc',
				'by-nd',
				'by-nc-sa',
				'by-sa',
				'pd'
			],
			'license_img':{
				'by':{
					'im':'1/11/Cc-by_new_white.svg/20px-Cc-by_new_white.svg.png'
				},
				'nc':{
					'im':'2/2f/Cc-nc_white.svg/20px-Cc-nc_white.svg.png'
				},
				'nd':{
					'im':'b/b3/Cc-nd_white.svg/20px-Cc-nd_white.svg.png'
				},
				'sa':{
					'im':'d/df/Cc-sa_white.svg/20px-Cc-sa_white.svg.png'
				},
				'pd':{
					'im':'5/51/Cc-pd-new_white.svg/20px-Cc-pd-new_white.svg.png'
				}
			}
		}
	},
	/*
	 * getlicenseImgSet
	 * @param license_key  the license key (ie "by-sa" or "by-nc-sa" etc)
	 */
	getlicenseImgSet: function( licenseObj ){
		//js_log('output images: '+ imgs);
		return '<div class="rsd_license" title="'+ licenseObj.title + '" >' +
					'<a target="_new" href="'+ licenseObj.lurl +'" ' +
					'title="' + licenseObj.title + '">'+
							licenseObj.img_html +
					'</a>'+
				  '</div>';
	},	
	/*
	 * getLicenceKeyFromKey
	 * @param license_key the key of the license (must be defined in: this.licenses.cc.licenses)
	 */
	getLicenceFromKey:function( license_key , force_url){	
		//set the current license pointer:
		var cl = this.licenses.cc;
		var title = gM('mwe-cc_title');
		var imgs = '';
		var license_set = license_key.split('-');
		for(var i=0;i < license_set.length; i++){
			var lkey =	 license_set[i];
			if(! cl.license_img[ lkey ] ){
				js_log("MISSING::" + lkey );
			}
			
			title += ' ' + gM( 'mwe-cc_' + lkey + '_title');
			imgs +='<img class="license_desc" width="20" src="' 
				+ cl.base_img_url +	cl.license_img[ lkey ].im + '">';
		}
		var url = (force_url) ? force_url : cl.base_license_url + cl.licenses[ license_key ];
		return {
			'title'		: title,
			'img_html'	: imgs,
			'key'		 : license_key,
			'lurl'		 : url
		};
	},
	/*
	 * getLicenceKeyFromUrl
	 * @param licence_url the url of the license
	 */
	getLicenceFromUrl: function( license_url ){
		//check for some pre-defined url types:
		if( license_url == 'http://www.usa.gov/copyright.shtml' ||
			license_url == 'http://creativecommons.org/licenses/publicdomain' )
			return this.getLicenceFromKey('pd' , license_url);
			
		
		//js_log("getLicenceFromUrl::" + license_url);				
		//first do a direct lookup check:
		for(var j =0; j < this.licenses.cc.licenses.length; j++){
			var jL = this.licenses.cc.licenses[ j ];
			//special 'pd' case: 
			if( jL == 'pd'){
				var keyCheck = 'publicdomain';
			}else{
				var keyCheck = jL;
			}			
			if( parseUri(license_url).path.indexOf('/'+ keyCheck +'/') != -1){
				return this.getLicenceFromKey(jL , license_url);
			}
		};				
		//could not find it return mwe-unknown_license
		return {
			'title'	 	: gM('mwe-unknown_license'),
			'img_html'	: '<span>' + gM('mwe-unknown_license') + '</span>',
			'lurl'		: license_url
		};
	},
	/**
	* getTypeIcon
	* @param str mime type of the requested file
	*/
	getTypeIcon:function( mimetype) {
		var typestr = 'unk';
		switch( mimetype ){
			case 'image/svg+xml':
				typestr = 'svg';
			break;
			case 'image/jpeg':
				typestr = 'jpg'
			break;
			case 'image/png':
				typestr = 'png';
			break;
			case 'audio/ogg':
				typestr = 'oga';
			case 'video/ogg':
			case 'application/ogg':
				typestr = 'ogg';
			break;
		}
		
		if(typestr=='unk')
			js_log("unkown ftype: " + mimetype );
			 
		return '<div class="rsd_file_type ui-corner-all ui-state-default ui-widget-content" title="' + gM('mwe-ftype-' + typestr) + '">' +
					typestr  +
				'</div>'
	},
	doInitDisplay:function(){
		var _this = this;
		//setup the parent container:
		this.init_modal();
		//fill in the html:
		this.init_interface_html();
		//bind actions:
		this.add_interface_bindings();

		//update the target binding to just un-hide the dialog:
		if( this.target_invocation ){
			$j(this.target_invocation).unbind().click(function(){
				js_log("doInitDisplay:target_invocation: click doReDisp");
			 	 _this.doReDisplay();
			 });
		 }
	},
	doReDisplay: function(){
		var _this = this;
		js_log("doReDisplay::");
		//update the base text:
		if( _this.target_textbox )
			_this.getTexboxSelection();
		//$j(_this.target_container).dialog("open");
		$j(_this.target_container).parents('.ui-dialog').fadeIn('slow');
		//re-center the dialog:
		$j(_this.target_container).dialog('option', 'position','center');
	},
	//gets the in and out points for insert position or grabs the selected text for search
	getTexboxSelection:function(){
		//update the caretPos
		this.getCaretPos();

		//if we have highlighted text use that as the query: (would be fun to add context menu once we have rich editor in-place)
		if( this.caret_pos.selection )
			this.default_query = this.caret_pos.selection

	},
	getCaretPos:function(){
		this.caret_pos={};
		var txtarea = $j(this.target_textbox).get(0);
		var getTextCusorStartPos = function (o){
			if (o.createTextRange) {
					var r = document.selection.createRange().duplicate()
					r.moveEnd('character', o.value.length)
					if (r.text == '') return o.value.length
					return o.value.lastIndexOf(r.text)
				} else return o.selectionStart
		}
		var getTextCusorEndPos = function (o){
			if (o.createTextRange) {
				var r = document.selection.createRange().duplicate();
				r.moveStart('character', -o.value.length);
				return r.text.length;
			} else{
				return o.selectionEnd
			}
		}
		this.caret_pos.s = getTextCusorStartPos( txtarea );
		this.caret_pos.e = getTextCusorEndPos( txtarea );
		this.caret_pos.text = txtarea.value;
		if(this.caret_pos.s && this.caret_pos.e &&
				(this.caret_pos.s != this.caret_pos.e))
			this.caret_pos.selection = this.caret_pos.text.substring(this.caret_pos.s, this.caret_pos.e).replace(/ /g, '\xa0') || '\xa0';

		js_log('got caret_pos:' + this.caret_pos.s);
		//restore text value: (creating textRanges sometimes screws with the text content)
		$j(this.target_textbox).val(this.caret_pos.text);
	},
	init_modal:function(){
		js_log("init_modal");
		var _this = this;	
		//add the parent target_container if not provided or missing
		if(!_this.target_container || $j(_this.target_container).length==0){
			_this.target_container = '#rsd_modal_target';
			$j('body').append('<div id="rsd_modal_target" style="position:absolute;top:3em;left:0px;bottom:3em;right:0px;" title="' + gM('mwe-add_media_wizard') + '" ></div>');			
			//js_log('appended: #rsd_modal_target' + $j(_this.target_container).attr('id'));
			//js_log('added target id:' + $j(_this.target_container).attr('id'));
			//get layout
			js_log( 'width: ' + $j(window).width() +  ' height: ' + $j(window).height());
			var cBtn = {};
			cBtn[ gM('mwe-cancel') ] = function(){
				_this.cancelClipEditCB();
			}			
			function doResize(){
				js_log('do resize:: ' + _this.target_container);				
				$j( '#rsd_modal_target').dialog('option', 'width', $j(window).width()-50 );
				$j( '#rsd_modal_target').dialog('option', 'height', $j(window).height()-50 );
				$j( '#rsd_modal_target').dialog('option', 'position','center');
			}
			
			$j(_this.target_container).dialog({
				bgiframe: true,
				autoOpen: true,
				modal: true,
				draggable:false,
				resizable:false,
				buttons:cBtn,								
				close: function() {
					//if we are 'editing' a item close that 
					//@@todo maybe prompt the user? 					
					_this.cancelClipEditCB();															
					$j(this).parents('.ui-dialog').fadeOut('slow');				
				}
			});				
			doResize();
			$j(window).resize(function(){
				doResize();
			});
			
			
			//add cancel callback and updated button with icon
			_this.cancelClipEditCB();
			
			//update the child position: (some of this should be pushed up-stream via dialog config options
			$j(_this.target_container +'~ .ui-dialog-buttonpane').css({
				'position':'absolute',
				'left':'0px',
				'right':'0px',
				'bottom':'0px'
			});			
		}
	},
	//sets up the initial html interface
	init_interface_html:function(){
		js_log('init_interface_html');
		var _this = this;
		var dq = (this.default_query)? this.default_query : '';
		js_log('f::init_interface_html');

		var o = '<div class="rsd_control_container" style="width:100%">' +
					'<form id="rsd_form" action="javascript:return false;" method="GET">'+
						'<input class="ui-widget-content ui-corner-all" type="text" tabindex="1" value="' + dq + '" maxlength="512" id="rsd_q" name="rsd_q" '+
							'size="20" autocomplete="off"/> '+
						$j.btnHtml( gM('mwe-media_search'), 'rms_search_button', 'search') +
					'</form>';
		//close up the control container:
		o+='</div>';

		//search provider tabs based on "checked" and "enabled" and "combined tab"
		o+='<div id="rsd_results_container" style="top:0px;bottom:0px;left:0px;right:0px;"></div>';
		$j(this.target_container).html( o );

		//add simple styles:
		$j(this.target_container + ' .rms_search_button').btnBind().click(function(){			
			_this.runSearch();
		});

		//draw the tabs:
		this.drawTabs();
		//run the default search:
		if( this.default_query )
			this.runSearch();
	},
	add_interface_bindings:function(){
		var _this = this;
		js_log("f:add_interface_bindings:");


		$j('#mso_selprovider,#mso_selprovider_close').unbind().click(function(){
			if($j('#rsd_options_bar:hidden').length !=0 ){
				$j('#rsd_options_bar').animate({
					'height':'110px',
					'opacity':1
				}, "normal");
			}else{
				$j('#rsd_options_bar').animate({
					'height':'0px',
					'opacity':0
				}, "normal", function(){
					$j(this).hide();
				});
			}
		});
		//set form bindings
		$j('#rsd_form').unbind().submit(function(){
			_this.runSearch();
			//don't submit the form
			return false;
		});
	},
	doUploadInteface:function(){
		js_log("doUploadInteface::");
		var _this = this;
		//set it to loading:
		mv_set_loading('#tab-upload');
		//do things async to keep interface snappy
		setTimeout(function(){
			//check if we need to setup the proxy::
			if( _this.upload_api_target == 'proxy' ){
				_this.setupProxy( function(){
					_this.getUploadForm();
				});														
			}else{				
				_this.getUploadForm();
			}							
		},1);
	},
	getUploadForm:function(){
		var _this = this;
		mvJsLoader.doLoad(['$j.fn.simpleUploadForm'],function(){
			//get extends info about the file
			var cp = _this.content_providers['this_wiki'];
			
			//check for "this_wiki" enabled
			/*if(!cp.enabled){
				$j('#tab-upload').html('error this_wiki not enabled (can\'t get uploaded file info)');
				return false;
			}*/
	
			//load  this_wiki search system to grab the rObj
			_this.loadSearchLib(cp, function(){
				//do basic layout form on left upload "bin" on right
				$j('#tab-upload').html('<table>' +
				'<tr>' +
					'<td valign="top" style="width:350px; padding-right: 12px;">' +
						'<h4>' + gM('mwe-upload_a_file', _this.upload_api_name ) + '</h4>' +
					 	'<div id="upload_form">' +
					 		mv_get_loading_img() +
					 	'</div>' +
					'</td>' +
					'<td valign="top" id="upload_bin_cnt">' +
					'<h4>' + gM('mwe-your_recent_uploads', _this.upload_api_name) + '</h4>' +
						'<div id="upload_bin">' +
							mv_get_loading_img() +
						'</div>'+
					'</td>' +
				'</tr>' +
				'</table>');
	
	
				//fill in the user page:
				if(typeof wgUserName != 'undefined' && wgUserName){						
					//load the upload bin with anything the current user has uploaded
					cp.sObj.getUserRecentUploads( wgUserName, function(){
						_this.drawOutputResults();
					});
				}else{
					$j('#upload_bin_cnt').empty();
				}
	
				//deal with the api form upload form directly:
				$j('#upload_form').simpleUploadForm({
					"api_target" :	_this.upload_api_target,
					"ondone_cb"	: function( resultData ){
						var wTitle = resultData['filename'];	
						//add a loading div
						_this.addResourceEditLoader();
						//@@note: we have most of what we need in resultData imageinfo
						cp.sObj.addByTitle( wTitle, function( rObj ){								
							//redraw (with added result if new)
							_this.drawOutputResults();
							//pull up resource editor:									
							_this.resourceEdit( rObj, $j('#res_upload__' + rObj.id).get(0) );									
						});
						//return false to close progress window:
						return false;
					}
				});		
			}); //load searchLibs
		}); //load simpleUploadForm
	},
	runSearch: function( restPage ){
		js_log("f:runSearch::" + this.disp_item);
		//draw_direct_flag
		var draw_direct_flag = true;
			
		//check if its the special upload tab case:
		if( this.disp_item == 'upload'){
			this.doUploadInteface();
			return true;
		}
		
		//else do runSearch					
		var cp = this.content_providers[this.disp_item];

		//check if we need to update:
		if( typeof cp.sObj != 'undefined' ){
			if(cp.sObj.last_query == $j('#rsd_q').val() && cp.sObj.last_offset == cp.offset){
				js_log('last query is: ' + cp.sObj.last_query + ' matches: ' +  $j('#rsd_q').val() );
			}else{
				js_log('last query is: ' + cp.sObj.last_query + ' not match: ' +  $j('#rsd_q').val() );
				draw_direct_flag = false;
			}
		}else{
			draw_direct_flag = false;
		}
		if( !draw_direct_flag ){
			//see if we should reset the pageing
			if( restPage ){
				cp.sObj.offset = cp.offset = 0;
			}
		
			//set the content to loading while we do the search:
			$j('#tab-' + this.disp_item).html( mv_get_loading_img() );

			//make sure the search library is loaded and issue the search request
			this.getLibSearchResults( cp );
		}
	},
	//issue a api request & cache the result
	//this check can be avoided by setting the this.import_url_mode = 'api' | 'form' | instead of 'autodetect' or 'none'
	checkForCopyURLSupport:function ( callback ){
		var _this = this;
		js_log('checkForCopyURLSupport:: ');		
		
		//see if we already have the import mode:
		if( this.import_url_mode != 'autodetect'){
			js_log('import mode: ' + _this.import_url_mode);
			callback();
		}
		//if we don't have the local wiki api defined we can't auto-detect use "link"
		if( ! _this.upload_api_target ){
			js_log('import mode: remote link (no import_wiki_api_url)');
			_this.import_url_mode = 'remote_link';
			callback();
		}
		if( this.import_url_mode == 'autodetect' ){
			do_api_req( {
				'data': { 'action':'paraminfo', 'modules':'upload' },
				'url': _this.upload_api_target
			}, function(data){				
				//jump right into api checks: 
				for( var i in data.paraminfo.modules[0].parameters ){
					var pname = data.paraminfo.modules[0].parameters[i].name;
					if( pname == 'url' ){
						js_log( 'Autodetect Upload Mode: api: copy by url:: ' );
						//check permission  too:
						_this.checkForCopyURLPermission(function( canCopyUrl ){
							if(canCopyUrl){
								_this.import_url_mode = 'api';
								js_log('import mode: ' + _this.import_url_mode);
								callback();
							}else{
								_this.import_url_mode = 'none';
								js_log('import mode: ' + _this.import_url_mode);
								callback();
							}
						});
						break;
					}
				}
			});
		}
	},
	/*
	 * checkForCopyURLPermission:
	 * not really necessary the api request to upload will return appropriate error if the user lacks permission. or $wgAllowCopyUploads is set to false
	 * (use this function if we want to issue a warning up front)
	 */
	checkForCopyURLPermission:function( callback ){
		var _this = this;
		//do api check:
		do_api_req( {
				'data':{ 'action' : 'query', 'meta' : 'userinfo', 'uiprop' : 'rights' },
				'url': _this.upload_api_target,
				'userinfo' : true
		}, function(data){
			for( var i in data.query.userinfo.rights){
				var right = data.query.userinfo.rights[i];
				//js_log('checking: ' + right ) ;
				if(right == 'upload_by_url'){
					callback( true );
					return true; //break out of the function
				}
			}
			callback( false );
		});
	},
	getLibSearchResults:function( cp ){
		var _this = this;

		//first check if we should even run the search at all (can we import / insert into the page? )
		if( !this.checkRepoLocal( cp ) && this.import_url_mode == 'autodetect' ){
			//cp is not local check if we can support the import mode:
			this.checkForCopyURLSupport( function(){
				_this.getLibSearchResults( cp );
			});
			return false;
		}else if( !this.checkRepoLocal( cp ) && this.import_url_mode == 'none'){
			if(  this.disp_item == 'combined' ){
				//combined results are harder to error handle just ignore that repo
				cp.sObj.loading = false;
			}else{
				$j('#tab-' + this.disp_item).html( '<div style="padding:10px">'+ gM('mwe-no_import_by_url') +'</div>');
			}
			return false;
		}
		_this.loadSearchLib(cp, function(){
			//do search
			cp.sObj.getSearchResults();
			_this.checkResultsDone();
		});
	},
	loadSearchLib:function(cp, callback){
		var _this = this;		
		//set up the library req:
		mvJsLoader.doLoad( [
			'baseRemoteSearch',
			cp.lib + 'Search'
		], function(){
			js_log("loaded lib:: " + cp.lib );
			//else we need to run the search:
			var iObj = {
				'cp'  : cp, 
				'rsd' : _this
			};
			eval('cp.sObj = new ' + cp.lib + 'Search( iObj );' );
			if(!cp.sObj){
				js_log('Error: could not find search lib for ' + cp_id);
				return false;
			}

			//inherit defaults if not set:
			cp.limit = (cp.limit) ? cp.limit : cp.sObj.limit;
			cp.offset = (cp.offset) ? cp.offset : cp.sObj.offset;
			callback();
		});
	},
	/* check for all the results to finish */
	checkResultsDone: function(){
		//js_log('rsd:checkResultsDone');
		var _this = this;
		var loading_done = true;

		for(var cp_id in  this.content_providers){
			var cp = this.content_providers[ cp_id ];
			if(typeof cp['sObj'] != 'undefined'){
				if( cp.sObj.loading )
					loading_done = false;
			}
		}
		if( loading_done ){
			this.drawOutputResults();
		}else{
			//make sure the instance name is up-to-date refrerance to _this;
			eval( _this.instance_name + ' = _this');
			setTimeout( _this.instance_name + '.checkResultsDone()', 50);
		}
	},
	drawTabs: function(){
		var _this = this;
		//add the tabs to the rsd_results container:
		var o='<div id="rsd_tabs_container" style="width:100%;">';
		var selected_tab = 0;
		var inx =0;
		o+= '<ul>';
			var tabc = '';
			for(var cp_id in  this.content_providers){
					var cp = this.content_providers[cp_id];
					if( cp.enabled && cp.checked && cp.api_url){
						//add selected default if set
						if( this.disp_item == cp_id)
							selected_tab=inx;

						o+='<li class="rsd_cp_tab">';
						o+='<a id="rsd_tab_' + cp_id + '" href="#tab-' + cp_id + '">';
							if(cp.tab_img === true){
								o+='<img alt="' + cp.title +'" src="' + mv_embed_path +'/skins/common/remote_cp/' + cp_id + '_tab.png">';
							}else{
								o+= cp.title;
							}
						o+='</a>';
						o+='</li>';
						inx++;
					}
					tabc+='<div id="tab-'+ cp_id +'" class="rsd_results"/>';

			}
			//do an upload tab if enabled:
			if( this.content_providers['upload'].enabled ){
				o+='<li class="rsd_cp_tab" ><a id="rsd_tab_upload" href="#tab-upload">' + gM('mwe-upload_tab') + '</a></li>';
				tabc+='<div id="tab-upload" />';
				if(this.disp_item == 'upload')
					selected_tab = inx++;
			}
			o+='</ul>';
			//output the tab content containers:
			o+=tabc;
		o+='</div>'; //close tab container

		//output the respective results holders
		$j('#rsd_results_container').html(o);
		//setup bindings for tabs make them sortable: (@@todo remember order)
		js_log('selected tab is: ' + selected_tab);
		$j("#rsd_tabs_container").tabs({
			selected:selected_tab,
			select: function(event, ui) {
				_this.selectTab( $j(ui.tab).attr('id').replace('rsd_tab_', '') );
			}
		//add sorting
		}).find(".ui-tabs-nav").sortable({axis:'x'});
		//@@todo store sorted repo 

	},
	//resource title
	getResourceFromTitle : function( rTitle , callback){
		var _this = this;
		reqObj={
			'action':'query',
			'titles': _this.cFileNS + ':' + rTitle
		};
		do_api_req( {
			'data':reqObj,
			'url':this.local_wiki_api_url
			}, function(data){
				//@@todo propagate the rObj
				var rObj = {};
			}
		);
	},
	//@@todo we could load the id with the content provider id to find the object faster...
	getResourceFromId:function( rid ){
		//js_log('getResourceFromId:' + rid );
		//strip out /res/ if preset:
		rid = rid.replace(/res_/, '');
		//js_log("looking at: " + rid);
		p = rid.split('__');
		var cp_id = p[0];
		var rid = p[1];
		
		//Set the upload helper cp_id (to render recent uploads by this user)
		if(cp_id == 'upload')
			cp_id = 'this_wiki';

		var cp = this.content_providers[cp_id];
		if(cp && cp['sObj'] && cp.sObj.resultsObj[rid]){
			return cp.sObj.resultsObj[rid];
		}
		js_log("ERROR: could not find " + rid);
		return false;
	},
	drawOutputResults: function(){
		js_log('f:drawOutputResults::' + this.disp_item);
		var _this = this;
		var o='';		
		
		var cp_id = this.disp_item;
		var tab_target = '';
		if(this.disp_item == 'upload'){
			tab_target = '#upload_bin';
			var cp = _this.content_providers['this_wiki'];		
		}else{
			var cp = this.content_providers[this.disp_item];
			tab_target = '#tab-' + cp_id;
			//output the results bar / controls			
		}			
		//empty the existing results:
		$j(tab_target).empty();
		//@@todo give the user upload control love 
		if(this.disp_item != 'upload'){
			_this.setResultBarControl();
		}
		
		var drawResultCount	= 0;

		//output all the results for the current disp_item
		if( typeof cp['sObj'] != 'undefined' ){			
			$j.each(cp.sObj.resultsObj, function(rInx, rItem){
				if( _this.result_display_mode == 'box' ){
					o+='<div id="mv_result_' + rInx + '" class="mv_clip_box_result" style="width:' +
							_this.thumb_width + 'px;height:'+ (_this.thumb_width-20) +'px;position:relative;">';
						//check for missing poster types for audio
						if( rItem.mime=='audio/ogg' && !rItem.poster ){
							rItem.poster = mv_skin_img_path + 'sound_music_icon-80.png';
						}
						//get a thumb with proper resolution transform if possible:
						o+='<img title="'+rItem.title+'" class="rsd_res_item" id="res_' + cp_id + '__' + rInx +
								'" style="width:' + _this.thumb_width + 'px;" src="' +
								cp.sObj.getImageTransform( rItem, { 'width' : _this.thumb_width } )
								+ '">';
						//add a linkback to resource page in upper right:
						if( rItem.link )
							o+='<div class="rsd_linkback ui-corner-all ui-state-default ui-widget-content" >' +								
									'<a target="_new" title="' + gM('mwe-resource_description_page') +
									'" href="' + rItem.link + '">'+ gM('mwe-link') + '</a>' + 
								'</div>';
								
						//add file type icon if known
						if( rItem.mime ){
							o+= _this.getTypeIcon( rItem.mime );
						}		
								
						//add license icons if present
						if( rItem.license )
							o+= _this.getlicenseImgSet( rItem.license );
													
					o+='</div>';
				}else if(_this.result_display_mode == 'list'){
					o+='<div id="mv_result_' + rInx + '" class="mv_clip_list_result" style="width:90%">';
						o+='<img title="'+rItem.title+'" class="rsd_res_item" id="res_' + cp_id + '__' + rInx +'" style="float:left;width:' +
								 _this.thumb_width + 'px;" src="' +
								 cp.sObj.getImageTransform( rItem, {'width':_this.thumb_width } )
								  + '">';
						//add license icons if present
						if( rItem.license )
							o+= _this.getlicenseImgSet( rItem.license );

						o+= rItem.desc ;
					o+='<div style="clear:both" />';
					o+='</div>';
				}
				drawResultCount++;
			});
			js_log('append to: ' + '#tab-' + cp_id);
			//put in the tab output (plus clear the output)
			$j(tab_target).append( o + '<div style="clear:both"/>');
		}

		js_log( 'did drawResultCount :: ' + drawResultCount + ' append: ' + $j('#rsd_q').val() );

		//remove any old search res
		$j('#rsd_no_search_res').remove();
		if( drawResultCount == 0 )
			$j('#tab-' + cp_id).append( '<span style="padding:10px">' + gM( 'rsd_no_results', $j('#rsd_q').val() ) + '</span>');

		this.addResultBindings();
	},
	addResultBindings:function(){
		var _this = this;
		$j('.mv_clip_'+_this.result_display_mode+'_result').hover(function(){
			$j(this).addClass('mv_clip_'+_this.result_display_mode+'_result_over');
			//also set the animated image if available
			var res_id = $j(this).children('.rsd_res_item').attr('id');
			var rObj = _this.getResourceFromId( res_id );
			if( rObj.poster_ani )
				$j('#' + res_id ).attr('src', rObj.poster_ani);
		},function(){
			$j(this).removeClass('mv_clip_'+_this.result_display_mode+'_result_over');
			var res_id = $j(this).children('.rsd_res_item').attr('id');
			var rObj = _this.getResourceFromId( res_id );
			//restore the original (non animated)
			if( rObj.poster_ani )
				$j('#' + res_id ).attr('src', rObj.poster);
		});
		//resource click action: (bring up the resource editor)
		$j('.rsd_res_item').unbind().click(function(){
			var rObj = _this.getResourceFromId( $j(this).attr("id") );
			_this.resourceEdit( rObj, this );
		});
	},
	addResourceEditLoader:function(maxWidth, overflow_style){
		var _this = this;
		if(!maxWidth)maxWidth=400;
		if(!overflow_style)overflow_style='overflow:auto;';
		//remove any old instance:
		$j( _this.target_container ).find('#rsd_resource_edit').remove();
		//add the edit layout window with loading place holders
		$j( _this.target_container ).append('<div id="rsd_resource_edit" '+
			'style="position:absolute;top:0px;left:0px;bottom:0px;right:4px;background-color:#FFF;">' +
				'<div id="clip_edit_ctrl" class="ui-widget ui-widget-content ui-corner-all" style="position:absolute;'+
					'left:2px;top:5px;bottom:10px;width:' + ( maxWidth + 5 ) + 'px;overflow:auto;padding:5px;">'+
						mv_get_loading_img() +
				'</div>'+
				'<div id="clip_edit_disp" class="ui-widget ui-widget-content ui-corner-all"' +  
					'style="position:absolute;' + overflow_style + ';left:'+ ( maxWidth + 20 ) +'px;right:0px;top:5px;bottom:10px;padding:5px;>' +
						mv_get_loading_img('position:absolute;top:30px;left:30px') +
				'</div>'+			
		'</div>');
	},
	resourceEdit:function( rObj, rsdElement){
		js_log('f:resourceEdit:' + rObj.title);
		var _this = this;
		//remove any existing resource edit interface:
		$j('#rsd_resource_edit').remove();
		//set the media type:
		if(rObj.mime.indexOf('image')!=-1){
			//set width to default image_edit_width
			var maxWidth = _this.image_edit_width;
			var mediaType = 'image';
		}else if(rObj.mime.indexOf('audio')!=-1){
			var maxWidth = _this.video_edit_width;
			var mediaType = 'audio';
		}else{
			//set to default video size:
			var maxWidth = _this.video_edit_width;
			var mediaType = 'video';
		}
		//so that transcripts show ontop
		var overflow_style = ( mediaType =='video' )?'':'overflow:auto;';
		//append to the top level of model window:
		_this.addResourceEditLoader(maxWidth, overflow_style);
		//update add media wizard title:
		$j( _this.target_container ).dialog( 'option', 'title', gM('mwe-add_media_wizard')+': '+ gM('rsd_resource_edit', rObj.title ) );
		js_log('did append to: '+ _this.target_container );

		$j('#rsd_resource_edit').css('opacity',0);

		$j('#rsd_edit_img').remove();//remove any existing rsd_edit_img

		//left side holds the image right size the controls /
		$j(rsdElement).clone().attr('id', 'rsd_edit_img').appendTo('#clip_edit_disp').css({
			'position':'absolute',
			'top':'40%',
			'left':'20%',
			'cursor':'default',
			'opacity':0
		});
		
		//try and keep aspect ratio for the thumbnail that we clicked:		
		var tRatio = $j(rsdElement).height() / $j(rsdElement).width();

		if(	! tRatio )
			var tRatio = 1; //set ratio to 1 if tRatio did not work. 

		js_log('Set from ' +  tRatio + ' to init thumbimage to ' + maxWidth + ' x ' + parseInt( tRatio * maxWidth) );
		//scale up image and to swap with high res version
		$j('#rsd_edit_img').animate({
			'opacity':1,
			'top':'5px',
			'left':'5px',
			'width': maxWidth + 'px',
			'height': parseInt( tRatio * maxWidth)  + 'px'
		}, "slow"); // do it slow to give it a chance to finish loading the HQ version

		if( mediaType == 'image' ){
			_this.loadHQImg(rObj, {'width':maxWidth}, 'rsd_edit_img', function(){
				$j('.mv_loading_img').remove();
			});
		}
		//also fade in the container:
		$j('#rsd_resource_edit').animate({
			'opacity':1,
			'background-color':'#FFF',
			'z-index':99
		});
		//do load the media Editor
		_this.doMediaEdit( rObj , mediaType );
	},
	loadHQImg:function(rObj, size, target_img_id, callback){
		// Get the HQ image url:
		rObj.pSobj.getImageObj( rObj, size, function( imObj ){
			rObj['edit_url'] = imObj.url;

			js_log("edit url: " + rObj.edit_url);
			// Update the rObj
			rObj['width'] = imObj.width;
			rObj['height'] = imObj.height;

			// See if we need to animate some transition
			if( size.width != imObj.width ){
				js_log('loadHQImg:size mismatch: ' + size.width + ' != ' + imObj.width );
				//set the target id to the new size:
				$j('#'+target_img_id).animate( {
					'width':imObj.width + 'px',
					'height':imObj.height + 'px'
				});
			}else{
				js_log('using req size: ' + imObj.width + 'x' + imObj.height);
				$j('#'+target_img_id).animate( {'width':imObj.width+'px', 'height' : imObj.height + 'px'});
			}
			//don't swap it in until its loaded:
			var img = new Image();
			// load the image image:
			$j(img).load(function () {
					 $j('#'+target_img_id).attr('src', rObj.edit_url );
					 //let the caller know we are done and what size we ended up with:
					 callback();
				}).error(function () {
					js_log("Error with:  " +  rObj.edit_url);
				}).attr('src', rObj.edit_url);
			});
	},
	cancelClipEditCB:function(){
		var _this = this;		
		js_log('cancelClipEditCB');
		var b_target =   _this.target_container + '~ .ui-dialog-buttonpane';
		$j('#rsd_resource_edit').remove();
		//remove preview if its 'on'
		$j('#rsd_preview_display').remove();
		//restore the resource container:
		$j('#rsd_results_container').show();
		
		//restore the title:
		$j( _this.target_container ).dialog( 'option', 'title', gM('mwe-add_media_wizard'));
		js_log("should update: " + b_target + ' with: cancel');
		//restore the buttons:		
		$j(b_target).html( $j.btnHtml( gM('mwe-cancel') , 'mv_cancel_rsd', 'close'))
			.children('.mv_cancel_rsd')
			.btnBind()
			.click(function(){
				$j( _this.target_container).dialog('close');				
			})

	},
	/*set-up the control actions for clipEdit with relevant callbacks */
	getClipEditControlActions:function( cp ){
		var _this = this;
		var cConf= {};

		cConf['insert'] = function(rObj){
			_this.insertResource(rObj);
		}
		//if not directly inserting the resource is support a preview option:
		if( _this.import_url_mode != 'remote_link'){
			cConf['preview'] = function(rObj){
				_this.previewResource( rObj )
			};
		}
		cConf['cancel'] = function(){
			_this.cancelClipEditCB()
		}
		return cConf;
	},
	//loads the media editor:
	doMediaEdit:function( rObj , mediaType){
		var _this = this;
		var cp = rObj.pSobj.cp;
		js_log('remoteSearchDriver::doMediaEdit: ' + mediaType);
		
		var mvClipInit = {
			'rObj' : rObj, //the resource object
			'parent_ct'			: 'rsd_modal_target',
			'clip_disp_ct'		: 'clip_edit_disp',
			'control_ct'		: 'clip_edit_ctrl',
			'media_type'		: mediaType,
			'p_rsdObj'			: _this,
			'controlActionsCb'	: _this.getClipEditControlActions( cp ),
			'enabled_tools'		: _this.enabled_tools
		};
		//set the base clip edit lib class req set:
		var clibs = ['mvClipEdit'];
		
		if( mediaType == 'image'){
			//display the mvClipEdit obj once we are done loading:
			mvJsLoader.doLoad( clibs, function(){
				//run the image clip tools
				_this.cEdit = new mvClipEdit( mvClipInit );
			});
		}else if( mediaType == 'video' || mediaType == 'audio'){
			js_log('media type:: ' + mediaType);
			//get any additional embedding helper meta data prior to doing the actual embed
			// normally this meta should be provided in the search result (but archive.org has another query for more media meta)
			rObj.pSobj.getEmbedTimeMeta( rObj, function(){
				//make sure we have the embedVideo libs:
				var runFlag = false;
				mvJsLoader.embedVideoCheck( function(){
					//strange concurency issue with callbacks 
					//@@todo try and figure out why this callback is fired twice
					if(!runFlag){
						runFlag = true;
					}else{
						js_log('only run embed vid once');
						return false;
					}
					js_log('append html: ' + rObj.pSobj.getEmbedHTML( rObj, {id:'embed_vid'}) );
					$j('#clip_edit_disp').html(
						rObj.pSobj.getEmbedHTML( rObj, {
							id : 'embed_vid'
						})
					);
					js_log("about to call rewrite_by_id::embed_vid");					
					//rewrite by id
					rewrite_by_id('embed_vid', function(){
						//grab any information that we got from the ROE xml or parsed from the media file
						rObj.pSobj.getEmbedObjParsedInfo( rObj, 'embed_vid' );
						//add the re-sizable to the doLoad request:
						clibs.push( '$j.ui.resizable');
						clibs.push( '$j.fn.hoverIntent');						
						mvJsLoader.doLoad(clibs, function(){
							//make sure the rsd_edit_img is hidden:
							$j('#rsd_edit_img').remove();
							//run the image clip tools
							_this.cEdit = new mvClipEdit( mvClipInit );
						});
					});
				});
			});
		}
	},
	checkRepoLocal:function( cp ){
		if( cp.local ){
			return true;
		}else{
			//check if we can embed the content locally per a domain name check:
			var local_host = parseUri( this.local_wiki_api_url ).host;
			if( cp.local_domains ) {
				for(var i=0;i < cp.local_domains.length; i++){
					var ld = cp.local_domains[i];
					 if( local_host.indexOf( ld ) != -1)
						 return true;
				}
			}
			return false;
		}
	},
	checkImportResource:function( rObj, cir_callback){
		var _this = this;		
		//add a loader ontop:
		$j.addLoaderDialog( gM('mwe-checking-resource') );		
		//extend the callback with close dialog
		var org_cir_callback = cir_callback;
		cir_callback = function( rObj ){
			var cat = org_cir_callback;			
			$j.closeLoaderDialog();				
			if( typeof org_cir_callback == 'function'){
				org_cir_callback( rObj );
			}
		}			
		//@@todo get the localized File/Image namespace name or do a general {NS}:Title
		var cp = rObj.pSobj.cp;
		var _this = this;

		//update base target_resource_title:
		rObj.target_resource_title = rObj.titleKey.replace(/File:|Image:/,'')

		//check if local repository
		//or if import mode if just "linking" (we should already have the 'url'

		if( this.checkRepoLocal( cp ) || this.import_url_mode == 'remote_link'){		
			//local repo jump directly to check Import Resource callback:
			 cir_callback( rObj );
		}else{						
			//check if the file is local (can be shared repo)  
			if( cp.check_shared ){			
				_this.checkForFile(rObj.target_resource_title, function(imagePage){				
					if( imagePage && imagePage['imagerepository'] == 'shared' ){						
						cir_callback( rObj );
					}else{
						_this.checkPrefixNameImport( rObj, cir_callback );
					}
				});
			}else{
				_this.checkPrefixNameImport(rObj, cir_callback);
			}
		}
	},
	checkPrefixNameImport: function(rObj, cir_callback){
		js_log('::checkPrefixNameImport:: ');
		var cp = rObj.pSobj.cp;
		var _this = this;		
		//update target_resource_title with resource repository prefix:
		rObj.target_resource_title = cp.resource_prefix + rObj.target_resource_title;							
		//check if the file exists: 
		_this.checkForFile( rObj.target_resource_title, function( imagePage ){		
			if( imagePage ){
				//update to local src
				rObj.local_src = imagePage['imageinfo'][0].url;
				//@@todo maybe  update poster too?
				rObj.local_poster = imagePage['imageinfo'][0].thumburl;				
				//update the title: 
				rObj.target_resource_title = imagePage.title.replace(/File:|Image:/,'');
				cir_callback( rObj );
			}else{
				//close the dialog and display the import interface: 
				$j.closeLoaderDialog();
				_this.doImportInterface(rObj, cir_callback);
			}
		});						
	},	
	doImportInterface : function( rObj, callback){		
		var _this = this;					
		js_log("doImportInterface:: update:"+ _this.cFileNS + ':' + rObj.target_resource_title);
		//update the rObj with import info
		rObj.pSobj.updateDataForImport( rObj );

		//setup the resource description from resource description:
		var wt = '{{Information '+"\n";

		if( rObj.desc ){
			wt += '|Description= ' + rObj.desc + "\n";
		}else{
			wt += '|Description= ' + gM('mwe-missing_desc_see_source', rObj.link ) + "\n";
		}

		//output search specific info
		wt+='|Source=' + rObj.pSobj.getImportResourceDescWiki( rObj ) + "\n";

		if( rObj.author )
			wt+='|Author=' + rObj.author +"\n";

		if( rObj.date )
			wt+='|Date=' + rObj.date +"\n";

		//add the Permision info:
		wt+='|Permission=' + rObj.pSobj.getPermissionWikiTag( rObj ) +"\n";

		if( rObj.other_versions )
			wt+='|other_versions=' + rObj.other_versions + "\n";

		wt+='}}';

		//get any extra categories or helpful links
		wt+= rObj.pSobj.getExtraResourceDescWiki( rObj );


		$j('#rsd_resource_import').remove();//remove any old resource imports

		//@@ show user dialog to import the resource
		$j( _this.target_container ).append('<div id="rsd_resource_import" '+
		'class="ui-state-highlight ui-widget-content ui-state-error" ' +
		'style="position:absolute;top:0px;left:0px;right:0px;bottom:0px;z-index:5">' +
			'<h3 style="color:red;padding:5px;">' + gM('mwe-resource-needs-import', [rObj.title, _this.upload_api_name] ) + '</h3>' +
				'<div id="rsd_preview_import_container" style="position:absolute;width:50%;bottom:0px;left:5px;overflow:auto;top:30px;">' +
					rObj.pSobj.getEmbedHTML( rObj, {
						'id': _this.target_container + '_rsd_pv_vid', 
						'max_height':'220',
						'only_poster':true
					}) + //get embedHTML with small thumb:
					'<br style="clear both">'+
					'<strong>'+gM('mwe-resource_page_desc') +'</strong>'+
					'<div id="rsd_import_desc" style="display:inline;">'+
						mv_get_loading_img('position:absolute;top:5px;left:5px') +
					'</div>'+
				'</div>'+
				'<div id="rds_edit_import_container" style="position:absolute;left:50%;' +
					'bottom:0px;top:30px;right:0px;overflow:auto;">'+
					'<strong>' + gM('mwe-local_resource_title') + '</strong><br>'+
					'<input type="text" size="30" value="' + rObj.target_resource_title + '" /><br>'+
					'<strong>' + gM('mwe-edit_resource_desc') + '</strong>' +
					'<textarea id="rsd_import_ta" id="mv_img_desc" style="width:90%;" rows="8" cols="50">' +
						wt +
					'</textarea><br>' +
					'<input type="checkbox" value="true" id="wpWatchthis" name="wpWatchthis" tabindex="7"/>' +
					'<label for="wpWatchthis">'+gM('mwe-watch_this_page')+'</label><br><br><br>' +
				'</div>'+
				//output the rendered and non-rendered version of description for easy switching:
		'</div>');
		var bPlaneTarget = _this.target_container +'~ .ui-dialog-buttonpane';
		$j(bPlaneTarget).html (
			//add the btns to the bottom: 
			$j.btnHtml(gM('mwe-do_import_resource'), 'rsd_import_doimport', 'check' ) + ' ' +
	
			$j.btnHtml(gM('mwe-update_preview'), 'rsd_import_apreview', 'refresh' ) + ' '+
	
			$j.btnHtml(gM('mwe-cancel_import'), 'rsd_import_acancel', 'close' ) + ' '
		).addClass('ui-state-error');
		
		//add hover:
		
		//update video tag (if a video) 
		if( rObj.mime.indexOf('video/') !== -1 )
			rewrite_by_id( $j(_this.target_container).attr('id') + '_rsd_pv_vid');
		
		//load the preview text:
		_this.getParsedWikiText( wt, _this.cFileNS +':'+ rObj.target_resource_title, function( o ){
			$j('#rsd_import_desc').html(o);
		});
		//add bindings:
		$j( bPlaneTarget + ' .rsd_import_apreview').btnBind().click(function(){
			js_log("do preview asset");
			$j('#rsd_import_desc').html( mv_get_loading_img() );			
			//load the preview text:
			_this.getParsedWikiText( $j('#rsd_import_ta').val(), _this.cFileNS +':'+ rObj.target_resource_title, function( o ){
				js_log('got updated preview: ');
				$j('#rsd_import_desc').html(o);
			});
		});
		$j( bPlaneTarget + ' .rsd_import_doimport').btnBind().click(function(){
			js_log("do import asset:" + _this.import_url_mode);
			//check import mode:					
			if( _this.import_url_mode == 'api' ){
				if( _this.upload_api_target == 'proxy' ){;
					_this.setupProxy( function(){
						_this.doImportAPI( rObj , callback);
					});												
				}else{
					_this.doImportAPI( rObj , callback);
				}
			}else{
				js_log("Error: import mode is not form or API (can not copy asset)");
			}
		});
		$j( bPlaneTarget + ' .rsd_import_acancel').btnBind().click(function(){
			$j('#rsd_resource_import').fadeOut("fast",function(){
				$j(this).remove();
				//restore buttons (from the clipEdit object::)
				_this.cEdit.updateInsertControlActions();
				$j(bPlaneTarget).removeClass('ui-state-error');
			});			
		});									
	},			
	/** 
	 * sets up the proxy for the remote inserts
	 */
	setupProxy:function(callback){
		var _this = this;
		
		if( _this.proxySetupDone ){
			if(callback) 
				callback();
			return ;
		}
		//setup the the proxy via mv_embed  $j.apiProxy loader:
		if( ! _this.upload_api_proxy_frame ){
			js_log("Error:: remote api but no proxy frame target");
			return false;
		}else{			
			$j.apiProxy(
				'client',
				{
					'server_frame' : _this.upload_api_proxy_frame									
				},function(){
					_this.proxySetupDone = true
					if(callback) 
						callback();
				}			
			);	
		}
	},
	checkForFile:function( fName, callback){
		js_log("checkForFile::");
		var _this = this;	 
		reqObj={
				'action':'query',
				'titles': _this.cFileNS + ':' + fName,
				'prop'		: 'imageinfo',
				'iiprop'	: 'url',
				'iiurlwidth': '400'
		};			
		//first check the api for imagerepository
		do_api_req( {
			'data':reqObj,
			'url':this.local_wiki_api_url
			},function(data){
				if( data.query.pages ){ 			
					for(var i in data.query.pages){		
						for(var j in data.query.pages[i]){
							if(j == 'missing' && data.query.pages[i].imagerepository != 'shared'){
								js_log(fName + " not found");
								callback( false );
								return ;
							}								
						}		
						//else page is found: 
						js_log(fName + "  found");
						callback( data.query.pages[i] );						
					}
				}
			}
		);
	},
	doImportAPI:function(rObj, cir_callback){
		var _this = this;
		js_log(":doImportAPI:");
		$j.addLoaderDialog( gM('mwe-importing_asset') );		
		//baseUploadInterface
		mvJsLoader.doLoad([
			'mvBaseUploadInterface',
			'$j.ui.progressbar'
		],function(){
			js_log('mvBaseUploadInterface ready');
			//initiate a upload object ( similar to url copy ):
			myUp = new mvBaseUploadInterface({
				'api_url' : _this.upload_api_target,
				'done_upload_cb':function(){
					js_log('doImportAPI:: run callback::' );
					//we have finished the upload:
				
					//close up the rsd_resource_import
					$j('#rsd_resource_import').remove();
					//return the parent callback:
					return cir_callback();					
				}
			});
			//get the edit token if we have it handy
			_this.getEditToken(function( token ){
				myUp.etoken = token;
				
				//close the loader now that we are ready to present the progress dialog::
				$j.closeLoaderDialog();
								
				myUp.doHttpUpload({
					'url'	    : rObj.src,
					'filename'  : rObj.target_resource_title,
					'comment'   : $j('#rsd_import_ta').val()
				});
			})


		});
	},
	getEditToken:function(callback){
		var _this = this;
		if( _this.upload_api_target != 'proxy'){
			//(if not a proxy) first try to get the token from the page:
			var etoken = $j("input[name='wpEditToken']").val();
			if(etoken){
				callback( etoken );
				return ;
			}
		}
		//@@todo try to load over ajax if( _this.local_wiki_api_url ) is set
		// (your on the api domain but are inserting from a normal page view)
		get_mw_token(null, _this.upload_api_target, function(token){
			callback( token );
		});		
	},	
	previewResource:function( rObj ){
		var _this = this;
		this.checkImportResource( rObj, function(){
			//put another window ontop:
			$j( _this.target_container ).append('<div id="rsd_preview_display"' +
					'style="position:absolute;overflow:hidden;z-index:4;top:0px;bottom:0px;right:0px;left:0px;background-color:#FFF;">' +
						mv_get_loading_img('top:30px;left:30px') +
					'</div>');

			var bPlaneTarget = _this.target_container +'~ .ui-dialog-buttonpane';
			var pTitle = $j( _this.target_container ).dialog('option', 'title');

			//update title:
			$j( _this.target_container ).dialog('option', 'title', gM('mwe-preview_insert_resource', rObj.title) );

			//update buttons preview:
			$j(bPlaneTarget).html( $j.btnHtml( gM('rsd_do_insert'), 'preview_do_insert', 'check') + ' ' )
				.children('.preview_do_insert')
				.click(function(){
					_this.insertResource( rObj );
				});
			//update cancel button
			$j(bPlaneTarget).append('<a href="#" class="preview_close">Do More Modification</a>')
				.children('.preview_close')
				.click(function(){
					$j('#rsd_preview_display').remove();
					//restore title:
					$j( _this.target_container ).dialog('option', 'title', pTitle);
					//restore buttons (from the clipEdit object::)
					_this.cEdit.updateInsertControlActions();
				});

			//update the preview_wtext
			_this.updatePreviewText( rObj );
			_this.getParsedWikiText(_this.preview_wtext, _this.target_title,
				function(phtml){
					$j('#rsd_preview_display').html( phtml );
					//update the display of video tag items (if any)
					mwdomReady(true);
				}
			);
		});
	},
	updatePreviewText:function( rObj ){
		var _this = this;

		if( _this.import_url_mode == 'remote_link' ){
			_this.cur_embed_code = rObj.pSobj.getEmbedHTML(rObj);
		}else{
			_this.cur_embed_code = rObj.pSobj.getEmbedWikiCode( rObj );
		}

		//insert at start if textInput cursor has not been set (ie == length)
		if( _this.caret_pos &&  _this.caret_pos.text){
			if( _this.caret_pos.text.length == _this.caret_pos.s)
				_this.caret_pos.s=0;
			_this.preview_wtext = _this.caret_pos.text.substring(0, _this.caret_pos.s) +
									_this.cur_embed_code +
								   _this.caret_pos.text.substring( _this.caret_pos.s );
		}else{
		   _this.preview_wtext =  $j(_this.target_textbox).val() +  _this.cur_embed_code;
		}
		//check for missing </refrences>
		if( _this.preview_wtext.indexOf('<references/>') ==-1 &&  _this.preview_wtext.indexOf('<ref>') != -1 )
			 _this.preview_wtext =  _this.preview_wtext + '<references/>';
	},
	getParsedWikiText:function( wikitext, title,  callback ){
		do_api_req( {
			'data':{'action':'parse',
					'text':wikitext
				   },
			'url':this.local_wiki_api_url
			},function(data){
				callback( data.parse.text['*'] );
			}
		);
	},
	insertResource:function( rObj){
		js_log('insertResource: ' + rObj.title);					
		
		var _this = this
		//dobule check that the resource is present:
		this.checkImportResource( rObj, function(){
			_this.updatePreviewText( rObj );
			$j(_this.target_textbox).val( _this.preview_wtext );

			//update the render area (if present)
			if(_this.target_render_area && _this.cur_embed_code){
				 //output with some padding:
				 $j(_this.target_render_area).append( _this.cur_embed_code + '<div style="clear:both;height:10px">')
				 
				 //update the player if video or audio:
				 if( rObj.mime.indexOf('audio')!=-1 ||
				 	 rObj.mime.indexOf('video')!=-1 ||
				 	 rObj.mime.indexOf('/ogg') !=-1){
					 mvJsLoader.embedVideoCheck(function(){
					 	mv_video_embed();
					 });
				 }
			}
			_this.closeAll();
		});
	},
	closeAll:function(){
		 var _this = this;
		 js_log("close all:: "  + _this.target_container);
		 _this.cancelClipEditCB();
		 $j(_this.target_container).dialog('close');
	},
	setResultBarControl:function( ){
		var _this = this;
		var box_dark_url	 = mv_skin_img_path + 'box_layout_icon_dark.png';
		var box_light_url	 = mv_skin_img_path + 'box_layout_icon.png';
		var list_dark_url	 = mv_skin_img_path + 'list_layout_icon_dark.png';
		var list_light_url	 = mv_skin_img_path + 'list_layout_icon.png';

		var about_desc ='';
		if( this.content_providers[this.disp_item] ){
			var cp = this.content_providers[this.disp_item];
			about_desc ='<span style="position:relative;top:0px;font-style:italic;">' +
					'<i>' + gM('mwe-results_from', [cp.homepage, cp.title]) + '</i></span>';
			$j('#tab-'+this.disp_item).append( '<div id="rds_results_bar">'+
				'<span style="float:left;top:0px;font-style:italic;">'+
					gM('rsd_layout')+' '+
					'<img id="msc_box_layout" ' +
						'title = "' + gM('rsd_box_layout') + '" '+
						'src = "' +  ( (_this.result_display_mode=='box')?box_dark_url:box_light_url ) + '" ' +
						'style="width:20px;height:20px;cursor:pointer;"> ' +
					'<img id="msc_list_layout" '+
						'title = "' + gM('rsd_list_layout') + '" '+
						'src = "' +  ( (_this.result_display_mode=='list')?list_dark_url:list_light_url ) + '" '+
						'style="width:20px;height:20px;cursor:pointer;">'+
					about_desc +
				'</span>'+
				'<span id="rsd_paging_ctrl" style="float:right;"></span>'+
				'</div>'
			);
			//get paging with bindings:
			this.getPaging('#rsd_paging_ctrl');

			$j('#msc_box_layout').hover(function(){
				$j(this).attr("src", box_dark_url );
			}, function(){
				$j(this).attr("src",  ( (_this.result_display_mode=='box')?box_dark_url:box_light_url ) );
			}).click(function(){
				$j(this).attr("src", box_dark_url);
				$j('#msc_list_layout').attr("src", list_light_url);
				_this.setDispMode('box');
			});

			$j('#msc_list_layout').hover(function(){
				$j(this).attr("src", list_dark_url);
			}, function(){
				$j(this).attr("src", ( (_this.result_display_mode=='list')?list_dark_url:list_light_url ) );
			}).click(function(){
				$j(this).attr("src", list_dark_url);
				$j('#msc_box_layout').attr("src", box_light_url);
				_this.setDispMode('list');
			});
		}
	},
	getPaging:function(target){
		var _this = this;
		var cp_id = this.disp_item;
		if( this.disp_item == 'upload'){
			var cp = _this.content_providers['this_wiki'];
		}else{		
			var cp = this.content_providers[ this.disp_item ];
		}		
		js_log('getPaging:'+ cp_id + ' len: ' + cp.sObj.num_results);
		var to_num = ( cp.limit > cp.sObj.num_results )?
						(parseInt( cp.offset ) + parseInt( cp.sObj.num_results ) ):
						( parseInt( cp.offset ) + parseInt( cp.limit) );
		var out = '';				
		
		//@@todo we should instead support the wiki number format template system instead of inline calls
		if( cp.sObj.num_results != 0 ){ 
			if( cp.sObj.num_results  >  cp.limit){		
				out+= gM( 'rsd_results_desc_total', [(cp.offset+1), to_num, $mw.lang.formatNumber( cp.sObj.num_results )] );
			}else{		
				out+= gM( 'rsd_results_desc', [(cp.offset+1), to_num] );
			}
		}
		//check if we have more results (next prev link)
		if(  cp.offset >=  cp.limit )
			out+=' <a href="#" id="rsd_pprev">' + gM('rsd_results_prev') + ' ' + cp.limit + '</a>';

		if( cp.sObj.more_results )
			out+=' <a href="#" id="rsd_pnext">' + gM('rsd_results_next') + ' ' + cp.limit + '</a>';

		$j(target).html(out);
		
		//set bindings
		$j('#rsd_pnext').click(function(){
			cp.offset += cp.limit;
			_this.runSearch( false );
		});
		
		$j('#rsd_pprev').click(function(){
			cp.offset -= cp.limit;
			if(cp.offset<0)
				cp.offset=0;
			_this.runSearch( false);
		});

		return;

	},
	selectTab:function( selected_cp_id ){
		js_log('select tab: ' + selected_cp_id);
		this.disp_item = selected_cp_id;
		if( this.disp_item == 'upload' ){
			this.doUploadInteface();
		}else{			
			//update the search results:
			this.runSearch();
		}
	},
	setDispMode:function(mode){
		js_log('setDispMode:' + mode);
		this.result_display_mode=mode;
		//run /update search display:
		this.drawOutputResults();
	}
};
