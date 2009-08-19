/*  the base video control JSON object with default attributes
*	for supported attribute details see README
*/

loadGM({
	"loading_plugin" : "loading plugin <blink>...<\/blink>",
	"select_playback" : "Set playback preference",
	"link_back" : "Link back",
	"error_load_lib" : "Error: mv_embed was unable to load required JavaScript libraries.\nInsert script via DOM has failed. Please try reloading this page.",
	"error_swap_vid" : "Error: mv_embed was unable to swap the video tag for the mv_embed interface",
	"add_to_end_of_sequence" : "Add to end of sequence",
	"missing_video_stream" : "The video file for this stream is missing",
	"play_clip" : "Play clip",
	"pause_clip" : "Pause clip",
	"volume_control" : "Volume control",
	"player_options" : "Player options",
	"closed_captions" : "Close captions",
	"player_fullscreen" : "Fullscreen",
	"next_clip_msg" : "Play next clip",
	"prev_clip_msg" : "Play previous clip",
	"current_clip_msg" : "Continue playing this clip",
	"seek_to" : "Seek to",
	"download_segment" : "Download selection:",
	"download_full" : "Download full video file:",
	"download_right_click" : "To download, right click and select <i>Save target as...<\/i>",
	"download_clip" : "Download video",
	"download_text" : "Download text (<a style=\"color:white\" title=\"cmml\" href=\"http:\/\/wiki.xiph.org\/index.php\/CMML\">CMML<\/a> xml):",
	"download" : "Download",
	"share" : "Share",
	"credits" : "Credits",
	"clip_linkback" : "Clip source page",
	"chose_player" : "Choose Video Player",
	"share_this_video" : "Share this video",
	"video_credits" : "Video credits",
	"menu_btn" : "Menu",
	"close_btn" : "Close",
	"mv_ogg-player-vlc-mozilla" : "VLC plugin",
	"mv_ogg-player-videoElement" : "Native Ogg video support",
	"mv_ogg-player-vlc-activex" : "VLC ActiveX",
	"mv_ogg-player-oggPlugin" : "Generic Ogg plugin",
	"mv_ogg-player-quicktime-mozilla" : "Quicktime plugin",
	"mv_ogg-player-quicktime-activex" : "Quicktime ActiveX",
	"mv_ogg-player-cortado" : "Java Cortado",
	"mv_ogg-player-flowplayer" : "Flowplayer",
	"mv_ogg-player-selected" : " (selected)",
	"mv_ogg-player-omtkplayer" : "OMTK Flash Vorbis",
	"mv_generic_missing_plugin" : "You browser does not appear to support the following playback type: <b>$1<\/b><br \/>Visit the <a href=\"http:\/\/commons.wikimedia.org\/wiki\/Commons:Media_help\">Playback Methods<\/a> page to download a player.<br \/>",
	"mv_for_best_experience" : "For a better video playback experience we recommend:<br \/><b><a href=\"http:\/\/www.mozilla.com\/en-US\/firefox\/upgrade.html?from=mwEmbed\">Firefox 3.5<\/a>.<\/b>",
	"mv_do_not_warn_again" : "Dissmiss for now.",
	"playerselect" : "Players"
});

var default_video_attributes = {
	"id":null,
	"class":null,
	"style":null,
	"name":null,
	"innerHTML":null,
	"width":"320",
	"height":"240",

	//video attributes:
	"src":null,
	"autoplay":false,
	"start":0,
	"end":null,
	"controls":true,
	"muted":false,

	//roe url (for xml based metadata)
	"roe":null,
	//if roe includes metadata tracks we can expose a link to metadata
	"show_meta_link":true,

	//default state attributes per html5 spec:
	//http://www.whatwg.org/specs/web-apps/current-work/#video)
	"paused":true,
	"readyState":0,  //http://www.whatwg.org/specs/web-apps/current-work/#readystate
	"currentTime":0, //current playback position (should be updated by plugin)
	"duration":null,   //media duration (read from file or the temporal url)
	"networkState":0,

	"startOffset":null, //if serving an ogg_chop segment use this to offset the presentation time

	//custom attributes for mv_embed:
	"play_button":true,
	"thumbnail":null,
	"linkback":null,
	"embed_link":true,
	"download_link":true,
	"type":null	 //the content type of the media
};
/*
 * the base source attibute checks
 */
var mv_default_source_attr= new Array(
	'id',
	'src',
	'title',
	'URLTimeEncoding', //boolean if we support temporal url requests on the source media
	'startOffset',
	'durationHint',
	'start',
	'end',
	'default',
	'lang'
);

/*
* Converts all occurrences of <video> tag into video object
*/
function mv_video_embed(swap_done_callback, force_id){
	mvEmbed.init( swap_done_callback, force_id );
}
mvEmbed = {
	//flist stores the set of functions to run after the video has been swaped in.
	flist:new Array(),
	init:function( swap_done_callback, force_id ){

		if(swap_done_callback)
			mvEmbed.flist.push( swap_done_callback );

		//get mv_embed location if it has not been set
		js_log('mv_embed ' + MV_EMBED_VERSION);

		var loadPlaylistLib=false;

		var eAction = function(this_elm){
			js_log( "Do SWAP: " + $j(this_elm).attr("id") + ' tag: '+ this_elm.tagName.toLowerCase() );

			if( $j(this_elm).attr("id") == '' ){
				$j(this_elm).attr("id", 'v'+ global_player_list.length);
			}
			//stre a global reference to the id
		   global_player_list.push( $j(this_elm).attr("id") );
		   //if video doSwap
		   switch( this_elm.tagName.toLowerCase()){
			   case 'video':
				    var videoInterface = new embedVideo(this_elm);
					mvEmbed.swapEmbedVideoElement( this_elm, videoInterface );
			   break;
			   case 'audio':
				   var videoInterface = new embedVideo(this_elm);
				   videoInterface.type ='audio';
				   mvEmbed.swapEmbedVideoElement( this_elm, videoInterface );
			   break;
			   case 'playlist':
				   loadPlaylistLib=true;
			   break;
		   }
		}

		if( force_id == null && force_id != '' ){
			var j_selector = 'video,audio,playlist';
		}else{
			var j_selector = '#' + force_id;
		}
		//process selected elements:
		//ie8 does not play well with the jQuery video,audio,playlist selector use native:
		if($j.browser.msie && $j.browser.version >= 8){
			jtags = j_selector.split(',');
			for( var i=0; i < jtags.length; i++){
				$j( document.getElementsByTagName( jtags[i] )).each(function(){
					eAction(this);
				});
			}
		}else{
			$j( j_selector ).each(function(){
				eAction(this);
			});
		}
		if(loadPlaylistLib){
			mvJsLoader.doLoad([
				'mvPlayList',
				'$j.ui',	//include dialog for pop-ing up thigns
				'$j.ui.dialog'
			], function(){
				$j('playlist').each(function(){
					//create new playlist interface:
					var plObj = new mvPlayList( this );
					mvEmbed.swapEmbedVideoElement(this, plObj);
					var added_height = plObj.pl_layout.title_bar_height + plObj.pl_layout.control_height;
					//move into a blocking display container with height + controls + title height:
					$j('#'+plObj.id).wrap('<div style="display:block;' +
							'height:' + (plObj.height + added_height) + 'px;' +
							'width:' + parseInt(this.width) + 'px;" ' +
							'id="k-player_' + this.id + '" class="k-player ui-widget">' +
						'</div>');
				});
			});
		}
		this.checkClipsReady();
	},
	/*
	* swapEmbedVideoElement
	* takes a video element as input and swaps it out with
	* an embed video interface based on the video_elements attributes
	*/
	swapEmbedVideoElement:function(video_element, videoInterface){
		js_log('do swap ' + videoInterface.id + ' for ' + video_element);
		embed_video = document.createElement('div');
		//make sure our div has a hight/width set:

		/*$j(embed_video).css({
			'width':videoInterface.width,
			'height':videoInterface.height
		}).html( mv_get_loading_img() );*/
		
		//inherit the video interface
		for(var method in videoInterface){ //for in loop oky in Element context
			if(method!='readyState'){ //readyState crashes IE
				if(method=='style'){
						embed_video.setAttribute('style', videoInterface[method]);
				}else if(method=='class'){
					if( $j.browser.msie )
						embed_video.setAttribute("className", videoInterface['class']);
					else
						embed_video.setAttribute("class", videoInterface['class']);
				}else{
					//normal inherit:
					embed_video[method]=videoInterface[method];
				}
			}
			//string -> boolean:
			if( embed_video[method] == "false") embed_video[method] = false;
			if( embed_video[method] == "true") embed_video[method] = true;
		}
		///js_log('did vI style');
		//now swap out the video element for the embed_video obj:
		$j(video_element).after(embed_video).remove();
		//js_log('did swap');
		$j('#'+embed_video.id).get(0).on_dom_swap();

		// now that "embed_video" is stable, do more initialization (if we are ready)
		if($j('#'+embed_video.id).get(0).loading_external_data == false &&
			   $j('#'+embed_video.id).get(0).init_with_sources_loadedDone == false){
			//load and set ready state since source are available:
			$j('#'+embed_video.id).get(0).init_with_sources_loaded();
		}

		js_log('done with child: ' + embed_video.id + ' len:' + global_player_list.length);
		return true;
	},
	//this should not be needed.
	checkClipsReady : function(){
		//js_log('checkClipsReady');
		var is_ready=true;
		  for(var i=0; i < global_player_list.length; i++){
			  if( $j('#'+global_player_list[i]).length !=0){
				  var cur_vid =  $j('#'+global_player_list[i]).get(0);
				is_ready = ( cur_vid.ready_to_play ) ? is_ready : false;
				if( !is_ready && cur_vid.load_error ){
					is_ready=true;
					$j(cur_vid).html( cur_vid.load_error );
				}
			}
		}
		if( is_ready ){
			mvEmbed.allClipsReady = true;
			// run queued functions
			//js_log('run queded functions:' + mvEmbed.flist[0]);
			mvEmbed.runFlist();
		}else{
			 setTimeout( 'mvEmbed.checkClipsReady()', 25 );
		 }
	},
	runFlist:function(){
		while (this.flist.length){
			this.flist.shift()();
		}
	}
}

/**
  * mediaSource class represents a source for a media element.
  * @param {String} type MIME type of the source.
  * @param {String} uri URI of the source.
  * @constructor
  */
function mediaSource(element)
{
	this.init(element);
}


