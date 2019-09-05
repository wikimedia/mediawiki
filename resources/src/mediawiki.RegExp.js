( function () {
	mw.RegExp = {};
	// Backwards-compatible alias; @deprecated since 1.34
	mw.log.deprecate( mw.RegExp, 'escape', mw.util.escapeRegExp, 'Use mw.util.escapeRegExp() instead.', 'mw.RegExp.escape' );
}() );
