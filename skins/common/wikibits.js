// MediaWiki JavaScript support functions

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var is_gecko = ((clientPC.indexOf('gecko')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('khtml') == -1) && (clientPC.indexOf('netscape/7.0')==-1));
var is_safari = ((clientPC.indexOf('applewebkit')!=-1) && (clientPC.indexOf('spoofer')==-1));
var is_khtml = (navigator.vendor == 'KDE' || ( document.childNodes && !document.all && !navigator.taintEnabled ));
// For accesskeys
var is_ff2_win = (clientPC.indexOf('firefox/2')!=-1 || clientPC.indexOf('minefield/3')!=-1) && clientPC.indexOf('windows')!=-1;
var is_ff2_x11 = (clientPC.indexOf('firefox/2')!=-1 || clientPC.indexOf('minefield/3')!=-1) && clientPC.indexOf('x11')!=-1;
if (clientPC.indexOf('opera') != -1) {
	var is_opera = true;
	var is_opera_preseven = (window.opera && !document.childNodes);
	var is_opera_seven = (window.opera && document.childNodes);
}

// Global external objects used by this script.
/*extern ta, stylepath, skin */

// add any onload functions in this hook (please don't hard-code any events in the xhtml source)
var doneOnloadHook;

if (!window.onloadFuncts) {
	var onloadFuncts = [];
}

function addOnloadHook(hookFunct) {
	// Allows add-on scripts to add onload functions
	onloadFuncts[onloadFuncts.length] = hookFunct;
}

function hookEvent(hookName, hookFunct) {
	if (window.addEventListener) {
		window.addEventListener(hookName, hookFunct, false);
	} else if (window.attachEvent) {
		window.attachEvent("on" + hookName, hookFunct);
	}
}

// document.write special stylesheet links
if (typeof stylepath != 'undefined' && typeof skin != 'undefined') {
	if (is_opera_preseven) {
		document.write('<link rel="stylesheet" type="text/css" href="'+stylepath+'/'+skin+'/Opera6Fixes.css">');
	} else if (is_opera_seven) {
		document.write('<link rel="stylesheet" type="text/css" href="'+stylepath+'/'+skin+'/Opera7Fixes.css">');
	} else if (is_khtml) {
		document.write('<link rel="stylesheet" type="text/css" href="'+stylepath+'/'+skin+'/KHTMLFixes.css">');
	}
}

if (wgBreakFrames) {
	// Un-trap us from framesets
	if (window.top != window) {
		window.top.location = window.location;
	}
}

// for enhanced RecentChanges
function toggleVisibility(_levelId, _otherId, _linkId) {
	var thisLevel = document.getElementById(_levelId);
	var otherLevel = document.getElementById(_otherId);
	var linkLevel = document.getElementById(_linkId);
	if (thisLevel.style.display == 'none') {
		thisLevel.style.display = 'block';
		otherLevel.style.display = 'none';
		linkLevel.style.display = 'inline';
	} else {
		thisLevel.style.display = 'none';
		otherLevel.style.display = 'inline';
		linkLevel.style.display = 'none';
	}
}

function historyRadios(parent) {
	var inputs = parent.getElementsByTagName('input');
	var radios = [];
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].name == "diff" || inputs[i].name == "oldid") {
			radios[radios.length] = inputs[i];
		}
	}
	return radios;
}

// check selection and tweak visibility/class onclick
function diffcheck() {
	var dli = false; // the li where the diff radio is checked
	var oli = false; // the li where the oldid radio is checked
	var hf = document.getElementById('pagehistory');
	if (!hf) {
		return true;
	}
	var lis = hf.getElementsByTagName('li');
	for (var i=0;i<lis.length;i++) {
		var inputs = historyRadios(lis[i]);
		if (inputs[1] && inputs[0]) {
			if (inputs[1].checked || inputs[0].checked) { // this row has a checked radio button
				if (inputs[1].checked && inputs[0].checked && inputs[0].value == inputs[1].value) {
					return false;
				}
				if (oli) { // it's the second checked radio
					if (inputs[1].checked) {
						oli.className = "selected";
						return false;
					}
				} else if (inputs[0].checked) {
					return false;
				}
				if (inputs[0].checked) {
					dli = lis[i];
				}
				if (!oli) {
					inputs[0].style.visibility = 'hidden';
				}
				if (dli) {
					inputs[1].style.visibility = 'hidden';
				}
				lis[i].className = "selected";
				oli = lis[i];
			}  else { // no radio is checked in this row
				if (!oli) {
					inputs[0].style.visibility = 'hidden';
				} else {
					inputs[0].style.visibility = 'visible';
				}
				if (dli) {
					inputs[1].style.visibility = 'hidden';
				} else {
					inputs[1].style.visibility = 'visible';
				}
				lis[i].className = "";
			}
		}
	}
	return true;
}

