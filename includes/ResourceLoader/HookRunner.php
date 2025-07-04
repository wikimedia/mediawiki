<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookContainer;

/**
 * @internal
 * @codeCoverageIgnore
 * @ingroup ResourceLoader
 */
class HookRunner implements
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderExcludeUserOptionsHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderForeignApiModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderModifyEmbeddedSourceUrlsHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderRegisterModulesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteModulePagesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderSiteStylesModulePagesHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderGetConfigVarsHook,
	\MediaWiki\ResourceLoader\Hook\ResourceLoaderJqueryMsgModuleMagicWordsHook
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

	/** @inheritDoc */
	public function onResourceLoaderForeignApiModules( &$dependencies, $context ): void {
		$this->container->run(
			'ResourceLoaderForeignApiModules',
			[ &$dependencies, $context ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderModifyEmbeddedSourceUrls( array &$urls ): void {
		$this->container->run(
			'ResourceLoaderModifyEmbeddedSourceUrls',
			[ &$urls ],
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

	/** @inheritDoc */
	public function onResourceLoaderSiteModulePages( $skin, array &$pages ): void {
		$this->container->run(
			'ResourceLoaderSiteModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onResourceLoaderSiteStylesModulePages( $skin, array &$pages ): void {
		$this->container->run(
			'ResourceLoaderSiteStylesModulePages',
			[ $skin, &$pages ],
			[ 'abortable' => false ]
		);
	}

	/** @inheritDoc */
	public function onResourceLoaderGetConfigVars( array &$vars, $skin, Config $config ): void {
		$this->container->run(
			'ResourceLoaderGetConfigVars',
			[ &$vars, $skin, $config ],
			[ 'abortable' => false ]
		);
	}

	public function onResourceLoaderJqueryMsgModuleMagicWords( Context $context,
		array &$magicWords
	): void {
		$this->container->run(
			'ResourceLoaderJqueryMsgModuleMagicWords',
			[ $context, &$magicWords ],
			[ 'abortable' => false ]
		);
	}
}
