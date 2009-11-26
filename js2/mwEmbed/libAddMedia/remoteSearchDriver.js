/*
 * remoteSearchDriver
 * Provides a base interface for the Add-Media-Wizard
 * supporting remote searching of http archives for free images/audio/video assets
 */

loadGM( {
	"mwe-add_media_wizard" : "Add media wizard",
	"mwe-media_search" : "Media search",
	"rsd_box_layout" : "Box layout",
	"rsd_list_layout" : "List layout",
	"rsd_results_desc" : "Results $1 to $2",
	"rsd_results_desc_total" : "Results $1 to $2 of $3",
	"rsd_results_next" : "next",
	"rsd_results_prev" : "previous",
	"rsd_no_results" : "No search results for <b>$1<\/b>",
	"mwe-upload_tab" : "Upload",
	"rsd_layout" : "Layout:",
	"rsd_resource_edit" : "Edit resource: $1",
	"mwe-resource_description_page" : "Resource description page",
	"mwe-link" : "link",
	"rsd_local_resource_title" : "Local resource title",
	"rsd_do_insert" : "Do insert",
	"mwe-cc_title" : "Creative Commons",
	"mwe-cc_by_title" : "Attribution",
	"mwe-cc_nc_title" : "Noncommercial",
	"mwe-cc_nd_title" : "No Derivative Works",
	"mwe-cc_sa_title" : "Share Alike",
	"mwe-cc_pd_title" : "Public Domain",
	"mwe-unknown_license" : "Unknown license",
	"mwe-no_import_by_url" : "This user or wiki <b>cannot<\/b> import assets from remote URLs.<p>Do you need to login?<\/p><p>Is upload_by_url permission set for you?<br \/>Does the wiki have <a href=\"http:\/\/www.mediawiki.org\/wiki\/Manual:$wgAllowCopyUploads\">$wgAllowCopyUploads<\/a> enabled?<\/p>",
	"mwe-results_from" : "Results from <a href=\"$1\" target=\"_new\" >$2<\/a>",
	"mwe-missing_desc_see_source" : "This asset is missing a description. Please see the [$1 original source] and help describe it.",
	"rsd_config_error" : "Add media wizard configuration error: $1",
	"mwe-your_recent_uploads" : "Your recent uploads to $1",
	"mwe-upload_a_file" : "Upload a new file to $1",
	"mwe-resource_page_desc" : "Resource page description:",
	"mwe-edit_resource_desc" : "Edit wiki text resource description:",
	"mwe-local_resource_title" : "Local resource title:",
	"mwe-watch_this_page" : "Watch this page",
	"mwe-do_import_resource" : "Import resource",
	"mwe-update_preview" : "Update resource page preview",
	"mwe-cancel_import" : "Cancel import",
	"mwe-importing_asset" : "Importing asset",
	"mwe-preview_insert_resource" : "Preview insert of resource: $1",
	"mwe-checking-resource" : "Checking for resource",
	"mwe-resource-needs-import" : "Resource $1 needs to be imported to $2",
	"mwe-ftype-svg" : "SVG vector file",
	"mwe-ftype-jpg" : "JPEG image file",
	"mwe-ftype-png" : "PNG image file",
	"mwe-ftype-oga" : "Ogg audio file",
	"mwe-ftype-ogg" : "Ogg video file",
	"mwe-ftype-unk" : "Unknown file format",

	"rsd-wiki_commons-title": "Wikimedia Commons",
	"rsd-wiki_commons": "Wikimedia Commons, an archive of freely-licensed educational media content (images, sound and video clips)",

	"rsd-this_wiki-title" : "This wiki",
	"rsd-this_wiki-desc" : "The local wiki install",

	"rsd-archive_org-title": "Archive.org",
	"rsd-archive_org-desc" : "The Internet Archive, a digital library of cultural artifacts",

	"rsd-flickr-title" : "Flickr.com",
	"rsd-flickr-desc" : "Flickr.com, a online photo sharing site",
	"rsd-metavid-title" : "Metavid.org",
	"rsd-metavid-desc" : "Metavid.org, a community archive of US House and Senate floor proceedings"

} );

var default_remote_search_options = {
	'profile': 'mediawiki_edit',
	'target_container': null, // the div that will hold the search interface

	'target_invoke_button': null, // the button or link that will invoke the search interface

	'default_provider_id': 'all', // all or one of the content_providers ids

	'local_wiki_api_url': null,

	// Can be 'api', 'autodetect', 'remote_link'
	'import_url_mode': 'api',

	'target_title': null,

	// Edit tools (can be an array of tools or keyword 'all')
	'enabled_tools': 'all',


	'target_textbox': null,
	'target_render_area': null, // where output render should go:
	'instance_name': null, // a globally accessible callback instance name
	'default_query': null, // default search query

	// Specific to sequence profile
	'p_seq': null,
	'cFileNS': 'File', // What is the canonical namespace prefix for images
	                   // @@todo (should get that from the api or in-page vars)

	'upload_api_target': 'local', // can be local or the url or remote
	'upload_api_name': null,
	'upload_api_proxy_frame': null, // a page that will request mw.proxy.server

	'enabled_providers': 'all', // can be keyword 'all' or an array of enabled content provider keys

	'currentProvider': null // sets the default display item:
}

if ( typeof wgServer == 'undefined' )
	wgServer = '';
if ( typeof wgScriptPath == 'undefined' )
	wgScriptPath = '';
if ( typeof stylepath == 'undefined' )
	stylepath = '';

/*
 * Base remoteSearch Driver interface
 */
var remoteSearchDriver = function( iObj ) {
	return this.init( iObj );
}

