/*
    Mouse position logging on button click event
*/
( function ( mw, $ ) {
    $( function () {
        $("#wpCreateaccount").click(function(e){
                var xPosition = e.clientX;
                var yPosition = e.clientY;
                console.log(xPosition + "   " + yPosition);
                mw.track('mouse.click.position' , "(" +xPosition + ","+ yPosition+ ")");
        });
    })
}( mediaWiki, jQuery ));