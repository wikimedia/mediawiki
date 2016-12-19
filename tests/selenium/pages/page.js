/* global browser */
function Page() {
	this.title = 'My Page';
}
Page.prototype.open = function ( path ) {
	browser.url( '/index.php?title=' + path );
};
module.exports = new Page();