// page history stuff
// attach event handlers to the input elements on history page
function histrowinit() {
	var hf = document.getElementById('pagehistory');
	if (!hf) {
		return;
	}
	var lis = hf.getElementsByTagName('li');
	for (var i = 0; i < lis.length; i++) {
		var inputs = historyRadios(lis[i]);
		if (inputs[0] && inputs[1]) {
			inputs[0].onclick = diffcheck;
			inputs[1].onclick = diffcheck;
		}
	}
	diffcheck();
}

// generate toc from prefs form, fold sections
// XXX: needs testing on IE/Mac and safari
// more comments to follow
function tabbedprefs() {
	var prefform = document.getElementById('preferences');
	if (!prefform || !document.createElement) {
		return;
	}
	if (prefform.nodeName.toLowerCase() == 'a') {
		return; // Occasional IE problem
	}
	prefform.className = prefform.className + 'jsprefs';
	var sections = [];
	var children = prefform.childNodes;
	var seci = 0;
	for (var i = 0; i < children.length; i++) {
		if (children[i].nodeName.toLowerCase() == 'fieldset') {
			children[i].id = 'prefsection-' + seci;
			children[i].className = 'prefsection';
			if (is_opera || is_khtml) {
				children[i].className = 'prefsection operaprefsection';
			}
			var legends = children[i].getElementsByTagName('legend');
			sections[seci] = {};
			legends[0].className = 'mainLegend';
			if (legends[0] && legends[0].firstChild.nodeValue) {
				sections[seci].text = legends[0].firstChild.nodeValue;
			} else {
				sections[seci].text = '# ' + seci;
			}
			sections[seci].secid = children[i].id;
			seci++;
			if (sections.length != 1) {
				children[i].style.display = 'none';
			} else {
				var selectedid = children[i].id;
			}
		}
	}
	var toc = document.createElement('ul');
	toc.id = 'preftoc';
	toc.selectedid = selectedid;
	for (i = 0; i < sections.length; i++) {
		var li = document.createElement('li');
		if (i === 0) {
			li.className = 'selected';
		}
		var a = document.createElement('a');
		a.href = '#' + sections[i].secid;
		a.onmousedown = a.onclick = uncoversection;
		a.appendChild(document.createTextNode(sections[i].text));
		a.secid = sections[i].secid;
		li.appendChild(a);
		toc.appendChild(li);
	}
	prefform.parentNode.insertBefore(toc, prefform.parentNode.childNodes[0]);
	document.getElementById('prefsubmit').id = 'prefcontrol';
}

function uncoversection() {
	var oldsecid = this.parentNode.parentNode.selectedid;
	var newsec = document.getElementById(this.secid);
	if (oldsecid != this.secid) {
		var ul = document.getElementById('preftoc');
		document.getElementById(oldsecid).style.display = 'none';
		newsec.style.display = 'block';
		ul.selectedid = this.secid;
		var lis = ul.getElementsByTagName('li');
		for (var i = 0; i< lis.length; i++) {
			lis[i].className = '';
		}
		this.parentNode.className = 'selected';
	}
	return false;
}

// Timezone stuff
// tz in format [+-]HHMM
function checkTimezone(tz, msg) {
	var localclock = new Date();
	// returns negative offset from GMT in minutes
	var tzRaw = localclock.getTimezoneOffset();
	var tzHour = Math.floor( Math.abs(tzRaw) / 60);
	var tzMin = Math.abs(tzRaw) % 60;
	var tzString = ((tzRaw >= 0) ? "-" : "+") + ((tzHour < 10) ? "0" : "") + tzHour + ((tzMin < 10) ? "0" : "") + tzMin;
	if (tz != tzString) {
		var junk = msg.split('$1');
		document.write(junk[0] + "UTC" + tzString + junk[1]);
	}
}

