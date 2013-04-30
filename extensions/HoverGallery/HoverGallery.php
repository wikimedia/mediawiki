<?php

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'HoverGallery',
	'description' => 'Adds a &lt;hovergallery&gt; tag that creates galleries like the standard ones, except that when the mouse hovers over the thumbnails, the full-size images are displayed.',
	'version' => 1,
	'author' => 'Luis Felipe Schenone',
	'url' => 'https://www.mediawiki.org/wiki/Extension:HoverGallery',
);

$wgResourceModules['ext.HoverGallery'] = array(
	'scripts' => 'HoverGallery.js',
	'styles' => 'HoverGallery.css',
	'position' => 'top',
	'localBasePath'	=> __DIR__,
	'remoteExtPath'	=> 'HoverGallery',
);

$wgAutoloadClasses['HoverGallery'] = __DIR__ . '/HoverGallery.body.php';

$wgHooks['BeforePageDisplay'][] = 'HoverGallery::Resources';
$wgHooks['ParserFirstCallInit'][] = 'HoverGallery::ParserHook';