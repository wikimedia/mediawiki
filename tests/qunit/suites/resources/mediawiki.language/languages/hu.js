( function ( mw, $ ) {
	var data;

	QUnit.module( 'mediawiki.langage.hu' );

	data = [
		[ 'fát', 'fa', 't', null, null ],
		[ 'oldalnak', 'oldal', 'nak', 'nek', null ],
		[ 'embernek', 'ember', 'nak', 'nek', null ],
		[ 'sofőrnek', 'sofőr', 'nak', 'nek', null ],
		[ 'oldalhoz', 'oldal', 'hoz', 'hez', 'höz' ],
		[ 'emberhez', 'ember', 'hoz', 'hez', 'höz' ],
		[ 'sofőrhöz', 'sofőr', 'hoz', 'hez', 'höz' ],
		[ 'főnökhöz', 'főnök', 'hoz', 'hez', 'höz' ],
		[ 'haverhoz', 'haver', 'hoz', 'hez', 'höz' ],
		[ 'oldallal', 'oldal', 'val', 'vel', null ],
		[ 'sakkal', 'sakk', 'val', 'vel', null ],
		[ 'kéménnyel', 'kémény', 'val', 'vel', null ],
		[ 'passzal', 'passz', 'val', 'vel', null ],
		[ 'csévével', 'cséve', 'val', 'vel', null ],
		[ 'tevén', 'teve', 'on', 'en', 'ön' ],
		[ 'ValamilyenWikivel', 'ValamilyenWiki', 'val', 'vel', null ]
	];

	QUnit.test( 'addSuffix', data.length, function ( assert ) {
		$.each( data, function ( i, row ) {
			var expected = row[0],
				word = row[1],
				backSuffix = row[2],
				frontSuffix = row[3],
				labialSuffix = row[4];
			assert.strictEqual( expected, mw.language.addSuffix( word, backSuffix, frontSuffix, labialSuffix ) );
		} );
	} );

	data = [
		[ 'a', 'ház' ],
		[ 'az', 'ajtó' ]
	];

	QUnit.test( 'article', data.length, function ( assert ) {
		$.each( data, function ( i, row ) {
			var expected = row[0],
				word = row[1];
			assert.strictEqual( expected, mw.language.getArticle( word ) );
		} );
	} );

	data = [
		[ 'sofőrről', 'suffix', 'sofőr', 'ról', 'ről', null ],
		[ 'a', 'article', 'sofőr', null, null, null ],
		[ 'sofőrről', 'rol', 'sofőr', null, null, null ],
		[ 'sofőrbe', 'ba', 'sofőr', null, null, null ],
		[ 'kaszák', 'k', 'kasza', null, null, null ]
	];

	QUnit.test( 'convertGrammar', data.length, function ( assert ) {
		$.each( data, function ( i, row ) {
			var expected = row[0],
				type = row[1],
				param1 = row[2],
				param2 = row[3],
				param3 = row[4],
				param4 = row[5];
			assert.strictEqual( expected, mw.language.convertGrammar(
				type, param1, param2, param3, param4 ) );
		} );
	} );
} ( mediaWiki, jQuery ) );