function unhidetzbutton() {
	var tzb = document.getElementById('guesstimezonebutton');
	if (tzb) {
		tzb.style.display = 'inline';
	}
}

// in [-]HH:MM format...
// won't yet work with non-even tzs
function fetchTimezone() {
	// FIXME: work around Safari bug
	var localclock = new Date();
	// returns negative offset from GMT in minutes
	var tzRaw = localclock.getTimezoneOffset();
	var tzHour = Math.floor( Math.abs(tzRaw) / 60);
	var tzMin = Math.abs(tzRaw) % 60;
	var tzString = ((tzRaw >= 0) ? "-" : "") + ((tzHour < 10) ? "0" : "") + tzHour +
		":" + ((tzMin < 10) ? "0" : "") + tzMin;
	return tzString;
}

function guessTimezone(box) {
	document.getElementsByName("wpHourDiff")[0].value = fetchTimezone();
}

function showTocToggle() {
	if (document.createTextNode) {
		// Uses DOM calls to avoid document.write + XHTML issues

		var linkHolder = document.getElementById('toctitle');
		if (!linkHolder) {
			return;
		}

		var outerSpan = document.createElement('span');
		outerSpan.className = 'toctoggle';

		var toggleLink = document.createElement('a');
		toggleLink.id = 'togglelink';
		toggleLink.className = 'internal';
		toggleLink.href = 'javascript:toggleToc()';
		toggleLink.appendChild(document.createTextNode(tocHideText));

		outerSpan.appendChild(document.createTextNode('['));
		outerSpan.appendChild(toggleLink);
		outerSpan.appendChild(document.createTextNode(']'));

		linkHolder.appendChild(document.createTextNode(' '));
		linkHolder.appendChild(outerSpan);

		var cookiePos = document.cookie.indexOf("hidetoc=");
		if (cookiePos > -1 && document.cookie.charAt(cookiePos + 8) == 1) {
			toggleToc();
		}
	}
}

function changeText(el, newText) {
	// Safari work around
	if (el.innerText) {
		el.innerText = newText;
	} else if (el.firstChild && el.firstChild.nodeValue) {
		el.firstChild.nodeValue = newText;
	}
}

function toggleToc() {
	var toc = document.getElementById('toc').getElementsByTagName('ul')[0];
	var toggleLink = document.getElementById('togglelink');

	if (toc && toggleLink && toc.style.display == 'none') {
		changeText(toggleLink, tocHideText);
		toc.style.display = 'block';
		document.cookie = "hidetoc=0";
	} else {
		changeText(toggleLink, tocShowText);
		toc.style.display = 'none';
		document.cookie = "hidetoc=1";
	}
}

var mwEditButtons = [];
var mwCustomEditButtons = []; // eg to add in MediaWiki:Common.js

// this function generates the actual toolbar buttons with localized text
// we use it to avoid creating the toolbar where javascript is not enabled
function addButton(imageFile, speedTip, tagOpen, tagClose, sampleText) {
	// Don't generate buttons for browsers which don't fully
	// support it.
	mwEditButtons[mwEditButtons.length] =
		{"imageFile": imageFile,
		 "speedTip": speedTip,
		 "tagOpen": tagOpen,
		 "tagClose": tagClose,
		 "sampleText": sampleText};
}

// this function generates the actual toolbar buttons with localized text
// we use it to avoid creating the toolbar where javascript is not enabled
function mwInsertEditButton(parent, item) {
	var image = document.createElement("img");
	image.width = 23;
	image.height = 22;
	image.src = item.imageFile;
	image.border = 0;
	image.alt = item.speedTip;
	image.title = item.speedTip;
	image.style.cursor = "pointer";
	image.onclick = function() {
		insertTags(item.tagOpen, item.tagClose, item.sampleText);
		return false;
	};
	
	parent.appendChild(image);
	return true;
}

