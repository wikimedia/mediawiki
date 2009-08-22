/*
* Adds advanced firefogg support (let you control and structure advanced controls over many aspects of video encoding)
*/

//@@todo put all msg text into loadGM json

loadGM({
	"fogg-help-sticky" : "Help (click to stick)",
	"fogg-cg-preset" : "Preset :  <strong>$1<\/strong>",
	"fogg-cg-quality" : "Basic quality and resolution control",
	"fogg-cg-meta" : "Metadata for the clip",
	"fogg-cg-range" : "Encoding range",
	"fogg-cg-advVideo" : "Advanced video encoding controls",
	"fogg-cg-advAudio" : "Advanced audio encoding controls",
	"fogg-preset-custom" : "Custom settings"
});

var mvAdvFirefogg = function( iObj ){
	return this.init( iObj );
}
var default_mvAdvFirefogg_config = {
	//which config groups to include
	'config_groups'	 : ['preset', 'range', 'quality', 'meta', 'advVideo', 'advAudio'],

	//if you want to load any custom presets must follow the mvAdvFirefogg.presetConf json outline below
	'custom_presets'	: {},

	//any firefog config properties that may need to be excluded from options
	'exclude_settings' : [],

	//the control container (where we put all the controls)
	'target_control_container':false
}

mvAdvFirefogg.prototype = {
	//the global groupings and titles for for configuration options :
	config_groups : [ 'preset', 'range', 'quality', 'meta', 'advVideo', 'advAudio'],
	//list of pre-sets:
	//local instance encoder config:
default_local_settings:{
	'd'	: 'webvideo',
	'type'	: 'select',
	'selectVal': ['webvideo'],
	'group' : "preset",
	'pSet' : {
		'custom':{
			'descKey': 'fogg-preset-custom',
			'conf': {}
		},
		'webvideo': {
			'desc': "Web Video Theora, Vorbis 400kbs & 400px max width",
			'conf': {
				'maxSize'      : 400,
				'videoBitrate' : 544,
				'audioBitrate' : 96,
				'noUpscaling'  : true,
			}
		},
		'savebandwith': {
			'desc': "Low Bandwith Theora, Vorbis 164kbs & 200px max size",
			'conf': {
				'maxSize'       : 200,
				'videoBitrate'  : 164,
				'audioBitrate'  : 32,
				'samplerate'    : 22050,
				'framerate'     : 15,
				'channels'      : 1,
				'noUpscaling'   : true
			}
		},
		'hqstream':{
			'desc': "High Quality Theora, Vorbis 1080px max width",
			'conf': {
				'maxSize'      : 1080,
				'videoQuality' : 6,
				'audioQuality' : 3,
				'noUpscaling'  : true,
			}
		},
	}
},
	local_settings: {},

	//core firefogg default encoder configuration
	//see encoder options here: http://www.firefogg.org/dev/index.html
	default_encoder_config : {
		//base quality settings:
		'videoQuality': {
			'd'     : 5,
			't'     : 'Video Quality',
			'range' : {'min':0,'max':10},
			'type'  : 'slider',
			'group' : 'quality',
			'help'  : "Used to set the <i>Visual Quality</i> of the encoded video. (not used if you set bitrate in advanced controls below)"
		},
		'twopass':{
			't'     : "Two Pass Encoding",
			'type'  : "boolean",
			'group' : "quality",
			'help'  : "Two Pass Encoding enables more consitant quality by making two passes over the video file"
		},
		'starttime':{
			't'		: "Start Second",
			'type'	: "float",
			'group' : "range",
			'help'	: "Only encode from time in seconds"
		},
		'endtime':{
			't'		: "End Second",
			'type'	: "float",
			'group' : "range",
			'help'	: "only encode to time in seconds"
		},
		'audioQuality': {
			'd'			: 1,
			't'			: 'Audio Quality',
			'range'	 : {'min':-1,'max':10},
			'type'	  : 'slider',
			'group'	 : 'quality',
			'help'	  : "Used to set the <i>Acoustic Quality</i> of the encoded audio. (not used if you set bitrate in advanced controls below)"
		},
		'videoCodec':{
			'd'			: "theora",
			't'			: 'Video Codec',
			'selectVal'	: ['theora'],
			'type'		: "select",
			'group'		: "quality",
			'help'	  : "Used to select the clip video codec. Presently only Theora is supported. More about the <a href=\"http://www.theora.org/\">theora codec</a> "
		},
		'audioCodec':{
			'd'			: "vorbis",
			't'			: 'Audio Codec',
			'selectVal'	: ['vorbis'],
			'type'		: "select",
			'group'		: "quality",
			'help'	  : "Used to set the clip audio codec. Presently only Vorbis is supported. More about the <a href=\"http://www.vorbis.com//\">vorbis codec</a> "
		},
		'width': {
			't'			: 'Video Width',
			'range'	 : {'min':0,'max':1080},
			'step'		: 4,
			'type'	  : 'slider',
			'group'	 : "quality",
			'help'		: "Resize to given width."
		},
		'height': {
			't'			: 'Video Height',
			'range'	 : {'min':0,'max':1080},
			'step'		: 4,
			'type'		: "slider",
			'group'		: "quality",
			'help'		: "Resize to given height"
		},
		//advanced Video control configs:
		'videoBitrate':{
			't'			: 'Video Bitrate',
			'range'		: {'min':1, 'max':16778},
			'type'		: "slider",
			'group'		: "advVideo",
			'help'		: "Video Bitrate sets the encoding bitrate for video in (kb/s)"
		} ,
		'framerate':{
			't'			: 'Framerate',
			'd'			: '24',
			'selectVal'	: ['12', '16', {'24000:1001':'23.97'}, '24', '25', {'30000:1001':'29.97'}, '30'],
			'type'		   : "select",
			'group'		: "advVideo",
			'help'		   : "The video Framerate. More about <a target=\"_new\" href=\"http://en.wikipedia.org/wiki/Frame_rate\">Framerate</a>"
		},
		'aspect':{
			't'			: 'Aspect Ratio',
			'd'			: '4:3',
			'type'		: "select",
			'selectVal'	: ['4:3', '16:9'],
			'group'		: "advVideo",
			'help'		: "The video aspect ratio can be fraction 4:3 or 16:9. More about <a target=\"_new\" href=\"http://en.wikipedia.org/wiki/Aspect_ratio_%28image%29\">aspect ratios</a>"
		},
		'keyframeInterval':{
			'd'			: '64',
			't'			: 'Key Frame Interval',
			'range'	 : {'min':0,'max':65536},
			'numberType': 'force keyframe every $1 frames',
			'type'		 : 'int',
			'group'		: 'advVideo',
			'help'		: "The keyframe interval in frames. Note: Most codecs force keyframes if the difference between frames is greater than keyframe encode size. More about <a href=\"http://en.wikipedia.org/wiki/I-frame\">keyframes</a>"
		},
		'denoise':{
			'type'		: "boolean",
			't'			: "Denoise Filter",
			'group'		: 'advVideo',
			'help'		: "Denoise input video. More about <a target=\"_new\" href=\"http://en.wikipedia.org/wiki/Video_denoising\">denoise</a>"
		},
		'novideo':{
			't'			: "No Video",
			'type'		: "boolean",
			'group'		: 'advVideo',
			'help'		: "disable video in the output"
		},

		//advanced Audio control Config:
		'audioBitrate':{
			't'			: "Audio Bitrate",
			'range'		: {'min':32,'max':500},
			'numberType': '$1 kbs',
			'type'		: 'slider',
			'group'		: 'advAudio'
		},
		'samplerate':{
			't'		: "Audio Sample Rate",
			'type'		: 'select',
			'selectVal'	: [{'22050':'22 kHz'}, {'44100':'44 khz'}, {'48000':'48 khz'}],
			'formatSelect'	: function(val){
				return (Math.round(val/100)*10) + ' Hz';
			},
			'help'		: "set output samplerate (in Hz)."
		},
		'noaudio':{
			't'		: "No Audio",
			'type'		: 'boolean',
			'group'		: 'advAudio',
			'help'		: "disable audio in the output"
		},

		//meta tags:
		'title':{
			't'	: "Title",
			'type'	: 'string',
			'group' : 'meta',
			'help'	: "A title for your clip"
		},
		'artist':{
			't'	: "Artist Name",
			'type'	: 'string',
			'group' : 'meta',
			'help'	: "The artist that created this clip"
		},
		'date':{
			't'	: "Date",
			'group' : 'meta',
			'type'	: 'date',
			'help'	: "The date the footage was created or released"
		},
		'location':{
			't'	: "Location",
			'type'	: 'string',
			'group' : 'meta',
			'help'	: "The location of the footage"
		},
		'organization':{
			't'	: "Organization",
			'type'	: 'string',
			'group'	: 'meta',
			'help'  : "Name of organization (studio)"
		},
		'copyright':{
			't'	: "Copyright",
			'type'	: 'string',
			'group'	: 'meta',
			'help'	: "The Copyright of the clip"
		},
		'license':{
			't'	   : "License",
			'type'	: 'string',
			'group'   : 'meta',
			'help'	: "The license of the clip (preferably a creative commons url)"
		},
		'contact':{
			't'	: "Contact",
			'type'	: 'string',
			'group'	: 'meta',
			'help'	: "Contact link"
		}
	},
	init:function( iObj ){
		//setup a "supported" iObj:
		for(var i in iObj){
			if( typeof default_mvAdvFirefogg_config [i] != 'undefined' ){
				this[i] = iObj[i];
			}
		}
		//inherit the base mvFirefogg class:
		var myFogg = new mvFirefogg( iObj );
		for(var i in myFogg){
			if( typeof this[i] != 'undefined'){
				this[ 'basefogg_' + i ] = myFogg[i];
			}else{
				this[ i ] = myFogg[i];
			}
		}
	},
	setupForm:function(){
		//call base firefogg form setup
		basefogg_setupForm();

		//gennerate the control html
		this.doControlHTML();

		//setup control bindings:
		this.doControlBindings();

	},
	doControlHTML: function(){
		js_log("adv doControlHTML");
		var _this = this;
		//load presets from cookie:
		this.loadEncSettings();

		//add base control buttons:
		this.basefogg_doControlHTML();

		//build the config group outpouts
		var gdout ='';
		$j.each(this.config_groups, function(inx, group_key){
			gdout+= '<div> '+
				'<h3><a href="#" class="gd_'+group_key+'" >' + gM('fogg-cg-'+group_key) + '</a></h3>'+
					'<div>';
			//output that group control options:
			gdout+='<table width="' + ($j(_this.selector).width()-60) + '" ><tr><td width="35%"></td><td width="65%"></td></tr>';
			//output the special prset output
			if(group_key=='preset'){
				gdout += _this.proccessPresetControl();
			}
			for(var cK in _this.default_encoder_config){
				var cConf = _this.default_encoder_config[cK];
				if(cConf.group == group_key){
					gdout+= _this.proccessCkControlHTML( cK );
				}
			}
			gdout+='</table>';
			gdout+=		'</div>' +
				 '</div>';

		});
		//add the control container:
		if(!this.target_control_container){
			this.target_control_container = this.selector + ' .control_container';
			//set the target contorl container to height
			$j(this.selector).append( '<p><div class="control_container"></div>' );
		}
		//hide the container and add the output
		$j(this.target_control_container).hide();
		$j(this.target_control_container).html( gdout );

	},
	//custom advanced target rewrites:
	getTargetHtml:function(target){
		if(	target=='target_btn_select_file' ||
			target=='target_btn_select_new_file'||
			target=='target_btn_save_local_file'){
			var icon = (target=='target_btn_save_local_file')?'ui-icon-video':'ui-icon-folder-open';
			return	 '<a class="ui-state-default ui-corner-all ui-icon_link '+
						target +'" href="#"><span class="ui-icon ' + icon + '"/>' +
						gM( 'fogg-' + target.substring(11) ) +
					'</a>';
		}else if(  target=='target_btn_select_url'){
			//return the btnHtml:
			return $j.btnHtml( gM( 'fogg-' + target.substring(11) ), target,  'link');

		}else if(	target=='target_use_latest_fox' ||
					target=='target_please_install' ||
					target == 'target_passthrough_mode'){
			return '<div style="margin-top:1em;padding: 0pt 0.7em;" class="ui-state-error ui-corner-all ' +
					target + '">' +
					'<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"/>'+
					gM( 'fogg-' + target.substring(7)) +'</p>'+
				'</div>';
		}else if( target == 'target_input_file_name'){
			return '<br><br><input style="" class="text ui-widget-content ui-corner-all ' + target + '" '+
					'type="text" value="' + gM( 'fogg-' + target.substring(11)) + '" size="60" /> ';
		}else{
			js_log('call : basefogg_getTargetHtml');
			return this.basefogg_getTargetHtml(target);
		}
	},
	proccessPresetControl:function(){
		var out='';
		var _this = this;
		js_log('proccessPresetControl::');
		if(typeof this.local_settings.pSet!= 'undefined' ){
			out+= '<select class="_preset_select">';
			$j.each(this.local_settings.pSet, function(pKey, pSet){
				var pDesc = (pSet.descKey) ? gM(pSet.descKey) : pSet.desc;
				var sel = (_this.local_settings.d == pKey)?' selected':'';
				out+='<option value="'+pKey+'" '+sel+'>'+ pDesc+'</option>';
			});
			out+='</select>';
			}
		return out;
	},
	proccessCkControlHTML:function( cK ){
		var cConf =  this.default_encoder_config[cK];
		var out ='';
		out+='<tr><td valign="top">'+
			'<label for="_' + cK + '">' +
			 cConf.t + ':' +
			 '<span title="' + gM('fogg-help-sticky') + '" class="help_'+ cK + ' ui-icon ui-icon-info" style="float:left"></span>'+
			 '</label></td><td valign="top">';
		//if we don't value for this:
		var dv = ( this.default_encoder_config[cK].d ) ? this.default_encoder_config[cK].d : '';
		//switch on the config type
		switch(	cConf.type ){
			case 'string':
			case 'date':
			case 'int':
			case 'float':
				var size = ( cConf.type =='string' ||cConf.type == 'date' )?'14':'4';
				out+= '<input size="' + size + '" type="text" class="_' + cK + ' text ui-widget-content ui-corner-all" value="' + dv + '" >' ;
			break;
			case 'boolean':
				var checked_attr = (dv===true)?' checked="true"':'';
				out+='<input type="checkbox" class="_'+cK+ ' ui-widget-content ui-corner-all" ' + checked_attr + '>';
			break;
			case 'slider':
				var strMax = this.default_encoder_config[ cK ].range.max + '';
				maxdigits = strMax.length +1;
				out+= '<input type="text" maxlength="'+maxdigits+'" size="' +maxdigits + '" '+
					'class="_'+cK+ ' text ui-widget-content ui-corner-all" style="display:inline;border:0; color:#f6931f; font-weight:bold;" ' +
					'value="' + dv + '" >' +
					'<div class="slider_' + cK + '"></div>';
			break;
			case 'select':
				out+= '<select class="_' + cK + '">'+
						'<option value=""> </option>';
				for(var i in cConf.selectVal){
					var val = cConf.selectVal[i];
					if(typeof val == 'string'){
						var sel = (	cConf.selectVal[i] == val)?' selected':'';
						out+= '<option value="'+val+'"'+sel+'>'+val+'</option>';
					}else if(typeof val == 'object'){
						for(var key in val){
							hr_val = val[key];
						}
						var sel = ( cConf.selectVal[i] == key )?' selected':'';

						out+= '<option value="'+key+'"'+sel+'>'+hr_val+'</option>';
					}
				}
				out+='</select>';
			break;
		}
		//output the help row:
		if(cConf.help){
			out+='<div class="helpRow_' + cK + '">'+
					'<span class="helpClose_' + cK + ' ui-icon ui-icon-circle-close" '+
					'title="Close Help"'+
					'style="float:left"/>'+
				 cConf.help +
				 '</div>';
		}
		out+='</td></tr><tr><td colspan="2" height="10"></td></tr>';
		return out;
	},
	selectByUrl:function(){
		var urlValue = prompt("Please enter the source media url you would like to transcode from.","http://");
		if( urlValue ){
			//update the mode:
			this.sourceMode = 'url';
			this.sourceUrl = urlValue;
			this.selectFoggActions();
			this.autoEncoderSettings();
			//update the input target
			$j(this.target_input_file_name).unbind().val( urlValue ).removeAttr('readonly');
		}
	},
	doControlBindings:function(){
		var _this = this;
		_this.basefogg_doControlBindings();
		//show the select by url if present:
		/*$j( this.target_btn_select_url ).unbind(
			).attr('disabled', false
			).css({'display':'inline'}
			).click(function(){
				_this.selectByUrl();
		});
		*/


		//hide the base advanced controls untill a file is selected:
		$j(this.target_control_container).hide();

		var helpState = {};
		//do some display tweeks:
		js_log('tw:' + $j(this.selector).width() +
				'ssf:' + $j(this.target_btn_select_new_file).width() +
				'sf:' +	 $j(this.target_btn_save_local_file).width() );

		//set width to 250
		$j(this.target_input_file_name).width( 250 );

		//special preset action:
		$j(this.selector + ' ._preset_select').change(function(){
			_this.updatePresetSelection( $j(this).val() );
		});

		//bind control actions
		for(var cK in this.default_encoder_config){
			var cConf =  this.default_encoder_config[cK];
			//set up the help for all types:
			if(cConf.help){
				//initial state is hidden:
				$j( this.selector + ' .helpRow_' + cK).hide();
				$j(this.selector + ' .help_' + cK).click(function(){
					//get the ckId (assume its the last class)
					var cK = _this.getClassId(this, 'help_');

					if(helpState[cK]){
						$j(_this.selector + ' .helpRow_' + cK).hide('slow');
						helpState[cK] = false;
					}else{
						$j(_this.selector + ' .helpRow_' + cK).show('slow');
						helpState[cK] = true;
					}
					return false;
				}).hover(
					function(){
						//get the ckId (assume its the last class)
						var cK = _this.getClassId(this, 'help_');
						$j( _this.selector + ' .helpRow_' + cK).show('slow');
					},function(){
						var cK = _this.getClassId(this, 'help_');
						if(!helpState[cK])
							$j( _this.selector + ' .helpRow_' + cK).hide('slow')
					}
				);
				$j( _this.selector + ' .helpClose_' + cK).click(function(){
					js_log("close help: " +cK);
					//get the ckId (assume its the last class)
					var cK = _this.getClassId(this, 'helpClose_');
					$j(_this.selector + ' .helpRow_' + cK).hide('slow');
					helpState[cK] = false;
					return false;
				}).css('cursor', 'pointer');
			}else{
				$j(this.selector + ' .help_' + cK).hide();
			}

			//setup bindings for change values: (validate input)

			switch(	cConf.type ){
				case 'boolean':
					$j(_this.selector + ' ._'+cK).click(function(){
						_this.updateLocalValue(  _this.getClassId(this), $j(this).is(":checked") );
						_this.updatePresetSelection('custom');
					})
				break;
				case 'select':
				case 'string':
				case 'int':
				case 'float':
					//@@check if we have a validate function on the string
					$j(_this.selector + ' ._'+cK).change(function(){
						$j(this).val( _this.updateLocalValue(
							_this.getClassId(this),
							$j(this).val() ));
						_this.updatePresetSelection('custom');
					})
				break;
				case 'date':
					$j(_this.selector + ' ._'+cK).datepicker({
							changeMonth: true,
							changeYear: true,
							dateFormat: 'd MM, yy',
							onSelect: function(dateText) {
								_this.updateInterfaceValue(_this.getClassId(this), dateText);
							}
					});
				break;
				case 'slider':
					$j(this.selector + ' .slider_' + cK ).slider({
						range: "min",
						animate: true,
						step: (cConf.step)?cConf.step:1,
						value: $j( this.selector +' ._' + cK ).val(),
						min: this.default_encoder_config[ cK ].range.min,
						max: this.default_encoder_config[ cK ].range.max,
						slide: function(event, ui) {
							$j( _this.selector + ' ._' + _this.getClassId(this, 'slider_') ).val( ui.value );

							//maintain source video aspect ratio:
							if(_this.getClassId(this, 'slider_') == 'width'){
								var hv = parseInt((_this.sourceFileInfo.video[0]['height']/_this.sourceFileInfo.video[0]['width'])* ui.value );
								//update the height value:	  the new hight value is > that orginal the slider:
								if(hv > _this.updateInterfaceValue('height', hv))
									return false;
							}
							if(_this.getClassId(this, 'slider_') == 'height'){
								var wv = parseInt((_this.sourceFileInfo.video[0]['width']/_this.sourceFileInfo.video[0]['height'])* ui.value );
								//update the height value:	  the new hight value is > that orginal the slider:
								if(wv > _this.updateInterfaceValue('width', wv))
									return false;
							}
						},
						change: function(event, ui){
							//update the local settings
							_this.updateLocalValue( _this.getClassId(this, 'slider_'), ui.value);
							_this.updatePresetSelection('custom');
						}
					})
					$j( this.selector +' ._' + cK).change(function(){
						var scid = _this.getClassId(this);
						var valdVal = _this.updateLocalValue(scid.substr(1),$j(this).val() );
						_this.updatePresetSelection('custom');
						//(validate user form input)
						$j(this).val(valdVal);
						//update the slider
						js_log("update: " + _this.selector + ' .slider' + scid);
						$j(_this.selector + ' .slider'+ scid).slider('option', 'value', valdVal );
					});
				break
			}
		}
		$j(this.target_control_container).accordion({
			header: "h3",
			collapsible: true,
			active: false,
			fillSpace: true
		});

		//do config value updates if any
		this.updateValuesInHtml();
	},
	updatePresetSelection:function( pKey ){
		//update the local key:
		this.local_settings.d = pKey;
		 //js_log('update preset desc: '+ pKey);
		var pset_desc = '';
		if(this.local_settings.pSet[ pKey  ].desc){
			pset_desc = this.local_settings.pSet[ pKey  ].desc;
		}else{
			pset_desc = gM('fogg-preset-'+ pKey);
		}
		//update the preset title:
		$j( this.selector + ' .gd_preset' ).html(
			gM('fogg-cg-preset', pset_desc)
		);
		//update the selector
		$j(this.selector + ' ._preset_select').val(pKey);
	},
	/*
	 * updates the interface
	 */
	updateInterfaceValue:function(confKey, val){
		var _this = this;
		if(!val){
			return ;
		}
		//js_log('updateInterfaceValue:: ' + confKey + ' v:' + val + ' cv:'+ _this.selector + '._'+ confKey+' len:' + $j(_this.selector + ' ._'+confKey).length );
		//lookup the type
		if(typeof this.default_encoder_config[confKey] == 'undefined'){
			js_error('error: missing default key: '+ confKey);
			return false;
		}

		//update the local value (if not already up-to-date
		if( this.local_settings.pSet['custom']['conf'][confKey] != val ){
			val = this.updateLocalValue(confKey, val);
		}
		//update the text filed:
		$j(_this.selector + ' ._'+confKey).val( val );
		//update the interaface widget:
		switch(this.default_encoder_config[confKey].type){
			case 'slider':
				$j(_this.selector + ' .slider_' + confKey).slider('option',
						'value', $j(_this.selector + ' ._'+ confKey).val() );
			break;
		}
		return val;
	},
	updateLocalValue:function(confKey, value){
		//update the local value (return the value we acutally set)
		if(typeof this.default_encoder_config[confKey] == 'undefined'){
		  js_log("Error:could not update conf key:" + confKey)
		  return value;
		}
		dec = this.default_encoder_config[confKey];
		if(dec.range){
			value = parseInt(value);
			var min = ( dec.range.local_min) ? dec.range.local_min  :dec.range.min;
			if(value < min)
				value = min;
			var max = ( dec.range.local_max) ? dec.range.local_max : dec.range.max
			if(value > max)
				value = max;
		}
		if(dec.type=='int')
			value = parseInt(value);

		//step value:
		/*if(dec.step){
			if((value % dec.step)!=0){
				value = value - (value % dec.step);
			}
		}*/

		js_log('update:local_settings:custom:conf:'+ confKey + ' = ' + value);
		this.local_settings.pSet['custom']['conf'][confKey] = value;

		return value;
	},
	getLocalValue:function(confKey){
		return this.local_settings.pSet['custom']['conf'][confKey];
	},
	getClassId:function(elm, rmstr){
		var elmclass = $j(elm).attr("class").split(' ').slice(0,1).toString();
		if(rmstr){
			return elmclass.replace( rmstr, '' );
		}else{
			//strip leading underscore:
			return (elmclass[0]=='_')?elmclass.substr(1):elmclass;
		}
	},
	/*
	 * sets up the autoEncoder settings
	 */
	autoEncoderSettings:function(){
		var _this = this;
		//do the base encoder settings setup:
		this.basefogg_autoEncoderSettings();
		//make sure we are "encoding" if not display not a video file eror:
		if( this.encoder_settings['passthrough'] ){
			js_log("in passthrough mode (hide control)");
			//hide all controls
			//dispaly not encodable video
			$j(this.target_control_container).hide('slow');
			$j(this.target_passthrough_mode).show('slow');
			return ;
		}
		//restore display:
		$j(this.target_control_container).show('slow');
		$j(this.target_passthrough_mode).hide('slow');

		//do setup settings based on local_settings /default_encoder_config with sourceFileInfo
		//see: http://firefogg.org/dev/sourceInfo_example.html
		var setValues = function(k, val, maxVal) {
			if( k !== false){
				//update the value if unset:
				_this.updateLocalValue(k, val);
			}
			if( maxVal ){
				 //update the local range:
				if(_this.default_encoder_config[k].range){
					_this.default_encoder_config[k].range.local_max = maxVal;
				}
			}
		}
		//container level settings
		for(var i in this.sourceFileInfo){
			var val = this.sourceFileInfo[i];
			var k = false;
			var maxVal= false;
			switch(i){
				//do nothing with these:
				case 'bitrate':
					k = 'videoBitrate';
					maxVal = (val*2 > this.default_encoder_config[k])?this.default_encoder_config[k]:val*2;
				break;
			}
			setValues(k, val, maxVal);
		}
		//video stream settings
		for(var i in this.sourceFileInfo.video[0]){
			var val = this.sourceFileInfo.video[0][i];
			var k = false;
			var maxVal= false;
			switch(i){
				case 'width':
				case 'height':
					k = i;
					maxVal = val;
				break;
			}
			setValues(k, val, maxVal);
		}
		//audio stream settings, assumes for now thare is only one stream
		for(var i in this.sourceFileInfo.audio[0]){
			var val = this.sourceFileInfo.audio[0][i];
			var k = false;
			var maxVal= false;
			switch(i){
				case 'bitrate':
					k = 'audioBitrate';
					maxVal = (val*2 > this.default_encoder_config[k])?this.default_encoder_config[k]:val*2;
				break;
			}
			setValues(k, val, maxVal);
		}

		//set all values to new default ranges & update slider:
		$j.each(this.default_encoder_config, function(inx, val){
			if($j(_this.selector + ' ._'+inx).length!=0){
				if(typeof val.range != 'undefined'){
					//udate slider range
					var new_max = (val.range.local_max)?val.range.local_max: val.range.max
					$j( _this.selector + ' .slider_'+inx).slider('option', 'max', new_max);

					//update slider/input value:
					_this.updateInterfaceValue(inx, _this.local_settings.pSet['custom']['conf'][inx]);
				}
			}
		});
		//update values
		this.updateValuesInHtml();
	},
	doEncode:function(){
		//update the encoder settings (from local settings)
		pKey = this.local_settings.d;
		this.encoder_settings = this.local_settings.pSet[ pKey ].conf;
		this.basefogg_doEncode();
	},
	updateValuesInHtml:function(){
		js_log('updateValuesInHtml::');
		var _this = this;
		var pKey = this.local_settings.d;
		this.updatePresetSelection( pKey );

		//set the actual HTML & widgets based on any local settings values:
		$j.each(_this.local_settings.pSet['custom']['conf'], function(inx, val){
			if($j(_this.selector + ' ._'+inx).length !=0){
				$j(_this.selector + ' ._'+inx).val( val );
			}
		});
	},
	//restors settings from a cookie if you have them)
	loadEncSettings:function( force ){
		if($j.cookie('fogg_encoder_config')){
			js_log("load:fogg_encoder_config from cookie ");
			this.local_settings = JSON.parse( $j.cookie('fogg_settings') );
		}
		//set to default if not loaded yet:
		if( this.local_settings &&  this.local_settings.pSet && this.local_settings.pSet['custom']['conf']){
			js_log('local settings already populated');
		}else{
			this.local_settings  = this.default_local_settings;
		}

	},
	//clear preset settings:
	clearSettings:function(force){

	},
	//save settings to the cookie
	saveEncSettings:function(){
		$j.cookie('fogg_settings', JSON.stringify( this.local_settings ) );
	}
};
