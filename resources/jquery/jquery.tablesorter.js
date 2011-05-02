/*
 * 
 * TableSorter for MediaWiki
 * 
 * Written 2011 Leo Koppelkamm
 * Based on tablesorter.com plugin, written (c) 2007 Christian Bach.
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 */
/**
 * 
 * @description Create a sortable table with multi-column sorting capabilitys
 * 
 * @example $( 'table' ).tablesorter();
 * @desc Create a simple tablesorter interface.
 *
 * @option String cssHeader ( optional ) A string of the class name to be appended
 *         to sortable tr elements in the thead of the table. Default value:
 *         "header"
 * 
 * @option String cssAsc ( optional ) A string of the class name to be appended to
 *         sortable tr elements in the thead on a ascending sort. Default value:
 *         "headerSortUp"
 * 
 * @option String cssDesc ( optional ) A string of the class name to be appended
 *         to sortable tr elements in the thead on a descending sort. Default
 *         value: "headerSortDown"
 * 
 * @option String sortInitialOrder ( optional ) A string of the inital sorting
 *         order can be asc or desc. Default value: "asc"
 * 
 * @option String sortMultisortKey ( optional ) A string of the multi-column sort
 *         key. Default value: "shiftKey"
 *
 * @option Boolean sortLocaleCompare ( optional ) Boolean flag indicating whatever
 *         to use String.localeCampare method or not. Set to false.
 *
 * @option Boolean cancelSelection ( optional ) Boolean flag indicating if
 *         tablesorter should cancel selection of the table headers text.
 *         Default value: true
 * 
 * @option Boolean debug ( optional ) Boolean flag indicating if tablesorter
 *         should display debuging information usefull for development.
 * 
 * @type jQuery
 * 
 * @name tablesorter
 * 
 * @cat Plugins/Tablesorter
 * 
 * @author Christian Bach/christian.bach@polyester.se
 */

