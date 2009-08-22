/*
* API modes (implementations should call these objects which inherit the mvBaseRemoteSearch
*/
loadGM({
	"mwe-stream_title" : "$1 $2 to $3"
});
var metavidSearch = function(iObj) {
	return this.init(iObj);
};
metavidSearch.prototype = {
	reqObj:{  //set up the default request paramaters
		'order':'recent',
		'feed_format':'rss'
	},
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
	},
	getSearchResults:function(){
		//call parent:
		this.parent_getSearchResults();

		var _this = this;
		//start loading:
		_this.loading= 1;
		js_log('metavidSearch::getSearchResults()');
		//proccess all options
		var url = this.cp.api_url;
		//add on the req_param
		for(var i in this.reqObj){
			url += '&' + i + '=' + this.reqObj[i];
		}
		url += '&f[0][t]=match&f[0][v]=' + $j('#rsd_q').val();
		//add offset limit:
		url+='&limit=' + this.cp.limit;
		url+='&offset=' + this.cp.offset;

		do_request(url, function(data){

			js_log('mvSearch: got data response');
			//should have an xml rss data object:
			_this.addRSSData( data , url );

			//do some metavid specific pos processing on the rObj data:
			for(var i in _this.resultsObj){
				var rObj = _this.resultsObj[i];
				var proe = parseUri( rObj['roe_url'] );
				rObj['start_time'] = proe.queryKey['t'].split('/')[0];
				rObj['end_time'] = proe.queryKey['t'].split('/')[1];
				rObj['stream_name'] = proe.queryKey['stream_name'];

				//all metavid content is public domain:
				rObj['license'] = _this.rsd.getLicenceFromKey( 'pd' );

				//transform the title into a wiki_safe title:
				//rObj['titleKey'] = proe.queryKey['stream_name'] + '_' + rObj['start_time'].replace(/:/g,'.') + '_' + rObj['end_time'].replace(/:/g,'.') + '.ogg';
				rObj['titleKey'] =	 _this.getTitleKey( rObj );

				//default width of metavid clips:
				rObj['target_width'] = 400;
			}
			//done loading:
			_this.loading=0;
		});
	},
	getTitleKey:function( rObj ){
		return rObj['stream_name'] + '_start-' + rObj['start_time'].replace(/:/g,'.') + '_end-' + rObj['end_time'].replace(/:/g,'.') + '.ogg';
	},
	getTitle:function( rObj ){
		var sn = rObj['stream_name'].replace(/_/g, ' ');
		sn = sn.charAt(0).toUpperCase() + sn.substr(1);
		return gM('mwe-stream_title', [ sn, rObj.start_time, rObj.end_time ]);
	},
	//metavid descption tied to public domain license key (government produced content)
	getPermissionWikiTag:function( rObj ){
		return '{{PD-USGov}}';
	},
	getExtraResourceDescWiki:function( rObj ){
		var o = "\n";
		//check for person
		if(  rObj.person && rObj.person['label'])
			o += '* featuring [[' + rObj.person['label'] + ']]' + "\n";

		if( rObj.parent_clip )
			o += '* part of longer [' + rObj.parent_clip + ' video clip]'+ "\n";

		if( rObj.person && rObj.person['url'] && rObj.person['label'] )
			o += '* also see speeches by [' +  $j.trim( rObj.person.url ) + ' ' + rObj.person['label'] + ']'+ "\n";

		//check for bill:
		if( rObj.bill && rObj.bill['label'] && rObj.bill['url'])
			o += '* related to bill: [[' + rObj.bill['label'] + ']] more bill [' + rObj.bill['url'] + ' video clips]'+ "\n";
		return o;
	},
	//format is "quote" followed by [[name of person]]
	getInlineDescWiki:function( rObj ){
		var o = this.parent_getInlineDescWiki( rObj );
		//add in person if found
		if( rObj.person &&  rObj.person['label'] ){
			o = $j.trim(  o.replace(rObj.person['label'], '') );
			//trim leading :
			if(o.substr(0,1)==':')
				o =  o.substr(1);
			//add quotes and person at the end:
			var d = this.getDateFromLink( rObj.link );
			o ='"' + o + '" [[' + rObj.person['label'] + ']] on ' + d.toDateString();
		}
		//could do ref or direct link:
		o += ' \'\'[' + $j.trim( rObj.link ) + ' source clip]\'\' ';

		//var o= '"' + o + '" by [[' + rObj.person['label'] + ']] '+
		//		'<ref>[' + rObj.link + ' Metavid Source Page] for ' + rObj.title +'</ref>';
		return o;
	},
	//give an updated start and end time updates the title and url
	applyVideoAdj: function( rObj ){
		js_log('mv ApplyVideoAdj::');
		//update the titleKey:
		rObj['titleKey'] =	 this.getTitleKey( rObj );

		//update the title:
		rObj['title'] = this.getTitle( rObj );

		//update the interface:
		js_log('update title to: ' + rObj['title']);
		$j('#rsd_resource_title').html( gM('rsd_resource_edit', rObj['title'] ) );

		//if the video is "roe" based select the ogg stream
		if( rObj.roe_url && rObj.pSobj.cp.stream_import_key){
			var source = $j('#embed_vid').get(0).media_element.getSourceById( rObj.pSobj.cp.stream_import_key );
			if(!source){
				js_error('Error::could not find source: ' +  rObj.pSobj.cp.stream_import_key);
			}else{
				rObj['src'] = source.getURI();
				js_log("g src_key: " + rObj.pSobj.cp.stream_import_key + ' src:' + rObj['src']) ;
				return true;
			}
		}
	},
	getEmbedHTML:function( rObj , options ){
	    if(!options)
		     options={};
		var id_attr = (options['id'])?' id = "' + options['id'] +'" ': '';
		var style_attr = (options['max_width'])?' style="width:'+options['max_width']+'px;"':'';
		//@@maybe check type here ?
		if(options['only_poster']){
			return '<img ' + id_attr + ' src="' + rObj['poster']+'" ' + style_attr + '>';
		}else{
			return '<video ' + id_attr + ' roe="' + rObj['roe_url'] + '"></video>';
		}
	},
	getImageTransform:function( rObj, opt ){
		if( opt.width <= 80 ){
			return getURLParamReplace( rObj.poster, { 'size' : "icon" } )
		}else if( opt.width <= 160 ){
			return getURLParamReplace( rObj.poster, { 'size' : "small" } )
		}else if( opt.width <= 320 ){
			return getURLParamReplace( rObj.poster, { 'size' : 'medium' } )
		}else if( opt.width <= 512 ){
			return getURLParamReplace( rObj.poster, { 'size' : 'large' } )
		}else{
			return getURLParamReplace( rObj.poster, { 'size' : 'full' } )
		}
	},
	getEmbedObjParsedInfo:function(rObj, eb_id){
		var sources = $j('#'+eb_id).get(0).media_element.getSources();
		rObj.other_versions ='*[' + rObj['roe_url'] + ' XML of all Video Formats and Timed Text]'+"\n";
		for(var i in sources){
			var cur_source = sources[i];
			//rObj.other_versions += '*['+cur_source.getURI() +' ' + cur_source.title +']' + "\n";
			if( cur_source.id ==  this.cp.target_source_id)
				rObj['url'] = cur_source.getURI();
		}
		//js_log('set url to: ' + rObj['url']);
		return rObj;
	},
	//update rObj for import:
	updateDataForImport:function( rObj ){
		rObj['author']='US Government';
		//convert data to UTC type date:
		var d = this.getDateFromLink( rObj.link );
		rObj['date'] =	 d.toDateString();
		rObj['license_template_tag']='PD-USGov';
		//update based on new start time:
		js_log('url is: ' + rObj.src + ' ns: ' + rObj.start_time + ' ne:' + rObj.end_time);

		return rObj;
	},
	getDateFromLink:function( link ){
		var dateExp = new RegExp(/_([0-9]+)\-([0-9]+)\-([0-9]+)/);
		var dParts = link.match (dateExp);
		var d = new Date();
		var year_full = (dParts[3].length==2)?'20'+dParts[3].toString():dParts[3];
		d.setFullYear(year_full, dParts[1]-1, dParts[2]);
		return d;
	}
}
