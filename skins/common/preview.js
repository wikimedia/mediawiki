/**
 * Live preview script for MediaWiki
 */

function doLivePreview( e ) {
	e.preventDefault();
	var previewText = $j('#wpTextbox1').val();

	var editToken = $j( '[name="wpEditToken"]' ).attr( 'value' );
	var editTime = $j( '[name="wpEdittime"]' ).attr( 'value' );
	var startTime = $j( '[name="wpStarttime"]' ).attr( 'value' );

	var postData = { 'action' : 'submit', 'wpTextbox1' : previewText, 'wpPreview' : true,
		'wpEditToken' : editToken, 'wpEdittime': editTime, 'wpStarttime': startTime, 'title' : wgPageName };
	
	// Hide active diff, used templates, old preview if shown
	var copyElements = ['#wikiPreview', '.templatesUsed', '.hiddencats',
						'#catlinks'];
	var copySelector = copyElements.join(',');

	$j.each( copyElements, function(k,v) { $j(v).fadeOut('fast'); } );
	
	// Display a loading graphic
	var loadSpinner = $j('<div class="mw-ajax-loader"/>');
	$j('#wikiPreview').before( loadSpinner );
	
	var page = $j('<div/>');
	page.load( wgScript+'?action=submit '+copySelector,
				postData,
		function() {
			
			for( var i=0; i<copyElements.length; ++i) {
				// For all the specified elements, find the elements in the loaded page
				//  and the real page, empty the element in the real page, and fill it
				//  with the content of the loaded page
				var copyContent = page.find( copyElements[i] ).contents();
				$j(copyElements[i]).empty().append( copyContent );
				var newClasses = page.find( copyElements[i] ).attr('class');
				$j(copyElements[i]).attr( 'class', newClasses );
			}
			
			$j.each( copyElements, function(k,v) {
				// Don't belligerently show elements that are supposed to be hidden
				$j(v).fadeIn( 'fast', function() { $j(this).css('display', ''); } );
			} );
			
			loadSpinner.remove();
		} );
}

$j(document).ready( function() {
	$j('#wpPreview').click( doLivePreview );
} );
