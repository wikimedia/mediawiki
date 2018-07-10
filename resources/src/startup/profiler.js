/*!
 * Augment mw.loader to facilitate module-level profiling.
 *
 * @author Timo Tijhof
 * @since 1.32
 */
/* global mw */
( function () {
	'use strict';

	var moduleTimes = Object.create( null );

	/**
	 * Private hooks inserted into mw.loader code if MediaWiki configuration
	 * `$wgResourceLoaderEnableJSProfiler` is `true`.
	 *
	 * To use this data, run `mw.inspect( 'time' )` from the browser console.
	 * See mw#inspect().
	 *
	 * @private
	 * @class
	 * @singleton
	 */
	mw.loader.profiler = {
		onExecuteStart: function ( moduleName ) {
			var time = performance.now();
			if ( moduleTimes[ moduleName ] ) {
				throw new Error( 'Unexpected perf record for "' + moduleName + '".' );
			}
			moduleTimes[ moduleName ] = {
				executeStart: time,
				executeEnd: null,
				scriptStart: null,
				scriptEnd: null
			};
		},
		onExecuteEnd: function ( moduleName ) {
			var time = performance.now();
			moduleTimes[ moduleName ].executeEnd = time;
		},
		onScriptStart: function ( moduleName ) {
			var time = performance.now();
			moduleTimes[ moduleName ].scriptStart = time;
		},
		onScriptEnd: function ( moduleName ) {
			var time = performance.now();
			moduleTimes[ moduleName ].scriptEnd = time;
		},

		/**
		 * For internal use by inspect.reports#time.
		 *
		 * @private
		 * @param {string} moduleName
		 * @return {Object|null}
		 * @throws {Error} If the perf record is incomplete.
		 */
		getProfile: function ( moduleName ) {
			var times, key, execute, script, total;
			times = moduleTimes[ moduleName ];
			if ( !times ) {
				return null;
			}
			for ( key in times ) {
				if ( times[ key ] === null ) {
					throw new Error( 'Incomplete perf record for "' + moduleName + '".', times );
				}
			}
			execute = times.executeEnd - times.executeStart;
			script = times.scriptEnd - times.scriptStart;
			total = execute + script;
			return {
				name: moduleName,
				execute: execute,
				script: script,
				total: total
			};
		}
	};

}() );