mediaSource.prototype =
{
	/** MIME type of the source. */
	mime_type:null,
	/** URI of the source. */
	uri:null,
	/** Title of the source. */
	title:null,
	/** True if the source has been marked as the default. */
	marked_default:false,
	/** True if the source supports url specification of offset and duration */
	URLTimeEncoding:false,
	/** Start offset of the requested segment */
	start_offset:null,
	/** Duration of the requested segment (0 if not known) */
	duration:0,
	is_playable:null,
	upddate_interval:null,

	id:null,
	start_ntp:null,
	end_ntp:null,

	init : function(element)
	{
		//js_log('adding mediaSource: ' + element);
		this.src = $j(element).attr('src');
		this.marked_default = false;
		if ( element.tagName.toLowerCase() == 'video')
			this.marked_default = true;

		//set default URLTimeEncoding if we have a time  url:
		//not ideal way to discover if content is on an oggz_chop server.
		//should check some other way.
		var pUrl = parseUri ( this.src );
		if(typeof pUrl['queryKey']['t'] != 'undefined'){
			this['URLTimeEncoding']=true;
		}
		for(var i=0; i < mv_default_source_attr.length; i++){ //array loop:
			var attr = mv_default_source_attr[ i ];
			if( $j(element).attr( attr ) ) {
				this[ attr ] =  $j(element).attr( attr );
			}
		}
		//update duration from hit if present:
		if(this.durationHint)
			this.duration = this.durationHint;


		if ( $j(element).attr('type'))
			this.mime_type = $j(element).attr('type');
		else if ($j(element).attr('content-type'))
			this.mime_type = $j(element).attr('content-type');
		else
			this.mime_type = this.detectType(this.src);

		//set the title if unset:
		if( !this.title )
			this.title = this.mime_type;

		this.parseURLDuration();
	},
	updateSource:function(element){
		//for now just update the title:
		if ($j(element).attr("title"))
			this.title = $j(element).attr("title");
	},
	/** updates the src time and start & end
	 *  @param {String} start_time in NTP format
	 *  @param {String} end_time in NTP format
	 */
	updateSrcTime:function (start_ntp, end_ntp){
		//js_log("f:updateSrcTime: "+ start_ntp+'/'+ end_ntp + ' from org: ' + this.start_ntp+ '/'+this.end_ntp);
		//js_log("pre uri:" + this.src);
		//if we have time we can use:
		if( this.URLTimeEncoding ){
			//make sure its a valid start time / end time (else set default)
			if( !npt2seconds(start_ntp) )
				start_ntp = this.start_ntp;

			if( !npt2seconds(end_ntp) )
				end_ntp = this.end_ntp;

			this.src = getURLParamReplace(this.src, { 't': start_ntp +'/'+ end_ntp } );

			//update the duration
			this.parseURLDuration();
		}
	},
	setDuration:function (duration)
	{
		this.duration = duration;
		if(!this.end_ntp){
			this.end_ntp = seconds2npt( this.start_offset + duration);
		}
	},
	/** MIME type accessor function.
		@return the MIME type of the source.
		@type String
	*/
	getMIMEType : function()
	{
		return this.mime_type;
	},
	/** URI accessor function.
	 *	 @param int seek_time_sec (used to adjust the URI for url based seeks)
		@return the URI of the source.
		@type String
	*/
	getURI : function( seek_time_sec )
	{
	   if( !seek_time_sec || !this.URLTimeEncoding ){
			   return this.src;
	   }
	   if(!this.end_ntp){
		  var endvar = '';
	   }else{
		  var endvar = '/'+ this.end_ntp;
	   }
	   return getURLParamReplace(this.src,  { 't': seconds2npt( seek_time_sec )+endvar } );	;
	},
	/** Title accessor function.
		@return the title of the source.
		@type String
	*/
	getTitle : function()
	{
		return this.title;
	},
	/** Index accessor function.
		@return the source's index within the enclosing mediaElement container.
		@type Integer
	*/
	getIndex : function()
	{
		return this.index;
	},
	/*
	 * function getDuration in milliseconds
	 * special case derive duration from request url
	 * supports media_url?t=ntp_start/ntp_end url request format
	 */
	parseURLDuration : function(){
		//check if we have a URLTimeEncoding:
		if( this.URLTimeEncoding ){
			var annoURL = parseUri( this.src );
			if( annoURL.queryKey['t'] ){
				var times = annoURL.queryKey['t'].split('/');
				this.start_ntp = times[0];
				this.end_ntp = times[1];
				this.start_offset = npt2seconds( this.start_ntp );
				this.duration = npt2seconds( this.end_ntp ) - this.start_offset;
			}else{
				//look for this info as attributes
				if(this.startOffset){
					this.start_offset = this.startOffset;
					this.start_ntp = seconds2npt( this.startOffset);
				}
				if(this.duration){
					this.end_ntp = seconds2npt( parseInt(this.duration) + parseInt(this.start_offset) );
				}
			}
		}
		//else nothing to parse just keep whatever info we already have

		//js_log('f:parseURLDuration() for:' + this.src  + ' d:' + this.duration);
	},
	/** Attempts to detect the type of a media file based on the URI.
		@param {String} uri URI of the media file.
		@returns The guessed MIME type of the file.
		@type String
	*/
	detectType:function(uri)
	{
		//@@todo if media is on the same server as the javascript or we have mv_proxy configured
		//we can issue a HEAD request and read the mime type of the media...
		// (this will detect media mime type independently of the url name)
		//http://www.jibbering.com/2002/4/httprequest.html (this should be done by extending jquery's ajax objects)
		var end_inx =  (uri.indexOf('?')!=-1)? uri.indexOf('?') : uri.length;
		var no_param_uri = uri.substr(0, end_inx);
		switch( no_param_uri.substr(no_param_uri.lastIndexOf('.'),4).toLowerCase() ){
			case '.flv':return 'video/x-flv';break;
			case '.ogg': case '.ogv': return 'video/ogg';break;
			case '.oga': return 'audio/ogg'; break;
			case '.anx':return 'video/ogg';break;
		}
	}
};

/** A media element corresponding to a <video> element.
	It is implemented as a collection of mediaSource objects.  The media sources
	will be initialized from the <video> element, its child <source> elements,
	and/or the ROE file referenced by the <video> element.
	@param {element} video_element <video> element used for initialization.
	@constructor
*/
function mediaElement(video_element)
{
	this.init(video_element);
};

mediaElement.prototype =
{
	/** The array of mediaSource elements. */
	sources:null,
	addedROEData:false,
	/** Selected mediaSource element. */
	selected_source:null,
	thumbnail:null,
	linkback:null,

	/** @private */
	init:function( video_element )
	{
		var _this = this;
		js_log('Initializing mediaElement...' );
		this.sources = new Array();
		this.thumbnail = mv_default_thumb_url;
		// Process the source element:
		if($j(video_element).attr("src"))
			this.tryAddSource(video_element);

		if($j(video_element).attr('thumbnail'))
			this.thumbnail = $j(video_element).attr('thumbnail');

		if($j(video_element).attr('poster'))
			this.thumbnail = $j(video_element).attr('poster');

		// Process all inner <source> elements
		//js_log("inner source count: " + video_element.getElementsByTagName('source').length );

		$j(video_element).find('source,text').each(function(inx, inner_source){
			_this.tryAddSource( inner_source );
		});
	},
	/** Updates the time request for all sources that have a standard time request argument (ie &t=start_time/end_time)
	 */
	updateSourceTimes:function(start_ntp, end_ntp){
		var _this = this;
		$j.each(this.sources, function(inx, mediaSource){
			mediaSource.updateSrcTime(start_ntp, end_ntp);
		});
	},
	/*timed Text check*/
	timedTextSources:function(){
		for(var i=0; i < this.sources.length; i++){
			if(	this.sources[i].mime_type == 'text/cmml' ||
				this.sources[i].mime_type == 'text/x-srt')
					return true;
		};
		return false;
	},
	/** Returns the array of mediaSources of this element.
		\returns {Array} Array of mediaSource elements.
	*/
	getSources:function( mime_filter )
	{
		if(!mime_filter)
			return this.sources;
		//apply mime filter:
		   var source_set = new Array();
		for(var i=0; i < this.sources.length ; i++){
			if( this.sources[i].mime_type.indexOf( mime_filter ) != -1 )
				source_set.push( this.sources[i] );
		}
		return source_set;
	},
	getSourceById:function( source_id ){
		for(var i=0; i < this.sources.length ; i++){
			if( this.sources[i].id ==  source_id)
				return this.sources[i];
		}
		return null;
	},
	/** Selects a particular source for playback.
	*/
	selectSource:function(index)
	{
		js_log('f:selectSource:'+index);
		var playable_sources = this.getPlayableSources();
		for(var i=0; i < playable_sources.length; i++){
			if( i==index ){
				this.selected_source = playable_sources[i];
				//update the user selected format:
				embedTypes.players.userSelectFormat( playable_sources[i].mime_type );
				break;
			}
		}
	},
	/** selects the default source via cookie preference, default marked, or by id order
	 * */
	autoSelectSource:function(){
		js_log('f:autoSelectSource:');
		//@@todo read user preference for source
		// Select the default source
		var playable_sources = this.getPlayableSources();
		var flash_flag=ogg_flag=false;
		//debugger;
		for(var source=0; source < playable_sources.length; source++){
			var mime_type =playable_sources[source].mime_type;
			if( playable_sources[source].marked_default ){
				js_log('set via marked default: ' + playable_sources[source].marked_default);
				this.selected_source = playable_sources[source];
				return true;
			}
			//set via user-preference
			if(embedTypes.players.preference['format_prefrence'] == mime_type){
				 js_log('set via preference: '+playable_sources[source].mime_type);
				 this.selected_source = playable_sources[source];
				 return true;
			}
		}
		//set Ogg via player support
		for(var source=0; source < playable_sources.length; source++){
			js_log('f:autoSelectSource:' + playable_sources[source].mime_type);
			var mime_type =playable_sources[source].mime_type;
			   //set source via player
			if(mime_type=='video/ogg' || mime_type=='ogg/video' || mime_type=='video/annodex' || mime_type=='application/ogg'){
				for(var i=0; i < embedTypes.players.players.length; i++){ //for in loop on object oky
					var player = embedTypes.players.players[i];
					if(player.library=='vlc' || player.library=='native'){
						js_log('set via ogg via order');
						this.selected_source = playable_sources[source];
						return true;
					}
				}
			}
		}
		//set basic flash
		for(var source=0; source < playable_sources.length; source++){
			var mime_type =playable_sources[source].mime_type;
			if( mime_type=='video/x-flv' ){
				js_log('set via by player preference normal flash')
				this.selected_source = playable_sources[source];
				return true;
			}
		}
		//set h264 flash
		for(var source=0; source < playable_sources.length; source++){
			var mime_type =playable_sources[source].mime_type;
			if( mime_type=='video/h264' ){
				js_log('set via playable_sources preference h264 flash')
				this.selected_source = playable_sources[source];
				return true;
			}
		}
		//select first source
		if (!this.selected_source)
		{
			js_log('set via first source:' + playable_sources[0]);
			this.selected_source = playable_sources[0];
			return true;
		}
	},
	/** Returns the thumbnail URL for the media element.
		\returns {String} thumbnail URL
	*/
	getThumbnailURL:function()
	{
		return this.thumbnail;
	},
	/** Checks whether there is a stream of a specified MIME type.
		@param {String} mime_type MIME type to check.
		@type {BooleanPrimitive}.
	*/
	hasStreamOfMIMEType:function(mime_type)
	{
		for(source in this.sources)
		{
			if(this.sources[source].getMIMEType() == mime_type)
				return true;
		}
		return false;
	},
	isPlayableType:function(mime_type)
	{
		if( embedTypes.players.defaultPlayer( mime_type ) ){
			return true;
		}else{
			return false;
		}
		//if(this.selected_player){
		//return mime_type=='video/ogg' || mime_type=='ogg/video' || mime_type=='video/annodex' || mime_type=='video/x-flv';
	},
	/** Adds a single mediaSource using the provided element if
		the element has a 'src' attribute.
		@param element {element} <video>, <source> or <mediaSource> element.
	*/
	tryAddSource:function(element)
	{
		js_log('f:tryAddSource:'+ $j(element).attr("src"));
		if (! $j(element).attr("src")){
			//js_log("element has no src");
			return false;
		}
		var new_src = $j(element).attr('src');
		//make sure an existing element with the same src does not already exist:
		for( var i=0; i < this.sources.length; i++ ){
			if(this.sources[i].src == new_src){
				//js_log('checking existing: '+this.sources[i].getURI() + ' != '+ new_src);
				//can't add it all but try to update any additional attr:
				this.sources[i].updateSource(element);
				return false;
			}
		}
		var source = new mediaSource( element );
		this.sources.push(source);
		//alert('pushed source to stack'+ source + 'sl:'+this.sources.length);
	},
	getPlayableSources: function(){
		 var playable_sources= new Array();
		 for(var i=0; i < this.sources.length; i++){
			 if( this.isPlayableType( this.sources[i].mime_type ) ){
				 playable_sources.push( this.sources[i] );
			 }else{
				 js_log("type "+ this.sources[i].mime_type + 'is not playable');
			 }
		 };
		 return playable_sources;
	},
	/* Imports media sources from ROE data.
	 *   @param roe_data ROE data.
	*/
	addROE:function(roe_data){
		js_log('f:addROE');
		this.addedROEData=true;
		var _this = this;
		if( typeof roe_data == 'string' )
		{
			var parser=new DOMParser();
			js_log('ROE data:' + roe_data);
			roe_data=parser.parseFromString(roe_data,"text/xml");
		}
		if( roe_data ){
			$j.each(roe_data.getElementsByTagName('mediaSource'), function(inx, source){
				_this.tryAddSource(source);
			});
			//set the thumbnail:
			$j.each(roe_data.getElementsByTagName('img'), function(inx, n){
				if($j(n).attr("id")=="stream_thumb"){
					js_log('roe:set thumb to '+$j(n).attr("src"));
					_this['thumbnail'] =$j(n).attr("src");
				}
			});
			//set the linkback:
			$j.each(roe_data.getElementsByTagName('link'), function(inx, n){
				if($j(n).attr('id')=='html_linkback'){
					js_log('roe:set linkback to '+$j(n).attr("href"));
					_this['linkback'] = $j(n).attr('href');
				}
			});
		}else{
			js_log('ROE data empty.');
		}
	}
};


