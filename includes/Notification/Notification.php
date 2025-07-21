<?php
namespace MediaWiki\Notification;

use MediaWiki\Notification\Types\WikiNotification;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\NonSerializable\NonSerializableTrait;

/**
 * @newable
 * @stable to extend
 * @since 1.45
 */
class Notification {

	use NonSerializableTrait;

	/**
	 * @TODO Idea: handle future types in format `namespace.type`, like `mediawiki.message`,
	 * `growth.welcome`. This way we can easily "override" some notifications, for example,
	 * we can have `echo.mention`, and the `echo.mention` could supersede `mediawiki.mention`. Also,
	 * it will be more difficult for notifications to conflict, and we will be able to easily filter
	 * apply logic depends on the namespace (for example, hide if extension not present).
	 *
	 * @var string Required, Read-only - The Notification type
	 */
	protected string $type;

	/**
	 * Internal, for now let's allow extensions to use generic notification and pass everything in
	 * custom array, but in future the notifications should have specific types.
	 * @var array An array of mixed data
	 */
	private array $custom;

	/**
	 * Base for Notifications. Type is the only required property, and any additional data can be
	 * passed via $custom array.
	 *
	 * @see WikiNotification in case you want a Notification with Agent and Title
	 * @param string $type Notification type
	 * @param (scalar|array|null|JsonCodecable)[] $custom Custom notification data, see
	 * setProperty() for more details about the allowed keys and values
	 */
	public function __construct( string $type, array $custom = [] ) {
		$this->type = $type;
		$this->custom = $custom;
	}

	/**
	 * Get the Notification type
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Sets a custom property to the notification.
	 *
	 * Any other keys may be used to pass additional data handled by specific extensions.*
	 * The value of keys other than those listed above should be (de)serializable as JSON, by either
	 * being scalar or array containing scalar values or using the JsonCodecable interface.
	 *
	 * @param string $key
	 * @param scalar|array|JsonCodecable $value
	 */
	public function setProperty( string $key, $value ): void {
		$this->custom[$key] = $value;
	}

	/**
	 * Retrieve Notification properties
	 */
	public function getProperties(): array {
		return $this->custom;
	}

}
