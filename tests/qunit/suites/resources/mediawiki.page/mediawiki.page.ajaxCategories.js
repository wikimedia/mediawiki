(function( mw ) {

module( 'mediawiki.page.ajaxCategories.js' );
mw.config.set( 'wgNamespaceIds', {'category' : 14} );
test( '-- Initial check', function() {
	expect(1);
	ok( mw.ajaxCategories, 'mw.ajaxCategories defined' );
});

/**
 * Create a category list like the one found below articles.
 * @param {String[]} categories
 * @return jQuery
 */
var listCreate = function( categories ) {
	var $container = $('<div id="catlinks" class="catlinks"><div id="mw-normal-catlinks"><ul></ul></div></div>'),
		$ul = $container.find('ul');
	$.each( categories, function(i, str) {
		var $li = $('<li>');
		$li.text(str).appendTo($ul);
	});
	
	return $container;
};
catList1 = ['Earth satellites', 'Space stations', 'astronauts'];

test( 'Testing containsCat', function() {
	expect(1);
	$( 'body' ).append( listCreate(catList1) );
	mw.ajaxCategories.setup();
	var ret = mw.ajaxCategories.containsCat('Earth satellites')

	equal(ret, true);
});

})( mediaWiki );
console.log('wtf0');
(function( mw ) {

console.log('wtf1');
module( 'mediawiki.page.ajaxCategories.js' );
mw.config.set( 'wgNamespaceIds', {'category' : 14} );
test( '-- Initial check', function() {
	expect(1);
	ok( mw.ajaxCategories, 'mw.ajaxCategories defined' );
});

/**
 * Create a category list like the one found below articles.
 * @param {String[]} categories
 * @return jQuery
 */
var listCreate = function( categories ) {
	var $container = $('<div id="catlinks" class="catlinks"><div id="mw-normal-catlinks"><ul></ul></div></div>'),
		$ul = $container.find('ul');
	$.each( categories, function(i, str) {
		var $li = $('<li>');
		$li.text(str).appendTo($ul);
	});
	
	return $container;
};
catList1 = ['Earth satellites', 'Space stations', 'astronauts'];

test( 'Testing containsCat', function() {
	expect(1);
	$( 'body' ).append( listCreate(catList1) );
	mw.ajaxCategories.setup();
	var ret = mw.ajaxCategories.containsCat('Earth satellites')

	equal(ret, true);
});

})( mediaWiki );