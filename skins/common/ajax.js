// remote scripting library
// (c) copyright 2005 modernmethod, inc
var sajax_debug_mode = false;
var sajax_request_type = "GET";

var started;
var typing;
var memory=null;
var body=null;
var oldbody=null;

function sajax_debug(text) {
	if (sajax_debug_mode)
		alert("RSD: " + text)
}


function sajax_init_object() {
	sajax_debug("sajax_init_object() called..")
	var A;
	try {
		A=new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			A=new ActiveXObject("Microsoft.XMLHTTP");
		} catch (oc) {
			A=null;
		}
	}
	if(!A && typeof XMLHttpRequest != "undefined")
		A = new XMLHttpRequest();
	if (!A)
		sajax_debug("Could not create connection object.");
	return A;
}


function sajax_do_call(func_name, args) {
	var i, x, n;
	var uri;
	var post_data;
	uri = wgServer + "/" + wgScriptPath + "/index.php?action=ajax";
	if (sajax_request_type == "GET") {
		if (uri.indexOf("?") == -1)
			uri = uri + "?rs=" + escape(func_name);
		else
			uri = uri + "&rs=" + escape(func_name);
		for (i = 0; i < args.length-1; i++)
			uri = uri + "&rsargs[]=" + escape(args[i]);
		//uri = uri + "&rsrnd=" + new Date().getTime();
		post_data = null;
	} else {
		post_data = "rs=" + escape(func_name);
		for (i = 0; i < args.length-1; i++)
			post_data = post_data + "&rsargs[]=" + escape(args[i]);
	}
	x = sajax_init_object();
	x.open(sajax_request_type, uri, true);
	if (sajax_request_type == "POST") {
		x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
		x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	}
	x.setRequestHeader("Pragma", "cache=yes");
	x.setRequestHeader("Cache-Control", "no-transform");
	x.onreadystatechange = function() {
		if (x.readyState != 4)
			return;
		sajax_debug("received " + x.responseText);
		var status;
		var data;
		status = x.responseText.charAt(0);
		data = x.responseText.substring(2);
		if (status == "-")
			alert("Error: " + data);
		else
			args[args.length-1](data);
	}
	x.send(post_data);
	sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);
	sajax_debug(func_name + " waiting..");
	delete x;
}

// Remove the typing barrier to allow call() to complete
function Search_doneTyping()
{
	typing=false;
}

// Wait 500ms to run call()
function Searching_Go()
{
        setTimeout("Searching_Call()", 500);
}

// If the user is typing wait until they are done.
function Search_Typing() {
	started=true;
	typing=true;
	window.status = "Waiting until you're done typing...";
	setTimeout("Search_doneTyping()", 500);

	// I believe these are needed by IE for when the users press return?
	if (window.event)
	{
		if (event.keyCode == 13)
		{
			event.cancelBubble = true;
			event.returnValue = false;
		}
	}
}

// Set the body div to the results
function Searching_SetResult(result)
{
        //body.innerHTML = result;
	t = document.getElementById("searchTarget");
	if ( t == null ) {
		oldbody=body.innerHTML;
		body.innerHTML= '<div id="searchTargetContainer"><div id="searchTarget" ></div></div>' ;
		t = document.getElementById("searchTarget");
	}
	t.innerHTML = result;
	t.style.display='block';
}

function Searching_Hide_Results()
{
	t = document.getElementById("searchTarget");
	t.style.display='none';
	body.innerHTML = oldbody;
}


// This will call the php function that will eventually
// return a results table
function Searching_Call()
{
	var x;
	Searching_Go();

	//Don't proceed if user is typing
	if (typing)
		return;

	x = document.getElementById("searchInput").value;

	// Don't search again if the query is the same
	if (x==memory)
		return;

	memory=x;
	if (started) {
		// Don't search for blank or < 3 chars.
		if ((x=="") || (x.length < 3))
		{
			return;
		}
		x_wfSajaxSearch(x, Searching_SetResult);
	}
}

function x_wfSajaxSearch() {
	sajax_do_call( "wfSajaxSearch", x_wfSajaxSearch.arguments );
}

	
//Initialize
function sajax_onload() {
	x = document.getElementById( 'searchInput' );
	x.onkeypress= function() { Search_Typing(); };
	Searching_Go();
	body = document.getElementById("content");
}

hookEvent("load", sajax_onload);
