
loadGM({
	"select_transcript_set" : "Select layers",
	"auto_scroll" : "auto scroll",
	"close" : "close",
	"improve_transcript" : "Improve"
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
	init:function( parentEmbed ){
		   //init a new availableTracks obj:
		this.availableTracks={};
		//set the parent embed object:
		this.pe=parentEmbed;
		//parse roe if not already done:
		this.getTimedTextTracks();
	},
	//@@todo separate out data loader & data display
	getTimedTextTracks:function(){
		js_log("load timed text from roe: "+ this.pe.roe);
		var _this = this;
		//if roe not yet loaded do load it:
		if(this.pe.roe){
			if(!this.pe.media_element.addedROEData){
				js_log("load roe data!");
				$j('#mv_txt_load_'+_this.pe.id).show(); //show the loading icon
				do_request( _this.pe.roe, function(data)
				{
					//continue
					_this.pe.media_element.addROE(data);
					_this.getParseTimedText_rowReady();
				});
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
	getParseTimedText_rowReady: function (){
		var _this = this;
		//create timedTextObj
		var default_found=false;
		js_log("mv_txt_load_:SHOW mv_txt_load_");
		$j('#mv_txt_load_'+_this.pe.id).show(); //show the loading icon

		$j.each( this.pe.media_element.sources, function(inx, source){

			if( typeof source.id == 'undefined' || source.id == null ){
				source.id = 'tt_' + inx;
			}
			var tObj = new timedTextObj( source );
			//make sure its a valid timed text format (we have not loaded or parsed yet) : (
			if( tObj.lib != null ){
				js_log('adding Track: ' + source.id + ' to ' + _this.pe.id);
				_this.availableTracks[ source.id ] = tObj;
				//js_log( 'is : ' + source.id + ' default: ' + source.default );
				//display if requested:
				if( source['default'] == "true" ){
					//we did set at least one track by default tag
					default_found=true;
					js_log('do load timed text: ' + source.id );
					_this.loadAndDisplay( source.id );
				}else{
					//don't load the track and don't display
				}
			}
		});

		//no default clip found take the first_id
		if(!default_found){
			$j.each( _this.availableTracks, function(inx, sourceTrack){
				_this.loadAndDisplay( sourceTrack.id );
				default_found=true;
				//retun after loading first available
				return false;
			});
		}

		//if nothing found anywhere update the loading icon to say no tracks found
		if(!default_found)
			$j('#mv_txt_load_'+_this.pe.id).html( gM('no_text_tracks_found') );


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
		js_log("SHOULD ADD: "+ track_id + ' count:' +  _this.availableTracks[ track_id ].textNodes.length);

		//a flag to avoid checking all clips if we know we are adding to the end:
		_this.add_to_end_on_this_pass = false;

		//run clip adding on a timed interval to not lock the browser on large srt file merges (should use worker threads)
		var i =0;
		var track_id = track_id;
		var addNextClip = function(){
			var text_clip = _this.availableTracks[ track_id ].textNodes[i];
			_this.add_merge_text_clip(text_clip);
			i++;
			if(i < _this.availableTracks[ track_id ].textNodes.length){
				setTimeout(addNextClip, 1);
			}
		}
		addNextClip();
	},
	add_merge_text_clip: function( text_clip ){
		var _this = this;
		//make sure the clip does not already exist:
		if($j('#tc_'+text_clip.id).length==0){
			var inserted = false;
			var text_clip_start_time = npt2seconds( text_clip.start );

			var insertHTML = '<div id="tc_'+text_clip.id+'" ' +
				'start_sec="' + text_clip_start_time + '" ' +
				'start="'+text_clip.start+'" end="'+text_clip.end+'" class="mvtt tt_'+text_clip.type_id+'">' +
					'<div class="mvttseek" style="top:0px;left:0px;right:0px;height:20px;font-size:small">'+
						text_clip.start + ' to ' +text_clip.end+
					'</div>'+
					text_clip.body +
			'</div>';
			//js_log("ADDING CLIP: "  + text_clip_start_time + ' html: ' + insertHTML);
			if(!_this.add_to_end_on_this_pass){
				$j('#mmbody_'+this.pe.id +' .mvtt').each(function(){
					if(!inserted){
						//js_log( npt2seconds($j(this).attr('start')) + ' > ' + text_clip_start_time);
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
		//check if we have already loaded the menu/body:
		if($j('#tt_mmenu_'+this.pe.id).length==0){
			$j('#metaBox_'+this.pe.id).html(
				this.getMenu() +
				this.getBody()
			);
			this.doMenuBindings();
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
					gM('mwe-loading_txt')+'</span>' +
				'</div>';
	},
	getTsSelect:function(){
		var _this = this;
		js_log('getTsSelect');
		var selHTML = '<div id="mvtsel_' + this.pe.id + '" style="position:absolute;background:#FFF;top:30px;left:0px;right:0px;bottom:0px;overflow:auto;">';
		selHTML+='<b>' + gM('select_transcript_set') + '</b><ul>';
		//debugger;
		for(var i in _this.availableTracks){ //for in loop ok on object
			var checked = ( _this.availableTracks[i].display ) ? 'checked' : '';
			selHTML+='<li><input name="'+i+'" class="mvTsSelect" type="checkbox" ' + checked + '>'+
				_this.availableTracks[i].getTitle() + '</li>';
		}
		selHTML+='</ul>' +
					'<a href="#" onClick="document.getElementById(\'' + this.pe.id + '\').textInterface.applyTsSelect();return false;">'+gM('close')+'</a>'+
				'</div>';
		$j('#metaBox_'+_this.pe.id).append( selHTML );
	},
	applyTsSelect:function(){
		var _this = this;
		//update availableTracks
		$j('#mvtsel_'+this.pe.id+' .mvTsSelect').each(function(){
			if(this.checked){
				var track_id = this.name;
				//if not yet loaded now would be a good time
				if(! _this.availableTracks[ track_id ].loaded ){
					_this.loadAndDisplay( track_id);
				}else{
					_this.availableTracks[this.name].display=true;
					//display the named class:
					$j('#mmbody_'+_this.pe.id +' .tt_'+this.name ).fadeIn("fast");
				}
			}else{
				if(_this.availableTracks[this.name].display){
					_this.availableTracks[this.name].display=false;
					//hide unchecked
					$j('#mmbody_'+_this.pe.id +' .tt_'+this.name ).fadeOut("fast");
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
			if($j('#mmbody_'+this.pe.id +' .tt_scroll_highlight').length != 0){
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
		//add in loading icon:
		var as_checked = (this.autoscroll)?'checked':'';
		out+= '<div id="tt_mmenu_'+this.pe.id+'" class="ui-widget-header" style="font-size:.6em;position:absolute;top:0;height:30px;left:0px;right:0px;">' +
				$j.btnHtml(gM('select_transcript_set'), 'tt-select', 'shuffle');
		if(this.pe.media_element.linkback){
			out+=' ' + $j.btnHtml(gM('improve_transcript'), 'tt-improve', 'document', {href:this.pe.media_element.linkback, target:'_new'});
		}
		out+='<input onClick="document.getElementById(\''+this.pe.id+'\').textInterface.setAutoScroll(this.checked);return false;" ' +
				'type="checkbox" '+as_checked +'>'+gM('auto_scroll') + ' ' +
              $j.btnHtml(gM('close'), 'tt-close', 'circle-close');
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
	    //use hard-coded link:
	    $j(mt + ' .tt-improve').btnBind();
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
		do_request( _this.getSRC() , function(data){
			_this.doParse( data );
			_this.loaded=true;
			callback();
		});
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
