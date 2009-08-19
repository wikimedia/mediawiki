/*
	hanndles clip edit controls
	'inoutpoints':0,	//should let you set the in and out points of clip
	'panzoom':0,		 //should allow setting keyframes and tweenning modes
	'overlays':0,		 //should allow setting "locked to clip" overlay tracks
	'audio':0			//should allow controlling the audio volume (with keyframes)
*/
//set gMsg object:
loadGM({
	"mv_crop" : "Crop image",
	"mv_apply_crop" : "Apply crop to image",
	"mv_reset_crop" : "Reset crop",
	"mv_insert_image_page" : "Insert into page",
	"mv_insert_into_sequence" : "Insert into sequence",
	"mv_preview_insert" : "Preview insert",
	"mv_cancel_image_insert" : "Cancel insert",
	"sc_fileopts" : "Clip detail edit",
	"sc_inoutpoints" : "Set in-out points",
	"sc_overlays" : "Overlays",
	"sc_audio" : "Audio control",
	"sc_duration" : "Duration",
	"mv_template_properties" : "Template properties",
	"mv_custom_title" : "Custom title",
	"mv_edit_properties" : "Edit properties",
	"mv_other_properties" : "Other properties",
	"mv_resource_page" : "Resource page : ",
	"mv_set_in_out_points" : "Set in-out points",
	"mv_start_time" : "Start time",
	"mv_end_time" : "End time",
	"mv_preview_inout" : "Preview\/play in-out points"
});

