//native embed library:
var nativeEmbed = {
	instanceOf:'nativeEmbed',
	canPlayThrough:false,
	grab_try_count:0,
	onlyLoadFlag:false,	
	urlAppend:'',
	supports: {
		'play_head':true, 
		'pause':true,		 
		'fullscreen':false, 
		'time_display':true, 
		'volume_control':true,
		
		'overlays':true,
		'playlist_swap_loader':true //if the object supports playlist functions		
   },
	getEmbedHTML : function (){					
		var embed_code =  this.getEmbedObj();
		js_log("embed code: " + embed_code)				
		setTimeout('$j(\'#' + this.id + '\').get(0).postEmbedJS()', 150);
		return this.wrapEmebedContainer( embed_code);		
	},
	getEmbedObj:function(){
		//we want to let mv_embed handle the controls so notice the absence of control attribute
		// controls=false results in controls being displayed: 
		//http://lists.whatwg.org/pipermail/whatwg-whatwg.org/2008-August/016159.html		
		js_log("native play url:" + this.getSrc() + ' start_offset: '+ this.start_ntp + ' end: ' + this.end_ntp);
		var eb = '<video ' +
					'id="' + this.pid + '" ' +
					'style="width:' + this.width+'px;height:' + this.height + 'px;" ' +
					'width="' + this.width + '" height="'+this.height+'" '+
					   'src="' + this.getSrc() + '" ';
					   
		/*if(!this.onlyLoadFlag)
			eb+='autoplay="true" ';*/
			
		//continue with the other attr:						
		eb+=		'oncanplaythrough="$j(\'#'+this.id+'\').get(0).oncanplaythrough();return false;" ' +
					   'onloadedmetadata="$j(\'#'+this.id+'\').get(0).onloadedmetadata();return false;" ' + 
					   'loadedmetadata="$j(\'#'+this.id+'\').get(0).onloadedmetadata();return false;" ' +
					   'onprogress="$j(\'#'+this.id+'\').get(0).onprogress( event );return false;" '+
					   'onended="$j(\'#'+this.id+'\').get(0).onended();return false;" >' +
			'</video>';
		return eb;
	},
	//@@todo : loading progress	
	postEmbedJS:function(){
		var _this = this;		
		js_log("f:native:postEmbedJS:");		
		this.getVID();
		var doActualPlay= function(){
			js_log("doActualPlay ");
			_this.vid.play();
		}
		if(typeof this.vid != 'undefined'){			
			//always load the media:
			if( this.onlyLoadFlag ){ 
				this.vid.load();
			}else{	
				this.vid.load();
				setTimeout(doActualPlay, 500);				 				
			}							
			setTimeout('$j(\'#'+this.id+'\').get(0).monitor()',100);		
		}else{
			js_log('could not grab vid obj trying again:' + typeof this.vid);
			this.grab_try_count++;
			if(	this.grab_count == 10 ){
				js_log(' could not get vid object after 10 tries re-run: getEmbedObj()' ) ;						
			}else{
				setTimeout('$j(\'#'+this.id+'\').get(0).postEmbedJS()',100);
			}			
		}
	},	
	doSeek:function(perc){				
		//js_log('native:seek:p: ' + perc+ ' : '  + this.supportsURLTimeEncoding() + ' dur: ' + this.getDuration() + ' sts:' + this.seek_time_sec );		
		//@@todo check if the clip is loaded here (if so we can do a local seek)
		if( this.supportsURLTimeEncoding() || !this.vid){			
			//make sure we could not do a local seek instead:
			if( perc < this.bufferedPercent && this.vid.duration && !this.didSeekJump ){
				js_log("do local seek " + perc + ' is already buffered < ' + this.bufferedPercent);
				this.doNativeSeek(perc);
			}else{
				this.parent_doSeek(perc);
			}			
		}else if(this.vid && this.vid.duration ){	   
			//(could also check bufferedPercent > perc seek (and issue oggz_chop request or not) 
			this.doNativeSeek( perc );	
		}else{
			this.doPlayThenSeek( perc )
		}				  
	},	
	doNativeSeek:function(perc){	
		this.seek_time_sec=0;			 
		this.vid.currentTime = perc * this.vid.duration;		
		this.parent_monitor();	
	},
	doPlayThenSeek:function(perc){
		js_log('native::doPlayThenSeek::');
		var _this = this;
		this.play();
		var rfsCount = 0;
		var readyForSeek = function(){
			_this.getVID();		
			//if we have duration then we are ready to do the seek
			if(this.vid && this.vid.duration){
				_this.doSeek(perc);
			}else{			
				//try to get player for 10 seconds: 
				if( rfsCount < 200 ){
					setTimeout(readyForSeek, 50);
					rfsCount++;
				}else{
					js_log('error:doPlayThenSeek failed');
				}
			}
		}
		readyForSeek();
	},
	setCurrentTime: function(pos, callback){
		var _this = this;
		this.getVID();
		if(!this.vid) {			
			this.load();
			var loaded = function(event) {
				js_log('native:setCurrentTime (after load): ' + pos + ' :  dur: ' + this.getDuration());
				_this.vid.currentTime = pos;
				var once = function(event) { 
					callback();
					_this.vid.removeEventListener('seeked', once, false) 
				};
				_this.vid.addEventListener('seeked', once, false);
				_this.removeEventListener('loadedmetadata', loaded, false);
			};
			_this.addEventListener('loadedmetadata', loaded, false);
		} else {
			//js_log('native:setCurrentTime: ' + pos + ' : '  + this.supportsURLTimeEncoding() + ' dur: ' + this.getDuration() + ' sts:' + this.seek_time_sec );
			_this.vid.currentTime = pos;
			var once = function(event) { 
				callback(); 
				_this.vid.removeEventListener('seeked', once, false) 
			};
			_this.vid.addEventListener('seeked', once, false);
		}
	},
	monitor : function(){
		this.getVID(); //make shure we have .vid obj
		if(!this.vid){
			js_log('could not find video embed: '+this.id + ' stop monitor');
			this.stopMonitor();			
			return false;
		}		
		//don't update status if we are not the current clip (playlist leekage?) .. should move to playlist overwite of monitor? 
		if(this.pc){
			if(this.pc.pp.cur_clip.id != this.pc.id)
				return true;
		}								
				
		//update currentTime				
		this.currentTime = this.vid.currentTime;		
		this.addPresTimeOffset();
		//js_log('currentTime:' + this.currentTime);
		//js_log('this.currentTime: ' + this.currentTime );
		//once currentTime is updated call parent_monitor
		this.parent_monitor();
	},	
	getSrc:function(){
		var src = this.parent_getSrc();
		if(  this.urlAppend != '')
			return src + ( (src.indexOf('?')==-1)?'?':'&') + this.urlAppend;
		return src;
	},
	/*
	 * native callbacks for the video tag: 
	 */
	oncanplaythrough : function(){		
		//js_log('f:oncanplaythrough');
		this.getVID();
		if( ! this.paused )
			this.vid.play();
	},
	onloadedmetadata: function(){
		this.getVID();
		js_log('f:onloadedmetadata metadata ready (update duration)');	
		//update duration if not set (for now trust the getDuration more than this.vid.duration		
		if( this.getDuration()==0  &&  !isNaN( this.vid.duration )){
			js_log('updaed duration via native video duration: '+ this.vid.duration)
			this.duration = this.vid.duration;
		}
	},
	onprogress: function(e){		
		this.bufferedPercent =   e.loaded / e.total;
		//js_log("onprogress:" +e.loaded + ' / ' +  (e.total) + ' = ' + this.bufferedPercent);
	},
	onended:function(){	  
		var _this = this	 
		this.getVID();	 
		js_log('native:onended:' + this.vid.currentTime + ' real dur:' +  this.getDuration() );
		//if we just started (under 1 second played) & duration is much longer.. don't run onClipDone just yet . (bug in firefox native sending onended event early) 
		if(this.vid.currentTime  < 1 && this.getDuration() > 1 && this.grab_try_count < 5){			
			js_log('native on ended called with time:' + this.vid.currentTime + ' of total real dur: ' +  this.getDuration() + ' attempting to reload src...');
			var doRetry = function(){
				_this.urlAppend = 'retry_src=' + _this.grab_try_count; 
				_this.doEmbedHTML();
				_this.grab_try_count++;
			}
			setTimeout(doRetry, 100);			
		}else{
			this.onClipDone();
		}
	},	
	pause : function(){		
		this.getVID();
		this.parent_pause(); //update interface		
		if(this.vid){			
			this.vid.pause();
		}
		//stop updates: 
		this.stopMonitor();
	},
	play:function(){
		this.getVID();
		this.parent_play(); //update interface
		if( this.vid ){
			this.vid.play();
			//re-start the monitor: 
			this.monitor();
		}
	},
	toggleMute:function(){
		this.parent_toggleMute();
		this.getVID();
		if(this.vid)
			this.vid.muted = this.muted;
	},	
	updateVolumen:function(perc){
		this.getVID();		
		if(this.vid)
			this.vid.volume = perc;			    
	},	   	
    getVolumen:function(){
		this.getVID();		
		if(this.vid)
			return this.vid.volume;			   
	},	
	getNativeDuration:function(){
		if(this.vid)
			return this.vid.duration;
	},
	load:function(){
		this.getVID();
		if( !this.vid ){
			//no vid loaded
			js_log('native::load() ... doEmbed');
			this.onlyLoadFlag = true;
			this.doEmbedHTML();
		}else{
			//won't happen offten
			this.vid.load();
		}
	},
	// get the embed vlc object 
	getVID : function (){
		this.vid = $j('#'+this.pid).get(0);		  
	},  
	/* 
	 * playlist driver	  
	 * mannages native playlist calls		  
	 */	
};
