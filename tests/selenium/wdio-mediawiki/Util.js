module.exports = {
	getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-Iñtërnâtiônàlizætiøn';
	}
};
