/*
    Mouse position logging on button click event
*/
(function (mw, $) {
    $(function () {
        $("#wpCreateaccount").click(function (e) {
            var position = {
                mouseClickX  : e.clientX,
                mouseClickY	 : e.clientY
            }
            mw.track("event.CaptchaFormData", position);
        });
    });
}(mediaWiki, jQuery));
