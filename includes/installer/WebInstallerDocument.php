<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

abstract class WebInstallerDocument extends WebInstallerPage {

	/**
	 * @return string
	 */
	abstract protected function getFileName();

	/** @inheritDoc */
	public function execute() {
		$text = $this->getFileContents();
		$text = InstallDocFormatter::format( $text );
		$this->parent->output->addWikiTextAsInterface( $text );
		$this->startForm();
		$this->endForm( false );
		return '';
	}

	/**
	 * @return string
	 */
	public function getFileContents() {
		$file = __DIR__ . '/../../' . $this->getFileName();
		if ( !file_exists( $file ) ) {
			return wfMessage( 'config-nofile', $file )->plain();
		}

		return file_get_contents( $file );
	}

}