function mwSetupToolbar() {
	var toolbar = document.getElementById('toolbar');
	if (!toolbar) { return false; }

	var textbox = document.getElementById('wpTextbox1');
	if (!textbox) { return false; }
	
	// Don't generate buttons for browsers which don't fully
	// support it.
	if (!document.selection && textbox.selectionStart === null) {
		return false;
	}
	
	for (var i in mwEditButtons) {
		mwInsertEditButton(toolbar, mwEditButtons[i]);
	}
	for (i in mwCustomEditButtons) {
		mwInsertEditButton(toolbar, mwCustomEditButtons[i]);
	}
	return true;
}

function escapeQuotes(text) {
	var re = new RegExp("'","g");
	text = text.replace(re,"\\'");
	re = new RegExp("\\n","g");
	text = text.replace(re,"\\n");
	return escapeQuotesHTML(text);
}

function escapeQuotesHTML(text) {
	var re = new RegExp('&',"g");
	text = text.replace(re,"&amp;");
	re = new RegExp('"',"g");
	text = text.replace(re,"&quot;");
	re = new RegExp('<',"g");
	text = text.replace(re,"&lt;");
	re = new RegExp('>',"g");
	text = text.replace(re,"&gt;");
	return text;
}

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
// copied and adapted from phpBB
function insertTags(tagOpen, tagClose, sampleText) {
	var txtarea;
	if (document.editform) {
		txtarea = document.editform.wpTextbox1;
	} else {
		// some alternate form? take the first one we can find
		var areas = document.getElementsByTagName('textarea');
		txtarea = areas[0];
	}

	// IE
	if (document.selection  && !is_gecko) {
		var theSelection = document.selection.createRange().text;
		if (!theSelection) {
			theSelection=sampleText;
		}
		txtarea.focus();
		if (theSelection.charAt(theSelection.length - 1) == " ") { // exclude ending space char, if any
			theSelection = theSelection.substring(0, theSelection.length - 1);
			document.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
		} else {
			document.selection.createRange().text = tagOpen + theSelection + tagClose;
		}

	// Mozilla
	} else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
		var replaced = false;
		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		if (endPos-startPos) {
			replaced = true;
		}
		var scrollTop = txtarea.scrollTop;
		var myText = (txtarea.value).substring(startPos, endPos);
		if (!myText) {
			myText=sampleText;
		}
		var subst;
		if (myText.charAt(myText.length - 1) == " ") { // exclude ending space char, if any
			subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
		} else {
			subst = tagOpen + myText + tagClose;
		}
		txtarea.value = txtarea.value.substring(0, startPos) + subst +
			txtarea.value.substring(endPos, txtarea.value.length);
		txtarea.focus();
		//set new selection
		if (replaced) {
			var cPos = startPos+(tagOpen.length+myText.length+tagClose.length);
			txtarea.selectionStart = cPos;
			txtarea.selectionEnd = cPos;
		} else {
			txtarea.selectionStart = startPos+tagOpen.length;
			txtarea.selectionEnd = startPos+tagOpen.length+myText.length;
		}
		txtarea.scrollTop = scrollTop;

	// All other browsers get no toolbar.
	// There was previously support for a crippled "help"
	// bar, but that caused more problems than it solved.
	}
	// reposition cursor if possible
	if (txtarea.createTextRange) {
		txtarea.caretPos = document.selection.createRange().duplicate();
	}
}


/**
 * Set the accesskey prefix based on browser detection.
 */
var tooltipAccessKeyPrefix = 'alt-';
if (is_opera) {
	tooltipAccessKeyPrefix = 'shift-esc-';
} else if (is_safari
	   || navigator.userAgent.toLowerCase().indexOf('mac') != -1
	   || navigator.userAgent.toLowerCase().indexOf('konqueror') != -1 ) {
	tooltipAccessKeyPrefix = 'ctrl-';
} else if (is_ff2_x11) {
	tooltipAccessKeyPrefix = 'ctrl-shift-';
} else if (is_ff2_win) {
	tooltipAccessKeyPrefix = 'alt-shift-';
}
var tooltipAccessKeyRegexp = /\[(ctrl-)?(alt-)?(shift-)?(esc-)?.\]$/;
var parentNode = null;

/**
 * Add the appropriate prefix to the accesskey shown in the tooltip.
 * If the nodeList parameter is given, only those nodes are updated;
 * otherwise, all the nodes that will probably have accesskeys by default
 * are updated.
 *
 * @param Array nodeList -- list of elements to update
 */
