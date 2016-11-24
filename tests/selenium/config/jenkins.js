/* eslint-env es6, node */
module.exports = {
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH + '/index.php?title=',
	logPath: process.env.WORKSPACE + '/log/',
	mochaTestOptions: {
		captureFile: process.env.WORKSPACE + '/log/junit.xml',
		reporter: 'xunit',
		slow: 10000,
		timeout: 20000 },
	password: 'testpass',
	username: 'WikiAdmin'
};
