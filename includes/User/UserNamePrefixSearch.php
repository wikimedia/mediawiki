<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Permissions\Authority;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Handles searching prefixes of user names
 *
 * @since 1.36
 * @ingroup User
 * @author DannyS712
 */
class UserNamePrefixSearch {

	public const AUDIENCE_PUBLIC = 'public';

	public function __construct(
		private readonly IConnectionProvider $dbProvider,
		private readonly UserNameUtils $userNameUtils,
		private readonly HideUserUtils $hideUserUtils
	) {
	}

	/**
	 * Do a prefix search of usernames and return a list of matching usernames.
	 *
	 * @param string|Authority $audience Either AUDIENCE_PUBLIC or a user to
	 *    show the search for
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return string[]
	 * @throws InvalidArgumentException if $audience is invalid
	 */
	public function search( $audience, string $search, int $limit, int $offset = 0 ): array {
		if ( $audience !== self::AUDIENCE_PUBLIC &&
			!( $audience instanceof Authority )
		) {
			throw new InvalidArgumentException(
				'$audience must be AUDIENCE_PUBLIC or an Authority object'
			);
		}

		// Invalid usernames are treated as empty strings
		$prefix = $this->userNameUtils->getCanonical( $search ) ?: '';

		$dbr = $this->dbProvider->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( $dbr->expr( 'user_name', IExpression::LIKE, new LikeValue( $prefix, $dbr->anyString() ) ) )
			->orderBy( 'user_name' )
			->limit( $limit )
			->offset( $offset );

		// Filter out hidden usernames
		if ( $audience === self::AUDIENCE_PUBLIC || !$audience->isAllowed( 'hideuser' ) ) {
			$queryBuilder->andWhere( $this->hideUserUtils->getExpression( $dbr ) );
		}

		return $queryBuilder->caller( __METHOD__ )->fetchFieldValues();
	}
}
