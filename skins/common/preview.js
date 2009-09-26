/**
 * Live preview script for MediaWiki
 */

function setupLivePreview() {
	var livePreviewButton = $j('#wpLivePreview');

	$j('#wpPreview').hide();
	livePreviewButton.show();

	livePreviewButton.click( doLivePreview );
}

function doLivePreview( e ) {
	e.preventDefault();
	var previewText = $j('#wpTextbox1').val();

	var editToken = $j( '[name="wpEditToken"]' ).attr( 'value' );
	var editTime = $j( '[name="wpEdittime"]' ).attr( 'value' );
	var startTime = $j( '[name="wpStarttime"]' ).attr( 'value' );

	var postData = { 'action' : 'submit', 'wpTextbox1' : previewText, 'wpPreview' : true,
		'wpEditToken' : editToken, 'wpEdittime': editTime, 'wpStarttime': startTime, 'title' : wgPageName };
	
	// Hide active diff, used templates, old preview if shown
	$j('#wikiDiff').slideUp();
	$j('#wikiPreview').slideUp();
	$j('.templatesUsed').slideUp();
	$j('.hiddencats').slideUp();
	
	// Display a loading graphic
	var loadSpinner = $j('<div class="mw-ajax-loader"/>');
	$j('#wikiPreview').before( loadSpinner );
	
	var page = $j('<html/>');
	page.load( wgScript+'?action=submit',
				postData,
		function() {
			var copyElements = ['#wikiPreview', '.templatesUsed', '.hiddencats',
								'#catlinks'];
			
			for( var i=0; i<copyElements.length; ++i) {
				// For all the specified elements, find the elements in the loaded page
				//  and the real page, empty the element in the real page, and fill it
				//  with the content of the loaded page
				var copyContent = page.find( copyElements[i] ).contents();
				$j(copyElements[i]).empty().append( copyContent );
				var newClasses = page.find( copyElements[i] ).attr('class');
				$j(copyElements[i]).attr( 'class', newClasses );
			}
			
			loadSpinner.remove();
			
			$j('#wikiPreview').slideDown();
		} );
}

js2AddOnloadHook( setupLivePreview );
