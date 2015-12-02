Setup
-----

Make sure you have a newish version of Node installed
(`brew install node` on OS X).

    npm install

Running tests
-------------

Run the entire suite of tests using `npm run`.

    npm run selenium

Run a single test file by executing mocha directly and passing the specific
filename. 

    node_modules/.bin/mocha -- tests/selenium/{filename}.js

Currently, this test suite assumes you have a MediaWiki install running at
`http://127.0.0.1:8080/` (the default MediaWiki-Vagrant setup).
