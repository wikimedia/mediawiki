<?php

final class MediaWikiCliOptions {
	/**
	 * @fixme This is an awful hack.
	 */
	public static $additionalOptions = [
		'use-filebackend' => false,
		'use-bagostuff' => false,
		'use-jobqueue' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
	];
}
