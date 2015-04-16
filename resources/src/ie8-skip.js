/*!
 * Skip function for ie8-shim module.
 *
 * Tests for window.Node because that's the only thing that this shim is adding.
 */
return ( function () {
	return !!window.Node;
}() );
