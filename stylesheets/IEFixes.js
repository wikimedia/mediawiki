// IE fix javascript
var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
if (rslt != null ) var version = Number(rslt[1]);
else var version = 0;

window.attachEvent("onload", hookit);
function hookit() {
    fixalpha();
    if(version == 6) {
        relativeforfloats();
        fixtextarea();
        var wrapper = document.getElementById('tawrapper');
        if(wrapper) {
            //wrapper.attachEvent("onclick", fixtextarea);
            window.onresize = refixtextarea;
        }
    }
}

// png alpha transparency fixes
function fixalpha(){
    // bg
    if(version >= 5.5) {
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

function fixtextarea() {
    var wrapper = document.getElementById('tawrapper');
    if(wrapper) {
            wrapper.style.width = 'auto';
            wrapper.style.width = (wrapper.offsetWidth - 4)  + 'px';
    }
}
function refixtextarea () {
    setTimeout("fixtextarea()",10);
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
        if(nodes[i].style.float != ('none'|null) ||
                nodes[i].align != (''|null|'none'))
            nodes[i].style.position = 'relative';
        i++;
    }
}