( function ($) {
	$.extend( {
		tablesorter: new

		function () {

			var parsers = [];

			this.defaults = {
				cssHeader: "headerSort",
				cssAsc: "headerSortUp",
				cssDesc: "headerSortDown",
				cssChildRow: "expand-child",
				sortInitialOrder: "asc",
				sortMultiSortKey: "shiftKey",
				sortLocaleCompare: false,
				parsers: {},
				widgets: [],
				headers: {},
				cancelSelection: true,
				sortList: [],
				headerList: [],
				selectorHeaders: 'thead tr:eq(0) th',
				debug: false
			};

			/* debuging utils */
			// 
			// function benchmark( s, d ) {
			//     console.log( s + " " + ( new Date().getTime() - d.getTime() ) + "ms" );
			// }
			// 
			// this.benchmark = benchmark;
			// 
			/* parsers utils */

			function buildParserCache( table, $headers ) {
				var rows = table.tBodies[0].rows,
					sortType;

				if ( rows[0] ) {

					var list = [],
						cells = rows[0].cells,
						l = cells.length;

					for ( var i = 0; i < l; i++ ) {
						p = false;
						sortType = $headers.eq(i).data('sort-type');
						if ( typeof sortType != 'undefined' ) {
							p = getParserById( sortType );
						}

						if (p === false) {
							p = detectParserForColumn( table, rows, i );
						}
						// if ( table.config.debug ) {
						//     console.log( "column:" + i + " parser:" + p.id + "\n" );
						// }
						list.push(p);
					}
				}
				return list;
			}

			function detectParserForColumn( table, rows, cellIndex ) {
				var l = parsers.length,
					nodeValue,
					// Start with 1 because 0 is the fallback parser
					i = 1,
					rowIndex = 0,
					concurrent = 0,
					needed = (rows.length > 4 ) ? 5 : rows.length;
				while( i<l ) {
					nodeValue = getTextFromRowAndCellIndex( rows, rowIndex, cellIndex );
					if ( nodeValue != '') {
						if ( parsers[i].is( nodeValue, table ) ) {
							concurrent++;
							rowIndex++;
							if (concurrent >= needed ) {
								// Confirmed the parser for multiple cells, let's return it
								return parsers[i];
							}
						} else {
							// Check next parser, reset rows
							i++;
							rowIndex = 0;
						}
					} else {
						// Empty cell
						rowIndex++;
						if ( rowIndex > rows.length ) {
							rowIndex = 0;
							i++;
						}
					}
				}
				
				// 0 is always the generic parser ( text )
				return parsers[0];
			}
			
			function getTextFromRowAndCellIndex( rows, rowIndex, cellIndex ) {
				if ( rows[rowIndex] && rows[rowIndex].cells[cellIndex] ) {
					return $.trim( getElementText( rows[rowIndex].cells[cellIndex] ) );
				} else {
					return '';
				}
			}
			
			function getParserById( name ) {
				var l = parsers.length;
				for ( var i = 0; i < l; i++ ) {
					if ( parsers[i].id.toLowerCase() == name.toLowerCase() ) {
						return parsers[i];
					}
				}
				return false;
			}

			/* utils */

			function buildCache( table ) {
				// if ( table.config.debug ) {
				//     var cacheTime = new Date();
				// }
				var totalRows = ( table.tBodies[0] && table.tBodies[0].rows.length ) || 0,
					totalCells = ( table.tBodies[0].rows[0] && table.tBodies[0].rows[0].cells.length ) || 0,
					parsers = table.config.parsers,
					cache = {
						row: [],
						normalized: []
					};

				for ( var i = 0; i < totalRows; ++i ) {

					// Add the table data to main data array
					var c = $( table.tBodies[0].rows[i] ),
						cols = [];

					// if this is a child row, add it to the last row's children and
					// continue to the next row
					if ( c.hasClass( table.config.cssChildRow ) ) {
						cache.row[cache.row.length - 1] = cache.row[cache.row.length - 1].add(c);
						// go to the next for loop
						continue;
					}

					cache.row.push(c);

					for ( var j = 0; j < totalCells; ++j ) {
						cols.push( parsers[j].format( getElementText( c[0].cells[j] ), table, c[0].cells[j] ) );
					}

					cols.push( cache.normalized.length ); // add position for rowCache
					cache.normalized.push( cols );
					cols = null;
				}

				// if ( table.config.debug ) {
				//     benchmark( "Building cache for " + totalRows + " rows:", cacheTime );
				// }
				return cache;
			}

			function getElementText( node ) {
				if ( node.hasAttribute && node.hasAttribute( "data-sort-value" ) ) {
					return node.getAttribute( "data-sort-value" );
				} else {
					return $( node ).text();
				}
			}

			function appendToTable( table, cache ) {
				// if ( table.config.debug ) {
				//      var appendTime = new Date()
				//  }
				var c = cache,
					r = c.row,
					n = c.normalized,
					totalRows = n.length,
					checkCell = (n[0].length - 1),
					tableBody = $( table.tBodies[0] ),
					fragment = document.createDocumentFragment();

				for ( var i = 0; i < totalRows; i++ ) {
					var pos = n[i][checkCell];

					var l = r[pos].length;

					for ( var j = 0; j < l; j++ ) {
						fragment.appendChild( r[pos][j] );
					}

				}
				tableBody[0].appendChild( fragment );
				// if ( table.config.debug ) {
				//      benchmark( "Rebuilt table:", appendTime );
				//  }
			}

			function buildHeaders( table ) {
				var maxSeen = 0;
				var longest;
				// if ( table.config.debug ) {
				//     var time = new Date();
				// }
				//var header_index = computeTableHeaderCellIndexes( table );
				var realCellIndex = 0;
				$tableHeaders = $( "thead:eq(0) tr", table );
				if ( $tableHeaders.length > 1 ) {
					$tableHeaders.each(function() {
						if (this.cells.length > maxSeen) {
							maxSeen = this.cells.length;
							longest = this;
						}
					});
					$tableHeaders = $( longest );
				}
				$tableHeaders = $tableHeaders.find('th').each( function ( index ) {
					//var normalIndex = allCells.index( this );
					//var realCellIndex = 0;
					this.column = realCellIndex;

					var colspan = this.colspan;
					colspan = colspan ? parseInt( colspan, 10 ) : 1;
					realCellIndex += colspan;

					//this.column = header_index[this.parentNode.rowIndex + "-" + this.cellIndex];
					this.order = 0;
					this.count = 0;

					if ( $( this ).is( '.unsortable' ) ) this.sortDisabled = true;

					if ( !this.sortDisabled ) {
						var $th = $( this ).addClass( table.config.cssHeader );
						//if ( table.config.onRenderHeader ) table.config.onRenderHeader.apply($th);
					}

					// add cell to headerList
					table.config.headerList[index] = this;
				} );

				// if ( table.config.debug ) {
				//     benchmark( "Built headers:", time );
				//     console.log( $tableHeaders );
				// }
				// 
				return $tableHeaders;

			}

			function isValueInArray( v, a ) {
				var l = a.length;
				for ( var i = 0; i < l; i++ ) {
					if ( a[i][0] == v ) {
						return true;
					}
				}
				return false;
			}

			function setHeadersCss( table, $headers, list, css ) {
				// remove all header information
				$headers.removeClass( css[0] ).removeClass( css[1] );

				var h = [];
				$headers.each( function ( offset ) {
					if ( !this.sortDisabled ) {
						h[this.column] = $( this );
					}
				} );

				var l = list.length;
				for ( var i = 0; i < l; i++ ) {
					h[list[i][0]].addClass( css[list[i][1]] );
				}
			}

			function checkSorting (array1, array2, sortList) {
				var col, fn, ret;
				for ( var i = 0, len = sortList.length; i < len; i++ ) {
					col = sortList[i][0];
					fn = ( sortList[i][1] ) ? sortTextDesc : sortText;
					ret = fn.call( this, array1[col], array2[col] );
					if ( ret !== 0 ) {
						return ret;
					}
				}
				return ret;
			}

			// Merge sort algorithm
			// Based on http://en.literateprograms.org/Merge_sort_(JavaScript)
			function mergeSortHelper(array, begin, beginRight, end, sortList) {
				for (; begin < beginRight; ++begin) {
					if (checkSorting( array[begin], array[beginRight], sortList )) {
						var v = array[begin];
						array[begin] = array[beginRight];
						var begin2 = beginRight;
						while ( begin2 + 1 < end && checkSorting( v, array[begin2 + 1], sortList ) ) {
							var tmp = array[begin2];
							array[begin2] = array[begin2 + 1];
							array[begin2 + 1] = tmp;
							++begin2;
						}
						array[begin2] = v;
					}
				}
			}

			function mergeSort(array, begin, end, sortList) {
				var size = end - begin;
				if (size < 2) return;

				var beginRight = begin + Math.floor(size / 2);

				mergeSort(array, begin, beginRight, sortList);
				mergeSort(array, beginRight, end, sortList);
				mergeSortHelper(array, begin, beginRight, end, sortList);
			}

			var lastSort = '';
			
			function multisort( table, sortList, cache ) {
				//var sortTime = new Date();
		    
				var i = sortList.length;
				if ( i == 1 && sortList[0][0] === lastSort) {
					// Special case a simple reverse
					cache.normalized.reverse();
				} else {
					mergeSort(cache.normalized, 0, cache.normalized.length, sortList);
				}
				lastSort = ( sortList.length == 1 ) ? sortList[0][0] : '';

				//benchmark( "Sorting in dir " + order + " time:", sortTime );
		    
				return cache;
			}

			function sortText( a, b ) {
				return ((a < b) ? false : ((a > b) ? true : 0));
			}

			function sortTextDesc( a, b ) {
				return ((b < a) ? false : ((b > a) ? true : 0));
			}

			function buildTransformTable() {
				var digits = '0123456789,.'.split('');

				if ( typeof wgSeparatorTransformTable == 'undefined' || ( wgSeparatorTransformTable[0] == '' && wgDigitTransformTable[2] == '' ) ) {
					ts.transformTable = false;
				} else {
					ts.transformTable = {};

					// Unpack the transform table
					var ascii = wgSeparatorTransformTable[0].split( "\t" ).concat( wgDigitTransformTable[0].split( "\t" ) );
					var localised = wgSeparatorTransformTable[1].split( "\t" ).concat( wgDigitTransformTable[1].split( "\t" ) );

					// Construct regex for number identification
					for ( var i = 0; i < ascii.length; i++ ) {
						ts.transformTable[localised[i]] = ascii[i];
						digits.push( $.escapeRE( localised[i] ) );
					}
				}
				var digitClass = '[' + digits.join( '', digits ) + ']';

				// We allow a trailing percent sign, which we just strip.  This works fine
				// if percents and regular numbers aren't being mixed.
				ts.numberRegex = new RegExp("^(" + "[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?" + // Fortran-style scientific
				"|" + "[-+\u2212]?" + digitClass + "+[\\s\\xa0]*%?" + // Generic localised
				")$", "i");
			}

			function buildDateTable() {
				var r = '';
				ts.monthNames = [
					[],
					[]
				];
				ts.dateRegex = [];

				for ( i = 1; i < 13; i++ ) {
					ts.monthNames[0][i] = wgMonthNames[i].toLowerCase();
					ts.monthNames[1][i] = wgMonthNamesShort[i].toLowerCase().replace( '.', '' );
					r += $.escapeRE( ts.monthNames[0][i] ) + '|';
					r += $.escapeRE( ts.monthNames[1][i] ) + '|';
				}

				//Remove trailing pipe
				r = r.slice( 0, -1 );

				//Build RegEx
				//Any date formated with . , ' - or /
				ts.dateRegex[0] = new RegExp(/^\s*\d{1,2}[\,\.\-\/'\s]*\d{1,2}[\,\.\-\/'\s]*\d{2,4}\s*?/i);

				//Written Month name, dmy
				ts.dateRegex[1] = new RegExp('^\\s*\\d{1,2}[\\,\\.\\-\\/\'\\s]*(' + r + ')' + '[\\,\\.\\-\\/\'\\s]*\\d{2,4}\\s*$', 'i');

				//Written Month name, mdy
				ts.dateRegex[2] = new RegExp('^\\s*(' + r + ')' + '[\\,\\.\\-\\/\'\\s]*\\d{1,2}[\\,\\.\\-\\/\'\\s]*\\d{2,4}\\s*$', 'i');

			}

			function explodeRowspans( $table ) {
				// Split multi row cells into multiple cells with the same content
				$table.find( '[rowspan]' ).each(function() {
					var rowSpan = this.rowSpan;
					this.rowSpan = 1;
					var cell = $( this );
					var next = cell.parent().nextAll();
					for ( var i = 0; i < rowSpan - 1; i++ ) {
						next.eq(0).find( 'td' ).eq( this.cellIndex ).before( cell.clone() );
					}
				});
			}

			function buildCollationTable() {
				ts.collationTable = mw.config.get('tableSorterCollation');
				if ( typeof ts.collationTable === "object" ) {
					ts.collationRegex = [];

					//Build array of key names
					for ( var key in ts.collationTable ) {
						if ( ts.collationTable.hasOwnProperty(key) ) { //to be safe
							ts.collationRegex.push(key);
						}
					}
					ts.collationRegex = new RegExp( '[' + ts.collationRegex.join('') + ']', 'ig' );
				}
			}

			function cacheRegexs() {
				ts.rgx = {
					IPAddress: [new RegExp(/^\d{1,3}[\.]\d{1,3}[\.]\d{1,3}[\.]\d{1,3}$/)],
					currency: [new RegExp(/^[£$€?.]/), new RegExp(/[£$€]/g)],
					url: [new RegExp(/^(https?|ftp|file):\/\/$/), new RegExp(/(https?|ftp|file):\/\//)],
					isoDate: [new RegExp(/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/)],
					usLongDate: [new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/)],
					time: [new RegExp(/^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/)]
				};
			} /* public methods */
			this.construct = function ( settings ) {
				return this.each( function () {
					// if no thead or tbody quit.
					if ( !this.tHead || !this.tBodies ) return;
					// declare
					var $this, $document, $headers, cache, config, shiftDown = 0,
						sortOrder, firstTime = true, that = this;
					// new blank config object
					this.config = {};
					// merge and extend.
					config = $.extend( this.config, $.tablesorter.defaults, settings );

					// store common expression for speed
					$this = $( this );
					// save the settings where they read
					$.data( this, "tablesorter", config );
					// build headers
					$headers = buildHeaders( this );
					// Grab and process locale settings
					buildTransformTable();
					buildDateTable();
					buildCollationTable();

					//Precaching regexps can bring 10 fold 
					//performance improvements in some browsers
					cacheRegexs();

					// get the css class names, could be done else where.
					var sortCSS = [config.cssDesc, config.cssAsc];
					// apply event handling to headers
					// this is to big, perhaps break it out?
					$headers.click( 

					function (e) {
						//var clickTime= new Date();
						if (firstTime) {
							firstTime = false;
							explodeRowspans( $this );
							// try to auto detect column type, and store in tables config
							that.config.parsers = buildParserCache( that, $headers );
							// build the cache for the tbody cells
							cache = buildCache( that );
						}
						var totalRows = ( $this[0].tBodies[0] && $this[0].tBodies[0].rows.length ) || 0;
						if ( !this.sortDisabled && totalRows > 0 ) {
							// Only call sortStart if sorting is
							// enabled.
							//$this.trigger( "sortStart" );

							// store exp, for speed
							var $cell = $( this );
							// get current column index
							var i = this.column;
							// get current column sort order
							this.order = this.count % 2;
							this.count++;
							// user only whants to sort on one
							// column
							if ( !e[config.sortMultiSortKey] ) {
								// flush the sort list
								config.sortList = [];
								// add column to sort list
								config.sortList.push( [i, this.order] );
								// multi column sorting
							} else {
								// the user has clicked on an already sorted column.
								if ( isValueInArray( i, config.sortList ) ) {
									// revers the sorting direction
									// for all tables.
									for ( var j = 0; j < config.sortList.length; j++ ) {
										var s = config.sortList[j],
											o = config.headerList[s[0]];
										if ( s[0] == i ) {
											o.count = s[1];
											o.count++;
											s[1] = o.count % 2;
										}
									}
								} else {
									// add column to sort list array
									config.sortList.push( [i, this.order] );
								}
							}
							setTimeout( function () {
								// set css for headers
								setHeadersCss( $this[0], $headers, config.sortList, sortCSS );
								appendToTable( 
								$this[0], multisort( 
								$this[0], config.sortList, cache ) );
								//benchmark( "Sorting " + totalRows + " rows:", clickTime );
							}, 1 );
							// stop normal event by returning false
							return false;
						}
						// cancel selection
					} ).mousedown( function () {
						if ( config.cancelSelection ) {
							this.onselectstart = function () {
								return false;
							};
							return false;
						}
					} );
					// apply easy methods that trigger binded events
					//Can't think of any use for these in a mw context
					// $this.bind( "update", function () {
					//     var me = this;
					//     setTimeout( function () {
					//         // rebuild parsers.
					//         me.config.parsers = buildParserCache( 
					//         me, $headers );
					//         // rebuild the cache map
					//         cache = buildCache(me);
					//     }, 1 );
					// } ).bind( "updateCell", function ( e, cell ) {
					//     var config = this.config;
					//     // get position from the dom.
					//     var pos = [( cell.parentNode.rowIndex - 1 ), cell.cellIndex];
					//     // update cache
					//     cache.normalized[pos[0]][pos[1]] = config.parsers[pos[1]].format( 
					//     getElementText( cell ), cell );
					// } ).bind( "sorton", function ( e, list ) {
					//     $( this ).trigger( "sortStart" );
					//     config.sortList = list;
					//     // update and store the sortlist
					//     var sortList = config.sortList;
					//     // update header count index
					//     updateHeaderSortCount( this, sortList );
					//     // set css for headers
					//     setHeadersCss( this, $headers, sortList, sortCSS );
					//     // sort the table and append it to the dom
					//     appendToTable( this, multisort( this, sortList, cache ) );
					// } ).bind( "appendCache", function () {
					//     appendToTable( this, cache );
					// } );
				} );
			};
			this.addParser = function ( parser ) {
				var l = parsers.length,
					a = true;
				for ( var i = 0; i < l; i++ ) {
					if ( parsers[i].id.toLowerCase() == parser.id.toLowerCase() ) {
						a = false;
					}
				}
				if (a) {
					parsers.push( parser );
				}
			};
			this.formatDigit = function (s) {
				if ( ts.transformTable != false ) {
					var out = '',
						c;
					for ( var p = 0; p < s.length; p++ ) {
						c = s.charAt(p);
						if ( c in ts.transformTable ) {
							out += ts.transformTable[c];
						} else {
							out += c;
						}
					}
					s = out;
				}
				var i = parseFloat( s.replace(/[, ]/g, '').replace( "\u2212", '-' ) );
				return ( isNaN(i)) ? 0 : i;
			};
			this.formatFloat = function (s) {
				var i = parseFloat(s);
				return ( isNaN(i)) ? 0 : i;
			};
			this.formatInt = function (s) {
				var i = parseInt( s, 10 );
				return ( isNaN(i)) ? 0 : i;
			};
			this.clearTableBody = function ( table ) {
				if ( $.browser.msie ) {
					function empty() {
						while ( this.firstChild )
						this.removeChild( this.firstChild );
					}
					empty.apply( table.tBodies[0] );
				} else {
					table.tBodies[0].innerHTML = "";
				}
			};
		}
	} );

	// extend plugin scope
	$.fn.extend( {
		tablesorter: $.tablesorter.construct
	} );

	// make shortcut
	var ts = $.tablesorter;

	// add default parsers
	ts.addParser( {
		id: "text",
		is: function (s) {
			return true;
		},
		format: function (s) {
			s = $.trim( s.toLowerCase() );
			if ( ts.collationRegex ) {
				var tsc = ts.collationTable;
				s = s.replace( ts.collationRegex, function ( match ) {
					var r = tsc[match] ? tsc[match] : tsc[match.toUpperCase()];
					return r.toLowerCase();
				} );
			}
			return s;
		},
		type: "text"
	} );

	ts.addParser( {
		id: "IPAddress",
		is: function (s) {
			return ts.rgx.IPAddress[0].test(s);
		},
		format: function (s) {
			var a = s.split("."),
				r = "",
				l = a.length;
			for ( var i = 0; i < l; i++ ) {
				var item = a[i];
				if ( item.length == 2 ) {
					r += "0" + item;
				} else {
					r += item;
				}
			}
			return $.tablesorter.formatFloat(r);
		},
		type: "numeric"
	} );

	ts.addParser( {
		id: "currency",
		is: function (s) {
			return ts.rgx.currency[0].test(s);
		},
		format: function (s) {
			return $.tablesorter.formatDigit( s.replace( ts.rgx.currency[1], "" ) );
		},
		type: "numeric"
	} );

	ts.addParser( {
		id: "url",
		is: function (s) {
			return ts.rgx.url[0].test(s);
		},
		format: function (s) {
			return $.trim( s.replace( ts.rgx.url[1], '' ) );
		},
		type: "text"
	} );

	ts.addParser( {
		id: "isoDate",
		is: function (s) {
			return ts.rgx.isoDate[0].test(s);
		},
		format: function (s) {
			return $.tablesorter.formatFloat((s != "") ? new Date(s.replace(
			new RegExp(/-/g), "/")).getTime() : "0");
		},
		type: "numeric"
	} );

	ts.addParser( {
		id: "usLongDate",
		is: function (s) {
			return ts.rgx.usLongDate[0].test(s);
		},
		format: function (s) {
			return $.tablesorter.formatFloat( new Date(s).getTime() );
		},
		type: "numeric"
	} );

	ts.addParser( {
		id: "date",
		is: function (s) {
			return ( ts.dateRegex[0].test(s) || ts.dateRegex[1].test(s) || ts.dateRegex[2].test(s ));
		},
		format: function ( s, table ) {
			s = $.trim( s.toLowerCase() );

			for ( i = 1, j = 0; i < 13 && j < 2; i++ ) {
				s = s.replace( ts.monthNames[j][i], i );
				if ( i == 12 ) {
					j++;
					i = 0;
				}
			}

			s = s.replace(/[\-\.\,' ]/g, "/");

			//Replace double slashes
			s = s.replace(/\/\//g, "/");
			s = s.replace(/\/\//g, "/");
			s = s.split('/');

			//Pad Month and Day
			if ( s[0] && s[0].length == 1 ) s[0] = "0" + s[0];
			if ( s[1] && s[1].length == 1 ) s[1] = "0" + s[1];

			if ( !s[2] ) {
				//Fix yearless dates
				s[2] = 2000;
			} else if ( ( y = parseInt( s[2], 10) ) < 100 ) {
				//Guestimate years without centuries
				if ( y < 30 ) {
					s[2] = 2000 + y;
				} else {
					s[2] = 1900 + y;
				}
			}
			//Resort array depending on preferences
			if ( wgDefaultDateFormat == "mdy" ) {
				s.push( s.shift() );
				s.push( s.shift() );
			} else if ( wgDefaultDateFormat == "dmy" ) {
				var d = s.shift();
				s.push( s.shift() );
				s.push(d);
			}
			return parseInt( s.join(''), 10 );
		},
		type: "numeric"
	} );
	ts.addParser( {
		id: "time",
		is: function (s) {
			return ts.rgx.time[0].test(s);
		},
		format: function (s) {
			return $.tablesorter.formatFloat( new Date( "2000/01/01 " + s ).getTime() );
		},
		type: "numeric"
	} );
	ts.addParser( {
		id: "number",
		is: function ( s, table ) {
			return $.tablesorter.numberRegex.test( $.trim(s ));
		},
		format: function (s) {
			return $.tablesorter.formatDigit(s);
		},
		type: "numeric"
	} );
	
} )( jQuery );