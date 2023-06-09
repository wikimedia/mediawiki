// For a detailed explanation regarding each configuration property, visit:
// https://jestjs.io/docs/en/configuration.html

module.exports = {
	moduleNameMapper: {
		'^./templates/(.*).mustache': '<rootDir>/includes/templates/$1.mustache'
	},

	// Automatically clear mock calls and instances between every test
	clearMocks: true,

	// Indicates whether the coverage information should be collected while executing the test
	collectCoverage: true,

	// An array of glob patterns indicating a set of files fo
	//  which coverage information should be collected
	collectCoverageFrom: [
		'resources/**/*.(js|vue)'
	],

	// The directory where Jest should output its coverage files
	coverageDirectory: 'coverage',

	// An array of regexp pattern strings used to skip coverage collection
	coveragePathIgnorePatterns: [
		'/node_modules/'
	],

	// An object that configures minimum threshold enforcement for coverage results
	coverageThreshold: {
		global: {
			branches: 31,
			functions: 39,
			lines: 38,
			statements: 38
		}
	},

	// An array of file extensions your modules use
	moduleFileExtensions: [
		'js',
		'json',
		'vue'
	],

	// The paths to modules that run some code to configure or
	// set up the testing environment before each test
	setupFiles: [
		'./jest.setup.js'
	],

	testEnvironment: 'jsdom',

	transform: {
		'^.+\\.mustache?$': 'mustache-jest',
		'.*\\.(vue)$': '<rootDir>/node_modules/@vue/vue3-jest'
	}
};
