<?php

namespace MediaWiki\Output;

use MediaWiki\Title\TitleFactory;
use RequestContext;
use SkinFactory;

/**
 * Factory for IframeSandbox objects.
 */
class IframeSandboxFactory {

	private TitleFactory $titleFactory;
	private SkinFactory $skinFactory;

	public function __construct(
		TitleFactory $titleFactory,
		SkinFactory $skinFactory
	) {
		$this->titleFactory = $titleFactory;
		$this->skinFactory = $skinFactory;
	}

	/**
	 * @param RequestContext $context
	 * @return IframeSandbox
	 * @internal This approach is still being verified and not ready for general use.
	 */
	public function create( RequestContext $context ): IframeSandbox {
		return new IframeSandbox(
			$this->titleFactory,
			$this->skinFactory,
			$context
		);
	}

}
