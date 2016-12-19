/* global browser */
function Page() {
}

Page.prototype.open = function ( path ) {
	browser.url( '/index.php?title=' + path );
};

module.exports = new Page();
