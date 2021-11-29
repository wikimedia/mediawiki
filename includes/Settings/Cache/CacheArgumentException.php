<?php

namespace MediaWiki\Settings\Cache;

use MediaWiki\Settings\SettingsBuilderException;
use Psr\SimpleCache\InvalidArgumentException;

class CacheArgumentException
	extends SettingsBuilderException
	implements InvalidArgumentException
{
}
