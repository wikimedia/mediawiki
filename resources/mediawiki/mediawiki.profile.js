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
		 * @var {Object}
		 */
		data: {},

		e: null,

		flyout: null,

		timespan: null,

		groups: null,

		/**
		 * Initializes the debugging pane.
		 * Shouldn't be called before the document is ready
		 * (since it binds to elements on the page).
		 *
		 */
		init: function ( data, width, mergeThresholdPx, dropThresholdPx ) {
			data = data || mw.config.get( 'debugInfo' ).profile;
			width = width || $(window).width();
			// merge events from same pixel(some events are very granular)
			mergeThresholdPx = mergeThresholdPx || 2;
			// only drop events if requested
			dropThresholdPx = dropThresholdPx || 0;

			// generate a flyout
			this.flyout = new Flyout( $( 'body' ) );

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
			// draw it
			this.buildSvg( width );

			return this.e;
		},

		/**
		 * There are too many unique events to display a line for each,
		 * so this does a basic grouping.
		 */
		groupOf: function( label ) {
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
		},

		/**
		 * @return list of objects with `name` and `events` keys
		 */
		groupEvents: function( events ) {
			var groups = {}, i, group;

			// Group events together
			for ( i = events.length - 1; i >= 0; i-- ) {
				if ( !events[i].periods.length ) {
					continue;
				}
				group = this.groupOf( events[i].name );
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
		},

		/**
		 * Transforms this.data grouping by labels, merging neighboring
		 * events in the groups, and drops events and groups below the
		 * display threshold. Groups are returned sorted by starting time.
		 */
		collate: function( width, mergeThresholdPx, dropThresholdPx ) {
			function reducePeriods( group, callback, result ) {
				return group.periods.reduce( function( result, period ) {
					return period.contained.reduce( callback, result );
				}, result );
			}
			// ms to pixel ratio
			var ratio = ( this.timespan.end - this.timespan.start ) / width,
				// transform thresholds to ms
				mergeThresholdMs = mergeThresholdPx * ratio,
				dropThresholdMs = dropThresholdPx * ratio;

			return  this.groupEvents( this.data )
				// generate data about the grouped events
				.map( function( group ) {
					group.periods = group.events
						// collect the periods from all events
						.reduce( function( result, event ) {
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


					// min and max timestamp per group
					group.timespan = reducePeriods( group, periodMinMax, periodMinMax.initial() );
					// ms from first call to end of last call
					group.timespan.length = group.timespan.end - group.timespan.start;
					// ms contained by all periods in group
					group.timespan.sum = reducePeriods( group, function( result, period ) {
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
		},

		buildSvg: function( width ) {
			function create( tag ) {
				return document.createElementNS( 'http://www.w3.org/2000/svg', tag );
			}

			function pointList( pairs ) {
				return pairs.map( function( pair ) {
					return pair.join( ',' );
				} ).join( ' ' );
			}

			var svg = this.e = this.e || create( 'svg' ),
				height = 0,
				gapPerEvent = 38,
				space = 10.5,
				timespan = this.timespan,
				ratio = ( width - space * 2 ) / ( timespan.end - timespan.start ),
				h = space,
				x = 0,
				self = this;

			$( svg ).children().remove();

			height += gapPerEvent * this.groups.length;

			svg.setAttribute( 'version', '1.2' );
			svg.setAttribute( 'baseProfile', 'tiny' );
			svg.setAttribute( 'style', 'height: ' + height  + 'px; width: ' + width + 'px;');

			this.groups.forEach( function( group ) {
				var rect, xc, text, tspan,
					ms = ' ~ ' + ( group.timespan.sum < 1 ? group.timespan.sum : parseInt( group.timespan.sum, 10 ) ) + ' ms',
					timeline = create( 'g' );

				svg.appendChild( timeline );
				timeline.setAttribute( 'class', 'timeline' );

				// Draw label
				text = create( 'text' );
				text.setAttribute( 'fill', '#444' );
				text.setAttribute( 'font-size', '12px' );
				text.setAttribute( 'font-family', 'sans-serif' );
				xc = x + ( group.timespan.start - timespan.start ) * ratio + 1;
				text.setAttribute( 'x', xc );
				text.setAttribute( 'y', h );
				text.textContent = group.name;
				timeline.appendChild( text );

				tspan = create( 'tspan' );
				tspan.setAttribute( 'font-size', '10px' );
				tspan.textContent = ms;
				text.appendChild( tspan );

				h += 8;

				group.periods.forEach( function( period ) {
					var start = period.start - timespan.start,
						end = period.end - timespan.start,
						timelineHeadPosition = x + start * ratio,
						timelineTailPosition = x + end * ratio,
						e, g = create( 'g' );

					timeline.appendChild( g );
					g.setAttribute( 'class', 'period' );
					$( g ).data( 'period', period );

					if ( timelineHeadPosition + 16 > timelineTailPosition ) {
						e = create( 'rect' );
						e.setAttribute( 'x', timelineHeadPosition );
						e.setAttribute( 'y', h );
						e.setAttribute( 'width', 2 );
						e.setAttribute( 'height', 9 );
						e.setAttribute( 'fill', 'red' );
						g.appendChild( e );

						e = create( 'rect' );
						e.setAttribute( 'x', timelineHeadPosition );
						e.setAttribute( 'y', h );
						e.setAttribute( 'width', ( end - start ) * ratio || 2 );
						e.setAttribute( 'height', 6 );
						e.setAttribute( 'fill', 'red' );
						g.appendChild( e );
					} else {
						e = create( 'polygon' );
						e.setAttribute( 'fill', 'red' );
						e.setAttribute( 'points', pointList( [
							[ timelineHeadPosition, h ],
							[ timelineHeadPosition, h + 11 ],
							[ timelineHeadPosition + 8, h ],
							[ timelineHeadPosition, h]
						] ) );
						g.appendChild( e );

						e = create( 'polygon' );
						e.setAttribute( 'fill', 'red' );
						e.setAttribute( 'points', pointList( [
							[ timelineTailPosition, h ],
							[ timelineTailPosition, h + 11 ],
							[ timelineTailPosition - 8, h ],
							[ timelineTailPosition, h ],
						] ) );
						g.appendChild( e );

						e = create( 'line' );
						e.setAttribute( 'stroke', 'red' );
						e.setAttribute( 'stroke-width', 2 );
						e.setAttribute( 'x1', timelineHeadPosition );
						e.setAttribute( 'y1', h + 1 );
						e.setAttribute( 'x2', timelineTailPosition );
						e.setAttribute( 'y2', h + 1 );
						g.appendChild( e );
					}
				} );

				// step to next line
				h += 30;

				// Gray full-width line under each timeline

				rect = create( 'line' );
				rect.setAttribute( 'x1', 0 );
				rect.setAttribute( 'y1', h - 10 );
				rect.setAttribute( 'x2', width );
				rect.setAttribute( 'y2', h - 10 );
				rect.setAttribute( 'stroke-width', 1 );
				rect.setAttribute( 'stroke', '#dfdfdf' );
				timeline.appendChild( rect );
			} );

			// delegated events dont work for svg
			$( '.period', svg ) .on( 'mouseenter', function( e ) {
				self.flyout.show( $( this ).data( 'period' ), e.pageX, e.pageY );
			} );
			$( svg ).on( 'mouseleave', function( e ) {
				// Only hide the flyout when we actually leave the svg
				if ( e.originalEvent.toElement !== self.flyout.e[0]
					&& e.originalEvent.fromElement !== self.flyout.e[0]
					&& ( !e.originalEvent.toElement || e.originalEvent.toElement.namespaceURI !== 'http://www.w3.org/2000/svg' )
				) {
					self.flyout.hide();
				}
			} ).on( 'click', function() {
				self.flyout.hide();
			} );

			return svg;
		},
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

	function Flyout( container ) {
		this.e = $( '<div/>' ).addClass( 'mw-debug-profile-flyout' );
		this.e.appendTo( container );

		return this;
	}

	Flyout.prototype.show = function( period, x, y ) {
		this.e.children().remove();
		this.e.css( {
			display: 'block',
			opacity: 1,
			top: y,
			left: x,
		} );

		period.contained.forEach( function( period ) {
			var sum = period.end - period.start,
				ms = '' + ( sum < 1 ? sum : parseInt( sum, 10 ) ) + ' ms',
				mem = formatBytes( period.memory );

			$( '<div/>' ).text( period.source.name )
				.append( $( '<div/>' ).text( ' ~ ' + ms + ' / ' + mem ).addClass( 'mw-debug-profile-meta' ) )
				.appendTo( this.e );
		}, this );
	};

	Flyout.prototype.hide = function() {
		this.e.css( 'opacity', 0 );
		setTimeout( function() {
			this.e.css( 'display', 'none' );
		}.bind( this ), 100 );
	};

	function formatBytes( bytes ) {
		var i, sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		if ( bytes === 0 ) {
			return '0 Bytes';
		}
		i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}

}( mediaWiki, jQuery ) );
