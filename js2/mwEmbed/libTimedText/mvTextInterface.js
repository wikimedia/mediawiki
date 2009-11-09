loadGM({
	"mwe-select_transcript_set" : "Select subtitles",
	"mwe-auto_scroll" : "auto scroll",
	"mwe-close" : "close",
	"mwe-improve_transcript" : "Improve",
	"mwe-no_text_tracks_found" : "No text subtitles found",
	"mwe-add-edit-subs"	: "Add/edit subtitles"
})
// text interface object (for inline display captions)
var mvTextInterface = function( parentEmbed ){
	return this.init( parentEmbed );
}
mvTextInterface.prototype = {
	text_lookahead_time:0,
	body_ready:false,
	default_time_range: "source", //by default just use the source don't get a time-range
	transcript_set:null,
	autoscroll:true,
	add_to_end_on_this_pass:false,
	scrollTimerId:0,	
	editlink: '',
	suportedMime:{
	    'srt': 'text/x-srt',
	    'cmml': 'text/cmml'
	},
	init:function( parentEmbed ){
		//init a new availableTracks obj:
		this.availableTracks = new Array();
		//set the parent embed object:
		this.pe=parentEmbed;
		//parse roe if not already done:
		this.getTimedTextTracks();
	},
	//@@todo separate out data loader & data display
	getTimedTextTracks:function(){
		//js_log("load timed text from roe: "+ this.pe.roe);
		var _this = this;
		var apiUrl = mwGetLocalApiUrl();
		//if roe not yet loaded do load it:
		if(this.pe.roe || _this.pe.wikiTitleKey ){
			if(!this.pe.media_element.addedROEData){
				js_log("load roe data!");
				$j('#mv_txt_load_'+_this.pe.id).show(); //show the loading icon
				if(_this.pe.roe){
					do_request( _this.pe.roe, function(data)
					{
						_this.pe.media_element.addROE(data);
						_this.getParseTimedText_rowReady();
					});
				}else if( _this.pe.wikiTitleKey ){
					do_api_req({
							'url' :	apiUrl,
							'data': {
								'list' : 'allpages',
								'apprefix' : _this.pe.wikiTitleKey,
								'apnamespace' : 102
							}
					}, function( subData ) {					
						_this.doProcSubPages( subData, wgServer + wgScriptPath);
					});
				}
			}else{
				js_log('row data ready (no roe request)');
				_this.getParseTimedText_rowReady();
			}
		}else{
			if( this.pe.media_element.timedTextSources() ){
				_this.getParseTimedText_rowReady();
			}else{
				js_log('no roe data or timed text sources');
			}
		}
	},
	doProcSubPages: function( subData, hostPath ){
		var _this = this;
		//look for text tracks:
		var foundTextTracks = false;
		do_api_req({
			'url':	hostPath + '/api.php',
			'data': {
				'meta' : 'siteinfo',
				'siprop' : 'languages'
			}
		}, function( langDataRaw ) {
				var langData = {};
				var lagRaw = langDataRaw.query.languages;
				for(var j in lagRaw){
					langData[ lagRaw[j].code ] = lagRaw[j]['*'];
				}
				for(var i in subData.query.allpages){
					var subPage = subData.query.allpages[i];
					var langKey = subPage.title.split('.');
					var extension = langKey.pop();
					langKey = langKey.pop();
					if( ! _this.suportedMime[ extension ] ){
						js_log('Error: unknown extension:'+ extension);
						continue;
					}

					if( !langData[ langKey] ){
						js_log('Error: langkey:'+ langKey + ' not found');
					}else{											
						var textElm = document.createElement('text');
						$j(textElm).attr({
							'category' : 'SUB',
							'lang' 	: langKey,
							'type' 	: _this.suportedMime[ extension ],
							'title'	: langData[ langKey],							
						});
						//set src to remote friendly 
						if( wgServer &&  hostPath.indexOf(wgServer)!==-1){
							$j(textElm).attr('src', hostPath + '/index.php?title=' + subPage.title + '&action=raw');
						}else{
							$j(textElm).attr({
								'apisrc'	: hostPath + '/api.php',
								'titleKey' 	: subPage.title
							});
						}												
						_this.pe.media_element.tryAddSource( textElm );
						foundTextTracks = true;											
					}
				}
				//after all text loaded (or we have allready checked commons
				if(foundTextTracks || hostPath.indexOf('commons.wikimedia') !== -1){
					//alert('calling 			getParseTimedText_rowReady ');		
					_this.getParseTimedText_rowReady();				
				}else {
					_this.checkSharedRepo();
				}
			});	//do_api_req({
	},
	checkSharedRepo:function(){
		var _this = this;
		js_log('checking for shared value of image');
		//check if its a shared repo
		do_api_req({
			'data':{
				'action':'query',
				'titles': 'File:' + _this.pe.wikiTitleKey, 
				'prop' : 'imageinfo'
			}
		},function( data ){			
			if( data.query.pages && data.query.pages['-1'] && data.query.pages['-1'].imagerepository == 'shared'){
				js_log('image is shared checking commons for subtitles');
				//found shared repo assume commons: 
				do_api_req({
					'url': 'http://commons.wikimedia.org/w/api.php',
					'data':{
						'list' : 'allpages',
						'apprefix' : _this.pe.wikiTitleKey,
						'apnamespace' : 102
					}
				},function( data ){		
					_this.editlink = 'http://commons.wikimedia.org/wiki/TimedText:' +  _this.pe.wikiTitleKey +'.'+ wgUserLanguage +'.srt';
					_this.doProcSubPages( data, 'http://commons.wikimedia.org/w/' );
				});
			}else{
				//no shared repo do normal proc
				_this.getParseTimedText_rowReady();
			}
		});
	},
	getParseTimedText_rowReady: function (){		
		var _this = this;		
		var found_tracks = false;
		//create timedTextObj		
		js_log("mv_txt_load_:SHOW mv_txt_load_");
		$j('#mv_txt_load_'+_this.pe.id).show(); //show the loading icon
		
		//setup edit link: 
		if( _this.editlink == ''){
			if( this.pe.media_element.linkback ){			
				_this.editlink = this.pe.media_element.linkback;
			}else if(this.pe.wikiTitleKey && wgServer && wgScript) { //check for wikiTitleKey (for edit linkback)
				//only local:
				_this.editlink = wgServer + wgScript + '?title=TimedText:' + this.pe.wikiTitleKey +'.'+ wgUserLanguage + '.srt&action=edit';	
			}
		}		
		$j.each( this.pe.media_element.sources, function(inx, source){
			if( typeof source.id == 'undefined' || source.id == null ){
				source.id = 'text_' + inx;
			}
			var tObj = new timedTextObj( source ); 
			//make sure its a valid timed text format (we have not loaded or parsed yet) : (
			if( tObj.lib != null ){	
				_this.availableTracks.push( tObj );				
				//display if requested:
				if( ( wgUserLanguage && source['lang'] == wgUserLanguage ) || source['default'] == "true" ){
					//we did set at least one track by default tag
					found_tracks = true;					
					_this.loadAndDisplay( _this.availableTracks.length -1 );
				}else{
					//don't load the track and don't display					
				}
			}
		});
		//no default clip found take the userLanguage key if set:
		if(!found_tracks){			
			$j.each( _this.availableTracks, function(inx, source){				
				_this.loadAndDisplay( inx );
				found_tracks=true;
				//return after loading first available
				return false;
			});
		}		
	    //if nothing found anywhere give the not found msg: 
		if(!found_tracks){
			$j( '#metaBox_' + _this.pe.id).html( ''+
				'<h3>' + gM('mwe-no_text_tracks_found') + '</h3>' +
				'<a href="' + _this.editlink + '">'+ gM('mwe-add-edit-subs') + '</a>'
			);			
		}
	},
	loadAndDisplay: function ( track_id){
		var _this = this;
		$j('#mv_txt_load_'+_this.pe.id).show();//show the loading icon
		_this.availableTracks[ track_id ].load(_this.default_time_range, function(){
			$j('#mv_txt_load_'+_this.pe.id).hide();
			_this.addTrack( track_id );
		});
	},
	addTrack: function( track_id ){
		js_log('f:displayTrack:'+ track_id);
		var _this = this;
		//set the display flag to true:
		_this.availableTracks[ track_id ].display=true;
		//setup the layout:
		this.setup_layout();
		js_log("SHOULD ADD: track:"+ track_id + ' count:' +  _this.availableTracks[ track_id ].textNodes.length);

		//a flag to avoid checking all clips if we know we are adding to the end:
		_this.add_to_end_on_this_pass = false;

		//run clip adding on a timed interval to not lock the browser on large srt file merges (should use worker threads)
		var i =0;
		var track_id = track_id;
		var addNextClip = function(){
			var text_clip = _this.availableTracks[ track_id ].textNodes[i];
			_this.add_merge_text_clip(text_clip, track_id);
			i++;
			if(i < _this.availableTracks[ track_id ].textNodes.length){
				setTimeout(addNextClip, 1);
			}
		}
		addNextClip();
	},
	add_merge_text_clip: function( text_clip, track_id ){
		var _this = this;
		//make sure the clip does not already exist:
		if($j('#tc_'+text_clip.id).length==0){
			var inserted = false;
			var text_clip_start_time = npt2seconds( text_clip.start );

			var insertHTML = '<div id="tc_'+text_clip.id+'" ' +
				'start_sec="' + text_clip_start_time + '" ' +
				'start="'+text_clip.start+'" end="'+text_clip.end + '" ' +
				'class="mvtt track_' + track_id + '">' +
					'<div class="mvttseek" style="top:0px;left:0px;right:0px;height:20px;font-size:small">'+
						text_clip.start + ' to ' +text_clip.end+
					'</div>'+
					text_clip.body +
			'</div>';
			if(!_this.add_to_end_on_this_pass){
				$j('#mmbody_'+this.pe.id +' .mvtt').each(function(){
					if(!inserted){
						if( $j(this).attr('start_sec') > text_clip_start_time){
							inserted=true;
							$j(this).before(insertHTML);
						}
					}else{
						_this.add_to_end = true;
					}
				});
			}
			//js_log('should just add to end: '+insertHTML);
			if(!inserted){
				$j('#mmbody_'+this.pe.id ).append(insertHTML);
			}

			//apply the mouse over transcript seek/click functions:
			$j(".mvttseek").click( function() {
				_this.pe.doSeek( $j(this).parent().attr("start_sec") / _this.pe.getDuration() );
			});
			$j(".mvttseek").hoverIntent({
				interval:200, //polling interval
				timeout:200, //delay before onMouseOut
				over:function () {
					  js_log('mvttseek: over');
					  $j(this).parent().addClass('tt_highlight');
					//do section highlight
					_this.pe.highlightPlaySection( {
						'start'	: $j(this).parent().attr("start"),
						'end'	: $j(this).parent().attr("end")
					});
				},
				out:function () {
					  js_log('mvttseek: out');
					  $j(this).parent().removeClass('tt_highlight');
					  //de highlight section
					_this.pe.hideHighlight();
				}
			  }
			);
		}
	},
	setup_layout:function(){
		var _this = this;
		//check if we have already loaded the menu/body:
		if($j('#tt_mmenu_'+this.pe.id).length==0){
			//alert( this.availableTracks.length );
			if( this.availableTracks.length != 0 ){
				$j('#metaBox_'+this.pe.id).html(
					this.getMenu() +
					this.getBody()
				);
				this.doMenuBindings();
			}
		}
	},
	show:function(){
		//setup layout if not already done:
		this.setup_layout();
		//display the interface if not already displayed:
		$j('#metaBox_'+this.pe.id).fadeIn("fast");
		//start the autoscroll timer:
		if( this.autoscroll )
			this.setAutoScroll();
	},
	close:function(){
		//the meta box:
		$j('#metaBox_'+this.pe.id).fadeOut('fast');
		//the icon link:
		$j('#metaButton_'+this.pe.id).fadeIn('fast');
	},
	getBody:function(){
		return '<div id="mmbody_'+this.pe.id+'" ' +
				'style="position:absolute;top:30px;left:0px;' +
				'right:0px;bottom:0px;' +
				'height:'+(this.pe.height-30)+
				'px;overflow:auto;"><span style="display:none;" id="mv_txt_load_' + this.pe.id + '">'+
					mv_get_loading_img() +'</span>' +
				'</div>';
	},
	getTsSelect:function(){
		var _this = this;
		js_log('getTsSelect');
		var selHTML = '<div id="mvtsel_' + this.pe.id + '" style="position:absolute;background:#FFF;top:30px;left:0px;right:0px;bottom:0px;overflow:auto;">';
		selHTML+='<b>' + gM('mwe-select_transcript_set') + '</b><ul>';
		//debugger;
		for(var i in _this.availableTracks){ //for in loop ok on object
			var checked = ( _this.availableTracks[i].display ) ? 'checked' : '';
			selHTML+='<li><input name="language" value="'+i+'" class="mvTsSelect" type="radio" ' + checked + '>'+
				_this.availableTracks[i].getTitle() + '</li>';
		}
		selHTML+='</ul>' +
				 '</div>';
		$j('#metaBox_'+_this.pe.id).append( selHTML );
		$j('.mvTsSelect').click(function(){			
			_this.applyTsSelect();
		});
	},
	applyTsSelect:function(){
		var _this = this;
		//update availableTracks
		$j('#mvtsel_' + this.pe.id + ' .mvTsSelect').each(function(){
			var track_id = $j(this).val();
			if(this.checked){
				//if not yet loaded now would be a good time
				if(! _this.availableTracks[ track_id ].loaded ){
					_this.loadAndDisplay( track_id);
				}else{
					_this.availableTracks[track_id].display=true;
					//display the named class:
					$j('#mmbody_'+_this.pe.id +' .track_'+track_id ).show();
				}
			}else{
				if(_this.availableTracks[track_id].display){
					_this.availableTracks[track_id].display=false;
					//hide unchecked				
					$j('#mmbody_'+_this.pe.id +' .track_'+track_id ).hide();
				}
			}
		});
		$j('#mvtsel_'+_this.pe.id).fadeOut("fast").remove();
	},
	monitor:function(){
		_this = this;
		//grab the time from the video object
		var cur_time = this.pe.currentTime ;
		if( cur_time!=0 ){
			var search_for_range = true;
			//check if the current transcript is already where we want:
			if($j('#mmbody_' + this.pe.id + ' .tt_scroll_highlight').length != 0){
				var curhl = $j('#mmbody_'+this.pe.id +' .tt_scroll_highlight').get(0);
				if(npt2seconds($j(curhl).attr('start') ) < cur_time &&
				   npt2seconds($j(curhl).attr('end') ) > cur_time){
					/*js_log('in range of current hl: ' +
						npt2seconds($j(curhl).attr('start')) +  ' to ' +  npt2seconds($j(curhl).attr('end')));
					*/
					search_for_range = false;
				}else{
					search_for_range = true;
					//remove the highlight from all:
					$j('#mmbody_'+this.pe.id +' .tt_scroll_highlight').removeClass('tt_scroll_highlight');
				}
			};
			/*js_log('search_for_range:'+search_for_range +  ' for: '+ cur_time);*/
			if( search_for_range ){
				//search for current time: add tt_scroll_highlight to clip
				// optimize:
				//  should do binnary search not iterative
				//  avoid jquery function calls do native loops
				$j('#mmbody_'+this.pe.id +' .mvtt').each(function(){
					if(npt2seconds($j(this).attr('start') ) < cur_time &&
					   npt2seconds($j(this).attr('end') ) > cur_time){
						_this.prevTimeScroll=cur_time;
						$j('#mmbody_'+_this.pe.id).animate({
							scrollTop: $j(this).get(0).offsetTop
						}, 'slow');
						$j(this).addClass('tt_scroll_highlight');
						//js_log('should add class to: ' + $j(this).attr('id'));
						//done with loop
						return false;
					}
				});
			}
		}
	},
	setAutoScroll:function( timer ){
		var _this = this;
		this.autoscroll = ( typeof timer=='undefined' )?this.autoscroll:timer;
		if(this.autoscroll){
			//start the timer if its not already running
			if(!this.scrollTimerId){
				this.scrollTimerId = setInterval('$j(\'#'+_this.pe.id+'\').get(0).textInterface.monitor()', 500);
			}
			//jump to the current position:
			var cur_time = parseInt (this.pe.currentTime );
			js_log('cur time: '+ cur_time);

			_this = this;
			var scroll_to_id='';
			$j('#mmbody_'+this.pe.id +' .mvtt').each(function(){
				if(cur_time > npt2seconds($j(this).attr('start'))  ){
					_this.prevTimeScroll=cur_time;
					if( $j(this).attr('id') )
						scroll_to_id = $j(this).attr('id');
				}
			});
			if(scroll_to_id != '')
				$j( '#mmbody_' + _this.pe.id ).animate( { scrollTop: $j('#'+scroll_to_id).position().top } , 'slow' );
		}else{
			//stop the timer
			clearInterval(this.scrollTimerId);
			this.scrollTimerId=0;
		}
	},
	getMenu:function(){
		var out='';
		var _this = this;
		//add in loading icon:
		var as_checked = (this.autoscroll)?'checked':'';
		out += '<div id="tt_mmenu_'+this.pe.id+'" class="ui-widget-header" style="font-size:.6em;position:absolute;top:0;height:30px;left:0px;right:0px;">';
		out += $j.btnHtml( gM('mwe-select_transcript_set'), 'tt-select', 'shuffle');
				
		if(_this.editlink!='')
			out+=' ' + $j.btnHtml(gM('mwe-improve_transcript'), 'tt-improve' );
		
		out+= '<input class="tt-scroll" type="checkbox" '+ as_checked + '>' + gM('mwe-auto_scroll');
		
		out+=' ' + $j.btnHtml(gM('mwe-close'), 'tt-close', 'circle-close');
		
		out+='</div>';
		return out;
	},
	doMenuBindings:function(){
	    var _this = this;
	    var mt = '#tt_mmenu_'+ _this.pe.id;
	    $j(mt + ' .tt-close').unbind().btnBind().click(function(){
	       $j( '#' + _this.pe.id).get(0).closeTextInterface();
	       return false;
	    });
	    $j(mt + ' .tt-select').unbind().btnBind().click(function(){
	       $j( '#' +  _this.pe.id).get(0).textInterface.getTsSelect();
	       return false;
	    });
	    $j(mt + ' .tt-scroll').click(function(){
	     	_this.setAutoScroll(this.checked);	     	
	     });
	    $j(mt + ' .tt-improve').unbind().btnBind().click(function(){	    	    	
	    	document.location.href =  _this.editlink;
	    });	    	    	    
	}
}

