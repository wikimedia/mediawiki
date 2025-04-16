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

	/**
	 * @var UserIdentity[]
	 */
	private array $recipients;

	/**
	 * @param UserIdentity|UserIdentity[] $recipients
	 */
	public function __construct( $recipients ) {
		if ( !is_array( $recipients ) ) {
			$recipients = [ $recipients ];
		}
		Assert::parameterElementType( UserIdentity::class, $recipients, '$recipients' );
		$this->recipients = $recipients;
	}

	/** @return UserIdentity[] */
	public function getRecipients(): array {
		return $this->recipients;
	}

	/**
	 * @param UserIdentity $identity
	 * @return void
	 */
	public function addRecipient( UserIdentity $identity ) {
		$this->recipients[] = $identity;
	}

	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->recipients );
	}

	public function count(): int {
		return count( $this->recipients );
	}
}
