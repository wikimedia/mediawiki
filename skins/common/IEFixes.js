// IE fixes javascript

var isMSIE55 = (window.showModalDialog && window.clipboardData && window.createPopup);

if (document.attachEvent)
  document.attachEvent('onreadystatechange', hookit);

function hookit() {
    if (document.getElementById && document.getElementById('bodyContent')) {
        fixalpha();
        relativeforfloats();
    }
}

// png alpha transparency fixes
function fixalpha() {
    // bg
    if (isMSIE55) {
        var plogo = document.getElementById('p-logo');
        var logoa = plogo.getElementsByTagName('a')[0];
        var bg = logoa.currentStyle.backgroundImage;
        var imageUrl = bg.substring(5, bg.length-2);

        if (imageUrl.substr(imageUrl.length-4).toLowerCase() == '.png') {
            var logospan = logoa.appendChild(document.createElement('span'));
           
            logoa.style.backgroundImage = 'none';
            logospan.style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src='+imageUrl+')';
            logospan.style.height = '100%';
            logospan.style.position = 'absolute';
            logospan.style.width = '100%';
            logospan.style.cursor = 'hand';
            // Center image with hack for IE5.5
            logospan.style.left = '50%';
            logospan.style.setExpression('marginLeft', '"-" + (this.offsetWidth / 2) + "px"');
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
