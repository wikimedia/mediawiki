var mediaWikiSearch = function( iObj ) {		
	return this.init( iObj );
};
mediaWikiSearch.prototype = {
	init:function( iObj ){
		//init base class and inherit: 
		var baseSearch = new baseRemoteSearch( iObj );
		for(var i in baseSearch){
			if(typeof this[i] =='undefined'){
				this[i] = baseSearch[i];
			}else{
				this['parent_'+i] =  baseSearch[i];
			}
		}
		//inherit the cp settings for 
	},	
	//returns a rObj by title 
	addByTitle:function( title , callback, redirect_count){		
		js_log("AddByTitle::" + title);
		var _this = this;		
		if(!redirect_count)
			redirect_count=0;
		if(redirect_count > 5){
			js_log('Error: addByTitle too many redirects');
			callback( false );
			return false;
		}
		var reqObj = {
			'action':'query',
			'titles':'File:'+title,	
			'prop':'imageinfo|revisions|categories',
			'iiprop':'url|mime|size',
			'iiurlwidth': parseInt( this.rsd.thumb_width ),
			'rvprop':'content'	
		}
		do_api_req( {
			'data':reqObj, 
			'url':this.cp.api_url 
			}, function(data){	
				//check for redirect
				for(var i in data.query.pages){
					var page = data.query.pages[i];					
					if(page.revisions[0]['*'] && page.revisions[0]['*'].indexOf('#REDIRECT') === 0){
					 	var re = new RegExp(/[^\[]*\[\[([^\]]*)/);
						var pt = page.revisions[0]['*'].match( re );
						if( pt[1] ){
							_this.addByTitle( pt[1], callback, redirect_count++);
							return ;
						}else{
							js_log('Error: addByTitle could not proccess redirect');
							callback( false );
							return false;
						}
					}
				}
				//if not a redirect do the callback directly: 	
				callback( _this.addSingleResult(data) );			
			}
		);			
	},
	clearResults:function(){
		this.resultsObj = {};		
		this.last_query ='';
	},
	//update the resultObj with recently uploaded items by current User:
	getUserRecentUploads:function(wgUser, callback){
		var _this = this;
		do_api_req({
			'data':{
				'action':'query',
				'list':'recentchanges',
				'rcnamespace':6, //only files
				'rcuser': wgUser,
				'rclimit':15, //get last 15 uploaded files 				
			},
			'url':this.cp.api_url
		},function(data){			
			var titleSet = {};
			var titleStr = ''
			var pound = '';
			//loop over the data and group by title
			if(data.query && data.query.recentchanges){	
				for(var i in data.query.recentchanges){
					var rc = data.query.recentchanges[i];
					if( !titleSet[ rc.title ] ){
						titleSet[ rc.title ]=true;
						titleStr+= pound + rc.title;
						pound ='|';
					}
				}
			}					
			//now run the actual query ( too bad we can't use recentchanges as a generator )
			//bug 20563
			do_api_req({
				'data' : {
					'action'	: 'query',
					'titles'	: titleStr,	
					'prop'		: 'imageinfo|revisions|categories',
					'iiprop'	: 'url|mime|size',
					'iiurlwidth': parseInt( _this.rsd.thumb_width ),
					'rvprop':'content'	
				},
				'url':_this.cp.api_url
			},function(data){				
				_this.clearResults();
				_this.addResults(data);
				if(callback)
					callback();
			});			
		});
	}, 	
	getSearchResults:function(){
		//call parent: 
		this.parent_getSearchResults();
		//set local ref:
		var _this = this;
				
		js_log('f:getSearchResults for:' + $j('#rsd_q').val() );		
		//do two queries against the Image / File / MVD namespace:
										 
		//build the image request object: 
		var reqObj = {
			'action':'query',			 
			'generator':'search',
			'gsrsearch':  $j('#rsd_q').val(),  
			'gsrnamespace':6, //(only search the "file" namespace (audio, video, images)
			'gsrwhat':'title',
			'gsrlimit':  this.cp.limit,
			'gsroffset': this.cp.offset,
			'prop':'imageinfo|revisions|categories',
			'iiprop':'url|mime|size',
			'iiurlwidth': parseInt( this.rsd.thumb_width ),
			'rvprop':'content'
		};				
		//set up the number of request: 
		this.completed_req=0;
		this.num_req=1;		
		//setup the number of requests result flag:											 
		//also do a request for page titles (would be nice if api could query both at the same time) 
		reqObj['gsrwhat']='text';
		do_api_req( {
			'data':reqObj, 
			'url':this.cp.api_url 
			}, function(data){
				js_log('mediaWikiSearch: got data response'); 
				//parse the return data
				_this.addResults( data);
				//_this.checkRequestDone(); //only need if we do two queries one for title one for text
				_this.loading = false;
		});			
	},	
	//same as below but returns your rObj for convenience 
	addSingleResult:function( data ){
		return this.addResults(data, true);
	},
	addResults:function( data, returnFirst ){	
		js_log("f:addResults");
		var _this = this		
		//check if we have 
		if( typeof data['query-continue'] != 'undefined'){
			if( typeof data['query-continue'].search != 'undefined')
				this.more_results = true;			
		}
		//make sure we have pages to idorate: 	
		if(data.query && data.query.pages){
			for(var page_id in  data.query.pages){
				var page =  data.query.pages[ page_id ];
				
				//make sure the reop is shared (don't show for now it confusing things)
				//@@todo support remote repository better
				if( page.imagerepository == 'shared'){
					continue;
				}
				
				//make sure the page is not a redirect
				if(page.revisions && page.revisions[0] && 
					page.revisions[0]['*'] && page.revisions[0]['*'].indexOf('#REDIRECT') === 0){
					//skip page is redirect 
					continue;
				}								
				//skip if its an empty or missing imageinfo: 
				if( !page.imageinfo )
					continue;
				var rObj = 	{
					'id'		 : page_id,
					'titleKey'	 : page.title,
					'link'		 : page.imageinfo[0].descriptionurl,				
					'title'		 : page.title.replace(/File:|.jpg|.png|.svg|.ogg|.ogv|.oga/ig, ''),
					'poster'	 : page.imageinfo[0].thumburl,
					'thumbwidth' : page.imageinfo[0].thumbwidth,
					'thumbheight': page.imageinfo[0].thumbheight,
					'orgwidth'	 : page.imageinfo[0].width,
					'orgheight'	 : page.imageinfo[0].height,
					'mime'		 : page.imageinfo[0].mime,
					'src'		 : page.imageinfo[0].url,
					'desc'		 : page.revisions[0]['*'],		
					//add pointer to parent search obj:
					'pSobj'		 :_this,			
					'meta':{
						'categories':page.categories
					}
				};	
				/*
				 //to use once we get the wiki-text parser in shape
				var pObj = mw.parser.pNew( rObj.desc );
				//structured data on commons is based on the "information" template: 
				var tmplInfo = pObj.templates( 'information' );				
				*/
				
				//attempt to parse out the description current user desc from the commons template: 
				//@@todo these could be combined to a single regEx
				// or better improve the wiki-text parsing and use above 
				var desc = rObj.desc.match(/\|\s*description\s*=\s*(([^\n]*\n)*)\|\s*source=/i);
				if( desc && desc[1] ){					
					rObj.desc = $j.trim( desc[1] );					
					//attempt to get the user language if set: 
					if( typeof wgUserLanguage != 'undefined' && wgUserLanguage ){		
						//for some reason the RegExp object is not happy:
						var reg = new RegExp( '\{\{\s*' + wgUserLanguage + '([^=]*)=([^\}]*)\}\}', 'gim' );						
						var langMatch = reg.exec( rObj.desc );
						if(langMatch && langMatch[2]){
							rObj.desc = langMatch[2];
						}else{											
							//try simple lang template form:
							var reg = new RegExp( '\{\{\s*' + wgUserLanguage + '\\|([^\}]*)\}\}', 'gim' );
							var langMatch = reg.exec( rObj.desc );																						
							if(langMatch && langMatch[1]){
								rObj.desc = langMatch[1];
							}						
						}
					}										
				}
										
				//likely a audio clip if no poster and type application/ogg 
				//@@todo we should return audio/ogg for the mime type or some other way to specify its "audio" 
				if( ! rObj.poster && rObj.mime == 'application/ogg' ){					
					rObj.mime = 'audio/ogg';
				}
				//add to the resultObj
				this.resultsObj[page_id] = rObj;
				
				//if returnFirst flag:
				if(returnFirst)
					return this.resultsObj[page_id];
				
				
				this.num_results++;	
				//for(var i in this.resultsObj[page_id]){
				//	js_log('added: '+ i +' '+ this.resultsObj[page_id][i]);
				//}
			}
		}else{
			js_log('no results:' + data);
		}	
	},	
	//check request done used for when we have multiple requests to check before formating results. 
	checkRequestDone:function(){
		//display output if done: 
		this.completed_req++;
		if(this.completed_req == this.num_req){
			this.loading = 0;
		}
	},	
	getImageObj:function( rObj, size, callback ){					
		if( rObj.mime=='application/ogg' )
			return callback( {'url':rObj.src, 'poster' : rObj.url } );		
		
		//his could be depreciated if thumb.php improves
		var reqObj = {
			'action':'query',
			'format':'json',
			'titles':rObj.titleKey,
			'prop':'imageinfo',
			'iiprop':'url|size|mime' 
		}
		//set the width: 
		if(size.width)
			reqObj['iiurlwidth']= size.width;				 
		 js_log('going to do req: ' + this.cp.api_url + ' ' + reqObj );
		do_api_req( {
			'data':reqObj, 
			'url' : this.cp.api_url
			}, function(data){								
				var imObj = {};
				for(var page_id in  data.query.pages){
					var iminfo =  data.query.pages[ page_id ].imageinfo[0];
					//store the orginal width:				 
					imObj['org_width']=iminfo.width;
					//check if thumb size > than image size and is jpeg or png (it will not scale well above its max res)				
					if( ( iminfo.mime=='image/jpeg' || iminfo=='image/png' ) &&
						iminfo.thumbwidth > iminfo.width ){		 
						imObj['url'] = iminfo.url;
						imObj['width'] = iminfo.width;
						imObj['height'] = iminfo.height;					
					}else{					
						imObj['url'] = iminfo.thumburl;					
						imObj['width'] = iminfo.thumbwidth;
						imObj['height'] = iminfo.thumbheight;
					}
				}
				js_log('getImageObj: get: ' + size.width + ' got url:' + imObj.url);			
				callback( imObj ); 
		});
	},
	//the insert image function   
	insertImage:function( cEdit ){
		if(!cEdit)
			var cEdit = _this.cEdit;		
	},
	getEmbedHTML: function( rObj , options) {		
		if(!options)
			options = {};	
		this.parent_getEmbedHTML( rObj, options);
		//check for image output:
		if( rObj.mime.indexOf('image') != -1 ){
			return this.getImageEmbedHTML( rObj, options );		
		}
		//for video and audio output: 		
		var ahtml='';
		if( rObj.mime == 'application/ogg' || rObj.mime == 'audio/ogg' ){
			ahtml = options.id_attr + 
						' src="' + rObj.src + '" ' +
						options.style_attr +
						' poster="'+  rObj.poster + '" '										
			if(rObj.mime.indexOf('application/ogg')!=-1){
				return '<video ' + ahtml + '></video>'; 
			}
					
			if(rObj.mime.indexOf('audio/ogg')!=-1){
				return '<audio ' + ahtml + '></audio>';
			}
		}						
		js_log( 'ERROR:unsupored mime type: ' + rObj.mime );
	},
	getInlineDescWiki:function( rObj ){						
		var desc = this.parent_getInlineDescWiki( rObj );
		
		//strip categories for inline Desc: (should strip license tags too but not as easy)
		desc = desc.replace(/\[\[Category\:[^\]]*\]\]/, '');
		
		//just grab the description tag for inline desc:
		var descMatch = new RegExp(/Description=(\{\{en\|)?([^|]*|)/);			
		var dparts = desc.match(descMatch);		
				
		if( dparts && dparts.length > 1){	
			desc = (dparts.length == 2) ? dparts[1] : dparts[2].replace('}}','');
			desc = (desc.substr(0,2) == '1=') ?desc.substr(2): desc;
			return desc;	 
		}
		//else return the title since we could not find the desc:
		js_log('Error: No Description Tag, Using::' + desc );
		return desc;
	},
	//returns the inline wikitext for insertion (template based crops for now) 
	getEmbedWikiCode: function( rObj ){		
			//set default layout to right justified
			var layout = ( rObj.layout)? rObj.layout:"right"
			//if crop is null do base output: 
			if( rObj.crop == null)
				return this.parent_getEmbedWikiCode( rObj );											
			//using the preview crop template: http://en.wikipedia.org/wiki/Template:Preview_Crop
			//@@todo should be replaced with server side cropping 
			return '{{Preview Crop ' + "\n" +
						'|Image   = ' + rObj.target_resource_title + "\n" +
						'|bSize   = ' + rObj.width + "\n" + 
						'|cWidth  = ' + rObj.crop.w + "\n" +
						'|cHeight = ' + rObj.crop.h + "\n" +
						'|oTop	= ' + rObj.crop.y + "\n" +
						'|oLeft   = ' + rObj.crop.x + "\n" +
						'|Location =' + layout + "\n" +
						'|Description =' + rObj.inlineDesc + "\n" +
					'}}';
	}
}