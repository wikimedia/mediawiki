<?php

/**
 * Some of the classes introduced in PHPUnit 6 that MediaWiki references.
 *
 * These are here because we currently also support PHPUnit 4, in which
 * these classes have slightly different names. Actual use within
 * MediaWiki is properly conditional, but depending on which version
 * PHPUnit is installed when Phan runs, Phan will either report the
 * PHPUnit 4 class name as undefined, or the PHPUnit 6 class name as
 * undefined. Fix that by declaring them here.
 *
 * See also phpunit4.php
 *
 * phpcs:ignoreFile
 */

namespace PHPUnit\TextUI;

class Command {

}
