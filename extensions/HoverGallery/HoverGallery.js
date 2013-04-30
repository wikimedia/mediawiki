$(document).ready(function(){

	var thumbs, mouseX, mouseY, clientWidth, clientHeight, fullImage, fullImageX, fullImageY, fullImageWidth, fullImageHeight;

	// Get all the thumbnails
	// The normal gallery is right before the hidden gallery, so just move to it and select all the images it contains
	thumbs = $('div.hover-gallery').prev().find('img');


	thumbs.mousemove(function(event){

		// Determine which of the thumbs is it
		var thumbIndex = $.inArray( this, thumbs );

		// Get the corresponding full-size image
		fullImage = $('div.hover-gallery').children().eq( thumbIndex );

		// Calculate the position of the mouse
		mouseX = event.clientX;
		mouseY = event.clientY;
		
		// Now the position of the top left corner of the full image
		fullImageX = mouseX + 10;
		fullImageY = mouseY + 10;

		// If the mouse is very near the border, move the full image to the other side
		clientWidth = document.body.clientWidth;
		clientHeight = document.body.clientHeight;
		fullImageWidth = fullImage.width();
		fullImageHeight = fullImage.height();
		if ( mouseX + fullImageWidth > clientWidth ) {
			fullImageX = mouseX - fullImageWidth - 10;
		}
		if ( mouseY + fullImageHeight > clientHeight ) {
			fullImageY = mouseY - fullImageHeight - 10;
		}

		// Show the image
		fullImage.css({ 'top': fullImageY, 'left': fullImageX }).show();

	}).mouseleave(function(){
		fullImage.hide();
	});
});