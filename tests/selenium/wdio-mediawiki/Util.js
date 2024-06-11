'use strict';

module.exports = {
	getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-Iñtërnâtiônàlizætiøn';
	},

	/**
	 * Wait for a given module to reach a specific state
	 *
	 * @param {string} moduleName The name of the module to wait for
	 * @param {string} moduleStatus 'registered', 'loaded', 'loading', 'ready', 'error', 'missing'
	 * @param {number} timeout The wait time in milliseconds before the wait fails
	 */
	async waitForModuleState( moduleName, moduleStatus = 'ready', timeout = 5000 ) {
		await browser.waitUntil(
			async () => await browser.execute(
				( arg ) => typeof mw !== 'undefined' && mw.loader.getState( arg.name ) === arg.status,
				{ status: moduleStatus, name: moduleName }
			), {
				timeout: timeout,
				timeoutMsg: 'Failed to wait for ' + moduleName + ' to be ' + moduleStatus + ' after ' + timeout + ' ms.'
			}
		);
	}
};
