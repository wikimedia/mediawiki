/*
 * ~mv_embed ~
 * For details see: http://metavid.org/wiki/index.php/Mv_embed
 *
 * All Metavid Wiki code is released under the GPL2.
 * For more information visit http://metavid.org/wiki/Code
 *
 * @url http://metavid.org
 *
 * parseUri:
 * http://stevenlevithan.com/demo/parseuri/js/
 *
 * Config values: you can manually set the location of the mv_embed folder here
 * (in cases where media will be hosted in a different place than the embedding page)
 *
 */

/**
 * AutoLoader paths 
 * @path The path to the file (or set of files) with ending slash
 * @gClasses The set of classes
 * 		if it's an array, $j.className becomes jquery.className.js
 * 		if it's an associative object then key => value pairs are used
 */
if( typeof mvAutoLoadClasses == 'undefined' )
	mvAutoLoadClasses = {};

// The script that loads the class set
function lcPaths( classSet ){
	for( var i in classSet ) {
		mvAutoLoadClasses[i] = classSet[i];
	}
}

function mvGetClassPath(k){
	if( mvAutoLoadClasses[k] ) {
		//js_log('got class path:' + k +  ' : '+ mvClassPaths[k]);
		return mvAutoLoadClasses[k];
	} else {
		js_log('Error:: Could not find path for requested class ' + k );
		return false;
	}
}
if( typeof mvCssPaths == 'undefined' )
	mvCssPaths = {};

function lcCssPath( cssSet ) {
	for( var i in cssSet ) {
		mvCssPaths[i] = mv_embed_path + cssSet[i];
	}
}

/*
 * --  Load Class Paths --
 *
 * MUST BE VALID JSON (NOT JS)
 * This is used by the script loader to auto-load classes (so we only define
 * this once for PHP & JavaScript)
 *
 * Right now the PHP AutoLoader only reads this mv_embed.js file.
 * In the future we could have multiple lcPath calls that PHP reads
 * (if our autoloading class list becomes too long) 
 * or 
 * we could support direct file requests to the script loader instead 
 * of shared class names read from a central location.
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
	"$j.fn.simpleUploadForm" : "libAddMedia/simpleUploadForm.js",
	
	"$mw.proxy"		: "libMwApi/mw.proxy.js",
	
	"$mw.testLang"	:  "tests/testLang.js",
	
	"ctrlBuilder"	: "skins/ctrlBuilder.js",
	"kskinConfig"	: "skins/kskin/kskin.js",
	"mvpcfConfig"	: "skins/mvpcf/mvpcf.js",

	"JSON"				: "libMwApi/json2.js",
	"$j.cookie"			: "jquery/plugins/jquery.cookie.js",
	"$j.contextMenu"	: "jquery/plugins/jquery.contextMenu.js",
	"$j.fn.suggestions"	: "jquery/plugins/jquery.suggestions.js",
	"$j.fn.textSelection"	: "jquery/plugins/jquery.textSelection.js",

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

	"$j.fn.dragDropFile"		: "libAddMedia/dragDropFile.js",
	"mvFirefogg"			: "libAddMedia/mvFirefogg.js",
	"mvAdvFirefogg"			: "libAddMedia/mvAdvFirefogg.js",
	"mvBaseUploadInterface"	: "libAddMedia/mvBaseUploadInterface.js",
	"remoteSearchDriver"	: "libAddMedia/remoteSearchDriver.js",
	"seqRemoteSearchDriver" : "libSequencer/seqRemoteSearchDriver.js",

	"baseRemoteSearch"		: "libAddMedia/searchLibs/baseRemoteSearch.js",
	"mediaWikiSearch"		: "libAddMedia/searchLibs/mediaWikiSearch.js",
	"metavidSearch"			: "libAddMedia/searchLibs/metavidSearch.js",
	"archiveOrgSearch"		: "libAddMedia/searchLibs/archiveOrgSearch.js",
	"flickrSearch"			: "libAddMedia/searchLibs/flickrSearch.js",
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

	"mvTextInterface"	: "libTimedText/mvTextInterface.js",
	"mvTimeTextEdit"	: "libTimedText/mvTimeTextEdit.js"
});

// Dependency mapping for CSS files for self-contained included plugins:
lcCssPath({
	'$j.Jcrop'			: 'libClipEdit/Jcrop/css/jquery.Jcrop.css',
	'$j.fn.ColorPicker'	: 'libClipEdit/colorpicker/css/colorpicker.css'
})


// parseUri 1.2.2
// (c) Steven Levithan <stevenlevithan.com>
// MIT License
function parseUri (str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};
parseUri.options = {
	strictMode: false,
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};

// For use when mv_embed with script-loader is in the root MediaWiki path
var mediaWiki_mvEmbed_path = 'js2/mwEmbed/';

//The global scope: will be depreciated once we get everything into $mw
var _global = this; 

/*
* setup the empty global $mw object
* will ensure all our functions and variables are properly namespaced
* reducing chance of conflicts
*/
if(!window['$mw']){
	window['$mw'] = {}
}

//@@todo move these into $mw
var global_req_cb = new Array(); // The global request callback array

