<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Watchlist
 */

namespace MediaWiki\Watchlist;

use DomainException;
use MediaWiki\User\UserIdentity;

/**
 * @ingroup Watchlist
 */
class WatchlistLabel {

	private string $name;

	public function __construct(
		private readonly UserIdentity $user,
		string $name,
		private ?int $id = null,
	) {
		$this->setName( $name );
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function setId( int $id ): void {
		if ( $this->id ) {
			throw new DomainException( 'WatchlistLabel ID can not be changed' );
		}
		$this->id = $id;
	}

	public function getUser(): UserIdentity {
		return $this->user;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * Change the label's name.
	 *
	 * @param string $name The new name.
	 */
	public function setName( string $name ): void {
		$this->name = trim( $name );
	}
}
