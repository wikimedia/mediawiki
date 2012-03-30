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
	if($("#catlinks").has("#mw-hidden-catlinks"))
	{
			jQuery('<a/>', {
			    id: 'mw-cat-toggle',
			    href: 'javascript:void(0)',
			    text: mw.msg('category-toggle','Show'),
			    click: handler
			}).prependTo('#catlinks');
	}
	var toggleCatBool = true;
	function handler(event){
		$("#mw-hidden-catlinks").toggle();
		if(toggleCatBool)
			$(this).text(mw.msg('category-toggle','Hide'));
		else
			$(this).text(mw.msg('category-toggle','Show'));
		toggleCatBool=!toggleCatBool;
		event.preventDefault();
		
	};
	
	/* Add accesskey hints to the tooltips */
	mw.util.updateTooltipAccessKeys();

} );
