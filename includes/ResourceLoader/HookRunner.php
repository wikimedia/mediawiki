<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\HookContainer\HookContainer;

/**
 * @internal
 * @codeCoverageIgnore
 * @ingroup ResourceLoader
 */
class HookRunner implements
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderExcludeUserOptionsHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderForeignApiModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderRegisterModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteModulePagesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteStylesModulePagesHook
{
	/** @var HookContainer */
	private $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	public function onResourceLoaderExcludeUserOptions( array &$keysToExclude, Context $context ): void {
		$this->container->run(
			'ResourceLoaderExcludeUserOptions',
			[ &$keysToExclude, $context ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderForeignApiModules( &$dependencies, $context ): void {
		$this->container->run(
			'ResourceLoaderForeignApiModules',
			[ &$dependencies, $context ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderRegisterModules( ResourceLoader $rl ): void {
		$this->container->run(
			'ResourceLoaderRegisterModules',
			[ $rl ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderSiteModulePages( $skin, array &$pages ): void {
		$this->container->run(
			'ResourceLoaderSiteModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderSiteStylesModulePages( $skin, array &$pages ): void {
		$this->container->run(
			'ResourceLoaderSiteStylesModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}
}