/** base embedVideo object
	@param element <video> tag used for initialization.
	@constructor
*/
var embedVideo = function(element) {
	return this.init(element);
};

embedVideo.prototype = {
	/** The mediaElement object containing all mediaSource objects */
	media_element:null,
	preview_mode:false,
	ready_to_play:false, //should use html5 ready state
	load_error:false, //used to set error in case of error
	loading_external_data:false,
	thumbnail_updating:false,
	thumbnail_disp:true,
	init_with_sources_loadedDone:false,
	inDOM:false,
	//for onClip done stuff:
	anno_data_cache:null,
	seek_time_sec:0,
	base_seeker_slider_offset:null,
	onClipDone_disp:false,
	supports:{},
	//for seek thumb updates:
	cur_thumb_seek_time:0,
	thumb_seek_interval:null,

	seeking:false,
	//set the buffered percent:
	bufferedPercent:0,
	//utility functions for property values:
	hx : function ( s ) {
		if ( typeof s != 'String' ) {
			s = s.toString();
		}
		return s.replace( /&/g, '&amp;' )
			. replace( /</g, '&lt;' )
			. replace( />/g, '&gt;' );
	},
	hq : function ( s ) {
		return '"' + this.hx( s ) + '"';
	},
	playerPixelWidth : function()
	{
		var player = $j('#dc_'+this.id).get(0);
		if(typeof player!='undefined' && player['offsetWidth'])
			return player.offsetWidth;
		else
			return parseInt(this.width);
	},
	playerPixelHeight : function()
	{
		var player = $j('#dc_'+this.id).get(0);
		if(typeof player!='undefined' && player['offsetHeight'])
			return player.offsetHeight;
		else
			return parseInt(this.height);
	},
	init: function(element){
		//this.element_pointer = element;

		//inherit all the default video_attributes
		for(var attr in default_video_attributes){ //for in loop oky on user object
			if(element.getAttribute(attr)){
				this[attr]=element.getAttribute(attr);
				//js_log('attr:' + attr + ' val: ' + element.getAttribute(attr) +'(set by elm)');
			}else{
				this[attr]=default_video_attributes[attr];
				//js_log('attr:' + attr + ' val: ' + video_attributes[attr] +" "+ 'elm_val:' + element.getAttribute(attr) + "\n (set by attr)");
			}
		}
		//make sure startOffset is cast as an int
		if( this.startOffset && this.startOffset.split(':').length >= 2)
			this.startOffset = npt2seconds(this.startOffset);
		//make sure offset is in float:
		this.startOffset = parseFloat(this.startOffset);

		if( this.duration && this.duration.split(':').length >= 2)
			this.duration = npt2seconds( this.duration );
		   //make sure duration is in float:
		this.duration = parseFloat(this.duration);
		js_log("duration is: " +  this.duration);
		//if style is set override width and height
		var dwh = mwConfig['video_size'].split('x');
		this.width = element.style.width ? element.style.width : dwh[0];
		this.height = element.style.height ? element.style.height : dwh[1];
		//set the plugin id
		this.pid = 'pid_' + this.id;

		//grab any innerHTML and set it to missing_plugin_html
		//@@todo we should strip source tags instead of checking and skipping
		if(element.innerHTML!='' && element.getElementsByTagName('source').length==0){
			js_log('innerHTML: ' + element.innerHTML);
			this.user_missing_plugin_html=element.innerHTML;
		}
		// load all of the specified sources
		this.media_element = new mediaElement(element);
	},
	on_dom_swap: function(){
		js_log('f:on_dom_swap');
		// Process the provided ROE file... if we don't yet have sources
		if(this.roe && this.media_element.sources.length==0 ){
			js_log('loading external data');
			this.loading_external_data=true;
			var _this = this;
			do_request(this.roe, function(data)
			{
				//continue
				_this.media_element.addROE( data );
				js_log('added_roe::' + _this.media_element.sources.length);

				js_log('set loading_external_data=false');
				_this.loading_external_data=false;

				_this.init_with_sources_loaded();
			});
		}
	},
	init_with_sources_loaded : function()
	{
		js_log('f:init_with_sources_loaded');
		//set flag that we have run this function:
		this.init_with_sources_loadedDone=true;
		//autoseletct the source
		this.media_element.autoSelectSource();
		//auto select player based on prefrence or default order
		if( !this.media_element.selected_source )
		{
			//check for parent clip:
			if( typeof this.pc != 'undefined' ){
				js_log('no sources, type:' +this.type + ' check for html');
				//debugger;
				//do load player if just displaying innerHTML:
				if( this.pc.type == 'text/html' ){
					this.selected_player = embedTypes.players.defaultPlayer( 'text/html' );
					js_log('set selected player:'+ this.selected_player.mime_type);
				}
			}
		}else{
			this.selected_player = embedTypes.players.defaultPlayer( this.media_element.selected_source.mime_type );
		}
		if( this.selected_player ){
			js_log('selected ' + this.selected_player.getName());
			js_log("PLAYBACK TYPE: "+this.selected_player.library);
			this.thumbnail_disp = true;
			this.inheritEmbedObj();
		}else{
			//no source's playable
			var missing_type ='';
			var or ='';
			for( var i=0; i < this.media_element.sources.length; i++){
				missing_type+= or + this.media_element.sources[i].mime_type;
				or=' or ';
			}
			if( this.pc )
				var missing_type = this.pc.type;
			   js_log('no player found for given source type ' + missing_type);
			   this.load_error= this.getPluginMissingHTML(missing_type);
		}
	},
	inheritEmbedObj:function(){
		js_log("inheritEmbedObj:duration is: " +  this.duration);
		//@@note: tricky cuz direct overwrite is not so ideal.. since the extended object is already tied to the dom
		//clear out any non-base embedObj stuff:
		if(this.instanceOf){
			eval('tmpObj = '+this.instanceOf);
			for(var i in tmpObj){ //for in loop oky for object
				if(this['parent_'+i]){
					this[i]=this['parent_'+i];
				}else{
					this[i]=null;
				}
			}
		}
		//set up the new embedObj
		js_log('f: inheritEmbedObj: embedding with ' + this.selected_player.library);
		var _this = this;
		this.selected_player.load( function()
		{
			 js_log("selected_player::load:duration is: " +  _this.duration);
			//js_log('inheriting '+_this.selected_player.library +'Embed to ' + _this.id + ' ' + $j('#'+_this.id).length);
			//var _this = $j('#'+_this.id).get(0);
			//js_log( 'type of ' + _this.selected_player.library +'Embed + ' +
			//		eval('typeof '+_this.selected_player.library +'Embed'));
			eval('embedObj = ' +_this.selected_player.library +'Embed;');
			for(var method in embedObj){ //for in loop oky for object
				//parent method preservation for local overwritten methods
				if(_this[method])
					_this['parent_' + method] = _this[method];
				_this[method]=embedObj[method];
			}
			js_log('TYPEOF_ppause: ' + typeof _this['parent_pause']);

			if(_this.inheritEmbedOverride){
				_this.inheritEmbedOverride();
			}
			//update controls if possible
			if(!_this.loading_external_data)
				_this.refreshControlsHTML();

			//js_log("READY TO PLAY:"+_this.id);
			_this.ready_to_play=true;
			_this.getDuration();
			_this.getHTML();
		});
	},
	selectPlayer:function(player)
	{
		var _this = this;
		if(this.selected_player.id != player.id){
			this.selected_player = player;
			this.inheritEmbedObj();
		}
	},
	doNativeWarningCheck:function(){
		if( $j.cookie('dismissNativeWarn') && $j.cookie('dismissNativeWarn')===true){
			return false;
		}else{
			//see if we have native support for ogg:
			var supporting_players = embedTypes.players.getMIMETypePlayers( 'video/ogg' );
			for(var i=0; i < supporting_players.length; i++){
				if(supporting_players[i].id == 'videoElement'){
					return false;
				}
			}
			//see if we are using mv_embed without a ogg source in which case no point in promoting firefox :P
			if(this.media_element && this.media_element.sources){
				var foundOgg = false;
				var playable_sources = this.media_element.getPlayableSources();
				for(var sInx=0; sInx < playable_sources.length; sInx++){
					var mime_type = playable_sources[sInx].mime_type;
					if( mime_type=='video/ogg' ){
						//they  have flash / h.264 fallback no need to push firefox :(
						foundOgg = true;
					}
				}
				//no ogg no point in download firefox
				if(!foundOgg)
					return false;

			}
		}
		return true;
	},
	getTimeReq:function(){
		var default_time_req = '0:00:00/' + seconds2npt(this.getDuration());
		if(!this.media_element)
			return default_time_req;
		if(!this.media_element.selected_source)
			return default_time_req;
		if(!this.media_element.selected_source.end_ntp)
			return default_time_req;
		return this.media_element.selected_source.start_ntp+'/'+this.media_element.selected_source.end_ntp;
	},
	getDuration:function(){
		//update some local pointers for the selected source:
		if(this.media_element && this.media_element.selected_source && this.media_element.selected_source.duration){
			this.duration = this.media_element.selected_source.duration;
			this.start_offset = this.media_element.selected_source.start_offset;
			this.start_ntp = this.media_element.selected_source.start_ntp;
			this.end_ntp = this.media_element.selected_source.end_ntp;
		}
		//update start end_ntp if duration !=0 (set from plugin)
		if(!this.start_ntp)
			this.start_ntp = '0:0:0';
		if(!this.end_ntp && this.duration)
			this.end_ntp = seconds2npt( this.duration );
		//return the duration
		return this.duration;
	},
	timedTextSources:function(){
		if(!this.media_element.timedTextSources)
			return false;
		return this.media_element.timedTextSources()
	},
	/*
	 * wrapEmebedContainer
	 * wraps the embed code into a container to better support playlist function
	 *  (where embed element is swapped for next clip
	 *  (where plugin method does not support playlsits)
	 */
	wrapEmebedContainer:function(embed_code){
		//check if parent clip is set( ie we are in a playlist so name the embed container by playlistID)
		var id = (this.pc!=null)?this.pc.pp.id:this.id;
		return '<div id="mv_ebct_'+id+'" style="width:'+this.width+'px;height:'+this.height+'px;">' +
					embed_code +
				'</div>';
	},
	getEmbedHTML : function(){
		//return this.wrapEmebedContainer( this.getEmbedObj() );
		return 'function getEmbedHTML should be overitten by embedLib ';
	},
	//do seek function (should be overwritten by implementing embedLibs)
	// first check if seek can be done on locally downloaded content.
	doSeek : function( perc ){
		if( this.supportsURLTimeEncoding() ){
			//make sure this.seek_time_sec is up-to-date:
			this.seek_time_sec = npt2seconds( this.start_ntp ) + parseFloat( perc * this.getDuration() );
			js_log('updated seek_time_sec: ' + seconds2npt ( this.seek_time_sec) );
			this.stop();
			this.didSeekJump=true;
			//update the slider
			this.setSliderValue( perc );
		}
		//do play in 100ms (give things time to clear)
		setTimeout('$j(\'#' + this.id + '\').get(0).play()',100);
	},
	/*
	 * seeks to the requested time and issues a callback when ready
	 * (should be overwitten by client that supports frame serving)
	 */
	setCurrentTime:function( time, callback){
		js_log('error: base embed setCurrentTime can not frame serve (overide via plugin)');
	},
	addPresTimeOffset:function(){
	   //add in the offset:
	   if(this.seek_time_sec && this.seek_time_sec!=0){
			this.currentTime+=this.seek_time_sec;
	   }else if(this.start_offset && this.start_offset!=0){
		   this.currentTime = parseFloat(this.currentTime) + parseFloat(this.start_offset);
	   }
	},
	doEmbedHTML:function()
	{
		js_log('f:doEmbedHTML');
		js_log('thum disp:'+this.thumbnail_disp);
		var _this = this;
		this.closeDisplayedHTML();

//		if(!this.selected_player){
//			return this.getPluginMissingHTML();
		//Set "loading" here
		$j('#dc_'+_this.id).html(''+
			'<div style="color:black;width:'+this.width+'px;height:'+this.height+'px;">' +
				gM('loading_plugin') +
			'</div>'
		);
		// schedule embedding
		this.selected_player.load(function()
		{
			js_log('performing embed for ' + _this.id);
			var embed_code = _this.getEmbedHTML();
			//js_log('shopuld embed:' + embed_code);
			$j('#dc_'+_this.id).html(embed_code);
		});
	},
	onClipDone:function(){
		js_log('base:onClipDone');
		//stop the clip (load the thumbnail etc)
		this.stop();
		this.seek_time_sec = 0;
		this.setSliderValue(0);
		var _this = this;

		//if the clip resolution is < 320 don't do fancy onClipDone stuff
		if(this.width < 300){
			return ;
		}
		this.onClipDone_disp = true;
		this.thumbnail_disp = true;
		//make sure we are not in preview mode( no end clip actions in preview mode)
		if( this.preview_mode )
			return ;

		$j('#img_thumb_'+this.id).css('zindex',1);
		$j('#big_play_link_'+this.id).hide();
		//add the liks_info_div black back
		$j('#dc_'+this.id).append('<div id="liks_info_'+this.id+'" ' +
					'style="width:' +parseInt(parseInt(this.width)/2)+'px;'+
					'height:'+ parseInt(parseInt(this.height)) +'px;'+
					'position:absolute;top:10px;overflow:auto'+
					'width: '+parseInt( ((parseInt(this.width)/2)-15) ) + 'px;'+
					'left:'+ parseInt( ((parseInt(this.width)/2)+15) ) +'px;">'+
				'</div>' +
				'<div id="black_back_'+this.id+'" ' +
					'style="z-index:-2;position:absolute;background:#000;' +
					'top:0px;left:0px;width:'+parseInt(this.width)+'px;' +
					'height:'+parseInt(this.height)+'px;">' +
				'</div>'
		   );

		//start animation (make thumb small in upper left add in div for "loading"
		$j('#img_thumb_'+this.id).animate({
				width:parseInt(parseInt(_this.width)/2),
				height:parseInt(parseInt(_this.height)/2),
				top:20,
				left:10
			},
			1000,
			function(){
				//animation done.. add "loading" to div if empty
				if($j('#liks_info_'+_this.id).html()==''){
					$j('#liks_info_'+_this.id).html(gM('loading_txt'));
				}
			}
		)
		//now load roe if run the showNextPrevLinks
		if(this.roe && this.media_element.addedROEData==false){
			do_request(this.roe, function(data)
			{
				_this.media_element.addROE(data);
				_this.getNextPrevLinks();
			});
		}else{
			this.getNextPrevLinks();
		}
	},
	//@@todo we should merge getNextPrevLinks with textInterface .. there is repeated code between them.
	getNextPrevLinks:function(){
		js_log('f:getNextPrevLinks');
		var anno_track_url = null;
		var _this = this;
		//check for annoative track
		$j.each(this.media_element.sources, function(inx, n){
			if(n.mime_type=='text/cmml'){
				if( n.id == 'Anno_en'){
					anno_track_url = n.src;
				}
			}
		});
		if( anno_track_url ){
			js_log('found annotative track:'+ anno_track_url);
			//zero out seconds (should improve cache hit rate and generally expands metadata search)
			//@@todo this could be repalced with a regExp
			var annoURL = parseUri(anno_track_url);
			var times = annoURL.queryKey['t'].split('/');
			var stime_parts = times[0].split(':');
			var etime_parts = times[1].split(':');
			//zero out the hour:
			var new_start = stime_parts[0]+':'+'0:0';
			//zero out the end sec
			var new_end   = (etime_parts[0]== stime_parts[0])? (etime_parts[0]+1)+':0:0' :etime_parts[0]+':0:0';

			var etime_parts = times[1].split(':');

			var new_anno_track_url = annoURL.protocol +'://'+ annoURL.host + annoURL.path +'?';
			$j.each(annoURL.queryKey, function(i, val){
				new_anno_track_url +=(i=='t')?'t='+new_start+'/'+new_end +'&' :
										 i+'='+ val+'&';
			});
			var request_key = new_start+'/'+new_end;
			//check the anno_data cache:
			//@@todo search cache see if current is in range.
			if(this.anno_data_cache){
				js_log('anno data found in cache: '+request_key);
				this.showNextPrevLinks();
			}else{
				do_request(new_anno_track_url, function(cmml_data){
					js_log('raw response: '+ cmml_data);
					if(typeof cmml_data == 'string')
					{
						var parser=new DOMParser();
						js_log('Parse CMML data:' + cmml_data);
						cmml_data=parser.parseFromString(cmml_data,"text/xml");
					}
					//init anno_data_cache
					if(!_this.anno_data_cache)
						_this.anno_data_cache={};
					//grab all metadata and put it into the anno_data_cache:
					$j.each(cmml_data.getElementsByTagName('clip'), function(inx, clip){
						_this.anno_data_cache[ $j(clip).attr("id") ]={
								'start_time_sec':npt2seconds($j(clip).attr("start").replace('npt:','')),
								'end_time_sec':npt2seconds($j(clip).attr("end").replace('npt:','')),
								'time_req':$j(clip).attr("start").replace('npt:','')+'/'+$j(clip).attr("end").replace('npt:','')
							};
						//grab all its meta
						_this.anno_data_cache[ $j(clip).attr("id") ]['meta']={};
						$j.each(clip.getElementsByTagName('meta'),function(imx, meta){
							//js_log('adding meta: '+ $j(meta).attr("name")+ ' = '+ $j(meta).attr("content"));
							_this.anno_data_cache[$j(clip).attr("id")]['meta'][$j(meta).attr("name")]=$j(meta).attr("content");
						});
					});
					_this.showNextPrevLinks();
				});
			}
		}else{
			js_log('no annotative track found');
			$j('#liks_info_'+this.id).html('no metadata found for related links');
		}
		//query current request time +|- 60s to get prev next speech links.
	},
	showNextPrevLinks:function(){
		//js_log('f:showNextPrevLinks');
		//int requested links:
		var link = {
			'prev':'',
			'current':'',
			'next':''
		}
		var curTime = this.getTimeReq().split('/');

		var s_sec = npt2seconds(curTime[0]);
		var e_sec = npt2seconds(curTime[1]);
		js_log('showNextPrevLinks: req time: '+ s_sec + ' to ' + e_sec);
		//now we have all the data in anno_data_cache
		var current_done=false;
		for(var clip_id in this.anno_data_cache){  //for in loop oky for object
			 var clip =  this.anno_data_cache[clip_id];
			 //js_log('on clip:'+ clip_id);
			 //set prev_link (if cur_link is still empty)
			if( s_sec > clip.end_time_sec){
				link.prev = clip_id;
				js_log('showNextPrevLinks: ' + s_sec + ' < ' + clip.end_time_sec + ' set prev');
			}

			if(e_sec==clip.end_time_sec && s_sec== clip.start_time_sec)
				current_done = true;
			//current clip is not done:
			if(  e_sec < clip.end_time_sec  && link.current=='' && !current_done){
				link.current = clip_id;
				js_log('showNextPrevLinks: ' + e_sec + ' < ' + clip.end_time_sec + ' set current');
			}

			//set end clip (first clip where start time is > end_time of req
			if( e_sec <  clip.start_time_sec && link.next==''){
				link.next = clip_id;
				js_log('showNextPrevLinks: '+  e_sec + ' < '+ clip.start_time_sec + ' && ' + link.next );
			}
		}
		var html='';
		if(link.prev=='' && link.current=='' && link.next==''){
			html='<p><a href="'+this.media_element.linkbackgetMsg+'">clip page</a>';
		}else{
			for(var link_type in link){
				var link_id = link[link_type];
				if(link_id!=''){
					var clip = this.anno_data_cache[link_id];
					var title_msg='';
					for(var j in clip['meta']){
						title_msg+=j.replace(/_/g,' ') +': ' +clip['meta'][j].replace(/_/g,' ') +" <br>";
					}
					var time_req =	 clip.time_req;
					if(link_type=='current') //if current start from end of current clip play to end of current meta:
						time_req = curTime[1]+ '/' + seconds2npt( clip.end_time_sec );

					//do special linkbacks for metavid content:
					var regTimeCheck = new RegExp(/[0-9]+:[0-9]+:[0-9]+\/[0-9]+:[0-9]+:[0-9]+/);
					html+='<p><a  ';
					if( regTimeCheck.test( this.media_element.linkback ) ){
						html+=' href="'+ this.media_element.linkback.replace(regTimeCheck,time_req) +'" ';
					}else{
						html+=' href="#" onClick="$j(\'#'+this.id+'\').get(0).playByTimeReq(\''+
								time_req + '\'); return false; "';
					}
					html+=' title="' + title_msg + '">' +
						 gM(link_type+'_clip_msg') +
					'</a><br><span style="font-size:small">'+ title_msg +'<span></p>';
				}
			}
		}
		//js_og("should set html:"+ html);
		$j('#liks_info_'+this.id).html(html);
	},
	playByTimeReq: function(time_req){
		js_log('f:playByTimeReq: '+time_req );
		this.stop();
		this.updateVideoTimeReq(time_req);
		this.play();
	},
	doThumbnailHTML:function()
	{
		var _this = this;
		js_log('f:doThumbnailHTML'+ this.thumbnail_disp);
		this.closeDisplayedHTML();
		$j( '#dc_' + this.id ).html( this.getThumbnailHTML() );
		this.paused = true;
		this.thumbnail_disp = true;
	},
	refreshControlsHTML:function(){
		js_log('refreshing controls HTML');
		if($j('#mv_embedded_controls_'+this.id).length==0)
		{
			js_log('#mv_embedded_controls_'+this.id + ' not present, returning');
			return;
		}else{
			$j('#mv_embedded_controls_'+this.id).html( this.getControlsHTML() );
			ctrlBuilder.addControlHooks(this);
		}
	},
	getControlsHTML:function()
	{
		return ctrlBuilder.getControls( this );
	},
	getHTML : function (){
		js_log('f:getHTML : ' + this.id );
		var _this = this;
		var html_code = '';

		//get the thumbnail:
		html_code = this.getThumbnailHTML();

		if(this.controls){
			js_log("f:getHTML:AddControls");
			html_code +='<div class="k-control-bar ui-widget-header ui-helper-clearfix">';
			html_code += this.getControlsHTML();
			html_code +='</div>';
			//block out some space by encapulating the top level div
			if($j(this).parents('.k-player').length==0){
				$j(this).wrap('<div style="width:'+parseInt(this.width)+'px;height:'
					+ (parseInt(this.height) + ctrlBuilder.height )+'px" ' +
					'id="k-player_' + this.id + '" class="k-player ui-widget"></div>'
					);
			}
		}

		//js_log('should set: '+this.id);
		$j(this).html( html_code );
		//add hooks once Controls are in DOM
		ctrlBuilder.addControlHooks(this);

		//js_log('set this to: ' + $j(this).html() );
		//alert('stop');
		//if auto play==true directly embed the plugin
		if(this.autoplay)
		{
			js_log('activating autoplay');
			this.play();
		}
	},
	/*
	* get missing plugin html (check for user included code)
	*/
	getPluginMissingHTML : function(missing_type){
		//keep the box width hight:
		var out = '<div style="width:'+this.width+'px;height:'+this.height+'px">';
		if(this.user_missing_plugin_html){
		  out+= this.user_missing_plugin_html;
		}else{
		  if(!missing_type)
		  	missing_type='';
		  out+= gM('mv_generic_missing_plugin', missing_type) + ' or <a title="'+gM('download_clip')+'" href="'+this.src +'">'+gM('download_clip')+'</a>';
		}
		return out + '</div>';
	},
	updateVideoTimeReq:function(time_req){
		js_log('f:updateVideoTimeReq');
		var time_parts =time_req.split('/');
		this.updateVideoTime(time_parts[0], time_parts[1]);
	},
	//update video time
	updateVideoTime:function(start_ntp, end_ntp){
		//update media
		this.media_element.updateSourceTimes( start_ntp, end_ntp );
		//update mv_time
		this.setStatus(start_ntp+'/'+end_ntp);
		//reset slider
		this.setSliderValue(0);
		//reset seek_offset:
		if(this.media_element.selected_source.URLTimeEncoding )
			this.seek_time_sec=0;
		else
			this.seek_time_sec=npt2seconds(start_ntp);
	},
	//@@todo overwite by embed library if we can render frames natavily
	renderTimelineThumbnail:function( options ){
		var my_thumb_src = this.media_element.getThumbnailURL();
		//check if our thumbnail has a time attribute:
		if( my_thumb_src.indexOf('t=') !== -1){
			var time_ntp =  seconds2npt ( options.time + parseInt(this.start_offset) );
			my_thumb_src = getURLParamReplace( my_thumb_src, { 't':time_ntp, 'size': options.size } );
		}
		var thumb_class = (typeof options['thumb_class'] != 'undefined' ) ? options['thumb_class'] : '';
		return '<div class="ui-corner-all ' + thumb_class + '" src="' + my_thumb_src + '" '+
				'style="height:' + options.height + 'px;' +
				'width:' + options.width + 'px" >' +
					 '<img src="' + my_thumb_src +'" '+
						'style="height:' + options.height + 'px;' +
						'width:' + options.width + 'px">' +
				'</div>';
	},
	updateThumbTimeNTP:function( time){
		this.updateThumbTime( npt2seconds(time) - parseInt(this.start_offset) );
	},
	updateThumbTime:function( float_sec ){
		//js_log('updateThumbTime:'+float_sec);
		var _this = this;
		if( typeof this.org_thum_src=='undefined' ){
			this.org_thum_src = this.media_element.getThumbnailURL();
		}
		if( this.org_thum_src.indexOf('t=') !== -1){
			this.last_thumb_url = getURLParamReplace(this.org_thum_src,
				{ 't' : seconds2npt( float_sec + parseInt(this.start_offset)) } );
			if(!this.thumbnail_updating){
				this.updateThumbnail(this.last_thumb_url ,false);
				this.last_thumb_url =null;
			}
		}
	},
	//for now provide a src url .. but need to figure out how to copy frames from video for plug-in based thumbs
	updateThumbPerc:function( perc ){
		return this.updateThumbTime( (this.getDuration() * perc) );
	},
	//updates the thumbnail if the thumbnail is being displayed
	updateThumbnail : function(src, quick_switch){
		//make sure we don't go to the same url if we are not already updating:
		if( !this.thumbnail_updating && $j('#img_thumb_'+this.id).attr('src')== src )
			return false;
		//if we are already updating don't issue a new update:
		if( this.thumbnail_updating && $j('#new_img_thumb_'+this.id).attr('src')== src )
			return false;

		js_log('update thumb: ' + src);

		if(quick_switch){
			$j('#img_thumb_'+this.id).attr('src', src);
		}else{
			var _this = this;
			//if still animating remove new_img_thumb_
			if(this.thumbnail_updating==true)
				$j('#new_img_thumb_'+this.id).stop().remove();

			if(this.thumbnail_disp){
				js_log('set to thumb:'+ src);
				this.thumbnail_updating=true;
				$j('#dc_'+this.id).append('<img src="'+src+'" ' +
					'style="display:none;position:absolute;zindex:2;top:0px;left:0px;" ' +
					'width="'+this.width+'" height="'+this.height+'" '+
					'id = "new_img_thumb_'+this.id+'" />');
				//js_log('appended: new_img_thumb_');
				$j('#new_img_thumb_'+this.id).fadeIn("slow", function(){
						//once faded in remove org and rename new:
						$j('#img_thumb_'+_this.id).remove();
						$j('#new_img_thumb_'+_this.id).attr('id', 'img_thumb_'+_this.id);
						$j('#img_thumb_'+_this.id).css('zindex','1');
						_this.thumbnail_updating=false;
						//js_log("done fadding in "+ $j('#img_thumb_'+_this.id).attr("src"));

						//if we have a thumb queued update to that
						if(_this.last_thumb_url){
							var src_url =_this.last_thumb_url;
							_this.last_thumb_url=null;
							_this.updateThumbnail(src_url);
						}
				});
			}
		}
	},
	/** Returns the HTML code for the video when it is in thumbnail mode.
		This includes the specified thumbnail as well as buttons for
		playing, configuring the player, inline cmml display, HTML linkback,
		download, and embed code.
	*/
	getThumbnailHTML : function ()
	{
		js_log('embedVideo:getThumbnailHTML::' + this.id);
		var thumb_html = '';
		var class_atr='';
		var style_atr='';
		//if(this.class)class_atr = ' class="'+this.class+'"';
		//if(this.style)style_atr = ' style="'+this.style+'"';
		//	else style_atr = 'overflow:hidden;height:'+this.height+'px;width:'+this.width+'px;';
		this.thumbnail = this.media_element.getThumbnailURL();

		//put it all in the div container dc_id
		thumb_html+= '<div id="dc_'+this.id+'" rel="emdded_play" style="position:relative;'+
			' overflow:hidden; top:0px; left:0px; width:'+this.playerPixelWidth()+'px; height:'+this.playerPixelHeight()+'px; z-index:0;">'+
			'<img width="' + this.playerPixelWidth() + '" height="' + this.playerPixelHeight() +
			'" style="position:relative;width:'+this.playerPixelWidth()+';height:'+this.playerPixelHeight()+'"' +
			' id="img_thumb_' + this.id+'" src="' + this.thumbnail + '">';

		if( this.play_button == true && this.controls == true )
			  thumb_html+=this.getPlayButton();

		thumb_html+='</div>';
		return thumb_html;
	},
	getEmbeddingHTML:function()
	{
		var thumbnail = this.media_element.getThumbnailURL();

		var embed_thumb_html;
		if(thumbnail.substring(0,1)=='/'){
			eURL = parseUri(mv_embed_path);
			embed_thumb_html = eURL.protocol + '://' + eURL.host + thumbnail;
			//js_log('set from mv_embed_path:'+embed_thumb_html);
		}else{
			embed_thumb_html = (thumbnail.indexOf('http://')!=-1)?thumbnail:mv_embed_path + thumbnail;
		}
		var embed_code_html = '&lt;script type=&quot;text/javascript&quot; ' +
					'src=&quot;'+mv_embed_path+'mv_embed.js&quot;&gt;&lt;/script&gt' +
					'&lt;video ';
		if(this.roe){
			embed_code_html+='roe=&quot;'+this.roe+'&quot; &gt;';
		}else{
			embed_code_html+='src=&quot;'+this.src+'&quot; ' +
				'poster=&quot;'+embed_thumb_html+'&quot;&gt;';
		}
		//close the video tag
		embed_code_html+='&lt;/video&gt;';

		return embed_code_html;
	},
	doOptionsHTML:function()
	{
		var sel_id = (this.pc!=null)?this.pc.pp.id:this.id;
		var pos = $j('#options_button_'+sel_id).offset();
		pos['top']=pos['top']+24;
		pos['left']=pos['left']-124;
		//js_log('pos of options button: t:'+pos['top']+' l:'+ pos['left']);
		$j('#mv_vid_options_'+sel_id).css(pos).toggle();
		return;
	},
	getPlayButton:function(id){
		if(!id)id=this.id;
		return '<div title="' + gM('play_clip') + '" class="ui-state-default play-btn-large" '+
			'style="left:'+((this.playerPixelWidth()-130)/2)+'px;'+
			'top:' + ((this.playerPixelHeight()-96)/2) + 'px;">'+
			'</div>';
	},
	doLinkBack:function(){
		if(this.roe && this.media_element.addedROEData==false){
			var _this = this;
			this.displayHTML(gM('loading_txt'));
			do_request(this.roe, function(data)
			   {
				  _this.media_element.addROE(data);
				  _this.doLinkBack();
			   });
		}else{
			if(this.media_element.linkback){
				window.location = this.media_element.linkback;
			}else{
				this.displayHTML(gM('could_not_find_linkback'));
			}
		}
	},
	showCredits: function($target){
		$target.html('credits page goes here');	
	},
	//display the code to remotely embed this video:
	showShare:function($target){	
		var	embed_code = this.getEmbeddingHTML();
		var o = '';
		if(this.linkback){
			o+='<a class="email" href="'+this.linkback+'">Share Clip via Link</a> '+
			'<p>or</p> ';
		}
		o+='<span style="color:#FFF;font-size:14px;">Embed Clip in Blog or Site</span><br>'+
				'<span style="color:#FFF;font-size:12px;"><a style="color:red" href="http://metavid.org/wiki/Security_Notes_on_Remote_Embedding">'+
					'Read This</a> before embeding.</span>'+
				'<div class="embed_code"> '+
					'<textarea onClick="this.select();" id="embedding_user_html_' + this.id + '" name="embed">' +
						embed_code+
					'</textarea> '+
					'<button onClick="$j(\'#' + this.id + '\').get(0).copyText(); return false;" class="copy_to_clipboard">Copy to Clipboard</button> '+
				'</div>';			
		js_log("should set share: " + o);
		$target.html(o);		
	},
	copyText:function(){
	  $j('#embedding_user_html_'+this.id).focus().select();
	  if(document.selection){
		  CopiedTxt = document.selection.createRange();
		  CopiedTxt.execCommand("Copy");
	  }
	},
	showTextInterface:function(){
		var _this = this;
		//display the text container with loading text:
		//@@todo support position config
		var loc = $j(this).position();
		if($j('#metaBox_'+this.id).length==0){
			$j(this).after('<div class="ui-widget ui-widget-content ui-corner-all" style="position:absolute;z-index:10;'+
						'top:' + (loc.top) + 'px;' +
						'left:' + (parseInt( loc.left ) + parseInt(this.width) + 10 )+'px;' +
						'height:'+ parseInt( this.height )+'px;width:400px;' +
						'display:none;" ' +
						'id="metaBox_' + this.id + '">'+
							gM('loading_txt') +
						'</div>');
		}
		//fade in the text display
		$j('#metaBox_'+this.id).fadeIn("fast");
		//check if textObj present:
		if(typeof this.textInterface == 'undefined' ){
			//load the default text interface:
			mvJsLoader.doLoad([
					'mvTextInterface',
					'$j.fn.hoverIntent'
				], function(){
					_this.textInterface = new mvTextInterface( _this );
					//show interface
					_this.textInterface.show();
					js_log("NEW TEXT INTERFACE");
					for(var i in _this.textInterface.availableTracks){
						js_log("tracks in new interface: "+_this.id+ ' tid:' + i);
					}
				}
			);
		}else{
			//show interface
			this.textInterface.show();
		}
	},
	closeTextInterface:function(){
		js_log('closeTextInterface '+ typeof this.textInterface);
		if(typeof this.textInterface !== 'undefined' ){
			this.textInterface.close();
		}
	},
	/** Generic function to display custom HTML inside the mv_embed element.
		The code should call the closeDisplayedHTML function to close the
		display of the custom HTML and restore the regular mv_embed display.
		@param {String} HTML code for the selection list.
	*/
	displayHTML:function(html_code)
	{
		var sel_id = (this.pc!=null)?this.pc.pp.id:this.id;

		if(!this.supports['overlays'])
			this.stop();

		//put select list on-top
		//make sure the parent is relatively positioned:
		$j('#'+sel_id).css('position', 'relative');
		//set height width (check for playlist container)
		var width = (this.pc)?this.pc.pp.width:this.playerPixelWidth();
		var height = (this.pc)?this.pc.pp.height:this.playerPixelHeight();

		if(this.pc)
			height+=(this.pc.pp.pl_layout.title_bar_height + this.pc.pp.pl_layout.control_height);

		var fade_in = true;
		if($j('#blackbg_'+sel_id).length!=0)
		{
			fade_in = false;
			$j('#blackbg_'+sel_id).remove();
		}
		//fade in a black bg div ontop of everything
		 var div_code = '<div id="blackbg_'+sel_id+'" class="videoComplete" ' +
			 'style="height:'+parseInt(height)+'px;width:'+parseInt(width)+'px;">'+
			  '<div class="videoOptionsComplete">'+
			//@@TODO: this style should go to .css
			'<span style="float:right;margin-right:10px">' +
					'<a href="#" style="color:white;" onClick="$j(\'#'+sel_id+'\').get(0).closeDisplayedHTML();return false;">close</a>' +
			'</span>'+
			'<div id="mv_disp_inner_'+sel_id+'" style="padding-top:10px;">'+
				 html_code
			   +'</div>'+
			   '</div></div>';
		$j('#'+sel_id).prepend(div_code);
		if (fade_in)
			$j('#blackbg_'+sel_id).fadeIn("slow");
		else
			$j('#blackbg_'+sel_id).show();
		return false; //onclick action return false
	},
	/** Close the custom HTML displayed using displayHTML and restores the
		regular mv_embed display.
	*/
	closeDisplayedHTML:function(){
		  var sel_id = (this.pc!=null)?this.pc.pp.id:this.id;
		 $j('#blackbg_'+sel_id).fadeOut("slow", function(){
			 $j('#blackbg_'+sel_id).remove();
		 });
		 return false; //onclick action return false
	},
	showPlayerselect:function( target ){
		//get id (in case where we have a parent container)
		var this_id = (this.pc!=null)?this.pc.pp.id:this.id;
		var _this=this;
//		var out= '<span style="color:#FFF;background-color:black;"><blockquote style="background-color:black;">';
		var o= '';
		o+='<h2>' + gM('chose_player') + '</h2>';
		var _this=this;
		//js_log('selected src'+ _this.media_element.selected_source.url);
		$j.each( this.media_element.getPlayableSources(), function(source_id, source){
			var default_player = embedTypes.players.defaultPlayer( source.getMIMEType() );

			var is_selected = (source == _this.media_element.selected_source);
			var image_src =  mv_skin_img_path ;

			//set the Playable source type:
			/*if( source.mime_type == 'video/x-flv' ){
				image_src += 'flash_icon_';
			}else if( source.mime_type == 'video/h264'){
				//for now all mp4 content is pulled from archive.org (so use archive.org icon)
				image_src += 'archive_org_';
			}else{
				image_src += 'fish_xiph_org_';
			}
			image_src += is_selected ? 'color':'bw';
			image_src += '.png';
			*/
			if (default_player){
				o+='<ul>';
				//output the player select code:
				var supporting_players = embedTypes.players.getMIMETypePlayers( source.getMIMEType() );

				for(var i=0; i < supporting_players.length ; i++){
					if( _this.selected_player.id == supporting_players[i].id && is_selected ){
						o+='<li>' +
							'<a href="#" class="active" rel="sel_source" id="sc_' + source_id + '_' + supporting_players[i].id +'">' +
								supporting_players[i].getName() +
							'</li>';
					}else{
						//else gray plugin and the plugin with link to select
						/*out+='<li style="margin-left:20px;">'+
						'<a href="#" class="sel_source" id="sc_' + source_id + '_' + supporting_players[i].id +'">'+
							'<img border="0" width="16" height="16" src="' + mv_skin_img_path + 'plugin_disabled.png">'+
							supporting_players[i].getName() +
						'</a>'+*/
                        o+='<li>' +
                         '<a href="#" rel="sel_source" id="sc_' + source_id + '_' + supporting_players[i].id +'">' +
                         	supporting_players[i].getName() + '</a>' +
						'</li>';
					}
				 }
				 o+='</ul>';
			}else{
				o+= source.getTitle() + ' - no player available';
			}
		});
		$j(target).html(o);

		//set up the click bindings:
		$j(target).find("[rel='sel_source']").each(function(){
			$j(this).click(function(){
				var iparts = $j(this).attr( 'id' ).replace(/sc_/,'').split('_');
				var source_id = iparts[0];
				var default_player_id = iparts[1];
				js_log('source id: ' +  source_id + ' player id: ' + default_player_id);

				$j('#' + this_id  ).get(0).closeDisplayedHTML();
				$j('#' + _this.id ).get(0).media_element.selectSource( source_id );

				embedTypes.players.userSelectPlayer( default_player_id,
					 _this.media_element.sources[ source_id ].getMIMEType() );

				//be sure to issue a stop
				$j('#' + this_id  ).get(0).stop();

				//don't follow the empty # link:
				return false;
			});
		});
	},
	showDownload:function( $target ){
		//load the roe if available (to populate out download options:
		//js_log('f:showDownload '+ this.roe + ' ' + this.media_element.addedROEData);
		if(this.roe && this.media_element.addedROEData == false){
			var _this = this;
			$target.html( gM('loading_txt') );
			do_request(this.roe, function(data)
			{
			   _this.media_element.addROE(data);
			   $target.html( _this.getShowVideoDownload() );
			});
		}else{
			$target.html( this.getShowVideoDownload() );
		}
	},
	getShowVideoDownload:function(){        
		var dl_txt_list = dl_list = '';
		$j.each(this.media_element.getSources(), function(index, source){
			var dl_line = '<li>' + '<a href="' + source.getURI() +'"> ' + source.getTitle() + '</a></li>\n';			
			if(this.getMIMEType()=="text/cmml" || this.getMIMEType()=="text/x-srt") {
	            dl_txt_list += dl_line;
			}else {
				dl_list += dl_line;
			}
		});
		var o='<h2>' + gM('download_clip') + '</h2>'+
                '<ul>' +
                	dl_list +                 
				'</ul>';
				
		//add text links: 		
		if(dl_txt_list != '')
			o+='<h2>' + gM('download_text') + '</h2>' + 
				'<ul>' +
                	dl_txt_list +                 
				'</ul>';
					
		o+='</div>';
		return o;
	},
	/*
	*  base embed controls
	*	the play button calls
	*/
	play:function(){
		var this_id = (this.pc!=null)?this.pc.pp.id:this.id;

		//js_log( "mv_embed play:" + this.id);
		//js_log('thum disp:'+this.thumbnail_disp);
		//check if thumbnail is being displayed and embed html
		if( this.thumbnail_disp ){
			if( !this.selected_player ){
				js_log('no selected_player');
				//this.innerHTML = this.getPluginMissingHTML();
				//$j(this).html(this.getPluginMissingHTML());
				$j('#'+this.id).html( this.getPluginMissingHTML() );
			}else{
				this.doEmbedHTML();
				this.onClipDone_disp=false;
				this.paused=false;
				this.thumbnail_disp=false;
			}
		}else{
			//the plugin is already being displayed
			this.paused=false; //make sure we are not "paused"
			this.seeking=false;
		}

		$j('#'+ this_id + ' .play-btn .ui-icon').removeClass('ui-icon-play').addClass('ui-icon-pause');
		$j('#'+ this_id + ' .play-btn').unbind().btnBind().click(function(){
		 	$j('#' + this_id ).get(0).pause();
		}).attr('title', gM('pause_clip'));

	},
	load:function(){
		//should be done by child (no base way to load assets)
		js_log('baseEmbed:load call');
	},
	getSrc:function(){
	   return this.media_element.selected_source.getURI( this.seek_time_sec );
	},
	/*
	 * base embed pause
	 *  there is no general way to pause the video
	 *  must be overwritten by embed object to support this functionality.
	 */
	pause: function(){
		var this_id = (this.pc!=null)?this.pc.pp.id:this.id;
		//js_log('mv_embed:do pause');
		//(playing) do pause
		this.paused = true;
		//update the ctrl "paused state"
		$j('#'+ this_id + ' .play-btn .ui-icon').removeClass('ui-icon-pause').addClass('ui-icon-play');
		$j('#'+ this_id + ' .play-btn').unbind().btnBind().click(function(){
				$j('#'+this_id).get(0).play();
		}).attr('title', gM('play_clip'));
	},
	/*
	 * base embed stop (can be overwritten by the plugin)
	 */
	stop: function(){
		var _this = this;
		js_log('mvEmbed:stop:'+this.id);

		//no longer seeking:
		this.didSeekJump=false;

		//first issue pause to update interface	(only call the parent)
		if(this['parent_pause']){
			this.parent_pause();
		}else{
			this.pause();
		}

		//reset the currentTime:
		this.currentTime=0;
		//check if thumbnail is being displayed in which case do nothing
		if( this.thumbnail_disp ){
			//already in stooped state
			js_log('already in stopped state');
		}else{
			//rewrite the html to thumbnail disp
			this.doThumbnailHTML();
			this.bufferedPercent=0; //reset buffer state
			this.setSliderValue(0);
			this.setStatus( this.getTimeReq() );
		}

		//make sure the big playbutton is has click action:
		$j('#'+ _this.id +' .play-btn-large').unbind('click').btnBind().click(function(){
			$j('#' +_this.id).get(0).play();
		});

		if(this.update_interval)
		{
			clearInterval(this.update_interval);
			this.update_interval = null;
		}
	},
	toggleMute:function(){
		var this_id = (this.pc!=null)?this.pc.pp.id:this.id;
		if(this.muted){
			this.muted=false;
			$j( '#volume_control_' + this_id + ' span').removeClass('ui-icon-volume-off').addClass('ui-icon-volume-on');
			$j( '#volume_bar_' + this_id).slider('value', 100);
			this.updateVolumen(1);
		}else{
			this.muted=true;
			$j('#volume_control_'+this_id + ' span').removeClass('ui-icon-volume-on').addClass('ui-icon-volume-off');
			$j('#volume_bar_'+this_id).slider('value', 0);
			this.updateVolumen(0);
		}
		js_log('f:toggleMute::' + this.muted);
	},
	updateVolumen:function(perc){
		js_log('update volume not supported with current playback type');
	},
	fullscreen:function(){
		js_log('fullscreen not supported with current playback type');
	},
	/* returns bool true if playing or paused, false if stopped
	 */
	isPlaying : function(){
		if(this.thumbnail_disp){
			//in stoped state
			return false;
		}else if( this.paused ){
			//paused state
			return false;
		}else{
			return true;
		}
	},
	isPaused : function(){
		return this.isPlaying() && this.paused;
	},
	isStoped : function(){
		return this.thumbnail_disp;
	},
	playlistSupport:function(){
		//by default not supported (implemented in js)
		return false;
	},
	postEmbedJS:function(){
		return '';
	},
	//do common monitor code like update the playhead and play status
	//plugin objects are responsible for updating currentTime
	monitor:function(){
		if( this.currentTime && this.currentTime > 0 && this.duration){
			if( !this.userSlide ){
				if( this.start_offset  ){
					//if start offset include that calculation
					this.setSliderValue( ( this.currentTime - this.start_offset ) / this.duration );
					this.setStatus( seconds2npt(this.currentTime) + '/'+ seconds2npt(parseFloat(this.start_offset)+parseFloat(this.duration) ));
				}else{
					this.setSliderValue( this.currentTime / this.duration );
					this.setStatus( seconds2npt(this.currentTime) + '/' + seconds2npt(this.duration ));
				}
			}
		}else{
			//js_log(' ct:' + this.currentTime + ' dur: ' + this.duration);
			if( this.isStoped() ){
				this.setStatus( this.getTimeReq() );
			}else if( this.isPaused() ){
				this.setStatus( "paused" );
			}else if( this.isPlaying() ){
				if( this.currentTime && ! this.duration )
					this.setStatus( seconds2npt( this.currentTime ) + ' /' );
				else
					this.setStatus(" - - - ");
			}else{
				this.setStatus( this.getTimeReq() );
			}
		}
		//update buffer information
		this.updateBufferStatus();

		//update monitorTimerId to call child monitor
		if( ! this.monitorTimerId ){
			//make sure an instance of this.id exists:
			if( document.getElementById(this.id) ){
				this.monitorTimerId = setInterval('$j(\'#'+this.id+'\').get(0).monitor()', 250);
			}
		}
	},
	stopMonitor:function(){
		if( this.monitorTimerId != 0 )
		{
			clearInterval( this.monitorTimerId );
			this.monitorTimerId = 0;
		}
	},
	updateBufferStatus: function(){
		//build the buffer targeet based for playlist vs clip
		var buffer_select = (this.pc) ?
			'#cl_status_' + this.id + ' .ui-slider-buffer':
			'#' + this.id + ' .ui-slider-buffer';

		//update the buffer progress bar (if available )
		if( this.bufferedPercent != 0 ){
			//js_log('bufferedPercent: ' + this.bufferedPercent);
			if(this.bufferedPercent > 1)
				this.bufferedPercent=1;

			$j(buffer_select).css("width", (this.bufferedPercent*100) +'%' );
		}else{
			$j(buffer_select).css("width", '0px' );
		}
	},
	relativeCurrentTime: function(){
		if(!this.start_offset)
			this.start_offset =0;
		var rt = this.currentTime - this.start_offset;
		if( rt < 0 ) //should not happen but does.
			return 0;
		return rt;
	},
	getPluginEmbed : function(){
		if (window.document[this.pid]){
			return window.document[this.pid];
		}
		if ($j.browser.msie){
			return document.getElementById(this.pid );
		}else{
			 if (document.embeds && document.embeds[this.pid])
				return  document.embeds[this.pid];
		}
		return null;
	},
	//HELPER Functions for selected source
	/*
	* returns the selected source url for players to play
	*/
	getURI : function( seek_time_sec ){
		return this.media_element.selected_source.getURI( this.seek_time_sec );
	},
	supportsURLTimeEncoding: function(){
		//do head request if on the same domain
		return this.media_element.selected_source.URLTimeEncoding;
	},
	setSliderValue: function(perc, hide_progress){
		if(this.controls){
			var this_id = (this.pc)?this.pc.pp.id:this.id;
			var val = parseInt( perc*1000 );
			$j('#'+this.id + ' .j-scrubber').slider('value', val);
		}
		//js_log('set#mv_seeker_slider_'+this_id + ' perc in: ' + perc + ' * ' + $j('#mv_seeker_'+this_id).width() + ' = set to: '+ val + ' - '+ Math.round(this.mv_seeker_width*perc) );
		//js_log('op:' + offset_perc + ' *('+perc+' * ' + $j('#slider_'+id).width() + ')');
	},
	highlightPlaySection:function(options){
		js_log('highlightPlaySection');
		var this_id = (this.pc)?this.pc.pp.id:this.id;
		var dur = this.getDuration();
		var hide_progress = true;
		//set the left percet and update the slider:
		rel_start_sec = npt2seconds( options['start']);
		//remove the start_offset if relevent:
		if(this.start_offset)
			rel_start_sec = rel_start_sec - this.start_offset

		var slider_perc=0;
		if( rel_start_sec <= 0 ){
			left_perc =0;
			options['start'] = seconds2npt( this.start_offset );
			rel_start_sec=0;
			this.setSliderValue( 0 , hide_progress);
		}else{
			left_perc = parseInt( (rel_start_sec / dur)*100 ) ;
			slider_perc = (left_perc / 100);
		}
		js_log("slider perc:" + slider_perc);
		if( ! this.isPlaying() ){
			this.setSliderValue( slider_perc , hide_progress);
		}

		width_perc = parseInt( (( npt2seconds( options['end'] ) - npt2seconds( options['start'] ) ) / dur)*100 ) ;
		if( (width_perc + left_perc) > 100 ){
			width_perc = 100 - left_perc;
		}
		//js_log('should hl: '+rel_start_sec+ '/' + dur + ' re:' + rel_end_sec+' lp:'  + left_perc + ' width: ' + width_perc);
		$j('#mv_seeker_' + this_id + ' .mv_highlight').css({
			'left':left_perc+'%',
			'width':width_perc+'%'
		}).show();

		this.jump_time =  options['start'];
		this.seek_time_sec = npt2seconds( options['start']);
		//trim output to
		this.setStatus( gM('seek_to')+' '+ seconds2npt( this.seek_time_sec ) );
		js_log('DO update: ' +  this.jump_time);
		this.updateThumbTime( rel_start_sec );
	},
	hideHighlight:function(){
		var this_id = (this.pc)?this.pc.pp.id:this.id;
		$j('#mv_seeker_' + this_id + ' .mv_highlight').hide();
		this.setStatus( this.getTimeReq() );
		this.setSliderValue( 0 );
	},
	setStatus:function(value){
		var id = (this.pc)?this.pc.pp.id:this.id;
		//update status:
		//$j('#mv_time_'+id).html(value);
		$j('#'+this.id + ' .k-timer').html(value);
	}
}



