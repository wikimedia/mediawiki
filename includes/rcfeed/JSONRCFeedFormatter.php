<?php

class JSONRCFeedFormatter extends MachineReadableRCFeedFormatter {

	protected function formatArray( array $packet ) {
		return FormatJson::encode( $packet );
	}
}
