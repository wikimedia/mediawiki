/* adds firefogg support. 
* autodetects: new upload api or old http POST.  
 */

loadGM({ 
	"fogg-select_file"			: "Select File", 
	"fogg-select_new_file"		: "Select New File",
	"fogg-select_url"			: "Select Url",
	"fogg-save_local_file"		: "Save Ogg",
	"fogg-check_for_fogg"		: "Checking for Firefogg <blink>...</blink>",
	"fogg-installed"			: "Firefogg is Installed",
	"fogg-for_improved_uplods"	: "For Improved uploads: ",
	"fogg-please_install"		: "<a href=\"$1\">Install Firefogg</a>. More <a href=\"http://commons.wikimedia.org/wiki/Commons:Firefogg\">about firefogg</a>",	
	"fogg-use_latest_fox"		: "Please first install <a href=\"http://www.mozilla.com/en-US/firefox/upgrade.html?from=firefogg\">Firefox 3.5</a> (or later). <i>then revisit this page to install the <b>firefogg</b> extention</i>",	
	"fogg-passthrough_mode"	    : "Your selected file is already ogg or not a video file",
	"fogg-transcoding"			: "Encoding Video to Ogg",
	"fogg-encoding-done"		: "Encoding Done",
	"fogg-badtoken"				: "Token is not valid"
	
});

var firefogg_install_inks =  {
	'macosx':	'http://firefogg.org/macosx/Firefogg.xpi',
	'win32'	:	'http://firefogg.org/win32/Firefogg.xpi',
	'linux' :	'http://firefogg.org/linux/Firefogg.xpi'
};

