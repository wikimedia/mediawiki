/*
 * uploadPage.js to be run on specialUpload page.
 * controls the invocation of the mvUploader class based on local config.
 */
js2AddOnloadHook( function(){
	mwUploadHelper.init();
});
var mwUploadFormTarget = '#mw-upload-form';
//set up the upoload form bindings once all dom manipluation is done
var mwUploadHelper = {
	firefogg_installed:false,
	init:function(){
		var _this = this;
		//if not boolean false set to true:
		if(typeof wgEnableFirefogg == 'undefined')
			wgEnableFirefogg = true;

		if( wgEnableFirefogg ){
			//setup the upload handler to firefogg  (supports our upload proccess) (should work with the http uploads too)
			$j('#wpUploadFile').firefogg({
				//an api url (we won't submit directly to action of the form)
				'api_url' : wgServer + wgScriptPath + '/api.php',
				'form_rewrite': true,
				'target_edit_from' : mwUploadFormTarget,
				'new_source_cb' : function( orgFilename, oggName ){
				        if($j('#wpDestFile').val() == "")
						    $j('#wpDestFile').val( oggName );
						$j('#wpDestFile').doDestCheck({
							'warn_target':'#wpDestFile-warning'
						});
				},
				'detect_cb':function( fogg_installed ){
					if(fogg_installed){
						_this.firefogg_installed=true;
					}else{
						_this.firefogg_installed=false;
					}
				}
			});

		}else{
			//Add basic upload profile support ( http status monitoring, progress box for browsers that support it etc.)
			if($j('#wpUploadFileURL').length != 0){
				$j('#wpUploadFileURL').baseUploadInterface({
					'api_url'   : wgServer + wgScriptPath + '/api.php',
					'target_edit_from' : mwUploadFormTarget
				});
			}
		}

		if( wgAjaxUploadDestCheck ){
			//do destination check:
			$j('#wpDestFile').change(function(){
				$j(this).doDestCheck({
					'warn_target':'#wpDestFile-warning'
				});
			});
		}

		//check if we have http enabled & setup enable/disable toggle:
		if($j('#wpUploadFileURL').length != 0){
			//set the initial toggleUpType
			_this.toggleUpType(true);

			$j("input[name='wpSourceType']").click(function(){
				_this.toggleUpType( this.id == 'wpSourceTypeFile' );
			});
		}
		$j('#wpUploadFile,#wpUploadFileURL').focus(function(){
			_this.toggleUpType( this.id == 'wpUploadFile' );
		}).change(function(){ //also setup the onChange event binding:
			if ( wgUploadAutoFill ) {
				mwUploadHelper.doDestinationFill( this );
			}
		});
	},
	/**
	 * toggleUpType sets the upload radio buttons
	 *
	 * boolean set
	 */
	toggleUpType:function( set ){
		$j('#wpSourceTypeFile').attr('checked', set);
		$j('#wpUploadFile').attr('disabled', !set);

		$j('#wpSourceTypeURL').attr('checked', !set);
		$j('#wpUploadFileURL').attr('disabled', set);

		//if firefogg is enbaled: toggle action per form select of http upload vs firefogg upload
		if( wgEnableFirefogg ){
			$j('#wpUploadFile').firefogg({
					'firefogg_form_action': $j('#wpSourceTypeFile').attr('checked')
			});
		}
	},	
	/**
	 * doDestinationFill fills in a destination file-name based on a source asset name.
	 */
	doDestinationFill : function( targetElm ){
		js_log("doDestinationFill")
		//remove any previously flagged errors
		$j('#mw-upload-permitted,#mw-upload-prohibited').hide();

		var path = $j(targetElm).val();
		// Find trailing part
		var slash = path.lastIndexOf('/');
		var backslash = path.lastIndexOf('\\');
		var fname;
		if (slash == -1 && backslash == -1) {
			fname = path;
		} else if (slash > backslash) {
			fname = path.substring(slash+1, 10000);
		} else {
			fname = path.substring(backslash+1, 10000);
		}
		//urls are less likely to have a usefull extension don't include them in the extention check
		if( wgFileExtensions && $j(targetElm).attr('id') != 'wpUploadFileURL' ){
			var found = false;
			if( fname.lastIndexOf('.')!=-1 ){
				var ext = fname.substr( fname.lastIndexOf('.')+1 );
				for(var i=0; i < wgFileExtensions.length; i++){
					if(  wgFileExtensions[i].toLowerCase()   ==  ext.toLowerCase() )
						found = true;
				}
			}
			if(!found){
				//clear the upload set mw-upload-permitted to error
				$j(targetElm).val('');
				$j('#mw-upload-permitted,#mw-upload-prohibited').show().addClass('error');
				//clear the wpDestFile as well:
				$j('#wpDestFile').val('');
				return false;
			}
		}
		// Capitalise first letter and replace spaces by underscores
		fname = fname.charAt(0).toUpperCase().concat(fname.substring(1,10000)).replace(/ /g, '_');
		// Output result
		$j('#wpDestFile').val( fname );

		//do a destination check
		$j('#wpDestFile').doDestCheck({
			'warn_target':'#wpDestFile-warning'
		});
	}
}

