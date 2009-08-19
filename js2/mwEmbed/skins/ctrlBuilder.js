//set the dismissNativeWarn flag:
_global['dismissNativeWarn'] = false;

var ctrlBuilder = {
	height:29,
	supports:{
		  'options':true,
		  'borders':true
	},
	menu_items:[
		'playerselect',
		'download',
		'share',  
		'credits',
	],	
	default_menu_item:'download',
	getControls:function( embedObj ){
		js_log('f:controlsBuilder:: opt:');
		this.id = (embedObj.pc)?embedObj.pc.pp.id:embedObj.id;
		this.available_width = embedObj.playerPixelWidth();
		//make pointer to the embedObj
		this.embedObj = embedObj;
		var _this = this;
		for(var i in embedObj.supports){
			_this.supports[i] = embedObj.supports[i];
		};

		//check for close_captions tracks:
		if( ( embedObj.roe ||  embedObj.timedTextSources() )
			&& embedObj.show_meta_link  )
			this.supports['closed_captions']=true;

		var o='';
		//get the length of the play_head
		this.player_head_length =  embedObj.playerPixelWidth();		
		for( var i in this.components ){
			if( this.supports[i] ){
				if( i != 'play_head'){
					this.player_head_length -= this.components[i].w;
				}
			}
		}		
		for(var i in this.components){
			if( this.supports[i] ){
				if( this.available_width > this.components[i].w ){
					//special case with playhead don't add unless we have 60px
					if( i == 'play_head' && this.player_head_length < 60 )
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
	addControlHooks:function( embedObj ){
		//add in drag/seek hooks:
		if(!embedObj.base_seeker_slider_offset &&  $j('#mv_seeker_slider_'+embedObj.id).get(0))
			embedObj.base_seeker_slider_offset = $j('#mv_seeker_slider_'+embedObj.id).get(0).offsetLeft;

		//js_log('looking for: #mv_seeker_slider_'+embedObj.id + "\n " +
		//		'start sec: '+embedObj.start_time_sec + ' base offset: '+embedObj.base_seeker_slider_offset);

		var $tp=$j('#' + embedObj.id);

		//@todo: which object is being play()'d (or whatever) ?
		//We select the element to attach the event to this way:
		//$tp.find('.ui-icon-play').parent().click(function(){alert(0)}); or we can give the button itself a class - probably better.

		//add play hook for play-btn and large_play_button
		$tp.find('.play-btn,.play-btn-large').unbind().btnBind().click(function(){
			$j('#' + embedObj.id).get(0).play();
		})
		//add recomend firefox if non-native playback:
		if( embedObj.doNativeWarningCheck() ){
			$j('#dc_'+ embedObj.id).hover(
				function(){
					if($j('#gnp_' + embedObj.id).length==0){
						$j(this).append('<div id="gnp_' + embedObj.id + '" class="ui-state-highlight ui-corner-all" ' +
							'style="position:absolute;display:none;background:#FFF;top:10px;left:10px;right:10px;height:60px;">' +
							gM('mv_for_best_experience') +
						'<br><input id="ffwarn_'+embedObj.id+'" type=\"checkbox\">' +
							gM('mv_do_not_warn_again') +
						'</div>');
						$j('#ffwarn_'+embedObj.id).click(function(){
							if( $j(this).is(':checked') ){
								//set up a cookie for 5 days:
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
		$j('#fullscreen_' + embedObj.id).unbind().btnBind().click(function(){
			$j('#' +embedObj.id).get(0).fullscreen();
		});

		js_log(" should add slider binding: " + $j('#mv_play_head_'+embedObj.id).length) ;
//		$j('#mv_play_head_'+embedObj.id).slider({
		$tp.find( '.j-scrubber' ).slider({
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
				embedObj.setStatus( gM('seek_to')+' '+embedObj.jump_time );
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
		//@todo: identify problem with volume button jumping...
		$tp.find('.k-volume-slider').slider({
			range: "min",
			value: 80,
			min: 0,
			max: 100,
                        slide: function(event, ui) {
                                 embedObj.updateVolumen(ui.value/100);
                        },
			change: function(event, ui){
				var level = ui.value/100;
				if (level==0) {
					$tp.find('.k-volume span').addClass('ui-icon-volume-off');
				}else{
					$tp.find('.k-volume span').removeClass('ui-icon-volume-off');
				}
				//only run the onChange event if done by a user slide:
				if(embedObj.userSlide){
					embedObj.userSlide=false;
					embedObj.seeking=true;
//					var perc = ui.value/100;
					embedObj.updateVolumen(level);
				}
			}
		});
		//up the z-index of the default status indicator:
//		$j('#mv_play_head_'+embedObj.id + ' .ui-slider-handle').css('z-index', 4);
//		$j('#mv_play_head_'+embedObj.id + ' .ui-slider-range').addClass('ui-corner-all').css('z-index', 2);
		//extended class list for jQuery ui themeing (we can probably refactor this with custom buffering highliter)
		$j('#' + embedObj.id + ' .j-scrubber').prepend( ctrlBuilder.getMvBufferHtml() );


		//adds options and bindings: (we do this onClick for faster vidoe tag startup times)  
		var addMvOptions = function(){
			if($j('#' + embedObj.id + ' .k-menu').length != 0 )
				return false;
				
			$j('#' + embedObj.id).prepend( ctrlBuilder.components['mv_embedded_options'].o( embedObj ) );
			
			//by default its hidden:
   			$tp.find('.k-menu').hide();
   			
   			//output menu-items: 
   			for(i=0; i < ctrlBuilder.menu_items.length ; i++){   				
		        $tp.find('.k-' +  ctrlBuilder.menu_items[i] + '-btn').click(function(){ 
		        	var mk = $j(this).attr('rel');
		        	$target = $j('#' + embedObj.id  + ' .menu-'+mk).hide();		        	
		        	//gennerate the menu html not already done:
		        	if( $target.children().length == 0 ){
						//call the function show{Menuitem} with target:	        		
						embedObj['show' + mk.charAt(0).toUpperCase() + mk.substring(1)](
							$j('#' + embedObj.id + ' .menu-'+mk)
						);
		        	}		        			        
		        	//slide out the others 
		        	 $j('#' + embedObj.id  + ' .menu-screen').hide();
		        	 $target.fadeIn("fast");
					//don't follow the # link								
		            return false;
				});	
   			}			
		}	
   		
   		//options menu display:			
       	$tp.find('.k-options').click(function(){      
       		if($j('#' + embedObj.id + ' .k-menu').length == 0 )
       			addMvOptions();
       		//set up the text and menu:       			 					
       		var $ktxt = $j(this).find('.ui-icon-k-menu');
       		var $kmenu = $tp.find('.k-menu');
			if( $kmenu.is(':visible') ){
				$kmenu.fadeOut("fast",function(){
       		 		$ktxt.html ( gM('menu_btn') );
				});
				$tp.find('.play-btn-large').fadeIn('fast');
			}else{
       			$kmenu.fadeIn("fast", function(){
       				$ktxt.html ( gM('close_btn') );
       			});
       			$tp.find('.play-btn-large').fadeOut('fast');
			}
        });	

		//volume binding:
		$tp.find('.k-volume').unbind().btnBind().click(function(){
			$tp.toggleMute();
		});

		var hoverOverDelay=false;
		/*$j('#volume_control_'+embedObj.id).unbind().btnBind().click(function(){
			$j('#' +embedObj.id).get(0).toggleMute();
		});
                .hover(
			function(){
				$j('#vol_container_' + embedObj.id).addClass('vol_container_top');
				//set to "below" if playing and embedType != native
				if(embedObj && embedObj.isPlaying() && !embedObj.supports['overlays']){
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
		});*/

	},
	getMvBufferHtml:function(){
		return '<div class="ui-slider-horizontal ui-corner-all ui-slider-buffer" />';
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
			'o':function( embedObj ){
				var o= '' +
				'<div class="k-menu ui-widget-content" ' +
					'style="width:' + embedObj.playerPixelWidth() + 'px; height:' + embedObj.playerPixelHeight() + 'px;">' +
						'<ul class="k-menu-bar">';						
							//output menu item containers: 
							for(i=0; i < ctrlBuilder.menu_items.length; i++){		
								var mk = ctrlBuilder.menu_items[i];				
								o+= '<li class="k-' + mk + '-btn" rel="' + mk + '">' +
										'<a href="#" title="' + gM( mk ) +'">' + gM( mk ) +'</a></li>';
							}							
						o+='</ul>' +
						//we have to substract the width of the k-menu-bar
						'<div class="k-menu-screens" style="width:' + ( embedObj.playerPixelWidth() -75) +
							'px; height:' + (embedObj.playerPixelHeight() - ctrlBuilder.height) + 'px;">';
						
						//output menu item containers: 
						for(i=0; i < ctrlBuilder.menu_items.length; i++){							
							o+= '<div class="menu-screen menu-' + ctrlBuilder.menu_items[i] + '"></div>';
						}													 				
						'</div>' +
					'</div>';
				return o;
			}
		},
		'pause':{
			'w':28, //28 147
			'o':function(){
				return '<button class="play-btn ui-state-default ui-corner-all" title="' +
					gM('play_clip') + '" ><span class="ui-icon ui-icon-play"></span></button>'
			}
		},
		'play_head':{  // scrubber
			'w':0, //special case (takes up remaining space)
			'o':function(){
				return '<div class="ui-slider ui-slider-horizontal ui-corner-all j-scrubber"' +
						' style="width:' + ( ctrlBuilder.player_head_length - 30 ) + 'px;"></div>'
			}
		},
		'time_display':{
			'w':36,
			'o':function(){
				return '<div class="k-timer">' + seconds2npt ( ctrlBuilder.embedObj.getDuration() ) + '</div>';
			}
		},
		'volume_control':{
			'w':40,
			'o':function(){
				return '<button class="ui-state-default ui-corner-all k-volume">' +
							'<span class="ui-icon ui-icon-volume-on"></span>' +
						'</button>' +
						'<div class="ui-slider ui-slider-horizontal k-volume-slider"></div>';

				//vertical volume control:
				/* return '<div title="' + gM('volume_control') + '" id="volume_control_'+ctrlBuilder.id+'" class="ui-state-default ui-corner-all ui-icon_link rButton">' +
					'<span class="ui-icon ui-icon-volume-on"></span>' +
					'<div style="position:absolute;display:none;" id="vol_container_'+ctrlBuilder.id+'" class="vol_container ui-corner-all">' +
						'<div class="volume_bar" id="volume_bar_' + ctrlBuilder.id + '"></div>' +
						'</div>'+
					'</div>';
				*/
			}
		},
		'closed_captions':{
			'w':24,
			'o':function(){
				return '<div title="' + gM('closed_captions') + '" id="timed_text_' + ctrlBuilder.id +'" ' +
							'class="ui-state-default ui-corner-all ui-icon_link rButton">' +
						'<span class="ui-icon ui-icon-comment"></span></div>';
			}
		},
		'fullscreen':{
			'w':24,
			'o':function(){
				return '<button class="ui-state-default ui-corner-all k-fullscreen" title="' + gM('player_fullscreen') + '">' +
						'<span class="ui-icon ui-icon-arrow-4-diag"></span></button>'
			}
		},
		'options':{
			'w':50,
			'o':function(){
				return '<button class="ui-state-default ui-corner-bl k-options" title="'+ gM('player_options') + '" >' +
							'<span class="ui-icon ui-icon-k-menu">'+ gM('menu_btn') + '</span>'
						'</button>'
			}
		}
	}
}
