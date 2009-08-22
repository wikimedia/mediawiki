/*
 * ~mv_embed ~
 * for details see: http://metavid.org/wiki/index.php/Mv_embed
 *
 * All Metavid Wiki code is Released under the GPL2
 * for more info visit http://metavid.org/wiki/Code
 *
 * @url http://metavid.org
 *
 * parseUri:
 * http://stevenlevithan.com/demo/parseuri/js/
 *
 * config values you can manually set the location of the mv_embed folder here
 * (in cases where media will be hosted in a different place than the embedding page)
 *
 */
//fix multiple instances of mv_embed (ie include twice from two different servers)
var MV_DO_INIT=true;
if( MV_EMBED_VERSION ){
	MV_DO_INIT=false;
}
//used to grab fresh copies of scripts. (should be changed on commit)
var MV_EMBED_VERSION = '1.0r19';

/*
 * Configuration variables (can be set from some precceding script)
 * set up mwConfig global overide any of the defaultMwConfig values:
 * @@ more config valuse on the way ;)
 */
var defaultMwConfig = {
	'skin_name': 'mvpcf',
	'jui_skin': 'redmond',
	'video_size':'400x300'
}

if(!mwConfig)
	  var mwConfig = {};

//install the default config values for anything not set in  mwConfig
checkDefaultMwConfig();

//whether or not to load java from an iframe.
//note: this is necessary for remote embedding because of java security model)
if(!mv_java_iframe)
	var mv_java_iframe = true;

//for when useing mv_embed with script-loader in root mediawiki path
var mediaWiki_mvEmbed_path = 'js2/mwEmbed/';

var global_player_list = new Array(); //the global player list per page
var global_req_cb = new Array(); //the global request callback array
var _global = this; //global obj
var mv_init_done = false;
var global_cb_count =0;

