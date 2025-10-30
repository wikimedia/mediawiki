<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Shellbox\Command\UnboxedResult;

// NO_AUTOLOAD -- breaks AutoLoaderStructureTest if included in classmap
class_alias( UnboxedResult::class, 'MediaWiki\\Shell\\Result', true );
