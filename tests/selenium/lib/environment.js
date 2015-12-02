var process = require( 'process' ),
    webdriver = require( 'selenium-webdriver' ),
		nodemw = require('nodemw'),
		url = require('url'),
    env = {};

env.Environment = function () {
	this.browser = process.env.BROWSER || 'firefox';
	this.scriptPath = process.env.MW_SCRIPT_PATH || 'http://127.0.0.1:8080/w';

	this.url = url.parse(this.scriptPath);

	this.api = new nodemw({
		protocol: this.url.protocol,
		server: this.url.hostname,
		port: this.url.port,
		path: this.url.path
	});
};

env.Environment.prototype.init = function (done) {
	var self = this;

	this.api.getSiteInfo(['general'], function (err, data) {
		if (err) throw err;

		var parts = data.general.articlepath.split('$1');

		self.articleURL = function ( path ) {
			var fullPath = parts[0] + path + parts[1];
			return url.resolve(self.scriptPath, fullPath);
		};

		done();
	});
};

env.Environment.prototype.articleURL = function ( path ) {
	throw 'the MW article path is unknown';
};

env.Environment.prototype.pageURL = function ( page ) {
	return this.articleURL(page.path);
};

env.Environment.prototype.startBrowser = function () {
	return new webdriver.Builder().forBrowser( this.browser ).build();
};

module.exports = env;