var default_firefogg_options = {
	//what to do when finished uploading
	'done_upload_cb':false,
	//if firefoog is enabled
	'fogg_enabled':false,
	//the api url to upload to
	'api_url':null,
	//the passthrough flag (enables un-modified uploads)
	'passthrough': false,
	//if we will be showing the encoder interface
	'encoder_interface': false,
	//if we want to limit the library functionality to "only firefoog" (no upload or progress bars) 
	'only_fogg': false,
	
	
	//callbacks:
	'new_source_cb': false, //called on source name update passes along source name
	
	//target control container or form (can't be left null)
	'selector'			: '',
	
	//if not rewriting a form we are encoding local.
	'form_rewrite'		: false, 
	
	//taget buttons: 
	'target_btn_select_file'	: false,
	'target_btn_select_new_file': false,	
	
	//'target_btn_select_url'		: false,
	
	'target_btn_save_local_file': false,
	'target_input_file_name'	: false,	
	
	
	//target install descriptions 
	'target_check_for_fogg'		: false,
	'target_installed'	: false,
	'target_please_install'	: false,
	'target_use_latest_fox': false,
	//status: 
	'target_passthrough_mode':false,
	
	//if firefogg should take over the form submit action
	'firefogg_form_action':true
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
	sourceFileInfo	: {},
	ogg_extensions	: ['ogg', 'ogv', 'oga'],
	video_extensions: ['avi', 'mov', 'mp4', 'mp2', 'mpeg', 'mpeg2', 'mpeg4', 'dv', 'wmv'],
	
	passthrough	: false,
	sourceMode	: 'file',
	
	init: function( iObj ){
		if(!iObj)
			iObj = {};
			
		//if we have no api_url set upload to "post" 
		if(!iObj.api_url)
			iObj.upload_mode = 'post'; 
			
		//inherit iObj properties:
		for(var i in default_firefogg_options){
			if(iObj[i]){
				this[i] = iObj[i];
			}else{
				this[i] = default_firefogg_options[i];
			}
		}
		//check if we want to limit the usage:
		if(!this.only_fogg){
			var myBUI = new mvBaseUploadInterface( iObj );
			
			//standard extends code: 
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
		//check if we are rewriting an input or a form:
		if( this.form_rewrite ){
			this.setupForm();
		}else{
			this.doControlHTML();	 
			this.doControlBindings();	
		}
		
		//doRewrite is done: 
		if(callback)
			callback();
	},
	doControlHTML: function( ){		
		var _this = this;		
		var out = '';		
		$j.each(default_firefogg_options, function(target, na){			
			if(target.substring(0, 6)=='target'){
				//js_log('check for target html: ' + target);
				//check for the target if missing add to the output: 
				if( _this[target] === false){					
					out += _this.getTargetHtml(target) + ' ';
					//update the target selector 
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
		
		//hide all targets:
		var hide_target_list='';
		var coma='';
		$j.each(default_firefogg_options, function(target, na){	
			if(target.substring(0, 6)=='target'){
				hide_target_list+=coma + _this[target];
				coma=',';
			}			
		});				
		$j( hide_target_list ).hide();				
		//now that the proper set of items has been hiiden show: 
		$j( this.selector ).show();
			
				
		//hide all but check-for-fogg
		//check for firefogg
		if( _this.firefoggCheck() ){
			
			//if rewriting the form lets keep the text input around:						 
			if( _this.form_rewrite )
				$j(this.target_input_file_name).show();
			
			//show select file: 
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
			//first check firefox version:		 
			if(!( $j.browser.mozilla && $j.browser.version >= '1.9.1' )) {
				js_log( 'show use latest::' + _this.target_use_latest_fox );
				if( _this.target_use_latest_fox ){
					if( _this.form_rewrite )
						$j( _this.target_use_latest_fox ).prepend( gM('fogg-for_improved_uplods') );
							 
					$j( _this.target_use_latest_fox ).show();
				}
				return ;
			}		
													
			//if rewriting form use upload msg text
			var upMsg = (_this.form_rewrite) ? gM('fogg-for_improved_uplods') : '';			
			$j( _this.target_please_install ).html( upMsg + gM('fogg-please_install', _this.getOSlink() )).css('padding', '10px').show();			
		}
		//setup the target save local file bindins: 
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
	//assume input target
	setupForm: function(){		
		js_log('firefogg::setupForm::');
		//to parent form setup if we want http updates 
		if( this.form_rewrite ){
			//do parent form setup: 
			this.pe_setupForm();		
		}
		
		//check if we have firefogg (if not just add a link and stop proccessing) 
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
			//update download link:
			this.doControlBindings();		
			return ;
		}
		
		//change the file browser to type text: (can't directly change input from "file" to "text" so longer way:
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
		//update the selector to the control target: 
		this.selector = '#' + $j(this.selector).attr('name') +  "_fogg-control";
		
		this.doControlHTML();
		//check for the other inline status indicator targets:		 
		
		//update the bindings: 
		this.doControlBindings();
	},
	getEditForm:function(){
		if( this.target_edit_from )
			return this.pe_getEditForm();
		//else try to get the parent "from" of the file selector:  
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
		//if not already hidden hide select file and show "select new": 
		$j(_this.target_btn_select_file).hide();
		
		//show and setup binding for select new file: 
		$j(_this.target_btn_select_new_file).show().unbind().click(function(){
			//create new fogg instance:				 
			_this.fogg = new Firefogg();
			_this.selectFogg();
		});
		
		//update if we are in passthrough mode or going to encode					
		if( _this.fogg.sourceInfo && _this.fogg.sourceFilename  ){									
			//update the source status
			try{
				_this.sourceFileInfo = JSON.parse( _this.fogg.sourceInfo ) ;
			}catch (e){
				js_error('error could not parse fogg sourceInfo');
			}					
					
			//now setup encoder settings based source type:
			_this.autoEncoderSettings();					
			
			//if set to passthough update the interface:
			if(_this.encoder_settings['passthrough']==true){
				$j(_this.target_passthrough_mode).show();
			}else{					
				$j(_this.target_passthrough_mode).hide();	
				//if set to encoder expose the encode button: 
				if( !_this.form_rewrite ){
					$j(_this.target_btn_save_local_file).show();
				}
			}
			//~otherwise the encoding will be triggered by the form~
			
			//do source name update callback:	
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
	   //request target location:
	   if(this.fogg){
		   if(!this.fogg.saveVideoAs() )
			   return false;
		   
		   //we have set a target now call the encode: 
		  this.doEncode();		   
	   }  
	},
	//simple auto encoder settings just enable passthough if file is not video or > 480 pixles tall 
	autoEncoderSettings:function(){		
		var _this = this;
		//grab the extension:
		var sf = _this.fogg.sourceFilename;						
		var ext = '';
		if(	sf.lastIndexOf('.') != -1){
			ext = sf.substring( sf.lastIndexOf('.')+1 ).toLowerCase();
		}	
				  
		//set to passthrough to true by default (images, arbitrary files that we want to send with http chunks) 
		this.encoder_settings['passthrough'] = true;
		
		//see if we have video or audio:  
		if(  _this.isSourceAudio() || _this.isSourceVideo() ){
			 _this.encoder_settings['passthrough'] = false; 
		}
							
		//special case see if we already have ogg video: 
		if( _this.isOggFormat() ){
			_this.encoder_settings['passthrough'] = true;
		}		
				 
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
		//return the parent if we don't have fogg turned on: 
		if(! this.fogg_enabled || !this.firefogg_form_action )
			return this.pe_getProgressTitle();				   
		if( !this.form_rewrite )
		  return gM('fogg-transcoding');
		//else return our upload+transcode msg:				
		return gM('upload-transcode-in-progress');
	},	
	doUploadSwitch:function(){				
		var _this = this;
		js_log("firefogg: doUploadSwitch:: " + this.fogg_enabled + ' up mode:' +  _this.upload_mode);
		//make sure firefogg is enabled otherwise do parent UploadSwich:		
		if( !this.fogg_enabled || !this.firefogg_form_action )
			return _this.pe_doUploadSwitch();
		
		//check what mode to use firefogg in: 
		if( _this.upload_mode == 'post' ){
			_this.doEncode();
		}else if( _this.upload_mode == 'api' && _this.chunks_supported){ //if api mode and chunks supported do chunkUpload
			_this.doChunkUpload();
		}else{
			js_error( 'Error: unrecongized upload mode: ' + _this.upload_mode );
		}		
	},
	//doChunkUpload does both uploading and encoding at the same time and uploads one meg chunks as they are ready
	doChunkUpload : function(){
		js_log('doChunkUpload::');
		var _this = this;					
		_this.action_done = false;					
		//extension should already be ogg but since its user editable,
		//check again
		//we are transcoding so we know it will be an ogg
		//(should not be done for passthrough mode)
		var sf = _this.formData['wpDestFile'];
		var ext = '';
		if(	sf.lastIndexOf('.') != -1){
			ext = sf.substring( sf.lastIndexOf('.') ).toLowerCase();
		}
		if(!_this.encoder_settings['passthrough'] && $j.inArray(ext.substr(1), _this.ogg_extensions) == -1 ){		
			var extreg = new RegExp(ext + '$', 'i');
			_this.formData['wpDestFile'] = sf.replace(extreg, '.ogg');
		}
		//add chunk response hook to build the resultURL when uploading chunks		
				
		//check for editToken:
		if(!this.etoken){
			if( _this.formData['wpEditToken']){
				_this.etoken = _this.formData['wpEditToken'];
				_this.doChunkWithFormData();
			}else{
				get_mw_token(
					'File:'+ _this.formData['wpDestFile'], 
					_this.api_url, 
					function( eToken ){		
						if( !eToken || eToken == '+\\' ){
							_this.updateProgressWin(gM('fogg-badtoken'), gM('fogg-badtoken'));
							return false;
						}							
						_this.etoken = eToken;
						_this.doChunkWithFormData();
					}
				);
			}
		}else{
			_this.doChunkWithFormData();
		}
	},
	doChunkWithFormData:function(){
		var _this = this;
		js_log("doChunkWithFormData::"  + _this.etoken);
		//build the api url: 
		var aReq ={
			'action'		: 'upload',
			'format'		: 'json',
			'filename'		: _this.formData['wpDestFile'],
			'comment'		: _this.formData['wpUploadDescription'],
			'enablechunks'	: 'true'
		};
		
		if( _this.etoken )
			aReq['token'] = this.etoken;
		
		if( _this.formData['wpWatchthis'] )
			aReq['watch'] =  _this.formData['wpWatchthis'];
		
		if(  _this.formData['wpIgnoreWarning'] )
			aReq['ignorewarnings'] = _this.formData['wpIgnoreWarning'];
		
		js_log('do fogg upload/encode call: '+ _this.api_url + ' :: ' + JSON.stringify( aReq ) );			
		js_log('foggEncode: '+ JSON.stringify( _this.encoder_settings ) );			
		_this.fogg.upload( JSON.stringify( _this.encoder_settings ),  _this.api_url ,  JSON.stringify( aReq ) );		
			
		//update upload status:						
		_this.doUploadStatus();
	},
	//doEncode and monitor progress:
	doEncode : function(){	
		var _this = this;
		_this.action_done = false;
		_this.dispProgressOverlay();				
		js_log('doEncode: with: ' +  JSON.stringify( _this.encoder_settings ) );
		_this.fogg.encode( JSON.stringify( _this.encoder_settings ) );			  
		
		
		 //show transcode status:
		$j('#up-status-state').html( gM('upload-transcoded-status') );
		
		//setup a local function for timed callback:
		var encodingStatus = function() {
			var status = _this.fogg.status();
			
			//update progress bar
			_this.updateProgress( _this.fogg.progress() );			
			
			//loop to get new status if still encoding
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
		//send to the post url:				 
		if( _this.form_rewrite && _this.upload_mode == 'post' ){
			js_log('done with encoding do POST upload:' + _this.editForm.action);								
			// ignore warnings & set source type 
			//_this.formData[ 'wpIgnoreWarning' ]='true';
			_this.formData[ 'wpSourceType' ] = 'upload';		
			_this.formData[ 'action' ]		 = 'submit';
			//wpUploadFile is set by firefogg
			delete _this.formData[ 'wpUploadFile' ];           

			_this.fogg.post( _this.editForm.action, 'wpUploadFile', JSON.stringify( _this.formData ) );				
			//update upload status:						
			_this.doUploadStatus();
		}else{
			js_log("done with encoding (no upload) ");
			//set stuats to 100% for one second:
			_this.updateProgress( 1 );		
			setTimeout(function(){
				_this.updateProgressWin(gM('fogg-encoding-done'), gM('fogg-encoding-done'));
			}, 1000);	
		}
	},
	doUploadStatus:function() {	
		var _this = this;
		$j( '#up-status-state' ).html( gM('uploaded-status')  );
		
		_this.oldResponseText = '';
		//setup a local function for timed callback:				 
		var uploadStatus = function(){			
			//get the response text: 
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
				//try and parse the response text and check for errors			
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
					//stop status update we have an error
					_this.action_done = true;
					_this.fogg.cancel();
					return false; 
				}		
			}	
			//update progress bar
			_this.updateProgress( _this.fogg.progress() );
						
			//loop to get new status if still uploading (could also be encoding if we are in chunk upload mode) 
			if( _this.fogg.state == 'encoding' || _this.fogg.state == 'uploading') {
				setTimeout(uploadStatus, 100);
				
			}//check upload state
			else if( _this.fogg.state == 'upload done' || 
						 _this.fogg.state == 'done' ||
						 _this.fogg.state == 'encoding done' ) {	
				   js_log( 'firefogg:upload done: ');																														   
				   //if in "post" upload mode read the html response (should be depricated): 
				   	if( _this.upload_mode == 'post' && _this.api_url ) {					   					  
					   _this.procPageResponse( response_text );						   
				   	}else if( _this.upload_mode == 'api'){										  
					   if( _this.fogg.resultUrl ){	
					   		var buttons ={}; 
					   		buttons[gM('go-to-resource')] =  function(){
								window.location = _this.fogg.resultUrl;
							}
							var go_to_url_txt = gM('go-to-resource');			   						   
						   	if( typeof _this.done_upload_cb == 'function' ){
						   		//if done action return 'true'
								if( _this.done_upload_cb() ){										
									//update status
						   			_this.updateProgressWin( gM('successfulupload'),  gM( 'mv_upload_done', _this.fogg.resultUrl),buttons);	
								}else{
									//if done action returns 'false' //close progress window
									this.action_done = true;							  	        
						  	       	$j('#upProgressDialog').dialog('close');	
								} 
						   	}
					   }else{
						   //done state with error? ..not really possible given how firefogg works
						   js_log(" upload done, in chunks mode, but no resultUrl!");
					   }																										   
				   }													
			}else{  
				//upload error: 
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
	  	if( confirm( gM('mv-cancel-confim') )){
	  	    if(navigator.oscpu && navigator.oscpu.search('Win') >= 0){
	  	         alert( 'sorry we do not yet support cancel on windows' );
	  	    }else{
	  	    	this.action_done = true;
	  	        this.fogg.cancel();
	  	        $j(dlElm).dialog('close');
	  	    }
	  	} else{	 	  		
	  		return false;
	  	} 
	},
	/**
	* procPageResponse should be faded out in favor of the upload api soon.. 
	* its all very fragile to read the html output and guess at stuff
	*/
	procPageResponse:function( result_page ){
		var _this = this;
		js_log('f:procPageResponse');
		var sstring = 'var wgTitle = "' + this.formData['wpDestFile'].replace('_',' ');		
		
		if(wgArticlePath){
			var result_txt = gM('mv_upload_done', wgArticlePath.replace(/\$1/, 'File:' + _this.formData['wpDestFile'] ) );
		}else{
			result_txt = 'File has uploaded but api "done" url was provided. Check the log for result page output';
		}		
		
		//set the error text in case we dont' get far along in processing the response 
		_this.updateProgressWin( gM('mv_upload_completed'), result_txt );
												
		if( result_page && result_page.toLowerCase().indexOf( sstring.toLowerCase() ) != -1){	
			js_log( 'upload done got redirect found: ' + sstring + ' r:' + _this.done_upload_cb );										
			if( _this.done_upload_cb == 'redirect' ){
				$j( '#dlbox-centered' ).html( '<h3>Upload Completed:</h3>' + result_txt + '<br>' + form_txt);
				window.location = wgArticlePath.replace( /\$1/, 'File:' + _this.formData['wpDestFile'] );
			}else{
				//check if the add_done_action is a callback:
				if( typeof _this.done_upload_cb == 'function' )
					_this.done_upload_cb();
			}									
		}else{								
			//js_log( 'upload page error: did not find: ' +sstring + ' in ' + "\n" + result_page );					
			var form_txt = '';		
			if( !result_page ){
				//@@todo fix this: 
				//the mediaWiki upload system does not have an API so we can\'t read errors							
			}else{
				var res = grabWikiFormError( result_page );
							
				if(res.error_txt)
					result_txt = res.error_txt;
					
				if(res.form_txt)
					form_txt = res.form_txt;
			}		
			js_log( 'error text is: ' + result_txt );		
			$j( '#dlbox-centered' ).html( '<h3>' + gM('mv_upload_completed') + '</h3>' + result_txt + '<br>' + form_txt);
		}
	}
};
