<?php
declare( strict_types = 1 );
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Parser\Parsoid\Config;

use InvalidArgumentException;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Parsoid\Config\PageContent as IPageContent;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;

/**
 * PageContent implementation for MediaWiki
 *
 * @since 1.39
 */
class PageContent extends IPageContent {
	public function __construct( private readonly RevisionRecord $rev ) {
	}

	/** @inheritDoc */
	public function getLinkTarget(): ParsoidLinkTarget {
		return $this->rev->getPageAsLinkTarget();
	}

	/** @inheritDoc */
	public function getRoles(): array {
		return $this->rev->getSlotRoles();
	}

	/** @inheritDoc */
	public function hasRole( string $role ): bool {
		return $this->rev->hasSlot( $role );
	}

	/**
	 * Throw if the revision doesn't have the named role
	 * @param string $role
	 * @throws InvalidArgumentException
	 */
	private function checkRole( string $role ): void {
		if ( !$this->rev->hasSlot( $role ) ) {
			throw new InvalidArgumentException( "PageContent does not have role '$role'" );
		}
	}

	/** @inheritDoc */
	public function getModel( string $role ): string {
		$this->checkRole( $role );
		return $this->rev->getContent( $role )->getModel();
	}

	/** @inheritDoc */
	public function getFormat( string $role ): string {
		$this->checkRole( $role );
		return $this->rev->getContent( $role )->getDefaultFormat();
	}

	/** @inheritDoc */
	public function getContent( string $role ): string {
		$this->checkRole( $role );
		return $this->rev->getContentOrThrow( $role )->serialize();
	}

}
