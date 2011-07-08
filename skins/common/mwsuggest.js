var $container = $('<div>', {'class' : 'open-search-suggestions'}),
	cache = {},
	$t,
	url = mw.config.get('wgScriptPath') + '/api.php?format=json&action=opensearch&search=',
	maxRowWindow;
	
$('body').append( $container );

//Grabs namespaces from DOM
//@return: Array of Namespaces
getNamespaces = function() {
	var namespaces = [];
	$('form#powersearch, form#search').find( '[name^="ns"]' ).each(function() {
		if ( this.checked || ( this.type == 'hidden' && this.value == '1' ) ) {
			namespaces.push( this.name.substring( 2 ) );
		}
	});
	if ( !namespaces.length ) {
		namespaces = wgSearchNamespaces;
	}
	return namespaces.join('|');
};


deliverResult = function( obj, response, maxRowWindow ) {
	if (obj && obj.length > 1) {
		response( obj[1] );
		var maxRow = Math.min.apply( Math, [7, obj[1].length, maxRowWindow] );
		$t.css( 'height', maxRow * $t.find( '.ui-menu-item' ).eq( 0 ).height() );
	} else {
		response( [] );
	}
};

$("#searchInput").autocomplete({
	minLength: 2,
	source: function ( request, response ) {
		request.term = $.trim (request.term);
		var namespaces = getNamespaces();
		var term = request.term + namespaces;
		if (term in cache) {
			deliverResult( cache[term], response, maxRowWindow );
			return;
		}
		$.getJSON(url + mw.util.rawurlencode( request.term ) + '&namespace=' + namespaces, function ( obj ) {
			cache[term] = obj;
			deliverResult( obj, response, maxRowWindow );
		});
	},
	select: function() {
		$('#searchGoButton').click();
	},
	create : function() {
		$t = $container.find('ul');
	},
	appendTo: '.open-search-suggestions',
	open: function() {
		maxRowWindow = Math.floor( ( $(window).height() - $t.offset().top + $(window).scrollTop() ) / $t.find( '.ui-menu-item' ).eq( 0 ).height()  );
	},
	focus : function() {
		console.log('focused');
	}
});
$("#searchInput").data("autocomplete")._resizeMenu = function () {
};
// $("#searchInput").data("autocomplete")._resizeMenu = function () {
// 	var ul = this.menu.element;
// 	ul.width( "" );
// 	ul.children().each(function() {
// 		console.log($(this).width('').width());
// 	});
// 	//Fix weird webkit reflow bug
// 	// window.setTimeout(function(ul) {
// 	// 	var ul = $('.ui-autocomplete');
// 	// 	ul.outerWidth( Math.max(
// 	// 		ul.width( "" ).outerWidth(),
// 	// 		this.element.outerWidth()
// 	// 	) );
// 	// }, 80);
// 	// window.setTimeout(function(ul) {
// 		var ht = ul[0].offsetWidth
// 	// }, 10);
// 	// var children = ul.children();
// 	// ul.empty();
// 	// ul.append(children);
// 	
// 
// }
/* Teardown, called when things like SimpleSearch need to disable MWSuggest */
window.os_MWSuggestTeardown = function() {
	$("#searchInput").autocomplete( "destroy" );
};