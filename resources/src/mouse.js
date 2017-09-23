/*
    Mouse position logging on button click event
*/
(function (mw, $) {
    $(function () {
        //Since this is a separate module, the following code is generic for 
        //all submit buttons. 
        $('button').click('submit', (function (e) {
            var position = {
                xPosition = e.clientX,
                yPosition = e.clientY
            }
            console.log(xPosition + "   " + yPosition);
            mw.track("CaptchaFormData", position);
        }));
    });
}(mediaWiki, jQuery));
