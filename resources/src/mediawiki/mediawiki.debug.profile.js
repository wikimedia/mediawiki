/*!
 * JavaScript for the debug toolbar profiler, enabled through $wgDebugToolbar
 * and StartProfiler.php.
 *
 * @author Erik Bernhardson
 * @since 1.23
 */

( function ( mw, $ ) {
	'use strict';

	/**
	 * @singleton
	 * @class mw.Debug.profile
	 */
	var profile = mw.Debug.profile = {
		/**
		 * Object containing data for the debug toolbar
		 *
		 * @property ProfileData
		 */
		data: null,

		/**
		 * @property DOMElement
		 */
		container: null,

		/**
		 * Initializes the profiling pane.
		 */
		init: function ( data, width, mergeThresholdPx, dropThresholdPx ) {
			data = data || mw.config.get( 'debugInfo' ).profile;
			profile.width = width || $(window).width() - 20;
			// merge events from same pixel(some events are very granular)
			mergeThresholdPx = mergeThresholdPx || 2;
			// only drop events if requested
			dropThresholdPx = dropThresholdPx || 0;

			if ( !Array.prototype.map || !Array.prototype.reduce || !Array.prototype.filter ) {
				profile.container = profile.buildRequiresES5();
			} else if ( data.length === 0 ) {
				profile.container = profile.buildNoData();
			} else {
				// generate a flyout
				profile.data = new ProfileData( data, profile.width, mergeThresholdPx, dropThresholdPx );
				// draw it
				profile.container = profile.buildSvg( profile.container );
				profile.attachFlyout();
			}

			return profile.container;
		},

		buildRequiresES5: function () {
			return $( '<div>' )
				.text( 'An ES5 compatible javascript engine is required for the profile visualization.' )
				.get( 0 );
		},

		buildNoData: function () {
			return $( '<div>' ).addClass( 'mw-debug-profile-no-data' )
				.text( 'No events recorded, ensure profiling is enabled in StartProfiler.php.' )
				.get( 0 );
		},

		/**
		 * Creates DOM nodes appropriately namespaced for SVG.
		 *
		 * @param string tag to create
		 * @return DOMElement
		 */
		createSvgElement: document.createElementNS
			? document.createElementNS.bind( document, 'http://www.w3.org/2000/svg' )
			// throw a error for browsers which does not support document.createElementNS (IE<8)
			: function () { throw new Error( 'document.createElementNS not supported' ); },

		/**
		 * @param DOMElement|undefined
		 */
		buildSvg: function ( node ) {
			var container, group, i, g,
				timespan = profile.data.timespan,
				gapPerEvent = 38,
				space = 10.5,
				currentHeight = space,
				totalHeight = 0;

			profile.ratio = ( profile.width - space * 2 ) / ( timespan.end - timespan.start );
			totalHeight += gapPerEvent * profile.data.groups.length;

			if ( node ) {
				$( node ).empty();
			} else {
				node = profile.createSvgElement( 'svg' );
				node.setAttribute( 'version', '1.2' );
				node.setAttribute( 'baseProfile', 'tiny' );
			}
			node.style.height = totalHeight;
			node.style.width = profile.width;

			// use a container that can be transformed
			container = profile.createSvgElement( 'g' );
			node.appendChild( container );

			for ( i = 0; i < profile.data.groups.length; i++ ) {
				group = profile.data.groups[i];
				g = profile.buildTimeline( group );

				g.setAttribute( 'transform', 'translate( 0 ' + currentHeight + ' )' );
				container.appendChild( g );

				currentHeight += gapPerEvent;
			}

			return node;
		},

		/**
		 * @param Object group of periods to transform into graphics
		 */
		buildTimeline: function ( group ) {
			var text, tspan, line, i,
				sum = group.timespan.sum,
				ms = ' ~ ' + ( sum < 1 ? sum.toFixed( 2 ) : sum.toFixed( 0 ) ) + ' ms',
				timeline = profile.createSvgElement( 'g' );

			timeline.setAttribute( 'class', 'mw-debug-profile-timeline' );

			// draw label
			text = profile.createSvgElement( 'text' );
			text.setAttribute( 'x', profile.xCoord( group.timespan.start ) );
			text.setAttribute( 'y', 0 );
			text.textContent = group.name;
			timeline.appendChild( text );

			// draw metadata
			tspan = profile.createSvgElement( 'tspan' );
			tspan.textContent = ms;
			text.appendChild( tspan );

			// draw timeline periods
			for ( i = 0; i < group.periods.length; i++ ) {
				timeline.appendChild( profile.buildPeriod( group.periods[i] ) );
			}

			// full-width line under each timeline
			line = profile.createSvgElement( 'line' );
			line.setAttribute( 'class', 'mw-debug-profile-underline' );
			line.setAttribute( 'x1', 0 );
			line.setAttribute( 'y1', 28 );
			line.setAttribute( 'x2', profile.width );
			line.setAttribute( 'y2', 28 );
			timeline.appendChild( line );

			return timeline;
		},

		/**
		 * @param Object period to transform into graphics
		 */
		buildPeriod: function ( period ) {
			var node,
				head = profile.xCoord( period.start ),
				tail = profile.xCoord( period.end ),
				g = profile.createSvgElement( 'g' );

			g.setAttribute( 'class', 'mw-debug-profile-period' );
			$( g ).data( 'period', period );

			if ( head + 16 > tail ) {
				node = profile.createSvgElement( 'rect' );
				node.setAttribute( 'x', head );
				node.setAttribute( 'y', 8 );
				node.setAttribute( 'width', 2 );
				node.setAttribute( 'height', 9 );
				g.appendChild( node );

				node = profile.createSvgElement( 'rect' );
				node.setAttribute( 'x', head );
				node.setAttribute( 'y', 8 );
				node.setAttribute( 'width', ( period.end - period.start ) * profile.ratio || 2 );
				node.setAttribute( 'height', 6 );
				g.appendChild( node );
			} else {
				node = profile.createSvgElement( 'polygon' );
				node.setAttribute( 'points', pointList( [
					[ head, 8 ],
					[ head, 19 ],
					[ head + 8, 8 ],
					[ head, 8]
				] ) );
				g.appendChild( node );

				node = profile.createSvgElement( 'polygon' );
				node.setAttribute( 'points', pointList( [
					[ tail, 8 ],
					[ tail, 19 ],
					[ tail - 8, 8 ],
					[ tail, 8 ]
				] ) );
				g.appendChild( node );

				node = profile.createSvgElement( 'line' );
				node.setAttribute( 'x1', head );
				node.setAttribute( 'y1', 9 );
				node.setAttribute( 'x2', tail );
				node.setAttribute( 'y2', 9 );
				g.appendChild( node );
			}

			return g;
		},

		/**
		 * @param Object
		 */
		buildFlyout: function ( period ) {
			var contained, sum, ms, mem, i,
				node = $( '<div>' );

			for ( i = 0; i < period.contained.length; i++ ) {
				contained = period.contained[i];
				sum = contained.end - contained.start;
				ms = '' + ( sum < 1 ? sum.toFixed( 2 ) : sum.toFixed( 0 ) ) + ' ms';
				mem = formatBytes( contained.memory );

				$( '<div>' ).text( contained.source.name )
					.append( $( '<span>' ).text( ' ~ ' + ms + ' / ' + mem ).addClass( 'mw-debug-profile-meta' ) )
					.appendTo( node );
			}

			return node;
		},

		/**
		 * Attach a hover flyout to all .mw-debug-profile-period groups.
		 */
		attachFlyout: function () {
			// for some reason addClass and removeClass from jQuery
			// arn't working on svg elements in chrome <= 33.0 (possibly more)
			var $container = $( profile.container ),
				addClass = function ( node, value ) {
					var current = node.getAttribute( 'class' ),
						list = current ? current.split( ' ' ) : false,
						idx = list ? list.indexOf( value ) : -1;

					if ( idx === -1 ) {
						node.setAttribute( 'class', current ? ( current + ' ' + value ) : value );
					}
				},
				removeClass = function ( node, value ) {
					var current = node.getAttribute( 'class' ),
						list = current ? current.split( ' ' ) : false,
						idx = list ? list.indexOf( value ) : -1;

					if ( idx !== -1 ) {
						list.splice( idx, 1 );
						node.setAttribute( 'class', list.join( ' ' ) );
					}
				},
				// hide all tipsy flyouts
				hide = function () {
					$container.find( '.mw-debug-profile-period.tipsy-visible' )
						.each( function () {
							removeClass( this, 'tipsy-visible' );
							$( this ).tipsy( 'hide' );
						} );
				};

			$container.find( '.mw-debug-profile-period' ).tipsy( {
				fade: true,
				gravity: function () {
					return $.fn.tipsy.autoNS.call( this )
						+ $.fn.tipsy.autoWE.call( this );
				},
				className: 'mw-debug-profile-tipsy',
				center: false,
				html: true,
				trigger: 'manual',
				title: function () {
					return profile.buildFlyout( $( this ).data( 'period' ) ).html();
				}
			} ).on( 'mouseenter', function () {
				hide();
				addClass( this, 'tipsy-visible' );
				$( this ).tipsy( 'show' );
			} );

			$container.on( 'mouseleave', function ( event ) {
				var $from = $( event.relatedTarget ),
					$to = $( event.target );
				// only close the tipsy if we are not
				if ( $from.closest( '.tipsy' ).length === 0 &&
					$to.closest( '.tipsy' ).length === 0 &&
					$to.get( 0 ).namespaceURI !== 'http://www.w4.org/2000/svg'
				) {
					hide();
				}
			} ).on( 'click', function () {
				// convenience method for closing
				hide();
			} );
		},

		/**
		 * @return number the x co-ordinate for the specified timestamp
		 */
		xCoord: function ( msTimestamp ) {
			return ( msTimestamp - profile.data.timespan.start ) * profile.ratio;
		}
	};

	function ProfileData( data, width, mergeThresholdPx, dropThresholdPx ) {
		// validate input data
		this.data = data.map( function ( event ) {
			event.periods = event.periods.filter( function ( period ) {
				return period.start && period.end
					&& period.start < period.end
					// period start must be a reasonable ms timestamp
					&& period.start > 1000000;
			} );
			return event;
		} ).filter( function ( event ) {
			return event.name && event.periods.length > 0;
		} );

		// start and end time of the data
		this.timespan = this.data.reduce( function ( result, event ) {
			return event.periods.reduce( periodMinMax, result );
		}, periodMinMax.initial() );

		// transform input data
		this.groups = this.collate( width, mergeThresholdPx, dropThresholdPx );

		return this;
	}

	/**
	 * There are too many unique events to display a line for each,
	 * so this does a basic grouping.
	 */
	ProfileData.groupOf = function ( label ) {
		var pos, prefix = 'Profile section ended by close(): ';
		if ( label.indexOf( prefix ) === 0 ) {
			label = label.substring( prefix.length );
		}

		pos = [ '::', ':', '-' ].reduce( function ( result, separator ) {
			var pos = label.indexOf( separator );
			if ( pos === -1 ) {
				return result;
			} else if ( result === -1 ) {
				return pos;
			} else {
				return Math.min( result, pos );
			}
		}, -1 );

		if ( pos === -1 ) {
			return label;
		} else {
			return label.substring( 0, pos );
		}
	};

	/**
	 * @return Array list of objects with `name` and `events` keys
	 */
	ProfileData.groupEvents = function ( events ) {
		var group, i,
			groups = {};

		// Group events together
		for ( i = events.length - 1; i >= 0; i-- ) {
			group = ProfileData.groupOf( events[i].name );
			if ( groups[group] ) {
				groups[group].push( events[i] );
			} else {
				groups[group] = [events[i]];
			}
		}

		// Return an array of groups
		return Object.keys( groups ).map( function ( group ) {
			return {
				name: group,
				events: groups[group]
			};
		} );
	};

	ProfileData.periodSorter = function ( a, b ) {
		if ( a.start === b.start ) {
			return a.end - b.end;
		}
		return a.start - b.start;
	};

	ProfileData.genMergePeriodReducer = function ( mergeThresholdMs ) {
		return function ( result, period ) {
			if ( result.length === 0 ) {
				// period is first result
				return [{
					start: period.start,
					end: period.end,
					contained: [period]
				}];
			}
			var last = result[result.length - 1];
			if ( period.end < last.end ) {
				// end is contained within previous
				result[result.length - 1].contained.push( period );
			} else if ( period.start - mergeThresholdMs < last.end ) {
				// neighbors within merging distance
				result[result.length - 1].end = period.end;
				result[result.length - 1].contained.push( period );
			} else {
				// period is next result
				result.push({
					start: period.start,
					end: period.end,
					contained: [period]
				});
			}
			return result;
		};
	};

	/**
	 * Collect all periods from the grouped events and apply merge and
	 * drop transformations
	 */
	ProfileData.extractPeriods = function ( events, mergeThresholdMs, dropThresholdMs ) {
		// collect the periods from all events
		return events.reduce( function ( result, event ) {
				if ( !event.periods.length ) {
					return result;
				}
				result.push.apply( result, event.periods.map( function ( period ) {
					// maintain link from period to event
					period.source = event;
					return period;
				} ) );
				return result;
			}, [] )
			// sort combined periods
			.sort( ProfileData.periodSorter )
			// Apply merge threshold. Original periods
			// are maintained in the `contained` property
			.reduce( ProfileData.genMergePeriodReducer( mergeThresholdMs ), [] )
			// Apply drop threshold
			.filter( function ( period ) {
				return period.end - period.start > dropThresholdMs;
			} );
	};

	/**
	 * runs a callback on all periods in the group.  Only valid after
	 * groups.periods[0..n].contained are populated. This runs against
	 * un-transformed data and is better suited to summing or other
	 * stat collection
	 */
	ProfileData.reducePeriods = function ( group, callback, result ) {
		return group.periods.reduce( function ( result, period ) {
			return period.contained.reduce( callback, result );
		}, result );
	};

	/**
	 * Transforms this.data grouping by labels, merging neighboring
	 * events in the groups, and drops events and groups below the
	 * display threshold. Groups are returned sorted by starting time.
	 */
	ProfileData.prototype.collate = function ( width, mergeThresholdPx, dropThresholdPx ) {
		// ms to pixel ratio
		var ratio = ( this.timespan.end - this.timespan.start ) / width,
			// transform thresholds to ms
			mergeThresholdMs = mergeThresholdPx * ratio,
			dropThresholdMs = dropThresholdPx * ratio;

		return ProfileData.groupEvents( this.data )
			// generate data about the grouped events
			.map( function ( group ) {
				// Cleaned periods from all events
				group.periods = ProfileData.extractPeriods( group.events, mergeThresholdMs, dropThresholdMs );
				// min and max timestamp per group
				group.timespan = ProfileData.reducePeriods( group, periodMinMax, periodMinMax.initial() );
				// ms from first call to end of last call
				group.timespan.length = group.timespan.end - group.timespan.start;
				// collect the un-transformed periods
				group.timespan.sum = ProfileData.reducePeriods( group, function ( result, period ) {
						result.push( period );
						return result;
					}, [] )
					// sort by start time
					.sort( ProfileData.periodSorter )
					// merge overlapping
					.reduce( ProfileData.genMergePeriodReducer( 0 ), [] )
					// sum
					.reduce( function ( result, period ) {
						return result + period.end - period.start;
					}, 0 );

				return group;
			}, this )
			// remove groups that have had all their periods filtered
			.filter( function ( group ) {
				return group.periods.length > 0;
			} )
			// sort events by first start
			.sort( function ( a, b ) {
				return ProfileData.periodSorter( a.timespan, b.timespan );
			} );
	};

	// reducer to find edges of period array
	function periodMinMax( result, period ) {
		if ( period.start < result.start ) {
			result.start = period.start;
		}
		if ( period.end > result.end ) {
			result.end = period.end;
		}
		return result;
	}

	periodMinMax.initial = function () {
		return { start: Number.POSITIVE_INFINITY, end: Number.NEGATIVE_INFINITY };
	};

	function formatBytes( bytes ) {
		var i, sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if ( bytes === 0 ) {
			return '0 Bytes';
		}
		i = parseInt( Math.floor( Math.log( bytes ) / Math.log( 1024 ) ), 10 );
		return Math.round( bytes / Math.pow( 1024, i ), 2 ) + ' ' + sizes[i];
	}

	// turns a 2d array into a point list for svg
	// polygon points attribute
	// ex: [[1,2],[3,4],[4,2]] = '1,2 3,4 4,2'
	function pointList( pairs ) {
		return pairs.map( function ( pair ) {
			return pair.join( ',' );
		} ).join( ' ' );
	}
}( mediaWiki, jQuery ) );
