module.exports = {
	getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-Iñtërnâtiônàlizætiøn';
	},

	/**
	 * Wait for a given module to reach a specific state
	 * @param {string} moduleName The name of the module to wait for
	 * @param {string} moduleStatus 'registered', 'loaded', 'loading', 'ready', 'error', 'missing'
	 * @param {int} timeout The wait time in milliseconds before the wait fails
	 */
	waitForModuleState( moduleName, moduleStatus = 'ready', timeout = 2000 ) {
		browser.waitUntil( () => {
			const result = browser.execute( ( module ) => {
				return typeof mw !== 'undefined' &&
					mw.loader.getState( module.name ) === module.status;
			}, { status: moduleStatus, name: moduleName } );
			return result.value;
		}, timeout, 'Failed to wait for ' + moduleName + ' to be ' + moduleStatus + ' after ' + timeout + ' ms.' );
	}
};
