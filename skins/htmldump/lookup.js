/**
 * "Go" function for static HTML dump
 */
function goToStatic(depth) {
	var url = getStaticURL(document.getElementById("searchInput").value, depth);
	if (url != "") {
		location = url;
	} else {
		alert("Invalid title");
	}
}

/**
 * Determine relative path for a given non-canonical title
 */
function getStaticURL(text, depth) {
	var pdbk = getPDBK(text);
	if (pdbk == "") {
		return "";
	} else {
		var i;
		var path = getHashedDirectory(pdbk, depth) + "/" + getFriendlyName(pdbk) + ".html";
		if (!/(index\.html|\/)$/.exec(location)) {
			for (i = 0; i < depth; i++) {
				path = "../" + path;
			}
		}
		return path;
	}
}

function getPDBK(text) {
	// Spaces to underscores
	text = text.replace(/ /g, "_");

	// Trim leading and trailing space
	text = text.replace(/^_+/g, "");
	text = text.replace(/_+$/g, "");

	// Capitalise first letter
	return ucfirst(text);
}

function getHashedDirectory(pdbk, depth) {
	// Find the first colon if there is one, use characters after it
	var dbk = pdbk.replace(/^[^:]*:_*(.*)$/, "$1");
	var i, c, dir = "";

	for (i=0; i < depth; i++) {
		if (i) {
			dir += "/";
		}
		if (i >= dbk.length) {
			dir += "_";
		} else {
			c = dbk.charAt(i);
			cc = dbk.charCodeAt(i);
			
			if (cc >= 128 || /[a-zA-Z0-9!#$%&()+,[\]^_`{}-]/.exec(c)) {
				dir += c.toLowerCase();
			} else {
				dir += binl2hex([cc]).substr(0,2).toUpperCase();
			}
		}
	}
	return dir;
}

function ucfirst(s) {
	return s.charAt(0).toUpperCase() + s.substring(1, s.length);
}

function getFriendlyName(name) {
	// Replace illegal characters for Windows paths with underscores
	var friendlyName = name.replace(/[\/\\*?"<>|~]/g, "_");

	// Work out lower case form. We assume we're on a system with case-insensitive
	// filenames, so unless the case is of a special form, we have to disambiguate
	var lowerCase = ucfirst(name.toLowerCase());

	// Make it mostly unique
	if (lowerCase != friendlyName) {
		friendlyName += "_" + hex_md5(_to_utf8(name)).substring(0, 4);
	}
	// Handle colon specially by replacing it with tilde
	// Thus we reduce the number of paths with hashes appended
	friendlyName = friendlyName.replace(":", "~");

	return friendlyName;
}

