<?php

namespace MediaWiki\Notification;

use ArrayIterator;
use IteratorAggregate;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * @since 1.44
 * @unstable
 */
class RecipientSet implements IteratorAggregate {

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

	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->recipients );
	}
}
