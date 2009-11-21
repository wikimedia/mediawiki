/* Firefogg support.
 * autodetects: new upload api or old http POST.
 */

loadGM({
	"fogg-select_file" : "Select file",
	"fogg-select_new_file" : "Select new file",
	"fogg-select_url" : "Select URL",
	"fogg-save_local_file" : "Save Ogg",
	"fogg-check_for_firefogg" : "Checking for Firefogg...",
	"fogg-installed" : "Firefogg is installed",
	"fogg-for_improved_uploads" : "For improved uploads:",
	"fogg-please_install" : "<a href=\"$1\">Install Firefogg<\/a>. More <a href=\"http:\/\/commons.wikimedia.org\/wiki\/Commons:Firefogg\">about Firefogg<\/a>.",
	"fogg-use_latest_firefox" : "Please first install <a href=\"http:\/\/www.mozilla.com\/en-US\/firefox\/upgrade.html?from=firefogg\">Firefox 3.5<\/a> (or later). <i>Then revisit this page to install the <b>Firefogg<\/b> extension.<\/i>",
	"fogg-passthrough_mode" : "Your selected file is already Ogg or not a video file",
	"fogg-transcoding" : "Encoding video to Ogg...",
	"fogg-encoding-done" : "Encoding complete",
	"fogg-badtoken" : "Token is not valid",
	"fogg-preview" : "Preview video",
	"fogg-hidepreview" : "Hide preview"
});

var firefogg_install_links = {
	'macosx': 'http://firefogg.org/macosx/Firefogg.xpi',
	'win32': 'http://firefogg.org/win32/Firefogg.xpi',
	'linux': 'http://firefogg.org/linux/Firefogg.xpi'
};

var default_firefogg_options = {
	// Callback for upload completion
	'done_upload_cb': false,

	// True if Firefogg is enabled in the client
	'have_firefogg': false,

	// The API URL to upload to
	'api_url': null,

	// True when a file is uploaded without re-encoding
	'passthrough': false,

	// True if we will be showing the encoder interface
	'encoder_interface': false,

	// True if we want to limit the library functionality to "only firefogg" 
	// (no upload or progress bars)
	'only_firefogg': false,

	// Callback which is called when the source name changes
	'new_source_cb': false,

	// CSS selector identifying the target control container or form (can't be left null)
	'selector': '',

	// May be "upload" to if we are rewriting an upload form, or "local" if we are encoding a local file	
	'form_type': 'local',

	// CSS selector for the select file button
	'target_btn_select_file': false,

	// CSS selector for the select new file button
	'target_btn_select_new_file': false,

	// CSS selector for the save local file button
	'target_btn_save_local_file': false,

	// CSS selector for the input file name button
	'target_input_file_name': false,

	// CSS selector for the "checking for firefogg..." message div
	'target_check_for_firefogg': false,

	// CSS selector for the "firefogg is installed" message div
	'target_installed': false,

	// CSS selector for the "please install firefogg" message div
	'target_please_install': false,

	// CSS selector for the "please use Firefox 3.5" message div
	'target_use_latest_firefox': false,

	// CSS selector for the message div warning that passthrough mode is enabled
	'target_passthrough_mode': false,

	// True if firefogg should take over the form submit action
	'firefogg_form_action': true,

	// True if we should show a preview of the encoding progress
	'show_preview': false
};


var mvFirefogg = function( options ) {
	return this.init( options );
};

