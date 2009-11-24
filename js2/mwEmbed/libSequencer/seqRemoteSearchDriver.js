/*the sequence remote search driver
	 extends the base remote search driver with sequence specific stuff
*/

var seqRemoteSearchDriver = function( iObj ) {
	return this.init( iObj )
}
seqRemoteSearchDriver.prototype = {
	sequence_add_target:false,
	init:function( this_seq ) {
		var _this = this;
		js_log( "init:seqRemoteSearchDriver" );
		// setup remote search driver with a seq parent:
		this.pSeq = this_seq;
		var iObj = {
			'target_container'	: '#cliplib_ic',
			'local_wiki_api_url': this_seq.getLocalApiUrl(),
			'instance_name'		: this_seq.instance_name + '.mySearch',
			'default_query'		: this.pSeq.plObj.title
		}
		if ( typeof this_seq.amw_conf != 'undefined' )
			$j.extend( iObj,  this_seq.amw_conf );
			
	
		// inherit the remoteSearchDriver properties:n
		var tmpRSD = new remoteSearchDriver( iObj );
		for ( var i in tmpRSD ) {
			if ( this[i] ) {
				this['parent_' + i] = tmpRSD[i];
			} else {
				this[i] = tmpRSD[i];
			}
		}
		// extend parent_do_refresh_timeline actions:
		if ( !this.pSeq.parent_do_refresh_timeline ) {
			this.pSeq.parent_do_refresh_timeline = this.pSeq.do_refresh_timeline;
			this.pSeq.do_refresh_timeline = function() {
				js_log( "seqRemoteSearchDriver::" + _this.pSeq.disp_menu_item );
				// call the parent
				_this.pSeq.parent_do_refresh_timeline();
				// add our local bindings
				_this.addResultBindings();
				return true;
			}
		}
	},
	resourceEdit:function() {
		var _this = this;

	},
	addResultBindings:function() {
		// set up seq:
		var _this = this;
		// setup parent bindings:
		this.parent_addResultBindings();

		// Add an additional click binding
		$j( '.rsd_res_item' ).click( function() {
			js_log( 'SeqRemoteSearch: rsd_res_item: click (remove sequence_add_target)' );
			_this.sequence_add_target = false;
		} );

		// Add an additional drag binding
		$j( '.rsd_res_item' ).draggable( 'destroy' ).draggable( {
			helper:function() {
				return $j( this ). clone ().appendTo( 'body' ).css( { 'z-index':9999 } ).get( 0 );
			},
			revert:'invalid',
			start:function() {
				js_log( 'start drag' );
			}
		} );
		$j( ".mv_clip_drag" ).droppable( 'destroy' ).droppable( {
			accept: '.rsd_res_item',
			over:function( event, ui ) {
				// js_log("over : mv_clip_drag: " + $j(this).attr('id') );
				$j( this ).css( 'border-right', 'solid thick red' );
			},
			out:function( event, ui ) {
				$j( this ).css( 'border-right', 'solid thin white' );
			},
			drop: function( event, ui ) {
				$j( this ).css( 'border-right', 'solid thin white' );
				js_log( "Droped: " + $j( ui.draggable ).attr( 'id' ) + ' on ' +  $j( this ).attr( 'id' ) );
				_this.sequence_add_target =  $j( this ).attr( 'id' );
				// load the orginal draged item
				var rObj = _this.getResourceFromId( $j( ui.draggable ).attr( 'id' ) );
				_this.resourceEdit( rObj, ui.draggable );
			}
		} );

	},
	insertResource:function( rObj ) {
		var _this = this;
		js_log( "SEQ insert resource after:" + _this.sequence_add_target  + ' of type: ' + rObj.mime );
		if ( _this.sequence_add_target ) {
			var tClip = _this.pSeq.getClipFromSeqID( _this.sequence_add_target );
			var target_order = false;
			if ( tClip )
				var target_order = tClip.order;
		}
		// @@todo show watting of sorts.

		// get target order:
		var cat = rObj;
		// check for target insert path
		this.checkImportResource( rObj, function() {

			var clipConfig = {
				'type' 	 : rObj.mime,
				'uri' 	 : _this.fileNS + ':' + rObj.target_resource_title,
				'title'	 : rObj.title
			};
			// Set via local properties if available 
			clipConfig['src'] = ( rObj.local_src ) ? rObj.local_src : rObj.src;
			clipConfig['poster'] = ( rObj.local_poster ) ? rObj.local_poster : rObj.poster;

			if ( rObj.start_time && rObj.end_time ) {
				clipConfig['dur'] = npt2seconds( rObj.end_time ) - npt2seconds( rObj.start_time );
			} else {
				// Provide a default duration if none set
				clipConfig['dur'] = 4;
			}

			// Create the media element (target order+1 (since we insert (after)
			_this.pSeq.plObj.tryAddMediaObj( clipConfig, ( parseInt( target_order ) + 1 ) );
			
			// Refresh the timeline:
			_this.pSeq.do_refresh_timeline();
			js_log( "run close all: " );
			_this.closeAll();
		} );
	},
	getClipEditControlActions:function() {
		var _this = this;
		return {
			'insert_seq':function( rObj ) {
				_this.insertResource( rObj )
			},
			'cancel':function( rObj ) {
				_this.cancelClipEditCB( rObj )
			}
		};
	},
	resourceEdit:function( rObj, rsdElement ) {
		var _this = this;
		// don't resize to default (full screen behavior)
		_this.dmodalCss = { };
		// open up a new target_contaienr:
		if ( $j( '#seq_resource_import' ).length == 0 )
			$j( 'body' ).append( '<div id="seq_resource_import" style="position:relative"></div>' );
		var bConf = { };
		bConf[ gM( 'mwe-cancel' ) ] = function() {
			$j( this ).dialog( "close" );
		}
		$j( '#seq_resource_import' ).dialog( 'destroy' ).dialog( {
			bgiframe: true,
			width:750,
			height:480,
			modal: true,
			buttons: bConf
		} );
		_this.target_container = '#seq_resource_import';
		// do parent resource edit (with updated target)
		this.parent_resourceEdit( rObj, rsdElement );
	},
	closeAll:function() {
		js_log( 'should close: seq_resource_import' );
		$j( '#seq_resource_import' ).dialog( 'close' ).dialog( 'destroy' ).remove();
		this.parent_closeAll();
	},
	getEditToken:function( callback ) {
		if ( this.pSeq.sequenceEditToken ) {
			callback( this.pSeq.sequenceEditToken )
		} else {
			this.parent_getEditToken( callback );
		}
	},
	cancelClipEditCB:function() {
		js_log( 'seqRSD:cancelClipEditCB' );
		$j( '#seq_resource_import' ).dialog( 'close' ).dialog( 'destroy' ).remove();
	}
};

