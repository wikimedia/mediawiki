<?php

namespace MediaWiki\Rest\Hook;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestInterface;

class HookRunner implements RestCheckCanExecuteHook {

	private HookContainer $container;

	public function __construct( HookContainer $container ) {
		$this->container = $container;
	}

	public function onRestCheckCanExecute(
		Module $module,
		Handler $handler,
		string $path,
		RequestInterface $request,
		?HttpException &$error
	): bool {
		return $this->container->run(
			'RestCheckCanExecute',
			[ $module, $handler, $path, $request, &$error ]
		);
	}

}