/**
  * mediaPlayer represents a media player plugin.
  * @param {String} id id used for the plugin.
  * @param {Array<String>} supported_types n array of supported MIME types.
  * @param {String} library external script containing the plugin interface code. (mv_<library>Embed.js)
  * @constructor
  */
function mediaPlayer(id, supported_types, library)
{
	this.id=id;
	this.supported_types = supported_types;
	this.library = library;
	this.loaded = false;
	this.loading_callbacks = new Array();
	return this;
}
mediaPlayer.prototype =
{
	id:null,
	supported_types:null,
	library:null,
	loaded:false,
	loading_callbacks:null,
	supportsMIMEType : function(type)
	{
		for (var i=0; i < this.supported_types.length; i++)
			if(this.supported_types[i] == type)
				return true;
		return false;
	},
	getName : function()
	{
		return gM('mv_ogg-player-' + this.id);
	},
	load : function(callback){
		var libName = this.library+'Embed';
		if( mvJsLoader.checkObjPath( libName ) ){
			js_log('plugin loaded, do callback:');
			callback();
		}else{
			var _this = this;
			//jQuery based get script does not work so well.
			mvJsLoader.doLoad([
				libName
			],function(){
				callback();
			});
		}
	}
}
/* players and supported mime types
@@todo ideally we query the plugin to get what mime types it supports in practice not always reliable/avaliable
*/
var flowPlayer = new mediaPlayer('flowplayer',['video/x-flv', 'video/h264'],'flash');

