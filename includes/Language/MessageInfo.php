<?php

namespace MediaWiki\Language;

/**
 * Class to hold extra information about the result of a MessageCache::get() call.
 */
class MessageInfo {
	/**
	 * @var string|null The message key used, after overrides
	 */
	public ?string $usedKey = null;
	/**
	 * @var string|null The language code used, after fallbacks
	 */
	public ?string $langCode = null;
}
