<?php
$wgSiteName = 'TestSite';
$wgSomething = 'TEST';
$wgStyleDirectory = '/test/skins';
$wgExtraLanguageCodes['no'] = 'nb';

$xySillyStuff = 'Silly';

// New style PHP based config: return a settings array.
return [
	'config' => [
		'ExtensionDirectory' => '/test/extensions',
		'ForeignUploadTargets' => [ 'acme' ],
	],
	'config-schema' => [
		'Extra' => [ 'default' => 'extra' ]
	],
];