var omtkPlayer = new mediaPlayer('omtkplayer',['audio/ogg'], 'omtk' );

var cortadoPlayer = new mediaPlayer('cortado',['video/ogg', 'audio/ogg'],'java');
var videoElementPlayer = new mediaPlayer('videoElement',['video/ogg', 'audio/ogg'],'native');

var vlcMineList = ['video/ogg','audio/ogg', 'video/x-flv', 'video/mp4',  'video/h264'];
var vlcMozillaPlayer = new mediaPlayer('vlc-mozilla',vlcMineList,'vlc');
var vlcActiveXPlayer = new mediaPlayer('vlc-activex',vlcMineList,'vlc');

//add generic
var oggPluginPlayer = new mediaPlayer('oggPlugin',['video/ogg'],'generic');

//depricate quicktime in favor of safari native
//var quicktimeMozillaPlayer = new mediaPlayer('quicktime-mozilla',['video/ogg'],'quicktime');
//var quicktimeActiveXPlayer = new mediaPlayer('quicktime-activex',['video/ogg'],'quicktime');

var htmlPlayer = new mediaPlayer('html',['text/html', 'image/jpeg', 'image/png', 'image/svg'], 'html');

/**
  * mediaPlayers is a collection of mediaPlayer objects supported by the client.
  * It could be merged with embedTypes, since there is one embedTypes per script
  * and one mediaPlayers per embedTypes.
  */
