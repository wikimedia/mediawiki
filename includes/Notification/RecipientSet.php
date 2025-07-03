<?php

namespace MediaWiki\Notification;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * @since 1.44
 * @unstable
 */
class RecipientSet implements IteratorAggregate, Countable {

	/** @var UserIdentity[] */
	private array $recipients = [];

	/**
	 * @param UserIdentity|UserIdentity[] $recipients
	 */
	public function __construct( $recipients ) {
		if ( !is_array( $recipients ) ) {
			$recipients = [ $recipients ];
		}
		Assert::parameterElementType( UserIdentity::class, $recipients, '$recipients' );
		foreach ( $recipients as $recipient ) {
			$this->addRecipient( $recipient );
		}
	}

	/** @return UserIdentity[] */
	public function getRecipients(): array {
		return array_values( $this->recipients );
	}

	public function addRecipient( UserIdentity $identity ): void {
		$this->recipients[ $identity->getName() ] = $identity;
	}

	public function removeRecipient( UserIdentity $identity ): void {
		unset( $this->recipients[ $identity->getName() ] );
	}

	/**
	 * @return ArrayIterator<UserIdentity>
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( array_values( $this->recipients ) );
	}

	public function count(): int {
		return count( $this->recipients );
	}
}
