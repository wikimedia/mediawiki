/*!
 * This file is currently loaded as part of the 'mediawiki' module and therefore
 * concatenated to mediawiki.js and executed at the same time. This file exists
 * to help prepare for splitting up the 'mediawiki' module.
 * This effort is tracked at https://phabricator.wikimedia.org/T192623
 *
 * In short:
 *
 * - mediawiki.js will be reduced to the minimum needed to define mw.loader and
 *   mw.config, and then moved to its own private "mediawiki.loader" module that
 *   can be embedded within the StartupModule response.
 *
 * - mediawiki.base.js and other files in this directory will remain part of the
 *   "mediawiki" module, and will remain a default/implicit dependency for all
 *   regular modules, just like jquery and wikibits already are.
 */
/* globals mw */
( function () {
	/**
	 * @class mw
	 * @singleton
	 */

	/**
	 * @inheritdoc mw.inspect#runReports
	 * @method
	 */
	mw.inspect = function () {
		var args = arguments;
		mw.loader.using( 'mediawiki.inspect', function () {
			mw.inspect.runReports.apply( mw.inspect, args );
		} );
	};
}() );
