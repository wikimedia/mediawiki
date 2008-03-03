/*
 * Table sorting script  by Joost de Valk, check it out at http://www.joostdevalk.nl/code/sortable-table/.
 * Based on a script from http://www.kryogenix.org/code/browser/sorttable/.
 * Distributed under the MIT license: http://www.kryogenix.org/code/browser/licence.html .
 *
 * Copyright (c) 1997-2006 Stuart Langridge, Joost de Valk.
 *
 * @todo don't break on colspans/rowspans (bug 8028)
 * @todo language-specific digit grouping/decimals (bug 8063)
 * @todo support all accepted date formats (bug 8226)
 */

var image_path = stylepath+"/common/images/";
var image_up = "sort_up.gif";
var image_down = "sort_down.gif";
var image_none = "sort_none.gif";
var europeandate = wgContentLanguage != "en"; // The non-American-inclined can change to "true"

var alternate_row_colors = true;


hookEvent( "load", sortables_init);

var SORT_COLUMN_INDEX;
var thead = false;

function sortables_init() {
	var idnum = 0;
	// Find all tables with class sortable and make them sortable
	if (!document.getElementsByTagName) return;
	tbls = document.getElementsByTagName("table");
	for (ti=0;ti<tbls.length;ti++) {
		thisTbl = tbls[ti];
		if ( (' '+thisTbl.className+' ').indexOf("sortable") != -1 ) {
			if (!thisTbl.id) {
				thisTbl.setAttribute('id','sortable_table_id_'+idnum);
				++idnum;
			}
			ts_makeSortable(thisTbl);
		}
	}
}

function ts_makeSortable(table) {
	if (table.rows && table.rows.length > 0) {
		if (table.tHead && table.tHead.rows.length > 0) {
			var firstRow = table.tHead.rows[table.tHead.rows.length-1];
			thead = true;
		} else {
			var firstRow = table.rows[0];
		}
	}
	if (!firstRow) return;

	// We have a first row: assume it's the header, and make its contents clickable links
	for (var i=0;i<firstRow.cells.length;i++) {
		var cell = firstRow.cells[i];
		if (cell.className != "unsortable" && cell.className.indexOf("unsortable") == -1) {
			cell.innerHTML += '&nbsp;&nbsp;<a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><span class="sortarrow"><img src="'+ image_path + image_none + '" alt="&darr;"/></span></a>';
		}
	}
	if (alternate_row_colors) {
		alternate(table);
	}
}

function ts_getInnerText(el) {
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };
	if (el.innerText) return el.innerText;	//Not needed but it is faster
	var str = "";
	
	var cs = el.childNodes;
	var l = cs.length;
	for (var i = 0; i < l; i++) {
		switch (cs[i].nodeType) {
			case 1: //ELEMENT_NODE
				str += ts_getInnerText(cs[i]);
				break;
			case 3:	//TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
}

function ts_resortTable(lnk) {
	// get the span
	var span;
	for (var ci=0;ci<lnk.childNodes.length;ci++) {
		if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') span = lnk.childNodes[ci];
	}
	var spantext = ts_getInnerText(span);
	var td = lnk.parentNode;
	var column = td.cellIndex;
	var table = getParent(td,'TABLE');

	// Work out a type for the column
	if (table.rows.length <= 1) return;

	for( var i = 1, itm = ""; itm.match(/^([\s]|\n|\&nbsp;|<!--[^-]+-->)*$/); i++) {
		var itm = ts_getInnerText(table.tBodies[0].rows[i].cells[column]);
		itm = trim(itm);
	}
	sortfn = ts_sort_caseinsensitive;
	if (itm.match(/^\d\d[\/. -][a-zA-Z]{3}[\/. -]\d\d\d\d$/)) sortfn = ts_sort_date;
	if (itm.match(/^\d\d[\/.-]\d\d[\/.-]\d\d\d\d$/)) sortfn = ts_sort_date;
	if (itm.match(/^\d\d[\/.-]\d\d[\/.-]\d\d$/)) sortfn = ts_sort_date;
	if (itm.match(/^[£$€Û¢´]/)) sortfn = ts_sort_currency;
	if (itm.match(/^[\d.,]+\%?$/)) sortfn = ts_sort_numeric;
	SORT_COLUMN_INDEX = column;
	var firstRow = new Array();
	var newRows = new Array();

	for (k=0;k<table.tBodies.length;k++) {
		for (i=0;i<table.tBodies[k].rows[0].length;i++) {
			firstRow[i] = table.tBodies[k].rows[0][i];
		}
	}

	for (k=0;k<table.tBodies.length;k++) {
		if (!thead) {
			// Skip the first row
			for (j=1;j<table.tBodies[k].rows.length;j++) {
				newRows[j-1] = table.tBodies[k].rows[j];
			}
		} else {
			// Do NOT skip the first row
			for (j=0;j<table.tBodies[k].rows.length;j++) { 
				newRows[j] = table.tBodies[k].rows[j];
			}
		}
	}

	newRows.sort(sortfn);

	if (span.getAttribute("sortdir") == 'down') {
			ARROW = '<img src="'+ image_path + image_down + '" alt="&darr;"/>';
			newRows.reverse();
			span.setAttribute('sortdir','up');
	} else {
			ARROW = '<img src="'+ image_path + image_up + '" alt="&uarr;"/>';
			span.setAttribute('sortdir','down');
	} 
	
	// We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
	// don't do sortbottom rows
	for (i=0; i<newRows.length; i++) {
		if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) {
			table.tBodies[0].appendChild(newRows[i]);
		}
	}
	// do sortbottom rows only
	for (i=0; i<newRows.length; i++) {
		if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1))
			table.tBodies[0].appendChild(newRows[i]);
	}

	// Delete any other arrows there may be showing
	var allspans = document.getElementsByTagName("span");
	for (var ci=0;ci<allspans.length;ci++) {
		if (allspans[ci].className == 'sortarrow') {
			if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
				allspans[ci].innerHTML = '<img src="'+ image_path + image_none + '" alt="&darr;"/>';
			}
		}
	}

	span.innerHTML = ARROW;
	alternate(table);		
}

