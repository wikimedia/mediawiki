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
			$("#mw-hidden-catlinks").before($('<a/>', {
			    id: 'mw-cat-toggle',
			    href: '#',
			    text: mw.msg('category-toggle-show')
			}).toggle(
			function(){
				$("#mw-hidden-catlinks").show();
				$("#mw-cat-toggle").text(mw.msg('category-toggle-hide'));
			},
			function(){
				$("#mw-hidden-catlinks").hide();
				$("#mw-cat-toggle").text(mw.msg('category-toggle-show'));
			}));
	}
	/* Add accesskey hints to the tooltips */
	mw.util.updateTooltipAccessKeys();

} );
