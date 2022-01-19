<?php

return [
	'config-schema' => [
		'AuthManagerAutoConfig' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'CapitalLinkOverrides' => [
			'mergeStrategy' => 'array_plus'
		],
		'ExtraGenderNamespaces' => [
			'mergeStrategy' => 'array_plus'
		],
		'GrantPermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'GroupPermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'Hooks' => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		'NamespaceContentModels' => [
			'mergeStrategy' => 'array_plus'
		],
		'NamespaceProtection' => [
			'mergeStrategy' => 'array_plus'
		],
		'NamespacesWithSubpages' => [
			'mergeStrategy' => 'array_plus'
		],
		'PasswordPolicy' => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		'RateLimits' => [
			'mergeStrategy' => 'array_plus_2d'
		],
		'RevokePermissions' => [
			'mergeStrategy' => 'array_plus_2d'
		],
	]
];
