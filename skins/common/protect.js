function protectInitialize(tableId, labelText) {
	if (document.createTextNode) {
		var box = document.getElementById(tableId);
		if (!box)
			return false;

		var tbody = box.getElementsByTagName('tbody')[0];
		var row = document.createElement('tr');
		tbody.appendChild(row);

		row.appendChild(document.createElement('td'));
		var col2 = document.createElement('td');
		row.appendChild(col2);

		var check = document.createElement('input');
		check.id = "mwProtectUnchained";
		check.type = "checkbox";
		check.addEventListener( 'click', protectChainUpdate, false );
		col2.appendChild(check);

		var space = document.createTextNode(" ");
		col2.appendChild(space);

		var label = document.createElement('label');
		label.setAttribute("for", "mwProtectUnchained");
		label.appendChild(document.createTextNode(labelText));
		col2.appendChild(label);

		if (protectAllMatch()) {
			check.checked = false;
			protectEnable(false);
		} else {
			check.checked = true;
			protectEnable(true);
		}
		
		allowCascade();

		return true;
	}
	return false;
}

function allowCascade() {
	var lists = protectSelectors();
	for( var i = 0; i < lists.length; i++ ) {
		if( lists[i].selectedIndex > -1 ) {
			var items = lists[i].getElementsByTagName( 'option' );
			var selected = items[ lists[i].selectedIndex ].value;
			if( wgCascadeableLevels.indexOf( selected ) == -1 ) {
				document.getElementById( 'mwProtect-cascade' ).checked = false;
				document.getElementById( 'mwProtect-cascade' ).disabled = true;
				return false;
			}
		}
	}
	document.getElementById( 'mwProtect-cascade' ).disabled = false;
	return true;
}

function protectLevelsUpdate(source) {
	if (!protectUnchained()) {
		protectUpdateAll(source.selectedIndex);
	}
	allowCascade();
}

function protectChainUpdate() {
	if (protectUnchained()) {
		protectEnable(true);
	} else {
		protectChain();
		protectEnable(false);
	}
	allowCascade();
}


function protectAllMatch() {
	var values = new Array();
	protectForSelectors(function(set) {
		values[values.length] = set.selectedIndex;
	});
	for (var i = 1; i < values.length; i++) {
		if (values[i] != values[0]) {
			return false;
		}
	}
	return true;
}

function protectUnchained() {
	var unchain = document.getElementById("mwProtectUnchained");
	if (!unchain) {
		alert("This shouldn't happen");
		return false;
	}
	return unchain.checked;
}

function protectChain() {
	// Find the highest-protected action and bump them all to this level
	var maxIndex = -1;
	protectForSelectors(function(set) {
		if (set.selectedIndex > maxIndex) {
			maxIndex = set.selectedIndex;
		}
	});
	protectUpdateAll(maxIndex);
}

function protectUpdateAll(index) {
	protectForSelectors(function(set) {
		if (set.selectedIndex != index) {
			set.selectedIndex = index;
		}
	});
}

function protectForSelectors(func) {
	var selectors = protectSelectors();
	for (var i = 0; i < selectors.length; i++) {
		func(selectors[i]);
	}
}

function protectSelectors() {
	var all = document.getElementsByTagName("select");
	var ours = new Array();
	for (var i = 0; i < all.length; i++) {
		var set = all[i];
		if (set.id.match(/^mwProtect-level-/)) {
			ours[ours.length] = set;
		}
	}
	return ours;
}

function protectEnable(val) {
	// fixme
	var first = true;
	protectForSelectors(function(set) {
		if (first) {
			first = false;
		} else {
			set.disabled = !val;
			set.style.visible = val ? "visible" : "hidden";
		}
	});
}