function updateTooltipAccessKeys( nodeList ) {
	if ( !nodeList ) {
		if ( !parentNode ) {
			if ( !document.getElementById( "wpSave" ) && !document.getElementById( "pagehistory" ) ) {
				// If this is an edit page, we need to go through the whole page.
				// Otherwise we can just do non-content stuff.
				// Note: hacky heuristic! Any better?
				parentNode = document.getElementById( "column-one" );
			}
			if (!parentNode) {
				// Presumably non-Monobook, have to go through the whole page
				parentNode = document;
			}
		}
		updateTooltipAccessKeys( parentNode.getElementsByTagName("a") );
		updateTooltipAccessKeys( parentNode.getElementsByTagName("input") );
		updateTooltipAccessKeys( parentNode.getElementsByTagName("label") );
	} else {
		for ( var i = 0; i < nodeList.length; i++ ) {
			var element = nodeList[i];
			var tip = element.getAttribute("title");
			var key = element.getAttribute("accesskey");
			if ( key && tooltipAccessKeyRegexp.exec(tip) ) {
				tip = tip.replace(tooltipAccessKeyRegexp,
						  "["+tooltipAccessKeyPrefix+key+"]");
				element.setAttribute("title", tip );
			}
		}
	}
}

/**
 * Add a link to one of the portlet menus on the page, including:
 *
 * p-cactions: Content actions (shown as tabs above the main content in Monobook)
 * p-personal: Personal tools (shown at the top right of the page in Monobook)
 * p-navigation: Navigation
 * p-tb: Toolbox
 *
 * This function exists for the convenience of custom JS authors.  All
 * but the first three parameters are optional, though providing at
 * least an id and a tooltip is recommended.
 *
 * By default the new link will be added to the end of the list.  To
 * add the link before a given existing item, pass the DOM node of
 * that item (easily obtained with document.getElementById()) as the
 * nextnode parameter; to add the link _after_ an existing item, pass
 * the node's nextSibling instead.
 *
 * @param String portlet -- id of the target portlet ("p-cactions", "p-personal", "p-navigation" or "p-tb")
 * @param String href -- link URL
 * @param String text -- link text (will be automatically lowercased by CSS for p-cactions in Monobook)
 * @param String id -- id of the new item, should be unique and preferably have the appropriate prefix ("ca-", "pt-", "n-" or "t-")
 * @param String tooltip -- text to show when hovering over the link, without accesskey suffix
 * @param String accesskey -- accesskey to activate this link (one character, try to avoid conflicts)
 * @param Node nextnode -- the DOM node before which the new item should be added, should be another item in the same list
 *
 * @return Node -- the DOM node of the new item (an LI element) or null
 */
function addPortletLink(portlet, href, text, id, tooltip, accesskey, nextnode) {
	var node = document.getElementById(portlet);
	if ( !node ) return null;
	node = node.getElementsByTagName( "ul" )[0];
	if ( !node ) return null;

	var link = document.createElement( "a" );
	link.appendChild( document.createTextNode( text ) );
	link.href = href;

	var item = document.createElement( "li" );
	item.appendChild( link );
	if ( id ) item.id = id;

	if ( accesskey ) {
		link.setAttribute( "accesskey", accesskey );
		tooltip += " ["+accesskey+"]";
	}
	if ( tooltip ) {
		link.setAttribute( "title", tooltip );
	}
	if ( accesskey && tooltip ) {
		updateTooltipAccessKeys( new Array( link ) );
	}

	if ( nextnode && nextnode.parentNode != node ) nextnode = null;
	node.insertBefore( item, nextnode );

	return item;
}


/**
 * Set up accesskeys/tooltips from the deprecated ta array.  If doId
 * is specified, only set up for that id.  Note that this function is
 * deprecated and will not be supported indefinitely -- use
 * updateTooltipAccessKey() instead.
 *
 * @param mixed doId string or null
 */