function mediaPlayers()
{
	this.init();
}

mediaPlayers.prototype =
{
	players : null,
	preference : null,
	default_players : {},
	init : function()
	{
		this.players = new Array();
		this.loadPreferences();

		//set up default players order for each library type
		this.default_players['video/x-flv'] = ['flash','vlc'];
		this.default_players['video/h264'] = ['flash', 'vlc'];

		this.default_players['video/ogg'] = ['native','vlc','java', 'generic'];
		this.default_players['application/ogg'] = ['native','vlc','java', 'generic'];
		this.default_players['audio/ogg'] = ['native','vlc', 'java', 'omtk' ];
		this.default_players['video/mp4'] = ['vlc'];

		this.default_players['text/html'] = ['html'];
		this.default_players['image/jpeg'] = ['html'];
		this.default_players['image/png'] = ['html'];
		this.default_players['image/svg'] = ['html'];

	},
	addPlayer : function(player, mime_type)
	{
		//js_log('Adding ' + player.id + ' with mime_type ' + mime_type);
		for (var i =0; i < this.players.length; i++){
			if (this.players[i].id == player.id)
			{
				if(mime_type!=null)
				{
					//make sure the mime_type is not already there:
					var add_mime = true;
					for(var j=0; j < this.players[i].supported_types.length; j++ ){
						if( this.players[i].supported_types[j]== mime_type)
							add_mime=false;
					}
					if(add_mime)
						this.players[i].supported_types.push(mime_type);
				}
				return;
			}
		}
		//player not found:
		if(mime_type!=null)
			player.supported_types.push(mime_type);

		this.players.push( player );
	},
	getMIMETypePlayers : function(mime_type)
	{
		var mime_players = new Array();
		var _this = this;
		var inx = 0;
		if( this.default_players[mime_type] ){
			$j.each( this.default_players[mime_type], function(d, lib){
				var library = _this.default_players[mime_type][d];
				for ( var i=0; i < _this.players.length; i++ ){
					if ( _this.players[i].library == library && _this.players[i].supportsMIMEType(mime_type) ){
						mime_players[ inx ] = _this.players[i];
						inx++;
					}
				}
			});
		}
		return mime_players;
	},
	defaultPlayer : function(mime_type)
	{
		js_log("get defaultPlayer for " + mime_type);
		var mime_players = this.getMIMETypePlayers(mime_type);
		if( mime_players.length > 0)
		{
			// check for prior preference for this mime type
			for( var i=0; i < mime_players.length; i++ ){
				if( mime_players[i].id==this.preference[mime_type] )
					return mime_players[i];
			}
			// otherwise just return the first compatible player
			// (it will be chosen according to the default_players list
			return mime_players[0];
		}
		js_log( 'No default player found for ' + mime_type );
		return null;
	},
	userSelectFormat : function (mime_format){
		 this.preference['format_prefrence'] = mime_format;
		 this.savePreferences();
	},
	userSelectPlayer : function(player_id, mime_type)
	{
		var selected_player=null;
		for(var i=0; i < this.players.length; i++){
			if(this.players[i].id == player_id)
			{
				selected_player = this.players[i];
				js_log('choosing ' + player_id + ' for ' + mime_type);
				this.preference[mime_type]=player_id;
				this.savePreferences();
				break;
			}
		}
		if( selected_player )
		{
			for(var i=0; i < global_player_list.length; i++)
			{
				var embed = $j('#'+global_player_list[i]).get(0);
				if(embed.media_element.selected_source && (embed.media_element.selected_source.mime_type == mime_type))
				{
					embed.selectPlayer(selected_player);
					js_log('using ' + embed.selected_player.getName() + ' for ' + embed.media_element.selected_source.getTitle());
				}
			}
		}
	},
	loadPreferences : function()
	{
		this.preference = new Object();
		// see if we have a cookie set to a clientSupported type:
		var cookieVal = $j.cookie( 'ogg_player_exp' );
		if (cookieVal)
		{
			var pairs = cookieVal.split('&');
			for(var i=0; i < pairs.length; i++)
			{
				var name_value = pairs[i].split('=');
				this.preference[name_value[0]]=name_value[1];
				//js_log('load preference for ' + name_value[0] + ' is ' + name_value[1]);
			}
		}
	},
	savePreferences : function()
	{
		var cookieVal = '';
		for(var i in this.preference)
			cookieVal+= i + '='+ this.preference[i] + '&';

		cookieVal=cookieVal.substr(0, cookieVal.length-1);
		var week = 7*86400*1000;
		$j.cookie( 'ogg_player_exp', cookieVal, { 'expires':week } );
	}
};

