<?php

namespace MediaWiki\Block;

use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;
use StatusValue;

/**
 * A block target for a registered user
 *
 * @since 1.44
 */
class UserBlockTarget extends BlockTarget implements BlockTargetWithUserPage {
	private UserIdentity $userIdentity;

	public function __construct( UserIdentity $userIdentity ) {
		parent::__construct( $userIdentity->getWikiId() );
		$this->userIdentity = $userIdentity;
	}

	public function toString(): string {
		return $this->userIdentity->getName();
	}

	public function getType(): int {
		return Block::TYPE_USER;
	}

	/** @inheritDoc */
	public function getSpecificity() {
		return 1;
	}

	public function getLogPage(): PageReference {
		return $this->getUserPage();
	}

	public function getUserPage(): PageReference {
		return new PageReferenceValue( NS_USER, $this->userIdentity->getName(), $this->wikiId );
	}

	public function getUserIdentity(): UserIdentity {
		return $this->userIdentity;
	}

	public function validateForCreation(): StatusValue {
		if ( !$this->userIdentity->isRegistered() ) {
			return StatusValue::newFatal(
				'nosuchusershort',
				wfEscapeWikiText( $this->userIdentity->getName() )
			);
		}
		return StatusValue::newGood();
	}

	/** @inheritDoc */
	protected function getLegacyUnion() {
		return $this->getUserIdentity();
	}
}
