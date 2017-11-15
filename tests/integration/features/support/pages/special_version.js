/*jshint esversion: 6, node:true */

var Page = require('./page');

class SpecialVersion extends Page {
	constructor() {
		super();
	}

	software_table_row( name ) {
		return this.table('#sv-software').element( 'td=' + name ).value;
	}
}

module.exports = new SpecialVersion( 'Special:Version' );