/*parseUri class parses URIs:*/
var parseUri=function(d){var o=parseUri.options,value=o.parser[o.strictMode?"strict":"loose"].exec(d);for(var i=0,uri={};i<14;i++){uri[o.key[i]]=value[i]||""}uri[o.q.name]={};uri[o.key[12]].replace(o.q.parser,function(a,b,c){if(b)uri[o.q.name][b]=c});return uri};parseUri.options={strictMode:false,key:["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],q:{name:"queryKey",parser:/(?:^|&)([^&=]*)=?([^&]*)/g},parser:{strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/}};


//get mv_embed location if it has not been set
if( !mv_embed_path ){
	var mv_embed_path = getMvEmbedPath();
}

//setup the skin path:
var mv_jquery_skin_path = mv_embed_path + 'jquery/jquery.ui/themes/' +mwConfig['jui_skin'] + '/';
var mv_skin_img_path = mv_embed_path + 'skins/' + mwConfig['skin_name'] + '/images/';
var mv_default_thumb_url = mv_skin_img_path + 'vid_default_thumb.jpg';


//init the global Msg if not already
if(!gMsg){var gMsg={};}

//laguage msg loader:
function loadGM( msgSet ){
	for(var i in msgSet){
		gMsg[ i ] = msgSet[i];
	}
}

//all default msg in [English] should be overwritten by the CMS language msg system.
loadGM({
	"mwe-loading_txt" : "loading <blink>...<\/blink>",
	"mwe-loading_title" : "Loading...",
	"mwe-size-gigabytes" : "$1 GB",
	"mwe-size-megabytes" : "$1 MB",
	"mwe-size-kilobytes" : "$1 K",
	"mwe-size-bytes" : "$1 B"
});

/**
 * AutoLoader paths (this should mirror the file: jsAutoloadLocalClasses.php )
 * any file _not_ listed here won't be auto-loadable
 * @path the path to the file (or set of files) with ending slash
 * @gClasses the set of classes
 * 		if an array $j.className become jquery.className.js
 * 		if an asssociative object then key => value paris are used
 */
if(typeof mvAutoLoadClasses == 'undefined')
	mvAutoLoadClasses = {};

//the script that loads the classet
function lcPaths( classSet){
	for(var i in classSet){
		mvAutoLoadClasses[i] = classSet[i];
	}
}

function mvGetClassPath(k){
	if( mvAutoLoadClasses[k] ){
		//js_log('got classpath:' + k +  ' : '+ mvClassPaths[k]);
		return mvAutoLoadClasses[k];
	}else{
		return js_error('could not find path for requested class ' + k );
	}
}
if(typeof mvCssPaths == 'undefined')
	mvCssPaths = {};

function lcCssPath(cssSet){
	for(var i in cssSet){
		mvCssPaths[i]= mv_embed_path + cssSet[i];
	}
}

/*
 * --  Load Class Paths --
 *
 * MUST BE VALID JSON (NOT JS)
 * is used by the scriptloader to autoLoad classes (so we only define this once for php & javascript)
 *
 * this is more verbose than earlier version that compressed paths
 * but its all good gziping help compress repetative path strings
 * grouped by
 *
 * right now php AutoLoader only reads this mv_embed.js file
 * in the future we could have multiple lcPath calls that php reads
 * (if our autoloading class list becomes too long) just have to add thouse
 * files to the jsAutoLoader file list.
 */
lcPaths({
	"mv_embed"			: "mv_embed.js",
	"window.jQuery"		: "jquery/jquery-1.3.2.js",
	"$j.fn.pngFix"		: "jquery/plugins/jquery.pngFix.js",
	"$j.fn.autocomplete": "jquery/plugins/jquery.autocomplete.js",
	"$j.fn.hoverIntent"	: "jquery/plugins/jquery.hoverIntent.js",
	"$j.fn.datePicker"	: "jquery/plugins/jquery.datePicker.js",
	"$j.ui"				: "jquery/jquery.ui/ui/ui.core.js",
	"$j.fn.ColorPicker"	: "libClipEdit/colorpicker/js/colorpicker.js",
	"$j.Jcrop"			: "libClipEdit/Jcrop/js/jquery.Jcrop.js",
	"$j.fn.simpleUploadForm": "libAddMedia/simpleUploadForm.js",

	"ctrlBuilder"	: "skins/ctrlBuilder.js",
	"kskin"			: "skins/kskin/kskin.js",
	"mvpcf"			: "skins/mvpcf/mvpcf.js",

	"$j.secureEvalJSON"	: "jquery/plugins/jquery.secureEvalJSON.js",
	"$j.cookie"			: "jquery/plugins/jquery.cookie.js",
	"$j.contextMenu"	: "jquery/plugins/jquery.contextMenu.js",

	"$j.effects.blind"		: "jquery/jquery.ui/ui/effects.blind.js",
	"$j.effects.drop"		: "jquery/jquery.ui/ui/effects.drop.js",
	"$j.effects.pulsate"	: "jquery/jquery.ui/ui/effects.pulsate.js",
	"$j.effects.transfer"	: "jquery/jquery.ui/ui/effects.transfer.js",
	"$j.ui.droppable"		: "jquery/jquery.ui/ui/ui.droppable.js",
	"$j.ui.slider"			: "jquery/jquery.ui/ui/ui.slider.js",
	"$j.effects.bounce"		: "jquery/jquery.ui/ui/effects.bounce.js",
	"$j.effects.explode"	: "jquery/jquery.ui/ui/effects.explode.js",
	"$j.effects.scale"		: "jquery/jquery.ui/ui/effects.scale.js",
	"$j.ui.datepicker"		: "jquery/jquery.ui/ui/ui.datepicker.js",
	"$j.ui.progressbar"		: "jquery/jquery.ui/ui/ui.progressbar.js",
	"$j.ui.sortable"		: "jquery/jquery.ui/ui/ui.sortable.js",
	"$j.effects.clip"		: "jquery/jquery.ui/ui/effects.clip.js",
	"$j.effects.fold"		: "jquery/jquery.ui/ui/effects.fold.js",
	"$j.effects.shake"		: "jquery/jquery.ui/ui/effects.shake.js",
	"$j.ui.dialog"			: "jquery/jquery.ui/ui/ui.dialog.js",
	"$j.ui.resizable"		: "jquery/jquery.ui/ui/ui.resizable.js",
	"$j.ui.tabs"			: "jquery/jquery.ui/ui/ui.tabs.js",
	"$j.effects.core"		: "jquery/jquery.ui/ui/effects.core.js",
	"$j.effects.highlight"	: "jquery/jquery.ui/ui/effects.highlight.js",
	"$j.effects.slide"		: "jquery/jquery.ui/ui/effects.slide.js",
	"$j.ui.accordion"		: "jquery/jquery.ui/ui/ui.accordion.js",
	"$j.ui.draggable"		: "jquery/jquery.ui/ui/ui.draggable.js",
	"$j.ui.selectable"		: "jquery/jquery.ui/ui/ui.selectable.js",

	"mvFirefogg"			: "libAddMedia/mvFirefogg.js",
	"mvAdvFirefogg"			: "libAddMedia/mvAdvFirefogg.js",
    "mvBaseUploadInterface"	: "libAddMedia/mvBaseUploadInterface.js",
	"remoteSearchDriver"	: "libAddMedia/remoteSearchDriver.js",
	"seqRemoteSearchDriver" : "libAddMedia/seqRemoteSearchDriver.js",

	"baseRemoteSearch"		: "libAddMedia/searchLibs/baseRemoteSearch.js",
	"mediaWikiSearch"		: "libAddMedia/searchLibs/mediaWikiSearch.js",
	"metavidSearch"			: "libAddMedia/searchLibs/metavidSearch.js",
	"archiveOrgSearch"		: "libAddMedia/searchLibs/archiveOrgSearch.js",
	"baseRemoteSearch"		: "libAddMedia/searchLibs/baseRemoteSearch.js",

	"mvClipEdit"			: "libClipEdit/mvClipEdit.js",

	"embedVideo"		: "libEmbedVideo/embedVideo.js",
	"flashEmbed"		: "libEmbedVideo/flashEmbed.js",
	"genericEmbed"		: "libEmbedVideo/genericEmbed.js",
	"htmlEmbed"			: "libEmbedVideo/htmlEmbed.js",
	"javaEmbed"			: "libEmbedVideo/javaEmbed.js",
	"nativeEmbed"		: "libEmbedVideo/nativeEmbed.js",
	"quicktimeEmbed"	: "libEmbedVideo/quicktimeEmbed.js",
	"vlcEmbed"			: "libEmbedVideo/vlcEmbed.js",

	"mvPlayList"		: "libSequencer/mvPlayList.js",
	"mvSequencer"		: "libSequencer/mvSequencer.js",
	"mvFirefoggRender"	: "libSequencer/mvFirefoggRender.js",
	"mvTimedEffectsEdit": "libSequencer/mvTimedEffectsEdit.js",

	"libTimedText"		: "libTimedText/mvTextInterface.js"

});

//depencency mapping for css files for self contained included plugins:
lcCssPath({
	'$j.Jcrop'			: 'libClipEdit/Jcrop/css/jquery.Jcrop.css',
	'$j.fn.ColorPicker'	: 'libClipEdit/colorpicker/css/colorpicker.css'
})

/**
 * Language Functions:
 *
 * These functions try to losely mirro the functionality of Language.php in mediaWiki
 */
function gM( key , args ) {
	var ms ='';
	if ( key in gMsg ) {
		ms = gMsg[ key ];
		if(typeof args == 'object' || typeof args == 'array'){
			 for(var v in args){
			 	//msg test replace arguments start at 1 insted of zero:
				 var rep = '\$'+ ( parseInt(v) + 1 );
				 ms = ms.replace( rep, args[v]);
			 }
		}else if(typeof args =='string' || typeof args =='number'){
			ms = ms.replace(/\$1/, args);
		 }
		 return ms;
	} else{
		//key is missing return indication:
		return '&lt;' + key + '&gt;';
	}
}
/*
 * msgSet is either a string corresponding to a single msg to load
 * or msgSet is an array with set of msg to load
 */
function gMsgLoadRemote(msgSet, callback){
	var ammessages = '';
	if(typeof msgSet == 'object' ){
		for(var i in msgSet){
			ammessages +=  msgSet[i] + '|';
		}
	}else if(typeof msgSet == 'string'){
		ammessages += msgSet;
	}
	if(ammessages == ''){
		js_log('gMsgLoadRemote::no msg set requested');
		return false;
	}
	do_api_req({
		'data':{
			'meta':'allmessages',
			'ammessages':ammessages
		}
	},function(data){
		if(data.query.allmessages){
			var msgs = data.query.allmessages;
			for(var i in msgs){
				var ld = {};
				ld[ msgs[i]['name'] ] =  msgs[i]['*'];
				loadGM( ld );
			}
		}
		//load the result into local msg var
		callback();
	});
}

/**
 * Format a size in bytes for output, using an appropriate
 * unit (B, KB, MB or GB) according to the magnitude in question
 *
 * @param size Size to format
 * @return string Plain text (not HTML)
 */
function formatSize( size ) {
	// For small sizes no decimal places necessary
	var round = 0;
	var msg = '';
	if( size > 1024 ) {
		size = size / 1024;
		if( size > 1024 ) {
			size = size / 1024;
			// For MB and bigger two decimal places are smarter
			round = 2;
			if( size > 1024 ) {
				size = size / 1024;
				msg = 'mwe-size-gigabytes';
			} else {
				msg = 'mwe-size-megabytes';
			}
		} else {
			msg = 'mwe-size-kilobytes';
		}
	} else {
		msg = 'mwe-size-bytes';
	}
	//javascript does not let you do precession points in rounding
	var p =  Math.pow(10,round);
	var size = Math.round( size * p  ) / p;
	//@@todo we need a formatNum and we need to request some special packaged info to deal with that case.
	return  gM( msg , size );
}

//gets the loading image:
function mv_get_loading_img( style , class_attr ){
	var style_txt = (style)?style:'';
	var class_attr = (class_attr)?'class="'+class_attr+'"':'class="mv_loading_img"';
	return '<div '+class_attr+' style="' + style +'"></div>';
}

function mv_set_loading(target, load_id){
	var id_attr = ( load_id )?' id="' + load_id + '" ':'';
	$j(target).append('<div '+id_attr+' style="position:absolute;top:0px;left:0px;height:100%;width:100%;'+
		'background-color:#FFF;">' +
			mv_get_loading_img('top:30px;left:30px') +
		'</div>');
}

/**
  * mvJsLoader class handles initialization and js file loads
  */
var mvJsLoader = {
	 libreq : {},
	 libs : {},
	 //base lib flags:
	 onReadyEvents:new Array(),
	 doneReadyEvents:false,
	 jQueryCheckFlag:false,
	 //to keep consistency across threads:
	 ptime:0,
	 ctime:0,
	 load_error:false, //load error flag (false by default)
	 load_time:0,
	 callbacks:new Array(),
	 cur_path: null,
	 missing_path : null,
	 doLoad:function(loadLibs, callback){
		 this.ctime++;
		 if( loadLibs && loadLibs.length!=0 ){ //setup this.libs:

			 //first check if we already have this lib loaded
			 var all_libs_loaded=true;
			 for(var i=0; i< loadLibs.length; i++){
				 //check if the lib is already loaded:
				if( ! this.checkObjPath( loadLibs[i] ) ){
					all_libs_loaded=false;
				}
			 }
			 if( all_libs_loaded ){
				js_log('all libs already loaded skipping... load req');
				callback();
				return ;
			}
			//do a check for any css we may need and get it:
			for(var i=0; i< loadLibs.length; i++){
				if( typeof mvCssPaths[ loadLibs[i] ] != 'undefined' ){
					loadExternalCss(  mvCssPaths[ loadLibs[i] ]);
				}
			}

			 //check if we should use the script loader to combine all the requests into one:
			 if( typeof mwSlScript != 'undefined' ){
				var class_set = '';
				  var last_class = '';
				  var coma = '';
				  for(var i=0; i< loadLibs.length; i++){
				  	  var curLib = loadLibs[i];
					  //only add if not included yet:
					  if( ! this.checkObjPath( curLib ) ){
						  class_set+=coma + curLib ;
						  last_class=curLib;
						  coma=',';
					  }
				  }
				  var puri = parseUri( getMvEmbedURL() );
				  if( (getMvEmbedURL().indexOf('://')!=-1) && puri.host != parseUri( document.URL).host){
					mwSlScript =  puri.protocol + '://' + puri.authority + mwSlScript;
				  }

				  var dbug_attr = (puri.queryKey['debug'])?'&debug=true':'';
				  this.libs[ last_class ] = mwSlScript + '?class=' + class_set +
									  '&urid=' + getMvUniqueReqId() + dbug_attr;

			 }else{
				//do many requests:
				for(var i=0; i< loadLibs.length; i++){
				     var curLib = loadLibs[i];
				     if(curLib){
					     var libLoc = mvGetClassPath(curLib);
						 // do a direct load of the file (pass along unique request id from request or mv_embed Version )
						 var qmark = (libLoc.indexOf('?')!==true)?'?':'&';
						 this.libs[curLib] =  mv_embed_path + libLoc + qmark + 'urid='+ getMvUniqueReqId();
				     }
				 }
			}
		}
		if( callback ){
			this.callbacks.push(callback);
		}
		if( this.checkLoading() ){
			 if( this.load_time++ > 2000){ //time out after ~80seconds
				 js_error( gM('mwe-error_load_lib') +  this.missing_path );
				 this.load_error = true;
			 }else{
				setTimeout( 'mvJsLoader.doLoad()', 20 );
			 }
		}else{
			 //js_log('checkLoading passed run callbacks');
			 //only do callback if we are in the same instance (weird concurency issue)
			 var cb_count=0;
			 for(var i=0; i < this.callbacks.length; i++)
				 cb_count++;
			 //js_log('REST LIBS: loading is: '+ loading + ' run callbacks: '+cb_count +' p:'+ this.ptime +' c:'+ this.ctime);
			 //reset the libs
			 this.libs={};
			 //js_log('done loading do call: ' + this.callbacks[0] );
			 while( this.callbacks.length !=0 ){
				 if( this.ptime== ( this.ctime-1) ){ //enforce thread consistency
					 this.callbacks.pop()();
					 //func = this.callbacks.pop();
					 //js_log(' run: '+this.ctime+ ' p: ' + this.ptime + ' ' +loading+ ' :'+ func);
					//func();
				 }else{
					 //re-issue doLoad ( ptime will be set to ctime so we should catch up)
					 setTimeout( 'mvJsLoader.doLoad()', 25 );
					 break;
				 }
			 }
		 }
		 this.ptime=this.ctime;
	 },
	 doLoadFullPaths:function(loadObj, callback){

	 },
	 doLoadDepMode:function(loadChain, callback){
	 	//firefox executes js ~in-order of it being included~ so just directly issue request:
	 	if( $j.browser.firefox ){
	 		var loadSet = [];
	 		for(var i=0; i< loadChain.length;i++){
	 			for(var j=0;j<loadChain[i].length;j++){
	 				loadSet.push(loadChain[i][j]);
	 			}
	 		}
	 		mvJsLoader.doLoad(loadSet, callback);
	 	}else{
	 		//safari and IE tend to execute out of order so load with dependenciy checks
		 	mvJsLoader.doLoad(loadChain.shift(),function(){
		 		if(loadChain.length!=0){
		 			mvJsLoader.doLoadDepMode(loadChain, callback);
		 		}else{
		 			callback();
		 		}
		 	});
	 	}		 },
	 checkLoading:function(){
		 var loading=0;
		 var i=null;
		 for(var i in this.libs){ //for in loop oky on object
			 if( !this.checkObjPath( i ) ){
				 if(!this.libreq[i]){
				 	loadExternalJs( this.libs[i] );
				 }

				 this.libreq[i]=1;
				 //js_log("has not yet loaded: " + i);
				 loading=1;
			 }
		 }
		 return loading;
	},
	checkObjPath:function( libVar ){
		if(!libVar)
			return false;
		var objPath = libVar.split('.')
		var cur_path ='';
		for(var p=0; p < objPath.length; p++){
			 cur_path = (cur_path=='')?cur_path+objPath[p]:cur_path+'.'+objPath[p];
			 eval( 'var ptest = typeof ( '+ cur_path + ' ); ');
			 if( ptest == 'undefined'){
				  this.missing_path = cur_path;
				 return false;
			 }
		}
		this.cur_path = cur_path;
		return true;
	},
	/**
	 * checks for jQuery and adds the $j noConflict var
	 */
	jQueryCheck:function(callback){
		//skip stuff if $j is already loaded:
		if(_global['$j'] && callback)
			callback();
		var _this = this;
		//load jquery
		_this.doLoad([
			 'window.jQuery'
		],function(){
			_global['$j'] = jQuery.noConflict();
			//set up ajax to not send dynamic urls for loading scripts (we control that with the scriptLoader)
			$j.ajaxSetup({
				  cache: true
			});
			js_log('jquery loaded');
			//setup mvEmbed jquery bindigns:
			mv_jqueryBindings();
			//run the callback
			if(callback){
				callback();
			}
		});
	},
	embedVideoCheck:function( callback ){
		var _this = this;
		js_log('embedVideoCheck:');
		//set videonojs to loading
		//issue a style sheet request get both mv_embed and jquery styles:
		loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
		loadExternalCss( mv_embed_path  + 'skins/'+mwConfig['skin_name']+'/styles.css');

		//make sure we have jQuery
		_this.jQueryCheck(function(){
			$j('.videonojs').html( gM('mwe-loading_txt') );
			var depReq = [
				[
					'$j.ui',
					'embedVideo',
					'ctrlBuilder',
					'$j.cookie'
				],
				[
					'$j.ui.slider'
				]
			];
			//add png fix if needed:
			if($j.browser.msie || $j.browser.version < 7)
				depReq[0].push( '$j.fn.pngFix' );

			_this.doLoadDepMode(depReq,function(){
				embedTypes.init();
				callback();
				$j('.videonojs').remove();
			});
		});
	},
	addLoadEvent:function(fn){
		 this.onReadyEvents.push(fn);
	},
	//checks the jQuery flag (this way when remote embeding we don't load jQuery
	// unless js2AddOnloadHook was used or there is video on the page
	runQuededFunctions:function(){
		var _this = this;
		this.doneReadyEvents=true;
		if(this.jQueryCheckFlag){
			this.jQueryCheck(function(){
				_this.runReadyEvents();
			});
		}else{
			this.runReadyEvents();
		}
	},
	runReadyEvents:function(){
		js_log("runReadyEvents");
		while( this.onReadyEvents.length ){
			this.onReadyEvents.shift()();
		}
	}

}
//load an external JS (similar to jquery .require plugin)
//but checks for object availability rather than load state

/*********** INITIALIZATION CODE *************
 * this will get called when DOM is ready
 *********************************************/
/* jQuery .ready does not work when jQuery is loaded dynamically
 * for an example of the problem see:1.1.3 working:http://pastie.caboo.se/92588
 * and >= 1.1.4 not working: http://pastie.caboo.se/92595
 * $j(document).ready( function(){ */
function mwdomReady(force){
	js_log('f:mwdomReady:');
	if( !force && mv_init_done  ){
		js_log("mv_init_done already done do nothing...");
		return false;
	}
	mv_init_done=true;
	//handle the execution of Queded function with jQuery "ready"

	//check if this page does have video or playlist
	var e = [
		document.getElementsByTagName("video"),
	 	document.getElementsByTagName("audio"),
	 	document.getElementsByTagName("playlist")
	];
	if(e[0].length!=0 || e[1].length!=0 || e[2].length!=0){
		js_log('we have items to rewrite');
		setSwapableToLoading(e);
		//load libs and proccess:
		mvJsLoader.embedVideoCheck(function(){
			//run any queded global events:
			mv_video_embed( function(){
				mvJsLoader.runQuededFunctions();
			});
		});
	}else{
		//if we already have jQuery make sure its loaded into its proper context $j
		//run any queded global events:
		mvJsLoader.runQuededFunctions();
	}
}
//a quick function that sets the intial text of swapable elements to "loading"
// (jquery might not be ready)
//(does not destroy inner elements)
function setSwapableToLoading(e){
	//for(var i =0)
	//for(var j = 0; i < j.length; j++){
	//}
}
//js2AddOnloadHook: ensure jQuery and the DOM are ready:
function js2AddOnloadHook( func ) {
	//make sure the skin/style sheets are avaliable always:
	loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
	loadExternalCss( mv_embed_path  + 'skins/'+mwConfig['skin_name']+'/styles.css');

	//if we have already run the dom ready just run the function directly:
	if( mvJsLoader.doneReadyEvents ){
		//make sure jQuery is there:
		mvJsLoader.jQueryCheck(function(){
			func();
		});
	}else{
		//if using js2AddOnloadHook we need to get jQuery into place (if its not already included)
		mvJsLoader.jQueryCheckFlag = true;
		mvJsLoader.addLoadEvent( func );
	};
}
//depreciated mwAddOnloadHook in favor of js2 naming (for clear seperation of js2 code from old mw code
var mwAddOnloadHook = js2AddOnloadHook;
/*
 * this function allows for targeted rewriting
 */
function rewrite_by_id( vid_id, ready_callback ){
	js_log('f:rewrite_by_id: ' + vid_id);
	//force a recheck of the dom for playlist or video element:
	mvJsLoader.embedVideoCheck(function(){
		 mv_video_embed(ready_callback, vid_id );
	});
}
//depricated in favor of updates to oggHanlder
function rewrite_for_oggHanlder( vidIdList ){
	for(var i = 0; i < vidIdList.length ; i++){
		var vidId = vidIdList[i];
		js_log('looking at vid: ' + i +' ' + vidId);
		//grab the thumbnail and src video
		var pimg = $j('#'+vidId + ' img');
		var poster_attr = 'poster = "' + pimg.attr('src') + '" ';
		var pwidth = pimg.attr('width');
		var pheight = pimg.attr('height');

		var type_attr = '';
		//check for audio
		if( pwidth=='22' && pheight=='22'){
			pwidth='400';
			pheight='300';
			type_attr = 'type="audio/ogg"';
			poster_attr = '';
		}

		//parsed values:
		var src = '';
		var duration = '';

		var re = new RegExp( /videoUrl(&quot;:?\s*)*([^&]*)/ );
		src  = re.exec( $j('#'+vidId).html() )[2];

		var re = new RegExp( /length(&quot;:?\s*)*([^&]*)/ );
		duration = re.exec( $j('#'+vidId).html() )[2];

		var re = new RegExp( /offset(&quot;:?\s*)*([^&]*)/ );
		offset = re.exec( $j('#'+vidId).html() )[2];
		var offset_attr = (offset)? 'startOffset="'+ offset + '"': '';

		if( src ){
			//replace the top div with mv_embed based player:
			var vid_html = '<video id="vid_' + i +'" '+
					 'src="' + src + '" ' +
					 poster_attr + ' ' +
					 type_attr + ' ' +
					 offset_attr + ' ' +
					 'duration="' + duration + '" ' +
					 'style="width:' + pwidth + 'px;height:' +
						 pheight + 'px;"></video>';
			//js_log("video html: " + vid_html);
			 $j('#'+vidId).html( vid_html );
		}

		//rewrite that video id:
		rewrite_by_id('vid_' + i);
	}
}


/*********** INITIALIZATION CODE *************
 * set DOM ready callback to init_mv_embed
 *********************************************/
// for Mozilla browsers
if (document.addEventListener ) {
	document.addEventListener("DOMContentLoaded", function(){mwdomReady()}, false);
}else{
	//backup "onload" method in case on DOMContentLoaded does not exist
	window.onload = function(){ mwdomReady() };
}
/*
 * should depreciate and use jquery.ui.dialog instead
 */
function mv_write_modal(content, speed){
	$j('#modalbox,#mv_overlay').remove();
	$j('body').append('<div id="modalbox" style="background:#DDD;border:3px solid #666666;font-size:115%;'+
				'top:30px;left:20px;right:20px;bottom:30px;position:fixed;z-index:100;">'+
				content +
			'</div>'+
			'<div id="mv_overlay" style="background:#000;cursor:wait;height:100%;left:0;position:fixed;'+
				'top:0;width:100%;z-index:5;filter:alpha(opacity=60);-moz-opacity: 0.6;'+
				'opacity: 0.6;"/>');
	$j('#modalbox,#mv_overlay').show( speed );
}
function mv_remove_modal(speed){
	$j('#modalbox,#mv_overlay').remove( speed);
}

/*
 * stores all the mwEmbed jQuery specific bindings
 * (setup after jQuery is avaliable)
 * lets you call rewrites in a jquery "way"
 *
 * @@ eventually we should refactor mwCode over to jQuery style plugins
 *	  and mv_embed.js will just hanndle dependency mapping and loading.
 *
 */
function mv_jqueryBindings(){
	js_log('mv_jqueryBindings');
	(function($) {
		$.fn.addMediaWiz = function( iObj, callback ){
			//first set the cursor for the button to "loading"
			$j(this.selector).css('cursor','wait').attr('title', gM('mwe-loading_title'));

			iObj['target_invocation'] = this.selector;

			//load the mv_embed_base skin:
			loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
			loadExternalCss( mv_embed_path  + 'skins/'+mwConfig['skin_name']+'/styles.css' );
			//load all the req libs:
			mvJsLoader.jQueryCheck(function(){
				//load with staged dependeinces (for ie and safari that don't execute in order)
				mvJsLoader.doLoadDepMode([
					[	'remoteSearchDriver',
						'$j.cookie',
						'$j.ui'
					],[
						'$j.ui.resizable',
						'$j.ui.draggable',
						'$j.ui.dialog',
						'$j.ui.tabs',
						'$j.ui.sortable'
					]
				], function(){
					iObj['instance_name']= 'rsdMVRS';
					_global['rsdMVRS'] = new remoteSearchDriver( iObj );
					if( callback ){
					   callback( _global['rsdMVRS'] );
					}
				});
			});
		}
		$.fn.sequencer = function( iObj, callback){
			//debugger;
			iObj['target_sequence_container'] = this.selector;
        	//issue a request to get the css file (if not already included):
        	loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css');
        	loadExternalCss( mv_embed_path+'skins/'+mwConfig['skin_name']+'/mv_sequence.css');
        	//make sure we have the required mv_embed libs (they are not loaded when no video element is on the page)
        	mvJsLoader.embedVideoCheck(function(){
        		//load playlist object and then jquery ui stuff:
        		mvJsLoader.doLoadDepMode([
	        		[
        				'mvPlayList',
        				'$j.ui',
        				'$j.contextMenu',
        				'$j.secureEvalJSON',
        				'mvSequencer'
	        		],
	        		[
	        			'$j.ui.accordion',
        				'$j.ui.dialog',
        				'$j.ui.droppable',
        				'$j.ui.draggable',
        				'$j.ui.progressbar',
        				'$j.ui.sortable',
        				'$j.ui.resizable',
        				'$j.ui.slider',
        				'$j.ui.tabs'
        			]
        		], function(){
    				js_log('calling new mvSequencer');
    				//init the sequence object (it will take over from there) no more than one mvSeq obj for now:
    				if(!_global['mvSeq']){
    					_global['mvSeq'] = new mvSequencer(iObj);
    				}else{
    					js_log('mvSeq already init');
    				}
        		});
        	});
		}
		/*
		 * the firefogg jquery function:
		 * @@note this firefogg envocation could be made to work more like real jquery plugins
		 */
		$.fn.firefogg = function( iObj, callback ) {
			if(!iObj)
				iObj={};
			//add base theme css:
			loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css');
			loadExternalCss( mv_embed_path  + 'skins/'+mwConfig['skin_name']+'/styles.css' );

			//check if we already have firefogg loaded (the call just updates properties for that element)
			var sElm = $j(this.selector).get(0);
			if(sElm['firefogg']){
				if(sElm['firefogg']=='loading'){
					js_log("Error: called firefogg operations on Firefogg selector that is not done loading");
					return false;
				}
				//update properties:
				for(var i in iObj){
					js_log("firefogg::updated: "+ i + ' to '+ iObj[i]);
					sElm['firefogg'][i] = iObj[i];
				}
				return sElm['firefogg'];
			}else{
				//avoid concurency
				sElm['firefogg'] = 'loading';
			}
			//add the selector:
			iObj['selector'] = this.selector;

			var loadSet = [
				[
					'mvBaseUploadInterface',
					'mvFirefogg',
					'$j.ui'
				],
				[
					'$j.ui.progressbar',
					'$j.ui.dialog'
				]
			];
			if( iObj.encoder_interface ){
				loadSet.push([
					'mvAdvFirefogg',
					'$j.cookie',
					'$j.ui.accordion',
					'$j.ui.slider',
					'$j.ui.datepicker'
				]);
			}
			//make sure we have everything loaded that we need:
			mvJsLoader.doLoadDepMode( loadSet, function(){
					js_log('firefogg libs loaded. target select:' + iObj.selector);
					//select interface provicer based on if we want to include the encoder interface or not:
					if(iObj.encoder_interface){
						var myFogg = new mvAdvFirefogg( iObj );
					}else{
						var myFogg = new mvFirefogg( iObj );
					}
					if(myFogg){
						myFogg.doRewrite( callback );
						var selectorElement = $j( iObj.selector ).get(0);
						selectorElement['firefogg']=myFogg;
					}
			});
		}
		//takes a input player as the selector and exposes basic rendering controls
		$.fn.firefoggRender = function( iObj, callback ){
			//check if we already have render loaded then just pass on updates/actions
			var sElm = $j(this.selector).get(0);
			if(sElm['fogg_render']){
				if(sElm['fogg_render']=='loading'){
					js_log("Error: called firefoggRender while loading");
					return false;
				}
				//call or update the property:
			}
			sElm['fogg_render']='loading';
			//add the selector:
			iObj['player_target'] = this.selector;
			mvJsLoader.doLoad([
				'mvFirefogg',
				'mvFirefoggRender'
			],function(){
				sElm['fogg_render']= new mvFirefoggRender( iObj );
				if( callback && typeof callback == 'function' )
					callback( sElm['fogg_render'] );
			});
		}

		$.fn.baseUploadInterface = function(iObj){
			mvJsLoader.doLoadDepMode([
				[
					'mvBaseUploadInterface',
					'$j.ui',
				],
				[
				  '$j.ui.progressbar',
				  '$j.ui.dialog'
				]
			],function(){
				myUp = new mvBaseUploadInterface( iObj );
				myUp.setupForm();
			});
		}

		//shortcut to a themed button:
		$.btnHtml = function(msg, className, iconId, opt){
		   if(!opt)
		      opt = {};
		   var href = (opt.href)?opt.href:'#';
		   var target_attr = (opt.target)?' target="' + opt.target + '" ':'';
		   var style_attr = (opt.style)?' style="'+opt.style +'" ':'';
		   return '<a href="' + href + '" ' + target_attr + style_attr +' class="ui-state-default ui-corner-all ui-icon_link ' +
				   className + '"><span class="ui-icon ui-icon-' + iconId + '" />' +
				   msg + '</a>';
		}
		//shortcut to bind hover state:
		$.fn.btnBind = function(){
			$j(this).hover(
				function(){
					$j(this).addClass('ui-state-hover');
				},
				function(){
					$j(this).removeClass('ui-state-hover');
				}
			)
			return this;
		}

	})(jQuery);
}
/*
* utility functions:
*/
//simple url re-writer for rewriting urls (could probably be refactored into an inline regular expresion)
function getURLParamReplace( url, opt ){
	var pSrc = parseUri( url );
	if(pSrc.protocol != '' ){
		var new_url = pSrc.protocol +'://'+ pSrc.authority + pSrc.path +'?';
	}else{
		var new_url = pSrc.path +'?';
	}
	var amp = '';
	for(var key in pSrc.queryKey){
		var val = pSrc.queryKey[ key ];
		//do override if requested
		if( opt[ key ] )
			val = opt[ key ];
		new_url+= amp + key + '=' + val;
		amp = '&';
	};
	//add any vars that did were not originally there:
	for(var i in opt){
		if(!pSrc.queryKey[i]){
		  new_url+=amp + i + '=' + opt[i];
		  amp = '&';
		}
	}
	return new_url;
}
/**
 * seconds2npt given a float seconds returns npt format response:
 * @param float seconds
 * @param boolean if we should show ms or not.
 */
function seconds2npt(sec, show_ms){
	if( isNaN( sec ) ){
		//js_log("warning: trying to get npt time on NaN:" + sec);
		return '0:0:0';
	}
	var hours = Math.floor(sec/ 3600);
	var minutes = Math.floor((sec/60) % 60);
	var seconds = sec % 60;
	//round the second amount requested significant digits
	if(show_ms){
		seconds = Math.round( seconds * 1000 ) / 1000;
	}else{
		seconds = Math.round( seconds );
	}
	if(seconds <10 )
		seconds = '0'+	seconds;
	if(minutes < 10 )
		minutes = '0' + minutes;

	return hours+":"+minutes+":"+seconds;
}
/*
 * takes hh:mm:ss,ms or  hh:mm:ss.ms input returns number of seconds
 */
function npt2seconds( npt_str ){
	if(!npt_str){
		//js_log('npt2seconds:not valid ntp:'+ntp);
		return false;
	}
	//strip npt: time definition if present
	npt_str = npt_str.replace('npt:', '');

	times = npt_str.split(':');
	if(times.length!=3){
		js_log('error: npt2seconds on ' + npt_str);
		return false;
	}
	//sometimes the comma is used inplace of pereid for ms
	times[2] = times[2].replace(/,\s?/,'.');
	//return seconds float (ie take seconds float value if present):
	return parseInt(times[0]*3600)+parseInt(times[1]*60)+parseFloat(times[2]);
}
/*
 * simple helper to grab a edit token
 *
 * @param title the wiki page title you want to edit )
 * @param api_url 'optional' the target api url
 * @param callback the callback function to pass the token or "false" to
 */
function get_mw_token( title, api_url, callback){
	js_log(':get_mw_token:');
	if(!title && wgUserName){
		title = 'User:' + wgUserName;
	}
	var reqObj = {
			'action':'query',
			'prop':'info',
			'intoken':'edit',
			'titles':title
		};
		do_api_req( {
			'data': reqObj,
			'url' : api_url
			},function(data){
				for(var i in data.query.pages){
					if(data.query.pages[i]['edittoken']){
						if(typeof callback == 'function')
							callback ( data.query.pages[i]['edittoken'] );
					}
				}
				//no token found:
				return false;
			}
		);
}
//does a remote or local api request based on request url
//@param options: url, data, cbParam, callback
function do_api_req( options, callback ){
	if(typeof options.data != 'object'){
		return js_error('Error: request paramaters must be an object');;
	}
	//gennerate the url if its missing:
	if( typeof options.url == 'undefined' ||  options.url === false){
		if(!wgServer || ! wgScriptPath){
			return js_error('Error: no api url for api request');;
		}
		//update to api.php (if index.php was in the wgScript path):
		 options.url =  mwGetLocalApiUrl();
	}
	if( typeof options.data == 'undefined' )
		options.data = {};

	//force format to json (if not already set)
	options.data['format'] = 'json';

	//if action not set assume query
	if(!options.data['action'])
		options.data['action']='query';

	js_log('do api req: ' + options.url +'?' +  jQuery.param(options.data) );
	//build request string:
	if( parseUri( document.URL ).host == parseUri( options.url ).host ){
		//local request do api request directly
		$j.ajax({
			type: "POST",
			url: options.url,
			data: options.data,
			dataType:'json', //api requests _should_ always return JSON data:
			async: false,
			success:function(data){
				callback(  data );
			},
			error:function(e){
				js_error( ' error' + e +' in getting: ' + options.url);
			}
		});
	}else{
		//set the callback param if not already set:
		if( typeof options.jsonCB == 'undefined')
			options.jsonCB = 'callback';

		var req_url = options.url;
		var paramAnd = (req_url.indexOf('?')==-1)?'?':'&';
		//put all the values into the GET req:
		for(var i in options.data){
			req_url += paramAnd + encodeURIComponent( i ) + '=' + encodeURIComponent( options.data[i] );
			paramAnd ='&';
		}
		var fname = 'mycpfn_' + ( global_cb_count++ );
		_global[ fname ]  =  callback;
		req_url += '&' + options.jsonCB + '=' + fname;
		loadExternalJs( req_url );
	}
}
function mwGetLocalApiUrl(url){
	if (wgServer && wgScriptPath){
		return wgServer + wgScriptPath + '/api.php';
	}
	return false;
}
//grab wiki form error for wiki html page proccessing (should be depricated)
function grabWikiFormError ( result_page ){
		var res = {};
		sp = result_page.indexOf('<span class="error">');
		if(sp!=-1){
			se = result_page.indexOf('</span>', sp);
			res.error_txt = result_page.substr(sp, (sp-se)) + '</span>';
		}else{
			//look for warning:
			sp = result_page.indexOf('<ul class="warning">')
			if(sp != -1){
				se = result_page.indexOf('</ul>', sp);
				res.error_txt = result_page.substr(sp, (se-sp)) + '</ul>';
				//try and add the ignore form item:
				sfp = result_page.indexOf('<form method="post"');
				if(sfp!=-1){
					sfe = result_page.indexOf('</form>', sfp);
					res.form_txt = result_page.substr(sfp, ( sfe - sfp )) + '</form>';
				}
			}else{
				//one more error type check:
				sp = result_page.indexOf('class="mw-warning-with-logexcerpt">')
				if(sp!=-1){
					se = result_page.indexOf('</div>', sp);
					res.error_txt = result_page.substr(sp, ( se - sp )) + '</div>';
				}
			}
		}
		return res;
}
//do a "normal" request
function do_request(req_url, callback){
	js_log('do_request::req_url:' + req_url + ' != ' +  parseUri( req_url).host);
	//if we are doing a request to the same domain or relative link do a normal GET:
	if( parseUri(document.URL).host == parseUri(req_url).host ||
		req_url.indexOf('://') == -1 ){ //relative url
		//do a direct request:
		$j.ajax({
			type: "GET",
			url:req_url,
			   async: false,
			success:function(data){
				callback( data );
			}
		});
	}else{
		//get data via DOM injection with callback
		global_req_cb.push(callback);
		//prepend json_ to feed_format if not already requesting json format
		if( req_url.indexOf("feed_format=")!=-1 &&  req_url.indexOf("feed_format=json")==-1)
			req_url = req_url.replace(/feed_format=/, 'feed_format=json_');
		loadExternalJs( req_url + '&cb=mv_jsdata_cb&cb_inx=' + (global_req_cb.length-1));
	}
}

function mv_jsdata_cb(response){
	js_log('f:mv_jsdata_cb:'+ response['cb_inx']);
	//run the callback from the global req cb object:
	if( !global_req_cb[response['cb_inx']] ){
		js_log('missing req cb index');
		return false;
	}
	if( !response['pay_load'] ){
		js_log("missing pay load");
		return false;
	}
	//switch on content type:
	switch(response['content-type']){
		case 'text/plain':
		break;
		case 'text/xml':
			if(typeof response['pay_load'] == 'string'){
				//js_log('load string:'+"\n"+ response['pay_load']);
				//debugger;
				//attempt to parse as xml for IE
				if( $j.browser.msie ){
					var xmldata=new ActiveXObject("Microsoft.XMLDOM");
					xmldata.async="false";
					xmldata.loadXML(response['pay_load']);
				}else{ //for others (firefox, safari etc)
					try{
						var xmldata = (new DOMParser()).parseFromString(response['pay_load'], "text/xml");
					}catch(e) {
							  js_log('XML parse ERROR: ' + e.message);
					  }
				}
				//@@todo hanndle xml parser errors
				if(xmldata)response['pay_load']=xmldata;
			}
		break
		default:
			js_log('bad response type' + response['content-type']);
			return false;
		break;
	}
	global_req_cb[response['cb_inx']]( response['pay_load'] );
}
//load external js via dom injection
function loadExternalJs( url, callback ){
	  js_log('load js: '+ url);
	//if(window['$j']) //use jquery call:
	   /*$j.ajax({
			type: "GET",
			url: url,
			dataType: 'script',
			cache: true
		});*/
  //  else{
		var e = document.createElement("script");
		e.setAttribute('src', url);
		e.setAttribute('type', "text/javascript");
		/*if(callback)
			e.onload = callback;
		*/
		//e.setAttribute('defer', true);
		document.getElementsByTagName("head")[0].appendChild(e);
   // }
}
function styleSheetPresent(url){
	style_elements = document.getElementsByTagName('link');
	if( style_elements.length > 0) {
		for(i = 0; i < style_elements.length; i++) {
			if(style_elements[i].href == url)
				return true;
		}
	}
	return false;
}
function loadExternalCss(url){
	//if could have script loader group thes css request
	//but debatable it may hurt more than it helps with caching and all
	if(typeof url =='object'){
		for(var i in url){
			loadExternalCss ( url[i] );
		}
		return ;
	}

	if( url.indexOf('?') == -1 ){
		url+='?'+getMvUniqueReqId();
	}
	if(!styleSheetPresent(url) ){
	   js_log('load css: ' + url);
	   var e = document.createElement("link");
	   e.href = url;
	   e.type = "text/css";
	   e.rel = 'stylesheet';
	   document.getElementsByTagName("head")[0].appendChild(e);
	}
}
function getMvEmbedURL(){
	if( _global['mv_embed_url'] )
		return _global['mv_embed_url'];
	var js_elements = document.getElementsByTagName("script");
	for(var i=0; i < js_elements.length; i++){
		//check for normal mv_embed.js and or script loader
		var src = js_elements[i].getAttribute("src");
		if( src ){
			if( src.indexOf('mv_embed.js') !=-1 || (
				( src.indexOf('mwScriptLoader.php') != -1 || src.indexOf('jsScriptLoader.php') != -1 )
					&& src.indexOf('mv_embed') != -1) ){ //(check for class=mv_embed script_loader call)
				_global['mv_embed_url'] = src;
				return  src;
			}
		}
	}
	js_error('Error: getMvEmbedURL failed to get Embed Path');
	return false;
}
//gets a unique request id to ensure fresh javascript
function getMvUniqueReqId(){
	if( _global['urid'] )
		return _global['urid'];
	var mv_embed_url = getMvEmbedURL();
	//if we have a uri retun that:
	var urid = parseUri( mv_embed_url).queryKey['urid']
	if( urid ){
		_global['urid']	= urid;
		return urid;
	}
	//if in debug mode get a fresh unique request key:
	if(  parseUri( mv_embed_url ).queryKey['debug'] == 'true'){
		var d = new Date();
		var urid = d.getTime();
		_global['urid']	= urid;
		return urid;
	}
	//else just return the mv_embed version;
	return MV_EMBED_VERSION;
}
/*
 * sets the global mv_embed path based on the scripts location
 */
function getMvEmbedPath(){
	if( _global['mv_embed_path'])
		return _global['mv_embed_path'];
	var mv_embed_url = getMvEmbedURL();
	if( mv_embed_url.indexOf('mv_embed.js') !== -1 ){
		mv_embed_path = mv_embed_url.substr(0, mv_embed_url.indexOf('mv_embed.js'));
	}else if(mv_embed_url.indexOf('mwScriptLoader.php')!==-1){
		//script load is in the root of mediaWiki so include the default mv_embed extention path (if using the script loader)
		mv_embed_path = mv_embed_url.substr(0, mv_embed_url.indexOf('mwScriptLoader.php'))  + mediaWiki_mvEmbed_path ;
	}else{
		mv_embed_path = mv_embed_url.substr(0, mv_embed_url.indexOf('jsScriptLoader.php'));
	}
	//absolute the url (if relative) (if we don't have mv_embed path)
	if( mv_embed_path.indexOf('://') == -1){
		var pURL = parseUri( document.URL );
		if(mv_embed_path.charAt(0)=='/'){
			mv_embed_path = pURL.protocol + '://' + pURL.authority + mv_embed_path;
		}else{
			//relative:
			if(mv_embed_path==''){
				mv_embed_path = pURL.protocol + '://' + pURL.authority + pURL.directory + mv_embed_path;
			}
		}
	}
	_global['mv_embed_path'] = mv_embed_path;
	return mv_embed_path;
}

if (typeof DOMParser == "undefined") {
   DOMParser = function () {}
   DOMParser.prototype.parseFromString = function (str, contentType) {
	  if (typeof ActiveXObject != "undefined") {
		 var d = new ActiveXObject("MSXML.DomDocument");
		 d.loadXML(str);
		 return d;
	  } else if (typeof XMLHttpRequest != "undefined") {
		 var req = new XMLHttpRequest;
		 req.open("GET", "data:" + (contentType || "application/xml") +
						 ";charset=utf-8," + encodeURIComponent(str), false);
		 if (req.overrideMimeType) {
			req.overrideMimeType(contentType);
		 }
		 req.send(null);
		 return req.responseXML;
	  }
   }
}
/*
* utility functions:
*/
function js_log(string){
  if( window.console ){
	   window.console.log(string);
  }else{
	 /*
	  * IE and non-firebug debug:
	  */
	 /*var log_elm = document.getElementById('mv_js_log');
	 if(!log_elm){
		 document.getElementsByTagName("body")[0].innerHTML = document.getElementsByTagName("body")[0].innerHTML +
					 '<div style="position:absolute;z-index:500;top:0px;left:0px;right:0px;height:10px;">'+
						 '<textarea id="mv_js_log" cols="120" rows="5"></textarea>'+
					 '</div>';

		 var log_elm = document.getElementById('mv_js_log');
	 }
	 if(log_elm){
		 log_elm.value+=string+"\n";
	 }*/
  }
  return false;
}

function checkDefaultMwConfig(){
	for(var i in defaultMwConfig){
	     if(typeof(mwConfig[i])=='undefined'){
	          mwConfig[i] =defaultMwConfig[i];
	     }
	  }
	}

function js_error(string){
	alert(string);
	return false;
}