mvFirefogg.prototype = { // extends mvBaseUploadInterface
	min_firefogg_version: '0.9.9.5',
	default_encoder_settings: { // @@todo allow the server to set these
		'maxSize'        : '400',
        'videoBitrate'   : '544',
        'audioBitrate'   : '96',
        'noUpscaling'    : true
	},
	have_firefogg: null, // lazy initialised, use getFirefogg()
	current_encoder_settings: null, // lazy initialised, use getEncoderSettings()
	sourceFileInfo: null, // lazy initialised, use getSourceFileInfo()
	ogg_extensions: [ 'ogg', 'ogv', 'oga' ],
	video_extensions: [ 'avi', 'mov', 'mp4', 'mp2', 'mpeg', 'mpeg2', 'mpeg4', 'dv', 'wmv' ],

	passthrough: false,
	sourceMode: 'file',

	/**
	 * Object initialisation
	 */
	init: function( options ) {
		if ( !options )
			options = {};

		// If we have no api_url, set upload mode to "post"
		if ( !options.api_url )
			options.upload_mode = 'post';

		// Set options
		for ( var i in default_firefogg_options ) {
			if ( options[i] ) {
				this[i] = options[i];
			} else {
				this[i] = default_firefogg_options[i];
			}
		}

		// Inherit from mvBaseUploadInterface (unless we're in only_firefogg mode)
		if ( !this.only_firefogg ) {
			var myBUI = new mvBaseUploadInterface( options );

			// Prefix conflicting members with pe_
			for ( var i in myBUI ) {
				if ( this[i] ) {
					this['pe_'+ i] = myBUI[i];
				} else {
					this[i] =  myBUI[i];
				}
			}
		}

		if ( !this.selector ) {
			js_log('firefogg: missing selector ');
		}
	},

	/**
	 * Rewrite the upload form, or create our own upload controls for local transcoding.
	 * Called from $j.firefogg(), in mv_embed.js.
	 */
	doRewrite: function( callback ) {
		var _this = this;
		js_log( 'sel len: ' + this.selector + '::' + $j( this.selector ).length + 
				' tag:' + $j( this.selector ).get( 0 ).tagName );
		if ( $j( this.selector ).length >= 0 ) {
			if ( $j( this.selector ).get( 0 ).tagName.toLowerCase() == 'input' ) {
				_this.form_type = 'upload';
			}
		}
		if ( this.form_type == 'upload' ) {
			// Initialise existing upload form
			this.setupForm();
		} else {
			// Create our own form controls
			this.createControls();
			this.bindControls();
		}

		if ( callback )
			callback();
	},

	/**
	 * Create controls for local transcoding and add them to the page
	 */
	createControls: function() {
		var _this = this;
		var out = '';
		$j.each( default_firefogg_options, function( target, na ) {
			if ( /^target/.test( target ) ) {
				// Create the control if it doesn't already exist
				if( _this[target] === false ) {
					out += _this.getControlHtml(target) + ' ';
					// Update the target selector
					_this[target] = _this.selector + ' .' + target;
				}
			}
		});
		$j( this.selector ).append( out ).hide();
	},

	/**
	 * Get the HTML for the control with a particular name
	 */
	getControlHtml: function( target ) {
		if ( /^target_btn_/.test( target ) ) {
			// Button
			var msg = gM( target.replace( /^target_btn_/, 'fogg-' ) );
			return '<input style="" ' + 
				'class="' + target + '" ' + 
				'type="button" ' + 
				'value="' + msg + '"/> ';
		} else if ( /^target_input_/.test( target ) ) {
			// Text input
			var msg = gM( target.replace( /^target_input_/, 'fogg-' ) );
			return '<input style="" ' + 
				'class="' + target + '" ' + 
				'type="text" ' + 
				'value="' + msg + '"/> ';
		} else if ( /^target_/.test( target ) ) {
			// Message
			var msg = gM( target.replace( '/^target_/', 'fogg-' ) );
			return '<div style="" class="' + target + '" >' + msg + '</div> ';
		} else {
			js_error( 'Invalid target: ' + target );
			return '';
		}
	},

	/**
	 * Set up events for the controls which were created with createControls()
	 */
	bindControls: function() {
		var _this = this;

		// Hide all controls
		var hide_target_list = '';
		var comma = '';
		$j.each( default_firefogg_options, function( target, na ) {
			if ( /^target/.test( target ) ) {
				hide_target_list += comma + _this[target];
				comma = ',';
			}
		});
		$j( hide_target_list ).hide();

		// Now show the form
		$j( _this.selector ).show();

		if ( _this.firefoggCheck() ) {
			// Firefogg enabled
			// If we're in upload mode, show the input filename
			if ( _this.form_type == 'upload' )
				$j( _this.target_input_file_name ).show();

			// Show the select file button
			$j( this.target_btn_select_file )
				.unbind()
				.attr( 'disabled', false )
				.css( { 'display': 'inline' } )
				.click( function() {
					_this.selectSourceFile();
				} );

			// Set up the click handler for the filename box
			$j( this.target_input_file_name )
				.unbind()
				.attr( 'readonly', 'readonly' )
				.click( function() {
					_this.selectSourceFile();
				});
		} else {
			// Firefogg disabled
			// FIXME: move this elsewhere. None of this is related to binding.

			// Show the "use latest Firefox" message if necessary
			if ( !( $j.browser.mozilla && $j.browser.version >= '1.9.1' ) ) {
				js_log( 'show use latest::' + _this.target_use_latest_firefox );
				if ( _this.target_use_latest_firefox ) {
					if ( _this.form_type == 'upload' )
						$j( _this.target_use_latest_firefox )
							.prepend( gM( 'fogg-for_improved_uploads' ) );

					$j( _this.target_use_latest_firefox ).show();
				}
				return;
			}

			// Otherwise show the "install Firefogg" message			
			var upMsg = ( _this.form_type == 'upload' ) ? gM( 'fogg-for_improved_uploads' ) : '';
			var firefoggUrl = _this.getFirefoggInstallUrl();
			if( firefoggUrl ){
				$j( _this.target_please_install )
					.html( upMsg + gM( 'fogg-please_install', firefoggUrl ) )
					.css( 'padding', '10px' )
					.show();
			}
		}

		// Set up the click handler for the "save local file" button
		$j( _this.target_btn_save_local_file )
			.unbind()
			.click( function() {
				_this.doLocalEncodeAndSave();
			} );
	},

	/*
	 * Get the URL for installing firefogg on the client OS
	 */
	getFirefoggInstallUrl: function() {
		var os_link = false;
		if ( navigator.oscpu ) {
			if ( navigator.oscpu.search( 'Linux' ) >= 0 )
				os_link = firefogg_install_links['linux'];
			else if ( navigator.oscpu.search( 'Mac' ) >= 0 )
				os_link = firefogg_install_links['macosx'];
			else if (navigator.oscpu.search( 'Win' ) >= 0 )
				os_link = firefogg_install_links['win32'];
		}
		return os_link;
	},

	/**
	 * Get the Firefogg instance (or false if firefogg is unavailable)
	 */
	getFirefogg: function() {
		if ( this.have_firefogg == null ) {
			if ( typeof( Firefogg ) != 'undefined' 
				&& Firefogg().version >= this.min_firefogg_version ) 
			{
				this.have_firefogg = true;
				this.fogg = new Firefogg();
			} else {
				this.have_firefogg = false;
				this.fogg = false;
			}
		}
		return this.fogg;
	},

	/**
	 * Set up the upload form
	 */
	setupForm: function() {
		js_log( 'firefogg::setupForm::' );

		// Set up the parent if we are in upload mode
		if ( this.form_type == 'upload' ) {
			this.pe_setupForm();
		}

		// If Firefogg is not available, just show a "please install" message
		if ( !this.firefoggCheck() ) {
			if ( !this.target_please_install ) {
				$j( this.selector ).after( this.getControlHtml( 'target_please_install' ) );
				this.target_please_install = this.selector + ' ~ .target_please_install';
			}
			if ( !this.target_use_latest_firefox ) {
				$j( this.selector ).after( this.getControlHtml( 'target_use_latest_firefox' ) );
				this.target_use_latest_firefox = this.selector + ' ~ .target_use_latest_firefox';
			}
			// Show download link
			this.bindControls();
			return;
		}

		// Change the file browser to type text. We can't simply change the attribute so 
		// we have to delete and recreate.
		var inputTag = '<input ';
		$j.each( $j( this.selector ).get( 0 ).attributes, function( i, attr ) {
			var val = attr.value;
			if ( attr.name == 'type' )
				val = 'text';
			inputTag += attr.name + '="' + val + '" ';
		} );
		if ( !$j( this.selector ).attr( 'style' ) )
			inputTag += 'style="display:inline" ';

		var id = $j( this.selector ).attr( 'name' ) + '_firefogg-control';
		inputTag += '/><span id="' + id + '"></span>';

		js_log( 'set input: ' + inputTag );
		$j( this.selector ).replaceWith( inputTag );

		this.target_input_file_name = 'input[name=' + $j( this.selector ).attr( 'name' ) + ']';

		// Point the selector at the span we just created
		this.selector = '#' + id;

		// Create controls for local transcoding
		this.createControls();
		this.bindControls();
	},

	/**
	 * Display an upload progress overlay. Overrides the function in mvBaseUploadInterface.
	 */
	displayProgressOverlay: function() {
		this.pe_dispProgressOverlay();
		// If we are uploading video (not in passthrough mode), show preview button
		if( this.getFirefogg() && !this.getEncoderSettings()['passthrough']  
			&& !this.isCopyUpload() ) 
		{
			this.createPreviewControls();
		}
	},

	/**
	 * Create controls for showing a transcode/crop/resize preview
	 */
	createPreviewControls: function() {
		var _this = this;
		// Add the preview button and canvas
		$j( '#upProgressDialog' ).append(
			'<div style="clear:both;height:3em"/>' +
			$j.btnHtml( gM( 'fogg-preview' ), 'fogg_preview', 'triangle-1-e' ) +
			'<div style="padding:10px;">' +
				'<canvas style="margin:auto;" id="fogg_preview_canvas" />' +
			'</div>'
		);

		// Set the initial state
		if ( _this.show_preview == true ) {
			$j( '#fogg_preview_canvas' ).show();
		} else {
			// Fix the icon class
			$j( this ).children( '.ui-icon' )
				.removeClass( 'ui-icon-triangle-1-s' )
				.addClass( 'ui-icon-triangle-1-e' );
			// Set the button text
			$j( this ).children( '.btnText' ).text( gM( 'fogg-preview' ) );
			$j( '#fogg_preview_canvas' ).hide();
		}

		// Bind the preview button
		$j( '#upProgressDialog .fogg_preview' ).btnBind().click( function() {
			return _this.onPreviewClick( this );
		});
	},

	/**
	 * onclick handler for the hide/show preview button
	 */
	onPreviewClick: function( sourceNode ) {
		var button = $j( sourceNode );
		var icon = button.children( '.ui-icon' );
		js_log( "click .fogg_preview" + icon.attr( 'class' ) );

		if ( icon.hasClass( 'ui-icon-triangle-1-e' ) ) {
			// Show preview
			// Toggle button class and set button text to "hide".
			this.show_preview = true;
			icon.removeClass( 'ui-icon-triangle-1-e' ).addClass( 'ui-icon-triangle-1-s' );
			button.children( '.btnText' ).text( gM( 'fogg-hidepreview' ) );
			$j( '#fogg_preview_canvas' ).show( 'fast' );
		} else {
			// Hide preview
			// Toggle button class and set button text to "show".
			this.show_preview = false;
			icon.removeClass( 'ui-icon-triangle-1-s' ).addClass( 'ui-icon-triangle-1-e' );
			button.children( '.btnText' ).text( gM( 'fogg-preview' ) );
			$j( '#fogg_preview_canvas' ).hide( 'slow' );
		}
		// Don't follow the # link
		return false;
	},

	/**
	 * Render the preview frame (asynchronously)
	 */
	renderPreview: function() {
		var _this = this;
		// Set up the hidden video to pull frames from
		if( $j( '#fogg_preview_vid' ).length == 0 )
			$j( 'body' ).append( '<video id="fogg_preview_vid" style="display:none"></video>' );
		var v = $j( '#fogg_preview_vid' ).get( 0 );

		function seekToEnd() {
			var v = $j( '#fogg_preview_vid' ).get( 0 );
			// TODO: document arbitrary 0.4s constant
			v.currentTime = v.duration - 0.4;
		}
		function renderFrame() {
			var v = $j( '#fogg_preview_vid' ).get( 0 );
			var canvas = $j( '#fogg_preview_canvas' ).get( 0 );
			if ( canvas ) {
				canvas.width = 160;
				canvas.height = canvas.width * v.videoHeight / v.videoWidth;
				var ctx = canvas.getContext( "2d" );
				ctx.drawImage( v, 0, 0, canvas.width, canvas.height );
			}
  		}
		function preview() {
			// Initialize the video if it is not set up already
			var v = $j( '#fogg_preview_vid' ).get( 0 );
			if ( v.src != _this.fogg.previewUrl ) {
				js_log( 'init preview with url:' + _this.fogg.previewUrl );
				v.src = _this.fogg.previewUrl;

				// Once it's loaded, seek to the end
				v.removeEventListener( "loadedmetadata", seekToEnd, true );
				v.addEventListener( "loadedmetadata", seekToEnd, true );

				// When the seek is done, render a frame
				v.removeEventListener( "seeked", renderFrame, true );
				v.addEventListener( "seeked", renderFrame, true );

				// Refresh the video duration and metadata
				var previewTimer = setInterval( function() {
					if ( _this.fogg.status() != "encoding" ) {
						clearInterval( previewTimer );
						_this.show_preview == false;
					}
					if ( _this.show_preview == true ) {
						v.load();
					}
				}, 1000 );
			}
		}
		preview();
	},

	/**
	 * Get the DOMNode of the form element we are rewriting.
	 * Returns false if it can't be found.
	 * Overrides mvBaseUploadInterface.getForm().
	 */
	getForm: function() {
		if ( this.form_selector ) {
			return this.pe_getForm();
		} else {
			// No configured form selector
			// Use the first form descendant of the current container
			return $j( this.selector ).parents( 'form:first' ).get( 0 );
		}
	},

	/**
	 * Show a dialog box allowing the user to select the source file of the 
	 * encode/upload operation. The filename is stored by Firefogg until the 
	 * next encode/upload call.
	 *
	 * After a successful select, the UI is updated accordingly.
	 */
	selectSourceFile: function() {
		var _this = this;
		if( !_this.fogg.selectVideo() ) {
			// User clicked "cancel"
			return;
		}
		_this.clearSourceInfoCache();
		_this.updateSourceFileUI();
	},

	/**
	 * Update the UI due to the source file changing
	 */
	updateSourceFileUI: function() {
		js_log( 'videoSelectReady' );

		if ( !_this.fogg.sourceInfo || !_this.fogg.sourceFilename ) {
			// Something wrong with the source file?
			js_log( 'selectSourceFile: sourceInfo/sourceFilename missing' );
			return;
		}

		// Hide the "select file" button and show "select new"
		$j( _this.target_btn_select_file ).hide();
		$j( _this.target_btn_select_new_file)
			.show()
			.unbind()
			.click( function() {
				_this.fogg = new Firefogg();
				_this.selectSourceFile();
			} );

		var settings = this.getEncoderSettings();

		// If we're in passthrough mode, update the interface (if not a form)
		if ( settings['passthrough'] == true && _this.form_type == 'local' ) {
			$j( _this.target_passthrough_mode ).show();
		} else {
			$j( _this.target_passthrough_mode ).hide();
			// Show the "save file" button if this is a local form
			if ( _this.form_type == 'local' ) {
				$j( _this.target_btn_save_local_file ).show();
			} // else the upload will be done on form submit
		}

		// Update the input file name box and show it
		js_log( " should update: " + _this.target_input_file_name + 
				' to: ' + _this.fogg.sourceFilename );
		$j( _this.target_input_file_name )
			.val( _this.fogg.sourceFilename )
			.show();


		// Notify callback new_source_cb
		if ( _this.new_source_cb ) {
			if ( settings['passthrough'] ) {
				var fName = _this.fogg.sourceFilename;
			} else {
				var oggExt = _this.isSourceAudio() ? 'oga' : 'ogg';
				oggExt = _this.isSourceVideo() ? 'ogv' : oggExt;
				oggExt = _this.isUnknown() ? 'ogg' : oggExt;
				oggName = _this.fogg.sourceFilename.substr( 0,
					_this.fogg.sourceFilename.lastIndexOf( '.' ) );
				var fName = oggName + '.' + oggExt;
			}
			_this.new_source_cb( _this.fogg.sourceFilename, fName );
		}
	},

	/**
	 * Get the source file info for the current file selected into this.fogg
	 */
	getSourceFileInfo: function() {
		if ( this.sourceFileInfo == null ) {
			if ( !this.fogg.sourceInfo ) {
				js_error( 'No firefogg source info is available' );
				return false;
			}
			try {
				this.sourceFileInfo = JSON.parse( firefogg.sourceInfo );
			} catch ( e ) {
				js_error( 'error could not parse fogg sourceInfo' );
				return false;
			}
		}
		return this.sourceFileInfo;
	},

	/**
	 * Clear the cache of the source file info, presumably due to a new file
	 * being selected into this.fogg
	 */
	clearSourceInfoCache: function() {
		this.sourceFileInfo = null;
		this.current_encoder_settings = null;
	},

	/**
	 * Save the result of the transcode as a local file
	 */
	doLocalEncodeAndSave: function() {
		var _this = this;
		if ( !this.fogg ) {
			js_error( 'doLocalEncodeAndSave: no Firefogg object!' );
			return false;
		}

		// Set up the target location
		// Firefogg shows the "save as" dialog box, and sets the path chosen as 
		// the destination for a later encode() call.
		if ( !this.fogg.saveVideoAs() ) {
			// User clicked "cancel"
			return false;
		}

		// We have a source file, now do the encode
		this.doEncode(
			function /* onProgress */ ( progress ) {
				_this.updateProgress( progress );
			},
			function /* onDone */ () {
				js_log( "done with encoding (no upload) " );
				// Set status to 100% for one second
				// FIXME: this is either a hack or a waste of time, not sure which
				_this.updateProgress( 1 );
				setTimeout( function() {
					_this.onLocalEncodeDone();
				}
			}
		);
	},

	/**
	 * This is called when a local encode operation has completed. It updates the UI.
	 */
	onLocalEncodeDone() {
		var _this = this;
		_this.updateProgressWin( gM( 'fogg-encoding-done' ),
			gM( 'fogg-encoding-done' ) + '<br>' +
			//show the video at full resolution upto 720px wide
			'<video controls="true" style="margin:auto" id="fogg_final_vid" src="' +
				_this.fogg.previewUrl + '"></video>'
		);
		//load the video and set a callback:
		var v = $j( '#fogg_final_vid' ).get( 0 );
		function resizeVid() {
			var v = $j( '#fogg_final_vid' ).get(0);
			if ( v.videoWidth > 720 ) {
				var vW = 720;
				var vH = 720 * v.videoHeight / v.videoWidth;
			} else {
				var vW = v.videoWidth;
				var vH = v.videoHeight;
			}
			//reize the video:
			$j( v ).css({
				'width': vW,
				'height': vH
			});
			//if large video resize the dialog box:
			if( vW + 5 > 400 ) {
				//also resize the dialog box
				$j( '#upProgressDialog' ).dialog( 'option', 'width', vW + 20 );
				$j( '#upProgressDialog' ).dialog( 'option', 'height', vH + 120 );

				//also position the dialog container
				$j( '#upProgressDialog') .dialog( 'option', 'position', 'center' );
			}
		}
		v.removeEventListener( "loadedmetadata", resizeVid, true );
		v.addEventListener( "loadedmetadata", resizeVid, true );
		v.load();
	},

	/**
	 * Get the appropriate encoder settings for the current Firefogg object, 
	 * into which a video has already been selected.
	 */
	getEncoderSettings function() {
		if ( this.current_encoder_settings == null ) {
			// Clone the default settings
			var defaults = function () {};
			var defaults.prototype = this.default_encoder_settings;
			var settings = new defaults();

			// Grab the extension
			var sf = this.fogg.sourceFilename;
			if ( !sf ) {
				js_error( 'getEncoderSettings(): No Firefogg source filename is available!' );
				return false;
			}
			var ext = '';
			if ( sf.lastIndexOf('.') != -1 )
				ext = sf.substring( sf.lastIndexOf( '.' ) + 1 ).toLowerCase();

			// Determine passthrough mode
			if ( this.isOggFormat() ) {
				// Already Ogg, no need to encode
				settings['passthrough'] = true;
			} else if ( this.isSourceAudio() || this.isSourceVideo() ) {
				// OK to encode
				settings['passthrough'] = false;
			} else {
				// Not audio or video, can't encode
				settings['passthrough'] = true;
			}

			js_log( 'base setupAutoEncoder::' + this.getSourceFileInfo().contentType  +
				' passthrough:' + settings['passthrough'] );
			this.current_encoder_settings = settings;
		}
		return this.current_encoder_settings;
	},

	isUnknown: function() {
		return ( this.getSourceFileInfo().contentType.indexOf("unknown") != -1 );
	},

	isSourceAudio: function() {
		return ( this.getSourceFileInfo().contentType.indexOf("audio/") != -1 );
	},

	isSourceVideo: function() {
		return ( this.getSourceFileInfo().contentType.indexOf("video/") != -1 );
	},

	isOggFormat: function() {
		var contentType = this.getSourceFileInfo().contentType;
		return ( contentType.indexOf("video/ogg") != -1 
			|| contentType.indexOf("application/ogg") != -1 );
	},

	/**
	 * Get the default title of the progress window
	 */
	getProgressTitle: function() {
		js_log( "fogg:getProgressTitle f:" +  ( this.getFirefogg() ? 'on' : 'off' ) + 
			' mode:' + this.form_type );
		// Return the parent's title if we don't have Firefogg turned on
		if ( !this.getFirefogg() || !this.firefogg_form_action ) {
			return this.pe_getProgressTitle();
		} else if ( this.form_type == 'local' ) {
			return gM( 'fogg-transcoding' );
		} else {
			return gM( 'mwe-upload-transcode-in-progress' );
		}
	},

	/**
	 * Do an upload, with the mode given by this.upload_mode
	 */
	doUpload: function() {
		var _this = this;
		js_log( "firefogg: doUpload:: " + 
			( this.getFirefogg() ? 'on' : 'off' ) + 
			' up mode:' + _this.upload_mode );

		// If Firefogg is disabled, just invoke the parent method
		if( !this.getFirefogg() || !this.firefogg_form_action ) {
			_this.pe_doUpload();
			return;
		}

		if ( _this.upload_mode == 'post' ) {
			// Encode and then do a post upload
			_this.doEncode(
				function /* onProgress */ ( progress ) {
					_this.updateProgress( progress );
				},
				function /* onDone */ () {
					js_log( 'done with encoding do POST upload:' + _this.editForm.action );
					// ignore warnings & set source type
					//_this.formData[ 'wpIgnoreWarning' ]='true';
					_this.formData['wpSourceType'] = 'upload';
					_this.formData['action'] = 'submit';
					// wpUploadFile is set by firefogg
					delete _this.formData['file'];

					_this.fogg.post( _this.editForm.action, 'wpUploadFile', 
						JSON.stringify( _this.formData ) );
					_this.doUploadStatus();
				}
			);
		} else if ( _this.upload_mode == 'api' ) {
			// We have the API so we can do a chunk upload
			_this.doChunkUpload();
		} else {
			js_error( 'Error: unrecongized upload mode: ' + _this.upload_mode );
		}
	},

	/**
	 * Do both uploading and encoding at the same time. Uploads 1MB chunks as 
	 * they become ready.
	 */
	doChunkUpload : function() {
		js_log( 'firefogg::doChunkUpload' );
		var _this = this;
		_this.action_done = false;

		if ( !_this.getEncoderSettings()['passthrough'] ) {
			// We are transcoding to Ogg. Fix the destination extension, it 
			// must be ogg/ogv/oga.
			var fileName = _this.formData['filename'];
			var ext = '';
			var dotPos = fileName.lastIndexOf( '.' );
			if ( dotPos != -1 ) {
				ext = fileName.substring( dotPos ).toLowerCase();
			}
			if ( $j.inArray( ext.substr( 1 ), _this.ogg_extensions ) == -1  ) {
				_this.formData['filename'] = fileName.substr(
					0, 


				var extreg = new RegExp( ext + '$', 'i' );
				_this.formData['filename'] = fileName.replace( extreg, '.ogg' );
			}
		}

		// Get the edit token from formData if it's not set already
		if ( !_this.editToken && _this.formData['token'] ) {
			_this.editToken = _this.formData['token'];
		}

		if( _this.editToken ) {
			js_log( 'we already have an edit token: ' + _this.editToken );
			_this.doChunkUploadWithFormData();
			return;
		}

		// No edit token. Fetch it asynchronously and then do the upload.
		get_mw_token(
			'File:'+ _this.formData['filename'],
			_this.api_url,
			function( editToken ) {
				if( !editToken || editToken == '+\\' ) {
					_this.updateProgressWin( gM( 'fogg-badtoken' ), gM( 'fogg-badtoken' ) );
					return false;
				}
				_this.editToken = editToken;
				_this.doChunkUploadWithFormData();
			}
		);
	},

	/**
	 * Internal function called from doChunkUpload() when the edit token is available
	 */
	doChunkUploadWithFormData: function() {
		var _this = this;
		js_log( "firefogg::doChunkUploadWithFormData"  + _this.editToken );
		// Build the API URL
		var aReq = {
			'action': 'upload',
			'format': 'json',
			'filename': _this.formData['filename'],
			'comment': _this.formData['comment'],
			'enablechunks': 'true'
		};

		if ( _this.editToken )
			aReq['token'] = this.editToken;

		if ( _this.formData['watch'] )
			aReq['watch'] = _this.formData['watch'];

		if ( _this.formData['ignorewarnings'] )
			aReq['ignorewarnings'] = _this.formData['ignorewarnings'];

		var encoderSettings = this.getEncoderSettings();
		js_log( 'do fogg upload/encode call: ' + _this.api_url + ' :: ' + JSON.stringify( aReq ) );
		js_log( 'foggEncode: ' + JSON.stringify( encoderSettings ) );
		_this.fogg.upload( JSON.stringify( encoderSettings ), _this.api_url, 
			JSON.stringify( aReq ) );

		// Start polling the upload status
		_this.doUploadStatus();
	},

	/**
	 * Encode the video and monitor progress.
	 *
	 * Calls progressCallback() regularly with a number between 0 and 1 indicating progress.
	 * Calls doneCallback() when the encode is finished.
	 *
	 * Asynchronous, returns immediately.
	 */
	doEncode : function( progressCallback, doneCallback ) {
		var _this = this;
		_this.action_done = false;
		_this.displayProgressOverlay();
		var encoderSettings = this.getEncoderSettings();
		js_log( 'doEncode: with: ' +  JSON.stringify( encoderSettings ) );
		_this.fogg.encode( JSON.stringify( encoderSettings ) );

		//show transcode status:
		$j( '#up-status-state' ).html( gM( 'mwe-upload-transcoded-status' ) );

		//setup a local function for timed callback:
		var encodingStatus = function() {
			var status = _this.fogg.status();

			if ( _this.show_preview == true && _this.fogg.state == 'encoding' ) {
				_this.renderPreview();
			}

			// Update progress
			progressCallback( _this.fogg.progress() );

			//loop to get new status if still encoding
			if ( _this.fogg.state == 'encoding' ) {
				setTimeout( encodingStatus, 500 );
			} else if ( _this.fogg.state == 'encoding done' ) {
				_this.action_done = true;
				progressCallback( 1 );
				doneCallback();
			} else if ( _this.fogg.state == 'encoding fail' ) {
				//@@todo error handling:
				js_error( 'encoding failed' );
			}
		}
		encodingStatus();
	},

	/**
	 * Poll the upload progress and update the UI regularly until the upload is complete. 
	 *
	 * Asynchronous, returns immediately.
	 */
	doUploadStatus: function() {
		var _this = this;
		$j( '#up-status-state' ).html( gM( 'mwe-uploaded-status' ) );

		_this.oldResponseText = '';

		// Create a local function for timed callback
		var uploadStatus = function() {
			var response_text = _this.fogg.responseText;
			if ( !response_text ) {
				try {
					var pstatus = JSON.parse( _this.fogg.uploadstatus() );
					response_text = pstatus["responseText"];
				} catch( e ) {
					js_log( "could not parse uploadstatus / could not get responseText" );
				}
			}

			if ( _this.oldResponseText != response_text ) {
				js_log( 'new result text:' + response_text + ' state:' + _this.fogg.state );
				_this.oldResponseText = response_text;
				// Parse the response text and check for errors
				try {
					var apiResult = JSON.parse( response_text );
				} catch( e ) {
					js_log( "could not parse response_text::" + response_text + 
						' ...for now try with eval...' );
					try {
						var apiResult = eval( response_text );
					} catch( e ) {
						var apiResult = null;
					}
				}
				if ( apiResult && !_this.isApiSuccess( apiResult ) ) {
					// Show the error and stop the upload
					_this.showApiError( apiResult );
					_this.action_done = true;
					_this.fogg.cancel();
					return false;
				}
			}
			if ( _this.show_preview == true ) {
				if ( _this.fogg.state == 'encoding' ) {
					_this.renderPreview();
				}
			}

			// Update the progress bar
			_this.updateProgress( _this.fogg.progress() );

			// If we're still uploading or encoding, continue to poll the status
			if ( _this.fogg.state == 'encoding' || _this.fogg.state == 'uploading' ) {
				setTimeout( uploadStatus, 100 );
				return;
			}

			// Upload done?
			if ( -1 == $j.inArray( _this.fogg.state, [ 'upload done', 'done', 'encoding done' ] ) ) {
				js_log( 'Error:firefogg upload error: ' + _this.fogg.state );
				return;
			}

			if ( _this.upload_mode == 'api' ) {
				if ( apiResult && apiResult.resultUrl ) {
					var buttons = {};
					buttons[ gM( 'mwe-go-to-resource' ) ] =  function() {
						window.location = apiResult.resultUrl;
					}
					var go_to_url_txt = gM( 'mwe-go-to-resource' );
					var showMessage = true;
					if ( typeof _this.done_upload_cb == 'function' ) {
						// Call the callback
						// It will return false if it doesn't want us to show our own "done" message
						showMessage = _this.done_upload_cb( _this.formData );
					}
					if ( showMessage ) {
						_this.updateProgressWin( gM( 'mwe-successfulupload' ), 
							gM( 'mwe-upload_done', apiResult.resultUrl ), buttons );
					} else {
						this.action_done = true;
						$j( '#upProgressDialog' ).empty().dialog( 'close' );
					}
				} else {
					// Done state with error? Not really possible given how firefogg works...
					js_log( " Upload done in chunks mode, but no resultUrl!" );
				}
			} else {
				js_log( "Error:: not supported upload mode" +  _this.upload_mode );
			}
		}
		uploadStatus();
	},

	/**
	 * This is the cancel button handler, referenced by getCancelButton() in the parent.
	 */
	onCancel: function( dlElm ) {
		if ( !this.have_firefogg ) {
			return this.pe_cancel_action();
		}
		js_log( 'firefogg:cancel' )
		if ( confirm( gM( 'mwe-cancel-confim' ) ) ) {
			// FIXME: sillyness
			if ( navigator.oscpu && navigator.oscpu.search( 'Win' ) >= 0 ) {
				alert( 'sorry we do not yet support cancel on windows' );
			} else {
				this.action_done = true;
				this.fogg.cancel();
				$j( dlElm ).empty().dialog( 'close' );
			}
		}
		// Don't follow the # link:
		return false;
	}
};
