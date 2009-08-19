/**
 * the base Upload Interface for uploading.
 *
 * this base uploader is optionally extended by firefogg
 */
loadGM({
	"upload-transcode-in-progress" : "Transcode and upload in progress (do not close this window)",
	"upload-in-progress" : "Upload in progress (do not close this window)",
	"upload-transcoded-status" : "Transcoded",
	"uploaded-status" : "Uploaded",
	"wgfogg_wrong_version" : "You have Firefogg installed but it is outdated. <a href=\"http : \/\/firefogg.org\">Please upgrade<\/a>.",
	"upload-stats-fileprogres" : "$1 of $2",
	"mv_upload_completed" : "Your upload is complete",
	"mv_upload_done" : "<a href=\"$1\">Your upload <i>should be<\/i> accessible<\/a>.",
	"upload-unknown-size" : "Unknown size",
	"mv-cancel-confim" : "Are you sure you want to cancel?",
	"successfulupload" : "Upload successful",
	"uploaderror" : "Upload error",
	"uploadwarning" : "Upload warning",
	"unknown-error" : "Unknown error : ",
	"return-to-form" : "Return to form",
	"file-exists-duplicate" : "This file is a duplicate of the following file : ",
	"fileexists" : "A file with this name exists already. Please check <b><tt>$1<\/tt><\/b> if you are not sure if you want to change it.",
	"fileexists-thumb" : "<center><b>Existing file<\/b><\/center>",
	"ignorewarning" : "Ignore warning and save file anyway",
	"file-thumbnail-no" : "The filename begins with <b><tt>$1<\/tt><\/b>",
	"go-to-resource" : "Go to resource page",
	"upload-misc-error" : "Unknown upload error",
	"wgfogg_waring_bad_extension" : "You have selected a file with an unsuported extension (<a href=\"http : \/\/commons.wikimedia.org\/wiki\/Commons : Firefogg#Supported_File_Types\">more information<\/a>).",
	"cancel-button" : "Cancel",
	"ok-button" : "OK"
});


