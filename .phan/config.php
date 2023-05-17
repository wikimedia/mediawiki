<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = array_merge(
	$cfg['directory_list'], [
		'../../extensions/CreateWiki',
	]
);

$cfg['exclude_analysis_directory_list'] = array_merge(
	$cfg['exclude_analysis_directory_list'], [
		'../../extensions/CreateWiki',
	]
);

$cfg['suppress_issue_types'] = array_merge(
	$cfg['suppress_issue_types'], [
		'PhanTypeComparisonFromArray',
		'PhanTypeArraySuspiciousNullable',
		'PhanTypePossiblyInvalidDimOffset',
		'PhanTypeMismatchArgumentNullable',
		'PhanTypeMismatchDimFetch',
		'PhanImpossibleCondition',
		'PhanTypeMismatchArgumentInternal',
		'PhanNonClassMethodCall',
		'PhanImpossibleTypeComparison',
		'PhanRedundantConditionInLoop',
		// Must work on fixing this and unsuppress it 1 error that was unable to fix and single line suppressing didn't work
		'SecurityCheck-XSS',
	]
);

$cfg['scalar_implicit_cast'] = true;

return $cfg;
