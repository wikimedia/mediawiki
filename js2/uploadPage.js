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
						mwUploadHelper.doDestCheck();
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
			$j('#wpDestFile').change( mwUploadHelper.doDestCheck );
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
	 * doDestCheck checks the destination
	 */
	doDestCheck:function(){
		var _this = this;
		$j('#wpDestFile-warning').empty();
		//show loading
		$j('#wpDestFile').after('<img id = "mw-spinner-wpDestFile" src ="'+ stylepath + '/common/images/spinner.gif" />');
		//try and get a thumb of the current file (check its destination)
		do_api_req({
			'data':{
				'titles': 'File:' + $j('#wpDestFile').val(),//@@todo we may need a more clever way to get a the filename
				'prop':  'imageinfo',
				'iiprop':'url|mime|size',
				'iiurlwidth': 150
			},
			'url': _this.api_url
		},function(data){
			//remove spinner:
			$j('#mw-spinner-wpDestFile').remove();
			if(data && data.query && data.query.pages){
				if( data.query.pages[-1] ){
					//all good no file there
				}else{
					for(var page_id in data.query.pages){
						if( data.query.normalized){
							var ntitle = data.query.normalized[0].to;
						}else{
							var ntitle = data.query.pages[ page_id ].title;
						}
						var img = data.query.pages[ page_id ].imageinfo[0];
						$j('#wpDestFile-warning').html(
							'<ul>' +
								'<li>'+
									gM('mwe-fileexists', ntitle) +
								'</li>'+
								'<div class="thumb tright">' +
									'<div style="width: ' + ( parseInt(img.thumbwidth)+2 ) + 'px;" class="thumbinner">' +
										'<a title="' + ntitle + '" class="image" href="' + img.descriptionurl + '">' +
											'<img width="' + img.thumbwidth + '" height="' + img.thumbheight + '" border="0" class="thumbimage" ' +
											'src="' + img.thumburl + '"' +
											'	 alt="' + ntitle + '"/>' +
										'</a>' +
										'<div class="thumbcaption">' +
											'<div class="magnify">' +
												'<a title="' + gM('thumbnail-more') + '" class="internal" ' +
													'href="' + img.descriptionurl +'"><img width="15" height="11" alt="" ' +
													'src="' + stylepath + "/common/images/magnify-clip.png\" />" +
												'</a>'+
											'</div>'+
											gM('mwe-fileexists-thumb') +
										'</div>' +
									'</div>'+
								'</div>' +
							'</ul>'
						);
					}
				}
			}
		});
	},
	/**
	 * doDestinationFill fills in a destination file-name based on a source asset name.
	 */
	doDestinationFill:function( targetElm ){
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
		this.doDestCheck();
	}
}

