<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\DAO;

use MediaWiki\Page\PageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;

/**
 * Helper trait for {@link WikiAwareEntity implementations}
 * @package MediaWiki\DAO
 */
trait WikiAwareEntityTrait {

	/**
	 * Get the ID of the wiki this entity belongs to.
	 *
	 * @since 1.36
	 *
	 * @see RevisionRecord::getWikiId()
	 * @see UserIdentity::getWikiId()
	 * @see PageIdentity::getWikiId()
	 * @see Block::getWikiId()
	 *
	 * @return string|false The wiki's logical name or WikiAwareEntity::LOCAL for the local wiki
	 */
	abstract public function getWikiId();

	/**
	 * Throws if $wikiId is not the same as this entity wiki.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *
	 * @throws PreconditionException
	 */
	public function assertWiki( $wikiId ) {
		if ( $wikiId !== $this->getWikiId() ) {
			$expected = $this->wikiIdToString( $wikiId );
			$actual = $this->wikiIdToString( $this->getWikiId() );
			throw new PreconditionException(
				"Expected " . __CLASS__ . " to belong to $expected, but it belongs to $actual"
			);
		}
	}

	/**
	 * Emits a deprecation warning $since version if $wikiId is not the same as this wiki.
	 *
	 * @param string|false $wikiId
	 * @param string $since
	 */
	protected function deprecateInvalidCrossWiki( $wikiId, string $since ) {
		if ( $wikiId !== $this->getWikiId() ) {
			$expected = $this->wikiIdToString( $wikiId );
			$actual = $this->wikiIdToString( $this->getWikiId() );
			wfDeprecatedMsg(
				'Deprecated cross-wiki access to ' . __CLASS__ . '. ' .
				"Expected: {$expected}, Actual: {$actual}. " .
				"Pass expected \$wikiId.",
				$since
			);
		}
	}

	/**
	 * Asserts correct $wikiId parameter was passed.
	 *
	 * @param string|false $wikiId
	 */
	protected function assertWikiIdParam( $wikiId ) {
		Assert::parameterType( [ 'string', 'false' ], $wikiId, '$wikiId' );
	}

	/**
	 * Convert $wikiId to a string for logging.
	 *
	 * @param string|false $wikiId
	 * @return string
	 */
	private function wikiIdToString( $wikiId ): string {
		return $wikiId === WikiAwareEntity::LOCAL ? 'the local wiki' : "'{$wikiId}'";
	}
}
