// NOTE the process module is imported globally for node 0.x but needs to be
// required in node >= 4.x
var proc = (process === undefined) ? require('process') : process,
		webdriver = require( 'selenium-webdriver' ),
		nodemw = require('nodemw'),
		url = require('url'),
    env = {};

env.Environment = function () {
	this.browser = proc.env.BROWSER || 'firefox';
	this.serverURL = proc.env.MW_SERVER || 'http://127.0.0.1:8080';
	this.scriptPath = proc.env.MW_SCRIPT_PATH || '/w';

	this.url = url.parse(this.serverURL + this.scriptPath);

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

		self.articleURL = function ( path ) {
			return url.resolve(self.url, data.general.articlepath.replace('$1', path));
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
