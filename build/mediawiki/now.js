module.exports = ( function () {
	var perf = window.performance,
		navStart = perf && perf.timing && perf.timing.navigationStart;
	return navStart && typeof perf.now === 'function' ?
		function () { return navStart + perf.now(); } :
		function () { return +new Date(); };
}() );
