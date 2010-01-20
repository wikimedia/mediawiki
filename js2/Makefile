all: js2stopgap.js js2stopgap.min.js

js2stopgap.js: jquery-1.4.js js2emulate.js
	cat jquery-1.4.js js2emulate.js > js2stopgap.js

js2stopgap.min.js: jquery-1.4.min.js js2emulate.js
	if [ -e ./jsmin ]; then ./jsmin < js2emulate.js | cat jquery-1.4.min.js - > js2stopgap.min.js;\
	else jsmin < js2emulate.js | cat jquery-1.4.min.js - > js2stopgap.min.js; fi

# JSMin - For more info on JSMin, see: http://www.crockford.com/javascript/jsmin.html
jsmin:
	type -P jsmin &>/dev/null || ( wget http://www.crockford.com/javascript/jsmin.c; gcc jsmin.c -o jsmin )