/*
 * embedTypes object handles setting and getting of supported embed types:
 * closely mirrors OggHandler so that its easier to share efforts in this area:
 * http://svn.wikimedia.org/viewvc/mediawiki/trunk/extensions/OggHandler/OggPlayer.js
 */
var embedTypes = {
	 // List of players
	 players: null,
	 detect_done:false,
	 init: function(){
		//detect supported types
		this.detect();
		this.detect_done=true;
	},
	clientSupports: { 'thumbnail' : true },
	supportedMimeType: function(mimetype) {
		for (var i = navigator.plugins.length; i-- > 0; ) {
			var plugin = navigator.plugins[i];
			if (typeof plugin[mimetype] != "undefined")
			  return true;
		}
		return false;
	},

	 detect: function() {
		 js_log("running detect");
		this.players = new mediaPlayers();
		//every browser supports html rendering:
		this.players.addPlayer( htmlPlayer );
		 // In Mozilla, navigator.javaEnabled() only tells us about preferences, we need to
		 // search navigator.mimeTypes to see if it's installed
		 var javaEnabled = navigator.javaEnabled();
		 // In Opera, navigator.javaEnabled() is all there is
		 var invisibleJava = $j.browser.opera;
		 // Some browsers filter out duplicate mime types, hiding some plugins
		 var uniqueMimesOnly = $j.browser.opera || $j.browser.safari;
		 // Opera will switch off javaEnabled in preferences if java can't be found.
		 // And it doesn't register an application/x-java-applet mime type like Mozilla does.
		 if ( invisibleJava && javaEnabled )
			 this.players.addPlayer( cortadoPlayer );

		 // ActiveX plugins
		 if($j.browser.msie){
			  // check for flash
			   if ( this.testActiveX( 'ShockwaveFlash.ShockwaveFlash')){
				   //try to get the flash version for omtk include:
				   try {
					a = new ActiveXObject(SHOCKWAVE_FLASH_AX + ".7");
					d = a.GetVariable("$version");	// Will crash fp6.0.21/23/29
					if (d) {
						d = d.split(" ")[1].split(",");
						//we need flash version 10 or greater:
						if(parseInt( d[0]) >=10){
							this.players.addPlayer( omtkPlayer );
						}

					}
				}catch(e) {}

				   //flowplayer has pretty good compatiablity
				   // (but if we wanted to be fancy we would check for version of flash and update the mp4/h.264 support
				   this.players.addPlayer( flowPlayer );
			   }
			 // VLC
			 if ( this.testActiveX( 'VideoLAN.VLCPlugin.2' ) )
				 this.players.addPlayer(vlcActiveXPlayer);
			 // Java
			 if ( javaEnabled && this.testActiveX( 'JavaWebStart.isInstalled' ) )
				 this.players.addPlayer(cortadoPlayer);
			 // quicktime
			 //if ( this.testActiveX( 'QuickTimeCheckObject.QuickTimeCheck.1' ) )
			 //	this.players.addPlayer(quicktimeActiveXPlayer);
		 }
		// <video> element
		if ( typeof HTMLVideoElement == 'object' // Firefox, Safari
				|| typeof HTMLVideoElement == 'function' ) // Opera
		{
			//do another test for safari:
			if( $j.browser.safari ){
				try{
					var dummyvid = document.createElement("video");
					if (dummyvid.canPlayType && dummyvid.canPlayType("video/ogg;codecs=\"theora,vorbis\"") == "probably")
					{
						this.players.addPlayer( videoElementPlayer );
					} else if(this.supportedMimeType( 'video/ogg' )) {
						/* older versions of safari do not support canPlayType,
						   but xiph qt registers mimetype via quicktime plugin */
						this.players.addPlayer( videoElementPlayer );
					} else {
						//@@todo add some user nagging to install the xiph qt
					}
				}catch(e){
					js_log('could not run canPlayType in safari');
				}
			}else{
				this.players.addPlayer( videoElementPlayer );
			}
		}

		 // Mozilla plugins
		if( navigator.mimeTypes && navigator.mimeTypes.length > 0) {
			for ( var i = 0; i < navigator.mimeTypes.length; i++ ) {
				var type = navigator.mimeTypes[i].type;
				var semicolonPos = type.indexOf( ';' );
				if ( semicolonPos > -1 ) {
					type = type.substr( 0, semicolonPos );
				}
				//js_log('on type: '+type);
				var pluginName = navigator.mimeTypes[i].enabledPlugin ? navigator.mimeTypes[i].enabledPlugin.name : '';
				if ( !pluginName ) {
					// In case it is null or undefined
					pluginName = '';
				}
				if ( pluginName.toLowerCase() == 'vlc multimedia plugin' || pluginName.toLowerCase() == 'vlc multimedia plug-in' ) {
					this.players.addPlayer(vlcMozillaPlayer, type);
					continue;
				}

				if ( javaEnabled && type == 'application/x-java-applet' ) {
					this.players.addPlayer(cortadoPlayer);
					continue;
				}

				if ( type == 'application/ogg' ) {
					if ( pluginName.toLowerCase() == 'vlc multimedia plugin' ){
						this.players.addPlayer(vlcMozillaPlayer, type);
					//else if ( pluginName.indexOf( 'QuickTime' ) > -1 )
					//	this.players.addPlayer(quicktimeMozillaPlayer);
					}else{
						this.players.addPlayer(oggPluginPlayer);
					}
					continue;
				} else if ( uniqueMimesOnly ) {
					if ( type == 'application/x-vlc-player' ) {
						this.players.addPlayer(vlcMozillaPlayer, type);
						continue;
					} else if ( type == 'video/quicktime' ) {
						//this.players.addPlayer(quicktimeMozillaPlayer);
						continue;
					}
				}

				/*if ( type == 'video/quicktime' ) {
					this.players.addPlayer(vlcMozillaPlayer, type);
					continue;
				}*/
				if(type=='application/x-shockwave-flash'){
					this.players.addPlayer( flowPlayer );

					//check version to add omtk:
					var flashDescription = navigator.plugins["Shockwave Flash"].description;
					var descArray = flashDescription.split(" ");
					var tempArrayMajor = descArray[2].split(".");
					var versionMajor = tempArrayMajor[0];
					//js_log("version of flash: " + versionMajor);
					if(versionMajor >= 10){
						this.players.addPlayer( omtkPlayer );
					}
					continue;
				}
			}
		}
		//@@The xiph quicktime component does not work well with annodex streams (temporarly disable)
		//this.clientSupports['quicktime-mozilla'] = false;
		//this.clientSupports['quicktime-activex'] = false;
		//js_log(this.clientSupports);
	},
	testActiveX : function ( name ) {
		 var hasObj = true;
		 try {
			 // No IE, not a class called "name", it's a variable
			 var obj = new ActiveXObject( '' + name );
		 } catch ( e ) {
			 hasObj = false;
		 }
		 return hasObj;
	}
};