// Get the mv_embed location if it has not been set
if( !mv_embed_path ) {
	var mv_embed_path = getMvEmbedPath();
}
/**
* The global $mw object:
*
* Any global functions/classes that are not jQuery plugins should make
* there way into the $mw namespace
*/
(function( $ ) {
	/*
	 * global config
	 */
	$.conf = {
		'skin_name' : 'mvpcf',
		'jui_skin' : 'redmond',
		'video_size' : '400x300'	
	}
	//list valid skins here:
	$.valid_skins = ['mvpcf', 'kskin'];
	// the version of mwEmbed
	$.version = '1.0r21';
	
	/*
	* some global containers flags 
	*/
	$.skin_list = new Array();
	$.init_done = false;
	$.cb_count = 0;
	$.player_list = new Array(), // The global player list per page
	$.req_cb = new Array() // The global request callback array	
	 
	/*
	* Language classes $mw.lang
	*
	* Localized Language support attempts to mirror the functionality of Language.php in MediaWiki
	* It contains methods for loading and transforming msg text
	*
	*/
	$.lang = {};
	/**
	* Setup the lang object
	*/
	var gMsg = {};
	var gRuleSet = {};

	/**
	* loadGM function
	* Loads a set of json messages into the lng object.
	*
	* @param json msgSet The set of msgs to be loaded
	*/
	$.lang.loadGM = function( msgSet ){
		for( var i in msgSet ) {
			gMsg[ i ] = msgSet[i];
		}
	},

	/**
	* loadRS function
	* Loads a ruleset by given template key ie PLURAL : { //ruleSetObj }
	*
	* @param json ruleSet The ruleset object ( extends  gRuleSet )
	*/
	$.lang.loadRS = function( ruleSet ){
		for( var i in ruleSet){
			gRuleSet[ i ] = ruleSet[ i ];
		}
	}

	/**
	 * Returns a transformed msg string
	 *
	 * it take a msg key and array of replacement values of form
	 * $1, $2 and does relevant msgkey transformation returning
	 * the user msg.
	 *
	 * @param string key The msg key as set by loadGm
	 * @param [mixed] args  An array of replacement strings
	 * @return string
	 */
	$.lang.gM = function( key , args ) {
		if(! gMsg[ key ])
			return '&lt;' + key + '&gt;';// Missing key placeholder

		//swap in the arg values
		var ms =  $.lang.gMsgSwap( key, args) ;
		
		
		
		//a quick check to see if we need to send the msg via the 'parser'
		//(we can add more detailed check once we support more wiki syntax)
		if( ms.indexOf('{{') === -1 && ms.indexOf('[') === -1){
			return ms;
		}

		//make sure we have the lagMagic setup:
		//@@todo move to init
		$.lang.magicSetup();
		//send the msg key through the parser
		var pObj = $.parser.pNew( ms );		
		//return the transformed msg
		return pObj.getHTML();
	}
	/**
	* gMsgSwap
	*
	* @param string key The msg key as set by loadGm
	* @param [mixed] args  An array or string to be replaced
	* @return string
	*/
	$.lang.gMsgSwap = function( key , args ){
		if(! gMsg[ key ])
			return '&lt;' + key + '&gt;';// Missing key placeholder
		//get the messeage string:
		var ms = gMsg[ key ];

		//replace values
		if( typeof args == 'object' || typeof args == 'array' ) {
			for( var v in args ) {
				// Message test replace arguments start at 1 instead of zero:
				var rep = new RegExp('\\$'+ ( parseInt(v) + 1 ), 'g');
				ms = ms.replace( rep, args[v] );
			}
		} else if( typeof args =='string' || typeof args =='number' ) {
			ms = ms.replace(/\$1/g, args);
		}
		return ms;
	}

	/**
	* gMsgNoTrans
	*
	* @returns string The msg key without transforming it
	*/
	$.lang.gMsgNoTrans = function( key ){
		if( gMsg[ key ] )
			return gMsg[ key ]

		// Missing key placeholder
		return '&lt;' + key + '&gt;';
	}
	/**
	* Add Supported Magic Words to parser
	*/
	// Set the setupflag to false:
	$.lang.doneSetup=false;
	$.lang.magicSetup = function(){
		if(!$.lang.doneSetup){
			$.parser.addMagic ( {
				'PLURAL' : $.lang.procPLURAL
			})

			$.lang.doneSetup = true;
		}

	}
	/**
	* Process the PLURAL special language template key:
	*/
	$.lang.procPLURAL = function( tObj ){
		//setup shortcuts
		// (gRuleSet is loaded from script-loader to contains local ruleset)
		var rs = gRuleSet['PLURAL'];

		/*
		 * Plural matchRuleTest
		 */
		function matchRuleTest(cRule, val){
			//js_log("matchRuleTest:: " + typeof cRule + ' ' + cRule + ' == ' + val );

			function checkValue(compare, val){
				if(typeof compare == 'string'){
					range = compare.split('-');
					if( range.length >= 1 ){
						if( val >= range[0] &&  val <= range[1] )
							return true;
					}
				}
				//else do a direct compare
				if(compare == val){
					return true;
				}
				return false;
			}
			//check for simple cRule type:
			if( typeof cRule == 'number'){
				return ( parseInt( val ) == parseInt( cRule) );
			}else if( typeof cRule == 'object' ){
				var cmatch = {};
				//if a list we need to match all for rule match
				for(var i in  cRule){
					var cr = cRule[i];
					//set cr type
					var crType =  '';
					for( var j in cr ){
						if(j == 'mod')
							crType = 'mod'
					}
					switch(crType){
						case 'mod':
							if( cr ['is'] ){
								if( checkValue( val % cr['mod'], cr ['is'] ) )
									cmatch[i] = true;
							}else if( cr['not']){
								if( ! checkValue( val % cr['mod'], cr ['not'] ) )
									cmatch[i] = true;
							}
						break;
					}
				}
				//check all the matches (taking into consideration "or" order)
				for(var i in cRule){
					if( ! cmatch[i] )
						return false;
				}
				return true;

			}
		}
		/**
		 * Maps a given rule Index to template params:
		 *
		 * if index is out of range return last param
		 * @param
		 */
		function getTempParamFromRuleInx(tObj, ruleInx ){
			//js_log('getTempParamFromRuleInx: ruleInx: ' + ruleInx + ' tempParamLength ' + tObj.param.length );
			if( ruleInx	>= tObj.param.length )
				return  tObj.param[  tObj.param.length -1 ];
			//else return the requested index:
			return tObj.param[ ruleInx ];
		}
		var rCount=0
		//run the actual rule lookup:
		for(var ruleInx in rs){
			cRule = rs[ruleInx];
			if( matchRuleTest( cRule, tObj.arg ) ){
				//js_log("matched rule: " + ruleInx );
				return getTempParamFromRuleInx(tObj, rCount );
			}
			rCount ++;
		}
		//js_log('no match found for: ' + tObj.arg + ' using last/other : ' +  tObj.param [ tObj.param.length -1 ] );
		//return the last /"other" template param
		return tObj.param [ tObj.param.length -1 ];
	}

	/**
	 * gMsgLoadRemote loads remote msg strings
	 *
	 * @param mixed msgSet the set of msg to load remotely
	 * @param function callback  the callback to issue once string is ready
	 */
	$.lang.gMsgLoadRemote = function( msgSet, callback ) {
		var ammessages = '';
		if( typeof msgSet == 'object' ) {
			for( var i in msgSet ) {
				ammessages += msgSet[i] + '|';
			}
		} else if( typeof msgSet == 'string' ) {
			ammessages += msgSet;
		}
		if( ammessages == '' ) {
			js_log( 'gMsgLoadRemote: no message set requested' );
			return false;
		}
		do_api_req({
			'data': {
				'meta': 'allmessages',
				'ammessages': ammessages
			}
		}, function( data ) {
			if( data.query.allmessages ) {
				var msgs = data.query.allmessages;
				for( var i in msgs ) {
					var ld = {};
					ld[ msgs[i]['name'] ] = msgs[i]['*'];
					loadGM( ld );
				}
			}
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
	$.lang.formatSize = function ( size ) {
		// For small sizes no decimal places are necessary
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
		// JavaScript does not let you choose the precision when rounding
		var p = Math.pow(10,round);
		var size = Math.round( size * p ) / p;
		return gM( msg , size );
	};
	
	$.lang.formatNumber = function( num ){
		/*
		*	addSeparatorsNF
		* @param Str: The number to be formatted, as a string or number.		
		* @param outD: The decimal character for the output, such as ',' for the number 100,2
		* @param sep: The separator character for the output, such as ',' for the number 1,000.2
		*/ 
		function addSeparatorsNF(nStr, outD, sep){
			nStr += '';
			var dpos = nStr.indexOf( '.' );
			var nStrEnd = '';
			if (dpos != -1) {
				nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
				nStr = nStr.substring(0, dpos);
			}
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(nStr)) {
				nStr = nStr.replace(rgx, '$1' + sep + '$2');
			}
			return nStr + nStrEnd;
		}		
		//@@todo read language code and give periods or comas: 
		return addSeparatorsNF( num, '.', ',');
	}
	
	
	
	/**
	* MediaWiki wikitext "Parser"
	*
	* This is not feature complete but we need a way to get at template properties
	*
	*
	* @param wikiText the wikitext to be parsed
	* @return parserObj returns a parser object that has methods for getting at
	* things you would want
	*/
	$.parser = {};
	var pMagicSet = {};
	/**
	 * parser addMagic
	 *
	 * Lets you add a set of magic keys and associated callback functions
	 *
	 * @param object magicSet key:callback
	 */
	$.parser.addMagic = function( magicSet ){
		for(var i in magicSet)
			pMagicSet[ i ] = magicSet[i];
	}

	//actual parse call (returns parser object)
	$.parser.pNew = function( wikiText, opt ){
		var parseObj = function( wikiText, opt){
			return this.init( wikiText, opt )
		}
		parseObj.prototype = {
			//the wikiText "DOM"... stores the parsed wikiText structure
			//wtDOM : {}, (not yet supported )

			pOut : '', //the parser output string container
			init  :function( wikiText ){
				this.wikiText = wikiText;
			},
			updateText : function( wikiText ){
				this.wikiText = wikiText;
				//invalidate the output (will force a re-parse )
				this.pOut = '';
			},
			parse : function(){
				/*
				 * quickly recursive / parse out templates:
				 */

				// ~ probably a better algorithm out there / should mirror php parser flow ~
				//	 (we are already running white-space issues ie php parse strips whitespace differently)
				
				// ... but I am having fun with recursion so here it is...
				// or at least mirror: http://www.mediawiki.org/wiki/Extension:Page_Object_Model
				function rdpp ( txt , cn){				
					var node = {};					
					//inspect each char
					for(var a=0; a < txt.length; a++){
						if( txt[a] == '{' && txt[a+1] == '{' ){
							a=a+2;
							node['p'] = node;
							if(!node['c'])
								node['c'] = new Array();

							node['c'].push( rdpp( txt.substr( a ), true ) );																																									
						}else if( txt[a] == '}' && txt[a+1] == '}'){							
							a=a+2;
							if( !node['p'] ){
								return node;
							}							
							node = node['p'];							
						}
						if(!node['t'])
							node['t']='';
						//don't put closures into output:
						if( txt[a] &&  txt[a]!='}' )
								node['t'] += txt[a];
								
					}
					return node;
				}
				/**
				 * parse template text as template name and named params
				 */
				function parseTmplTxt( ts ){				
					var tObj = {};
					//Get template name:
					tname = ts.split('\|').shift() ;
					tname = tname.split('\{').shift() ;
					tname = tname.replace( /^\s+|\s+$/g, "" ); //trim

					//check for arguments:
					if( tname.split(':').length == 1 ){
						tObj["name"] = tname;
					}else{
						tObj["name"] = tname.split(':').shift();
						tObj["arg"] = tname.split(':').pop();
					}

					//js_log("TNAME::" + tObj["name"] + ' from:: ' + ts);						
					var pSet = ts.split('\|');
					pSet.splice(0,1);					
					if( pSet.length ){
						tObj.param = new Array();
						for(var pInx in pSet){							
							var tStr = pSet[ pInx ];
							//check for empty param
							if(tStr==''){
								tObj.param[ pInx ] = '';
								continue;
							}
							for(var b=0 ; b < tStr.length ; b++){
								if(tStr[b] == '=' && b>0 && b<tStr.length && tStr[b-1]!='\\'){
									//named param
									tObj.param[ tStr.split('=').shift() ] =	tStr.split('=').pop();
								}else{
									//indexed param
									tObj.param[ pInx ] = tStr;
								}
							}
						}
					}							
					return tObj;
				}
				function getMagicTxtFromTempNode( node ){
					node.tObj = parseTmplTxt ( node.t );
					//do magic swap if template key found in pMagicSet
					if( node.tObj.name in pMagicSet){
						var nt = pMagicSet[ node.tObj.name ]( node.tObj );
						return nt;
					}else{
						//don't swap just return text
						return node.t;
					}
				}
				/**
				 * recurse_magic_swap
				 *
				 * go last child first swap upward: (could probably be integrated above somehow)
				 */
				var pNode = null;
				function recurse_magic_swap( node ){
					if( !pNode )
						pNode = node;

					if( node['c'] ){
						//swap all the kids:
						for(var i in node['c']){
							var nt = recurse_magic_swap( node['c'][i] );
							//swap it into current
							if( node.t ){
								node.t = node.t.replace( node['c'][i].t, nt);
							}
							//swap into parent
							pNode.t  = pNode.t.replace( node['c'][i].t, nt);
						}
						//do the current node:
						var nt = getMagicTxtFromTempNode( node );
						pNode.t = pNode.t.replace(node.t , nt);
						//run the swap for the outer most node
						return node.t;
					}else{
						//node.t = getMagicFromTempObj( node.t )
						return getMagicTxtFromTempNode( node );
					}
				}
				//parse out the template node structure:
				this.pNode = rdpp ( this.wikiText );
				//strip out the parent from the root	
				this.pNode['p'] = null;
				
				//do the recursive magic swap text:
				this.pOut = recurse_magic_swap( this.pNode );

			},
			
			/*
			 * parsed template api ~loosely based off of ~POM~
			 * http://www.mediawiki.org/wiki/Extension:Page_Object_Model
			 */
			
			/**
			 * templates
			 * 
			 * gets a requested template from the wikitext (if available)
			 *  
			 */
			templates: function( tname ){
				this.parse();
				var tmplSet = new Array();
				function getMatchingTmpl( node ){
					if( node['c'] ){
						for(var i in node['c']){
							getMatchingTmpl( node['c'] );
						}
					}					
					if( tname && node.tObj){
						if( node.tObj['name'] == tname )
							tmplSet.push( node.tObj );
					}else if( node.tObj ){
						tmplSet.push( node.tObj );
					}
				}				
				getMatchingTmpl( this.pNode );				
				return tmplSet;				
			},
			/**
			 * Returns the transformed wikitext
			 * 
			 * Build output from swapable index 
			 * 		(all transforms must be expanded in parse stage and linearly rebuilt)  
			 * Alternatively we could build output using a place-holder & replace system 
			 * 		(this lets us be slightly more sloppy with ordering and indexes, but probably slower)
			 * 
			 * Ideal: we build a 'wiki DOM' 
			 * 		When editing you update the data structure directly
			 * 		Then in output time you just go DOM->html-ish output without re-parsing anything			   
			 */
			getHTML : function(){
				//wikiText updates should invalidate pOut
				if( this.pOut == ''){
					this.parse();
				}				
				return this.pOut;
			}
		};
		//return the parserObj
		return new parseObj( wikiText, opt) ;
	}
	
})(window.$mw);

//load in js2 stopgap into proper location: 
if( typeof gMsg != 'undefined'){
	$mw.lang.loadGM( gMsg )
}

//setup legacy global shortcuts:
var loadGM = $mw.lang.loadGM;
var loadRS = $mw.lang.loadRS;
var gM = $mw.lang.gM;

// All default messages in [English] should be overwritten by the CMS language message system.
$mw.lang.loadGM({
	"mwe-loading_txt" : "Loading ...",
	"mwe-size-gigabytes" : "$1 GB",
	"mwe-size-megabytes" : "$1 MB",
	"mwe-size-kilobytes" : "$1 K",
	"mwe-size-bytes" : "$1 B",
	"mwe-error_load_lib" : "Error: JavaScript $1 was not retrievable or does not define $2",
	"mwe-loading-add-media-wiz" : "Loading add media wizard",
	"mwe-apiproxy-setup" : "Setting up API proxy",
	"mwe-load-drag-item" : "Loading dragged item",
	"mwe-ok" : "OK",
	"mwe-cancel" : "Cancel"
});


// Get the loading image
function mv_get_loading_img( style, class_attr ){
	var style_txt = (style)?style:'';
	var class_attr = (class_attr) ? 'class="' + class_attr + '"' : 'class="mv_loading_img"';
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
* mvJsLoader class handles initialisation and js file loads
*/
var mvJsLoader = {
	libreq : {},
	libs : {},

	// Base lib flags
	onReadyEvents: new Array(),
	doneReadyEvents: false,
	jQuerySetupFlag: false,

	// To keep consistency across threads
	ptime: 0,
	ctime: 0,

	load_error: false, // Load error flag (false by default)
	load_time: 0,
	callbacks: new Array(),
	cur_path: null,
	missing_path : null,
	doLoad: function( loadLibs, callback ) {
		this.ctime++;
		if( loadLibs && loadLibs.length != 0 ) {
			//js_log("doLoad setup::" + JSON.stringify( loadLibs ) );
			// Set up this.libs
			// First check if we already have this library loaded
			var all_libs_loaded = true;
			for( var i = 0; i< loadLibs.length; i++ ) {
				// Check if the library is already loaded
				if( ! this.checkObjPath( loadLibs[i] ) ) {
					all_libs_loaded = false;
				}
			}
			if( all_libs_loaded ) {
				js_log( 'Libraries ( ' + loadLibs  +  ') already loaded... skipping load request' );
				callback();
				return;
			}
			// Do a check for any CSS we may need and get it
			for( var i = 0; i < loadLibs.length; i++ ) {
				if( typeof mvCssPaths[ loadLibs[i] ] != 'undefined' ) {
					loadExternalCss( mvCssPaths[ loadLibs[i] ] );
				}
			}

			// Check if we should use the script loader to combine all the requests into one
			if( typeof mwSlScript != 'undefined' ) {
				var class_set = '';
				var last_class = '';
				var coma = '';
				for( var i = 0; i < loadLibs.length; i++ ) {
					var curLib = loadLibs[i];
					// Only add if not included yet:
					if( ! this.checkObjPath( curLib ) ) {
						class_set += coma + curLib;
						last_class = curLib;
						coma = ',';
					}
				}
				//Build the url to the scriptServer striping its request parameters:
				var puri = parseUri( getMvEmbedURL() );
				if( ( getMvEmbedURL().indexOf('://') != -1 )
					&& puri.host != parseUri( document.URL ).host )
				{
					var scriptPath = puri.protocol + '://' + puri.authority + puri.path;
				}else{
					var scriptPath = puri.path;
				}
				//js_log('scriptServer Path is: ' + scriptPath + "\n host script path:" + getMvEmbedURL() );				
				this.libs[ last_class ] = scriptPath + '?class=' + class_set +
					'&' + getMwReqParam();

			} else {
				// Do many requests
				for( var i = 0; i < loadLibs.length; i++ ) {
					var curLib = loadLibs[i];
					if( curLib ) {
						var libLoc = mvGetClassPath( curLib );
						// Do a direct load of the file (pass along unique request id from
						// request or mv_embed Version )
						var qmark = (libLoc.indexOf( '?' ) !== true) ? '?' : '&';
						this.libs[curLib] = mv_embed_path + libLoc + qmark + getMwReqParam();
					}
				}
			}
		}
		
		if( callback ) {
			this.callbacks.push( callback );
		}
		if( this.checkLoading() ) {
			//@@todo we should check the <script> Element .onLoad property to
			//make sure its just not a very slow connection
			//(even though the class is not loaded)
			if( this.load_time++ > 4000 ){ // Time out after ~80 seconds
				js_log( gM('mwe-error_load_lib', [mvGetClassPath(this.missing_path),  this.missing_path]) );
				this.load_error = true;
			} else {
				setTimeout( 'mvJsLoader.doLoad()', 20 );
			}
		} else {
			//js_log('checkLoading passed. Running callbacks...');
			// Only do callbacks if we are in the same instance (weird concurrency issue)
			var cb_count=0;
			for( var i = 0; i < this.callbacks.length; i++ )
				cb_count++;
			//js_log('RESET LIBS: loading is: '+ loading + ' callback count: '+cb_count +
			//	' p:'+ this.ptime +' c:'+ this.ctime);

			// Reset the libs
			this.libs = {};
			//js_log('done loading, do call: ' + this.callbacks[0] );
			while( this.callbacks.length != 0 ) {
				if( this.ptime == this.ctime - 1 ) { // Enforce thread consistency
					this.callbacks.pop()();
					//func = this.callbacks.pop();
					//js_log(' run: '+this.ctime+ ' p: ' + this.ptime + ' ' +loading+ ' :'+ func);
					//func();
				} else {
					// Re-issue doLoad ( ptime will be set to ctime so we should catch up)
					setTimeout( 'mvJsLoader.doLoad()', 25 );
					break;
				}
			}
		}
		this.ptime = this.ctime;
	},
	doLoadDepMode: function( loadChain, callback ) {
		// Firefox executes JS in the order in which it is included, so just directly issue the request
		if( $j.browser.firefox ) {
			var loadSet = [];
			for( var i = 0; i < loadChain.length; i++ ) {
				for( var j = 0; j < loadChain[i].length; j++ ) {
					loadSet.push( loadChain[i][j] );
				}
			}
			mvJsLoader.doLoad( loadSet, callback );
		} else {
			// Safari and IE tend to execute out of order so load with dependency checks
			mvJsLoader.doLoad( loadChain.shift(), function() {
				if( loadChain.length != 0 ) {
					mvJsLoader.doLoadDepMode( loadChain, callback );
				} else {
					callback();
				}
			});
		}
	},
	checkLoading: function() {
		var loading = 0;
		var i = null;
		for( var i in this.libs ) { // for/in loop is OK on an object
			if( !this.checkObjPath( i ) ) {
				if( !this.libreq[i] ) {
					loadExternalJs( this.libs[i] );
				}
				this.libreq[i] = 1;
				//js_log("has not yet loaded: " + i);
				loading = 1;
			}
		}
		return loading;
	},
	checkObjPath: function( libVar ) {
		if( !libVar )
			return false;
		var objPath = libVar.split( '.' )
		var cur_path = '';
		for( var p = 0; p < objPath.length; p++ ) {
			cur_path = (cur_path == '') ? cur_path + objPath[p] : cur_path + '.' + objPath[p];
			eval( 'var ptest = typeof ( '+ cur_path + ' ); ');
			if( ptest == 'undefined' ) {
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
	jQueryCheck: function( callback ) {
		//js_log( 'jQueryCheck::' );
		var _this = this;
		// Skip stuff if $j is already loaded:
		if( _global['$j'] && callback ){
			callback();
			if( _this.jQuerySetupFlag )
				return ;
		}		
		// Load jQuery
		_this.doLoad([
			'window.jQuery'
		], function() {
			//only do the $j setup once:
			if(!_global['$j']){
				_global['$j'] = jQuery.noConflict();
			}
			if( _this.jQuerySetupFlag == false){
				//js_log('setup mv_embed jQuery bindings');
				// Setup our global settings using the (jQuery helper)

				// Set up the skin path
				_global['mv_jquery_skin_path'] = mv_embed_path + 'jquery/jquery.ui/themes/' + $mw.conf['jui_skin'] + '/';
				_global['mv_skin_img_path'] = mv_embed_path + 'skins/' + $mw.conf['skin_name'] + '/images/';
				_global['mv_default_thumb_url'] = mv_skin_img_path + 'vid_default_thumb.jpg';			

				// Make sure the skin/style sheets are always available:
				loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
				loadExternalCss( mv_embed_path + 'skins/' + $mw.conf['skin_name'] + '/styles.css' );

				// Set up AJAX to not send dynamic URLs for loading scripts (we control that with
				// the scriptLoader)
				$j.ajaxSetup({
					cache: true
				});

				js_log( 'jQuery loaded into $j' );
				// Set up mvEmbed jQuery bindings and config based dependencies
				mv_jqueryBindings();
				_this.jQuerySetupFlag = true;
			}
			// Run the callback
			if( callback ) {
				callback();
			}
		});
	},
	embedVideoCheck:function( callback ) {
		var _this = this;
		js_log( 'embedVideoCheck:' );
		// Make sure we have jQuery
		_this.jQueryCheck( function() {
			//set class videonojs to loading
			$j('.videonojs').html( gM('mwe-loading_txt') );
			//Set up the embed video player class request: (include the skin js as well)
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
			
			//add any requested skins (supports multiple skins per single page)
			if( $mw.skin_list ){
				for(var i in $mw.skin_list  ){
					depReq[0].push( $mw.skin_list[i] + 'Config' );
				}
			}				

			// Add PNG fix if needed:
			if( $j.browser.msie || $j.browser.version < 7 )
				depReq[0].push( '$j.fn.pngFix' );

			//load the video libs:
			_this.doLoadDepMode( depReq, function() {
				embedTypes.init();
				callback();
				$j('.videonojs').remove();
			});
		});
	},
	addLoadEvent: function( fn ) {
		this.onReadyEvents.push( fn );
	},
	// Check the jQuery flag. This way, when remote embedding, we don't load jQuery
	// unless js2AddOnloadHook was used or there is video on the page.
	runQueuedFunctions: function() {
		js_log("runQueuedFunctions");		
		var _this = this;		
		this.jQueryCheck( function() {			
			_this.runReadyEvents();
			_this.doneReadyEvents = true;
		});
	},
	runReadyEvents: function() {
		js_log( "runReadyEvents" );
		while( this.onReadyEvents.length ) {	
			var func = this.onReadyEvents.shift();
			//js_log('run onReady:: ' + func );
			func();
		}
	}
}

// Shortcut ( @@todo consolidate shortcuts & re-factor mvJsLoader )
function mwLoad( loadSet, callback ) {
	mvJsLoader.doLoad( loadSet, callback );
}
//$mw.shortcut
$mw.load = mwLoad;

// Load an external JS file. Similar to jquery .require plugin,
// but checks for object availability rather than load state.

/*********** INITIALIZATION CODE *************
 * This will get called when the DOM is ready
 *********************************************/
/* jQuery .ready does not work when jQuery is loaded dynamically.
 * For an example of the problem see: 1.1.3 working: http://pastie.caboo.se/92588
 * and >= 1.1.4 not working: http://pastie.caboo.se/92595
 * $j(document).ready( function(){ */
function mwdomReady( force ) {
	js_log( 'f:mwdomReady:' );
	if( !force && $mw.init_done ) {
		js_log( "mw done, do nothing..." );
		return false;
	}
	$mw.init_done = true;
	// Handle the execution of queued functions with jQuery "ready"

	// Check if this page has a video, audio or playlist tag
	var e = [
		document.getElementsByTagName( "video" ),
		document.getElementsByTagName( "audio" ),
		document.getElementsByTagName( "playlist" )
	];
	if( e[0].length != 0 || e[1].length != 0 || e[2].length != 0 ) {
		//look for any skin classes we have to load: 
		for(var j in e){
			for(var k in e[j]){
				if(e[j][k] && typeof( e[j][k]) == 'object'){
					var	sn = e[j][k].getAttribute('class');				
					if( sn && sn != ''){
						for(var n=0;n< $mw.valid_skins.length;n++){ 
							if( sn.indexOf($mw.valid_skins[n]) !== -1){
								$mw.skin_list.push( $mw.valid_skins[n] );
							}
						}
					}
				}
			}
		}				
		// Load libs and process videos
		mvJsLoader.embedVideoCheck( function() {
			// Run any queued global events:
			mv_video_embed( function() {
				mvJsLoader.runQueuedFunctions();
			});
		});
	} else {
		mvJsLoader.runQueuedFunctions();
	}
}

//js2AddOnloadHook: ensure jQuery and the DOM are ready
function js2AddOnloadHook( func ) {	
	//js_log('js2AddOnloadHook:: jquery:' +func);	
	//check for jQuery then add the load event (to run after video tag rewrites (if present) 
	mvJsLoader.jQueryCheck( function() {
		if( mvJsLoader.doneReadyEvents ) {
			//js_log('run queued event: ' + func);	
			func();
		} else {		
			mvJsLoader.addLoadEvent( func );
		}
	});
}
// Deprecated mwAddOnloadHook in favour of js2 naming (for clear separation of js2 code from old MW code
var mwAddOnloadHook = js2AddOnloadHook;
/*
 * This function allows for targeted rewriting
 */
function rewrite_by_id( vid_id, ready_callback ) {
	js_log( 'f:rewrite_by_id: ' + vid_id );
	// Force a re-check of the DOM for playlist or video elements:
	mvJsLoader.embedVideoCheck( function() {
		mv_video_embed( ready_callback, vid_id );
	});
}


/*********** INITIALIZATION CODE *************
 * set DOM-ready callback to init_mv_embed
 *********************************************/
// for Mozilla / modern browsers
if ( document.addEventListener ) {
	document.addEventListener( "DOMContentLoaded", mwdomReady, false );
}
var temp_f;
if( window.onload ) {
    temp_f = window.onload;
}
// Use the onload method as a backup
window.onload = function () {
    if( temp_f )
        temp_f();
	mwdomReady();
}

/*
 * Store all the mwEmbed jQuery-specific bindings
 * (set up after jQuery is available).
 *
 * These functions are generally are loaders that do the dynamic mapping of
 * dependencies for a given component
 * 
 *
 */
function mv_jqueryBindings() {
	js_log( 'mv_jqueryBindings' );
	(function( $ ) {
		/*
		* dragDrop file loader 
		*/
		$.fn.dragFileUpload = function ( conf ){
			if( this.selector ){	
				var _this = this;	
				//load the dragger and "setup"
				$mw.load( ['$j.fn.dragDropFile'], function(){
					$j(_this.selector).dragDropFile();							
				}); 
			}					 
		}
		/*
		 * apiProxy Loader loader:
		 * 
		 * @param mode is either 'server' or 'client'
		 */				
		$.apiProxy = function( mode, pConf, callback ){
			js_log('do apiProxy setup');			
			mvJsLoader.doLoad( [
				'$mw.proxy',
				'JSON'
			], function(){				
				//do the proxy setup or 
				if( mode == 'client'){
					//just do the setup (no callbcak for client setup) 
					$mw.proxy.client( pConf );
					if( callback )
						callback();
				}else if( mode=='server' ){
					//do the request with the callback
					$mw.proxy.server( pConf , callback );
				}
			});	
		}
		
		//non selector based add-media-wizard direct invocation with loader
		$.addMediaWiz = function( iObj, callback ){			
			js_log(".addMediaWiz call");
			//check if already loaded:
			if( _global['rsdMVRS'] ){
				_global['rsdMVRS'].doReDisplay();
				if( callback )
					callback( _global['rsdMVRS'] );
				return ;
			}
			//display a loader: 
			$.addLoaderDialog( gM('mwe-loading-add-media-wiz') );
			//load the addMedia wizard without a target: 
			$.fn.addMediaWiz ( iObj, function( amwObj ){
				//close the dialog
				$.closeLoaderDialog();
				//do the add-media-wizard display
				amwObj.doInitDisplay();				
				//call the parent callback:
				if( callback )
					callback( _global['rsdMVRS'] ); 
			});
		}
		$.fn.addMediaWiz = function( iObj, callback ) {			
			if( this.selector ){			
				// First set the cursor for the button to "loading"
				$j( this.selector ).css( 'cursor', 'wait' ).attr( 'title', gM( 'mwe-loading_txt' ) );
				//set the target: 
				iObj['target_invocation'] = this.selector;
			}

			// Load the mv_embed_base skin:
			loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
			loadExternalCss( mv_embed_path + 'skins/' + $mw.conf['skin_name'] + '/styles.css' );
			// Load all the required libs:
			mvJsLoader.jQueryCheck( function() {
				// Load with staged dependencies (for IE that does not execute in order)
				mvJsLoader.doLoadDepMode([
					[	'remoteSearchDriver',
						'$j.cookie',
						'$j.fn.textSelection',
						'$j.ui'
					],[
						'$j.ui.resizable',
						'$j.ui.draggable',
						'$j.ui.dialog',
						'$j.ui.tabs',
						'$j.ui.sortable'
					]
				], function() {
					iObj['instance_name'] = 'rsdMVRS';
					if( ! _global['rsdMVRS'] )
						_global['rsdMVRS'] = new remoteSearchDriver( iObj );
					if( callback ) {
						callback( _global['rsdMVRS'] );
					}
				});
			});
		}
		/*
		* Sequencer loader
		*/
		$.fn.sequencer = function( iObj, callback ) {
			// Debugger
			iObj['target_sequence_container'] = this.selector;
			// Issue a request to get the CSS file (if not already included):
			loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
			loadExternalCss( mv_embed_path + 'skins/' + $mw.conf['skin_name'] + '/mv_sequence.css' );
			// Make sure we have the required mv_embed libs (they are not loaded when no video
			// element is on the page)
			mvJsLoader.embedVideoCheck( function() {
				// Load the playlist object and then the jQuery UI stuff:
				mvJsLoader.doLoadDepMode([
					[
						'mvPlayList',
						'$j.ui',
						'$j.contextMenu',
						'JSON',
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
				], function() {
					js_log( 'calling new mvSequencer' );
					// Initialise the sequence object (it will take over from there)
					// No more than one mvSeq obj for now:
					if( !_global['mvSeq'] ) {
						_global['mvSeq'] = new mvSequencer( iObj );
					} else {
						js_log( 'mvSeq already init' );
					}
				});
			});
		}
		/*
		 * The Firefogg jQuery function:
		 * @@note This Firefogg invocation could be made to work more like real jQuery plugins
		 */		
		var queuedFirefoggConf = {};
		$.fn.firefogg = function( iObj, callback ) {
			if( !iObj )
				iObj = {};
			// Add the base theme CSS:
			loadExternalCss( mv_jquery_skin_path + 'jquery-ui-1.7.1.custom.css' );
			loadExternalCss( mv_embed_path + 'skins/' + $mw.conf['skin_name'] + '/styles.css' );

			// Check if we already have Firefogg loaded (the call just updates the element's
			// properties)
			var sElm = $j( this.selector ).get( 0 );
			if( sElm['firefogg'] ) {
				if( sElm['firefogg'] == 'loading' ) {
					js_log( "Queued firefogg operations ( firefogg " +
						"not done loading ) " );
					$j.extend( queuedFirefoggConf, iObj );
					return false;
				}
				// Update properties
				for( var i in iObj ) {
					js_log( "firefogg::updated: " + i + ' to '+ iObj[i] );
					sElm['firefogg'][i] = iObj[i];
				}
				return sElm['firefogg'];
			} else {
				// Avoid concurrency
				sElm['firefogg'] = 'loading';
			}
			// Add the selector
			iObj['selector'] = this.selector;

			var loadSet = [
				[
					'mvBaseUploadInterface',
					'mvFirefogg',
					'$j.ui'
				],
				[
					'$j.ui.progressbar',
					'$j.ui.dialog',
					'$j.ui.draggable'
				]
			];
			if( iObj.encoder_interface ) {
				loadSet.push([
					'mvAdvFirefogg',
					'$j.cookie',
					'$j.ui.accordion',
					'$j.ui.slider',
					'$j.ui.datepicker'
				]);
			}
			// Make sure we have everything loaded that we need:
			mvJsLoader.doLoadDepMode( loadSet, function() {
					js_log( 'firefogg libs loaded. target select:' + iObj.selector );
					// Select interface provider based on whether we want to include the
					// encoder interface or not
					if( iObj.encoder_interface ) {
						var myFogg = new mvAdvFirefogg( iObj );
					} else {
						var myFogg = new mvFirefogg( iObj );
					}
					if( myFogg ) {
						myFogg.doRewrite( callback );
						var selectorElement = $j( iObj.selector ).get( 0 );
						selectorElement['firefogg'] = myFogg;
						
						js_log('pre:'+ selectorElement['firefogg']['firefogg_form_action'])
						if(queuedFirefoggConf)
							$j.extend(selectorElement['firefogg'], queuedFirefoggConf);
						js_log('post:'+ selectorElement['firefogg']['firefogg_form_action'])
					}
			});
		}
		// Take an input player as the selector and expose basic rendering controls
		$.fn.firefoggRender = function( iObj, callback ) {
			// Check if we already have render loaded then just pass on updates/actions
			var sElm = $j( this.selector ).get( 0 );
			if( sElm['fogg_render'] ) {
				if( sElm['fogg_render'] == 'loading' ) {
					js_log( "Error: called firefoggRender while loading" );
					return false;
				}
				// Call or update the property:
			}
			sElm['fogg_render'] = 'loading';
			// Add the selector
			iObj['player_target'] = this.selector;
			mvJsLoader.doLoad([
				'mvFirefogg',
				'mvFirefoggRender'
			], function() {
				sElm['fogg_render'] = new mvFirefoggRender( iObj );
				if( callback && typeof callback == 'function' )
					callback( sElm['fogg_render'] );
			});
		}

		$.fn.baseUploadInterface = function(iObj) {
			mvJsLoader.doLoadDepMode([
				[
					'mvBaseUploadInterface',
					'$j.ui',
				],
				[
					'$j.ui.progressbar',
					'$j.ui.dialog'
				]
			], function() {
				myUp = new mvBaseUploadInterface( iObj );
				myUp.setupForm();
			});
		}

		// Shortcut to a themed button
		$.btnHtml = function( msg, className, iconId, opt ) {
			if( !opt )
				opt = {};
			var href = (opt.href) ? opt.href : '#';
			var target_attr = (opt.target) ? ' target="' + opt.target + '" ' : '';
			var style_attr = (opt.style) ? ' style="' + opt.style + '" ' : '';
			return '<a href="' + href + '" ' + target_attr + style_attr +
				' class="ui-state-default ui-corner-all ui-icon_link ' +
				className + '"><span class="ui-icon ui-icon-' + iconId + '" />' +
				'<span class="btnText">'+ msg +'<span></a>';
		}
		// Shortcut to bind hover state
		$.fn.btnBind = function() {
			$j( this ).hover(
				function() {
					$j( this ).addClass( 'ui-state-hover' );
				},
				function() {
					$j( this ).removeClass( 'ui-state-hover' );
				}
			)
			return this;
		}
		/**
		* resize the dialog to fit the window
		*/
		$.fn.dialogFitWindow = function(opt){			
			var opt_default = {'hspace':50,'vspace':50};
			if(!opt)
				var opt={};			
			$j.extend(opt, opt_default);
			$j( this.selector).dialog('option', 'width', $j(window).width() - opt.hspace );
			$j( this.selector).dialog('option', 'height', $j(window).height() - opt.vspace );
			$j( this.selector).dialog('option', 'position','center');
				//update the child position: (some of this should be pushed up-stream via dialog config options
			$j( this.selector +'~ .ui-dialog-buttonpane').css({
				'position':'absolute',
				'left':'0px',
				'right':'0px',
				'bottom':'0px',
			});			
		}
		
		/**
		* addLoaderDialog
		*  small helper for putting a loading dialog box on top of everything
		* (helps block for request that
		*
		* @param msg text text of the loader msg
		*/
		$.addLoaderDialog = function( msg_txt ){			
			$.addDialog( msg_txt, msg_txt + '<br>' + mv_get_loading_img() );		
		}						
		
		$.addDialog = function ( title, msg_txt, btn ){
			$('#mwe_tmp_loader').remove();
			//append the style free loader ontop: 
			$('body').append('<div id="mwe_tmp_loader" style="display:none" title="' + title + '" >' +
					msg_txt +
			'</div>');			
			//special btn == ok gives empty give a single "oky" -> "close"
			if( btn == 'ok' ){
				btn[ gM('mwe-ok') ] = function(){
					$j('#mwe_tmp_loader').close();
				}
			}
			//turn the loader into a real dialog loader: 
			mvJsLoader.doLoadDepMode([
				[
					'$j.ui'
				],
				[
					'$j.ui.dialog'
				]
			], function() {
				$('#mwe_tmp_loader').dialog({
					bgiframe: true,
					draggable: false,
					resizable: false,					
					modal: true,
					width:400,
					buttons: btn
				});
			});
		}
		$.closeLoaderDialog = function(){		
			mvJsLoader.doLoadDepMode([
				[
					'$j.ui'
				],
				[
					'$j.ui.dialog'
				]
			], function() {	
				$j('#mwe_tmp_loader').dialog('close');
			});
		}
	
		$.mwProxy = function( apiConf ){
			mvJsLoader.doLoad( ['$mw.apiProxy'], 
			function(){
				$mw.apiProxy( apiConf );
			});
		}
	})(jQuery);
}
/*
* Utility functions:
*/
// Simple URL rewriter (could probably be refactored into an inline regular exp)
function getURLParamReplace( url, opt ) {
	var pSrc = parseUri( url );
	if( pSrc.protocol != '' ) {
		var new_url = pSrc.protocol + '://' + pSrc.authority + pSrc.path + '?';
	} else {
		var new_url = pSrc.path + '?';
	}
	var amp = '';
	for( var key in pSrc.queryKey ) {
		var val = pSrc.queryKey[ key ];
		// Do override if requested
		if( opt[ key ] )
			val = opt[ key ];
		new_url += amp + key + '=' + val;
		amp = '&';
	};
	// Add any vars that were not already there:
	for( var i in opt ) {
		if( !pSrc.queryKey[i] ) {
			new_url += amp + i + '=' + opt[i];
			amp = '&';
		}
	}
	return new_url;
}
/**
 * Given a float number of seconds, returns npt format response.
 *
 * @param float Seconds
 * @param boolean If we should show milliseconds or not.
 */
function seconds2npt( sec, show_ms ) {
	if( isNaN( sec ) ) {
		// js_log("warning: trying to get npt time on NaN:" + sec);
		return '0:0:0';
	}
	var hours = Math.floor( sec / 3600 );
	var minutes = Math.floor( (sec / 60) % 60 );
	var seconds = sec % 60;
	// Round the number of seconds to the required number of significant digits
	if( show_ms ) {
		seconds = Math.round( seconds * 1000 ) / 1000;
	} else {
		seconds = Math.round( seconds );
	}
	if( seconds < 10 )
		seconds = '0' +	seconds;
	if( minutes < 10 )
		minutes = '0' + minutes;

	return hours + ":" + minutes + ":" + seconds;
}
/*
 * Take hh:mm:ss,ms or hh:mm:ss.ms input, return the number of seconds
 */
function npt2seconds( npt_str ) {
	if( !npt_str ) {
		//js_log('npt2seconds:not valid ntp:'+ntp);
		return false;
	}
	// Strip "npt:" time definition if present
	npt_str = npt_str.replace( 'npt:', '' );

	times = npt_str.split( ':' );
	if( times.length != 3 ){
		js_log( 'error: npt2seconds on ' + npt_str );
		return false;
	}
	// Sometimes a comma is used instead of period for ms
	times[2] = times[2].replace( /,\s?/, '.' );
	// Return seconds float
	return parseInt( times[0] * 3600) + parseInt( times[1] * 60 ) + parseFloat( times[2] );
}
/*
 * Simple helper to grab an edit token
 *
 * @param title The wiki page title you want to edit
 * @param api_url 'optional' The target API URL
 * @param callback The callback function to pass the token to
 */
function get_mw_token( title, api_url, callback ) {
	js_log( ':get_mw_token:' );
	if( !title && wgUserName ) {
		title = 'User:' + wgUserName;
	}
	var reqObj = {
			'action': 'query',
			'prop': 'info',
			'intoken': 'edit',
			'titles': title
		};
	do_api_req( {
		'data': reqObj,
		'url' : api_url
		}, function(data) {
			for( var i in data.query.pages ) {
				if( data.query.pages[i]['edittoken'] ) {
					if( typeof callback == 'function' )
						callback ( data.query.pages[i]['edittoken'] );
				}
			}
			// No token found:
			return false;
		}
	);
}
// Do a remote or local API request based on request URL
//@param options: url, data, cbParam, callback
function do_api_req( options, callback ) {
	if( typeof options.data != 'object' ) {
		return js_error( 'Error: request paramaters must be an object' );
	}
	// Generate the URL if it's missing
	if( typeof options.url == 'undefined' || !options.url ) {
		if( !wgServer || ! wgScriptPath ) {
			return js_error('Error: no api url for api request');
		}
		options.url = mwGetLocalApiUrl();
	}
	if( typeof options.data == 'undefined' )
		options.data = {};

	// Force format to JSON
	options.data['format'] = 'json';

	// If action is not set, assume query
	if( ! options.data['action'] )
		options.data['action'] = 'query';

	// js_log('do api req: ' + options.url +'?' + jQuery.param(options.data) );	
	if( options.url == 'proxy' && $mw.proxy){
		//assume the proxy is already "setup" since $mw.proxy is defined.
		// @@todo we probably integrate that setup into the api call
		$mw.proxy.doRequest( options.data,  callback);	
	}else if( parseUri( document.URL ).host == parseUri( options.url ).host ) {
		// Local request: do API request directly
		$j.ajax({
			type: "POST",
			url: options.url,
			data: options.data,
			dataType: 'json', // API requests _should_ always return JSON data:
			async: false,
			success: function( data ) {
				callback( data );
			},
			error: function( e ) {
				js_error( ' error' + e + ' in getting: ' + options.url );
			}
		});
	} else {
		// Remote request
		// Set the callback param if it's not already set
		if( typeof options.jsonCB == 'undefined' )
			options.jsonCB = 'callback';

		var req_url = options.url;
		var paramAnd = ( req_url.indexOf( '?' ) == -1 ) ? '?' : '&';
		// Put all the parameters into the URL
		for( var i in options.data ) {
			req_url += paramAnd + encodeURIComponent( i ) + '=' + encodeURIComponent( options.data[i] );
			paramAnd = '&';
		}
		var fname = 'mycpfn_' + ( $mw.cb_count++ );
		_global[ fname ] = callback;
		req_url += '&' + options.jsonCB + '=' + fname;
		loadExternalJs( req_url );
	}
}
function mwGetLocalApiUrl( url ) {
	if ( wgServer && wgScriptPath ) {
		return wgServer + wgScriptPath + '/api.php';
	}
	return false;
}
// Do a "normal" request
function do_request( req_url, callback ) {
	js_log( 'do_request::req_url:' + req_url + ' != ' +  parseUri( req_url ).host );
	// If we are doing a request to the same domain or relative link, do a normal GET
	if( parseUri( document.URL ).host == parseUri( req_url ).host ||
		req_url.indexOf('://') == -1 ) // Relative url
	{
		// Do a direct request
		$j.ajax({
			type: "GET",
			url: req_url,
			async: false,
			success: function( data ) {
				callback( data );
			}
		});
	} else {
		// Get data via DOM injection with callback
		global_req_cb.push( callback );
		// Prepend json_ to feed_format if not already requesting json format (metavid specific)
		if( req_url.indexOf( "feed_format=" ) != -1 && req_url.indexOf( "feed_format=json" ) == -1 )
			req_url = req_url.replace( /feed_format=/, 'feed_format=json_' );		
		
		loadExternalJs( req_url + '&cb=mv_jsdata_cb&cb_inx=' + (global_req_cb.length - 1) );
	}
}

function mv_jsdata_cb( response ) {
	js_log( 'f:mv_jsdata_cb:'+ response['cb_inx'] );
	// Run the callback from the global request callback object
	if( !global_req_cb[response['cb_inx']] ) {
		js_log( 'missing req cb index' );
		return false;
	}
	if( !response['pay_load'] ) {
		js_log( "missing pay load" );
		return false;
	}
	switch( response['content-type'] ) {
		case 'text/plain':
		break;
		case 'text/xml':
			if( typeof response['pay_load'] == 'string' ) {
				//js_log('load string:'+"\n"+ response['pay_load']);
				// Debugger;
				if( $j.browser.msie ) {
					// Attempt to parse as XML for IE
					var xmldata = new ActiveXObject("Microsoft.XMLDOM");
					xmldata.async = "false";
					xmldata.loadXML( response['pay_load'] );
				} else {
					// For others (Firefox, Safari etc.)
					try {
						var xmldata = (new DOMParser()).parseFromString( response['pay_load'], "text/xml" );
					} catch( e ) {
						js_log( 'XML parse ERROR: ' + e.message );
					}
				}
				//@@todo handle XML parser errors
				if( xmldata )response['pay_load'] = xmldata;
			}
		break
		default:
			js_log( 'bad response type' + response['content-type'] );
			return false;
		break;
	}
	global_req_cb[response['cb_inx']]( response['pay_load'] );
}
// Load external JS via DOM injection
function loadExternalJs( url, callback ) {
	js_log( 'load js: '+ url );
	//if(window['$j']) // use jquery call:
		/*$j.ajax({
			type: "GET",
			url: url,
			dataType: 'script',
			cache: true
		});*/
	//else{
		var e = document.createElement( "script" );
		e.setAttribute( 'src', url );
		e.setAttribute( 'type', "text/javascript" );
		/*if(callback)
			e.onload = callback;
		*/
		//e.setAttribute('defer', true);
		document.getElementsByTagName( "head" )[0].appendChild( e );
	// }
}
function styleSheetPresent( url ) {
	style_elements = document.getElementsByTagName( 'link' );
	if( style_elements.length > 0 ) {
		for( i = 0; i < style_elements.length; i++ ) {
			if( style_elements[i].href == url )
				return true;
		}
	}
	return false;
}
function loadExternalCss( url ) {
	// We could have the script loader group these CSS requests.
	// But it's debatable: it may hurt more than it helps with caching and all
	if( typeof url =='object' ) {
		for( var i in url ) {
			loadExternalCss( url[i] );
		}
		return ;
	}

	if( url.indexOf('?') == -1 ) {
		url += '?' + getMwReqParam();
	}
	if( !styleSheetPresent( url ) ) {
		js_log( 'load css: ' + url );
		var e = document.createElement( "link" );
		e.href = url;
		e.type = "text/css";
		e.rel = 'stylesheet';
		document.getElementsByTagName( "head" )[0].appendChild( e );
	}
}
function getMvEmbedURL() {
	if( _global['mv_embed_url'] )
		return _global['mv_embed_url'];
	var js_elements = document.getElementsByTagName( "script" );
	for( var i = 0; i < js_elements.length; i++ ) {
		// Check for mv_embed.js and/or script loader
		var src = js_elements[i].getAttribute( "src" );
		if( src ) {
			if( src.indexOf( 'mv_embed.js' ) != -1 || (
				( src.indexOf( 'mwScriptLoader.php' ) != -1 || src.indexOf('jsScriptLoader.php') != -1 )
				&& src.indexOf('mv_embed') != -1) ) //(check for class=mv_embed script_loader call)
			{
				_global['mv_embed_url'] = src;
				return src;
			}
		}
	}
	js_error( 'Error: getMvEmbedURL failed to get Embed Path' );
	return false;
}
// Get a unique request ID to ensure fresh JavaScript
function getMwReqParam() {	
	if( _global['req_param'] )
		return _global['req_param'];
	var mv_embed_url = getMvEmbedURL();
	
	var req_param = '';
	
	// If we have a URI, add it to the req
	var urid = parseUri( mv_embed_url ).queryKey['urid']	
	// If we're in debug mode, get a fresh unique request key and pass on "debug" param
	if( parseUri( mv_embed_url ).queryKey['debug'] == 'true' ){
		var d = new Date();
		req_param += 'urid=' + d.getTime() + '&debug=true';		
	}else if( urid ) {
		// Set from request urid:
		req_param += 'urid=' + urid;
	}else{
		// Otherwise, just use the mv_embed version
		req_param += 'urid=' + $mw.version;
	}
	//add the lang param:
	var langKey = parseUri( mv_embed_url ).queryKey['uselang'];
	if( langKey )
		req_param += '&uselang=' + langKey;
			
	_global['req_param'] = req_param;
		
	return _global['req_param'];
}
/*
 * Set the global mv_embed path based on the script's location
 */
function getMvEmbedPath() {
	if( _global['mv_embed_path'] )
		return _global['mv_embed_path'];
	var mv_embed_url = getMvEmbedURL();
	if( mv_embed_url.indexOf( 'mv_embed.js' ) !== -1 ) {
		mv_embed_path = mv_embed_url.substr( 0, mv_embed_url.indexOf( 'mv_embed.js' ) );
	} else if( mv_embed_url.indexOf( 'mwScriptLoader.php' ) !== -1 ) {
		// Script loader is in the root of MediaWiki, so include the default mv_embed extension path
		mv_embed_path = mv_embed_url.substr( 0, mv_embed_url.indexOf( 'mwScriptLoader.php' ) )
			+ mediaWiki_mvEmbed_path;
	} else {
		mv_embed_path = mv_embed_url.substr( 0, mv_embed_url.indexOf( 'jsScriptLoader.php' ) );
	}
	// Make an absolute URL (if it's relative and we don't have an mv_embed path)
	if( mv_embed_path.indexOf( '://' ) == -1 ) {
		var pURL = parseUri( document.URL );
		if( mv_embed_path.charAt( 0 ) == '/' ) {
			mv_embed_path = pURL.protocol + '://' + pURL.authority + mv_embed_path;
		} else {
			// Relative
			if( mv_embed_path == '' ) {
				mv_embed_path = pURL.protocol + '://' + pURL.authority + pURL.directory + mv_embed_path;
			}
		}
	}
	_global['mv_embed_path'] = mv_embed_path;
	return mv_embed_path;
}

if ( typeof DOMParser == "undefined" ) {
	DOMParser = function () {}
	DOMParser.prototype.parseFromString = function ( str, contentType ) {
		if ( typeof ActiveXObject != "undefined" ) {
			var d = new ActiveXObject( "MSXML.DomDocument" );
			d.loadXML( str );
			return d;
		} else if ( typeof XMLHttpRequest != "undefined" ) {
			var req = new XMLHttpRequest;
			req.open( "GET", "data:" + (contentType || "application/xml") +
					";charset=utf-8," + encodeURIComponent(str), false );
			if ( req.overrideMimeType ) {
				req.overrideMimeType(contentType);
			}
			req.send( null );
			return req.responseXML;
		}
	}
}
/*
* Utility functions
*/
function js_log( string ) {
	// Add any prepend debug strings if necessary (used for cross browser)
	if( $mw.conf['debug_pre'] )
		string = $mw.conf['debug_pre']+ string;
			
	if( window.console ) {				
		window.console.log( string );
	} else {
		/*
		 * IE and non-Firebug debug:
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

function js_error( string ) {
	alert( string );
	return false;
}
