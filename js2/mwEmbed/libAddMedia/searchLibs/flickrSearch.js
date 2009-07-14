var flickrOrgSearch = function ( iObj){
	return this.init( iObj );
}
flickrOrgSearch.prototype = {
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
	getSearchResults:function(){
		//call parent: 
		this.parent_getSearchResults();
		//setup the flickr request: 
	}
}