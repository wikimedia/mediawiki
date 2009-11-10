/* adds firefogg support.
* autodetects: new upload api or old http POST.
 */

loadGM({
	"fogg-select_file" : "Select file",
	"fogg-select_new_file" : "Select new file",
	"fogg-select_url" : "Select URL",
	"fogg-save_local_file" : "Save Ogg",
	"fogg-check_for_fogg" : "Checking for Firefogg...",
	"fogg-installed" : "Firefogg is installed",
	"fogg-for_improved_uplods" : "For improved uploads:",
	"fogg-please_install" : "<a href=\"$1\">Install Firefogg<\/a>. More <a href=\"http:\/\/commons.wikimedia.org\/wiki\/Commons:Firefogg\">about Firefogg<\/a>.",
	"fogg-use_latest_fox" : "Please first install <a href=\"http:\/\/www.mozilla.com\/en-US\/firefox\/upgrade.html?from=firefogg\">Firefox 3.5<\/a> (or later). <i>Then revisit this page to install the <b>Firefogg<\/b> extension.<\/i>",
	"fogg-passthrough_mode" : "Your selected file is already Ogg or not a video file",
	"fogg-transcoding" : "Encoding video to Ogg...",
	"fogg-encoding-done" : "Encoding complete",
	"fogg-badtoken" : "Token is not valid",
	"fogg-preview" : "Preview video",
	"fogg-hidepreview" : "Hide preview"
});

var firefogg_install_links =  {
	'macosx':	'http://firefogg.org/macosx/Firefogg.xpi',
	'win32':	'http://firefogg.org/win32/Firefogg.xpi',
	'linux' :	'http://firefogg.org/linux/Firefogg.xpi'
};

var default_firefogg_options = {
	// What to do when finished uploading
	'done_upload_cb':false,
	// If firefoog is enabled
	'fogg_enabled':false,
	// The api url to upload to
	'api_url':null,
	// The passthrough flag (enables un-modified uploads)
	'passthrough': false,
	// If we will be showing the encoder interface
	'encoder_interface': false,
	// If we want to limit the library functionality to "only firefoog" (no upload or progress bars)
	'only_fogg': false,

	// Callbacks:
	'new_source_cb': false, //called on source name update passes along source name

	// Target control container or form (can't be left null)
	'selector': '',

	// If not rewriting a form we are encoding local.
	'form_rewrite': false,

	// Target buttons:
	'target_btn_select_file': false,
	'target_btn_select_new_file': false,

	//'target_btn_select_url': false,
	'target_btn_save_local_file': false,
	'target_input_file_name': false,


	// Target install descriptions
	'target_check_for_fogg': false,
	'target_installed': false,
	'target_please_install': false,
	'target_use_latest_fox': false,
	
	// Status:
	'target_passthrough_mode':false,

	// If firefogg should take over the form submit action
	'firefogg_form_action':true,
	
	// If we should show a preview of encoding progress 
	'show_preview':false
}