var default_bui_options = {
	'api_url':null,
	'parent_uploader':null,
	'edit_from':null,
	'done_upload_cb': null,
	'target_edit_from':null,

	//upload_mode can be 'post', 'api' or 'autodetect'. (autodetect issues an api call)
	'upload_mode': 'autodetect'

}
var mvBaseUploadInterface = function( iObj ){
	return this.init( iObj );
}
mvBaseUploadInterface.prototype = {
	parent_uploader:false,
	formData:{}, //the form to be submitted
	warnings_sessionkey:null,
	chunks_supported:false,
	form_post_override:false,
	action_done:false,
	//the edit token:
	etoken:false,
	init: function( iObj ){
		if(!iObj)
			iObj = {};
		//inherit iObj properties:
		for(var i in default_bui_options){
			if(iObj[i]){
				this[i] = iObj[i];
			}else{
				this[i] = default_bui_options[i];
			}
		}
	},
	setupForm:function(){
		var _this = this;
		//set up the local pointer to the edit form:
		_this.editForm = _this.getEditForm();
		if( _this.editForm ){
			//set up the org_onsubmit if not set:
			if( typeof( _this.org_onsubmit ) == 'undefined' &&  _this.editForm.onsubmit )
				_this.org_onsubmit = _this.editForm.onsubmit;

			//set up the submit action:
			$j( _this.editForm ).submit( function(){
				js_log('j.onSubmit');
				//run the original onsubmit (if not run yet set flag to avoid excessive chaining )
				if( typeof( _this.org_onsubmit ) == 'function' ){
					if( ! _this.org_onsubmit() ){
						//error in org submit return false;
						return false;
					}
				}
				//check for post action override:
				if( _this.form_post_override ){
					js_log('form_post_override is true do form proccessing:');
					return true;
				}
				//get the input form data in flat json:
				var tmpAryData = $j( _this.editForm ).serializeArray();
				for(var i=0; i < tmpAryData.length; i++){
					if( tmpAryData[i]['name'] )
						_this.formData[ tmpAryData[i]['name'] ] = tmpAryData[i]['value'];
				}
				//put into a try catch so we are sure to return false:
				try{
					//get a clean loader:
					_this.dispProgressOverlay();

					//for some unknown reason we have to drop down the #p-search z-index:
					$j('#p-search').css('z-index', 1);

					//select upload mode:
					_this.detectUploadMode();
				}catch(e){

				}

				//don't submit the form we will do the post in ajax
				return false;
			});
		}

	},
	detectUploadMode:function( callback ){
		var _this = this;
		//check the upload mode:
		if( _this.upload_mode == 'autodetect' ){
			js_log('detectUploadMode::' + _this.upload_mode + ' api:' + _this.api_url);
			if( ! _this.api_url )
				return js_error( 'Error: can\'t autodetect mode without api url' );
			do_api_req( {
				'data':{ 'action':'paraminfo','modules':'upload' },
				'url' :_this.api_url
			}, function(data){
				if( typeof data.paraminfo == 'undefined' || typeof data.paraminfo.modules == 'undefined' )
					return js_error( 'Error: bad api results' );
				if( typeof data.paraminfo.modules[0].classname == 'undefined'){
					js_log( 'Autodetect Upload Mode: \'post\' ');
					_this.upload_mode = 'post';
				}else{
					js_log( 'Autodetect Upload Mode: api ' );
					_this.upload_mode = 'api';
					//check to see if chunks are supported:
					for( var i in data.paraminfo.modules[0].parameters ){
						var pname = data.paraminfo.modules[0].parameters[i].name;
						if( pname == 'enablechunks' ){
							js_log( 'this.chunks_supported = true' );
							_this.chunks_supported = true;
							break;
						}
					}
				}
				js_log("do call: doUploadSwitch");
				_this.doUploadSwitch();
			});
		}else{
			_this.doUploadSwitch();
		}
	},
	doUploadSwitch:function(){
		var _this = this;
		js_log('mvUPload:doUploadSwitch():' + _this.upload_mode);
		//issue a normal post request
		if( _this.upload_mode == 'post' ) {
			//we don't support the upload api
			//trick the browser into thinking the wpUpload button was pressed (there might be a cleaner way to do this)
			$j(_this.editForm).append(
				'<input type="hidden" name="wpUpload" value="' + $j('input[name=\'wpUpload\']').val() + '"/>'
			);
			//do normal post
			_this.form_post_override = true;
			//do the submit :
			_this.editForm.submit();
		}else if(
			_this.upload_mode=='api' &&
			( $j('#wpSourceTypeFile').length ==  0 || $j('#wpSourceTypeFile').get(0).checked )
		){
			//@@TODO check for sendAsBinnary to support firefox 3.5 progress

			//set the form target to iframe target:
			_this.iframeId = 'f_' + ($j('iframe').length + 1);
			$j(_this.editForm).attr('target', _this.iframeId);

			//add the iframe
			$j("body").append('<iframe src="javascript:false;" id="' + _this.iframeId + '" ' +
				'name="' + _this.iframeId + '" style="display:none;" ></iframe>');

			//set up the done binding
			$j('#' + _this.iframeId).load(function(){
				_this.proccessIframeResult(  $j(this).get(0) );
			});

			//set the editForm iframe target
			//$j(_this.editForm).attr('target', id);

			//set the action to the api url:
			$j(_this.editForm).attr('action', _this.api_url );
			//add api action:
			if( $j(_this.editForm).find("[name='action']").length == 0)
				$j(_this.editForm).append('<input type="hidden" name="action" value="upload">');

			//add json format
			if( $j(_this.editForm).find("[name='format']").length == 0)
				$j(_this.editForm).append('<input type="hidden" name="format" value="jsonfm">');

			//map the form vars to api vars:
			$j(_this.editForm).find('#wpUploadFile').attr('name', 'file');
			$j(_this.editForm).find('#wpDestFile').attr('name', 'filename');
			$j(_this.editForm).find('#wpUploadDescription').attr('name', 'comment');
			$j(_this.editForm).find('#wpEditToken').attr('name', 'token');
			$j(_this.editForm).find('#wpIgnoreWarning').attr('name', 'ignorewarnings');
			$j(_this.editForm).find('#wpWatchthis').attr('name', 'watch');

			//update the status to 100% progress bar (no status in iframe submit)
			$j('#up-progressbar' ).progressbar('value', parseInt( 100 ) );
			$j('#up-status-container').html( gM('upload-in-progress') );

			js_log('do iframe form submit to: ' +  $j(_this.editForm).attr('target'));

			//do post override
			_this.form_post_override = true;
			//reset the done with action flag:
			_this.action_done = false;

			_this.editForm.submit();

			return false;
		}else if( _this.upload_mode == 'api' && $j('#wpSourceTypeURL').get(0).checked){
			js_log('doHttpUpload (no form submit) ');
			//if the api is supported.. && source type is http do upload with http status updates
			var httpUpConf ={
			    'url'		: $j('#wpUploadFileURL').val(),
			    'filename'	: $j('#wpDestFile').val(),
			    'comment' 	: $j('#wpUploadDescription').val(),
				'watch'		: ($j('#wpWatchthis').is(':checked'))?'true':'false'
			}
			//set up ignore warnings and watch arguments:
			if( $j('#wpIgnoreWarning').is(':checked') ){
				httpUpConf[ 'ignorewarnings'] =  'true';
			}
			if( $j('#wpWatchthis').is(':checked') ){
				httpUpConf[ 'watch'] =  'true';
			}
			//check for editToken
			_this.etoken = $j("#wpEditToken").val();
			_this.doHttpUpload( httpUpConf );
		}else{
			js_error( 'Error: unrecongized upload mode: ' + _this.upload_mode );
		}
		return false;
	},
	proccessIframeResult:function(iframe){
		var _this = this;
		var doc = iframe.contentDocument ? iframe.contentDocument : frames[iframe.id].document;
		// fixing Opera 9.26
		if (doc.readyState && doc.readyState != 'complete'){
			return;
		}
		// fixing Opera 9.64
		if (doc.body && doc.body.innerHTML == "false"){
			return;
		}
		var response;
		if (doc.XMLDocument){
			// response is a xml document IE property
			response = doc.XMLDocument;
		} else if (doc.body){
			// get the json str:
			json_str = $j(doc.body).find('pre').html();
			//htmlentties
			if (json_str) {
				response = window["eval"]("(" +json_str + ")");
			} else {
				response = {};
			}
		} else {
			// response is a xml document
			var response = doc;
		}
		//do proccess the api result
		_this.processApiResult(	response );
	},
	doHttpUpload:function( opt ){
		var _this = this;
		//set the http box to loading (in case we don't get an update for some time)
		$j('#dlbox-centered').html( '<h5>' + _this.getProgressTitle() + '</h5>' +
			mv_get_loading_img( 'left:40%;top:20%')
		);
		//setup request:
		var req = {
			'action'		: 'upload',
			'asyncdownload' : true	//do a s
		};
		//set config from options:
		for(var i in opt){
			req[i]= opt[i];
		}

		//else try and get a token:
		if(!_this.etoken  && _this.api_url){
			js_log('Error:doHttpUpload: missing token');
		}else{
			req['token'] =_this.etoken;
		}
		//reset the done with action flag:
		_this.action_done = false;
		//do the api req
		do_api_req({
			'data': req,
			'url' : _this.api_url
		}, function( data ){
			_this.processApiResult( data );
		});
	},
	doAjaxWarningIgnore:function(){
		var _this = this;
		if( !_this.upload_session_key )
			return js_error('missing upload_session_key (can\'t ignore warnigns');
		//do the ignore warnings submit to the api:
		var req = {
				'ignorewarnings' : 'true',
				'sessionkey'	 :!_this.upload_session_key
			};
		//add token if present:
		if(this.etoken)
			req['token'] = this.etoken;

		do_api_req({
			'data':req,
			'url': _this.api_url
		},function(data){
			_this.processApiResult(data);
		});
	},
	doAjaxUploadStatus:function() {
		var _this = this;

		//set up the progress display for status updates:
		_this.dispProgressOverlay();
		var req = {
					'action'	 : 'upload',
					'httpstatus' : 'true',
					'sessionkey' : _this.upload_session_key
		};
		//add token if present:
		if(this.etoken)
			req['token'] = this.etoken;

		var uploadStatus = function(){
			//do the api request:
			do_api_req({
				'data':req,
				'url' : _this.api_url
			}, function( data ){
				//@@check if we are done
				if( data.upload['apiUploadResult'] ){
					//update status to 100%
					_this.updateProgress( 1 );
					if(typeof JSON == 'undefined'){
						//we need to load the jQuery json parser: (older browsers don't have JSON.parse
						mvJsLoader.doLoad([
							'$j.secureEvalJSON'
						],function(){
							var  apiResult = $j.secureEvalJSON( data.upload['apiUploadResult'] );
							_this.processApiResult( apiResult );
						});
					}else{
						var apiResult = {};
						try{
							apiResult = JSON.parse ( data.upload['apiUploadResult'] ) ;
						}catch (e){
							//could not parse api result
							js_log('errro: could not parse apiUploadResult ')
						}
						_this.processApiResult( apiResult );
					}
					return ;
				}

				//@@ else update status:
				if( data.upload['content_length'] &&  data.upload['loaded'] ){
					//we have content length we can show percentage done:
					var perc =  data.upload['loaded'] / data.upload['content_length'];
					//update the status:
					_this.updateProgress( perc );
					//special case update the file progress where we have data size:
					$j('#up-status-container').html(
						gM('upload-stats-fileprogres', [
							formatSize( data.upload['loaded'] ),
							formatSize( data.upload['content_length'] )
							]
						)
					);
				}else if( data.upload['loaded'] ){
					_this.updateProgress( 1 );
					js_log('just have loaded (no cotent length: ' + data.upload['loaded']);
					//for lack of content-length requests:
					$j('#up-status-container').html(
						gM('upload-stats-fileprogres', [
							formatSize( data.upload['loaded'] ),
							gM('upload-unknown-size')
							]
						)
					);
				}
				//(we got a result) set it to 100ms + your server update interval (in our case 2s)
				setTimeout(uploadStatus, 2100);
			});
		}
		uploadStatus();
	},
	apiUpdateErrorCheck:function( apiRes ){
		var _this = this;
		if( apiRes.error || ( apiRes.upload && apiRes.upload.result == "Failure" ) ){
			//gennerate the error button:
			var bObj = {};
			bObj[ gM('return-to-form') ] = 	function(){
					_this.form_post_override = false;
					$j(this).dialog('close');
			 };

			//@@TODO should be refactored to more specialUpload page type error handling

			//check a few places for the error code:
			var error_code=0;
			var errorReplaceArg='';
			if( apiRes.error && apiRes.error.code ){
				error_code = apiRes.error.code;
			}else if( apiRes.upload.code ){
				if(typeof apiRes.upload.code == 'object'){
					if(apiRes.upload.code[0]){
						error_code = apiRes.upload.code[0];
					}
					if(apiRes.upload.code['status']){
						error_code = apiRes.upload.code['status'];
						if(apiRes.upload.code['filtered'])
							errorReplaceArg =apiRes.upload.code['filtered'];
					}
				}else{
					apiRes.upload.code;
				}
			}

			var error_msg = '';
			if(typeof apiRes.error == 'string')
				error_msg = apiRes.error;
			//error space is too large so we don't front load it
			//this upload error space replicates code in: SpecialUpload.php::processUpload()
			//would be nice if we refactored that to the upload api.(problem is some need special actions)
			var error_msg_key = {
				'2' : 'largefileserver',
				'3' : 'emptyfile',
				'4' : 'minlength1',
				'5' : 'illegalfilename'
			};

			//@@todo: need to write conditionals that mirror SpecialUpload for handling these error types:
			var error_onlykey = {
				'1': 'BEFORE_PROCESSING',
				'6': 'PROTECTED_PAGE',
				'7': 'OVERWRITE_EXISTING_FILE',
				'8': 'FILETYPE_MISSING',
				'9': 'FILETYPE_BADTYPE',
				'10': 'VERIFICATION_ERROR',
				'11': 'UPLOAD_VERIFICATION_ERROR',
				'12': 'UPLOAD_WARNING',
				'13': 'INTERNAL_ERROR',
				'14': 'MIN_LENGHT_PARTNAME'
			}

			//do a remote call to get the error msg:
			if(!error_code || error_code == 'unknown-error'){
				if(typeof JSON != 'undefined'){
					js_log('Error: apiRes: ' + JSON.stringify( apiRes) );
				}
				if( apiRes.upload.error == 'internal-error'){
					errorKey = apiRes.upload.details[0];
					gMsgLoadRemote(errorKey, function(){
						_this.updateProgressWin( gM( 'uploaderror' ), gM( errorKey ), bObj );

					});
					return false;
				}

				_this.updateProgressWin( gM('uploaderror'), gM('unknown-error') + '<br>' + error_msg, bObj );
				return false;
			}else{
				if(apiRes.error && apiRes.error.info ){
					_this.updateProgressWin(  gM('uploaderror'), apiRes.error.info ,bObj);
					return false;
				}else{
					if(typeof error_code == 'number' && typeof error_msg_key[error_code] == 'undefined' ){
						if(apiRes.upload.code.finalExt){
							_this.updateProgressWin(  gM('uploaderror'), gM('wgfogg_waring_bad_extension', apiRes.upload.code.finalExt) , bObj);
						}else{
							_this.updateProgressWin( gM('uploaderror'), gM('unknown-error') + ' : ' + error_code, bObj);
						}
					}else{
						js_log('get key: ' + error_msg_key[ error_code ])
						gMsgLoadRemote( error_msg_key[ error_code ], function(){
							_this.updateProgressWin(  gM('uploaderror'), gM(  error_msg_key[ error_code ], errorReplaceArg ), bObj);
						});
						js_log("api.erorr");
					}
					return false;
				}
			}
		}
		//check for upload.error type erros.
		if( apiRes.upload && apiRes.upload.error){
			js_log(' apiRes.upload.error: ' +  apiRes.upload.error );
			_this.updateProgressWin( gM('uploaderror'), gM('unknown-error') + '<br>', bObj);
			return false;
		}
		//check for known warnings:
		if(apiRes.upload && apiRes.upload.warnings ){
			//debugger;
			var wmsg = '<ul>';
			for(var wtype in apiRes.upload.warnings){
				var winfo = apiRes.upload.warnings[wtype]
				wmsg+='<li>';
				switch(wtype){
					case 'duplicate':
					case 'exists':
						if(winfo[1] && winfo[1].title && winfo[1].title.mTextform){
							wmsg += gM('file-exists-duplicate') +' '+
									'<b>' + winfo[1].title.mTextform + '</b>';
						}else{
							//misc error (weird that winfo[1] not present
							wmsg += gM('upload-misc-error') + ' ' + wtype;
						}
					break;
					case 'file-thumbnail-no':
						wmsg += gM('file-thumbnail-no', winfo);
					break;
					default:
						wmsg += gM('upload-misc-error') + ' ' + wtype;
					break;
				}
				wmsg+='</li>';
			}
			wmsg+='</ul>';
			if( apiRes.upload.warnings.sessionkey)
			 	_this.warnings_sessionkey = apiRes.upload.warnings.sessionkey;
			var bObj = {};
			bObj[ gM('ignorewarning') ] =  	function() {
				//re-inciate the upload proccess
				$j('#wpIgnoreWarning').attr('checked', true);
				$j( '#mw-upload-form' ).submit();
			};
			bObj[ gM('return-to-form') ] = function(){
				$j(this).dialog('close');
				_this.form_post_override = false;
			}
			_this.updateProgressWin(  gM('uploadwarning'),  '<h3>' + gM('uploadwarning') + '</h3>' +wmsg + '<p>',bObj);
			return false;
		}
		//should be "OK"
		return true;
	},
	processApiResult: function( apiRes ){
		var _this = this;
		js_log('processApiResult::');
		//check for upload api error:
		// {"upload":{"result":"Failure","error":"unknown-error","code":{"status":5,"filtered":"NGC2207%2BIC2163.jpg"}}}
		if( _this.apiUpdateErrorCheck(apiRes) === false){
			//error returned false (updated and
			return false;
		}else{
			//check for upload_session key for async upload:
			if( apiRes.upload && apiRes.upload.upload_session_key ){
				//set the session key
				_this.upload_session_key = apiRes.upload.upload_session_key;

				//do ajax upload status:
				_this.doAjaxUploadStatus();
				js_log("set upload_session_key: " + _this.upload_session_key);
				return ;
			}

			if( apiRes.upload.imageinfo &&  apiRes.upload.imageinfo.descriptionurl ){
				var url = apiRes.upload.imageinfo.descriptionurl;
				//check done action:
				if( _this.done_upload_cb && typeof _this.done_upload_cb == 'function'){
					//close up shop:
					$j('#upProgressDialog').dialog('close');
					//call the callback:
					_this.done_upload_cb( url );
				}else{
					var bObj = {};
					bObj[ gM('return-to-form')] = function(){
						$j(this).dialog('close');
						_this.form_post_override = false;
					}
					bObj[ gM('go-to-resource') ] = function(){
							window.location = url;
					};
					_this.action_done = true;
					_this.updateProgressWin( gM('successfulupload'),  gM( 'mv_upload_done', url), bObj);
					js_log('apiRes.upload.imageinfo::'+url);
				}
				return ;
			}
		}
	},
	updateProgressWin:function(title_txt, msg, buttons){
		var _this = this;
		 if(!title_txt)
		   title_txt = _this.getProgressTitle();
		 if(!msg)
		   msg = mv_get_loading_img( 'left:40%;top:40px;');
		 $j( '#upProgressDialog' ).dialog('option', 'title',  title_txt );
		 $j( '#upProgressDialog' ).html( msg );
		 if(buttons){
			 $j('#upProgressDialog').dialog('option','buttons', buttons);
		 }else{
			 //@@todo should convice the jquery ui people to not use object keys as user msg's
			 var bObj = {};
			 bObj[ gM('ok-button') ] =  function(){
				  $j(this).dialog('close');
			 };
			 $j('#upProgressDialog').dialog('option','buttons', bObj);
		 }
	},
	getProgressTitle:function(){
		return gM('upload-in-progress');
	},
	getEditForm:function(){
		if( this.target_edit_from && $j( this.target_edit_from ).length != 0){
			return $j( this.target_edit_from ).get(0);
		}
		//just return the first form fond on the page.
		return $j('form :first').get(0);
	},
	updateProgress:function( perc ){
		//js_log('update progress: ' + perc);
		$j( '#up-progressbar' ).progressbar('value', parseInt( perc * 100 ) );
		$j( '#up-pstatus' ).html( parseInt( perc * 100 ) + '% - ' );
	},
	/*update to jQuery.ui progress display type */
	dispProgressOverlay:function(){
	  var _this = this;
	  //remove old instance:
	  if($j('#upProgressDialog').length!=0){
		 $j('#upProgressDialog').dialog( 'destroy' ).remove();
	  }
	  //re add it:
	  $j('body').append('<div id="upProgressDialog" ></div>');

	  $j('#upProgressDialog').dialog({
		  title:_this.getProgressTitle(),
		  bgiframe: true,
		  modal: true,
		  width:400,
		  heigh:200,
		  beforeclose: function(event, ui) {
			  if( event.button==0 && _this.action_done === false){
				return _this.cancel_action();
			  }else{
				 //click on button (dont do close action);
				 return true;
			  }
		  },
		  buttons: _this.cancel_button()
	  });
	  $j('#upProgressDialog').html(
	  //set initial content:
		'<div id="up-pbar-container" style="width:90%;height:15px;" >' +
			'<div id="up-progressbar" style="height:15px;"></div>' +
			'<div id="up-status-container">'+
				'<span id="up-pstatus">0% - </span> ' +
				'<span id="up-status-state">' + gM('uploaded-status') + '</span> ' +
			'</div>'+
		'</div>'
	  )
	  //setup progress bar:
	   $j('#up-progressbar').progressbar({
		   value:0
	   });
	   //just display an empty progress window
	   $j('#upProgressDialog').dialog('open');

	},
	cancel_button:function(){
	   var _this = this;
	   var cancelBtn = new Array();
	   cancelBtn[ gM('cancel-button') ] =  function(){
	   		return _this.cancel_action(this)
	   };
	   return cancelBtn;
	},
	cancel_action : function( dlElm ){
		//confirm:
		if( confirm( gM('mv-cancel-confim') )){
			//@@todo (cancel the encode / upload)
			$j(this).dialog('close');
			return false;
		}else{
			return true;
		}
	}
};