remoteSearchDriver.prototype = {
	results_cleared: false,
	
	caretPos: null, // lazy initialised
	textboxValue: null, // lazy initialised

	// here we define the set of possible media content providers:
	// FIXME: unused
	main_search_options: {
		'selprovider': {
			'title': 'Select Providers'
		},
		'advanced_search': {
			'title': 'Advanced Options'
		}
	},

	/** the default content providers list.
	 *
	 * (should be note that special tabs like "upload" and "combined" don't go into the content providers list:
	 * @note do not use double underscore in content providers names (used for id lookup)
	 *
	 * @@todo we will want to load more per user-preference and per category lookup
	 */
	content_providers: {
		/*content_providers documentation:
		 *  @@todo we should move the bulk of the configuration to each file
		 *

			@enabled: whether the search provider can be selected
			@checked: whether the search provider will show up as selectable tab
			@default: default: if the current cp should be displayed (only one should be the default)
			@title: the title of the search provider
			@desc: can use html
			@api_url: the url to query against given the library type:
			@lib: the search library to use corresponding to the
			    search object ie: 'mediaWiki' = new mediaWikiSearchSearch()
			@tab_img: the tab image (if set to false use title text)
			    if === "true" use standard location skin/images/{cp_id}_tab.png
			    if === string use as url for image

			@linkback_icon default is: /wiki/skins/common/images/magnify-clip.png

			//domain insert: two modes: simple config or domain list:
			@local : if the content provider assets need to be imported or not.
			@local_domains : sets of domains for which the content is local
			//@@todo should query wgForeignFileRepos setting maybe interwikimap from the api
		 */

		'this_wiki': {
			'enabled': 1,
			'checked': 1,
			'api_url':  ( wgServer && wgScriptPath ) ? 
				wgServer + wgScriptPath + '/api.php' : null,
			'lib': 'mediaWiki',
			'local': true,
			'tab_img': false
		},
		'wiki_commons': {
			'enabled': 1,
			'checked': 1,
			'homepage': 'http://commons.wikimedia.org/wiki/Main_Page',
			'api_url': 'http://commons.wikimedia.org/w/api.php',
			'lib': 'mediaWiki',
			'resource_prefix': 'WC_', // prefix on imported resources (not applicable if the repository is local)

			// if we should check for shared repository asset ( generally only applicable to commons )
			'check_shared': true,

			// list all the domains where commons is local?
			// probably should set this some other way by doing an api query
			// or by seeding this config when calling the remote search?
			'local_domains': [ 'wikimedia', 'wikipedia', 'wikibooks' ],
			// specific to wiki commons config:
			'search_title': false, // disable title search
			'tab_img': true
		},
		'archive_org': {
			'enabled': 1,
			'checked': 1,
			'homepage': 'http://www.archive.org/about/about.php',

			'api_url': 'http://homeserver7.us.archive.org:8983/solr/select',
			'lib': 'archiveOrg',
			'local': false,
			'resource_prefix': 'AO_',
			'tab_img': true
		},
		'flickr': {
			'enabled': 1,
			'checked': 1,
			'homepage': 'http://www.flickr.com/about/',

			'api_url': 'http://www.flickr.com/services/rest/',
			'lib': 'flickr',
			'local': false,
			// Just prefix with Flickr_ for now.
			'resource_prefix': 'Flickr_',
			'tab_img': true
		},
		'metavid': {
			'enabled': 1,
			'checked': 1,
			'homepage': 'http://metavid.org/wiki/Metavid_Overview',
			'api_url': 'http://metavid.org/w/index.php?title=Special:MvExportSearch',
			'lib': 'metavid',
			'local': false, // if local set to true we can use local
			'resource_prefix': 'MV_', // what prefix to use on imported resources

			'local_domains': ['metavid'], // if the domain name contains metavid
			                              // no need to import metavid content to metavid sites

			'stream_import_key': 'mv_ogg_low_quality', // which stream to import, could be mv_ogg_high_quality
			                                           // or flash stream, see ROE xml for keys

			'remote_embed_ext': false, // if running the remoteEmbed extension no need to copy local
			                           // syntax will be [remoteEmbed:roe_url link title]
			'tab_img': true
		},
		// special cp "upload"
		'upload': {
			'enabled': 1,
			'checked': 1,
			'title': 'Upload'
		}
	},

	// define the licenses
	// ... this will get complicated quick...
	// (just look at complexity for creative commons without excessive "duplicate data")
	// ie cc_by could be "by/3.0/us/" or "by/2.1/jp/" to infinitum...
	// some complexity should be negated by license equivalences.

	// but we will have to abstract into another class let content providers provide license urls
	// and we have to clone the license object and allow local overrides

	licenses: {
		// for now only support creative commons type licenses
		// used page: http://creativecommons.org/licenses/
		'cc': {
			'base_img_url':'http://upload.wikimedia.org/wikipedia/commons/thumb/',
			'base_license_url': 'http://creativecommons.org/licenses/',
			'licenses': [
				'by',
				'by-sa',
				'by-nc-nd',
				'by-nc',
				'by-nd',
				'by-nc-sa',
				'by-sa',
				'pd'
			],
			'license_images': {
				'by': {
					'image_url': '1/11/Cc-by_new_white.svg/20px-Cc-by_new_white.svg.png'
				},
				'nc': {
					'image_url': '2/2f/Cc-nc_white.svg/20px-Cc-nc_white.svg.png'
				},
				'nd': {
					'image_url': 'b/b3/Cc-nd_white.svg/20px-Cc-nd_white.svg.png'
				},
				'sa': {
					'image_url': 'd/df/Cc-sa_white.svg/20px-Cc-sa_white.svg.png'
				},
				'pd': {
					'image_url': '5/51/Cc-pd-new_white.svg/20px-Cc-pd-new_white.svg.png'
				}
			}
		}
	},

	// some default layout values:
	thumb_width: 80,
	image_edit_width: 400,
	video_edit_width: 400,
	insert_text_pos: 0, // insert at the start (will be overwritten by the user cursor pos)
	displayMode : 'box', // box or list

	cUpLoader: null,
	clipEdit: null,
	proxySetupDone: null,
	dmodalCss: {},

	init: function( options ) {
		var _this = this;
		js_log( 'remoteSearchDriver:init' );
		// Add in a local "id" reference to each provider
		for ( var cp_id in this.content_providers ) {
			this.content_providers[ cp_id ].id = cp_id;
		}

		// Merge in the options
		// @@todo for cleaner config we should set _this.opt to the provided options
		$j.extend( _this, default_remote_search_options, options );

		// Quick fix for cases where people put ['all'] instead of 'all' for enabled_providers
		if ( _this.enabled_providers.length == 1 && _this.enabled_providers[0] == 'all' )
			_this.enabled_providers = 'all';

		// Set up content_providers
		for ( var provider in this.content_providers ) {
			if ( _this.enabled_providers == 'all' && !this.currentProvider  ) {
				this.currentProvider = provider;
				break;
			} else {
				if ( $j.inArray( provider, _this.enabled_providers ) != -1 ) {
					// This provider is enabled
					this.content_providers[provider].enabled = true;
					// Set the current provider to the first enabled one
					if ( !this.currentProvider ) {
						this.currentProvider = provider;
					}
				} else {
					// This provider is disabled
					if ( _this.enabled_providers != 'all' ) {
						this.content_providers[provider].enabled = false;
					}
				}
			}
		}

		// Set the upload target name if unset
		if ( _this.upload_api_target == 'local' 
			&& ! _this.upload_api_name 
			&& typeof wgSiteName != 'undefined' )
		{
			_this.upload_api_name =  wgSiteName;
		}

		// Set the target to "proxy" if a proxy frame is configured
		if ( _this.upload_api_proxy_frame )
			_this.upload_api_target = 'proxy';

		// Set up the local API upload URL
		if ( _this.upload_api_target == 'local' ) {
			if ( ! _this.local_wiki_api_url ) {
				$j( '#tab-upload' ).html( gM( 'rsd_config_error', 'missing_local_api_url' ) );
				return false;
			} else {
				_this.upload_api_target = _this.local_wiki_api_url;
			}
		}

		// Set up the "add media wizard" button, which invokes this object
		if ( $j( this.target_invoke_button ).length == 0 ) {
			js_log( "RemoteSearchDriver:: no target invocation provided " + 
				"(will have to run your own createUI() )" );
		} else {
			if ( this.target_invoke_button ) {
				$j( this.target_invoke_button )
					.css( 'cursor', 'pointer' )
					.attr( 'title', gM( 'mwe-add_media_wizard' ) )
					.click( function() {
						_this.createUI();
					} );
			}
		}
	},

	/*
	 * getLicenseIconHtml
	 * @param license_key  the license key (ie "by-sa" or "by-nc-sa" etc)
	 */
	getLicenseIconHtml: function( licenseObj ) {
		// js_log('output images: '+ imgs);
		return '<div class="rsd_license" title="' + licenseObj.title + '" >' +
			'<a target="_new" href="' + licenseObj.lurl + '" ' +
			'title="' + licenseObj.title + '">' +
			licenseObj.img_html +
			'</a>' +
	  		'</div>';
	},

	/*
	 * getLicenseKeyFromKey
	 * @param license_key the key of the license (must be defined in: this.licenses.cc.licenses)
	 */
	getLicenseFromKey: function( license_key, force_url ) {
		// set the current license pointer:
		var cl = this.licenses.cc;
		var title = gM( 'mwe-cc_title' );
		var imgs = '';
		var license_set = license_key.split( '-' );
		for ( var i = 0; i < license_set.length; i++ ) {
			var lkey = license_set[i];
			if ( !cl.license_images[ lkey ] ) {
				js_log( "MISSING::" + lkey );
			}

			title += ' ' + gM( 'mwe-cc_' + lkey + '_title' );
			imgs += '<img class="license_desc" width="20" src="' +
				cl.base_img_url + cl.license_images[ lkey ].image_url + '">';
		}
		var url = ( force_url ) ? force_url : cl.base_license_url + cl.licenses[ license_key ];
		return {
			'title': title,
			'img_html': imgs,
			'key': license_key,
			'lurl': url
		};
	},

	/*
	 * getLicenseKeyFromUrl
	 * @param license_url the url of the license
	 */
	getLicenseFromUrl: function( license_url ) {
		// check for some pre-defined url types:
		if ( license_url == 'http://www.usa.gov/copyright.shtml' ||
			license_url == 'http://creativecommons.org/licenses/publicdomain' )
			return this.getLicenseFromKey( 'pd' , license_url );


		// js_log("getLicenseFromUrl::" + license_url);
		// first do a direct lookup check:
		for ( var j = 0; j < this.licenses.cc.licenses.length; j++ ) {
			var jL = this.licenses.cc.licenses[ j ];
			// special 'pd' case:
			if ( jL == 'pd' ) {
				var keyCheck = 'publicdomain';
			} else {
				var keyCheck = jL;
			}
			if ( mw.parseUri( license_url ).path.indexOf( '/' + keyCheck + '/' ) != -1 ) {
				return this.getLicenseFromKey( jL , license_url );
			}
		}
		// Could not find it return mwe-unknown_license
		return {
			'title': gM( 'mwe-unknown_license' ),
			'img_html': '<span>' + gM( 'mwe-unknown_license' ) + '</span>',
			'lurl': license_url
		};
	},

	/**
	* getTypeIcon
	* @param str mime type of the requested file
	*/
	getTypeIcon: function( mimetype ) {
		var typestr = 'unk';
		switch ( mimetype ) {
			case 'image/svg+xml':
				typestr = 'svg';
				break;
			case 'image/jpeg':
				typestr = 'jpg'
				break;
			case 'image/png':
				typestr = 'png';
				break;
			case 'audio/ogg':
				typestr = 'oga';
			case 'video/ogg':
			case 'application/ogg':
				typestr = 'ogg';
				break;
		}

		if ( typestr == 'unk' ) {
			js_log( "unkown ftype: " + mimetype );
			return '';
		}

		return '<div ' + 
			'class="rsd_file_type ui-corner-all ui-state-default ui-widget-content" ' + 
			'title="' + gM( 'mwe-ftype-' + typestr ) + '">' +
			typestr  +
			'</div>';
	},

	createUI: function() {
		var _this = this;

		this.clearTextboxCache();
		// setup the parent container:
		this.createDialogContainer();
		// fill in the html:
		this.initDialog();
		// bind actions:
		this.add_interface_bindings();

		// update the target binding to just un-hide the dialog:
		if ( this.target_invoke_button ) {
			$j( this.target_invoke_button )
				.unbind()
				.click( function() {
					js_log( "createUI:target_invoke_button: click showDialog" );
					 _this.showDialog();
				 } );
		}
	},

	showDialog: function() {
		var _this = this;
		js_log( "showDialog::" );
		_this.clearTextboxCache();
		var query = _this.getDefaultQuery();
		if ( query !=  $j( '#rsd_q' ).val() ) {
			$j( '#rsd_q' ).val( query );
			_this.showCurrentTab();
		}
		// $j(_this.target_container).dialog("open");
		$j( _this.target_container ).parents( '.ui-dialog' ).fadeIn( 'slow' );
		// re-center the dialog:
		$j( _this.target_container ).dialog( 'option', 'position', 'center' );
	},

	clearTextboxCache: function() {
		this.caretPos = null;
		this.textboxValue = null;
		this.default_query = null;
	},

	getCaretPos: function() {
		if ( this.caretPos == null ) {
			if ( this.target_textbox ) {
				this.caretPos = $j( this.target_textbox ).getCaretPosition();
			} else {
				this.caretPos = false;
			}
		}
		return this.caretPos;
	},

	getTextboxValue: function() {
		if ( this.textboxValue == null ) {
			if ( this.target_textbox ) {
				this.textboxValue = $j( this.target_textbox ).val();
			} else {
				this.textboxValue = '';
			}
		}
		return this.textboxValue;
	},

	getDefaultQuery: function() {
		if ( this.default_query == null ) {
			if ( this.target_textbox ) {
				var ts = $j( this.target_textbox ).textSelection();
				if ( ts != '' ) {
					this.default_query = ts;
				} else {
					this.default_query = '';
				}
			}
		}
		return this.default_query;
	},

	createDialogContainer: function() {
		js_log( "createDialogContainer" );
		var _this = this;
		// add the parent target_container if not provided or missing
		if ( _this.target_container && $j( _this.target_container ).length != 0 ) {
			js_log(  'dialog already exists' );
			return;
		}

		_this.target_container = '#rsd_modal_target';
		$j( 'body' ).append(
			'<div ' + 
				'id="rsd_modal_target" ' + 
				'style="position:absolute;top:3em;left:0px;bottom:3em;right:0px;" ' + 
				'title="' + gM( 'mwe-add_media_wizard' ) + '" >' + 
			'</div>' );
		// js_log('appended: #rsd_modal_target' + $j(_this.target_container).attr('id'));
		// js_log('added target id:' + $j(_this.target_container).attr('id'));
		// get layout
		js_log( 'width: ' + $j( window ).width() +  ' height: ' + $j( window ).height() );
		var cBtn = {};
		cBtn[ gM( 'mwe-cancel' ) ] = function() {
			_this.onCancelClipEdit();
		}

		$j( _this.target_container ).dialog( {
			bgiframe: true,
			autoOpen: true,
			modal: true,
			draggable: false,
			resizable: false,
			buttons: cBtn,
			close: function() {
				// if we are 'editing' a item close that
				// @@todo maybe prompt the user?
				_this.onCancelClipEdit();
				$j( this ).parents( '.ui-dialog' ).fadeOut( 'slow' );
			}
		} );
		$j( _this.target_container ).dialogFitWindow();
		$j( window ).resize( function() {
			$j( _this.target_container ).dialogFitWindow();
		} );

		// add cancel callback and updated button with icon
		_this.onCancelClipEdit();
	},

	// sets up the initial html interface
	initDialog: function() {
		js_log( 'initDialog' );
		var _this = this;
		js_log( 'f::initDialog' );

		var o = '<div class="rsd_control_container" style="width:100%">' +
			'<form id="rsd_form" action="javascript:return false;" method="GET">' +
			'<input ' + 
				'class="ui-widget-content ui-corner-all" ' + 
				'type="text" ' + 
				'tabindex="1" ' + 
				'value="' + this.getDefaultQuery() + '" ' + 
				'maxlength="512" ' + 
				'id="rsd_q" ' + 
				'name="rsd_q" ' +
				'size="20" ' + 
				'autocomplete="off" />' +
			$j.btnHtml( gM( 'mwe-media_search' ), 'rms_search_button', 'search' ) +
			'</form>';
		// close up the control container:
		o += '</div>';

		// search provider tabs based on "checked" and "enabled" and "combined tab"
		o += '<div ' + 
				'id="rsd_results_container" ' + 
				'style="top:0px;bottom:0px;left:0px;right:0px;">' + 
			'</div>';
		$j( this.target_container ).html( o );
		// add simple styles:
		$j( this.target_container + ' .rms_search_button' ).btnBind().click( function() {
			_this.showCurrentTab();
		} );

		// draw the tabs:
		this.createTabs();
		// run the default search:
		if ( this.getDefaultQuery() )
			this.showCurrentTab();

		// Add bindings
		$j( '#mso_selprovider,#mso_selprovider_close' )
			.unbind()
			.click( function() {
				if ( $j( '#rsd_options_bar:hidden' ).length != 0 ) {
					$j( '#rsd_options_bar' ).animate( {
						'height': '110px',
						'opacity': 1
					}, "normal" );
				} else {
					$j( '#rsd_options_bar' ).animate( {
						'height': '0px',
						'opacity': 0
					}, "normal", function() {
						$j( this ).hide();
					} );
				}
			} );
		// set form bindings
		$j( '#rsd_form' )
			.unbind()
			.submit( function() {
				_this.showCurrentTab();
				// don't submit the form
				return false;
			} );
	},

	showUploadTab: function() {
		js_log( "showUploadTab::" );
		var _this = this;
		// set it to loading:
		mv_set_loading( '#tab-upload' );
		// do things async to keep interface snappy
		setTimeout(
			function() {
				// check if we need to setup the proxy::
				if ( _this.upload_api_target == 'proxy' ) {
					_this.setupProxy( function() {
						_this.showUploadForm();
					} );
				} else {
					_this.showUploadForm();
				}
			}, 
			1 );
	},

	showUploadForm: function() {
		var _this = this;
		mvJsLoader.doLoad( ['$j.fn.simpleUploadForm'], function() {
			// get extends info about the file
			var provider = _this.content_providers['this_wiki'];

			// check for "this_wiki" enabled
			/*if(!provider.enabled){
				$j('#tab-upload')
					.html('error this_wiki not enabled (can\'t get uploaded file info)');
				return false;
			}*/

			// load  this_wiki search system to grab the resource
			_this.loadSearchLib( provider, function() {
				_this.showUploadForm_internal( provider );
			} );
		} );
	},

	showUploadForm_internal: function( provider ) {
		var _this = this;
		var uploadMsg = gM( 'mwe-upload_a_file', _this.upload_api_name );
		var recentUploadsMsg = gM( 'mwe-your_recent_uploads', _this.upload_api_name );
		// do basic layout form on left upload "bin" on right
		$j( '#tab-upload' ).html( 
			'<table>' +
			'<tr>' +
			'<td valign="top" style="width:350px; padding-right: 12px;">' +
			'<h4>' + uploadMsg + '</h4>' +
			'<div id="upload_form">' +
			mv_get_loading_img() +
			'</div>' +
			'</td>' +
			'<td valign="top" id="upload_bin_cnt">' +
			'<h4>' + recentUploadsMsg + '</h4>' +
			'<div id="upload_bin">' +
			mv_get_loading_img() +
			'</div>' +
			'</td>' +
			'</tr>' +
			'</table>' );


		// fill in the user page:
		if ( typeof wgUserName != 'undefined' && wgUserName ) {
			// load the upload bin with anything the current user has uploaded
			provider.sObj.getUserRecentUploads( wgUserName, function() {
				_this.showResults();
			} );
		} else {
			$j( '#upload_bin_cnt' ).empty();
		}

		// deal with the api form upload form directly:
		$j( '#upload_form' ).simpleUploadForm( {
			"api_target" : _this.upload_api_target,
			"ondone_cb": function( resultData ) {
				var wTitle = resultData['filename'];
				// add a loading div
				_this.addResourceEditLoader();
				// @@note: we have most of what we need in resultData imageinfo
				provider.sObj.addByTitle( wTitle, function( resource ) {
					// Redraw ( with added result if new )
					_this.showResults();
					// Pull up resource editor:
					_this.showResourceEditor( resource, $j( '#res_upload__' + resource.id ).get( 0 ) );
				} );
				// Return false to close progress window:
				return false;
			}
		} );
	},

	showCurrentTab: function() {
		if ( this.currentProvider == 'upload' ) {
			this.showUploadTab();
		} else {
			this.showSearchTab( this.currentProvider, false );
		}
	}

	showSearchTab: function( providerName, resetPaging ) {
		js_log( "f:showSearchTab::" + providerName );

		var draw_direct_flag = true;

		// else do showSearchTab
		var provider = this.content_providers[providerName];

		// check if we need to update:
		if ( typeof provider.sObj != 'undefined' ) {
			if ( provider.sObj.last_query == $j( '#rsd_q' ).val() 
				&& provider.sObj.last_offset == provider.offset ) 
			{
				js_log( 'last query is: ' + provider.sObj.last_query + 
					' matches: ' +  $j( '#rsd_q' ).val() );
			} else {
				js_log( 'last query is: ' + provider.sObj.last_query + 
					' not match: ' +  $j( '#rsd_q' ).val() );
				draw_direct_flag = false;
			}
		} else {
			draw_direct_flag = false;
		}
		if ( !draw_direct_flag ) {
			// see if we should reset the paging
			if ( resetPaging ) {
				provider.sObj.offset = provider.offset = 0;
			}

			// set the content to loading while we do the search:
			$j( '#tab-' + providerName ).html( mv_get_loading_img() );

			// Make sure the search library is loaded and issue the search request
			this.getLibSearchResults( provider );
		}
	},

	// Issue a api request & cache the result
	// this check can be avoided by setting the 
	// this.import_url_mode = 'api' | 'form' | instead of 'autodetect' or 'none'
	checkForCopyURLSupport: function ( callback ) {
		var _this = this;
		js_log( 'checkForCopyURLSupport:: ' );

		// See if we already have the import mode:
		if ( this.import_url_mode != 'autodetect' ) {
			js_log( 'import mode: ' + _this.import_url_mode );
			callback();
		}
		// If we don't have the local wiki api defined we can't auto-detect use "link"
		if ( ! _this.upload_api_target ) {
			js_log( 'import mode: remote link (no import_wiki_api_url)' );
			_this.import_url_mode = 'remote_link';
			callback();
		}
		if ( this.import_url_mode == 'autodetect' ) {
			do_api_req( 
				{
					'url': _this.upload_api_target,
					'data': {
						'action': 'paraminfo',
						'modules': 'upload'
					}
				}, function( data ) {
					// jump right into api checks:
					for ( var i in data.paraminfo.modules[0].parameters ) {
						var pname = data.paraminfo.modules[0].parameters[i].name;
						if ( pname == 'url' ) {
							js_log( 'Autodetect Upload Mode: api: copy by url:: ' );
							// check permission  too:
							_this.checkForCopyURLPermission( function( canCopyUrl ) {
								if ( canCopyUrl ) {
									_this.import_url_mode = 'api';
									js_log( 'import mode: ' + _this.import_url_mode );
									callback();
								} else {
									_this.import_url_mode = 'none';
									js_log( 'import mode: ' + _this.import_url_mode );
									callback();
								}
							} );
							break;
						}
					}
				}
			);
		}
	},

	/*
	 * checkForCopyURLPermission:
	 * not really necessary the api request to upload will return appropriate error 
	 * if the user lacks permission. or $wgAllowCopyUploads is set to false
	 * (use this function if we want to issue a warning up front)
	 */
	checkForCopyURLPermission: function( callback ) {
		var _this = this;
		// do api check:
		do_api_req( 
			{
				'data': { 'action' : 'query', 'meta' : 'userinfo', 'uiprop' : 'rights' },
				'url': _this.upload_api_target,
				'userinfo' : true
			}, function( data ) {
				for ( var i in data.query.userinfo.rights ) {
					var right = data.query.userinfo.rights[i];
					// js_log('checking: ' + right ) ;
					if ( right == 'upload_by_url' ) {
						callback( true );
						return true; // break out of the function
					}
				}
				callback( false );
			} 
		);
	},

	getLibSearchResults: function( provider ) {
		var _this = this;

		// first check if we should even run the search at all (can we import / insert 
		// into the page? )
		if ( !this.isProviderLocal( provider ) && this.import_url_mode == 'autodetect' ) {
			// provider is not local check if we can support the import mode:
			this.checkForCopyURLSupport( function() {
				_this.getLibSearchResults( provider );
			} );
			return false;
		} else if ( !this.isProviderLocal( provider ) && this.import_url_mode == 'none' ) {
			if (  this.currentProvider == 'combined' ) {
				// combined results are harder to error handle just ignore that repo
				provider.sObj.loading = false;
			} else {
				$j( '#tab-' + this.currentProvider ).html( 
					'<div style="padding:10px">' + 
					gM( 'mwe-no_import_by_url' ) + 
					'</div>' );
			}
			return false;
		}
		_this.loadSearchLib( provider, function() {
			// Do the search
			provider.sObj.getSearchResults();
			_this.waitForResults( function() {
				this.showResults();
			} );
		} );
	},

	loadSearchLib: function( provider, callback ) {
		var _this = this;
		// set up the library req:
		mvJsLoader.doLoad( [
			'baseRemoteSearch',
			provider.lib + 'Search'
		], function() {
			js_log( "loaded lib:: " + provider.lib );
			// else we need to run the search:
			var options = {
				'provider': provider,
				'rsd': _this
			};
			eval( 'provider.sObj = new ' + provider.lib + 'Search( options );' );
			if ( !provider.sObj ) {
				js_log( 'Error: could not find search lib for ' + cp_id );
				return false;
			}

			// inherit defaults if not set:
			provider.limit = provider.limit ? provider.limit : provider.sObj.limit;
			provider.offset = provider.offset ? provider.offset : provider.sObj.offset;
			callback();
		} );
	},

	/* check for all the results to finish */
	waitForResults: function( callback ) {
		// js_log('rsd:waitForResults');
		var _this = this;
		var loading_done = true;

		for ( var cp_id in this.content_providers ) {
			var cp = this.content_providers[ cp_id ];
			if ( typeof cp['sObj'] != 'undefined' ) {
				if ( cp.sObj.loading )
					loading_done = false;
			}
		}
		if ( loading_done ) {
			callback();
		} else {
			setTimeout( 
				function() {
					_this.waitForResults( callback );
				}, 
				50 
			);
		}
	},

	createTabs: function() {
		var _this = this;
		// add the tabs to the rsd_results container:
		var s = '<div id="rsd_tabs_container" style="width:100%;">';
		var selected_tab = 0;
		var index = 0;
		s += '<ul>';
		var content = '';
		for ( var providerName in this.content_providers ) {
			var provider = this.content_providers[providerName];
			var tabImage = mv_embed_path + '/skins/common/remote_cp/' + providerName + '_tab.png';
			if ( provider.enabled && provider.checked && provider.api_url ) {
				// add selected default if set
				if ( this.currentProvider == providerName )
					selected_tab = index;

				s += '<li class="rsd_cp_tab">';
				s += '<a id="rsd_tab_' + providerName + '" href="#tab-' + providerName + '">';
				if ( provider.tab_img === true ) {
					s += '<img alt="' + gM( 'rsd-' + providerName + '-title' ) + '" ' + 
						'src="' + tabImage + '">';
				} else {
					s += gM( 'rsd-' + providerName + '-title' );
				}
				s += '</a>';
				s += '</li>';
				index++;
			}
			content += '<div id="tab-' + providerName + '" class="rsd_results"/>';
		}
		// Do an upload tab if enabled:
		if ( this.content_providers['upload'].enabled ) {
			s += '<li class="rsd_cp_tab" >' + 
				'<a id="rsd_tab_upload" href="#tab-upload">' + 
				gM( 'mwe-upload_tab' ) + 
				'</a></li>';
			content += '<div id="tab-upload" />';
			if ( this.currentProvider == 'upload' )
				selected_tab = index++;
		}
		s += '</ul>';
		// Output the tab content containers:
		s += content;
		s += '</div>'; // close tab container

		// Output the respective results holders
		$j( '#rsd_results_container' ).html( s );
		// Setup bindings for tabs make them sortable: (@@todo remember order)
		js_log( 'selected tab is: ' + selected_tab );
		$j( "#rsd_tabs_container" )
			.tabs( {
				selected: selected_tab,
				select: function( event, ui ) {
					_this.selectTab( $j( ui.tab ).attr( 'id' ).replace( 'rsd_tab_', '' ) );
				}
			})
			// Add sorting
			.find( ".ui-tabs-nav" ).sortable( { axis: 'x' } );
		// @@todo store sorted repo
	},

	// Resource title
	getResourceFromTitle: function( title, callback ) {
		var _this = this;
		reqObj = {
			'action': 'query',
			'titles': _this.cFileNS + ':' + title
		};
		do_api_req( {
			'data': reqObj,
			'url': this.local_wiki_api_url
			}, function( data ) {
				// @@todo propagate the resource
				var resource = {};
			}
		);
	},

	// @@todo we could load the id with the content provider id to find the object faster...
	getResourceFromId: function( id ) {
		var parts = id.replace( /^res_/, '' ).split( '__' );
		var providerName = parts[0];
		var resIndex = parts[1];

		// Set the upload helper providerName (to render recent uploads by this user)
		if ( providerName == 'upload' )
			providerName = 'this_wiki';

		var provider = this.content_providers[providerName];
		if ( provider && provider['sObj'] && provider.sObj.resultsObj[resIndex] ) {
			return provider.sObj.resultsObj[resIndex];
		}
		js_log( "ERROR: could not find " + resIndex );
		return false;
	},

	showResults: function() {
		js_log( 'f:showResults::' + this.currentProvider );
		var _this = this;
		var o = '';
		var tabSelector = '';

		if ( this.currentProvider == 'upload' ) {
			tabSelector = '#upload_bin';
			var provider = _this.content_providers['this_wiki'];
		} else {
			var provider = this.content_providers[this.currentProvider];
			tabSelector = '#tab-' + this.currentProvider;
			// Output the results bar / controls
		}
		// Empty the existing results:
		$j( tabSelector ).empty();
		// @@todo give the user upload control love
		if ( this.currentProvider != 'upload' ) {
			_this.showResultsHeader();
		}

		var numResults = 0;

		// Output all the results for the current currentProvider
		if ( typeof provider['sObj'] != 'undefined' ) {
			$j.each( provider.sObj.resultsObj, function( resIndex, resource ) {
				o += _this.getResultHtml( provider, resIndex, resource );
				numResults++;
			} );
			js_log( 'append to: ' + '#tab-' + cp_id );
			// Put in the tab output (plus clear the output)
			$j( tabSelector ).append( o + '<div style="clear:both"/>' );
		}

		js_log( 'did numResults :: ' + numResults + 
			' append: ' + $j( '#rsd_q' ).val() );

		// Remove any old search res
		$j( '#rsd_no_search_res' ).remove();
		if ( numResults == 0 ) {
			$j( '#tab-' + cp_id ).append( 
				'<span style="padding:10px">' + 
				gM( 'rsd_no_results', $j( '#rsd_q' ).val() ) + 
				'</span>' );
		}
		this.addResultBindings();
	},

	getResultHtml: function( provider, resIndex, resource ) {
		var o = '';
		if ( this.displayMode == 'box' ) {
			o += '<div id="mv_result_' + resIndex + '" ' + 
				'class="mv_clip_box_result" ' + 
				'style="' + 
					'width:' + this.thumb_width + 'px;' + 
					'height:' + ( this.thumb_width - 20 ) + 'px;' + 
					'position:relative;">';
			// Check for missing poster types for audio
			if ( resource.mime == 'audio/ogg' && !resource.poster ) {
				resource.poster = mv_skin_img_path + 'sound_music_icon-80.png';
			}
			// Get a thumb with proper resolution transform if possible:
			var thumbUrl = provider.sObj.getImageTransform( resource, 
				{ 'width' : this.thumb_width } );

			o += '<img title="' + resource.title + '" ' +
				'class="rsd_res_item" id="res_' + cp_id + '__' + resIndex + '" ' +
				'style="width:' + this.thumb_width + 'px;" ' + 
				'src="' + thumbUrl + '">';
			// Add a linkback to resource page in upper right:
			if ( resource.link ) {
				o += '<div class="' + 
						'rsd_linkback ui-corner-all ui-state-default ui-widget-content" >' +
					'<a target="_new" title="' + gM( 'mwe-resource_description_page' ) +
					'" href="' + resource.link + '">' + gM( 'mwe-link' ) + '</a>' +
					'</div>';
			}

			// Add file type icon if known
			if ( resource.mime ) {
				o += this.getTypeIcon( resource.mime );
			}

			// Add license icons if present
			if ( resource.license )
				o += this.getLicenseIconHtml( resource.license );

			o += '</div>';
		} else if ( this.displayMode == 'list' ) {
			o += '<div id="mv_result_' + resIndex + '" class="mv_clip_list_result" style="width:90%">';
			o += 
				'<img ' + 
					'title="' + resource.title + '" ' + 
					'class="rsd_res_item" id="res_' + cp_id + '__' + resIndex + '" ' + 
					'style="float:left;width:' + this.thumb_width + 'px;" ' +
					'src="' + provider.sObj.getImageTransform( resource, { 'width': this.thumb_width } ) + '">';
			// Add license icons if present
			if ( resource.license )
				o += this.getLicenseIconHtml( resource.license );

			o += resource.desc ;
			o += '<div style="clear:both" />';
			o += '</div>';
		}
		return o;
	}

	addResultBindings: function() {
		var _this = this;
		$j( '.mv_clip_' + _this.displayMode + '_result' ).hover( 
			function() {
				$j( this ).addClass( 'mv_clip_' + _this.displayMode + '_result_over' );
				// Also set the animated image if available
				var res_id = $j( this ).children( '.rsd_res_item' ).attr( 'id' );
				var resource = _this.getResourceFromId( res_id );
				if ( resource.poster_ani )
					$j( '#' + res_id ).attr( 'src', resource.poster_ani );
			}, function() {
				$j( this ).removeClass( 
					'mv_clip_' + _this.displayMode + '_result_over' );
				var res_id = $j( this ).children( '.rsd_res_item' ).attr( 'id' );
				var resource = _this.getResourceFromId( res_id );
				// Restore the original (non animated)
				if ( resource.poster_ani )
					$j( '#' + res_id ).attr( 'src', resource.poster );
			} 
		);
		// Resource click action: (bring up the resource editor)
		$j( '.rsd_res_item' ).unbind().click( function() {
			var resource = _this.getResourceFromId( $j( this ).attr( "id" ) );
			_this.showResourceEditor( resource, this );
		} );
	},

	addResourceEditLoader: function( maxWidth, overflowStyle ) {
		var _this = this;
		if ( !maxWidth ) maxWidth = 400;
		if ( !overflowStyle ) overflowStyle = 'overflow:auto;';
		// Remove any old instance:
		$j( _this.target_container ).find( '#rsd_resource_edit' ).remove();

		// Hide the results container
		$j( '#rsd_results_container' ).hide();

		var pt = $j( _this.target_container ).html();
		// Add the edit layout window with loading place holders
		$j( _this.target_container ).append( 
			'<div id="rsd_resource_edit" ' +
				'style="position:absolute;top:0px;left:0px;' + 
					'bottom:0px;right:4px;background-color:#FFF;"> ' +
			'<div id="clip_edit_ctrl" ' + 
				'class="ui-widget ui-widget-content ui-corner-all" ' + 
				'style="position:absolute;left:2px;top:5px;bottom:10px;' + 
				'width:' + ( maxWidth + 5 ) + 'px;overflow:auto;padding:5px;" >' +
			'</div>' +
			'<div id="clip_edit_disp" ' +
				'class="ui-widget ui-widget-content ui-corner-all"' +
				'style="position:absolute;' + overflowStyle + ';' + 
				'left:' + ( maxWidth + 20 ) + 'px;right:0px;top:5px;bottom:10px;' + 
				'padding:5px;" >' +
			mv_get_loading_img( 'position:absolute;top:30px;left:30px' ) +
			'</div>' +
			'</div>' );
	},

	getEditWidth: function( resource ) {
		var mediaType = this.getMediaType( resource );
		if ( mediaType == 'image' ) {
			return resource.image_edit_width;
		} else {
			return resource.video_edit_width;
		}
	},

	getMediaType: function( resource ) {
		if ( resource.mime.indexOf( 'image' ) != -1 ) {
			return 'image';
		} else if ( resource.mime.indexOf( 'audio' ) != -1 ) {
			return 'audio';
		} else {
			return 'video';
		}
	},

	removeResourceEditor: function() {
		$j( '#rsd_resource_edit' ).remove();
		$j( '#rsd_resource_edit' ).css( 'opacity', 0 );
		$j( '#rsd_edit_img' ).remove();
	}

	showResourceEditor: function( resource, rsdElement ) {
		js_log( 'f:showResourceEditor:' + resource.title );
		var _this = this;

		// Remove any existing resource edit interface
		_this.removeResourceEditor();

		var mediaType = _this.getMediaType( resource );
		var maxWidth = _this.getEditWidth( resource );

		// So that transcripts show on top
		var overflow_style = ( mediaType == 'video' ) ? '' : 'overflow:auto;';
		// Append to the top level of model window:
		_this.addResourceEditLoader( maxWidth, overflow_style );
		// update add media wizard title:
		var dialogTitle = gM( 'mwe-add_media_wizard' ) + ': ' + 
			gM( 'rsd_resource_edit', resource.title );
		$j( _this.target_container ).dialog( 'option', 'title', dialogTitle );
		js_log( 'did append to: ' + _this.target_container );

		// Left side holds the image right size the controls /
		$j( rsdElement )
			.clone()
			.attr( 'id', 'rsd_edit_img' )
			.appendTo( '#clip_edit_disp' )
			.css( {
				'position':'absolute',
				'top':'40%',
				'left':'20%',
				'cursor':'default',
				'opacity':0
			} );

		// Try and keep aspect ratio for the thumbnail that we clicked:
		var tRatio = $j( rsdElement ).height() / $j( rsdElement ).width();

		if ( !tRatio ) {
			var tRatio = 1; // set ratio to 1 if tRatio did not work.
		}
		js_log( 'Set from ' +  tRatio + ' to init thumbimage to ' + 
			maxWidth + ' x ' + parseInt( tRatio * maxWidth ) );
		// Scale up image and to swap with high res version
		$j( '#rsd_edit_img' ).animate( 
			{
				'opacity': 1,
				'top': '5px',
				'left': '5px',
				'width': maxWidth + 'px',
				'height': parseInt( tRatio * maxWidth )  + 'px'
			}, 
			"slow" ); // Do it slow to give it a chance to finish loading the high quality version

		if ( mediaType == 'image' ) {
			_this.loadHighQualityImage( 
				resource, 
				{ 'width': maxWidth }, 
				'rsd_edit_img', 
				function() {
					$j( '.mv_loading_img' ).remove();
				}
			);
		}
		// Also fade in the container:
		$j( '#rsd_resource_edit' ).animate( {
			'opacity': 1,
			'background-color': '#FFF',
			'z-index': 99
		} );

		// Show the editor itself
		if ( mediaType == 'image' ) {
			_this.showImageEditor( resource );
		} else if ( mediaType == 'video' || mediaType == 'audio' ) {
			_this.showVideoEditor( resource );
		}
	},

	loadHighQualityImage: function( resource, size, target_img_id, callback ) {
		// Get the high quality image url:
		resource.pSobj.getImageObj( resource, size, function( imObj ) {
			resource['edit_url'] = imObj.url;

			js_log( "edit url: " + resource.edit_url );
			// Update the resource
			resource['width'] = imObj.width;
			resource['height'] = imObj.height;

			// See if we need to animate some transition
			if ( size.width != imObj.width ) {
				js_log( 'loadHighQualityImage:size mismatch: ' + size.width + ' != ' + imObj.width );
				// Set the target id to the new size:
				$j( '#' + target_img_id ).animate( {
					'width': imObj.width + 'px',
					'height': imObj.height + 'px'
				});
			} else {
				js_log( 'using req size: ' + imObj.width + 'x' + imObj.height );
				$j( '#' + target_img_id ).animate( {
					'width': imObj.width + 'px', 
					'height': imObj.height + 'px' 
				});
			}
			// Don't swap it in until its loaded:
			var img = new Image();
			// Load the image image:
			$j( img ).load( function () {
					 $j( '#' + target_img_id ).attr( 'src', resource.edit_url );
					 // Let the caller know we are done and what size we ended up with:
					 callback();
				} ).error( function () {
					js_log( "Error with:  " +  resource.edit_url );
				} ).attr( 'src', resource.edit_url );
		} );
	},

	onCancelClipEdit: function() {
		var _this = this;
		js_log( 'onCancelClipEdit' );
		var b_target = _this.target_container + '~ .ui-dialog-buttonpane';
		$j( '#rsd_resource_edit' ).remove();
		// Remove preview if its 'on'
		$j( '#rsd_preview_display' ).remove();
		// Restore the resource container:
		$j( '#rsd_results_container' ).show();

		// Restore the title:
		$j( _this.target_container ).dialog( 'option', 'title', gM( 'mwe-add_media_wizard' ) );
		js_log( "should update: " + b_target + ' with: cancel' );
		// Restore the buttons:
		$j( b_target )
			.html( $j.btnHtml( gM( 'mwe-cancel' ) , 'mv_cancel_rsd', 'close' ) )
			.children( '.mv_cancel_rsd' )
			.btnBind()
			.click( function() {
				$j( _this.target_container ).dialog( 'close' );
			} );
	},

	/** 
	 *  Get the control actions for clipEdit with relevant callbacks
	 */
	getClipEditControlActions: function( provider ) {
		var _this = this;
		var actions = { };

		actions['insert'] = function( resource ) {
			_this.insertResource( resource );
		}
		// If not directly inserting the resource is support a preview option:
		if ( _this.import_url_mode != 'remote_link' ) {
			actions['preview'] = function( resource ) {
				_this.showPreview( resource )
			};
		}
		actions['cancel'] = function() {
			_this.onCancelClipEdit()
		}
		return actions;
	},

	getClipEditOptions: function( resource ) {
		return {
			'rObj': resource,
			'parent_ct': 'rsd_modal_target',
			'clip_disp_ct': 'clip_edit_disp',
			'control_ct': 'clip_edit_ctrl',
			'media_type': this.getMediaType( resource ),
			'p_rsdObj': this,
			'controlActionsCb': this.getClipEditControlActions( resource.pSobj.cp ),
			'enabled_tools': this.enabled_tools
		};
	},

	/**
	 * Internal function called by showResourceEditor() to show an image editor
	 */
	showImageEditor: function( resource ) {
		var _this = this;
		var options = _this.getClipEditOptions( resource );
		// Display the mvClipEdit obj once we are done loading:
		mvJsLoader.doLoad( clibs, function() {
			// Run the image clip tools
			_this.clipEdit = new mvClipEdit( options );
		} );
	},

	/**
	 * Internal function called by showResourceEditor() to show a video or audio
	 * editor.
	 */
	showVideoEditor: function( resource ) {
		var _this = this;
		var options = _this.getClipEditOptions( resource );
		var mediaType = this.getMediaType( resource );

		js_log( 'media type:: ' + mediaType );
		// Get any additional embedding helper meta data prior to doing the actual embed
		// normally this meta should be provided in the search result 
		// (but archive.org has another query for more media meta)
		resource.pSobj.addResourceInfoCallback( resource, function() {
			// Make sure we have the embedVideo libs:
			var runFlag = false;
			mvJsLoader.embedVideoCheck( function() {
				// Strange concurrency issue with callbacks
				// @@todo try and figure out why this callback is fired twice
				if ( !runFlag ) {
					runFlag = true;
				} else {
					js_log( 'Error: embedVideoCheck run twice' );
					return false;
				}
				var embedHtml = resource.pSobj.getEmbedHTML( resource, 
					{ id : 'embed_vid' } );
				js_log( 'append html: ' + embedHtml );
				$j( '#clip_edit_disp' ).html( embedHtml );
				js_log( "about to call rewrite_by_id::embed_vid" );
				// Rewrite by id
				rewrite_by_id( 'embed_vid', function() {
					// Grab information avaliable from the embed instance
					resource.pSobj.addResourceInfoFromEmbedInstance( resource, 'embed_vid' );

					// Add the re-sizable to the doLoad request:
					clibs.push( '$j.ui.resizable' );
					clibs.push( '$j.fn.hoverIntent' );
					mvJsLoader.doLoad( clibs, function() {
						// Make sure the rsd_edit_img is removed:
						$j( '#rsd_edit_img' ).remove();
						// Run the image clip tools
						_this.clipEdit = new mvClipEdit( options );
					} );
				} );
			} );
		} );
	},

	isProviderLocal: function( provider ) {
		if ( provider.local ) {
			return true;
		} else {
			// Check if we can embed the content locally per a domain name check:
			var localHost = mw.parseUri( this.local_wiki_api_url ).host;
			if ( provider.local_domains ) {
				for ( var i = 0; i < provider.local_domains.length; i++ ) {
					var domain = provider.local_domains[i];
					if ( localHost.indexOf( domain ) != -1 )
						return true;
				}
			}
			return false;
		}
	},

	/**
	 * Check if the file is either a local upload, or if it has already been 
	 * imported under the standard filename scheme. 
	 *
	 * Calls the callback with two parameters:
	 *     callback( resource, status )
	 *
	 * resource: a resource object pointing to the local file if there is one,
	 *    or false if not
	 *
	 * status: may be 'local', 'shared', 'imported' or 'missing'
	 */
	isFileLocallyAvailable: function( resource, callback ) {
		var _this = this;
		// Add a loader on top
		$j.addLoaderDialog( gM( 'mwe-checking-resource' ) );

		// Extend the callback, closing the loader dialog before chaining
		myCallback = function( newRes, status ) {
			$j.closeLoaderDialog();
			if ( typeof callback == 'function' ) {
				callback( newRes, status );
			}
		}

		// @@todo get the localized File/Image namespace name or do a general {NS}:Title
		var provider = resource.pSobj.cp;
		var _this = this;

		// Clone the resource
		var proto = {};
		proto.prototype = resource;
		var myRes = new proto;

		// Update base target_resource_title:
		myRes.target_resource_title = myRes.titleKey.replace( /^(File:|Image:)/ , '' )

		// check if local repository
		// or if import mode if just "linking" (we should already have the 'url'

		if ( this.isProviderLocal( provider ) || this.import_url_mode == 'remote_link' ) {
			// Local repo, jump directly to the callback:
			myCallback( myRes, 'local' );
		} else {
			// Check if the file is local (can be shared repo)
			if ( provider.check_shared ) {
				_this.findFileInLocalWiki( myRes.target_resource_title, function( imagePage ) {
					if ( imagePage && imagePage['imagerepository'] == 'shared' ) {
						myCallback( myRes, 'shared' );
					} else {
						_this.isFileAlreadyImported( myRes, myCallback );
					}
				} );
			} else {
				_this.isFileAlreadyImported( myRes, myCallback );
			}
		}
	},

	/**
	 * Check if the file is already imported with this extension's filename scheme
	 *
	 * Calls the callback with two parameters:
	 *     callback( resource, status )
	 *
	 * If the image is found, the status will be 'imported' and the resource
	 * will be the new local resource.
	 *
	 * If the image is not found, the status  will be 'missing' and the resource 
	 * will be false.
	 */
	isFileAlreadyImported: function( resource, callback ) {
		js_log( '::isFileAlreadyImported:: ' );
		var _this = this;

		// Clone the resource
		var proto = {};
		proto.prototype = resource;
		var myRes = new proto;

		var provider = myRes.pSobj.cp;

		// update target_resource_title with resource repository prefix:
		myRes.target_resource_title = provider.resource_prefix + myRes.target_resource_title;
		// check if the file exists:
		_this.findFileInLocalWiki( myRes.target_resource_title, function( imagePage ) {
			if ( imagePage ) {
				// update to local src
				myRes.local_src = imagePage['imageinfo'][0].url;
				// @@todo maybe  update poster too?
				myRes.local_poster = imagePage['imageinfo'][0].thumburl;
				// update the title:
				myRes.target_resource_title = imagePage.title.replace(/^(File:|Image:)/ , '' );
				callback( myRes, 'imported' );
			} else {
				callback( false, 'missing' );
			}
		} );
	},

	showImportUI: function( resource, callback ) {
		var _this = this;
		js_log( "showImportUI:: update:" + _this.cFileNS + ':' + 
			resource.target_resource_title );

		// setup the resource description from resource description:
		// FIXME: i18n, namespace
		var desc = '{{Information ' + "\n";

		if ( resource.desc ) {
			desc += '|Description= ' + resource.desc + "\n";
		} else {
			desc += '|Description= ' + gM( 'mwe-missing_desc_see_source', resource.link ) + "\n";
		}

		// output search specific info
		desc += '|Source=' + resource.pSobj.getImportResourceDescWiki( resource ) + "\n";

		if ( resource.author )
			desc += '|Author=' + resource.author + "\n";

		if ( resource.date )
			desc += '|Date=' + resource.date + "\n";

		// add the Permision info:
		desc += '|Permission=' + resource.pSobj.getPermissionWikiTag( resource ) + "\n";

		if ( resource.other_versions )
			desc += '|other_versions=' + resource.other_versions + "\n";

		desc += '}}';

		// get any extra categories or helpful links
		desc += resource.pSobj.getExtraResourceDescWiki( resource );


		$j( '#rsd_resource_import' ).remove();// remove any old resource imports

		// @@ show user dialog to import the resource
		$j( _this.target_container ).append( 
			'<div id="rsd_resource_import" ' +
				'class="ui-widget-content" ' +
				'style="position:absolute;top:0px;left:0px;right:0px;bottom:0px;z-index:5">' +
			'<h3 style="color:red;padding:5px;">' + 
			gM( 'mwe-resource-needs-import', [resource.title, _this.upload_api_name] ) + 
			'</h3>' +
			'<div id="rsd_preview_import_container" ' + 
				'style="position:absolute;width:50%;bottom:0px;left:5px;' + 
					'overflow:auto;top:30px;">' +
			resource.pSobj.getEmbedHTML( resource, {
				'id': _this.target_container + '_rsd_pv_vid',
				'max_height': '220',
				'only_poster': true
			} ) + // get embedHTML with small thumb:
			'<br style="clear both"/>' +
			'<strong>' + gM( 'mwe-resource_page_desc' ) + '</strong>' +
			'<div id="rsd_import_desc" style="display:inline;">' +
			mv_get_loading_img( 'position:absolute;top:5px;left:5px' ) +
			'</div>' +
			'</div>' +
			'<div id="rds_edit_import_container" ' + 
				'style="position:absolute; ' + 
				'left:50%;bottom:0px;top:30px;right:0px;overflow:auto;">' +
			'<strong>' + gM( 'mwe-local_resource_title' ) + '</strong>' + 
			// FIXME: invalid HTML, <br> must be empty
			'<br/>' +
			'<input type="text" size="30" value="' + resource.target_resource_title + '" />' + 
			'<br/>' +
			'<strong>' + gM( 'mwe-edit_resource_desc' ) + '</strong>' +
			// FIXME: invalid HTML, two id attributes
			'<textarea id="rsd_import_ta" id="mv_img_desc" ' + 
				'style="width:90%;" rows="8" cols="50">' +
			desc +
			'</textarea>' + 
			'<br/>' +
			'<input type="checkbox" value="true" id="wpWatchthis" ' + 
				'name="wpWatchthis" tabindex="7" />' +
			'<label for="wpWatchthis">' + gM( 'mwe-watch_this_page' ) + '</label> ' + 
			'<br/><br/><br/>' +
			$j.btnHtml( gM( 'mwe-update_preview' ), 'rsd_import_apreview', 'refresh' ) + 
			' ' +
			'</div>' +
			// output the rendered and non-rendered version of description for easy switching:
			'</div>' );
		var buttonPaneSelector = _this.target_container + '~ .ui-dialog-buttonpane';
		$j( buttonPaneSelector ).html (
			// add the btns to the bottom:
			$j.btnHtml( gM( 'mwe-do_import_resource' ), 'rsd_import_doimport', 'check' ) + 
			' ' +
			$j.btnHtml( gM( 'mwe-cancel_import' ), 'rsd_import_acancel', 'close' ) + ' '
		);

		// add hover:

		// update video tag (if a video)
		if ( resource.mime.indexOf( 'video/' ) !== -1 )
			rewrite_by_id( $j( _this.target_container ).attr( 'id' ) + '_rsd_pv_vid' );

		// load the preview text:
		_this.parse(
			desc, _this.cFileNS + ':' + resource.target_resource_title, 
			function( descHtml ) {
				$j( '#rsd_import_desc' ).html( descHtml );
			} 
		);
		// add bindings:
		$j( _this.target_container + ' .rsd_import_apreview' )
			.btnBind()
			.click( function() {
				js_log( " Do preview asset update" );
				$j( '#rsd_import_desc' ).html( mv_get_loading_img() );
				// load the preview text:
				_this.parse( 
					$j( '#rsd_import_ta' ).val(), 
					_this.cFileNS + ':' + resource.target_resource_title, 
					function( o ) {
						js_log( 'got updated preview: ' );
						$j( '#rsd_import_desc' ).html( o );
					} 
				);
			} );

		$j( buttonPaneSelector + ' .rsd_import_doimport' )
			.btnBind()
			.click( function() {
				js_log( "do import asset:" + _this.import_url_mode );
				// check import mode:
				if ( _this.import_url_mode == 'api' ) {
					if ( _this.upload_api_target == 'proxy' ) {
						_this.setupProxy( function() {
							_this.doApiImport( resource, callback );
						} );
					} else {
						_this.doApiImport( resource, callback );
					}
				} else {
					js_log( "Error: import mode is not form or API (can not copy asset)" );
				}
			} );
		$j( buttonPaneSelector + ' .rsd_import_acancel' )
			.btnBind()
			.click( function() {
				$j( '#rsd_resource_import' ).fadeOut( "fast", function() {
					$j( this ).remove();
					// restore buttons (from the clipEdit object::)
					_this.clipEdit.updateInsertControlActions();
					$j( buttonPaneSelector ).removeClass( 'ui-state-error' );
				} );
			} );
	},

	/**
	 * Sets up the proxy for the remote inserts
	 */
	setupProxy: function( callback ) {
		var _this = this;

		if ( _this.proxySetupDone ) {
			if ( callback )
				callback();
			return;
		}
		// setup the the proxy via  $j.apiProxy loader:
		if ( !_this.upload_api_proxy_frame ) {
			js_log( "Error:: remote api but no proxy frame target" );
			return false;
		} else {
			$j.apiProxy(
				'client',
				{
					'server_frame': _this.upload_api_proxy_frame
				}, function() {
					_this.proxySetupDone = true
					if ( callback )
						callback();
				}
			);
		}
	},

	findFileInLocalWiki: function( fName, callback ) {
		js_log( "findFileInLocalWiki::" + fName );
		var _this = this;
		reqObj = {
			'action': 'query',
			'titles': _this.cFileNS + ':' + fName,
			'prop': 'imageinfo',
			'iiprop': 'url',
			'iiurlwidth': '400'
		};
		// first check the api for imagerepository
		do_api_req( 
			{
				'data': reqObj,
				'url': this.local_wiki_api_url
			}, function( data ) {
				if ( data.query.pages ) {
					for ( var i in data.query.pages ) {
						for ( var j in data.query.pages[i] ) {
							if ( j == 'missing' 
								&& data.query.pages[i].imagerepository != 'shared' ) 
							{
								js_log( fName + " not found" );
								callback( false );
								return;
							}
						}
						// else page is found:
						js_log( fName + "  found" );
						callback( data.query.pages[i] );
					}
				}
			}
		);
	},

	doApiImport: function( resource, callback ) {
		var _this = this;
		js_log( ":doApiImport:" );
		$j.addLoaderDialog( gM( 'mwe-importing_asset' ) );
		// baseUploadInterface
		mvJsLoader.doLoad( 
			[
				'mvBaseUploadInterface',
				'$j.ui.progressbar'
			], 
			function() {
				js_log( 'mvBaseUploadInterface ready' );
				// initiate a upload object ( similar to url copy ):
				var uploader = new mvBaseUploadInterface( {
					'api_url' : _this.upload_api_target,
					'done_upload_cb':function() {
						js_log( 'doApiImport:: run callback::' );
						// we have finished the upload:

						// close up the rsd_resource_import
						$j( '#rsd_resource_import' ).remove();
						// return the parent callback:
						return callback();
					}
				} );
				// get the edit token if we have it handy
				_this.getEditToken( function( token ) {
					uploader.editToken = token;

					// close the loader now that we are ready to present the progress dialog::
					$j.closeLoaderDialog();

					uploader.doHttpUpload( {
						'url': resource.src,
						'filename': resource.target_resource_title,
						'comment': $j( '#rsd_import_ta' ).val()
					} );
				} )
			}
		);
	},

	getEditToken: function( callback ) {
		var _this = this;
		if ( _this.upload_api_target != 'proxy' ) {
			// (if not a proxy) first try to get the token from the page:
			var editToken = $j( "input[name='wpEditToken']" ).val();
			if ( editToken ) {
				callback( editToken );
				return;
			}
		}
		// @@todo try to load over ajax if( _this.local_wiki_api_url ) is set
		// (your on the api domain but are inserting from a normal page view)
		get_mw_token( null, _this.upload_api_target, function( token ) {
			callback( token );
		} );
	},

	showPreview: function( resource ) {
		var _this = this;
		this.isFileLocallyAvailable( resource, function( newRes, status ) {
			if ( status === 'missing' ) {
				_this.showImportUI( resource, callback );
				return;
			}

			// put another window ontop:
			$j( _this.target_container ).append( 
				'<div id="rsd_preview_display"' +
					'style="position:absolute;overflow:hidden;z-index:4;' + 
					'top:0px;bottom:0px;right:0px;left:0px;background-color:#FFF;">' +
				mv_get_loading_img( 'top:30px;left:30px' ) +
				'</div>' );

			var buttonPaneSelector = _this.target_container + '~ .ui-dialog-buttonpane';
			var origTitle = $j( _this.target_container ).dialog( 'option', 'title' );

			// update title:
			$j( _this.target_container ).dialog( 'option', 'title', 
				gM( 'mwe-preview_insert_resource', newRes.title ) );

			// update buttons preview:
			$j( buttonPaneSelector )
				.html(
					$j.btnHtml( gM( 'rsd_do_insert' ), 'preview_do_insert', 'check' ) + ' ' )
				.children( '.preview_do_insert' )
				.click( function() {
					_this.insertResource( newRes );
				} );
			// update cancel button
			$j( buttonPaneSelector )
				.append( '<a href="#" class="preview_close">Do More Modification</a>' )
				.children( '.preview_close' )
				.click( function() {
					$j( '#rsd_preview_display' ).remove();
					// restore title:
					$j( _this.target_container ).dialog( 'option', 'title', origTitle );
					// restore buttons (from the clipEdit object::)
					_this.clipEdit.updateInsertControlActions();
				} );

			// Get the preview wikitext
			_this.parse( 
				_this.getPreviewText( newRes ),
				_this.target_title,
				function( phtml ) {
					$j( '#rsd_preview_display' ).html( phtml );
					// update the display of video tag items (if any)
					mwdomReady( true );
				}
			);
		} );
	},

	getEmbedCode: function( resource ) {
		if ( this.import_url_mode == 'remote_link' ) {
			return resource.pSobj.getEmbedHTML( resource );
		} else {
			return resource.pSobj.getEmbedWikiCode( resource );
		}
	},

	getPreviewText: function( resource ) {
		var _this = this;
		var text;

		// insert at start if textInput cursor has not been set (ie == length)
		var insertPos = _this.getCaretPos();
		var originalText = _this.getTextboxValue();
		var embedCode = _this.getEmbedCode( resource );
		if ( insertPos !== false && originalText ) {
			if ( originalText.length == insertPos ) {
				insertPos = 0;
			}
			text = originalText.substring( 0, insertPos ) +
				embedCode + originalText.substring( insertPos );
		} else {
			text = $j( _this.target_textbox ).val() + embedCode;
		}
		// check for missing </references>
		if ( text.indexOf( '<references/>' ) == -1 && text.indexOf( '<ref>' ) != -1 ) {
			text = text + '<references/>';
		}
		return text;
	},

	parse: function( wikitext, title, callback ) {
		do_api_req( 
			{
				'data': {
					'action': 'parse',
					'text': wikitext
				},
				'url': this.local_wiki_api_url
			}, function( data ) {
				callback( data.parse.text['*'] );
			}
		);
	},

	insertResource: function( resource ) {
		js_log( 'insertResource: ' + resource.title );

		var _this = this;
		// double check that the resource is present:
		this.isFileLocallyAvailable( resource, function( newRes, status ) {
			if ( status === 'missing' ) {
				_this.showImportUI( resource, callback );
				return;
			}
			
			$j( _this.target_textbox ).val( _this.getPreviewText( newRes ) );
			_this.clearTextboxCache();

			// update the render area (if present)
			var embedCode = _this.getEmbedCode( newRes );
			if ( _this.target_render_area && embedCode ) {
				// output with some padding:
				$j( _this.target_render_area )
					.append( embedCode + '<div style="clear:both;height:10px">' )

				// update the player if video or audio:
				if ( newRes.mime.indexOf( 'audio' ) != -1 ||
					newRes.mime.indexOf( 'video' ) != -1 ||
					newRes.mime.indexOf( '/ogg' ) != -1 ) 
				{
					mvJsLoader.embedVideoCheck( function() {
						mv_video_embed();
					} );
				}
			}
			_this.closeAll();
		} );
	},

	closeAll: function() {
		var _this = this;
		js_log( "close all:: "  + _this.target_container );
		_this.onCancelClipEdit();
		// Give a chance for the events to complete
		// (somehow at least in firefox a rare condition occurs where
		// the modal of the edit-box stick around even after the
		// close request has been issued. )
		setTimeout( 
			function() {
				$j( _this.target_container ).dialog( 'close' );
			}, 10 
		);
	},

	showResultsHeader: function() {
		var _this = this;
		var darkBoxUrl = mv_skin_img_path + 'box_layout_icon_dark.png';
		var lightBoxUrl = mv_skin_img_path + 'box_layout_icon.png';
		var darkListUrl = mv_skin_img_path + 'list_layout_icon_dark.png';
		var lightListUrl = mv_skin_img_path + 'list_layout_icon.png';

		if ( !this.content_providers[ this.currentProvider ] ) {
			return;
		}
		var cp = this.content_providers[this.currentProvider];
		var resultsFromMsg = gM( 'mwe-results_from', 
			[ cp.homepage, gM( 'rsd-' + this.currentProvider + '-title' ) ] );
		var defaultBoxUrl, defaultListUrl;
		if ( _this.displayMode == 'box' ) {
			defaultBoxUrl = darkBoxUrl;
			defaultListUrl = lightListUrl;
		} else {
			defaultBoxUrl = lightBoxUrl;
			defaultListUrl = darkListUrl;
		}

		var about_desc = '<span style="position:relative;top:0px;font-style:italic;">' +
			'<i>' + resultsFromMsg + '</i></span>';

		$j( '#tab-' + this.currentProvider ).append( '<div id="rds_results_bar">' +
			'<span style="float:left;top:0px;font-style:italic;">' +
			gM( 'rsd_layout' ) + ' ' +
			'<img id="msc_box_layout" ' +
				'title = "' + gM( 'rsd_box_layout' ) + '" ' +
				'src = "' +  defaultBoxUrl + '" ' +
				'style="width:20px;height:20px;cursor:pointer;"> ' +
			'<img id="msc_list_layout" ' +
				'title = "' + gM( 'rsd_list_layout' ) + '" ' +
				'src = "' +  defaultListUrl + '" ' +
				'style="width:20px;height:20px;cursor:pointer;">' +
			about_desc +
			'</span>' +
			'<span id="rsd_paging_ctrl" style="float:right;"></span>' +
			'</div>'
		);

		// Get paging with bindings:
		this.showPagingHeader( '#rsd_paging_ctrl' );

		$j( '#msc_box_layout' )
			.hover( 
				function() {
					$j( this ).attr( "src", darkBoxUrl );
				}, 
				function() {
					$j( this ).attr( "src",  defaultBoxUrl );
				} )
			.click( function() {
				$j( this ).attr( "src", darkBoxUrl );
				$j( '#msc_list_layout' ).attr( "src", lightListUrl );
				_this.setDisplayMode( 'box' );
			} );

		$j( '#msc_list_layout' )
			.hover( 
				function() {
					$j( this ).attr( "src", darkListUrl );
				}, 
				function() {
					$j( this ).attr( "src", defaultListUrl );
				} )
			.click( function() {
				$j( this ).attr( "src", darkListUrl );
				$j( '#msc_box_layout' ).attr( "src", lightBoxUrl );
				_this.setDisplayMode( 'list' );
			} );
	},

	showPagingHeader: function( target ) {
		var _this = this;
		if ( _this.currentProvider == 'upload' ) {
			var provider = _this.content_providers['this_wiki'];
		} else {
			var provider = _this.content_providers[ _this.currentProvider ];
		}
		var search = provider.sObj;
		js_log( 'showPagingHeader:' + _this.currentProvider + ' len: ' + search.num_results );
		var to_num = ( provider.limit > search.num_results ) ?
			( parseInt( provider.offset ) + parseInt( search.num_results ) ) :
			( parseInt( provider.offset ) + parseInt( provider.limit ) );
		var out = '';

		// @@todo we should instead support the wiki number format template system instead of inline calls
		if ( search.num_results != 0 ) {
			if ( search.num_results  >  provider.limit ) {
				out += gM( 'rsd_results_desc_total', [( provider.offset + 1 ), to_num, 
					mw.lang.formatNumber( search.num_results )] );
			} else {
				out += gM( 'rsd_results_desc', [( provider.offset + 1 ), to_num] );
			}
		}
		// check if we have more results (next prev link)
		if ( provider.offset >= provider.limit ) {
			out += ' <a href="#" id="rsd_pprev">' + gM( 'rsd_results_prev' ) + ' ' + provider.limit + '</a>';
		}

		if ( search.more_results ) {
			out += ' <a href="#" id="rsd_pnext">' + gM( 'rsd_results_next' ) + ' ' + provider.limit + '</a>';
		}

		$j( target ).html( out );

		// set bindings
		$j( '#rsd_pnext' ).click( function() {
			provider.offset += provider.limit;
			_this.showCurrentTab();
		} );

		$j( '#rsd_pprev' ).click( function() {
			provider.offset -= provider.limit;
			if ( provider.offset < 0 )
				provider.offset = 0;
			_this.showCurrentTab();
		} );
	},

	selectTab: function( provider ) {
		js_log( 'select tab: ' + provider );
		this.currentProvider = provider;
		if ( this.currentProvider == 'upload' ) {
			this.showUploadTab();
		} else {
			// update the search results:
			this.showCurrentTab();
		}
	},

	setDisplayMode: function( mode ) {
		js_log( 'setDisplayMode:' + mode );
		this.displayMode = mode;
		// run /update search display:
		this.showResults();
	}
};
