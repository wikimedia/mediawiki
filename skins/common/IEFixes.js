// IE fix javascript
var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
if (rslt != null ) var version = Number(rslt[1]);
else var version = 0;

window.attachEvent("onload", hookit);
function hookit() {
    fixalpha();
    relativeforfloats();
}

// png alpha transparency fixes
function fixalpha(){
    // bg
    if(version >= 5.5) {
        var logoa = document.getElementById('p-logo').firstChild;
        var bg = logoa.currentStyle.backgroundImage;
        if (bg.match(/\.png/i) != null){
            var mypng = bg.substring(5,bg.length-2);
            logoa.style.backgroundImage = "none";
            logoa.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+mypng+"', sizingMethod='crop')";
        }
    }
}

// fix ie6 disappering float bug
function relativeforfloats() {
    var bc = document.getElementById('bodyContent');
    if (bc) {
        var tables = bc.getElementsByTagName('table');
        var divs = bc.getElementsByTagName('div');
    }
    setrelative(tables);
    setrelative(divs);
}
function setrelative (nodes) {
    var i = 0;
    while (i < nodes.length) {
        if(((nodes[i].style.float && nodes[i].style.float != ('none') ||
        (nodes[i].align && nodes[i].align != ('none'))) &&
        (!nodes[i].style.position || nodes[i].style.position != 'relative'))) 
        {
            nodes[i].style.position = 'relative';
        }
        i++;
    }
}
