/**
 * JavaScript for the debug toolbar profiler, enabled through $wgDebugToolbar
 * and StartProfiler.php.
 *
 * @author Erik Bernhardson
 * @since 1.23
 */

( function ( mw, $ ) {
	'use strict';

	var profile,
		hovzer = $.getFootHovzer();

	profile = mw.Debug.Profile = {
		/**
		 * Object containing data for the debug toolbar
		 *
		 * @var {Object}
		 */
		data: {},

		/**
		 * Canvas timeline is drawn to
		 *
		 * @var {Element}
		 */
		canvas: null,

		/**
		 * Initializes the debugging pane.
		 * Shouldn't be called before the document is ready
		 * (since it binds to elements on the page).
		 *
		 * @param {Object} data, defaults to 'debugInfo' from mw.config
		 */
		init: function ( data, width, mergeThreshold, dropThreshold ) {

			this.canvas = document.createElement( 'canvas' );
			this.data = data || mw.config.get( 'debugInfo' ).profile;
			this.buildCanvas( width || $(window).width() - 100, mergeThreshold || 1, dropThreshold || 1 );
		},

		collate: function( width, mergeThreshold, dropThreshold ) {
			var label, pos, i, sum, minmax, ratio,
				groups = {},
				prefix = 'Profile section ended by close(): ';


			// group together by partial label and period
			for ( i in this.data ) {
				if ( 0 === i.indexOf( '-' ) || i == "" ) {
					continue;
				}
				if ( 0 === i.indexOf( prefix ) ) {
					i = i.substring( prefix.length );
				}
				pos = [ '::', ':', '-' ].reduce( function( result, separator ) {
					var pos = i.indexOf( separator );
					if ( pos === -1 ) {
						return result;
					} else if ( result === -1 ) {
						return pos;
					} else {
						return Math.min( pos, result );
					}
				}, -1 );
				if ( pos === -1 ) {
					label = i;
				} else {
					label = i.substring( 0, pos );
				}
				groups[label] = groups[label] || [];
				groups[label].push.apply( 
					groups[label], 
					// filter any bad data
					this.data[i].periods.filter( function( period ) {
						return period.start && period.end && period.end > period.start;
					} )
				);
			}

			// find the min and max timestamp
			minmax = Object.keys( groups ).reduce( function ( result, label ) {
				return groups[label].reduce( periodMinMax, result );
			}, { start: Number.POSITIVE_INFINITY, end: Number.NEGATIVE_INFINITY} );

			// change mergeThreshold from pixels to ms
			ratio = ( minmax.end - minmax.start ) / width;
			mergeThreshold *= ratio;

			// merge periods closer than a step size 
			return  Object.keys( groups ).reduce( function( result,  label ) {
				var periods = groups[label].sort( function ( a, b ) {
					if ( a.start == b.start ) {
						return a.end - b.end;
					}
					return a.start - b.start;
				} ).reduce( function ( result, period ){
					// shift all periods left by minmax.start
					// only when period.start > 1000000, because sometimes mw profiler 
					// outputs relative time(bug?)
					if ( period.start > 1000000 ) {
						period.start -= minmax.start;
						period.end -= minmax.start;
					}

					if ( result.length == 0 ) {
						return [period];
					}
					var last = result[result.length - 1];
					if ( period.end < last.end ) {
						// skip, end is contained within previous
					} else if ( period.start - mergeThreshold < last.end ) {
						// neighbors within merging distance
						result[result.length - 1].end = period.end;
					} else {
						// period is next result
						result.push( period );
					}
					return result;
				}, [] ).filter( function( period ) {
					return period.end - period.start > dropThreshold;
				} );
				if ( periods.length ) {
					result[label] = periods;
				}
				return result;
			}, {} );
		},

		/**
		 * Constructs the HTML for the profile toolbar
		 */
		buildCanvas: function ( width, mergeThreshold, dropThreshold ) {
			var $container, $bits, i, pos, groups, label;

			$container = $( '<div id="mw-profile-toolbar" class="mw-profile" lang="en" dir="ltr"></div>' );

			$bits = $( '<div class="mw-debug-bits"></div>' );

			groups = this.collate( width, mergeThreshold, dropThreshold );

			// Calculate extra timespan based data
			var timespans = {}, first, last;
			for ( label in groups ) {
				timespans[label] = groups[label].reduce( 
					periodMinMax,
					{ start: Number.POSITIVE_INFINITY, end: Number.NEGATIVE_INFINITY} 
				);
				timespans[label].length = timespans[label].end - timespans[label].start;
				timespans[label].sum = groups[label].reduce( function( result, period ) {
					return result + period.end - period.start;
				}, 0 );
			}
			var total = Object.keys( timespans ).reduce(
				function( result, label ) { return periodMinMax( result, timespans[label] ); },
				{ start: Number.POSITIVE_INFINITY, end: Number.NEGATIVE_INFINITY} 
			);
			var max = total.end - total.start;

			// Sort by contained time
			this.sortedLabels = Object.keys( groups ).sort( function( a, b ) {
				return timespans[a].start - timespans[b].start;	
			} );
			// for debugging
			this.other = groups;

			// start drawing
			var i, j,
				text,
				canvasHeight = 0,
				gapPerEvent = 38,
				colors = { 
					default: 'red',
				},
				space = 10.5,
				ratio = ( width - space * 2 ) / max,
				h = space,
				x = 0,
				canvas = this.canvas,
				ctx = canvas.getContext( "2d" ),
				backingStoreRatio,
				scaleRatio,
				devicePixelRatio;


			// reset the canvas
			ctx.save();
			ctx.setTransform( 1, 0, 0, 1, 0, 0 );
			ctx.clearRect( 0, 0, canvas.width, canvas.height );
			ctx.restore();

			// Figure out our size
			canvasHeight += gapPerEvent * Object.keys( groups ).length;
			devicePixelRatio = window.devicePixelRatio == "undefined" ? 1 : window.devicePixelRatio;
			backingStoreRatio = ctx.webkitBackingStorePixelRatio == "undefined" ? 1 : ctx.webkitBackingStorePixelRatio;
			scaleRatio = devicePixelRatio / 1;

			canvas.width = width * scaleRatio;
			canvas.height = canvasHeight * scaleRatio;

			canvas.style.width = width + 'px';
			canvas.style.height = canvasHeight + 'px';

			ctx.scale( scaleRatio, scaleRatio );

			ctx.textBaseline = "middle";
			ctx.lineWidth = 0;

			this.sortedLabels.forEach( function( label ) {
				h += 8;

				if ( colors[label] ) {
					ctx.fillStyle = colors[label];
					ctx.strokeStyle = colors[label];
				} else {
					ctx.fillStyle = colors['default'];
					ctx.strokeStyle = colors['default'];
				}

				groups[label].forEach( function( period ) {
					
					var timelineHeadPosition = x + period.start * ratio,
						timelineTailPosition = x + period.end * ratio;

					// if less than width draw a plain box
					if ( timelineHeadPosition + 16 > timelineTailPosition ) {
						ctx.fillRect( timelineHeadPosition, h, 2, 9 );
						ctx.fillRect( timelineHeadPosition, h, ( period.end - period.start ) * ratio || 2, 6 );
					} else {
						ctx.beginPath();
						ctx.moveTo( timelineHeadPosition, h );
						ctx.lineTo( timelineHeadPosition, h + 11 );
						ctx.lineTo( timelineHeadPosition + 8, h );
						ctx.lineTo( timelineHeadPosition, h );
						ctx.fill();
						ctx.closePath();
						ctx.stroke();

						ctx.beginPath();
						ctx.moveTo( timelineTailPosition, h );
						ctx.lineTo( timelineTailPosition, h + 11 );
						ctx.lineTo( timelineTailPosition - 8, h );
						ctx.lineTo( timelineTailPosition, h );
						ctx.fill();
						ctx.closePath();
						ctx.stroke();

						ctx.beginPath();
						ctx.moveTo( timelineHeadPosition, h );
						ctx.lineTo( timelineTailPosition, h );
						ctx.lineTo( timelineTailPosition, h + 2 );
						ctx.lineTo( timelineHeadPosition, h + 2 );
						ctx.lineTo( timelineHeadPosition, h );
						ctx.fill();
						ctx.closePath();
						ctx.stroke();
					}
				} );

				h += 30;

				ctx.beginPath();
				ctx.strokeStyle = "#dfdfdf";
				ctx.moveTo( 0, h - 10 );
				ctx.lineTo( width, h - 10 );
				ctx.closePath();
				ctx.stroke();
			} );

			h = space;

			this.sortedLabels.forEach( function( label ) {
				var ms = " ~ " + ( timespans[label].sum < 1 ? timespans[label].sum : parseInt( timespans[label].sum, 10 ) ) + " ms",
					xc;

				ctx.fillStyle = "#444";
				ctx.font = "12px sans-serif";
				ctx.textAlign = "start";
				xc = x + timespans[label].start * ratio + 1;
				ctx.fillText( label, xc, h );
				
				xc += ctx.measureText( label ).width;
				ctx.font = "10px sans-serif";
				ctx.fillText( ms, xc, h );

				h += gapPerEvent;
			} );
		}
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
	};

}( mediaWiki, jQuery ) );
