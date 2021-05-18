/**
 * SVGO Configuration
 * Recommended options from:
 * https://www.mediawiki.org/wiki/Manual:Coding_conventions/SVG#Exemplified_safe_configuration
 */
const { extendDefaultPlugins } = require( 'svgo' );
module.exports = {
	multipass: true,
	plugins: extendDefaultPlugins( [
		{
			name: 'cleanupIDs',
			active: false
		},
		{
			name: 'removeDesc',
			active: false
		},
		{
			name: 'removeRasterImages',
			active: true
		},
		{
			name: 'removeTitle',
			active: false
		},
		{
			name: 'removeViewBox',
			active: false
		},
		{
			// If the SVG doesn't start with an XML declaration, then its MIME type will
			// be detected as "text/plain" rather than "image/svg+xml" by libmagic and,
			// consequently, MediaWiki's CSSMin CSS minifier. libmagic's default database
			// currently requires that SVGs contain an XML declaration:
			// https://github.com/threatstack/libmagic/blob/master/magic/Magdir/sgml#L5
			name: 'removeXMLProcInst',
			active: false
		},
		{
			name: 'sortAttrs',
			active: true
		}
	] ),

	// Configure the indent (default 4 spaces) used by `--pretty` here:
	// @see https://github.com/svg/svgo/blob/master/lib/svgo/js2svg.js#L6 for more config options
	//
	// Unfortunately EOL cannot be configured, SVGO uses the platform's EOL marker.
	// On non-unix systems the linebreaks will be normalized to LF (unix) only at git commit,
	// assuming `core.autocrlf` is 'true' (default) or 'input'.
	js2svg: {
		indent: "\t",
		pretty: true,
	}
}
