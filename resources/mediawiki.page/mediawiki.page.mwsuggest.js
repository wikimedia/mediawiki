jQuery( document ).ready( function( $ ) {
	var $container = $( '<div>', { 'class' : 'open-search-suggestions' } ),
		cache = {},
		$suggestionList,
		url = mw.util.wikiScript( 'api' ),
		maxRowWindow;
	
	//Append the container which will hold the menu to the body
	$( 'body' ).append( $container );

	/* Grabs namespaces from search form or
	 * in case we're not on a search page, take it from wgSearchNamespaces.
	 * @return Array: List of Namespaces that should be searched
	 */
	var getNamespaces = function() {
		var namespaces = [];
		$( 'form#powersearch, form#search' ).find( '[name^="ns"]' ).each(function() {
			if ( this.checked || ( this.type == 'hidden' && this.value == '1' ) ) {
				namespaces.push( this.name.substring( 2 ) );
			}
		});
		if ( !namespaces.length ) {
			namespaces = mw.config.get( 'wgSearchNamespaces' );
		}
		return namespaces.join('|');
	};

	/* Helper function to make sure that the list doesn't expand below the visible part of the window */
	var deliverResult = function( obj, response ) {
		if ( obj && obj.length > 1 ) {
			response( obj[1] );
			// Get the lowest from multiple numbers using fn.apply
			var maxRow = Math.min.apply( Math, [7, obj[1].length, maxRowWindow] );
			$suggestionList.css( 'height', maxRow * $suggestionList.find( '.ui-menu-item' ).eq( 0 ).height() );
		} else {
			response( [] );
		}
	};

	/* The actual autocomplete setup */
	$( "#searchInput" ).autocomplete({
		minLength: 2,
		source: function ( request, response ) {
			var namespaces = getNamespaces();
			// We're caching queries for performance
			var term = request.term + namespaces;
			if ( term in cache ) {
				deliverResult( cache[term], response );
				return;
			}
			var params = {
				format : 'json',
				action : 'opensearch',
				search : request.term,
				namespace : namespaces
			};
			$.getJSON( url, params, function ( obj ) {
				// Save to cache
				cache[ term ] = obj;
				deliverResult( obj, response );
			});
		},
		select : function() {
			$( '#searchGoButton' ).click();
		},
		create : function() {
			$suggestionList = $container.find( 'ul' );
		},
		appendTo : '.open-search-suggestions',
		open : function() {
			maxRowWindow = Math.floor(
				( $( window ).height() - $suggestionList.offset().top + $( window ).scrollTop() ) /
					$suggestionList.find( '.ui-menu-item' ).eq( 0 ).height()
			);
		}
	});

	/* Legacy teardown, called when things like SimpleSearch need to disable MWSuggest */
	window.os_MWSuggestDisable = function() {
		return $("#searchInput").autocomplete( "destroy" );
	};
});