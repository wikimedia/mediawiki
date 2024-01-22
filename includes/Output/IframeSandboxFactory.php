<?php

namespace MediaWiki\Output;

use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserFactory;
use RequestContext;
use SkinFactory;

/**
 * Factory for IframeSandbox objects.
 */
class IframeSandboxFactory {

	private TitleFactory $titleFactory;
	private SkinFactory $skinFactory;
	private UserFactory $userFactory;

	public function __construct(
		TitleFactory $titleFactory,
		SkinFactory $skinFactory,
		UserFactory $userFactory
	) {
		$this->titleFactory = $titleFactory;
		$this->skinFactory = $skinFactory;
		$this->userFactory = $userFactory;
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
			$this->userFactory,
			$context
		);
	}

}
