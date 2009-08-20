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
	"add_media_wizard" : "Add media wizard",
	"mv_media_search" : "Media search",
	"rsd_box_layout" : "Box layout",
	"rsd_list_layout" : "List layout",
	"rsd_results_desc" : "Results",
	"rsd_results_next" : "next",
	"rsd_results_prev" : "previous",
	"rsd_no_results" : "No search results for <b>$1<\/b>",
	"upload_tab" : "Upload",
	"rsd_layout" : "Layout : ",
	"rsd_resource_edit" : "Edit resource :  $1",
	"resource_description_page" : "Resource description page",
	"rsd_local_resource_title" : "Local resource title",
	"rsd_do_insert" : "Do insert",
	"cc_title" : "Creative Commons",
	"cc_by_title" : "Attribution",
	"cc_nc_title" : "Noncommercial",
	"cc_nd_title" : "No Derivative Works",
	"cc_sa_title" : "Share Alike",
	"cc_pd_title" : "Public Domain",
	"unknown_license" : "Unknown license",
	"no_import_by_url" : "This user or wiki <b>can not<\/b> import assets from remote URLs.<\/p><p>Do you need to login?<\/p><p>If permissions are set, you may have to enable $wgAllowCopyUploads (<a href=\"http : \/\/www.mediawiki.org\/wiki\/Manual : $wgAllowCopyUploads\">more information<\/a>).<\/p>",
	"results_from" : "Results from <a href=\"$1\" target=\"_new\" >$2<\/a>",
	"missing_desc_see_soruce" : "This asset is missing a description. Please see the [$1 orginal source] and help describe it.",
	"rsd_config_error" : "Add media wizard configuration error :  $1",
	"uploaded_itmes" : "Uploaded Items:",
	
	"your_recent_uploads" : "Your Recent Uploads",
	"upload_a_file": "Upload a New File"
});
var default_remote_search_options = {
	'profile':'mediawiki_edit',
	'target_container':null, //the div that will hold the search interface
	//if using a modeal dialog (instead of target_container) how close to the edge of the window should we go:
	'modal_edge_padding':'20px',

	'target_invocation': null, //the button or link that will invoke the search interface

	'default_provider_id':'all', //all or one of the content_providers ids

	'caret_pos':null,
	'local_wiki_api_url':null,

	//can be 'api', 'form', 'autodetect', 'remote_link'
	'import_url_mode': 'autodetect',

	'target_title':null,

	'target_textbox':null,
	'target_render_area': null, //where output render should go:
	'instance_name': null, //a globally accessible callback instance name
	'default_query':null, //default search query
	//specific to sequence profile
	'p_seq':null,
	'cFileNS':'File', //what is the cannonical namespace for images
					  //@@todo (should get that from the api or inpage vars)

	'enable_upload_tab':true, // if we want to enable an uploads tab:
	'upload_api_target'	   : 'http://localhost/wiki_trunk/api.php' // can be local or the url of the upload api.
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
	/*
	 * sets the default display item:
	 * can be any content_providers key or 'all'
	 */
	disp_item : 'upload',
	/** the default content providers list.
	 *
	 * (should be note that special tabs like "upload" and "combined" don't go into the content proviers list:
	 *
	 * @@todo we will want to load more per user-preference and per category lookup
	 */
	content_providers:{
		/*content_providers documentation:
		 *  @@todo we should move the bulk of the configuration to each file
		 *

			@enabled: whether the search provider can be selected
			@checked: whether the search provider will show up as seleatable tab (todo: user prefrence)
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

			//list all the domains where commons is local?
			// probably should set this some other way by doing an api query
			// or by seeding this config when calling the remote search?
			'local_domains': ['wikimedia','wikipedia','wikibooks'],
			//specific to wiki commons config:
			'search_title':false, //disable title search
			//set up default range limit
			'offset'			: 0,
			'limit'				: 30,
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
		'metavid':{
			'enabled':1,
			'checked':1,
			'title'	:'Metavid.org',
			'homepage':'http://metavid.org',
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
		}
	},
	//define the licenses
	// ... this will get complicated quick...
	// (just look at complexity for creative commons without exessive "duplicate data")
	// ie cc_by could be "by/3.0/us/" or "by/2.1/jp/" to infinitum...
	// some complexity should be negated by license equivalances.

	// but we will have to abstract into another class let content providers provide license urls
	// and we have to clone the license object and allow local overrides

	licenses:{
		//for now only support creative commons type licenses
		//used page: http://creativecommons.org/licenses/
		'cc':{
			'base_img_url':'http://upload.wikimedia.org/wikipedia/commons/thumb/',
			'base_license_url': 'http://creativecommons.org/licenses/',
			'licenses':{
				'by': 'by/3.0/',
				'by-sa': 'by-sa/3.0/',
				'by-nc-nd': 'by-nc-nd/3.0/',
				'by-nc': 'by-nc/3.0/',
				'by-nd': 'by-nd/3.0/',
				'by-nc-sa': 'by-nc-sa/3.0/',
				'by-sa': 'by-nc/3.0',
				'pd': 'publicdomain/'
			},
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
		if( typeof( this.licenses.cc.licenses[ license_key ]) == 'undefined')
			return js_error('could not find:' + license_key);
		//set the current license pointer:
		var cl = this.licenses.cc;
		var title = gM('cc_title');
		var imgs = '';
		var license_set = license_key.split('-');
		for(var i=0;i < license_set.length; i++){
			lkey =	 license_set[i];
			title += ' ' + gM( 'cc_' + lkey + '_title');
			imgs +='<img class="license_desc" width="20" src="' + cl.base_img_url +
				cl.license_img[ lkey ].im + '">';
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
		//js_log("getLicenceFromUrl::" + license_url);
		//first do a direct lookup check:
		for(var i in this.licenses.cc.licenses){
			var lkey = this.licenses.cc.licenses[i].split('/')[0];
			//guess by url trim
			if( parseUri(license_url).path.indexOf('/'+ lkey +'/') != -1){
				return this.getLicenceFromKey( i , license_url);
			}
		}
		//could not find it return unknown_license
		return {
			'title'	 	: gM('unknown_license'),
			'img_html'	: '<span>' + gM('unknown_license') + '</span>',
			'lurl'		: license_url
		};
	},
	//some default layout values:
	thumb_width		 : 80,
	image_edit_width	: 400,
	video_edit_width	: 400,
	insert_text_pos		: 0,	  //insert at the start (will be overwritten by the user cursor pos)
	result_display_mode : 'box', //box or list

	cUpLoader			: null,
	cEdit				: null,
	dmodalCss			: {},

	init: function( iObj ){
		var _this = this;
		js_log('remoteSearchDriver:init');
		for( var i in default_remote_search_options ) {
			if( iObj[i]){
				this[ i ] = iObj[i];
			}else{
				this[ i ] = default_remote_search_options[i];
			}
		}
		//update the base text:
		if(_this.target_textbox)
		   _this.getTexboxSelection();

		//set up the content provider config:
		if( this.cpconfig ){
			for(var cpc in cpconfig){
				for(var cinx in this.cpconfig[cpc]){
					if( this.content_providers[cpc] )
						this.content_providers[ cpc ][ cinx ] = this.cpconfig[cpc][ cinx];
				}
			}
		}

		//make sure the selected cp has an api to query against (if its a content_provider
		if( this.content_providers[ this.disp_item ] &&
			!this.content_providers[ this.disp_item ].api_url  ){
			for(var inx in this.content_providers){
				if( this.content_providers[ inx ].api_url ){
					this.disp_item = inx;
					break;
				}
			}
		}


		//set up the default model config:
		this.dmodalCss = {
			'width':'auto',
			'height':'auto',
			'top'	: this.modal_edge_padding,
			'left'	: this.modal_edge_padding,
			'right' : this.modal_edge_padding,
			'bottom': this.modal_edge_padding
		}


		//set up the target invocation:
		if( $j(this.target_invocation).length==0 ){
			js_log("RemoteSearchDriver:: no target invocation provided (will have to run your own doInitDisplay() )");
		}else{
			if(this.target_invocation){
				$j(this.target_invocation).css('cursor','pointer').attr('title', gM('add_media_wizard')).click(function(){
					_this.doInitDisplay();
				});
			}
		}
	},
	doInitDisplay:function(){
		var _this = this;
		//setup the parent container:
		this.init_modal();
		//fill in the html:
		this.init_interface_html();
		//bind actions:
		this.add_interface_bindings();

		//update the target bining to just unhide the dialog:
		$j(this.target_invocation).unbind().click(function(){
		 	  js_log("re-open");
			  //update the base text:
			  if( _this.target_textbox )
					_this.getTexboxSelection();
			  //$j(_this.target_container).dialog("open");
			  $j(_this.target_container).parents('.ui-dialog').fadeIn('slow');
		 });
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
			$j('body').append('<div id="rsd_modal_target" style="position:absolute;top:30px;left:0px;bottom:45px;right:0px;" title="' + gM('add_media_wizard') + '" ></div>');
			_this.target_container = '#rsd_modal_target';
			//js_log('appended: #rsd_modal_target' + $j(_this.target_container).attr('id'));
			//js_log('added target id:' + $j(_this.target_container).attr('id'));
			//get layout
			//layout = _this.getMaxModalLayout();
			$j(_this.target_container).dialog({
				bgiframe: true,
				autoOpen: true,
				modal: true,
				buttons: {
					'_': function() {
						//just a place-holder
					}
				},
				close: function() {
					js_log('closed modal');
					$j(this).parents('.ui-dialog').fadeOut('slow');
				}
			}).parent('.ui-dialog').css( _this.dmodalCss )
			//@@bind on resize to disable css dialog to update dmodelCss
			.bind('resizestart', function(event, ui) {
				 _this.dmodalCss = {};
				 $j(this).css({});
			})
			//bind on drag to remove preset style as well
			.bind('dragstart', function(event, ui) {
				 _this.dmodalCss = {};
				 $j(this).css({});
			});
			//update the child position: (some of this should be pushed up-stream via dialog config options
			$j(_this.target_container +'~ .ui-dialog-buttonpane').css({
				'position':'absolute',
				'left':'0px',
				'right':'0px',
				'bottom':'0px'
			});
			//re add cancel button
			_this.cancelClipEditCB();
			js_log('done setup of target_container: ' +
				$j(_this.target_container +'~ .ui-dialog-buttonpane').length);


			/*var resizeTimer = false;
			$j(window).bind('resize', function() {
				var adjustModal = function(){
					var layout = _this.getMaxModalLayout();
					//js_log("should adjust: h " + layout.h + ' width:' + layout.w);
					$j(_this.target_container).dialog('option', 'width', layout.w);
					$j(_this.target_container).dialog('option', 'height', layout.h);
				}
				if (resizeTimer) clearTimeout(resizeTimer);
				var resizeTimer = setTimeout(adjustModal, 100);
			});*/
		}
	},
	getMaxModalLayout:function(border){
		if(!border)
			border = 50;
		//js_log('setting h:' + (parseInt( $j(document).height() ) - parseInt(border*2)) + ' from:' + $j(document).height() );
		return {
			'h': parseInt( $j(document).height() ) - parseInt(border*4),
			'w': parseInt( $j(document).width() ) - parseInt(border*2),
			'r': border,
			't': border
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
						$j.btnHtml( gM('mv_media_search'), 'rms_search_button', 'search') +
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

		//do config variable reality checks:
		if( _this.upload_api_target == 'local' ){
			if( ! _this.local_wiki_api_url ){
				$j('#tab-upload').html( gM( 'rsd_config_error', 'missing_local_api_url' ) );
				return false;
			}else{
				_this.upload_api_target = _this.local_wiki_api_url;
			}
		}
		//make sure we have a url for the upload target:
		if(  parseUri( _this.upload_api_target ).host ==  _this.upload_api_target ){
			$j('#tab-upload').html( gM('rsd_config_error', 'bad_api_url') );
			return false;
		}
		//output the form
		//set the form action based on domain:
		if( parseUri( document.URL ).host == parseUri( _this.upload_api_target ).host ){
			mvJsLoader.doLoad(['$j.fn.simpleUploadForm'],function(){
								
				//get extened info about the file
				var cp = _this.content_providers['this_wiki'];
				//check for "this_wiki" enabled
				if(!cp.enabled){
					$j('#tab-upload').html('error this_wiki not enabled (can\'t get uploaded file info)');
					return false;
				}				
							
				//load  this_wiki search system to grab the rObj
				_this.loadSearchLib(cp, function(){
					//do basic layout form on left upload "bin" on right
					$j('#tab-upload').html('<table cellspacing="10">' +
					'<tr>' +
						'<td valign="top" style="width:350px;">' +
							'<h4>' + gM('upload_a_file') + '</h4>' +  
						 	'<div id="upload_form">' +						 	
						 		mv_get_loading_img() +
						 	'</div>' +
						'</td>' +
						'<td valign="top" id="upload_bin_cnt">' +
						'<h4>' + gM('your_recent_uploads') + '</h4>' +  
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
						"api_target" :	_this.upload_api_target ,
						"ondone_cb"	: function( resultData ){
							var wTitle = resultData['wpDestFile'];
							//add a loading div
							$j( _this.target_container ).append('<div id="temp_edit_loader" '+
								'style="position:absolute;top:0px;left:0px;bottom:5px;right:4px;background-color:#FFF;">' +
									mv_get_loading_img('position:absolute;top:30px;left:30px') +
							'</div>');											
								cp.sObj.addByTitle( wTitle, function( rObj ){
									$j( _this.target_container ).find('#temp_edit_loader').remove();
									//redraw (with added result if new) 
									_this.drawOutputResults();																	
									//pull up recource editor: 
									_this.resourceEdit( rObj, $j('#res_upload_' + rObj.id).get(0) );
								});								
							//return false to close progress window:
							return false;
						}
					})
				});
			});
		}else{
			//setup the proxy
			js_log('do proxy:: ' + parseUri( _this.upload_api_target ).host);
			$j('#tab-upload').html('proxy upload not yet ready');
		}
	},
	runSearch: function(){
		js_log("f:runSearch::" + this.disp_item);
		//draw_direct_flag
		var draw_direct_flag = true;
		if( !this.content_providers[this.disp_item] ){
			//check if its the special upload tab case:
			if( this.disp_item == 'upload'){
				this.doUploadInteface();
			}else{
				js_log("can't run search for:" + this.disp_item);
			}
			return false;
		}
		cp = this.content_providers[this.disp_item];

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
			//set the content to loading while we do the search:
			$j('#tab-' + this.disp_item).html( mv_get_loading_img() );

			//make sure the search library is loaded and issue the search request
			this.getLibSearchResults( cp );
		}
	},
	//issue a api request & cache the result
	//this check can be avoided by setting the this.import_url_mode = 'api' | 'form' | insted of 'autodetect' or 'none'
	checkForCopyURLSupport:function ( callback ){
		var _this = this;
		js_log('checkForCopyURLSupport:: ');
		//see if we already have the import mode:
		if( this.import_url_mode != 'autodetect'){
			js_log('import mode: ' + _this.import_url_mode);
			callback();
		}
		//if we don't have the local wiki api defined we can't auto-detect use "link"
		if(!_this.local_wiki_api_url){
			js_log('import mode: remote link (no import_wiki_api_url)');
			_this.import_url_mode = 'remote_link';
			callback();
		}
		if( this.import_url_mode == 'autodetect' ){
			do_api_req( {
				'data': { 'action':'paraminfo', 'modules':'upload' },
				'url': _this.local_wiki_api_url
			}, function(data){
				if( typeof data.paraminfo.modules[0].classname == 'undefined'){
					//@@todo would be nice if API permission on: action=query&meta=userinfo&uiprop=rights
					// upload_by_url property reflected if $wgAllowCopyUploads config value .. oh well.
					$j.ajax({
						type: "GET",
						dataType: 'html',
						url: wgArticlePath.replace( '$1', 'Special:Upload' ), //@@todo may have problems in localized special pages
															   //(could hit meta=siteinfo & specialpagealiases )
															   // but might be overkill for now cuz we want to switch to new-upload branch soon.
						success: function( form_html ){
							if( form_html.indexOf( 'wpUploadFileURL' ) != -1){
								_this.import_url_mode = 'form';
							}else{
								_this.import_url_mode = 'none';
							}
							js_log('import mode: ' + _this.import_url_mode);
							callback();
						},
						error: function(){
							js_log('error in getting Special:Upload page');
							_this.import_url_mode = 'none';

							js_log('import mode: ' + _this.import_url_mode);
							callback();
						}
					});
				}else{
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
				}
			});
		}
	},
	/*
	* checkForCopyURLPermission:
	* not really nessesary the api request to upload will return apopprirate error if the user lacks permission. or $wgAllowCopyUploads is set to false
	* (use this function if we want to issue a warning up front)
	*/
	checkForCopyURLPermission:function( callback ){
		var _this = this;
		//do api check:
		do_api_req( {
				'data':{ 'action' : 'query', 'meta' : 'userinfo', 'uiprop' : 'rights' },
				'url': _this.local_wiki_api_url,
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
				$j('#tab-' + this.disp_item).html( '<div style="padding:10px">'+ gM('no_import_by_url') +'</div>');
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
			cp.lib +'Search'
		], function(){
			js_log("loaded lib:: " + cp.lib );
			//else we need to run the search:
			var iObj = {'cp':cp, 'rsd':_this};
			eval('cp.sObj = new '+cp.lib+'Search( iObj );');
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
					loading_done=false;
			}
		}
		if( loading_done ){
			this.drawOutputResults();
		}else{
			//make sure the instance name is up-to-date refrence to _this;
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
								o+='<img alt="' + cp.title +'" src="' + mv_skin_img_path + 'remote_cp/' + cp_id + '_tab.png">';
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
			if( this.enable_upload_tab ){
				o+='<li class="rsd_cp_tab" ><a id="rsd_tab_upload" href="#tab-upload">' + gM('upload_tab') + '</a></li>';
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

		/*$j('.rsd_cp_tab').click(function(){
			_this.selectTab( $j(this).attr('id').replace(/rsd_tab_/, '') );
		});*/

		//setup key binding (no longer nessesary tabs provide this functionality)
		/*$j().keyup(function(e){
			js_log('keyup on : ' +e.which );
			//if escape pressed clear the interface:
			if(e.which == 27)
				_this.closeAll();
		});*/

	},
	//resource title
	getResourceFromTitle:function( rTitle , callback){
		var _this = this;
		reqObj={
			'action':'query',
			'titles': _this.cFileNS + ':' + rTitle
		};
		do_api_req( {
			'data':reqObj,
			'url':this.local_wiki_api_url
			}, function(data){
				//@@todo propogate the rObj
				var rObj = {};
			}
		);
	},
	//@@todo we could load the id with the content provider id to find the object faster...
	getResourceFromId:function( rid ){
		//js_log('getResourceFromId:' + rid );
		//strip out /res/ if preset:				
		rid = rid.replace(/res_/, '');
		js_log("looking at: " + rid);
		p = rid.split('_');
		var cp_id = p[0];
		var rid = p[1];		
		if(cp_id == 'upload')
			cp_id = 'this_wiki';
			
		var cp = this.content_providers[cp_id];
		
		if(cp['sObj'] && cp.sObj.resultsObj[rid]){
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
			var cp = this.content_providers['this_wiki'];
		}else{			
			var cp = this.content_providers[this.disp_item];
			tab_target = '#tab-' + cp_id;			
		}
		//empty the existing results:
		$j(tab_target).empty();

		//output the results bar / controls
		_this.setResultBarControl();

		var drawResultCount	=0;

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
						o+='<img title="'+rItem.title+'" class="rsd_res_item" id="res_' + cp_id + '_' + rInx +
								'" style="width:' + _this.thumb_width + 'px;" src="' +
								cp.sObj.getImageTransform( rItem, {'width':_this.thumb_width } )
								+ '">';
						//add a linkback to resource page in upper right:
						if( rItem.link )
							o+='<a target="_new" style="position:absolute;top:0px;right:0px" title="' +
								 gM('resource_description_page') +
								'" href="' + rItem.link + '"><img src="http://upload.wikimedia.org/wikipedia/commons/6/6b/Magnify-clip.png"></a>';
						//add license icons if present
						if( rItem.license )
							o+= _this.getlicenseImgSet( rItem.license );
					o+='</div>';
				}else if(_this.result_display_mode == 'list'){
					o+='<div id="mv_result_' + rInx + '" class="mv_clip_list_result" style="width:90%">';
						o+='<img title="'+rItem.title+'" class="rsd_res_item" id="res_' + cp_id + '_' + rInx +'" style="float:left;width:' +
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

		js_log( ' drawResultCount :: ' + drawResultCount + ' append: ' + $j('#rsd_q').val() );

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
			//also set the animated image if avaliable
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
		//hide the results container
		$j('#rsd_results_container').hide();			
		//remove any old instance: 
		$j( _this.target_container ).find('#rsd_resource_edit').remove();
		//add the edit layout window with loading place holders	
		$j( _this.target_container ).append('<div id="rsd_resource_edit" '+
			'style="position:absolute;top:0px;left:0px;bottom:5px;right:4px;background-color:#FFF;">' +
				'<div id="clip_edit_disp" style="position:absolute;' + overflow_style + 'width:100%;height:100%;padding:5px;'+
					'width:' + (maxWidth) + 'px;" >' +
						mv_get_loading_img('position:absolute;top:30px;left:30px') +
				'</div>'+
				'<div id="clip_edit_ctrl" class="ui-widget ui-widget-content ui-corner-all" style="position:absolute;'+
					'left:' + ( maxWidth + 10 ) +'px;top:5px;bottom:10px;right:0px;overflow:auto;padding:5px;">'+
						mv_get_loading_img() +
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
		$j( _this.target_container ).dialog( 'option', 'title', gM('add_media_wizard')+': '+ gM('rsd_resource_edit', rObj.title ) );
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


		//assume we keep aspect ratio for the thumbnail that we clicked:
		var tRatio = $j(rsdElement).height() / $j(rsdElement).width();
		if(	! tRatio )
			var tRatio = 1; //set ratio to 1 if the width of the thumbnail can't be found for some reason

		js_log('set from ' +  $j('#rsd_edit_img').width()+'x'+ $j('#rsd_edit_img').height() + ' to init thumbimage to ' + maxWidth + ' x ' + parseInt( tRatio * maxWidth) );
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
		js_log('do load the media editor:');
		//do load the media Editor
		_this.doMediaEdit( rObj , mediaType );
	},
	loadHQImg:function(rObj, size, target_img_id, callback){
		//get the HQ image url:
		rObj.pSobj.getImageObj( rObj, size, function( imObj ){
			rObj['edit_url'] = imObj.url;

			js_log("edit url: " + rObj.edit_url);
			//update the rObj
			rObj['width'] = imObj.width;
			rObj['height'] = imObj.height;

			//see if we need to animate some transition
			var newSize = false;
			if( size.width != imObj.width ){
				js_log('loadHQImg:size mismatch: ' + size.width + ' != ' + imObj.width );
				newSize={
					'width':imObj.width + 'px',
					'height':imObj.height + 'px'
				}
				//set the target id to the new size:
				$j('#'+target_img_id).animate( newSize );
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
		var b_target =   _this.target_container + '~ .ui-dialog-buttonpane';
		$j('#rsd_resource_edit').remove();
		//restore the resource container: 
		$j('#rsd_results_container').show();
		//restore the title:
		$j( _this.target_container ).dialog( 'option', 'title', gM('add_media_wizard'));
		js_log("should update: " + b_target + ' with: cancel');
		//restore the buttons:
		$j(b_target).html( $j.btnHtml( 'Cancel' , 'mv_cancel_rsd', 'close'))
			.children('.mv_cancel_rsd')
			.btnBind()
			.click(function(){
				$j( _this.target_container).dialog('close');
			})

	},
	/*set-up the control actions for clipEdit with relevent callbacks */
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
		var mvClipInit = {
				'rObj':rObj, //the resource object
				'parent_ct'			: 'rsd_modal_target',
				'clip_disp_ct'		: 'clip_edit_disp',
				'control_ct'		: 'clip_edit_ctrl',
				'media_type'		: mediaType,
				'p_rsdObj'			: _this,
				'controlActionsCb'	: _this.getClipEditControlActions( cp )
		};

		var clibs = ['mvClipEdit'];
		if( mediaType == 'image'){
			//display the mvClipEdit obj once we are done loading:
			mvJsLoader.doLoad( clibs, function(){
				//run the image clip tools
				_this.cEdit = new mvClipEdit( mvClipInit );
			});
		}
		if( mediaType == 'video' || mediaType == 'audio'){
			//get any additonal embedding helper meta data prior to doing the acutal embed
			// normally this meta should be provided in the search result (but archive.org has a seperate query for more meida meta)
			rObj.pSobj.getEmbedTimeMeta( rObj, function(){
				//make sure we have the embedVideo libs:
				mvJsLoader.embedVideoCheck( function(){
					js_log('append html: ' + rObj.pSobj.getEmbedHTML( rObj, {id:'embed_vid'}) );
					$j('#clip_edit_disp').html(
						rObj.pSobj.getEmbedHTML( rObj, {id:'embed_vid'})
					);
					//rewrite by id
					rewrite_by_id('embed_vid',function(){
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
		//@@todo get the localized File/Image namespace name or do a general {NS}:Title
		var cp = rObj.pSobj.cp;
		var _this = this;

		//update base target_resource_title:
		rObj.target_resource_title = rObj.titleKey.replace(/File:|Image:/,'')

		//check if local repository
		//or if import mode if just "linking" (we should alaredy have the 'url'

		if( this.checkRepoLocal( cp ) || this.import_url_mode == 'remote_link'){
			//local repo jump directly to check Import Resource callback:
			 cir_callback( rObj );
		}else{
			//update target_resource_title with resource repository prefix:
			rObj.target_resource_title = cp.resource_prefix + rObj.target_resource_title;

			//check if the resource is not already on this wiki
			reqObj={
				'action':'query',
				'titles': _this.cFileNS + ':' + rObj.target_resource_title,
				'prop'		: 'imageinfo',
				'iiprop'	: 'url',
				'iiurlwidth': '400'
			};

			do_api_req( {
				'data':reqObj,
				'url':this.local_wiki_api_url
				}, function(data){
					var found_title = false;
					for(var i in data.query.pages){
						if( i != '-1' && i != '-2' ){
							js_log('found title: ' + i + ':' +  data.query.pages[i]['title']);
							found_title=data.query.pages[i]['title'];
							//update to local src
							rObj.local_src = data.query.pages[i]['imageinfo'][0].url;
							//@@todo maybe  update poster too?
							rObj.local_poster = data.query.pages[i]['imageinfo'][0].thumburl;
						}
					}
					if( found_title ){
						js_log("checkImportResource:found title:" + found_title);
						//resource is already present (or resource with same name is already present)
						rObj.target_resource_title = found_title.replace(/File:|Image:/,'');
						cir_callback( rObj );
					}else{
						js_log("resource not present: update:"+ _this.cFileNS + ':' + rObj.target_resource_title);

						//update the rObj with import info
						rObj.pSobj.updateDataForImport( rObj );

						//setup the resource description from resource description:
						var wt = '{{Information '+"\n";

						if( rObj.desc ){
							wt += '|Description= ' + rObj.desc + "\n";
						}else{
							wt += '|Description= ' + gM('missing_desc_see_soruce', rObj.link ) + "\n";
						}

						//output person and bill info if
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
						'style="position:absolute;top:50px;left:50px;right:50px;bottom:50px;z-index:5">' +
							'<h3 style="color:red">Resource: <span style="color:black">' + rObj.title + '</span> needs to be imported</h3>'+
								'<div id="rsd_preview_import_container" style="position:absolute;width:50%;bottom:0px;left:0px;overflow:auto;top:30px;">' +
									rObj.pSobj.getEmbedHTML( rObj, {'id': _this.target_container + '_rsd_pv_vid', 'max_height':'200','only_poster':true} )+ //get embedHTML with small thumb:
									'<br style="clear both">'+
									'<strong>Resource Page Description:</strong>'+
									'<div id="rsd_import_desc" syle="display:inline;">'+
										mv_get_loading_img('position:absolute;top:5px;left:5px') +
									'</div>'+
								'</div>'+
								'<div id="rds_edit_import_container" style="position:absolute;left:50%;' +
									'bottom:0px;top:30px;right:0px;overflow:auto;">'+
									'<strong>Local Resource Title:</strong><br>'+
									'<input type="text" size="30" value="' + rObj.target_resource_title + '" readonly="true"><br>'+
									'<strong>Edit WikiText Resource Description:</strong>(will be replaced by forms soon)' +
									'<textarea id="rsd_import_ta" id="mv_img_desc" style="width:90%;" rows="8" cols="50">' +
										wt +
									'</textarea><br>' +
									'<input type="checkbox" value="true" id="wpWatchthis" name="wpWatchthis" tabindex="7"/>' +
									'<label for="wpWatchthis">Watch this page</label><br><br><br>' +

									$j.btnHtml('Do Import Resource', 'rsd_import_doimport', 'check' ) + ' ' +

									$j.btnHtml('Update Preview', 'rsd_import_apreview', 'refresh' ) + '<div style="clear:both;height:20px;"/>' +

									$j.btnHtml('Cancel Import', 'rsd_import_acancel', 'close' ) + ' ' +

								'</div>'+
								//output the rendered and non-renderd version of description for easy swiching:
						'</div>');
						//add hover:
						//update video tag
						rewrite_by_id(_this.target_container + '_rsd_pv_vid');
						//load the preview text:
						_this.getParsedWikiText( wt, _this.cFileNS +':'+ rObj.target_resource_title, function( o ){
							$j('#rsd_import_desc').html(o);
						});
						//add bidings:
						$j( _this.target_container + ' .rsd_import_apreview').btnBind().click(function(){
							/*$j('#rsd_import_desc').show().html(
								mv_get_loading_img()
							);*/
							//load the preview text:
							_this.getParsedWikiText( $j('#rsd_import_ta').val(), _this.cFileNS +':'+ rObj.target_resource_title, function( o ){
								js_log('got updated preivew: ');
								$j('#rsd_import_desc').html(o);
							});
						});
						$j(_this.target_container + ' .rsd_import_doimport').btnBind().click(function(){
							//check import mode:
							if(_this.import_url_mode=='form'){
								_this.doImportSpecialPage( rObj, cir_callback );
							}else if( _this.import_url_mode=='api'){
								_this.doImportAPI( rObj , cir_callback);
							}else{
								js_log("Error: import mode is not form or API (can not copy asset)");
							}
						});
						$j( _this.target_container + ' .rsd_import_acancel').btnBind().click(function(){
							$j('#rsd_resource_import').fadeOut("fast",function(){
								$j(this).remove();
							});
						});
					}
				}
			);
		}
	},
	doImportAPI:function(rObj, cir_callback){
		var _this = this;
		//baseUploadInterface
		mvJsLoader.doLoad([
			'mvBaseUploadInterface',
			'$j.ui.progressbar'
		],function(){
			//initicate a download similar to url copy:
			myUp = new mvBaseUploadInterface({
				'api_url' : _this.local_wiki_api_url,
				'done_upload_cb':function(){
				   //we have finished the upload:

				   //close up the rsd_resource_import
				   $j('#rsd_resource_import').remove();
				   //run the parent callback:
				   cir_callback();
				   //return false to avoid BaseUploadInterface done actions
				   return false;
				}
			});
			//set the edit token if we have it handy
			_this.getEditToken(function( token ){
				myUp.etoken = token;
				myUp.doHttpUpload({
					'url'	    : rObj.src,
					'filename'  : rObj.target_resource_title,
					'comment'   : $j('#rsd_import_ta').val()
				});
			})


		});
	},
	getEditToken:function(callback){
		//first try the page form:
		var etoken = $j("input[name='wpEditToken']").val();
		if(etoken){
			callback( etoken );
			return ;
		}
		//@@todo try to load over ajax if( _this.local_wiki_api_url ) is set
		// (your on the api domain but are inserting from a normal page view)
		if( _this.local_wiki_api_url){
			get_mw_token(null, _this.local_wiki_api_url, function(token){
					callback( token );
			})
		}
		callback(false);
		return false;
	},
	/**
	 * doImportSpecialPage
	 * can be depricated once we support upload api support is widespred.
	 */
	doImportSpecialPage:function(rObj, cir_callback){
		var _this = this;
		 //get an edittoken:
		do_api_req( {
			'data':	{	'action':'query',
						'prop':'info',
						'intoken':'edit',
						'titles': rObj.titleKey
					},
			'url':_this.local_wiki_api_url
			}, function(data){
				//could recheck if it has been created in the mean time
				if( data.query.pages[-1] ){
					var editToken = data.query.pages[-1]['edittoken'];
					if(!editToken){
						//@@todo give an ajax login or be more friendly in some way:
						js_error("You don't have permission to upload (are you logged in?)");
						//remove top level:
						$j('#modalbox').fadeOut("normal",function(){
							$j(this).remove();
							$j('#mv_overlay').remove();
						});
					}else{
						//not sure if we can do remote url uploads (so just do a local post)
						js_log('got token for new page:' +editToken);
						var postVars = {
							'wpSourceType'			:'web',
							'wpUploadFileURL'	 	: rObj.src,
							'wpDestFile'		  	: rObj.target_resource_title,
							'wpUploadDescription' 	: $j('#rsd_import_ta').val(),
							'wpWatchthis'		 	: $j('#wpWatchthis').val(),
							'wpUpload'				: 'Upload file'
						}
						//set to uploading:
						$j('#rsd_resource_import').append('<div id="rsd_import_progress"'+
							'style="position:absolute;top:0px;'+
								'left:0px;width:100%;height:100%;'+
								'z-index:5;background:#FFF;overflow:auto;">'+
									'<div style="position:absolute;left:30%;right:30%"><h3>Importing Asset</h3><br>' +
										mv_get_loading_img('','mv_loading_bar_img') +
									'</div>'+
							'</div>'
						);
						$j.post(wgArticlePath.replace(/\$1/,'Special:Upload'),
							postVars,
							function(data){
								//@@todo this will be replaced once we add upload image support to the api.

								//very basic test to see if we got passed to the image page:
								//@@todo more normalization stuff
								var sstring ='var wgPageName = "' + _this.cFileNS + ':' + rObj.target_resource_title.replace(/ /g,'_') +'"';
								if(data.indexOf( sstring ) !=-1){
									js_log('found: ' + sstring);
									$j('#rsd_resource_import').remove();
									cir_callback( rObj );
								}else{
									js_log("Error or warning: (did not find: \"" + sstring + ' in output' );
									pos_etitle = '<h1 class="firstHeading">';
									var error_txt = form_txt = '';
									var res = grabWikiFormError( data );

									if( res.error_txt )
										error_txt = res.error_txt;

									if( res.form_txt )
										form_txt = res.form_txt;

									js_log( 'error text is: ' + error_txt );
									$j( '#rsd_resource_import' ).html( '<h3>Error</h3>' + error_txt + '<br>' + form_txt +
											'<br>'+
										'<a href="#" id="rsd_import_error" >Cancel import</a>'
									);
									//set up cancel action:
									$j('#rsd_import_error').click(function(){
										$j('#rsd_resource_import').remove();
									});
								}

							}
						);
					}
				}
			}
		);
	},
	previewResource:function( rObj ){
		var _this = this;
		this.checkImportResource( rObj, function(){
			//put another window ontop:
			$j( _this.target_container ).append('<div id="rsd_preview_display"' +
					'style="position:absolute;overflow:hidden;z-index:4;top:0px;bottom:75px;right:0px;left:0px;background-color:#FFF;">' +
						mv_get_loading_img('top:30px;left:30px') +
					'</div>');

			var bPlaneTarget = _this.target_container +'~ .ui-dialog-buttonpane';
			var pTitle = $j( _this.target_container ).dialog('option', 'title');

			//update title:
			$j( _this.target_container ).dialog('option', 'title', 'Preview Insert of Resource: ' + rObj.title );

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
				 //update if its video or audio:
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
					'<i>' + gM('results_from', [cp.homepage, cp.title]) + '</i></span>';
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
		var cp = this.content_providers[ this.disp_item ];
		//js_log('getPaging:'+ cp_id + ' len: ' + cp.sObj.num_results);
		var to_num = ( cp.limit > cp.sObj.num_results )?
						(cp.offset + cp.sObj.num_results):
						(cp.offset + cp.limit);
		var out = gM('rsd_results_desc') + ' ' +  (cp.offset+1) + ' to ' + to_num;
		//check if we have more results (next prev link)
		if(  cp.offset >=  cp.limit )
			out+=' <a href="#" id="rsd_pprev">' + gM('rsd_results_prev') + ' ' + cp.limit + '</a>';

		if( cp.sObj.more_results )
			out+=' <a href="#" id="rsd_pnext">' + gM('rsd_results_next') + ' ' + cp.limit + '</a>';

		$j(target).html(out);
		//set bindings
		$j('#rsd_pnext').click(function(){
			cp.offset += cp.limit;
			_this.runSearch();
		});
		$j('#rsd_pprev').click(function(){
			cp.offset -= cp.limit;
			if(cp.offset<0)
				cp.offset=0;
			_this.runSearch();
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
