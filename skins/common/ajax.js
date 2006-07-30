// remote scripting library
// (c) copyright 2005 modernmethod, inc
var sajax_debug_mode = false;
var sajax_request_type = "GET";

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