function akeytt( doId ) {
	// A lot of user scripts (and some of the code below) break if
	// ta isn't defined, so we make sure it is.  Explictly using
	// window.ta avoids a "ta is not defined" error.
	if (!window.ta) window.ta = new Array;

	// Make a local, possibly restricted, copy to avoid clobbering
	// the original.
	var ta;
	if ( doId ) {
		ta = new Array;
		ta[doId] = window.ta[doId];
	} else {
		ta = window.ta;
	}

	// Now deal with evil deprecated ta
	watchCheckboxExists = document.getElementById( 'wpWatchthis' ) ? true : false;
	for (var id in ta) {
		var n = document.getElementById(id);
		if (n) {
			var a = null;
			var ak = '';
			// Are we putting accesskey in it
			if (ta[id][0].length > 0) {
				// Is this object a object? If not assume it's the next child.

				if (n.nodeName.toLowerCase() == "a") {
					a = n;
				} else {
					a = n.childNodes[0];
				}
			 	// Don't add an accesskey for the watch tab if the watch
			 	// checkbox is also available.
				if (a && ((id != 'ca-watch' && id != 'ca-unwatch') || !watchCheckboxExists)) {
					a.accessKey = ta[id][0];
					ak = ' ['+tooltipAccessKeyPrefix+ta[id][0]+']';
				}
			} else {
				// We don't care what type the object is when assigning tooltip
				a = n;
				ak = '';
			}

			if (a) {
				a.title = ta[id][1]+ak;
			}
		}
	}
}

function setupRightClickEdit() {
	if (document.getElementsByTagName) {
		var spans = document.getElementsByTagName('span');
		for (var i = 0; i < spans.length; i++) {
			var el = spans[i];
			if(el.className == 'editsection') {
				addRightClickEditHandler(el);
			}
		}
	}
}

function addRightClickEditHandler(el) {
	for (var i = 0; i < el.childNodes.length; i++) {
		var link = el.childNodes[i];
		if (link.nodeType == 1 && link.nodeName.toLowerCase() == 'a') {
			var editHref = link.getAttribute('href');
			// find the enclosing (parent) header
			var prev = el.parentNode;
			if (prev && prev.nodeType == 1 &&
			prev.nodeName.match(/^[Hh][1-6]$/)) {
				prev.oncontextmenu = function(e) {
					if (!e) { e = window.event; }
					// e is now the event in all browsers
					var targ;
					if (e.target) { targ = e.target; }
					else if (e.srcElement) { targ = e.srcElement; }
					if (targ.nodeType == 3) { // defeat Safari bug
						targ = targ.parentNode;
					}
					// targ is now the target element

					// We don't want to deprive the noble reader of a context menu
					// for the section edit link, do we?  (Might want to extend this
					// to all <a>'s?)
					if (targ.nodeName.toLowerCase() != 'a'
					|| targ.parentNode.className != 'editsection') {
						document.location = editHref;
						return false;
					}
					return true;
				};
			}
		}
	}
}

function setupCheckboxShiftClick() {
	if (document.getElementsByTagName) {
		var uls = document.getElementsByTagName('ul');
		var len = uls.length;
		for (var i = 0; i < len; ++i) {
			addCheckboxClickHandlers(uls[i]);
		}
	}
}

function addCheckboxClickHandlers(ul, start, finish) {
	if (ul.checkboxHandlersTimer) {
		clearInterval(ul.checkboxHandlersTimer);
	}
	if ( !ul.childNodes ) {
		return;
	}
	var len = ul.childNodes.length;
	if (len < 2) {
		return;
	}
	start = start || 0;
	finish = finish || start + 250;
	if ( finish > len ) { finish = len; }
	ul.checkboxes = ul.checkboxes || [];
	ul.lastCheckbox = ul.lastCheckbox || null;
	for (var i = start; i<finish; ++i) {
		var child = ul.childNodes[i];
		if ( child && child.childNodes && child.childNodes[0] ) {
			var cb = child.childNodes[0];
			if ( !cb.nodeName || cb.nodeName.toLowerCase() != 'input' ||
			     !cb.type || cb.type.toLowerCase() != 'checkbox' ) {
				return;
			}
			cb.index = ul.checkboxes.push(cb) - 1;
			cb.container = ul;
			cb.onmouseup = checkboxMouseupHandler;
		}
	}
	if (finish < len) {
	  var f=function(){ addCheckboxClickHandlers(ul, finish, finish+250); };
	  ul.checkboxHandlersTimer=setInterval(f, 200);
	}
}

