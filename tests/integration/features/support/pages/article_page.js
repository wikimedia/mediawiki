/*jshint esversion: 6,  node:true */

// TODO: Incomplete
// Page showing the article with some actions.  This is the page that everyone
// is used to reading on wikpedia.  My mom would recognize this page.

var Page = require('./page');

class ArticlePage extends Page {
	constructor(){
		super();
	}
}

module.exports = new ArticlePage();