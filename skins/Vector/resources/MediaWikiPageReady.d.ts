interface MediaWikiPageReadyModule {
	/**
	 * Loads search module when search input is focused.
	 * @param {string} moduleName to load on input focus.
	 */
	loadSearchModule(moduleName: string): void
}