/* text format objects
*  @@todo allow loading from external lib set
*/
var timedTextObj = function( source ){
	//@@todo in the future we could support timed text in oggs if they can be accessed via javascript
	//we should be able to do a HEAD request to see if we can read transcripts from the file.
	switch( source.mime_type ){
		case 'text/cmml':
			this.lib = 'CMML';
		break;
		case 'text/srt':
		case 'text/x-srt':
			this.lib = 'SRT';
		break;
		default:
			js_log( source.mime_type + ' is not suported timed text fromat');
			return ;
		break;
	}
	//extend with the per-mime type lib:
	eval('var tObj = timedText' + this.lib + ';');
	for( var i in tObj ){
		this[ i ] = tObj[i];
	}
	return this.init( source );
}

//base timedText object
timedTextObj.prototype = {
	loaded: false,
	lib:null,
	display: false,
	textNodes:new Array(),
	init: function( source ){
		//copy source properties
		this.source = source;
		this.id = source.id;
	},
	getTitle:function(){
		return this.source.title;
	},
	getSRC:function(){
		return this.source.src;
	}
}

// Specific Timed Text formats:

timedTextCMML = {
	load: function( range, callback ){
		var _this = this;
		js_log('textCMML: loading track: '+ this.src);

		//:: Load transcript range ::
		var pcurl =  parseUri( _this.getSRC() );
		//check for urls without time keys:
		if( typeof pcurl.queryKey['t'] == 'undefined'){
			//in which case just get the full time req:
			do_request( this.getSRC(), function(data){
				_this.doParse( data );
				_this.loaded=true;
				callback();
			});
			return ;
		}
		//temporal urls: 
		var req_time = pcurl.queryKey['t'].split('/');
		req_time[0]=npt2seconds(req_time[0]);
		req_time[1]=npt2seconds(req_time[1]);
		if(req_time[1]-req_time[0]> _this.request_length){
			//longer than 5 min will only issue a (request 5 min)
			req_time[1] = req_time[0]+_this.request_length;
		}
		//set up request url:
		url = pcurl.protocol + '://' + pcurl.authority + pcurl.path +'?';
		$j.each(pcurl.queryKey, function(key, val){
			if( key != 't'){
				url+=key+'='+val+'&';
			}else{
				url+= 't=' + seconds2npt(req_time[0]) + '/' + seconds2npt(req_time[1]) + '&';
			}
		});
		do_request( url, function(data){
			js_log("load track clip count:" + data.getElementsByTagName('clip').length );
			_this.doParse( data );
			_this.loaded=true;
			callback();
		});
	},
	doParse: function(data){
		var _this = this;
		$j.each(data.getElementsByTagName('clip'), function(inx, clip){
			//js_log(' on clip ' + clip.id);
			var text_clip = {
				start: $j(clip).attr('start').replace('npt:', ''),
				end: $j(clip).attr('end').replace('npt:', ''),
				type_id: _this.id,
				id: $j(clip).attr('id')
			}
			$j.each( clip.getElementsByTagName('body'), function(binx, bn ){
				if(bn.textContent){
					text_clip.body = bn.textContent;
				}else if(bn.text){
					text_clip.body = bn.text;
				}
			});
			_this.textNodes.push( text_clip );
		});
	}
}
timedTextSRT = {
	load: function( range, callback ){
		var _this = this;
		js_log('textSRT: loading : '+ _this.getSRC() );
		if( _this.getSRC() ){
			do_request( _this.getSRC() , function(data){
				_this.doParse( data );
				_this.loaded=true;
				callback();
			});
		}else if( _this.source.apisrc ){
			do_api_req({
				'url' : _this.source.apisrc, 
				'data': {
					'titles': _this.source.titleKey,
					'prop':'revisions',
					'rvprop':'content'
				}
			},function(data){
				if(data && data.query && data.query.pages){
					for(var i in data.query.pages){
						var page = data.query.pages[i];
						if(page.revisions){
							for(var j in page.revisions){
								if( page.revisions[j]['*'] ){
									_this.doParse( page.revisions[j]['*'] );
									_this.loaded=true;
									callback();
								}
							}
						}
					}
				}
			});
		}
	},
	doParse:function( data ){
		//split up the transcript chunks:
		//strip any \r's
		var tc = data.split(/[\r]?\n[\r]?\n/);
		//pushing can take time
		for(var s=0; s < tc.length ; s++) {
			var st = tc[s].split('\n');
			if(st.length >=2) {
				var n = st[0];
				var i = st[1].split(' --> ')[0].replace(/^\s+|\s+$/g,"");
				var o = st[1].split(' --> ')[1].replace(/^\s+|\s+$/g,"");
				var t = st[2];
				if(st.length > 2) {
					for(j=3; j<st.length;j++)
						t += '\n'+st[j];
				}
				var text_clip = {
					"start": i,
					"end": o,
					"type_id": this.id,
					"id": this.id + '_' + n,
					"body": t
				}
				this.textNodes.push( text_clip );
			 }
		}
	}
};
