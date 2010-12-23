/*
 * Scripts common to all skins
 */

/**
 * Collapsible tables and divs.  
 * 
 * Users can create tables and nested divs which collapse either on-click or on-load,
 * to save space on a page, or to conceal information at first sight.  Eg:
 * 
 *   <table class="wikitable collapsible collapsed"><tr>
 *   	<th>A table header which will always be visible</th>
 *   </tr><tr>
 *   	<td>A table cell which will be hidden on page-load</td>
 *   </tr></table>
 *   
 *   <div class="collapsible collapsed">
 *   	Content which will always be visible 
 *   	<span class="collapsible-expander">Click me!</span>
 *   	<div>
 *   		This content will be hidden until the span above is clicked
 *   	</div>
 *   </div>
 *   
 * If the user does not create a toggle-button manually, one will be created, 
 * in the top-right header cell for tables, and at the beginning of the outer
 * div's content for a collapsible div.
 * @author Happy-melon
 */
$('.collapsible').each( function(){
	var $x = $j(this);
	if( $('.collapsible-expander', $x).size() ){
		$('.collapsible-expander', $x)
			.click(function(e, rmClass){
				e.preventDefault();
				rmClass = !(rmClass == false);
				$(this)
					.toggleClass('show')
					.trigger('mw-toggle-collapse', [rmClass]);
				return false;
			});
	} else {
		var $expander = $j('<div class="collapsible-expander">')
			.text( '[' + mediaWiki.msg( 'hide' ) + ']' )
			.click(function(e, rmClass){
				rmClass = !(rmClass == false);
				e.preventDefault();
				$(this)
					.toggleClass('show')
					.trigger('mw-toggle-collapse', [rmClass]) 
					.text(
						'[' + 
						($(this).is('.collapsible.collapsed .collapsible-expander') 
							? mediaWiki.msg( 'show' )
							: mediaWiki.msg( 'hide' )) + 
						']'
					);
				return false;
			});
		if( $x.is('div.collapsible')){
			$x.prepend($expander);
		} else {
			$('tr:first th:last',$x).append($expander);
		}
	}
	
});

/**
 * This is a bit nasty because it also toggles any nested
 * collapsible objects.  But it would be a nightmare to only
 * select the outer collapser without adding ids everywhere.
 */
$('table.collapsible').live( 'mw-toggle-collapse', function(e, rmClass){
	e.stopPropagation();
	var time = rmClass ? 500 : 0;
	$('tr:gt(0)',$(this))
		// We'd like to use slideToggle() like for divs, but the jQuery 
		// slide animation for table rows is just *pig ugly*...
		.toggle("fade", { direction: "vertical" }, time);
	if( rmClass ){
		$('table.collapsible',$(this)).andSelf().toggleClass('collapsed');
	}
	return false;
});

$('div.collapsible').live( 'mw-toggle-collapse', function(e, rmClass){
	e.stopPropagation();
	var time = rmClass ? 500 : 0;
	$(this).children(':not(.collapsible-expander)')
		.slideToggle(time);
	if( rmClass ){
		$('div.collapsible',$(this)).andSelf().toggleClass('collapsed');
	}
	return false;
});

/**
 * Here we want to collapse .collapsible-expander buttons whose closest
 * div.collapsible parent wants to be collapsed on first view
 */
$('.collapsible-expander').filter(function(){
	return $(this).closest('.collapsible').is('.collapsible.collapsed')
}).trigger( 'click', [false] );



