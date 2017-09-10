function printMousePos(event) {

	var clientX = event.clientX
	var clientY = event.clientY

	console.log(clientX,clientY)

	return {X:clientX,Y:clientY}

}

var mousePosition = document.addEventListener("click", printMousePos);

mw.track( 'mouse.position', mousePosition);