var default_clipedit_values = {
	'rObj':	null,		 // the resource object
	'clip_disp_ct':null,//target clip disp
	'control_ct':null,	 //control container
	'media_type': null, //media type
	'parent_ct': null,	 //parent container

	'p_rsdObj': null,	//parent remote search object
	'p_seqObj': null,	 //parent sequence Object

	'controlActionsCb' : null, //the object that configures control Action callbacks

	'edit_action': null, //the requested edit action
	'profile': 'inpage' //the given profile either "inpage" or "sequence"
}
var mvClipEdit = function(iObj) {
	return this.init(iObj);
};
mvClipEdit.prototype = {

	selTool:null, //selected tool
	crop: null, //the crop values
	base_img_src:null,

	init:function( iObj){
		//init object:
		for(var i in default_clipedit_values){
			if( iObj[i] ){
				this[i] = iObj[i];
			}
		}

		//if media type was not supplied detect for resource if possible:
		//@@todo more advanced detection.
		if(!this.media_type && this.rObj && this.rObj.type ){
			if( this.rObj.type.indexOf("image/") === 0){
				this.media_type = 'image';
			}else if( this.rObj.type.indexOf("video/") === 0){
				this.media_type = 'video';
			}else if( this.rObj.type.indexOf("text/") === 0){
				this.media_type = 'template';
			}
		}

		//display control:
		if(this.profile == 'sequence'){
			this.doEditTypesMenu();
			this.doDisplayEdit();
		}else{
			//check the media_type:
			//js_log('mvClipEdit:: media type:' + this.media_type + ' base width: ' + this.rObj.width + ' bh: ' + this.rObj.height);
			//could seperate out into media Types objects for now just call method
			if(this.media_type == 'image'){
				this.setUpImageCtrl();
			}else if(this.media_type=='video'){
				this.setUpVideoCtrl();
			}
		}
	},

	//master edit types object:
	//maybe we should refactor these into their own classes
	//more refactor each media type should be its own class inheriting the shared baseEditType object
	edit_types:{
		'duration':{
			'media':['image','template'],
			'doEdit':function( _this, target ){
				//(_this is a smilClip instance)
				//do clock mouse scroll duration editor
				$j(target).html(
						'<label for="ce_dur">Duration: </label>' +
						'<input name="ce_dur" tabindex="1" maxlength="11" value="'+
							seconds2npt( _this.rObj.getDuration() )+
							'" size="10"/>'+
					'</div>'
				).children("input[name='ce_dur']").change(function(){
					 js_log("update duration:" + $j(this).val() );
					 //update the parent sequence object:
					 _this.rObj.dur = smilParseTime( $j(this).val() );
					 //update the playlist:
					 _this.p_seqObj.do_refresh_timeline( true );
				});

			}
		},
		'inoutpoints':{
			'media':['video'],
			'doEdit':function( _this, target ){
				//do clock mouse scroll duration editor
				var end_ntp = ( _this.rObj.embed.end_ntp) ? _this.rObj.embed.end_ntp : _this.rObj.embed.getDuration();
				if(!end_ntp)
					end_ntp = seconds2npt( _this.rObj.dur );

				var start_ntp = (_this.rObj.embed.start_ntp) ? _this.rObj.embed.start_ntp : seconds2npt( 0 );
				if(!start_ntp)
					seconds2npt( 0 );

				$j(target).html(
					_this.getSetInOutHtml({
						'start_ntp'	: start_ntp,
						'end_ntp'	: end_ntp
					})
				);
				_this.setInOutBindings();
			}
		},
		'fileopts':{
			'media':['image','video','template'],
			'doEdit':function(_this, target ){
				//if media type is template we have to query to get its URI to get its paramaters
				if(_this.media_type == 'template' && !_this.rObj.tVars){
					mv_set_loading('#sub_cliplib_ic');
					var reqObj ={	'action':'query',
									'prop':'revisions',
									'titles': _this.rObj.uri,
									'rvprop':'content'
								};
					//get the interface uri from the plObject
					var api_url = _this.p_seqObj.plObj.interface_url;
					//first check
					do_api_req( {
						'data':reqObj,
						'url':api_url
						}, function(data){
							if(typeof data.query.pages == 'undefined')
								return _this.doEditOpts(target);
							for(var i in data.query.pages){
								var page = data.query.pages[i];
								if(!page['revisions'] || !page['revisions'][0]['*']){
									return _this.doEditOpts(target);
								}else{
									var template_rev = page['revisions'][0]['*'];
								}
							}

							//do a regular ex to get the ~likely~ template values
							//(of course this sucks)
							//but maybe this will make its way into the api sometime soon to support wysiwyg type editors
							//idealy it would expose a good deal of info about the template params
							js_log('matching against: ' + template_rev);
							var tempVars = template_rev.match(/\{\{\{([^\}]*)\}\}\}/gi);
							//clean up results:
							_this.rObj.tVars = new Array();
							for(var i=0; i < tempVars.length; i++){
								var tvar = tempVars[i].replace('{{{','').replace('}}}','');
								//strip anything after a |
								if(tvar.indexOf('|') != -1){
									tvar = tvar.substr(0, tvar.indexOf('|'));
								}
								//check for duplicates:
								var do_add=true;
								for(var j=0; j < _this.rObj.tVars.length; j++){
									js_log('checking: ' + _this.rObj.tVars[j] + ' against:' + tvar);
									if( _this.rObj.tVars[j] == tvar)
										do_add=false;
								}
								//add the template vars to the output obj
								if(do_add)
									_this.rObj.tVars.push( tvar );
							}
							_this.doEditOpts(target);
						}
					);
				}else{
					_this.doEditOpts(target);
				}
			}
		},
		'overlays':{
			'media':['image','video'],
			'doEdit':function(_this, target){
				//do clock mouse scroll duration editor
				$j(target).html('<h3>Current Overlays:</h3>Add,Remove,Modify');
			}
		},
		'audio':{
			'media':['image','video', 'template'],
			'doEdit':function(_this, target){
				//do clock mouse scroll duration editor
				$j(target).html('<h3>Audio Volume:</h3>');
			}
		}
	},
	doEditOpts:function(target){
		var _this = this;
		//add html for rObj resource:
		var o=	'<table>' +
				'<tr>' +
					'<td colspan="2"><b>'+gM('mv_edit_properties')+'</b></td>'+
				'</tr>'+
				'<tr>'+
					'<td>' +
						gM('mv_custom_title') +
					'</td>'+
					'<td><input type="text" size="15" maxwidth="255" value="';
						if(_this.rObj.title != null)
							o+=_this.rObj.title;
						o+='">'+
					'</td>'+
				'</tr>';
		if( _this.rObj.tVars){
			var existing_p = _this.rObj.params;
			var testing_a = _this.rObj.tVars;
			//debugger;
			o+= '<tr>'+
					'<td colspan="2"><b>' + gM('mv_template_properties') + '</b></td>'+
				'</tr>';
			for(var i =0; i < _this.rObj.tVars.length ; i++){
				o+='<tr>'+
					'<td>' +
						_this.rObj.tVars[i] +
					'</td>' +
					'<td><input name="'+_this.rObj.tVars[i]+'" class="ic_tparam" type="text" size="15" maxwidth="255" value="';
				if(_this.rObj.params[ _this.rObj.tVars[i] ]){
					o+= _this.rObj.params[ _this.rObj.tVars[i] ];
				}
				o+='">'+
					'</td>'+
				'</tr>';
			}
		}
		if(typeof wgArticlePath != 'undefined' ){
			var res_src = wgArticlePath.replace(/\$1/, _this.rObj.uri );
			var res_title = _this.rObj.uri;
		}else{
			//var res_page =
			var res_src = _this.rObj.src;
			var res_title = parseUri(_this.rObj.src).file;
		}
		o+=	'<tr>'+
					'<td colspan="2"><b>'+gM('mv_other_properties')+'</b></td>'+
				'</tr>'+
				'<tr>'+
					'<td>' +
						gM('mv_resource_page') +
					'</td>' +
					'<td><a href="' + res_src  +'" '+
						' target="new">'+
							res_title + '</a>'+
					'</td>'+
				'</tr>';
		o+='</table>';

		$j(target).html ( o );

		//add update bindings
		$j(target + ' .ic_tparam').change(function(){
			js_log("updated tparam::" + $j(this).attr("name"));
			//update param value:
			_this.rObj.params[ $j(this).attr("name") ] = $j(this).val();
			//re-parse & update template
			var template_wiki_text = '{{' + _this.rObj.uri;
			for(var i =0;i < _this.rObj.tVars.length ; i++){

				template_wiki_text += "\n|"+_this.rObj.tVars[i] + ' = ' +  _this.rObj.params[ _this.rObj.tVars[i] ]  ;
			}
			template_wiki_text += "\n}}";
			var reqObj ={
					'action':'parse',
					'title'	: _this.p_seqObj.plObj.mTitle,
					'text'	:	template_wiki_text
			};
			$j( _this.rObj.embed ).html( mv_get_loading_img() );

			var api_url = _this.p_seqObj.plObj.interface_url;
			do_api_req({
				'data':reqObj,
				'url':api_url
			},function(data){
				if(data.parse.text['*']){
					//update the target
					$j( _this.rObj.embed ).html( data.parse.text['*'] );
				}
			})
		})

		//update doFocusBindings
		if( _this.p_seqObj )
			_this.p_seqObj.doFocusBindings();
	},
	doEditTypesMenu:function(){
		var _this = this;
		//add in subMenus if set
		//check for submenu and add to item container
		var o='';
		var tabc ='';
		o+= '<div id="mv_submenu_clipedit">';
		o+='<ul>';
		var first_tab = false;
		$j.each(this.edit_types, function(sInx, editType){
			//check if the given editType is valid for our given media type
			var include = false;
			for(var i =0; i < editType.media.length;i++){
				if( editType.media[i] == _this.media_type){
					include = true;
					if(!first_tab)
						first_tab = sInx;
				}
			}
			if(include){
				o+=	'<li>'+
						'<a id="mv_smi_'+sInx+'" href="#sc_' + sInx + '">' + gM('sc_' + sInx ) + '</a>'+
					'</li>';
				tabc += '<div id="sc_' + sInx + '" style="overflow:auto;" ></div>';
			}
		});
		o+= '</ul>' + tabc;
		o+= '</div>';
		//add sub menu container with menu html:
		$j('#'+this.control_ct).html( o ) ;
		//set up bindings:
		$j('#mv_submenu_clipedit').tabs({
			selected: 0,
			select: function(event, ui) {
				_this.doDisplayEdit( $j(ui.tab).attr('id').replace('mv_smi_', '') );
			}
		}).addClass('ui-tabs-vertical ui-helper-clearfix');
		//close left:
		$j("#mv_submenu_clipedit li").removeClass('ui-corner-top').addClass('ui-corner-left');
		//update the default edit display:
		_this.doDisplayEdit( first_tab );
	},
	doDisplayEdit:function( edit_type ){
		if(!edit_type)
			return false;
		js_log('doDisplayEdit: ' + edit_type );

		//do edit interface for that edit type:
		if( this.edit_types[ edit_type ].doEdit )
			this.edit_types[ edit_type ].doEdit(this, '#sc_'+edit_type );
	},
	setUpVideoCtrl:function(){
		js_log('setUpVideoCtrl:f');
		var _this = this;
		var eb = $j('#embed_vid').get(0);
		//turn on preview to avoid onDone actions
		eb.preview_mode = true;
		$j('#'+this.control_ct).html('<h3>Edit Video Tools:</h3>');
		if( eb.supportsURLTimeEncoding() ){
			$j('#'+this.control_ct).append(
				_this.getSetInOutHtml({
					'start_ntp'	: eb.start_ntp,
					'end_ntp'	: eb.end_ntp
				})
			);
			_this.setInOutBindings();
		}
		//if in a sequence we have no need for insertDesc
		if( !_this.p_seqObj){
			$j('#'+this.control_ct).append(	_this.getInsertDescHtml() );
		}
		//update control actions
		this.updateInsertControlActions();
	},
	setInOutBindings:function(){
		var _this = this;

		var start_sec = npt2seconds($j('#'+this.control_ct + ' .startInOut').val() );
		var end_sec   = npt2seconds($j('#'+this.control_ct + ' .endInOut').val() );

		//if we don't have 0 as start then assume we are in a range request and give some buffer area:
		var min_slider =  (start_sec - 60 < 0 ) ? 0 : start_sec - 60;
		if(min_slider!=0){
			var max_slider =  end_sec+60;
		}else{
			max_slider = end_sec;
		}

		$j('#'+this.control_ct + ' .inOutSlider').slider({
			range: true,
			min: min_slider,
			max: max_slider,
			values: [start_sec, end_sec],
			slide: function(event, ui) {
				js_log(" vals:"+  seconds2npt( ui.values[0] ) + ' : ' + seconds2npt( ui.values[1]) );
				$j('#'+_this.control_ct + ' .startInOut').val( seconds2npt( ui.values[0] ) );
				$j('#'+_this.control_ct + ' .endInOut').val( seconds2npt( ui.values[1] ) );
			},
			change:function(event, ui){
				do_video_time_update( seconds2npt( ui.values[0]), seconds2npt( ui.values[1] ) );
			}
		});

		//preview button:
		$j('#'+this.control_ct + ' .inOutPreviewClip').hover(
			function(){
				$j(this).addClass('ui-state-hover');
			},
			function(){
				$j(this).removeClass('ui-state-hover');
			}
		).click(function(){
			$j('#embed_vid').get(0).stop();
			$j('#embed_vid').get(0).play();
		});
		//simple hover:

	},
	getSetInOutHtml:function( setInt ){
		return '<strong>' + gM('mv_set_in_out_points') + '</strong>'+
			'<table border="0" style="background: transparent; width:94%;height:50px;">'+
				'<tr>' +
					'<td style="width:55px">'+
						gM('mv_start_time') +
						'<input class="ui-widget-content ui-corner-all startInOut" size="9" value="' + setInt.start_ntp +'">'+
					'</td>' +
					'<td>' +
						'<div class="inOutSlider"></div>'+
					'</td>' +
					'<td style="width:55px">'+
						gM('mv_end_time') +
						'<input class="ui-widget-content ui-corner-all endInOut" size="9" value="'+ setInt.end_ntp +'">'+
					'</td>' +
				'</tr>' +
			'</table>'+
			'<a href="#" class="ui-state-default ui-corner-all ui-icon_link inOutPreviewClip"><span class="ui-icon ui-icon-video"></span>'+ gM('mv_preview_inout') +'</a>';
	},
	getInsertDescHtml:function(){
		var o= '<h3>Inline Description</h3>'+
					'<textarea style="width:95%" id="mv_inline_img_desc" rows="5" cols="30">';
		if( this.p_rsdObj ){
			//if we have a parent remote search driver let it parse the inline description
			o+= this.rObj.pSobj.getInlineDescWiki( this.rObj );
		}
		o+='</textarea><br>';
		//js_log('getInsertDescHtml: ' + o );
		return o;
	},
	updateInsertControlActions:function(){
		var _this = this;
		var b_target =   _this.p_rsdObj.target_container + '~ .ui-dialog-buttonpane';
		//empty the ui-dialog-buttonpane bar:
		$j(b_target).empty();
		for( var cbType in _this.controlActionsCb ){
			switch(cbType){
				case 'insert_seq':
					$j(b_target).append( $j.btnHtml(gM('mv_insert_into_sequence'), 'mv_insert_sequence', 'check' ) + ' ' )
						.children('.mv_insert_sequence')
						.btnBind()
						.click(function(){
							_this.applyEdit();
							_this.controlActionsCb['insert_seq'](  _this.rObj );
						});
				break;
				case 'insert':
					$j(b_target).append(  $j.btnHtml(gM('mv_insert_image_page'), 'mv_insert_image_page', 'check' ) + ' ' )
						.children('.mv_insert_image_page')
						.btnBind()
						.click(function(){
							_this.applyEdit();
							_this.controlActionsCb['insert'](  _this.rObj );
						}).show('slow');
				break;
				case 'preview':
					$j(b_target).append( $j.btnHtml( gM('mv_preview_insert'), 'mv_preview_insert', 'refresh') + ' ' )
						.children('.mv_preview_insert')
						.btnBind()
						.click(function(){
							_this.applyEdit();
							_this.controlActionsCb['preview'](  _this.rObj );
						}).show('slow');
				break;
				case 'cancel':
					$j(b_target).append( $j.btnHtml( gM('mv_cancel_image_insert'), 'mv_cancel_img_edit', 'close') + ' ')
						.children('.mv_cancel_img_edit')
						.btnBind()
						.click(function(){
							//no cancel action;
							_this.controlActionsCb['cancel'](  _this.rObj );
						}).show('slow');
				break;
			}
		}
	},
	applyEdit:function(){
		var _this = this;
		js_log('applyEdit::' + this.media_type);
		if(this.media_type == 'image'){
			this.applyCrop();
		}else if(this.media_type == 'video'){
			this.applyVideoAdj();
		}
		//copy over the desc text to the resource object
		_this.rObj['inlineDesc']= $j('#mv_inline_img_desc').val();
	},
	setUpImageCtrl:function(){
		var _this = this;
		//by default apply Crop tool
		$j('#'+this.control_ct).html(
			'<h3>Edit tools</h3>' +
					'<div class="mv_edit_button mv_crop_button_base" id="mv_crop_button" alt="crop" title="'+gM('mv_crop')+'"/>'+
					'<a href="#" class="mv_crop_msg">' + gM('mv_crop') + '</a> '+
					'<span style="display:none" class="mv_crop_msg_load">' + gM('loading_txt') + '</span> '+
					'<a href="#" style="display:none" class="mv_apply_crop">' + gM('mv_apply_crop') + '</a> '+
					'<a href="#" style="display:none" class="mv_rest_crop">' + gM('mv_reset_crop') + '</a> '+
				'<hr style="clear:both"/><br>'+
			'<span style="float:left;">Layout:</span>' +
				'<input type="radio" name="mv_layout" id="mv_layout_left" style="float:left"><div id="mv_layout_left_img" title="'+gM('mv_layout_left')+'"/>'+
				'<input type="radio" name="mv_layout" id="mv_layout_right" style="float:left"><div id="mv_layout_right_img" title="'+gM('mv_layout_left')+'"/>'+
			'<hr style="clear:both" /><br>' +
				_this.getInsertDescHtml()
		);
		//add the actions to the 'button bar'
		_this.updateInsertControlActions()

		/*scale:
		 '<div class="mv_edit_button mv_scale_button_base" id="mv_scale_button" alt="crop" title="'+gM('mv_scale')+'"></div>'+
				'<a href="#" class="mv_scale_msg">' + gM('mv_scale') + '</a><br>'+
				'<a href="#" style="display:none" class="mv_apply_scale">' + gM('mv_apply_scale') + '</a> '+
				'<a href="#" style="display:none" class="mv_rest_scale">' + gM('mv_reset_scale') + '</a><br> '+

		*/
		//add bindings:

		//make sure the default is reflected:
		if( ! _this.rObj.layout )
			_this.rObj.layout = 'right';
		$j('#mv_layout_' + _this.rObj.layout)[0].checked = true;

		//left radio click
		$j('#mv_layout_left,#mv_layout_left_img').click(function(){
			$j('#mv_layout_right')[0].checked = false;
			$j('#mv_layout_left')[0].checked = true;
			_this.rObj.layout = 'left';
		});
		//right radio click
		$j('#mv_layout_right,#mv_layout_right_img').click(function(){
			$j('#mv_layout_left')[0].checked = false;
			$j('#mv_layout_right')[0].checked = true;
			_this.rObj.layout = 'right';
		});
		$j('#mv_crop_button,.mv_crop_msg,.mv_apply_crop').click(function(){
			js_log('click:mv_crop_button: base width: ' + _this.rObj.width + ' bh: ' + _this.rObj.height);
			if($j('#mv_crop_button').hasClass('mv_crop_button_selected')){
				_this.applyCrop();
			}else{
				js_log('click:turn on');
				_this.enableCrop();
			}
		});
		$j('.mv_rest_crop').click(function(){
			$j('.mv_apply_crop,.mv_rest_crop').hide();
			$j('.mv_crop_msg').show();
			$j('#mv_crop_button').removeClass('mv_crop_button_selected').addClass('mv_crop_button_base').attr('title',gM('mv_crop'));
			_this.rObj.crop=null;
			$j('#' + _this.clip_disp_ct ).empty().html(
				'<img src="' + _this.rObj.edit_url + '" id="rsd_edit_img">'
			);
		});
	},
	applyVideoAdj:function(){
		js_log('applyVideoAdj::');

		//be sure to "stop the video (some plugins can't have DOM elements on top of them)
		$j('#embed_vid').get(0).stop();

		//update video related keys:
		;
		this.rObj['start_time'] = $j('#'+this.control_ct + ' .startInOut').val();
		this.rObj['end_time']   = $j('#'+this.control_ct + ' .endInOut').val() ;

		//do the local video adjust
		if(typeof this.rObj.pSobj['applyVideoAdj'] != 'undefined'){
			this.rObj.pSobj.applyVideoAdj( this.rObj );
		}
	},
	applyCrop:function(){
		var _this = this;
		$j('.mv_apply_crop').hide();
		$j('.mv_crop_msg').show();
		$j('#mv_crop_button').removeClass('mv_crop_button_selected').addClass('mv_crop_button_base').attr('title',gM('mv_crop'));
		js_log( 'click:turn off' );
		var cat = _this.rObj;
		if(_this.rObj.crop){
			//empty out and display croped:
			$j('#'+_this.clip_disp_ct ).empty().html(
				'<div id="mv_cropcotainer" style="overflow:hidden;position:absolute;'+
					'width:' + _this.rObj.crop.w + 'px;'+
					'height:' + _this.rObj.crop.h + 'px;">'+
					'<div id="mv_crop_img" style="position:absolute;'+
						'top:-' + _this.rObj.crop.y +'px;'+
						'left:-' + _this.rObj.crop.x + 'px;">'+
						'<img src="' + _this.rObj.edit_url  + '">'+
					'</div>'+
				'</div>'
			);
		}
		return true;
	},
	//right now enableCrop loads "just in time"
	//@@todo we really need an "auto loader" type system.
	enableCrop:function(){
		var _this = this;
		$j('.mv_crop_msg').hide();
		$j('.mv_crop_msg_load').show();
		var doEnableCrop = function(){
			$j('.mv_crop_msg_load').hide();
			$j('.mv_rest_crop,.mv_apply_crop').show();
			$j('#mv_crop_button').removeClass('mv_crop_button_base').addClass('mv_crop_button_selected').attr('title',gM('mv_crop_done'));
			$j('#' + _this.clip_disp_ct + ' img').Jcrop({
				 onSelect: function(c){
					 js_log('on select:' + c.x +','+ c.y+','+ c.x2+','+ c.y2+','+ c.w+','+ c.h);
					 _this.rObj.crop = c;
				 },
				 onChange: function(c){
				 }
			});
			//temporary hack (@@todo need to debug why rsd_res_item gets moved )
			$j('#clip_edit_disp .rsd_res_item').css({
				'top':'0px',
				'left':'0px'
			});
		}
		//load the jcrop library if needed:
		mvJsLoader.doLoad([
			'$j.Jcrop'
		],function(){
			doEnableCrop();
		});
	}
}

