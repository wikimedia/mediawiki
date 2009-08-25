//set the dismissNativeWarn flag:
_global['dismissNativeWarn'] = false;

/* 
 * controlsBuilder:
 * 
 */
var ctrlBuilder = {
	height:29,
	supports:{
		  'options':true,				 
		  'borders':true			   
	},	
	getControls:function( embedObj ){	
		js_log('f:controlsBuilder:: opt:' + this.options);		
		this.id = (embedObj.pc)?embedObj.pc.pp.id:embedObj.id;
		this.available_width = embedObj.playerPixelWidth();
		//make pointer to the embedObj
		this.embedObj =embedObj;
		var _this = this;		
		for(var i in embedObj.supports){			
			_this.supports[i] = embedObj.supports[i];
		};
					
		//special case vars: 
		if( ( embedObj.roe || 
				(embedObj.media_element.timedTextSources && 
				embedObj.media_element.timedTextSources() ) 
			)  && embedObj.show_meta_link  )
			this.supports['closed_captions']=true;   
		
			
		//append options to body (if not already there)
		if($j('#mv_vid_options_'+ctrlBuilder.id).length==0)
			$j('body').append( this.components['mv_embedded_options'].o() );		
					
		var o='';	
		for( var i in this.components ){
			if( this.supports[i] ){
				if( this.available_width > this.components[i].w ){
					//special case with playhead don't add unless we have 60px
					if( i == 'play_head' && ctrlBuilder.available_width < 60 )
						continue;						
					o+=this.components[i].o();
					this.available_width -= this.components[i].w;
				}else{
					js_log('not enough space for control component:' + i);
				}
			}
		}		
		return o;
	},
	 /*
	 * addControlHooks
	 * to be run once controls are attached to the dom
	 */
	addControlHooks:function(embedObj){								
		//add in drag/seek hooks: 
		if(!embedObj.base_seeker_slider_offset &&  $j('#mv_seeker_slider_'+embedObj.id).get(0))
			embedObj.base_seeker_slider_offset = $j('#mv_seeker_slider_'+embedObj.id).get(0).offsetLeft;			  
		
		//js_log('looking for: #mv_seeker_slider_'+embedObj.id + "\n " +
		//		'start sec: '+embedObj.start_time_sec + ' base offset: '+embedObj.base_seeker_slider_offset);
		
		//add play hook: 
		$j('#mv_play_pause_button_' + embedObj.id).unbind().btnBind().click(function(){
			$j('#' + embedObj.id).get(0).play();
		})	
		
		//big_play_link_ play binding: 
		$j('#big_play_link_' + embedObj.id).unbind().click(function(){
			$j('#' + embedObj.id).get(0).play();
		});		
		
		//add recomend firefox if non-native playback:
		if( embedObj.doNativeWarningCheck() ){															
			$j('#dc_'+ embedObj.id).hover(
				function(){										
					if($j('#gnp_' + embedObj.id).length==0){
						$j(this).append('<div id="gnp_' + embedObj.id + '" class="ui-state-highlight ui-corner-all" ' +
							'style="position:absolute;display:none;background:#FFF;top:10px;left:10px;right:10px;">' +
							gM('mwe-for_best_experience') + 
						'<br><input id="ffwarn_'+embedObj.id+'" type=\"checkbox\">' + 
							gM('mwe-do_not_warn_again') + 
						'</div>');							
						$j('#ffwarn_'+embedObj.id).click(function(){
							if( $j(this).is(':checked') ){
								//set up a cookie for 7 days:
								$j.cookie('dismissNativeWarn', true, { expires: 5 });
								//set the current instance
								_global['dismissNativeWarn'] = true;
								$j('#gnp_' + embedObj.id).fadeOut('slow');
							}else{
								_global['adismissNativeWarn'] = false;
								$j.cookie('dismissNativeWarn', false);
							}
							
						});	
					}					
					if( ($j.cookie('dismissNativeWarn') !== true) &&
						_global['dismissNativeWarn'] === false  ){
						$j('#gnp_' + embedObj.id).fadeIn('slow');
					}
				},
				function(){
					$j('#gnp_' + embedObj.id).fadeOut('slow');
				}
			);
		}
		
		if( $j.browser.msie  &&  $j.browser.version <= 6){			
			$j('#big_play_link_' + embedObj.id).pngFix();
		}
		
		
		//captions binding:
		$j('#timed_text_'  + embedObj.id).unbind().btnBind().click(function(){
			$j('#' + embedObj.id).get(0).showTextInterface();
		});
		
		//options binding: 
		$j('#options_button_' + embedObj.id).unbind().btnBind().click(function(){
			$j('#' +embedObj.id).get(0).doOptionsHTML();
		});
				
		//fullscreen binding: 
		$j('#fullscreen_'+embedObj.id).unbind().btnBind().click(function(){
			$j('#' +embedObj.id).get(0).fullscreen();
		});				
		
		js_log(" should add slider binding: " + $j('#mv_play_head_'+embedObj.id).length) ;
		$j('#mv_play_head_'+embedObj.id).slider({
			range: "min",
			value: 0,
			min: 0,
			max: 1000,
			start: function(event, ui){
				var id = (embedObj.pc!=null)?embedObj.pc.pp.id:embedObj.id;
				embedObj.userSlide=true;
				$j('#big_play_link_'+id).fadeOut('fast');
				//if playlist always start at 0
				embedObj.start_time_sec = (embedObj.instanceOf == 'mvPlayList')?0:
								npt2seconds(embedObj.getTimeReq().split('/')[0]);	 
			},
			slide: function(event, ui) {									
				var perc = ui.value/1000;																																			
				embedObj.jump_time = seconds2npt( parseFloat( parseFloat(embedObj.getDuration()) * perc ) + embedObj.start_time_sec);	
				//js_log('perc:' + perc + ' * ' + embedObj.getDuration() + ' jt:'+  this.jump_time);
				embedObj.setStatus( gM('mwe-seek_to')+' '+embedObj.jump_time );	
				//update the thumbnail / frame 
				if(embedObj.isPlaying==false){
					embedObj.updateThumbPerc( perc );
				}
			},
			change:function(event, ui){
				//only run the onChange event if done by a user slide: 
				if(embedObj.userSlide){
					embedObj.userSlide=false;
					embedObj.seeking=true;
					//stop the monitor timer (if we can)
					if(embedObj.stopMonitor)				 
						embedObj.stopMonitor();			
							
					var perc = ui.value/1000;														  
					//set seek time (in case we have to do a url seek)				
					embedObj.seek_time_sec = npt2seconds( embedObj.jump_time, true );   
					js_log('do jump to: '+embedObj.jump_time + ' perc:' +perc + ' sts:' + embedObj.seek_time_sec);								
					embedObj.doSeek(perc);
				}
			}		  
		});
		//up the z-index of the default status indicator: 
		$j('#mv_play_head_'+embedObj.id + ' .ui-slider-handle').css('z-index', 4);
		$j('#mv_play_head_'+embedObj.id + ' .ui-slider-range').addClass('ui-corner-all').css('z-index', 2);
		//extended class list for jQuery ui themeing (we can probably refactor this with custom buffering highliter) 
		$j('#mv_play_head_'+embedObj.id).append( ctrlBuilder.getMvBufferHtml() );
				
		//videoOptions: 
		$j('#mv_vid_options_'+ctrlBuilder.id+' .vo_selection').click(function(){
			embedObj.selectPlaybackMethod();
			$j('#mv_vid_options_'+ctrlBuilder.id).hide();
			return false;
		});
		$j('#mv_vid_options_'+ctrlBuilder.id+' .vo_download').click(function(){
			embedObj.showVideoDownload();
			$j('#mv_vid_options_'+ctrlBuilder.id).hide();
			return false;
		})		
		$j('#mv_vid_options_'+ctrlBuilder.id+' .vo_showcode').click(function(){
			embedObj.showEmbedCode();
			$j('#mv_vid_options_'+ctrlBuilder.id).hide();
			return false;
		});		
			
		//volume binding:
		var hoverOverDelay=false;
		$j('#volume_control_'+embedObj.id).unbind().btnBind().click(function(){
			$j('#' +embedObj.id).get(0).toggleMute();
		}).hover(
			function(){			
				$j('#vol_container_' + embedObj.id).addClass('vol_container_top');
				//set to "below" if playing and embedType != native
				if(embedObj && embedObj.isPlaying && embedObj.isPlaying() && !embedObj.supports['overlays']){
					$j('#vol_container_' + embedObj.id).removeClass('vol_container_top').addClass('vol_container_below');
				}
				
				$j('#vol_container_' + embedObj.id).fadeIn('fast');
				hoverOverDelay = true;
			},
			function(){		
				hoverOverDelay= false;		
				setTimeout(function doHideVolume(){
					if(!hoverOverDelay){
						$j('#vol_container_' + embedObj.id).fadeOut('fast');
					}
				}, 500);	
			}
		);
		//Volumen Slider
		$j('#volume_bar_'+embedObj.id).slider({
			orientation: "vertical",			
			range: "min",
			value: 80,
			min: 0,
			max: 100,			
			slide: function(event, ui) {									
				var perc = ui.value/100;		
				//js_log('update volume:' + perc);
				embedObj.updateVolumen(perc);								
			},
			change:function(event, ui){
				var perc = ui.value/100;	
				if (perc==0) {
					$j('#volume_control_'+embedObj.id + ' span').removeClass('ui-icon-volume-on').addClass('ui-icon-volume-off');								
				}else{					
					$j('#volume_control_'+embedObj.id + ' span').removeClass('ui-icon-volume-off').addClass('ui-icon-volume-on');
				}
				//only run the onChange event if done by a user slide: 
				if(embedObj.userSlide){
					embedObj.userSlide=false;
					embedObj.seeking=true;							
					var perc = ui.value/100;														  
					embedObj.updateVolumen(perc);					
				}
			}		  
		});					
		
	},	
	getMvBufferHtml:function(){
		return '<div class="ui-slider-range ui-slider-range-min ui-widget-header ' +
				'ui-state-highlight ui-corner-all '+
				'mv_buffer" style="width:0px;height:100%;z-index:1;top:0px" />';
	},
	components:{
		'borders':{
			'w':8,
			'o':function(){
				return	'';
			}
		},
		'mv_embedded_options':{
			'w':0,
			'o':function(){
				var o= '<div id="mv_vid_options_'+ctrlBuilder.id+'" class="videoOptions">'+
				'<div class="videoOptionsTop"></div>'+
				'<div class="videoOptionsBox">'+
				'<div class="block">'+
					'<h6>Video Options</h6>'+
				'</div>'+
					'<div class="block">'+
						'<p class="short_match vo_selection"><a href="#"><span>'+gM('mwe-chose_player')+'</span></a></p>'+
						'<p class="short_match vo_download"><a href="#"><span>'+gM('mwe-download')+'</span></a></p>'+
						'<p class="short_match vo_showcode"><a href="#"><span>'+gM('mwe-share')+'</span></a></p>';
					
					//link to the stream page if we are not already there: 
					if( ctrlBuilder.embedObj.roe && typeof mv_stream_interface == 'undefined' )
						o+='<p class="short_match"><a href="javascript:$j(\'#'+ctrlBuilder.id+'\').get(0).doLinkBack()"><span><strong>Source Page</strong></span></a></p>';
											
				o+='</div>'+  
				'</div><!--videoOptionsInner-->' +   
					'<div class="videoOptionsBot"></div>' +   
				'</div><!--videoOptions-->';
				return o;
			}
		},
		'fullscreen':{
			'w':20,
			'o':function(){
				return '<div title="' + gM('mwe-player_fullscreen') + '" id="fullscreen_'+ctrlBuilder.id+'" class="ui-state-default ui-corner-all ui-icon_link rButton"><span class="ui-icon ui-icon-arrow-4-diag"></span></div>'
			}
		},
		'options':{
			'w':26,
			'o':function(){
				return '<div title="'+ gM('mwe-player_options') + '" id="options_button_'+ctrlBuilder.id+'" class="ui-state-default ui-corner-all ui-icon_link rButton"><span class="ui-icon ui-icon-wrench"></span></div>';						 
			}
		},
		'pause':{
			'w':24,
			'o':function(){
				return '<div title="' + gM('mwe-play_clip') + '" id="mv_play_pause_button_' + ctrlBuilder.id + '" class="ui-state-default ui-corner-all ui-icon_link lButton"><span class="ui-icon ui-icon-play"/></div>';
			}
		},
		'closed_captions':{
			'w':23,
			'o':function(){
				return '<div title="' + gM('mwe-closed_captions') + '" id="timed_text_'+ctrlBuilder.id+'" class="ui-state-default ui-corner-all ui-icon_link rButton"><span class="ui-icon ui-icon-comment"></span></div>'
			}			
		},
		'volume_control':{
			'w':23,
			'o':function(){
					return '<div title="' + gM('mwe-volume_control') + '" id="volume_control_'+ctrlBuilder.id+'" class="ui-state-default ui-corner-all ui-icon_link rButton">' +
								'<span class="ui-icon ui-icon-volume-on"></span>' +
								'<div style="position:absolute;display:none;" id="vol_container_'+ctrlBuilder.id+'" class="vol_container ui-corner-all">' +
									'<div class="volume_bar" id="volume_bar_' + ctrlBuilder.id + '"></div>' +
								'</div>'+
							'</div>';														
			}
		},
		'time_display':{
			'w':90,
			'o':function(){
				return '<div id="mv_time_'+ctrlBuilder.id+'" class="ui-widget time">' + ctrlBuilder.embedObj.getTimeReq() + '</div>';
			}
		},
		'play_head':{
			'w':0, //special case (takes up remaining space) 
			'o':function(){
				return '<div class="play_head" id="mv_play_head_' + ctrlBuilder.id + '" style="width: ' + ( ctrlBuilder.available_width - 30 ) + 'px;"></div>';
			}
		}										
	}	
}