(function(){ // hide everything externally to avoid name collisions

// whether to display debugging output
var svgDebug = true;

// whether we are locally debugging (i.e. the page is downloaded to our
// hard drive and served from a local server to ease development)
var localDebug = true;

// the full URL to where svg.js is located
// Note: Update this before putting on production
var svgSrcURL;
if (localDebug) {
  svgSrcURL = wgScriptPath + '/extensions/SVGZoom/src/svg.js';
} else {
  svgSrcURL = wgScriptPath + '/extensions/SVGZoom/src/svg.js';
}

// whether the pan and zoom UI is initialized
var svgUIReady = false;

// a reference to the SVG OBJECT on the page
var svgObject;

// a reference to our zoom and pan controls
var svgControls;

// a reference to our SVG root tag
var svgRoot;

var isWebkit = (Math.max(navigator.appVersion.indexOf('WebKit'), 
                         navigator.appVersion.indexOf('Safari'), 0));

var isFF = false;
if (navigator.userAgent.indexOf('Gecko') >= 0) {
  isFF = parseFloat(navigator.userAgent.split('Firefox/')[1]) || undefined;
}                         

// the URL to the proxy from which we can fetch SVG images within the same
// domain as this page is served from
// TODO: define once a proxy or API call is setup

// the location of our images
var imageBundle;
if (localDebug) {
  // for local debugging
  var imageRoot = '/images/';
  imageBundle = {
    'searchtool': imageRoot + 'searchtool.png',
    'controls-north-mini': imageRoot + 'north-mini.png',
    'controls-west-mini': imageRoot + 'west-mini.png',
    'controls-east-mini': imageRoot + 'east-mini.png',
    'controls-south-mini': imageRoot + 'south-mini.png',
    'controls-zoom-plus-mini': imageRoot + 'zoom-plus-mini.png',
    'controls-zoom-world-mini': imageRoot + 'zoom-world-mini.png',
    'controls-zoom-minus-mini': imageRoot + 'zoom-minus-mini.png'
  };
} else {
  // Note: update this before putting on production
  var imageRoot = wgScriptPath + '/extensions/SVGZoom/images/';
  imageBundle = {
    'searchtool': imageRoot + 'searchtool.png',
    'controls-north-mini': imageRoot + 'north-mini.png',
    'controls-west-mini': imageRoot + 'west-mini.png',
    'controls-east-mini': imageRoot + 'east-mini.png',
    'controls-south-mini': imageRoot + 'south-mini.png',
    'controls-zoom-plus-mini': imageRoot + 'zoom-plus-mini.png',
    'controls-zoom-world-mini': imageRoot + 'zoom-world-mini.png',
    'controls-zoom-minus-mini': imageRoot + 'zoom-minus-mini.png'
  };
}

// determines if we are at a Wikimedia Commons detail page for an SVG file
function isSVGPage() {
  if (wgNamespaceNumber == 6 && wgTitle && wgTitle.indexOf('.svg') != -1
      && wgAction == 'view') {
    return true;
  } else {
    return false;
  }
}

// Determines whether this page has image annotation enabled (i.e. the
// Add Note button). If this is enabled the DOM changes slightly and we have
// to account for it. Some SVG images have this (Tux.svg); others don't
// (Commons-logo.svg, for example).
function hasAnnotation() {
  if (document.getElementById('ImageAnnotationAddButton')) {
    return true;
  } else {
    return false;
  }
}

// inserts the SVG Web library into the page
function insertSVGWeb() {
   document.write('<script type="text/javascript" '
                  + 'src="' + svgSrcURL + '" '
                  + 'data-path="../../../../src" ' /* Note: remove before production */
                  + 'data-debug="' + svgDebug + '"></script>');
}

// adds a button that when pressed turns on the zoom and pan UI
function addStartButton() {
   // are we already present? user could have hit back button on an old 
   // loaded page
   if (document.getElementById('SVGZoom.startButton')) {
     return;
   }

   // insert ourselves beside the SVG thumbnail area
   var info = getSVGInfo();
   var thumbnail = info.fileNode;
   if (hasAnnotation()) {
     thumbnail = thumbnail.childNodes[0].childNodes[0];
   }
   // make the container element we will go into a bit larger to accommodate 
   // the icon
   var infoWidth = Number(String(info.width).replace('px', ''));
   thumbnail.style.width = (infoWidth + 30) + 'px';
   var img = document.createElement('img');
   img.id = 'SVGZoom.startButton';
   img.src = imageBundle['searchtool'];
   img.setAttribute('width', '30px');
   img.setAttribute('height', '30px');
   img.style.position = 'absolute';
   img.style.cursor = 'pointer';
   img.onclick = initUI;
   // some SVG pages have a spurious <br/> element; add before that
   if (thumbnail.lastChild.nodeType == 1 
       && thumbnail.lastChild.nodeName.toLowerCase() == 'br') {
      thumbnail.insertBefore(img, thumbnail.lastChild);
   } else {
      thumbnail.appendChild(img);
   }
}

// adds the pan and zoom UI and turns the PNG into an SVG object
function initUI() {
  if (svgUIReady) { // already initialized
    return;
  }
  
  svgUIReady = true;
  
  // remove magnifying glass icon
  var startButton = document.getElementById('SVGZoom.startButton');
  startButton.parentNode.removeChild(startButton);
  
  // get the thumbnail container and make it invisible
  var info = getSVGInfo();
  var thumbnail = info.fileNode;
  if (hasAnnotation()) {
    thumbnail = thumbnail.childNodes[0].childNodes[0];
  }
  var oldPNG = thumbnail.childNodes[0];
  oldPNG.style.visibility = 'hidden';
  oldPNG.style.zIndex = -1000;
  
  // store a reference to the SVG root to make subsequent accesses faster
  svgRoot = svgObject.contentDocument.rootElement;
  
  // Safari/Native has a bug where it doesn't respect the height/width of 
  // the OBJECT when scaling the size of some objects (commons-logo.svg, 
  // for example). A workaround is to manually set the size inside the SVG.
  if (isWebkit) {
    svgRoot.setAttribute('width', info.width);
    svgRoot.setAttribute('height', info.height);
  }
  
  // reveal the SVG object and controls
  svgObject.parentNode.style.zIndex = 1000;
  svgControls.style.display = 'block';

  // make the cursor a hand when over the SVG; not all browsers support
  // this property yet
  svgRoot.setAttribute('cursor', 'pointer');
  // TODO: Get hand cursor showing up in SVG Web's Flash renderer
  
  // add drag listeners on the SVG root
  svgRoot.addEventListener('mousedown', mouseDown, false);
  svgRoot.addEventListener('mousemove', mouseMove, false);
  svgRoot.addEventListener('mouseup', mouseUp, false);
}

// Creates the SVG OBJECT during page load so that when we swap the PNG
// thumbnail and the SVG OBJECT it happens much faster
function createSVGObject() {
  var info = getSVGInfo();
  var thumbnail = info.fileNode;
  if (hasAnnotation()) {
    thumbnail = thumbnail.childNodes[0].childNodes[0];
  }
  
  // create the SVG OBJECT that will replace our thumbnail container
  var obj = document.createElement('object', true);
  obj.setAttribute('type', 'image/svg+xml');
  obj.setAttribute('data', info.url);
  obj.setAttribute('width', info.width);
  obj.setAttribute('height', info.height);
  obj.addEventListener('load', function() {
    // store a reference to the SVG OBJECT
    svgObject = this;
    
    // create the controls
    svgControls = createControls();
    svgControls.style.display = 'none';
    
    // now place the controls on top of the SVG object
    if (thumbnail.lastChild.nodeType == 1 
        && thumbnail.lastChild.nodeName.toLowerCase() == 'br') {
       thumbnail.insertBefore(svgControls, thumbnail.lastChild);
    } else {
       thumbnail.appendChild(svgControls);
    }
    
    // add our magnification icon
    addStartButton();
    
    // set up the mouse scroll wheel; FF 3.5/Native has an annoying bug
    // where DOMMouseScroll events do not propagate to OBJECTs under
    // some situations!
    if (isFF && svgweb.getHandlerType() == 'native') {
      hookEvent(svgObject.contentDocument.rootElement, 'mousewheel',
                MouseWheel);
    } else {
      hookEvent('file', 'mousewheel', MouseWheel);
    }
    
    // prevent IE memory leaks
    thumbnail = obj = null;
  }, false);
  // ensure that the thumbnail container has relative positioning; this will
  // reset our absolutely positioned elements to be relative to our parent
  // so we have correct coordinates
  thumbnail.style.position = 'relative';
  // position object behind the PNG image; do it in a DIV to avoid any
  // strange style + OBJECT interactions
  var container = document.createElement('div');
  container.id = 'SVGZoom.container';
  container.style.zIndex = -1000;
  container.style.position = 'absolute';
  // FIXME: This is a hack; figure out why the Flash version of Commons-logo.svg
  // is off by one 1 pixel on x and y
  if (!hasAnnotation() && svgweb.getHandlerType() == 'flash') {
    container.style.top = '-1px';
    container.style.left = '-1px';
  } else {
    container.style.top = '0px';
    container.style.left = '0px';
  }
  if (thumbnail.lastChild.nodeType == 1 
      && thumbnail.lastChild.nodeName.toLowerCase() == 'br') {
    thumbnail.insertBefore(container, thumbnail.lastChild);
  } else {
    thumbnail.appendChild(container);
  }
  svgweb.appendChild(obj, container);
}

// Returns a DIV ready to append to the page with our zoom and pan controls
function createControls() {
  var controls = document.createElement('div');
  controls.id = 'SVGZoom.controls';
  controls.innerHTML = 
    '<div style="position: absolute; left: 4px; top: 4px; z-index: 1004;" unselectable="on">'
    + '<div id="SVGZoom.panup" style="position: absolute; left: 13px; top: 4px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.panup.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-north-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.panleft" style="position: absolute; left: 4px; top: 22px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.panleft.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-west-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.panright" style="position: absolute; left: 22px; top: 22px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.panright.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-east-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.pandown" style="position: absolute; left: 13px; top: 40px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.pandown.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-south-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.zoomin" style="position: absolute; left: 13px; top: 63px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.zoomin.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-zoom-plus-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.zoomworld" style="position: absolute; left: 13px; top: 81px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.zoomworld.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-zoom-world-mini'] + '"/>'
    + '</div>'
    + '<div id="SVGZoom.zoomout" style="position: absolute; left: 13px; top: 99px; width: 18px; height: 18px;">'
    + '  <img id="SVGZoom.zoomout.innerImage" style="position: relative; width: 18px; height: 18px;" '
          + 'src="' + imageBundle['controls-zoom-minus-mini'] + '"/>'
    + '</div>'
    + '</div>';
    
  // attach event handlers
  controls.childNodes[0].childNodes[0].onclick = panUp;
  controls.childNodes[0].childNodes[1].onclick = panLeft;
  controls.childNodes[0].childNodes[2].onclick = panRight;
  controls.childNodes[0].childNodes[3].onclick = panDown;
  controls.childNodes[0].childNodes[4].onclick = zoomIn;
  controls.childNodes[0].childNodes[5].onclick = zoomWorld;
  controls.childNodes[0].childNodes[6].onclick = zoomOut;
  
  return controls;
}

function panUp() {
  svgRoot.currentTranslate.setY(svgRoot.currentTranslate.getY() - 25);
}

function panLeft() {
  svgRoot.currentTranslate.setX(svgRoot.currentTranslate.getX() - 25);
}

function panRight() {
  svgRoot.currentTranslate.setX(svgRoot.currentTranslate.getX() + 25);
}

function panDown() {
  svgRoot.currentTranslate.setY(svgRoot.currentTranslate.getY() + 25);
}

function zoomIn() {
  svgRoot.currentScale = svgRoot.currentScale * 1.1;
}

function zoomWorld() {
  svgRoot.currentScale = 1;
  svgRoot.currentTranslate.setXY(0, 0);
}

function zoomOut() {
  svgRoot.currentScale = svgRoot.currentScale / 1.1;
}

// variables used for dragging
var x, y, rootX, rootY;
var dragX = 0, dragY = 0;
var dragging = false;
 
function mouseDown(evt) {
  dragging = true;
  
  x = evt.clientX;
  y = evt.clientY;
  
  rootX = svgRoot.currentTranslate.getX();
  rootY = svgRoot.currentTranslate.getY();
    
  evt.preventDefault(true);
}

function mouseMove(evt) {
  if (!dragging) {
     return;
  }
  
  dragX = evt.clientX - x;
  dragY = evt.clientY - y;
  
  // Firefox and Webkit differ on the coordinates they return; Firefox
  // returns the mouse coordinates with no scaling, while Webkit and SVG Web
  // scale the mouse coordinates using the currentScale
  if (isWebkit || svgweb.getHandlerType() == 'flash') {
    dragX /= svgRoot.currentScale;
    dragY /= svgRoot.currentScale;
  }
  
  var newX = rootX + dragX;
  var newY = rootY + dragY;
    
  svgRoot.currentTranslate.setXY(newX, newY);
  
  evt.preventDefault(true);
}

function mouseUp(evt) {
  dragging = false;
  
  evt.preventDefault(true);
}

// Returns a data structure that has info about the SVG file on this page, including:
// url - filename and URL necessary to fetch the SVG file
// width and height - the width and height to make the SVG file
// fileNode - the DOM node that has the top level PNG thumbnail in it to replace
// imgNode - the actual IMG tag that has the PNG thumbnail inside of it
// Note that this method returns null if there is no file node on the page.
function getSVGInfo() {
  var fileNode = document.getElementById('file');
  if (!fileNode) {
    return null;
  }
  
  var url;
  if (hasAnnotation()) {
    url = fileNode.childNodes[0].childNodes[0].childNodes[0].href;
  } else {
    url = fileNode.childNodes[0].href;
  }
  var imgNode = fileNode.getElementsByTagName('img')[0];
  var width = imgNode.getAttribute('width');
  var height = imgNode.getAttribute('height');

  return { url: url, fileNode: fileNode, imgNode: imgNode, 
           width: width, height: height };
}

// Mousewheel Scrolling thanks to
// http://blog.paranoidferret.com/index.php/2007/10/31/javascript-tutorial-the-scroll-wheel/
function hookEvent(element, eventName, callback) {
  if (typeof(element) == 'string') {
    element = document.getElementById(element);
  }
  
  if (element == null) {
    return;
  }
  
  if (element.addEventListener) {
    if (eventName == 'mousewheel') {
      element.addEventListener('DOMMouseScroll', callback, false);  
    }
    element.addEventListener(eventName, callback, false);
  } else if (element.attachEvent) {
    element.attachEvent("on" + eventName, callback);
  }
}

function MouseWheel(e) {
  e = e ? e : window.event;

  var wheelData = e.detail ? e.detail * -1 : e.wheelDelta;
  
  if (wheelData > 0) {
    zoomIn();
  } else {
    zoomOut();
  }
  
  if (e.preventDefault) {
    e.preventDefault();
  }
  
  return false;
} 

// called when the page is loaded and ready to be manipulated
function pageLoaded() {
  svgweb.addOnLoad(createSVGObject);
}

if (isSVGPage()) {
  insertSVGWeb();
  addOnloadHook(pageLoaded);
}

// hide internal implementation details inside of a closure
})();
