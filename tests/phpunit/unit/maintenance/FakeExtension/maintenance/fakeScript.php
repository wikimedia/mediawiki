<?php
namespace MediaWiki\Extension\FakeExtension\Maintenance;

use Maintenance;

//phpcs:ignore MediaWiki.Files.ClassMatchesFilename.WrongCase
class FakeScript extends Maintenance {

	public function execute() {
		// do nothing
	}
}

$maintClass = FakeScript::class;
