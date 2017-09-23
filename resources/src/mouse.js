/*
    Mouse position logging on button click event
*/
(function (mw, $) {
    $(function () {
        $("#wpCreateaccount").click(function (e) {
            var position = {
                xPosition: e.clientX,
                yPosition: e.clientY
            }
            mw.track("CaptchaFormData", position);
        });
    });
}(mediaWiki, jQuery));
