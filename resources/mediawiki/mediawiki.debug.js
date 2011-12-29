/**
 * JavaScript for the new debug toolbar, enabled with $wgDebugToolbar
 *
 * @author John Du Hart
 * @since 1.19
 */

( function ( $, mw, undefined ) {
"use strict";

	var debug = mw.Debug = {
		/**
		 * Toolbar container element
		 *
		 * @var {jQuery}
		 */
		$container: null,

		/**
		 * Object containing data for the debug toolbar
		 *
		 * @var {Object}
		 */
		data: {},

		/**
		 * Initializes the debugging pane
		 *
		 * @param {Object} data
		 */
		init: function ( data ) {
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
		switchPane: function ( e ) {
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
		buildHtml: function () {
			var $container, panes, id;

			$container = $( '<div>' )
				.attr({
					id: 'mw-debug-container',
					'class': 'mw-debug'
				});

			/**
			 * Returns a jQuery element for a debug-bit div
			 *
			 * @param id
			 * @return {jQuery}
			 */
			function bitDiv( id ) {
				return $( '<div>' ).attr({
					id: 'mw-debug-' + id,
					'class': 'mw-debug-bit'
				});
			}

			/**
			 * Returns a jQuery element for a pane link
			 *
			 * @param id
			 * @param text
			 * @return {jQuery}
			 */
			function paneLink( id, text ) {
				return $( '<a>' )
					.attr({
						href: '#',
						'class': 'mw-debug-panelink',
						id: 'mw-debug-' + id + '-link'
					})
					.text( text );
			}

			bitDiv( 'mwversion' )
				.append( $( '<a href="//www.mediawiki.org/"></a>' ).text( 'MediaWiki' ) )
				.append( ': ' + this.data.mwVersion )
				.appendTo( $container );

			bitDiv( 'phpversion' )
				.append( $( '<a href="//www.php.net/"></a>' ).text( 'PHP' ) )
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

			panes = {
				querylist: this.buildQueryTable(),
				debuglog: this.buildDebugLogTable(),
				request: this.buildRequestPane(),
				includes: this.buildIncludesPane()
			};

			for ( id in panes ) {
				if ( !panes.hasOwnProperty( id ) ) {
					continue;
				}

				$( '<div>' )
					.attr({
						'class': 'mw-debug-pane',
						id: 'mw-debug-pane-' + id
					})
					.append( panes[id] )
					.appendTo( $container );
			}

			this.$container = $container;
		},

		/**
		 * Query list pane
		 */
		buildQueryTable: function () {
			var $table, i, length, query;

			$table = $( '<table id="mw-debug-querylist"></table>' );

			for ( i = 0, length = this.data.queries.length; i < length; i += 1 ) {
				query = this.data.queries[i];

				$( '<tr>' )
					.append( $( '<td>' ).text( i + 1 ) )
					.append( $( '<td>' ).text( query.sql ) )
					.append( $( '<td>' )
						.append( $( '<span class="mw-debug-query-time"></span>' ).text( '(' + query.time.toFixed( 4 ) + 'ms) ' ) )
						.append( query['function'] )
					)
					.appendTo( $table );
			}

			return $table;
		},

		/**
		 * Legacy debug log pane
		 */
		buildDebugLogTable: function () {
			var $list, i, length, line;
			$list = $( '<ul>' );

			for ( i = 0, length = this.data.debugLog.length; i < length; i += 1 ) {
				line = this.data.debugLog[i];
				$( '<li>' )
					.html( mw.html.escape( line ).replace( /\n/g, "<br />\n" ) )
					.appendTo( $list );
			}

			return $list;
		},

		/**
		 * Request information pane
		 */
		buildRequestPane: function () {

			function buildTable( title, data ) {
				var $unit, $table, key;

				$unit = $( '<div>' ).append( $( '<h2>' ).text( title ) );

				$table = $( '<table>' ).appendTo( $unit );

				$( '<tr>' )
					.html( '<th>Key</th><th>Value</th>' )
					.appendTo( $table );

				for ( key in data ) {
					if ( !data.hasOwnProperty( key ) ) {
						continue;
					}

					$( '<tr>' )
						.append( $( '<th>' ).text( key ) )
						.append( $( '<td>' ).text( data[key] ) )
						.appendTo( $table );
				}

				return $unit;
			}

			return $( '<div>' )
				.text( this.data.request.method + ' ' + this.data.request.url )
				.append( buildTable( 'Headers', this.data.request.headers ) )
				.append( buildTable( 'Parameters', this.data.request.params ) );
		},

		/**
		 * Included files pane
		 */
		buildIncludesPane: function () {
			var $list, i, len, file;

			$list = $( '<ul>' );

			for ( i = 0, len = this.data.includes.length; i < len; i += 1 ) {
				file = this.data.includes[i];
				$( '<li>' )
					.text( file.name )
					.prepend( $( '<span class="mw-debug-right"></span>' ).text( file.size ) )
					.appendTo( $list );
			}

			return $list;
		}
	};

} )( jQuery, mediaWiki );
