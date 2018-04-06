'use strict';
exports.config = {

	specs: [
		'./features/**/*.feature'
	],
	framework: 'cucumber',
	// If you are using Cucumber you need to specify the location of your step definitions.
	cucumberOpts: {
		require: [ './features/step-definitions' ], // <string[]> (file/dir) require files before executing features
		backtrace: false, // <boolean> show full backtrace for errors
		compiler: [], // <string[]> ("extension:module") require files with the given EXTENSION after requiring MODULE (repeatable)
		dryRun: false, // <boolean> invoke formatters without executing steps
		failFast: false, // <boolean> abort the run on first failure
		format: [ 'pretty' ], // <string[]> (type[:path]) specify the output format, optionally supply PATH to redirect formatter output (repeatable)
		colors: true, // <boolean> disable colors in formatter output
		snippets: true, // <boolean> hide step definition snippets for pending steps
		source: true, // <boolean> hide source uris
		profile: [], // <string[]> (name) specify the profile to use
		strict: false, // <boolean> fail if there are any undefined or pending steps
		tags: [], // <string[]> (expression) only execute the features or scenarios with tags matching the expression
		timeout: 20000, // <number> timeout for step definitions
		ignoreUndefinedDefinitions: false // <boolean> Enable this config to treat undefined definitions as warnings.
	}

	//
	// =====
	// Hooks
	// =====

	/**
     * Runs before a Cucumber feature
     * @param {Object} feature feature details
     */
	// beforeFeature: function (feature) {
	// },
	/**
     * Runs before a Cucumber scenario
     * @param {Object} scenario scenario details
     */
	// beforeScenario: function (scenario) {
	// },
	/**
     * Runs before a Cucumber step
     * @param {Object} step step details
     */
	// beforeStep: function (step) {
	// },
	/**
     * Runs after a Cucumber step
     * @param {Object} stepResult step result
     */
	// afterStep: function (stepResult) {
	// },
	/**
     * Runs after a Cucumber scenario
     * @param {Object} scenario scenario details
     */
	// afterScenario: function (scenario) {
	// },
	/**
     * Runs after a Cucumber feature
     * @param {Object} feature feature details
     */
	// afterFeature: function (feature) {
	// },

};
