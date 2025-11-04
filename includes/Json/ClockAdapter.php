<?php

namespace MediaWiki\Json;

use DateTimeImmutable;
use Lcobucci\Clock\Clock;
use Wikimedia\Timestamp\ConvertibleTimestamp;

// FIXME upgrade to a more recent lcobucci/jwt version that supports PSR-20, then add support for
//   that to ConvertibleTimestamp directly

class ClockAdapter implements Clock {

	public function now(): DateTimeImmutable {
		$ts = new ConvertibleTimestamp();
		return DateTimeImmutable::createFromMutable( $ts->timestamp );
	}

}
