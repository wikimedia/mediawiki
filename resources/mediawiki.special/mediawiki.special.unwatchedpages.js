/*
 * JavaScript for Special:UnwatchedPages
 * 
 */
 
 //set the watch links to use Ajax
 //see bug #17367
 $(document).ready(function(){
	 $('.mw-watchLink a').click(function(){
		var api=new mw.Api();
		var watchedLi=$(this).parents('li');
		var link=$(this);
		var toWatch=(link.text()==mw.messages.get('watch'));
		if(toWatch){
			api.watch(this.title,function(){
				watchedLi.toggleClass('mw-watched-item');
				link.text(mw.messages.get('unwatch'));
			});
		}
		else{
			api.unwatch(this.title,function(){
				watchedLi.toggleClass('mw-watched-item');
				link.text(mw.messages.get('watch'));
			});
		}
		return false;
	 });
 });