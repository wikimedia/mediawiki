/*
* API modes (implementations should call these objects which inherit the mvBaseRemoteSearch
*/
loadGM( {
	"mwe-stream_title" : "$1 $2 to $3"
} );
var metavidSearch = function( iObj ) {
	return this.init( iObj );
};
metavidSearch.prototype = {
	defaultReq: {  // set up the default request paramaters
		'order':'recent',
		'feed_format':'json_rss',
		'cb_inx': 1 // Not really used (we should update the metavid json retrun system) 
	},
	init:function( iObj ) {
		// init base class and inherit:
		var baseSearch = new baseRemoteSearch( iObj );
		for ( var i in baseSearch ) {
			if ( typeof this[i] == 'undefined' ) {
				this[i] = baseSearch[i];
			} else {
				this['parent_' + i] =  baseSearch[i];
			}
		}
	},
	getSearchResults:function() {
		// call parent:
		this.parent_getSearchResults();
		// set local ref:
		var _this = this;
		js_log( 'metavidSearch::getSearchResults()' );
		// Proccess all options
		var url = this.cp.api_url;
		var reqObj = $j.extend({}, this.defaultReq);
		reqObj[ 'f[0][t]' ] = 'match';
		reqObj[ 'f[0][v]' ] = $j( '#rsd_q' ).val();
		
		// add offset limit:
		reqObj[ 'limit' ] = this.cp.limit;
		reqObj[ 'offset' ] =  this.cp.offset;

		do_api_req({
			'url' : url,
			'jsonCB' : 'cb',			
			'data' : reqObj
		}, function( data ) {	
			js_log( 'mvSearch: got data response::' );
			var xmldata = ( data && data['pay_load'] ) ? mw.parseXML( data['pay_load'] ) : false;
			if( !xmldata ){
				// XML Error or No results: 
				_this.resultsObj = {};
				_this.loading = 0;
				return ;				
			}
						
			// Add the data xml payload with context url:
			_this.addRSSData( xmldata , url );
			
			// Do some metavid specific pos processing on the rObj data:
			for ( var i in _this.resultsObj ) {
				var rObj = _this.resultsObj[i];
				var proe = mw.parseUri( rObj['roe_url'] );
				rObj['start_time'] = proe.queryKey['t'].split( '/' )[0];
				rObj['end_time'] = proe.queryKey['t'].split( '/' )[1];
				rObj['stream_name'] = proe.queryKey['stream_name'];

				// All metavid content is public domain:
				rObj['license'] = _this.rsd.getLicenseFromKey( 'pd' );

				// Transform the title into a wiki_safe title:				
				rObj['titleKey'] =	 _this.getTitleKey( rObj );

				// Default width of metavid clips:
				rObj['target_width'] = 400;
								
				rObj['author'] = 'US Government';
				
				// Add in the date as UTC "toDateString" : 
				var d = _this.getDateFromLink( rObj.link );
				rObj['date'] =	 d.toDateString();
				
				// Set the license_template_tag ( all metavid content is PD-USGov )
				rObj['license_template_tag'] = 'PD-USGov';
			}
			// done loading:
			_this.loading = 0;
		} );
	},
	/** 
	* Get a Title key for the assset name inside the mediaWiki system
	*/
	getTitleKey:function( rObj ) {
		return rObj['stream_name'] + '_part_' + rObj['start_time'].replace(/:/g, '.' ) + '_to_' + rObj['end_time'].replace(/:/g, '.' ) + '.ogv';
	},
	getTitle:function( rObj ) {
		var sn = rObj['stream_name'].replace( /_/g, ' ' );
		sn = sn.charAt( 0 ).toUpperCase() + sn.substr( 1 );
		return gM( 'mwe-stream_title', [ sn, rObj.start_time, rObj.end_time ] );
	},
	getExtraResourceDescWiki:function( rObj ) {
		var o = "\n";
		// check for person
		if (  rObj.person && rObj.person['label'] )
			o += '* featuring [[' + rObj.person['label'] + ']]' + "\n";

		if ( rObj.parent_clip )
			o += '* part of longer [' + rObj.parent_clip + ' video clip]' + "\n";

		if ( rObj.person && rObj.person['url'] && rObj.person['label'] )
			o += '* also see speeches by [' +  $j.trim( rObj.person.url ) + ' ' + rObj.person['label'] + ']' + "\n";

		// check for bill:
		if ( rObj.bill && rObj.bill['label'] && rObj.bill['url'] )
			o += '* related to bill: [[' + rObj.bill['label'] + ']] more bill [' + rObj.bill['url'] + ' video clips]' + "\n";
		return o;
	},
	// format is "quote" followed by [[name of person]]
	getInlineDescWiki:function( rObj ) {
		var o = this.parent_getInlineDescWiki( rObj );
		// add in person if found
		if ( rObj.person &&  rObj.person['label'] ) {
			o = $j.trim(  o.replace( rObj.person['label'], '' ) );
			// trim leading :
			if ( o.substr( 0, 1 ) == ':' )
				o =  o.substr( 1 );
			// add quotes and person at the end:
			var d = this.getDateFromLink( rObj.link );
			o = '"' + o + '" [[' + rObj.person['label'] + ']] on ' + d.toDateString();
		}
		// could do ref or direct link:
		o += ' \'\'[' + $j.trim( rObj.link ) + ' source clip]\'\' ';

		// var o= '"' + o + '" by [[' + rObj.person['label'] + ']] '+
		//		'<ref>[' + rObj.link + ' Metavid Source Page] for ' + rObj.title +'</ref>';
		return o;
	},
	// give an updated start and end time updates the title and url
	applyVideoAdj: function( rObj ) {
		js_log( 'mv ApplyVideoAdj::' );
		// update the titleKey:
		rObj['titleKey'] =	 this.getTitleKey( rObj );

		// update the title:
		rObj['title'] = this.getTitle( rObj );

		// update the interface:
		js_log( 'update title to: ' + rObj['title'] );
		$j( '#rsd_resource_title' ).html( gM( 'rsd_resource_edit', rObj['title'] ) );

		// if the video is "roe" based select the ogg stream
		if ( rObj.roe_url && rObj.pSobj.cp.stream_import_key ) {
			var source = $j( '#embed_vid' ).get( 0 ).media_element.getSourceById( rObj.pSobj.cp.stream_import_key );
			if ( !source ) {
				js_error( 'Error::could not find source: ' +  rObj.pSobj.cp.stream_import_key );
			} else {
				rObj['src'] = source.getURI();
				js_log( "g src_key: " + rObj.pSobj.cp.stream_import_key + ' src:' + rObj['src'] ) ;
				return true;
			}
		}
	},
	getEmbedHTML:function( rObj , options ) {
	    if ( !options )
		     options = { };
		var id_attr = ( options['id'] ) ? ' id = "' + options['id'] + '" ': '';
		var style_attr = ( options['max_width'] ) ? ' style="width:' + options['max_width'] + 'px;"':'';
		// @@maybe check type here ?
		if ( options['only_poster'] ) {
			return '<img ' + id_attr + ' src="' + rObj['poster'] + '" ' + style_attr + '>';
		} else {
			return '<video ' + id_attr + ' roe="' + rObj['roe_url'] + '"></video>';
		}
	},
	getImageTransform:function( rObj, opt ) {
		if ( opt.width <= 80 ) {
			return getURLParamReplace( rObj.poster, { 'size' : "icon" } )
		} else if ( opt.width <= 160 ) {
			return getURLParamReplace( rObj.poster, { 'size' : "small" } )
		} else if ( opt.width <= 320 ) {
			return getURLParamReplace( rObj.poster, { 'size' : 'medium' } )
		} else if ( opt.width <= 512 ) {
			return getURLParamReplace( rObj.poster, { 'size' : 'large' } )
		} else {
			return getURLParamReplace( rObj.poster, { 'size' : 'full' } )
		}
	},
	addResourceInfoFromEmbedInstance : function( rObj, embed_id ) {
		var sources = $j( '#' + embed_id ).get( 0 ).media_element.getSources();
		rObj.other_versions = '*[' + rObj['roe_url'] + ' XML of all Video Formats and Timed Text]' + "\n";
		for ( var i in sources ) {
			var cur_source = sources[i];
			// rObj.other_versions += '*['+cur_source.getURI() +' ' + cur_source.title +']' + "\n";
			if ( cur_source.id ==  this.cp.target_source_id )
				rObj['url'] = cur_source.getURI();
		}
		// js_log('set url to: ' + rObj['url']);
		return rObj;
	},
	getDateFromLink:function( link ) {
		var dateExp = new RegExp( /_([0-9]+)\-([0-9]+)\-([0-9]+)/ );
		var dParts = link.match ( dateExp );
		var d = new Date();
		var year_full = ( dParts[3].length == 2 ) ? '20' + dParts[3].toString():dParts[3];
		d.setFullYear( year_full, dParts[1] - 1, dParts[2] );
		return d;
	}
}
