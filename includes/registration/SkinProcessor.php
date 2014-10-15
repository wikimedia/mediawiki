<?php

class SkinProcessor extends ExtensionProcessor {

	public function __construct() {
		static::$globalSettings[] = 'ValidSkinNames';
	}
}
