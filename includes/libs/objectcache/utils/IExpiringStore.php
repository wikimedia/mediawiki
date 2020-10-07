<?php

use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\LightweightObjectStore\StorageAwareness;

/**
 * Generic interface providing TTL constants for lightweight expiring object stores
 *
 * @ingroup Cache
 * @since 1.27
 * @deprecated 1.35
 */
interface IExpiringStore extends StorageAwareness, ExpirationAwareness {

}