function checkboxMouseupHandler(e) {
	if (typeof e == 'undefined') {
		e = window.event;
	}
	if ( !e.shiftKey || this.container.lastCheckbox === null ) {
		this.container.lastCheckbox = this.index;
		return true;
	}
	var endState = !this.checked;
	if ( is_opera ) { // opera has already toggled the checkbox by this point
		endState = !endState;
	}
	var start, finish;
	if ( this.index < this.container.lastCheckbox ) {
		start = this.index + 1;
		finish = this.container.lastCheckbox;
	} else {
		start = this.container.lastCheckbox;
		finish = this.index - 1;
	}
	for (var i = start; i <= finish; ++i ) {
		this.container.checkboxes[i].checked = endState;
	}
	this.container.lastCheckbox = this.index;
	return true;
}

function toggle_element_activation(ida,idb) {
	if (!document.getElementById) {
		return;
	}
	document.getElementById(ida).disabled=true;
	document.getElementById(idb).disabled=false;
}

function toggle_element_check(ida,idb) {
	if (!document.getElementById) {
		return;
	}
	document.getElementById(ida).checked=true;
	document.getElementById(idb).checked=false;
}

function fillDestFilename(id) {
	if (!document.getElementById) {
		return;
	}
	var path = document.getElementById(id).value;
	// Find trailing part
	var slash = path.lastIndexOf('/');
	var backslash = path.lastIndexOf('\\');
	var fname;
	if (slash == -1 && backslash == -1) {
		fname = path;
	} else if (slash > backslash) {
		fname = path.substring(slash+1, 10000);
	} else {
		fname = path.substring(backslash+1, 10000);
	}

	// Capitalise first letter and replace spaces by underscores
	fname = fname.charAt(0).toUpperCase().concat(fname.substring(1,10000)).replace(/ /g, '_');

	// Output result
	var destFile = document.getElementById('wpDestFile');
	if (destFile) {
		destFile.value = fname;
	}
}


function considerChangingExpiryFocus() {
	if (!document.getElementById) {
		return;
	}
	var drop = document.getElementById('wpBlockExpiry');
	if (!drop) {
		return;
	}
	var field = document.getElementById('wpBlockOther');
	if (!field) {
		return;
	}
	var opt = drop.value;
	if (opt == 'other') {
		field.style.display = '';
	} else {
		field.style.display = 'none';
	}
}

function scrollEditBox() {
	var editBoxEl = document.getElementById("wpTextbox1");
	var scrollTopEl = document.getElementById("wpScrolltop");
	var editFormEl = document.getElementById("editform");

	if (editBoxEl && scrollTopEl) {
		if (scrollTopEl.value) { editBoxEl.scrollTop = scrollTopEl.value; }
		editFormEl.onsubmit = function() {
			document.getElementById("wpScrolltop").value = document.getElementById("wpTextbox1").scrollTop;
		};
	}
}

hookEvent("load", scrollEditBox);

var allmessages_nodelist = false;
var allmessages_modified = false;
var allmessages_timeout = false;
var allmessages_running = false;

function allmessagesmodified() {
	allmessages_modified = !allmessages_modified;
	allmessagesfilter();
}

function allmessagesfilter() {
	if ( allmessages_timeout )
		window.clearTimeout( allmessages_timeout );

	if ( !allmessages_running )
		allmessages_timeout = window.setTimeout( 'allmessagesfilter_do();', 500 );
}

function allmessagesfilter_do() {
	if ( !allmessages_nodelist )
		return;

	var text = document.getElementById('allmessagesinput').value;
	var nodef = allmessages_modified;

	allmessages_running = true;

	for ( var name in allmessages_nodelist ) {
		var nodes = allmessages_nodelist[name];
		var display = ( name.indexOf( text ) == -1 ? 'none' : '' );

		for ( var i = 0; i < nodes.length; i++)
			nodes[i].style.display =
				( nodes[i].className == "def" && nodef
				  ? 'none' : display );
	}

	if ( text != document.getElementById('allmessagesinput').value ||
	     nodef != allmessages_modified )
		allmessagesfilter_do();  // repeat

	allmessages_running = false;
}

