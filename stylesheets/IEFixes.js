// IE fix javascript

// png alpha transparency fixes
window.attachEvent("onload", fixalpha);

function fixalpha(){
    // bg
    var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
    if (rslt != null && Number(rslt[1]) >= 5.5)
    {
        var logoa = document.getElementById('portlet-logo').firstChild;
        var bg = logoa.currentStyle.backgroundImage;
        if (bg.match(/\.png/i) != null){
            var mypng = bg.substring(5,bg.length-2);
            logoa.style.backgroundImage = "none";
            logoa.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+mypng+"', sizingMethod='image')";
        }


    
    /*
        for (i=0; i<document.all.length; i++){
            if(document.all[i].currentStyle && document.all[i].currentStyle.backgroundImage) {
                var bg = document.all[i].currentStyle.backgroundImage;
            } else {
                bg = false;
            }
            if (bg){
                if (bg.match(/\.png/i) != null){
                    var mypng = bg.substring(5,bg.length-2);
                    document.all[i].style.backgroundImage = "none";
                    document.all[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+mypng+"', sizingMethod='image')";
                }
            }
        }*/
    }
}
