<?php

abstract class WebInstallerDocument extends WebInstallerPage {

	/**
	 * @return string
	 */
	abstract protected function getFileName();

	public function execute() {
		$text = $this->getFileContents();
		$text = InstallDocFormatter::format( $text );
		$this->parent->output->addWikiText( $text );
		$this->startForm();
		$this->endForm( false );
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

