/**
 * JavaScript for the debug toolbar profiler, enabled through $wgDebugToolbar
 * and StartProfiler.php.
 *
 * @author Erik Bernhardson
 * @since 1.23
 */

( function ( mw, $ ) {
	'use strict';

	mw.Debug.Profile = {
		/**
		 * Object containing data for the debug toolbar
		 *
		 * @var ProfileData
		 */
		data: null,

		/**
		 * @var DOMElement
		 */
		e: null,

		/**
		 * Initializes the debugging pane.
		 * Shouldn't be called before the document is ready
		 * (since it binds to elements on the page).
		 *
		 */
		init: function ( data, width, mergeThresholdPx, dropThresholdPx ) {
			data = data || mw.config.get( 'debugInfo' ).profile;
			this.width = width || $(window).width() - 20;
			// merge events from same pixel(some events are very granular)
			mergeThresholdPx = mergeThresholdPx || 2;
			// only drop events if requested
			dropThresholdPx = dropThresholdPx || 0;

			if ( data.length === 0 ) {
				this.buildNoData();
				return;
			}

			// generate a flyout
			this.data = new ProfileData( data, this.width, mergeThresholdPx, dropThresholdPx );
			// draw it
			this.buildSvg();
			this.attachFlyout();

			return this.e;
		},

		buildNoData: function() {
			this.e = document.createElement( 'div' );
			$( this.e ).addClass( 'mw-debug-profile-no-data' )
				.text( 'No events recorded, ensure profiling is enabled in StartProfiler.php.' );
		},

		// svg elements need to be namespaced
		createElement: document.createElementNS.bind( document, 'http://www.w3.org/2000/svg' ),

		buildSvg: function() {
			// svg elements need to be namespaced
			var container,
				timespan = this.data.timespan,
				height = 0,
				gapPerEvent = 38,
				space = 10.5,
				h = space;


			this.ratio = ( this.width - space * 2 ) / ( timespan.end - timespan.start );
			height += gapPerEvent * this.data.groups.length;

			if ( this.e ) {
				$( this.e ).children().remove();
			} else {
				this.e = this.createElement( 'svg' );
				this.e.setAttribute( 'version', '1.2' );
				this.e.setAttribute( 'baseProfile', 'tiny' );
				this.e.setAttribute( 'style', 'height: ' + height  + 'px; width: ' + this.width + 'px;');
			}

			// use a container that can be transformed
			container = this.createElement( 'g' );
			this.e.appendChild( container );

			this.data.groups.forEach( function( group ) {
				var g = this.buildTimeline( group );

				g.setAttribute( 'transform', 'translate( 0 ' + h + ' )' );
				container.appendChild( g );

				h += gapPerEvent;
			}, this );
		},

		/**
		 * @var Object group of periods to transform into graphics
		 */
		buildTimeline: function( group ) {
			var line, text, tspan,
				start = this.data.timespan.start,
				sum = group.timespan.sum,
				ms = ' ~ ' + ( sum < 1 ? sum.toFixed( 2 ) : sum.toFixed( 0 ) ) + ' ms',
				timeline = this.createElement( 'g' );

			timeline.setAttribute( 'class', 'mw-debug-profile-timeline' );

			// draw label
			text = this.createElement( 'text' );
			text.setAttribute( 'x', ( group.timespan.start - start ) * this.ratio + 1 );
			text.setAttribute( 'y', 0 );
			text.textContent = group.name;
			timeline.appendChild( text );

			// draw metadata
			tspan = this.createElement( 'tspan' );
			tspan.textContent = ms;
			text.appendChild( tspan );

			// draw timeline periods
			group.periods.forEach( function( period ) {
				timeline.appendChild( this.buildPeriod( period ) );
			}, this );

			// full-width line under each timeline
			line = this.createElement( 'line' );
			line.setAttribute( 'class', 'mw-debug-profile-underline' );
			line.setAttribute( 'x1', 0 );
			line.setAttribute( 'y1', 28 );
			line.setAttribute( 'x2', this.width );
			line.setAttribute( 'y2', 28 );
			timeline.appendChild( line );

			return timeline;
		},

		/**
		 * @var Object period to transform into graphics
		 */
		buildPeriod: function( period ) {
			var start = this.data.timespan.start,
				head = ( period.start - start ) * this.ratio,
				tail = ( period.end - start ) * this.ratio,
				e, g = this.createElement( 'g' );

			g.setAttribute( 'class', 'mw-debug-profile-period' );
			$( g ).data( 'period', period );

			if ( head + 16 > tail ) {
				e = this.createElement( 'rect' );
				e.setAttribute( 'x', head );
				e.setAttribute( 'y', 8 );
				e.setAttribute( 'width', 2 );
				e.setAttribute( 'height', 9 );
				g.appendChild( e );

				e = this.createElement( 'rect' );
				e.setAttribute( 'x', head );
				e.setAttribute( 'y', 8 );
				e.setAttribute( 'width', ( period.end - period.start ) * this.ratio || 2 );
				e.setAttribute( 'height', 6 );
				g.appendChild( e );
			} else {
				e = this.createElement( 'polygon' );
				e.setAttribute( 'points', pointList( [
					[ head, 8 ],
					[ head, 19 ],
					[ head + 8, 8 ],
					[ head, 8]
				] ) );
				g.appendChild( e );

				e = this.createElement( 'polygon' );
				e.setAttribute( 'points', pointList( [
					[ tail, 8 ],
					[ tail, 19 ],
					[ tail - 8, 8 ],
					[ tail, 8 ],
				] ) );
				g.appendChild( e );

				e = this.createElement( 'line' );
				e.setAttribute( 'x1', head );
				e.setAttribute( 'y1', 9 );
				e.setAttribute( 'x2', tail );
				e.setAttribute( 'y2', 9 );
				g.appendChild( e );
			}

			return g;
		},

		buildFlyout: function( period ) {
			var e = $( '<div/>' );
			period.contained.forEach( function( period ) {
				var sum = period.end - period.start,
					ms = '' + ( sum < 1 ? sum.toFixed( 2 ) : sum.toFixed( 0 ) ) + ' ms',
					mem = formatBytes( period.memory );

				$( '<div/>' ).text( period.source.name )
					.append( $( '<span/>' ).text( ' ~ ' + ms + ' / ' + mem ).addClass( 'mw-debug-profile-meta' ) )
					.appendTo( e );
			}, this );

			return e;
		},

		attachFlyout: function() {
			var $periods = $( '.mw-debug-profile-period', this.e ),
				// for some reason addClass and removeClass from jQuery
				// arn't working on svg elements in chrome 29.0
				addClass = function( e, value ) {
					var current = e.getAttribute( 'class' ),
						list = current === null ? null : current.split( ' ' ),
						idx = current === null ? -1 : list.indexOf( value );

					if ( idx === -1 ) {
						e.setAttribute( 'class', current.length === 0 ? value : current + ' ' + value );
					}
				},
				removeClass = function( e, value ) {
					var current = e.getAttribute( 'class' ),
						list = current === null ? null : current.split( ' ' ),
						idx = current === null ? -1 : list.indexOf( value );

					if ( idx !== -1 ) {
						delete list[idx];
						e.setAttribute( 'class', list.join( ' ' ) );
					}
				},
				self = this,
				// hide all tipsy flyouts
				hide = function() {
					$( '.mw-debug-profile-period.tipsy-visible', self.e )
						.each( function() {
							removeClass( this, 'tipsy-visible' );
							$( this ).tipsy( 'hide' );
						} );
				};

			$periods.tipsy( {
				fade: true,
				gravity: function() {
					return $.fn.tipsy.autoNS.call( this )
						+ $.fn.tipsy.autoWE.call( this );
				},
				className: 'mw-debug-profile-tipsy',
				center: false,
				html: true,
				trigger: 'manual',
				title: function() {
					return self.buildFlyout( $( this ).data( 'period' ) ).html();
				},
			} ).on( 'mouseenter', function() {
				hide();
				addClass( this, 'tipsy-visible' );
				$( this ).tipsy( 'show' );
			} );

			$( this.e ).on( 'mouseleave', function( e ) {
				var from = $( e.originalEvent.fromElement ),
					to = $( e.originalEvent.toElement );

				// hide if neither source or dest is the tipsy
				// and dest is not an svg element
				if ( from.closest( '.tipsy').length === 0
					&& to.closest( '.tipsy').length === 0
					&& ( to.length === 0 || to[0].namespaceURI !== 'http://www.w4.org/2000/svg' )
				) {
					hide();
				}
			} ).on( 'click', function() {
				hide();
			} );
		},
	};

	function ProfileData( data, width, mergeThresholdPx, dropThresholdPx ) {
		// validate input data
		this.data = data.map( function( event ) {
			event.periods = event.periods.filter( function( period ) {
				return period.start && period.end
					&& period.start < period.end
					// period start must be a reasonable ms timestamp
					&& period.start > 1000000;
			} );
			return event;
		} ).filter( function( event ) {
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
	ProfileData.groupOf = function( label ) {
		var pos, prefix = 'Profile section ended by close(): ';
		if ( 0 === label.indexOf( prefix ) ) {
			label = label.substring( prefix.length );
		}

		pos = [ '::', ':', '-' ].reduce( function( result, separator ) {
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
	 * @return list of objects with `name` and `events` keys
	 */
	ProfileData.groupEvents = function( events ) {
		var groups = {}, i, group;

		// Group events together
		for ( i = events.length - 1; i >= 0; i-- ) {
			group = ProfileData.groupOf( events[i].name );
			if ( groups[group] ) {
				groups[group].events.push( events[i] );
			} else {
				groups[group] = {
					name: group,
					events: [events[i]],
				};
			}
		}

		// Return an array of groups
		return Object.keys( groups ).map( function( group ) {
			return groups[group];
		} );
	};

	/**
	 * Collect all periods from the grouped events and apply merge and
	 * drop transformations
	 */
	ProfileData.extractPeriods = function( events, mergeThresholdMs, dropThresholdMs ) {
		// collect the periods from all events
		return events.reduce( function( result, event ) {
				if ( !event.periods.length ) {
					return result;
				}
				var i;
				// maintain link from period to event
				for( i = event.periods.length - 1; i >= 0; i-- ) {
					event.periods[i].source = event;
				}
				result.push.apply( result, event.periods );
				return result;
			}, [] )
			// sort combined periods
			.sort( function ( a, b ) {
				if ( a.start === b.start ) {
					return a.end - b.end;
				}
				return a.start - b.start;
			} )
			// Apply merge threshold. Original periods
			// are maintained in the `contained` key
			.reduce( function ( result, period ){
				if ( result.length === 0 ) {
					// period is first result
					return [{
						start: period.start,
						end: period.end,
						contained: [period],
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
						contained: [period],
					});
				}
				return result;
			}, [] )
			// Apply drop threshold
			.filter( function( period ) {
				return period.end - period.start > dropThresholdMs;
			} );
	};

	/**
	 * runs a callback on all periods in the group.  Only valid after
	 * groups.periods[0..n].contained are populated. This runs against
	 * un-transformed data and is better suited to summing or other
	 * stat collection
	 */
	ProfileData.reducePeriods = function( group, callback, result ) {
		return group.periods.reduce( function( result, period ) {
			return period.contained.reduce( callback, result );
		}, result );
	};

	/**
	 * Transforms this.data grouping by labels, merging neighboring
	 * events in the groups, and drops events and groups below the
	 * display threshold. Groups are returned sorted by starting time.
	 */
	ProfileData.prototype.collate = function( width, mergeThresholdPx, dropThresholdPx ) {
		// ms to pixel ratio
		var ratio = ( this.timespan.end - this.timespan.start ) / width,
			// transform thresholds to ms
			mergeThresholdMs = mergeThresholdPx * ratio,
			dropThresholdMs = dropThresholdPx * ratio;

		return ProfileData.groupEvents( this.data )
			// generate data about the grouped events
			.map( function( group ) {
				// Cleaned periods from all events
				group.periods = ProfileData.extractPeriods( group.events, mergeThresholdMs, dropThresholdMs );
				// min and max timestamp per group
				group.timespan = ProfileData.reducePeriods( group, periodMinMax, periodMinMax.initial() );
				// ms from first call to end of last call
				group.timespan.length = group.timespan.end - group.timespan.start;
				// ms contained by all periods in group
				group.timespan.sum = ProfileData.reducePeriods( group, function( result, period ) {
					return result + period.end - period.start;
				}, 0 );

				return group;
			}, this )
			// remove groups that have had all their periods filtered
			.filter( function ( group ) {
				return group.periods.length > 0;
			} )
			// sort events by first start
			.sort( function( a, b ) {
				if ( a.timespan.start === b.timespan.start ) {
					return a.timespan.end - b.timespan.end;
				}
				return a.timespan.start - b.timespan.start;
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

	periodMinMax.initial = function() {
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
	// polygon points attr
	// ex: [[1,2],[3,4],[4,2]] = '1,2 3,4 4,2'
	function pointList( pairs ) {
		return pairs.map( function( pair ) {
			return pair.join( ',' );
		} ).join( ' ' );
	}
}( mediaWiki, jQuery ) );