function getParent(el, pTagName) {
	if (el == null) {
		return null;
	} else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase()) {	// Gecko bug, supposed to be uppercase
		return el;
	} else {
		return getParent(el.parentNode, pTagName);
	}
}

function sort_date(date) {	
	// y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
	dt = "00000000";
	if (date.length == 11) {
		monthstr = date.substr(3,3);
		monthstr = monthstr.toLowerCase();
		switch(monthstr) {
			case "jan": var month = "01"; break;
			case "feb": var month = "02"; break;
			case "mar": var month = "03"; break;
			case "apr": var month = "04"; break;
			case "may": var month = "05"; break;
			case "jun": var month = "06"; break;
			case "jul": var month = "07"; break;
			case "aug": var month = "08"; break;
			case "sep": var month = "09"; break;
			case "oct": var month = "10"; break;
			case "nov": var month = "11"; break;
			case "dec": var month = "12"; break;
			// default: var month = "00";
		}
		dt = date.substr(7,4)+month+date.substr(0,2);
		return dt;
	} else if (date.length == 10) {
		if (europeandate == false) {
			dt = date.substr(6,4)+date.substr(0,2)+date.substr(3,2);
			return dt;
		} else {
			dt = date.substr(6,4)+date.substr(3,2)+date.substr(0,2);
			return dt;
		}
	} else if (date.length == 8) {
		yr = date.substr(6,2);
		if (parseInt(yr) < 50) { 
			yr = '20'+yr; 
		} else { 
			yr = '19'+yr; 
		}
		if (europeandate == true) {
			dt = yr+date.substr(3,2)+date.substr(0,2);
			return dt;
		} else {
			dt = yr+date.substr(0,2)+date.substr(3,2);
			return dt;
		}
	}
	return dt;
}

function ts_sort_date(a,b) {
	dt1 = sort_date(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
	dt2 = sort_date(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));

	if (dt1==dt2) {
		return 0;
	}
	if (dt1<dt2) {
		return -1;
	}
	return 1;
}

function ts_sort_currency(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	return compare_numeric(aa,bb);
}
 
function ts_sort_numeric(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	return compare_numeric(aa,bb);
}
 
function compare_numeric(a,b) {
	a = parseFloat(a.replace(/,/, ""));
	a = (isNaN(a) ? 0 : a);
	b = parseFloat(b.replace(/,/, ""));
	b = (isNaN(b) ? 0 : b);
	return a - b;
}

function ts_sort_caseinsensitive(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
	if (aa==bb) {
		return 0;
	}
	if (aa<bb) {
		return -1;
	}
	return 1;
}

function ts_sort_default(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	if (aa==bb) {
		return 0;
	}
	if (aa<bb) {
		return -1;
	}
	return 1;
}

function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,	NS6 and Mozilla
// By Scott Andrew
{
	if (elm.addEventListener){
		elm.addEventListener(evType, fn, useCapture);
		return true;
	} else if (elm.attachEvent){
		var r = elm.attachEvent("on"+evType, fn);
		return r;
	} else {
		alert("Handler could not be removed");
	}
} 

function replace(s, t, u) {
	/*
	**  Replace a token in a string
	**    s  string to be processed
	**    t  token to be found and removed
	**    u  token to be inserted
	**  returns new String
	*/
	i = s.indexOf(t);
	r = "";
	if (i == -1) return s;
	r += s.substring(0,i) + u;
	if ( i + t.length < s.length) {
		r += replace(s.substring(i + t.length, s.length), t, u);
	}
	return r;
}

function trim(s) {
	return s.replace(/^([ \t]|\n|\&nbsp;|<!--[^-]+-->)*/, "").replace(/([ \t]|\n|\&nbsp;|<!--[^-]+-->)*$/, "");
}

function alternate(table) {
	// Take object table and get all it's tbodies.
	var tableBodies = table.getElementsByTagName("tbody");
	// Loop through these tbodies
	for (var i = 0; i < tableBodies.length; i++) {
		// Take the tbody, and get all it's rows
		var tableRows = tableBodies[i].getElementsByTagName("tr");
		// Loop through these rows
		// Start at 1 because we want to leave the heading row untouched
		for (var j = 0; j < tableRows.length; j++) {
			// Check if j is even, and apply classes for both possible results
			if ( (j % 2) == 0  ) {
				if ( !(tableRows[j].className.indexOf('odd') == -1) ) {
					tableRows[j].className = replace(tableRows[j].className, 'odd', 'even');
				} else {
					if ( tableRows[j].className.indexOf('even') == -1 ) {
						tableRows[j].className += " even";
					}
				}
			} else {
				if ( !(tableRows[j].className.indexOf('even') == -1) ) {
					tableRows[j].className = replace(tableRows[j].className, 'even', 'odd');
				} else {
					if ( tableRows[j].className.indexOf('odd') == -1 ) {
						tableRows[j].className += " odd";
					}
				}
			} 
		}
	}
}
