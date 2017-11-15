/*jshint esversion: 6,  node:true */
/*global browser */

/**
 * The Page object contains shortcuts and properties you would expect
 * to find on a wiki page such as title, url.
 */

class Page {

	constructor( title ){
		// tag selector shortcut.
		// analogous to Ruby's link(:create_link, text: "Create") etc.
		// assuming first param is a selector, second is text.
		['h1',
		'table',
		'td',
		'a',
		'ul',
		'li',
		'button',
		'textarea',
		'div',
		'span',
		'p',
		'input[type=text]',
		'input[type=submit]',
		].forEach( ( el ) => {
			var alias = el;
			switch ( el ) {
				case 'a': alias = 'link'; break;
				case 'input[type=text]': alias = 'text_field'; break;
				case 'textarea': alias = 'text_area'; break;
				case 'p': alias = 'paragraph'; break;
				case 'ul': alias = 'unordered_list'; break;
				case 'td': alias = 'cell'; break;
			}
			// the text option here doesn't work on child selectors
			// when more that one element is returned.
			// so "table.many-tables td=text" doesn't work!
			this[el] = this[alias] = ( selector, text ) => {
				let s = selector || '';
				let t = ( text ) ? '='+text : '';
				let sel = el + s + t;
				let elems = browser.elements( sel );
				return elems;
			};
		} );
		this._title = title || '';
		this._url = `/wiki/${this._title}`;
	}

	get url() {
		return this._url;
	}
	set url( title ) {
		this._url = `/wiki/${title}`;
	}

	title( title ) {
		if ( !title ) {
			return this._title;
		} else {
			this.url = title;
			this._title = title;
			return this;
		}
	}
}
module.exports = Page;