var mvFirefogg = function(iObj){
	return this.init( iObj );
}
mvFirefogg.prototype = { //extends mvBaseUploadInterface

	min_firefogg_version : '0.9.9.5',
	fogg_enabled : false,		 //if firefogg is enabled or not.
	encoder_settings:{			//@@todo allow server to set this
		'maxSize'        : '400',
        'videoBitrate'   : '544',
        'audioBitrate'   : '96',
        'noUpscaling'    : true
	},
	sourceFileInfo: {},
	ogg_extensions: ['ogg', 'ogv', 'oga'],
	video_extensions: ['avi', 'mov', 'mp4', 'mp2', 'mpeg', 'mpeg2', 'mpeg4', 'dv', 'wmv'],

	passthrough: false,
	sourceMode: 'file',

	init: function( iObj ){
		if(!iObj)
			iObj = {};

		// If we have no api_url set upload to "post"
		if(!iObj.api_url)
			iObj.upload_mode = 'post';

		// Inherit iObj properties:
		for(var i in default_firefogg_options){
			if(iObj[i]){
				this[i] = iObj[i];
			}else{
				this[i] = default_firefogg_options[i];
			}
		}
		// Check if we want to limit the usage:
		if(!this.only_fogg){
			var myBUI = new mvBaseUploadInterface( iObj );

			// Standard extends code:
			for(var i in myBUI){
				if(this[i]){
					this['pe_'+ i] = myBUI[i];
				}else{
					this[i] =  myBUI[i];
				}
			}
		}

		if(!this.selector){
			js_log('firefogg: missing selector ');
		}
	},
	doRewrite:function( callback ){
		var _this = this;
		js_log('sel len: ' + this.selector + '::' + $j(this.selector).length + ' tag:'+ $j(this.selector).get(0).tagName);
		if( $j(this.selector).length >=0 ){

			if( $j(this.selector).get(0).tagName.toLowerCase() == 'input' ){
				_this.form_rewrite = true;
			}
		}
		// Check if we are rewriting an input or a form:
		if( this.form_rewrite ){
			this.setupForm();
		}else{
			this.doControlHTML();
			this.doControlBindings();
		}

		// doRewrite is done:
		if(callback)
			callback();
	},
	doControlHTML: function( ){
		var _this = this;
		var out = '';
		$j.each(default_firefogg_options, function(target, na){
			if(target.substring(0, 6)=='target'){
				//js_log('check for target html: ' + target);
				// Check for the target if missing add to the output:
				if( _this[target] === false){
					out += _this.getTargetHtml(target) + ' ';
					// Update the target selector
				    _this[target] = _this.selector + ' .' + target;
				}
			}
		});
		$j( this.selector ).append( out ).hide();
	},
	getTargetHtml:function(target){
		if( target.substr(7,3)=='btn'){
			return '<input style="" class="' + target + '" type="button" value="' + gM( 'fogg-' + target.substring(11)) + '"/> ';
		}else if(target.substr(7,5)=='input'){
			return '<input style="" class="' + target + '" type="text" value="' + gM( 'fogg-' + target.substring(11)) + '"/> ';
		}else{
			return '<div style="" class="' + target + '" >'+ gM('fogg-'+ target.substring(7)) + '</div> ';
		}
	},
	doControlBindings: function(){
		var _this = this;

		// Hide all targets:
		var hide_target_list='';
		var coma='';
		$j.each( default_firefogg_options, function(target, na) {
			if(target.substring(0, 6)=='target'){
				hide_target_list+=coma + _this[target];
				coma=',';
			}
		});
		
		// Hide all but check-for-fogg
		$j( hide_target_list ).hide();
		// Now that the proper set of items has been hiiden show:
		$j( _this.selector ).show();
		
		// Check for firefogg
		if( _this.firefoggCheck() ){

			// If rewriting the form lets keep the text input around:
			if( _this.form_rewrite )
				$j( _this.target_input_file_name ).show();

			// Show select file:
			$j( this.target_btn_select_file ).unbind(
				).attr('disabled', false
				).css({'display':'inline'}
				).click(function(){
					_this.selectFogg();
				});

		    //also setup the text file display on Click to select file:
		    $j(this.target_input_file_name).unbind().attr('readonly', 'readonly').click(function(){
		        _this.selectFogg();
		    })

		}else{
			// First check firefox version:
			if(!( $j.browser.mozilla && $j.browser.version >= '1.9.1' )) {
				js_log( 'show use latest::' + _this.target_use_latest_fox );
				if( _this.target_use_latest_fox ){
					if( _this.form_rewrite )
						$j( _this.target_use_latest_fox ).prepend( gM('fogg-for_improved_uplods') );

					$j( _this.target_use_latest_fox ).show();
				}
				return ;
			}

			// If rewriting form use upload msg text
			var upMsg = (_this.form_rewrite) ? gM('fogg-for_improved_uplods') : '';
			$j( _this.target_please_install ).html( upMsg + gM('fogg-please_install', _this.getOSlink() )).css('padding', '10px').show();
		}
		// Setup the target save local file bindings:
		$j( _this.target_btn_save_local_file ).unbind().click(function(){
			_this.saveLocalFogg();
		});
	},
	/*
	 * returns the firefogg link for your os:
	 */
	getOSlink:function(){
		var os_link = false;
		if(navigator.oscpu){
			if(navigator.oscpu.search('Linux') >= 0)
				os_link = firefogg_install_links['linux'];
			else if(navigator.oscpu.search('Mac') >= 0)
				  os_link = firefogg_install_links['macosx'];
			else if(navigator.oscpu.search('Win') >= 0)
				  os_link = firefogg_install_links['win32'];
		}
		return os_link
	},
	firefoggCheck:function(){
		if(typeof(Firefogg) != 'undefined' && Firefogg().version >= this.min_firefogg_version){
			this.fogg = new Firefogg();
			this.fogg_enabled = true;
			return true;
		}else{
			return false;
		}
	},
	// Assume input target
	setupForm: function(){
		js_log('firefogg::setupForm::');
		//to parent form setup if we want http updates
		if( this.form_rewrite ){
			//do parent form setup:
			this.pe_setupForm();
		}

		// Check if we have firefogg (if not just add a link and stop processing)
		if( !this.firefoggCheck() ){
			//add some status indicators if not provided:
			if(!this.target_please_install){
				$j(this.selector).after ( this.getTargetHtml('target_please_install') );
				this.target_please_install = this.selector + ' ~ .target_please_install';
			}
			if(!this.target_use_latest_fox){
				$j(this.selector).after ( this.getTargetHtml('target_use_latest_fox') );
				this.target_use_latest_fox = this.selector + ' ~ .target_use_latest_fox';
			}
			// Update download link:
			this.doControlBindings();
			return ;
		}

		// Change the file browser to type text: (can't directly change input from "file" to "text" so longer way:
		var inTag = '<input ';
		$j.each($j(this.selector).get(0).attributes, function(i, attr){
			var val = attr.value;
			if( attr.name == 'type')
				val = 'text';
			inTag += attr.name + '="' + val + '" ';
		});
		if(!$j(this.selector).attr('style'))
			inTag += 'style="display:inline" ';

		inTag+= '/><span id="' + $j(this.selector).attr('name') + '_fogg-control"></span>';

		js_log('set input: ' + inTag);
		$j(this.selector).replaceWith(inTag);

		this.target_input_file_name = 'input[name=' + $j(this.selector).attr('name') + ']';
		
		// Update the selector to the control target:
		this.selector = '#' + $j(this.selector).attr('name') +  "_fogg-control";

		this.doControlHTML();

		// Update the bindings:
		this.doControlBindings();
	},
	// Do firefogg specific additions: 
	dispProgressOverlay:function(){
		this.pe_dispProgressOverlay();			
		// If we are uploading video (not in passthrough mode show preview button)
		if( this.fogg_enabled && ! this.encoder_settings['passthrough']  && ! this.http_copy_upload ){ 
			this.doPreviewControl();	
		}
	},
	doPreviewControl:function(){
		var _this = this;
		// Prepend preview (if fogg)				
		$j('#upProgressDialog').append(		
			'<div style="clear:both;height:3em"/>'+							
			$j.btnHtml(gM('fogg-preview'), 'fogg_preview', 'triangle-1-e') + 
			'<div style="padding:10px;">' +  				
				'<canvas style="margin:auto;" id="fogg_preview_canvas" />' +
			'</div>'
		);
		// Set initial state
		if( _this.show_preview == true){
			$j('#fogg_preview_canvas').show();
		}else{
			// Update icon:
			$j(this).children('.ui-icon')
				.removeClass('ui-icon-triangle-1-s')
				.addClass('ui-icon-triangle-1-e');									
			// Update text:
			$j(this).children('.btnText').text( gM('fogg-preview') );				
			$j('#fogg_preview_canvas').hide();
		}
		// Apply preview binding:
		$j('#upProgressDialog .fogg_preview').btnBind().click( function(){
			js_log("click .foog_preview" + $j(this).children('.ui-icon').attr('class') );
			// Check state: 
			if( $j(this).children('.ui-icon').hasClass('ui-icon-triangle-1-e') ){
				_this.show_preview = true; 			
				// Update icon:
				$j(this).children('.ui-icon')
					.removeClass('ui-icon-triangle-1-e')
					.addClass('ui-icon-triangle-1-s'); 
				// Update text
				$j(this).children('.btnText').text( gM('fogg-hidepreview') );
				 
				// Show preview window			
				$j('#fogg_preview_canvas').show('fast');
			}else{					
				_this.show_preview = false; 						
				// Update icon:
				$j(this).children('.ui-icon')
					.removeClass('ui-icon-triangle-1-s')
					.addClass('ui-icon-triangle-1-e');									
				// Update text:
				$j(this).children('.btnText').text( gM('fogg-preview') );				
				
				$j('#fogg_preview_canvas').hide('slow');
			}
			// Don't follow the #
			return false; 
		});
	}, 
	doRenderPreview:function(){
		var _this = this;		
		// Set up the hidden video to pull frames from: 
		if( $j('#fogg_preview_vid').length == 0 )
			$j('body').append('<video id="fogg_preview_vid" style="display:none"></video>');	
		var v = $j('#fogg_preview_vid').get(0);							
		
		function seekToEnd(){			
		    var v = $j('#fogg_preview_vid').get(0);		    	
		    v.currentTime = v.duration-0.4;		
		}
		function getFrame() {
			var v = $j('#fogg_preview_vid').get(0);
			var canvas = $j('#fogg_preview_canvas').get(0);
			if( canvas ){
				canvas.width = 160;
				canvas.height = canvas.width * v.videoHeight/v.videoWidth;
				var ctx = canvas.getContext("2d");
				ctx.drawImage(v, 0, 0, canvas.width, canvas.height);
			}
  		}		
  		var previewI=null;
		function preview() {				
			// Initialize the video if not setup already:
			var v = $j('#fogg_preview_vid').get(0);						
			if( v.src != _this.fogg.previewUrl ){		
				js_log('init preview with url:' + _this.fogg.previewUrl); 		
				v.src = _this.fogg.previewUrl;
				
				// Set the video to seek to the end of the video
				v.removeEventListener("loadedmetadata", seekToEnd, true);
				v.addEventListener("loadedmetadata", seekToEnd, true);
				
				// Render a frame once seek is complete: 
				v.removeEventListener("seeked", getFrame, true);
				v.addEventListener("seeked", getFrame, true);
				
				// Refresh the video duration/meta:
				clearInterval(previewI);
				var previewI = setInterval(function(){
					if (_this.fogg.status() != "encoding"){
						clearInterval(previewI);
						_this.show_preview == false;
					}
					if (_this.show_preview == true) {
						v.load();
					}
				}, 1000);			 
			} 
		}		
		preview();	
	},
	getEditForm:function(){
		if( this.target_edit_from )
			return this.pe_getEditForm();
		// Else try to get the parent "from" of the file selector:
		return $j(this.selector).parents('form:first').get(0);
	},
	selectFogg:function(){
		var _this = this;
		if(_this.fogg.selectVideo() ){
			this.selectFoggActions();
		}
	},
	selectFoggActions:function(){
		var _this = this;
		js_log('videoSelectReady');
		// If not already hidden hide select file and show "select new":
		$j(_this.target_btn_select_file).hide();

		// Show and setup binding for select new file:
		$j(_this.target_btn_select_new_file).show().unbind().click(function(){
			//create new fogg instance:
			_this.fogg = new Firefogg();
			_this.selectFogg();
		});

		// Update if we are in passthrough mode or going to encode
		if( _this.fogg.sourceInfo && _this.fogg.sourceFilename  ){
			//update the source status
			try{
				_this.sourceFileInfo = JSON.parse( _this.fogg.sourceInfo ) ;
			}catch (e){
				js_error('error could not parse fogg sourceInfo');
			}

			// Now setup encoder settings based source type:
			_this.autoEncoderSettings();

			// If set to passthough update the interface (if not a form) 
			if(_this.encoder_settings['passthrough'] == true && !_this.form_rewrite){
				$j(_this.target_passthrough_mode).show();
			}else{
				$j(_this.target_passthrough_mode).hide();
				// If set to encoder expose the encode button:
				if( !_this.form_rewrite ){
					$j(_this.target_btn_save_local_file).show();
				}
			}
			//~otherwise the encoding will be triggered by the form~

			// Do source name update callback:
			js_log(" should update: " + _this.target_input_file_name + ' to: ' + _this.fogg.sourceFilename );
			$j(_this.target_input_file_name).val(_this.fogg.sourceFilename).show();

			if(_this.new_source_cb){
				if(_this.encoder_settings['passthrough']){
					var fName = _this.fogg.sourceFilename
				}else{
				    var oggExt = (_this.isSourceAudio())?'oga':'ogg';
                    oggExt = (_this.isSourceVideo())?'ogv':oggExt;
                    oggExt = (_this.isUnknown())?'ogg':oggExt;
				    oggName = _this.fogg.sourceFilename.substr(0,
				                  _this.fogg.sourceFilename.lastIndexOf('.'));
				    var fName = oggName +'.'+ oggExt
				}
				_this.new_source_cb( _this.fogg.sourceFilename , fName);
			}
		}
	},
	saveLocalFogg:function(){
	   // Request target location:
	   if(this.fogg){
		   if(!this.fogg.saveVideoAs() )
			   return false;

		  // We have set a target now call the encode:
		  this.doEncode();
	   }
	},
	// Simple auto encoder settings just enable passthough if file is not video or > 480 pixles tall
	autoEncoderSettings:function(){
		var _this = this;
		// Grab the extension:
		var sf = _this.fogg.sourceFilename;
		var ext = '';
		if(	sf.lastIndexOf('.') != -1)
			ext = sf.substring( sf.lastIndexOf('.')+1 ).toLowerCase();		

		// Set to passthrough to true by default (images, arbitrary files that we want to send with http chunks)
		this.encoder_settings['passthrough'] = true;

		// See if we have video or audio:
		if(  _this.isSourceAudio() || _this.isSourceVideo() )
			 _this.encoder_settings['passthrough'] = false;		

		// Special case see if we already have ogg video:
		if( _this.isOggFormat() )
			_this.encoder_settings['passthrough'] = true;
		
		js_log('base autoEncoderSettings::' + _this.sourceFileInfo.contentType  + ' passthrough:' + _this.encoder_settings['passthrough']);
	},
	isUnknown:function(){
		return (this.sourceFileInfo.contentType.indexOf("unknown") != -1);
	},
	isSourceAudio:function(){
	   return (this.sourceFileInfo.contentType.indexOf("audio/") != -1);
	},
	isSourceVideo:function(){
	    return (this.sourceFileInfo.contentType.indexOf("video/") != -1);
	},
	isOggFormat:function(){
	   return ( this.sourceFileInfo.contentType.indexOf("video/ogg") != -1 ||
	    		this.sourceFileInfo.contentType.indexOf("application/ogg") != -1   );
	},
	getProgressTitle:function(){
		js_log("fogg:getProgressTitle f:" + this.fogg_enabled  + ' rw:' + this.form_rewrite);
		// Return the parent if we don't have fogg turned on:
		if(! this.fogg_enabled || !this.firefogg_form_action )
			return this.pe_getProgressTitle();
		if( !this.form_rewrite )
		  return gM('fogg-transcoding');
		// Else return our upload+transcode msg:
		return gM('mwe-upload-transcode-in-progress');
	},
	doUploadSwitch:function(){
		var _this = this;		
		js_log("firefogg: doUploadSwitch:: " + this.fogg_enabled + ' up mode:' +  _this.upload_mode);
		// Make sure firefogg is enabled otherwise do parent UploadSwich:
		if( !this.fogg_enabled || !this.firefogg_form_action )
			return _this.pe_doUploadSwitch();

		// Check what mode to use firefogg in:
		if( _this.upload_mode == 'post' ){
			_this.doEncode();
		}else if( _this.upload_mode == 'api' ){ //if api mode and chunks supported do chunkUpload
			_this.doChunkUpload();
		}else{
			js_error( 'Error: unrecongized upload mode: ' + _this.upload_mode );
		}
	},
	/**
	* doChunkUpload
	* does both uploading and encoding at the same time and uploads one meg chunks
	*/
	doChunkUpload : function(){
		js_log('firefogg::doChunkUpload');
		var _this = this;
		_this.action_done = false;
		
		// File extension should already be ogg but since its user editable,
		// (should not be needed for passthrough mode)
		var sf = _this.formData['filename'];
		var ext = '';
		if(	sf.lastIndexOf('.') != -1){
			ext = sf.substring( sf.lastIndexOf('.') ).toLowerCase();
		}
		if( !_this.encoder_settings['passthrough'] && $j.inArray(ext.substr(1), _this.ogg_extensions) == -1 ){
			var extreg = new RegExp(ext + '$', 'i');
			_this.formData['filename'] = sf.replace(extreg, '.ogg');
		}
		// Add chunk response hook to build the resultURL when uploading chunks

		// Check for editToken:
		if(!this.etoken){
			js_log('missing token try ' + _this.formData['token']);
			if( _this.formData['token']){
				_this.etoken = _this.formData['token'];
				_this.doChunkWithFormData();
			}else{
				get_mw_token(
					'File:'+ _this.formData['filename'],
					_this.api_url,
					function( eToken ){
						if( !eToken || eToken == '+\\' ){
							_this.updateProgressWin( gM('fogg-badtoken'), gM('fogg-badtoken') );
							return false;
						}
						_this.etoken = eToken;
						_this.doChunkWithFormData();
					}
				);
			}
		}else{
			js_log('we already have token: ' + this.etoken);
			_this.doChunkWithFormData();
		}
	},
	doChunkWithFormData:function(){
		var _this = this;
		js_log("firefogg::doChunkWithFormData"  + _this.etoken);
		// Build the api url:
		var aReq ={
			'action': 'upload',
			'format': 'json',
			'filename': _this.formData['filename'],
			'comment': _this.formData['comment'],
			'enablechunks': 'true'
		};

		if( _this.etoken )
			aReq['token'] = this.etoken;

		if( _this.formData['watch'] )
			aReq['watch'] =  _this.formData['watch'];

		if(  _this.formData['ignorewarnings'] )
			aReq['ignorewarnings'] = _this.formData['ignorewarnings'];

		js_log('do fogg upload/encode call: '+ _this.api_url + ' :: ' + JSON.stringify( aReq ) );
		js_log('foggEncode: '+ JSON.stringify( _this.encoder_settings ) );
		_this.fogg.upload( JSON.stringify( _this.encoder_settings ),  _this.api_url ,  JSON.stringify( aReq ) );

		// Update upload status:
		_this.doUploadStatus();
	},
	// doEncode and monitor progress:
	doEncode : function(){
		var _this = this;
		_this.action_done = false;
		_this.dispProgressOverlay();
		js_log('doEncode: with: ' +  JSON.stringify( _this.encoder_settings ) );
		_this.fogg.encode( JSON.stringify( _this.encoder_settings ) );

		 // Show transcode status:
		$j('#up-status-state').html( gM('mwe-upload-transcoded-status') );

		// Setup a local function for timed callback:
		var encodingStatus = function() {
			var status = _this.fogg.status();

			if( _this.show_preview == true && _this.fogg.state == 'encoding'){
				_this.doRenderPreview();
			}

			// Update progress bar
			_this.updateProgress( _this.fogg.progress() );

			// Loop to get new status if still encoding
			if( _this.fogg.state == 'encoding' ) {
				setTimeout(encodingStatus, 500);
			}else if ( _this.fogg.state == 'encoding done' ) { //encoding done, state can also be 'encoding failed
				_this.encodeDone();
			}else if(_this.fogg.state == 'encoding fail'){
				//@@todo error handling:
				js_error('encoding failed');
			}
		}
		encodingStatus();
	},
	encodeDone:function(){
		var _this = this;
		js_log('::encodeDone::');
		_this.action_done = true;
		
		// Send to the post url:
		if( _this.form_rewrite && _this.upload_mode == 'post' ){
			js_log('done with encoding do POST upload:' + _this.editForm.action);
			// ignore warnings & set source type
			//_this.formData[ 'wpIgnoreWarning' ]='true';
			_this.formData[ 'wpSourceType' ] = 'upload';
			_this.formData[ 'action' ]		 = 'submit';
			//wpUploadFile is set by firefogg
			delete _this.formData[ 'file' ];

			_this.fogg.post( _this.editForm.action, 'wpUploadFile', JSON.stringify( _this.formData ) );
			// Update upload status:
			_this.doUploadStatus();
		}else{
			js_log("done with encoding (no upload) ");						
			// Set status to 100% for one second:
			_this.updateProgress( 1 );			
			setTimeout(function(){
				_this.updateProgressWin(gM('fogg-encoding-done'), 	
					gM('fogg-encoding-done') + '<br>' + 				
					// Show the video at full resolution up-to 720px wide
					'<video controls="true" style="margin:auto" id="fogg_final_vid" src="' + 
						 _this.fogg.previewUrl + '"></video>'					
				);
				//load the video and set a callback: 
				var v = $j('#fogg_final_vid').get(0);
				function resizeVid(){
					var v = $j('#fogg_final_vid').get(0);					
					if( v.videoWidth > 720 ){
						var vW = 720;
						var vH = 720 * (v.videoHeight/v.videoWidth)													
					}else{
						var vW = v.videoWidth;
						var vH = v.videoHeight;												
					}
					// Resize the video:
					$j(v).css({
						'width'	: vW,
						'height': vH					
					});
					// If large video resize the dialog box:
					if( vW + 5 > 400){ 
						//also resize the dialog box
						$j('#upProgressDialog').dialog('option', 'width', vW + 20);	
						$j('#upProgressDialog').dialog('option', 'height', 	vH + 120);	 			
						
						//also position the dialog container				
						$j('#upProgressDialog').dialog('option', 'position', 'center');
					}
				}								 
				v.removeEventListener("loadedmetadata", resizeVid, true);
				v.addEventListener("loadedmetadata", resizeVid, true);
				v.load();				
			}, 1000);
		}
	},
	doUploadStatus:function() {
		var _this = this;
		$j( '#up-status-state' ).html( gM('mwe-uploaded-status')  );

		_this.oldResponseText = '';
		// Setup a local function for timed callback:
		var uploadStatus = function(){
			// Get the response text:
			var response_text =  _this.fogg.responseText;
			if(!response_text){
				   try{
					   var pstatus = JSON.parse( _this.fogg.uploadstatus() );
					   response_text = pstatus["responseText"];
				   }catch(e){
					   js_log("could not parse uploadstatus / could not get responseText");
				   }
			}

			if( _this.oldResponseText != response_text){
				js_log('new result text:' + response_text + ' state:' + _this.fogg.state);
				_this.oldResponseText = response_text;
				// Try and parse the response text and check for errors
				try{
					var apiResult = JSON.parse( response_text );
				}catch(e){
					js_log("could not parse response_text::" + response_text + ' ...for now try with eval...');
					try{
						var apiResult = eval( response_text );
					}catch(e){
						var apiResult = null;
					}					
				}
				if(apiResult && _this.apiUpdateErrorCheck( apiResult ) === false){
					// Stop status update we have an error
					_this.action_done = true;
					_this.fogg.cancel();
					return false;
				}
			}
			if( _this.show_preview == true ){
				if(  _this.fogg.state == 'encoding' ){
					_this.doRenderPreview();
				}
			}
			
			// Update progress bar
			_this.updateProgress( _this.fogg.progress() );

			// Loop to get new status if still uploading (could also be encoding if we are in chunk upload mode)
			if( _this.fogg.state == 'encoding' || _this.fogg.state == 'uploading') {
				setTimeout(uploadStatus, 100);
			}// Check upload state
			else if( _this.fogg.state == 'upload done' ||
						 _this.fogg.state == 'done' ||
						 _this.fogg.state == 'encoding done' ) {
				   // If in "post" upload mode read the html response (should be deprecated):
				   if( _this.upload_mode == 'api' ){
					   if( apiResult && apiResult.resultUrl ){
					   		var buttons ={};
					   		buttons[gM('mwe-go-to-resource')] =  function(){
								window.location = apiResult.resultUrl;
							}
							var go_to_url_txt = gM('mwe-go-to-resource');
						   	if( typeof _this.done_upload_cb == 'function' ){
						   		// If done action return 'true'
								if( _this.done_upload_cb( _this.formData ) ){
									// Update status
						   			_this.updateProgressWin( gM('mwe-successfulupload'),  gM( 'mwe-upload_done', apiResult.resultUrl),buttons);
								}else{
									// If done action returns 'false' //close progress window
									this.action_done = true;
						  	       	$j('#upProgressDialog').empty().dialog('close');
								}
						   	}else{
						   		// Update status (without done_upload_cb)
						   		_this.updateProgressWin( gM('mwe-successfulupload'),  gM( 'mwe-upload_done', apiResult.resultUrl),buttons);
						   	}
					   }else{
						   // Done state with error? ..not really possible given how firefogg works
						   js_log(" Upload done in chunks mode, but no resultUrl!");
					   }
				   }else{
					   js_log("Error:: not supported upload mode" +  _this.upload_mode);
				   }
			}else{
				// Upload error:
				js_log('Error:firefogg upload error: ' + _this.fogg.state );
		   }
	   }
	   uploadStatus();
	},
	cancel_action:function( dlElm ){
		if(!this.fogg_enabled){
			return this.pe_cancel_action();
		}
	  	js_log('firefogg:cancel')
	  	if( confirm( gM('mwe-cancel-confim') )){
	  	    if(navigator.oscpu && navigator.oscpu.search('Win') >= 0){
	  	         alert( 'sorry we do not yet support cancel on windows' );
	  	    }else{
	  	    	this.action_done = true;
	  	        this.fogg.cancel();
	  	        $j(dlElm).empty().dialog('close');	  	       
	  	    }
	  	}
	  	// Dont' follow the link:
	  	return false;
	}
};
