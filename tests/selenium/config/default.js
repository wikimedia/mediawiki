module.exports = {
	baseUrl: 'http://127.0.0.1:8080/w/index.php?title=',
	browser: 'chrome',
	logPath: 'log/',
	mochaTestOptions: {
		reporter: 'spec',
		slow: 10000,
		timeout: 20000 },
	password: 'vagrant',
	username: 'Admin'
};
