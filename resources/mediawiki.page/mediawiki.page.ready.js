jQuery( document ).ready( function( $ ) {

	/* Emulate placeholder if not supported by browser */
	if ( !( 'placeholder' in document.createElement( 'input' ) ) ) {
		$( 'input[placeholder]' ).placeholder();
	}

	/* Enable makeCollapsible */
	$( '.mw-collapsible' ).makeCollapsible();

	/* Lazy load jquery.tablesorter */
	if ( $( 'table.sortable' ).length ) {
		mw.loader.using( 'jquery.tablesorter', function() {
			$( 'table.sortable' ).tablesorter();
		});
	}

	/* Enable CheckboxShiftClick */
	$( 'input[type=checkbox]:not(.noshiftselect)' ).checkboxShiftClick();
	
	/* Enable toggle category link if at all the page has hidden category links */
	if($("#mw-hidden-catlinks").length>0)
	{
			jQuery('<a/>', {
			    id: 'mw-cat-toggle',
			    href: '#',
			    text: mw.msg('category-toggle-show'),
			    click: handler
			}).prependTo('#catlinks');
	}
	var toggleCatBool = true;
	function handler(event){
		$("#mw-hidden-catlinks").toggle();
		if(toggleCatBool)
		{
			$("#mw-cat-toggle").text(mw.msg('category-toggle-hide'));
		}
		else
		{
			$("#mw-cat-toggle").text(mw.msg('category-toggle-show'));
		}
		toggleCatBool=!toggleCatBool;
		event.preventDefault();
	}	 
	/* Add accesskey hints to the tooltips */
	mw.util.updateTooltipAccessKeys();

} );
