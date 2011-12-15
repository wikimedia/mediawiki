/**
 * Javascript for the new debug toolbar, enabled with $wgDebugToolbar
 *
 * @author John Du Hart
 * @since 1.19
 */

( function( $ ) {

	var debug = mw.Debug = {
		/**
		 * Toolbar container element
		 *
		 * @var {jQuery}
		 */
		$container: null,

		/**
		 * Array containing data for the debug toolbar
		 *
		 * @var {Array}
		 */
		data: {},

		/**
		 * Initializes the debugging pane
		 *
		 * @param {Array} data
		 */
		init: function( data ) {
			this.data = data;
			this.buildHtml();

			// Insert the container into the DOM
			$( 'body' ).append( this.$container );

			$( '.mw-debug-panelink' ).click( this.switchPane );
		},

		/**
		 * Switches between panes
		 *
		 * @todo Store cookie for last pane open
		 * @context {Element}
		 * @param {jQuery.Event} e
		 */
		switchPane: function( e ) {
			var currentPaneId = debug.$container.data( 'currentPane' ),
				requestedPaneId = $(this).parent().attr( 'id' ).substr( 9 ),
				$currentPane = $( '#mw-debug-pane-' + currentPaneId ),
				$requestedPane = $( '#mw-debug-pane-' + requestedPaneId );
			e.preventDefault();

			// Hide the current pane
			if ( requestedPaneId === currentPaneId ) {
				$currentPane.slideUp();
				debug.$container.data( 'currentPane', null );
				return;
			}

			debug.$container.data( 'currentPane', requestedPaneId );

			if ( currentPaneId === undefined || currentPaneId === null ) {
				$requestedPane.slideDown();
			} else {
				$currentPane.hide();
				$requestedPane.show();
			}
		},

		/**
		 * Constructs the HTML for the debugging toolbar
		 */
		buildHtml: function() {
			var $container = this.$container = $( '<div></div>' )
				.attr({
					id: 'mw-debug-container',
					class: 'mw-debug'
				});

			/**
			 * Returns a jQuery element for a debug-bit div
			 *
			 * @param id
			 * @return {jQuery}
			 */
			var bitDiv = function( id ) {
				return $( '<div></div>' ).attr({
					id: 'mw-debug-' + id,
					class: 'mw-debug-bit'
				});
			};
			/**
			 * Returns a jQuery element for a pane link
			 *
			 * @param id
			 * @param text
			 * @return {jQuery}
			 */
			var paneLink = function( id, text ) {
				return $( '<a></a>' )
					.attr({
						href: '#',
						class: 'mw-debug-panelink',
						id: 'mw-debug-' + id + '-link'
					})
					.text( text );
			}

			bitDiv( 'mwversion' )
				.append( $( '<a href="//www.mediawiki.org/">' ).text( 'MediaWiki' ) )
				.append( ': ' + this.data.mwVersion )
				.appendTo( $container );

			bitDiv( 'phpversion' )
				.append( $( '<a href="//www.php.net/">' ).text( 'PHP' ) )
				.append( ': ' + this.data.phpVersion )
				.appendTo( $container );

			bitDiv( 'time' )
				.text( 'Time: ' + this.data.time.toFixed( 5 ) )
				.appendTo( $container );
			bitDiv( 'memory' )
				.text( 'Memory: ' + this.data.memory )
				.append( $( '<span title="Peak usage"></span>' ).text( ' (' + this.data.memoryPeak + ')' ) )
				.appendTo( $container );

			bitDiv( 'querylist' )
				.append( paneLink( 'query', 'Queries: ' + this.data.queries.length ) )
				.appendTo( $container );

			bitDiv( 'debuglog' )
				.append( paneLink( 'debuglog', 'Debug Log (' + this.data.debugLog.length + ' lines)' ) )
				.appendTo( $container );

			bitDiv( 'request' )
				.append( paneLink( 'request', 'Request' ) )
				.appendTo( $container );

			bitDiv( 'includes' )
				.append( paneLink( 'files-includes', this.data.includes.length + ' Files Included' ) )
				.appendTo( $container );

			var panes = {
				'querylist': this.buildQueryTable(),
				'debuglog': this.buildDebugLogTable(),
				'request': this.buildRequestPane(),
				'includes': this.buildIncludesPane()
			};

			for ( var id in panes ) {
				if ( !panes.hasOwnProperty( id ) ) {
					continue;
				}

				$( '<div></div>' )
					.attr({
						class: 'mw-debug-pane',
						id: 'mw-debug-pane-' + id
					})
					.append( panes[id] )
					.appendTo( $container );
			}
		},

		/**
		 * Query list pane
		 */
		buildQueryTable: function() {
			var $table = $( '<table id="mw-debug-querylist"></table>' );

			for ( var i = 0, length = this.data.queries.length; i < length; i++ ) {
				var query = this.data.queries[i];

				$( '<tr>' )
					.append( $( '<td>' ).text( i + 1 ) )
					.append( $( '<td>' ).text( query.sql ) )
					.append( $( '<td>' )
						.append( $( '<span class="mw-debug-query-time">' ).text( '(' + query.time.toFixed( 4 ) + 'ms) ' ) )
						.append( query['function'] )
					)
					.appendTo( $table );
			}

			return $table;
		},

		/**
		 * Legacy debug log pane
		 */
		buildDebugLogTable: function() {
			var $list = $( '<ul>' );

			for ( var i = 0, length = this.data.debugLog.length; i < length; i++ ) {
				var line = this.data.debugLog[i];
				$( '<li>' )
					.html( mw.html.escape( line ).replace( /\n/g, "<br />\n" ) )
					.appendTo( $list );
			}

			return $list;
		},

		/**
		 * Request information pane
		 */
		buildRequestPane: function() {
			var buildTable = function( title, data ) {
				var $unit = $( '<div></div>' )
					.append( $( '<h2>' ).text( title ) );

				var $table = $( '<table>' ).appendTo( $unit );

				$( '<tr>' )
					.html( '<th>Key</th> <th>Value</th>' )
					.appendTo( $table );

				for ( var key in data ) {
					if ( !data.hasOwnProperty( key ) ) {
						continue;
					}

					$( '<tr>' )
						.append( $( '<th>' ).text( key ) )
						.append( $( '<td>' ).text( data[key] ) )
						.appendTo( $table );
				}

				return $unit;
			};

			var $pane = $( '<div>' )
				.text( this.data.request.method + ' ' + this.data.request.url )
				.append( buildTable( 'Headers', this.data.request.headers ) )
				.append( buildTable( 'Parameters', this.data.request.params ) );
			return $pane;
		},

		/**
		 * Included files pane
		 */
		buildIncludesPane: function() {
			var $list = $( '<ul>' )

			for ( var i = 0, l = this.data.includes.length; i < l; i++ ) {
				var file = this.data.includes[i];
				$( '<li>' )
					.text( file.name )
					.prepend( $( '<span class="mw-debug-right">' ).text( file.size ) )
					.appendTo( $list );
			}

			return $list;
		}
	};

} )( jQuery );