// mv_lock_vid_updates defined in mv_stream.js (we need to do some more refactoring )
if(typeof mv_lock_vid_updates == 'undefined')
	mv_lock_vid_updates= false;

function add_adjust_hooks(mvd_id, adj_callback){
	/*myClipEdit = new mvClipEdit({
		'control_ct': '#mvd_form_'+mvd_id
	});
	$j('#mvd_form_'+mvd_id).html(
		mvClipEdit.getSetInOutHtml({
			'start_ntp'	:  $j('#mv_start_hr_' + mvd_id).val(),
			'end_ntp'	:  $j('#mv_end_hr_' + mvd_id).val()
		})
	);
	mvClipEdit.setInOutBindings();*/

	var start_sec = npt2seconds($j('#mv_start_hr_' + mvd_id).val() );
	var end_sec   = npt2seconds($j('#mv_end_hr_' + mvd_id).val()  );

	//if we don't have 0 as start then assume we are in a range request and give some buffer area:
	var min_slider =  (start_sec - 60 < 0 ) ? 0 : start_sec - 60;
	if(min_slider!=0){
		var max_slider =  end_sec+60;
	}else{
		max_slider = end_sec;
	}
	//pre-destroy just in case:
	$j('#mvd_form_' + mvd_id + ' .inOutSlider').slider( 'destroy' ).slider({
		range: true,
		min: min_slider,
		max: max_slider,
		values: [start_sec, end_sec],
		slide: function(event, ui) {
			js_log(" vals:"+  seconds2npt( ui.values[0] ) + ' : ' + seconds2npt( ui.values[1]) );
			$j('#mv_start_hr_' + mvd_id).val( seconds2npt( ui.values[0] ) );
			$j('#mv_end_hr_' + mvd_id).val( seconds2npt( ui.values[1] ) );
		},
		change:function(event, ui){
			do_video_time_update( seconds2npt( ui.values[0]), seconds2npt( ui.values[1] ) );
		}
	});
	$j('.mv_adj_hr').change(function(){
		//preserve track duration for nav and seq:
		//ie seems to crash so no interface updates for IE for the time being
		if(!$j.browser.msie){
			if(mvd_id=='nav'||mvd_id=='seq'){
				add_adjust_hooks(mvd_id); // (no adj_callback)
			}else{
				add_adjust_hooks(mvd_id)
			}
		}
		//update the video time for onChange
		do_video_time_update( $j('#mv_start_hr_'+mvd_id).val(), $j('#mv_end_hr_'+mvd_id).val() );
	});
}

function do_video_time_update(start_time, end_time, mvd_id)	{
	js_log('do_video_time_update: ' +start_time +' '+ end_time);

	if(mv_lock_vid_updates==false){
		//update the vid title:
		$j('#mv_videoPlayerTime').html( start_time + ' to ' + end_time );
		var ebvid = $j('#embed_vid').get(0);
		if( ebvid ){
			if(ebvid.isPaused())
				ebvid.stop();
			ebvid.updateVideoTime(start_time, end_time);
			js_log('update thumb: '+ start_time);
			ebvid.updateThumbTimeNTP( start_time );
		}
	}
}