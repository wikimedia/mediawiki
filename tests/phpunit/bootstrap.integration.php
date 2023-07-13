<?php

/**
 * Bootstrapping for MediaWiki PHPUnit tests that allows running integration tests.
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

require_once __DIR__ . '/bootstrap.common.php';

TestSetup::loadSettingsFiles();

TestSetup::maybeCheckComposerLockUpToDate();
