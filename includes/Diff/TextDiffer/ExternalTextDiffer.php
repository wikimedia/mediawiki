<?php

namespace MediaWiki\Diff\TextDiffer;

use MediaWiki\Shell\Shell;
use RuntimeException;

/**
 * @since 1.41
 */
class ExternalTextDiffer extends BaseTextDiffer {
	/** @var string */
	private $externalPath;

	/**
	 * @param string $externalPath The path to the executable file which provides the diff
	 */
	public function __construct( $externalPath ) {
		$this->externalPath = $externalPath;
	}

	public function getName(): string {
		return 'external';
	}

	public function getFormats(): array {
		return [ 'external' ];
	}

	/** @inheritDoc */
	public function getFormatContext( string $format ) {
		return self::CONTEXT_ROW;
	}

	protected function doRenderBatch( string $oldText, string $newText, array $formats ): array {
		return [ 'external' => $this->doRender( $oldText, $newText ) ];
	}

	/**
	 * @param string $oldText
	 * @param string $newText
	 * @return string
	 */
	private function doRender( $oldText, $newText ) {
		$tmpDir = wfTempDir();
		$tempName1 = tempnam( $tmpDir, 'diff_' );
		$tempName2 = tempnam( $tmpDir, 'diff_' );

		$tempFile1 = fopen( $tempName1, "w" );
		if ( !$tempFile1 ) {
			throw new RuntimeException( "Could not create temporary file $tempName1 for external diffing" );
		}
		$tempFile2 = fopen( $tempName2, "w" );
		if ( !$tempFile2 ) {
			throw new RuntimeException( "Could not create temporary file $tempName2 for external diffing" );
		}
		fwrite( $tempFile1, $oldText );
		fwrite( $tempFile2, $newText );
		fclose( $tempFile1 );
		fclose( $tempFile2 );
		$cmd = [ $this->externalPath, $tempName1, $tempName2 ];
		$result = Shell::command( $cmd )
			->execute();
		$exitCode = $result->getExitCode();
		if ( $exitCode !== 0 ) {
			throw new RuntimeException( "External diff command returned code {$exitCode}. Stderr: "
				. wfEscapeWikiText( $result->getStderr() )
			);
		}
		$diffText = $result->getStdout();
		unlink( $tempName1 );
		unlink( $tempName2 );

		return $diffText;
	}
}
