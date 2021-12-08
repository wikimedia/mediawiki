<?php

return [
	'config-schema' => [
		'wgAuthManagerAutoConfig' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'wgCapitalLinkOverrides' => [
			'mergeStrategy' => 'array_plus'
		],
		'wgExtraGenderNamespaces' => [
			'mergeStrategy' => 'array_plus'
		],
		'wgGrantPermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'wgGroupPermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'wgHooks' => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		'wgNamespaceContentModels' => [
			'mergeStrategy' => 'array_plus'
		],
		'wgNamespaceProtection' => [
			'mergeStrategy' => 'array_plus'
		],
		'wgNamespacesWithSubpages' => [
			'mergeStrategy' => 'array_plus'
		],
		'wgPasswordPolicy' => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		'wgRateLimits' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'wgRevokePermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
	]
];
