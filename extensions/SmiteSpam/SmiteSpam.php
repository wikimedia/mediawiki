<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not a valid entry point.' );
}

$wgExtensionCredits['antispam'][] = array(
	'path' => __FILE__,
	'name' => 'SmiteSpam',
	'namemsg' => 'smitespam-extensionname',
	'author' => 'Vivek Ghaisas',
	'descriptionmsg'  => 'smitespam-desc',
	'license-name' => 'GPL-2.0',
	'version' => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:SmiteSpam',
);

$ssRoot = __DIR__;

require_once "$ssRoot/autoload.php";

$wgMessagesDirs['SmiteSpam'] = "$ssRoot/i18n";
$wgExtensionMessagesFiles['SmiteSpamAlias'] = "$ssRoot/SmiteSpam.alias.php";
$wgSpecialPages['SmiteSpam'] = 'SpecialSmiteSpam';
$wgSpecialPages['SmiteSpamTrustedUsers'] = 'SpecialSmiteSpamTrustedUsers';

$wgAvailableRights[] = 'smitespam';
$wgGroupPermissions['sysop']['smitespam'] = true;

$wgAPIModules['smitespamanalyze'] = 'SmiteSpamApiQuery';
$wgAPIModules['smitespamtrustuser'] = 'SmiteSpamApiTrustUser';

$wgHooks['LoadExtensionSchemaUpdates'][] = 'SmiteSpamHooks::createTables';
$wgHooks['AdminLinks'][] = 'SmiteSpamHooks::addToAdminLinks';

$wgResourceModules['ext.SmiteSpam.retriever'] = array(
	'scripts' => 'js/ext.smitespam.js',
	'styles' => 'css/smitespam.css',
	'localBasePath' => "$ssRoot/static",
	'remoteExtPath' => 'SmiteSpam/static',
	'dependencies' => array(
		'mediawiki.jqueryMsg',
		'jquery.spinner'
	),
	'messages' => array(
		'smitespam-block',
		'smitespam-block-reason',
		'smitespam-blocked',
		'smitespam-block-failed',
		'smitespam-created-by',
		'smitespam-delete',
		'smitespam-delete-page-failure-msg',
		'smitespam-loading',
		'smitespam-trust',
		'smitespam-trusted',
		'table_pager_next',
		'table_pager_prev',
		'smitespam-blocked-user-failure-msg',
		'smitespam-blocked-user-success-msg',
		'smitespam-delete-page-failure-msg',
		'smitespam-delete-page-success-msg',
		'smitespam-trusted-user-failure-msg',
		'smitespam-trusted-user-success-msg',
		'powersearch-toggleall',
		'powersearch-togglenone',
		'smitespam-deleted-reason',
		'smitespam-probability-low',
		'smitespam-probability-medium',
		'smitespam-probability-high',
		'smitespam-probability-very-high'
	),
);

// Config options

// List of enabled checkers and respective weights
$wgSmiteSpamCheckers = array(
	'ExternalLinks' => 1,
	'RepeatedExternalLinks' => 1,
	'Wikitext' => 1,
);

// Threshold (tolerance)
// Pages analyzed as having a spam "probability" higher than this will be shown on Special Page
$wgSmiteSpamThreshold = 0.7;

// Ignore pages smaller than 500 characters?
$wgSmiteSpamIgnoreSmallPages = true;

// Should SmiteSpam ignore all pages that don't have any external links
// outside of template calls?
$wgSmiteSpamIgnorePagesWithNoExternalLinks = true;

// Number of pages to analyze in one AJAX request
$wgQueryPageSize = 500;

// Number of pages to display in one paginated page
$wgDisplayPageSize = 250;
