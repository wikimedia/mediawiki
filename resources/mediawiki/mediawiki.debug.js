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
			this.$container = $( '<div></div>' )
				.attr({
					id: 'mw-debug-container',
					class: 'mw-debug'
				});

			var html = '';

			html += '<div class="mw-debug-bit" id="mw-debug-mwversion">'
				+ '<a href="//www.mediawiki.org/">MediaWiki</a>: '
				+ this.data.mwVersion + '</div>';

			html += '<div class="mw-debug-bit" id="mw-debug-phpversion">'
				+ '<a href="//www.php.net/">PHP</a>: '
				+ this.data.phpVersion + '</div>';

			html += '<div class="mw-debug-bit" id="mw-debug-time">'
				+ 'Time: ' + this.data.time.toFixed( 5 ) + 's</div>';
			html += '<div class="mw-debug-bit" id="mw-debug-memory">'
				+ 'Memory: ' + this.data.memory + ' (<span title="Peak usage">'
				+ this.data.memoryPeak + '</span>)</div>';

			var queryLink = '<a href="#" class="mw-debug-panelink" id="mw-debug-query-link">Queries: '
				+ this.data.queries.length + '</a>';
			html += '<div class="mw-debug-bit" id="mw-debug-querylist">'
				+ queryLink + '</div>';

			var debugLink = '<a href="#" class="mw-debug-panelink" id="mw-debug-debuglog-link">Debug Log ('
				+ this.data.debugLog.length + ' lines)</a>';
			html += '<div class="mw-debug-bit" id="mw-debug-debuglog">'
				+ debugLink + '</div>';

			var requestLink = '<a href="#" class="mw-debug-panelink" id="mw-debug-request-link">Request</a>';
			html += '<div class="mw-debug-bit" id="mw-debug-request">'
				+ requestLink + '</div>';

			var filesLink = '<a href="#" class="mw-debug-panelink" id="mw-debug-files-includes">'
				+ this.data.includes.length + ' Files Included</a>';
			html += '<div class="mw-debug-bit" id="mw-debug-includes">'
				+ filesLink + '</div>';

			html += '<div class="mw-debug-pane" id="mw-debug-pane-querylist">'
				+ this.buildQueryTable() + '</div>';
			html += '<div class="mw-debug-pane" id="mw-debug-pane-debuglog">'
				+ this.buildDebugLogTable() + '</div>';
			html += '<div class="mw-debug-pane" id="mw-debug-pane-request">'
				+ this.buildRequestPane() + '</div>';
			html += '<div class="mw-debug-pane" id="mw-debug-pane-includes">'
				+ this.buildIncludesPane() + '</div>';

			this.$container.html( html );
		},

		/**
		 * Query list pane
		 */
		buildQueryTable: function() {
			var html = '<table id="mw-debug-querylist">';

			for ( var i = 0, length = this.data.queries.length; i < length; i++ ) {
				var query = this.data.queries[i];

				html += '<tr><td>' + ( i + 1 ) + '</td>';

				html += '<td>' + query.sql + '</td>';
                html += '<td><span class="mw-debug-query-time">(' + query.time.toFixed( 4 ) + 'ms)</span> ' + query.function + '</td>';

                html += '</tr>';
			}

			html += '</table>';

			return html;
		},

		/**
		 * Legacy debug log pane
		 */
		buildDebugLogTable: function() {
			var html = '<ul>';

			for ( var i = 0, length = this.data.debugLog.length; i < length; i++ ) {
				var line = this.data.debugLog[i];
				html += '<li>' + line.replace( /\n/g, "<br />\n" ) +  '</li>';
			}

			return html + '</ul>';
		},

		/**
		 * Request information pane
		 */
		buildRequestPane: function() {
			var buildTable = function( title, data ) {
				var t = '<h2>' + title + '</h2>'
					+ '<table> <tr> <th>Key</th> <th>Value</th> </tr>';

				for ( var key in data ) {
					if ( !data.hasOwnProperty( key ) ) {
						continue;
					}
					var value = data[key];

					t += '<tr><th>' + key + '</th><td>' + value + '</td></tr>';
				}

				t += '</table>';
				return t;
			};

			var html = this.data.request.method + ' ' + this.data.request.url;
			html += buildTable( 'Headers', this.data.request.headers );
			html += buildTable( 'Parameters', this.data.request.params );

			return html;
		},

		/**
		 * Included files pane
		 */
		buildIncludesPane: function() {
			var html = '<ul>';

			for ( var i = 0, l = this.data.includes.length; i < l; i++ ) {
				var file = this.data.includes[i];
				html += '<li><span class="mw-debug-right">' + file.size + '</span> ' + file.name + '</li>';
			}

			html += '</ul>';
			return html;
		}
	};

} )( jQuery );