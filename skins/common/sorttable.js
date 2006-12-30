/*
 * From: http://www.kryogenix.org/code/browser/sorttable/
 * Licence: X11
 */

hookEvent( "load", sortables_init);

var NO_ARROW = stylepath+'/common/sort_none.gif';
var UP_ARROW = stylepath+'/common/sort_up.gif';
var DOWN_ARROW = stylepath+'/common/sort_down.gif';

function sortables_init() {
	var idnum = 0;
    // Find all tables with class sortable and make them sortable
    if (!document.getElementsByTagName) return;
    tbls = document.getElementsByTagName("table");
    for (ti=0;ti<tbls.length;ti++) {
        thisTbl = tbls[ti];
        if ((' '+thisTbl.className+' ').indexOf(" sortable ") != -1) {
			if (!thisTbl.id) {
				thisTbl.setAttribute('id','sortable_table_id_'+idnum);
				++idnum;
			}
            //initTable(thisTbl.id);
            ts_makeSortable(thisTbl);
        }
    }
}

function ts_makeSortable(table) {
    if (table.rows && table.rows.length > 0) {
        var firstRow = table.rows[0];
    }
    if (!firstRow) return;

    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i=0;i<firstRow.cells.length;i++) {
        var cell = firstRow.cells[i];
        var txt = ts_getInnerText(cell);
        cell.innerHTML = txt+'<a href="#" class="sortheader" onclick="ts_resortTable(this);return false;"><img class="sortarrow" src="'+NO_ARROW+'" alt="&#x2195;" /></a>';
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
    // get the arrow image
    var img;
    for (var ci=0;ci<lnk.childNodes.length;ci++) {
        if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'img') img = lnk.childNodes[ci];
    }
    var reverse = (img.getAttribute("sortdir") == 'down');

    var td = lnk.parentNode;
    var column = td.cellIndex;
    var table = getParent(td,'TABLE');

    // Work out a type for the column
    if (table.rows.length <= 1) return;
    var itm = ts_getInnerText(table.rows[1].cells[column]);
    sortfn = ts_sort_caseinsensitive;
    // Note: The trailing \n$ is needed because that's how MediaWiki spits out its table syntax.
    if (itm.match(/^\s*\d{2}[\/-]\d{2}[\/-]\d{4}\s*$/)) sortfn = ts_sort_date;
    if (itm.match(/^\s*\d{2}[\/-]\d{2}[\/-]\d{2}\s*$/)) sortfn = ts_sort_date;
    if (itm.match(/^\s*[?$]/)) sortfn = ts_sort_currency;
    if (itm.match(/^\s*[\d\.]+\s*$/)) sortfn = ts_sort_numeric;
    var firstRow = new Array();
    var newRows = new Array();
    for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
    for (j=1;j<table.rows.length;j++) {
	var obj = new Object();
	obj.row = table.rows[j];
	obj.grp = ((' '+obj.row.className+' ').indexOf(' sortbottom ') == -1 ? 0 : reverse ? -1 : 1);
	obj.txt = ts_getInnerText(obj.row.cells[column]);
	obj.idx = (reverse ? -j : j);
	newRows[j-1] = obj;
    }

    newRows.sort(sortfn);

    if (reverse) {
	ARROW = UP_ARROW;
        newRows.reverse();
        img.setAttribute('sortdir','up');
    } else {
	ARROW = DOWN_ARROW;
        img.setAttribute('sortdir','down');
    }

    // We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
    for (i=0;i<newRows.length;i++) { table.tBodies[0].appendChild(newRows[i].row); }

    // Delete any other arrows there may be showing
    var allimgs = document.getElementsByTagName("img");
    for (var ci=0;ci<allimgs.length;ci++) {
        if (allimgs[ci].className == 'sortarrow') {
            if (getParent(allimgs[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
                allimgs[ci].setAttribute('src',NO_ARROW);
                allimgs[ci].setAttribute('alt',"&#x2195;");
            }
        }
    }

    img.setAttribute('src',ARROW);
    img.setAttribute('alt',img.getAttribute("sortdir") == 'down' ? '&darr;' : '&uarr;');
}

function getParent(el, pTagName) {
	if (el == null) return null;
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
		return el;
	else
		return getParent(el.parentNode, pTagName);
}
function ts_sort_date(a,b) {
    if (a.grp != b.grp) return a.grp-b.grp;
    // y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
    aa = a.txt;
    bb = b.txt;
    if (aa.length == 10) {
        dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
    } else {
        yr = aa.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
    }
    if (bb.length == 10) {
        dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
    } else {
        yr = bb.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
    }
    if (dt1==dt2) return a.idx-b.idx;
    if (dt1<dt2) return -1;
    return 1;
}

function ts_sort_currency(a,b) { 
    if (a.grp != b.grp) return a.grp-b.grp;
    aa = parseFloat(a.txt.replace(/[^0-9.]/g,''));
    bb = parseFloat(b.txt.replace(/[^0-9.]/g,''));
    if (isNaN(aa)) aa = 0;
    if (isNaN(bb)) bb = 0;
    if (aa==bb) return a.idx-b.idx;
    return aa-bb;
}

function ts_sort_numeric(a,b) { 
    if (a.grp != b.grp) return a.grp-b.grp;
    aa = parseFloat(a.txt);
    bb = parseFloat(b.txt); 
    if (isNaN(aa)) aa = 0;
    if (isNaN(bb)) bb = 0;
    if (aa==bb) return a.idx-b.idx;
    return aa-bb;
}

function ts_sort_caseinsensitive(a,b) {
    if (a.grp != b.grp) return a.grp-b.grp;
    aa = a.txt.toLowerCase();
    bb = b.txt.toLowerCase();
    if (aa==bb) return a.idx-b.idx;
    if (aa<bb) return -1;
    return 1;
}

function ts_sort_default(a,b) {
    if (a.grp != b.grp) return a.grp-b.grp;
    aa = a.txt;
    bb = b.txt;
    if (aa==bb) return a.idx-b.idx;
    if (aa<bb) return -1;
    return 1;
}