function allmessagesfilter_init() {
	if ( allmessages_nodelist )
		return;

	var nodelist = new Array();
	var templist = new Array();

	var table = document.getElementById('allmessagestable');
	if ( !table ) return;

	var rows = document.getElementsByTagName('tr');
	for ( var i = 0; i < rows.length; i++ ) {
		var id = rows[i].getAttribute('id')
		if ( id && id.substring(0,16) != 'sp-allmessages-r' ) continue;
		templist[ id ] = rows[i];
	}

	var spans = table.getElementsByTagName('span');
	for ( var i = 0; i < spans.length; i++ ) {
		var id = spans[i].getAttribute('id')
		if ( id && id.substring(0,17) != 'sp-allmessages-i-' ) continue;
		if ( !spans[i].firstChild || spans[i].firstChild.nodeType != 3 ) continue;

		var nodes = new Array();
		var row1 = templist[ id.replace('i', 'r1') ];
		var row2 = templist[ id.replace('i', 'r2') ];

		if ( row1 ) nodes[nodes.length] = row1;
		if ( row2 ) nodes[nodes.length] = row2;
		nodelist[ spans[i].firstChild.nodeValue ] = nodes;
	}

	var k = document.getElementById('allmessagesfilter');
	if (k) { k.style.display = ''; }

	allmessages_nodelist = nodelist;
}

hookEvent( "load", allmessagesfilter_init );

/*
	Written by Jonathan Snook, http://www.snook.ca/jonathan
	Add-ons by Robert Nyman, http://www.robertnyman.com
	Author says "The credit comment is all it takes, no license. Go crazy with it!:-)"
	From http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
*/
function getElementsByClassName(oElm, strTagName, oClassNames){
	var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
	var arrReturnElements = new Array();
	var arrRegExpClassNames = new Array();
	if(typeof oClassNames == "object"){
		for(var i=0; i<oClassNames.length; i++){
			arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames[i].replace(/\-/g, "\\-") + "(\\s|$)"));
		}
	}
	else{
		arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames.replace(/\-/g, "\\-") + "(\\s|$)"));
	}
	var oElement;
	var bMatchesAll;
	for(var j=0; j<arrElements.length; j++){
		oElement = arrElements[j];
		bMatchesAll = true;
		for(var k=0; k<arrRegExpClassNames.length; k++){
			if(!arrRegExpClassNames[k].test(oElement.className)){
				bMatchesAll = false;
				break;
			}
		}
		if(bMatchesAll){
			arrReturnElements.push(oElement);
		}
	}
	return (arrReturnElements)
}

function sortableTables() {
	if (getElementsByClassName(document, "table", "sortable").length != 0) {
		document.write('<script type="text/javascript" src="'+stylepath+'/common/sorttable.js"></script>');
	}
}

function redirectToFragment(fragment) {
	var match = navigator.userAgent.match(/AppleWebKit\/(\d+)/);
	if (match) {
		var webKitVersion = parseInt(match[1]);
		if (webKitVersion < 420) {
			// Released Safari w/ WebKit 418.9.1 messes up horribly
			// Nightlies of 420+ are ok
			return;
		}
	}
	if (is_gecko) {
		// Mozilla needs to wait until after load, otherwise the window doesn't scroll
		addOnloadHook(function () {
			if (window.location.hash == "")
				window.location.hash = fragment;
		});
	} else {
		if (window.location.hash == "")
			window.location.hash = fragment;
	}
}

function runOnloadHook() {
	// don't run anything below this for non-dom browsers
	if (doneOnloadHook || !(document.getElementById && document.getElementsByTagName)) {
		return;
	}

	// set this before running any hooks, since any errors below
	// might cause the function to terminate prematurely
	doneOnloadHook = true;

	histrowinit();
	unhidetzbutton();
	tabbedprefs();
	updateTooltipAccessKeys( null );
	akeytt( null );
	scrollEditBox();
	setupCheckboxShiftClick();
	sortableTables();

	// Run any added-on functions
	for (var i = 0; i < onloadFuncts.length; i++) {
		onloadFuncts[i]();
	}
}

//note: all skins should call runOnloadHook() at the end of html output,
//      so the below should be redundant. It's there just in case.
hookEvent("load", runOnloadHook);

hookEvent("load", mwSetupToolbar);
