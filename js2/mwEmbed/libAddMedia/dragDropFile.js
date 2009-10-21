/* firefox 3.6 drag-drop uploading 
*/
var TCNDDU = TCNDDU || {};
		
(function( $ ) {
	$.dragDropFile = function ( selector ) {
		//setup drag binding and highlight
		var dC = $j( selector ).get(0);
		dC.addEventListener("dragenter", 
			function(event){
				$j(dC).css('border', 'solid red');
				event.stopPropagation();
				event.preventDefault();
			}, false);
		dC.addEventListener("dragleave", 
			function(event){
				//default textbox css (should be an easy way to do this)
				$j(dC).css('border', '');
				event.stopPropagation(); 
				event.preventDefault();
			}, false);
		dC.addEventListener("dragover", 
			function(event){						
				event.stopPropagation(); 
				event.preventDefault();
			}, false);
		dC.addEventListener("drop", 
			function( event ){															
				//for some reason scope does not persist for events so here we go: 
				doDrop( event );
				//handle the drop loader and call event 
				
			}, false);
	function doDrop(event){		
		var dt = event.dataTransfer,
			files = dt.files,
			imgPreviewFragment = document.createDocumentFragment(),
			count = files.length,
			domElements;
			
		event.stopPropagation();
		event.preventDefault();
		// ( error out if they dragged multiple files for now) 
		if( files.length > 1 ){
			js_log( 'errro multiple files');
			
			return false;
		}
		for (var i = 0; i < count; i++) {
			if(files[i].fileSize < 1048576) {
				domElements = [
					document.createElement('li'),
					document.createElement('a'),
					document.createElement('img')
				];
			
				domElements[2].src = files[i].getAsDataURL(); // base64 encodes local file(s)
				domElements[2].width = 300;
				domElements[2].height = 200;
				domElements[1].appendChild(domElements[2]);
				domElements[0].id = "item"+i;
				domElements[0].appendChild(domElements[1]);
				
				imgPreviewFragment.appendChild(domElements[0]);
				
				dropListing.appendChild(imgPreviewFragment);
				
				TCNDDU.processXHR(files.item(i), i);
			} else {
				alert("file is too big, needs to be below 1mb");
			}	
		}
	}
	
})(window.$mw);
