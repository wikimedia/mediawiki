/* eslint-env es6, node */
module.exports = {
	baseUrl: process.env.MW_SERVER + process.env.MW_SCRIPT_PATH + '/index.php?title=',
	logPath: process.env.WORKSPACE + '/log/',
	password: 'testpass',
	username: 'WikiAdmin'
};
