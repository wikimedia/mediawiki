Modified version of Easy-Deflate https://github.com/Jacob-Christian-Munch-Andersen/Easy-Deflate

This version: https://github.com/edg2s/Easy-Deflate

* Added semi-colons to easydeflate.js so it can be minified
* Namespaced functions inside global EasyDeflate object
* Base64 lib replaced with one with detailed license info

Modifications by Ed Sanders, Public Domain.

Easy-Deflate
============

Library for compressing and decompressing strings in JavaScript, feature full Unicode support and is compatible with most browsers.

Use:
====
Copy the script inclusion from demo.html.<br>
Call EasyDeflate.deflate(foo) in order to compress a string.<br>
Call EasyDeflate.inflate(bar) in order to decompress a string compressed in this manner.<br>
Both functions return a string, or null in case of illegal input.

The compression works by first UTF-8 encoding the input, then compressing it to a raw deflate stream. The stream is then base64 encoded, and finally the identifier "rawdeflate," is prepended.

Credits:
========
Gildas Lormeau made the JavaScript conversion of a Deflate utility: https://github.com/gildas-lormeau/zip.js<br>
Jacob Christian Munch-Andersen made this package in order to make simple use easier and compatible with older browsers.

The following shims are included:<br>
es5-shim by Kristopher Michael Kowal https://github.com/kriskowal/es5-shim<br>
JSON 3 by Kit Cambridge http://bestiejs.github.com/json3/<br>
Typed arrays light shim by Jacob Christian Munch-Andersen https://github.com/Jacob-Christian-Munch-Andersen/Typed-arrays-light-shim<br>
<s>base64 by Yaffle https://gist.github.com/1284012</s>

License:
========
Main packages come with a BSD licence, the shims, except for base64 that include no license text, each has a permissive license.
