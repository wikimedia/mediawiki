<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\HookContainer\HookContainer;
use ResourceLoader;

/**
 * @internal
 * @ingroup ResourceLoader
 */
class HookRunner implements
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderForeignApiModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderRegisterModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteModulePagesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteStylesModulePagesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderTestModulesHook
{
	/** @var HookContainer */
	private $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	public function onResourceLoaderForeignApiModules( &$dependencies, $context ) : void {
		$this->container->run(
			'ResourceLoaderForeignApiModules',
			[ &$dependencies, $context ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderRegisterModules( ResourceLoader $rl ) : void {
		$this->container->run(
			'ResourceLoaderRegisterModules',
			[ $rl ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderSiteModulePages( $skin, array &$pages ) : void {
		$this->container->run(
			'ResourceLoaderSiteModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderSiteStylesModulePages( $skin, array &$pages ) : void {
		$this->container->run(
			'ResourceLoaderSiteStylesModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderTestModules( array &$testModules, ResourceLoader $rl ) : void {
		$this->container->run(
			'ResourceLoaderTestModules',
			[ &$testModules, $rl ],
			[ 'abortable' => false ]
		);
	}
}
