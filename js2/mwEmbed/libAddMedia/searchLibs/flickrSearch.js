/*
 * basic flickr search uses flickr jsonp api  
 * http://www.flickr.com/services/api/
 * 
 * uses the "example api_key" 519b66e3fd8d8080e27a64fe51101e2c
 * should update with a different "public" key sometime soon
 * http://www.flickr.com/services/rest/?method=flickr.test.echo&format=json&api_key=519b66e3fd8d8080e27a64fe51101e2c
 *
 * we look for licenses from method=flickr.photos.licenses.getInfo
 * per http://commons.wikimedia.org/wiki/Special:Upload?uselang=fromflickr
 * we are interested in:  
 *	(4) Attribution License 
 *	(5) Attribution-ShareAlike License, 
 *	(7) No known copyright restrictions,
 *	(8) United States Government Work
 */

var flickrSearch = function ( iObj ) {
	return this.init( iObj );
}
flickrSearch.prototype = {
	dtUrl : 'http://www.flickr.com/photos/',
	// @@todo probably would be good to read the api-key from configuration
	apikey : '2867787a545cc66c0bce6f2e57aca1d1',
	// what licence we are interested in
	_licence_keys: '4,5,7,8',
	_srctypes: ['t', 'sq', 's', 'm', 'o'],
	licenceMap: {
		'4' : 'http://creativecommons.org/licenses/by/3.0/',
		'5'	: 'http://creativecommons.org/licenses/by-sa/3.0/',
		'7'	: 'http://www.flickr.com/commons/usage/',
		'8' : 'http://www.usa.gov/copyright.shtml'
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
		// inherit the cp settings for 
	},
	getSearchResults:function() {
		var _this = this;
		js_log( "flickr::getSearchResults" );
		// call parent (sets loading sate and other setup stuff) 
		this.parent_getSearchResults();
		// setup the flickr request: 
		var reqObj = {
			'method':'flickr.photos.search',
			'format':'json',
			'license':this._licence_keys,
			'api_key':this.apikey,
			'per_page': this.cp.limit,
			'page' : this.cp.offset,
			'text': $j( '#rsd_q' ).val(),
			'extras' :	'license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_m, url_o'
		}
		do_api_req( {
			'data': reqObj,
			'url':this.cp.api_url,
			'jsonCB':'jsoncallback',
		},	function( data ) {
			_this.addResults( data );
			_this.loading = false;
		} );
	},
	addResults:function( data ) {
		var _this = this;
		if ( data.photos && data.photos.photo ) {
			// set result info: 
			this.num_results = data.photos.total;
			if ( this.num_results > this.cp.offset + this.cp.limit ) {
				this.more_results = true;
			}
			for ( var resource_id in data.photos.photo ) {
				var resource = data.photos.photo[resource_id];
			
				var rObj = {
					'titleKey'	 : resource.title + '.jpg',
					'resourceKey': resource.id,
					'link'		 : _this.dtUrl + resource.pathalias + '/' + resource.id,
					'title'		 : resource.title,
					'thumbwidth' : resource.width_t,
					'thumbheight': resource.height_t,
					'desc'		 : resource.title,
					// set the license: (rsd is a pointer to the parent remoteSearchDriver )		 
					'license'	 : this.rsd.getLicenceFromUrl( _this.licenceMap[ resource.license ] ),
					'pSobj'		 : _this,
					// assume image/jpeg for "photos"
					'mime'		 : 'image/jpeg'
				};
				// set all the provided srcs:
				rObj['srcSet'] = { };
				for ( var i in _this._srctypes ) {
					var st = _this._srctypes[i];
					// if resource has a url add it to the srcSet:	
					if ( resource['url_' + st] ) {
						rObj['srcSet'][st] = {
							'h': resource['height_' + st],
							'w': resource['width_' + st],
							'src': resource['url_' + st]
						}
						// set src to the largest 
						rObj['src'] = resource['url_' + st];
					}
				}
																															
				_this.resultsObj[ resource_id ] = rObj;
			}
		}
	},
	// return image transform via callback 
	getImageObj:function( rObj, size, callback ) {
		if ( size.width ) {
			var skey = this.getSrcTypeKey( rObj, size.width )
			callback ( {
				'url' : rObj.srcSet[ skey ].src,
				'width' : rObj.srcSet[ skey ].w,
				'height' : rObj.srcSet[ skey ].h
			} );
		}
	},
	getImageTransform:function( rObj, opt ) {
		if ( opt.width ) {
			return rObj.srcSet[ this.getSrcTypeKey( rObj, opt.width ) ].src;
		}
	},
	getSrcTypeKey:function( rObj, width ) {
		if ( width <= 75 ) {
			if ( rObj.srcSet['sq'] )
				return 'sq';
		} else if ( width <= 100 ) {
			if ( rObj.srcSet['t'] )
				return 't';
		} else if ( width <= 240 ) {
			if ( rObj.srcSet['s'] )
				return 's';
		} else if ( width <= 500 ) {
			if ( rObj.srcSet['m'] )
				return 'm';
		} else {
			if ( rObj.srcSet['o'] )
				return 'o';
		}
		// original was missing return medium or small
		if ( rObj.srcSet['m'] )
			return 'm';
		if ( rObj.srcSet['s'] )
			return 's';
		
	}
}