// IE fix javascript

// png alpha transparency fixes
window.attachEvent("onload", fixalpha);

function fixalpha(){
    // bg
    var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
    if (rslt != null && Number(rslt[1]) >= 5.5)
    {
        for (i=0; i<document.all.length; i++){
            var bg = document.all[i].currentStyle.backgroundImage;
            if (bg){
                if (bg.match(/\.png/i) != null){
                    var mypng = bg.substring(5,bg.length-2);
                    document.all[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+mypng+"', sizingMethod='image')";
                    document.all[i].style.backgroundImage = "none";
                }
            }
        }
    }
}
