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

namespace MediaWiki\Page\Event;

use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain events representing changes to pages.
 *
 * @todo: decide whether we want to change the name to PageChangedEvent
 * and rename PageUpdatedEvent to something more descriptive, like
 * PageContentUpdatedEvent.
 *
 * @unstable until 1.45
 */
abstract class PageEvent extends DomainEvent {

	public const TYPE = 'Page';

	private ProperPageIdentity $page;
	private UserIdentity $performer;

	/** @var array<string,bool> */
	private array $flags;

	/** @var array<string> */
	private array $tags;

	/**
	 * @param ProperPageIdentity $page The page affected by the update.
	 * @param UserIdentity $performer The user performing the update.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		ProperPageIdentity $page,
		UserIdentity $performer,
		array $tags = [],
		array $flags = [],
		$timestamp = false
	) {
		parent::__construct( $timestamp );
		$this->declareEventType( self::TYPE );

		Assert::parameterElementType( 'string', $tags, '$tags' );
		Assert::parameterKeyType( 'integer', $tags, '$tags' );

		Assert::parameterElementType( 'boolean', $flags, '$flags' );
		Assert::parameterKeyType( 'string', $flags, '$flags' );

		Assert::parameter( $page->exists(), '$page', 'must exist' );

		$this->page = $page;
		$this->performer = $performer;
		$this->tags = $tags;
		$this->flags = $flags;
	}

	/**
	 * Returns the page that was edited.
	 */
	public function getPage(): PageIdentity {
		return $this->page;
	}

	/**
	 * Returns the user that performed the update.
	 * For an edit, this will be the same as the user returned by getAuthor().
	 * However, it may be a different user for update events caused e.g. by
	 * undeletion or imports.
	 */
	public function getPerformer(): UserIdentity {
		return $this->performer;
	}

	/**
	 * Checks flags describing the page update.
	 * Use with FLAG_XXX constants declared by subclasses.
	 */
	public function hasFlag( string $name ): bool {
		return $this->flags[$name] ?? false;
	}

	/**
	 * Returns any tags applied to the edit.
	 * @see ChangeTags
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * Checks for a tag associated the page update.
	 *
	 * @see ChangeTags
	 */
	public function hasTag( string $name ): bool {
		return in_array( $name, $this->tags );
	}

}
