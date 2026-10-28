<?php

namespace MediaWiki\EditPage;

use MediaWiki\Html\Html;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\PageEdit\PageEditStatus;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @internal
 * @since 1.48
 */
class EditStatusFormatter {

	private const array WARNINGS_TO_WRAP = [
		'edit-constraint-newsectionmissingsubject-warning',
		'edit-constraint-selfredirect-warning',
		'edit-constraint-brokenredirect-warning',
		'edit-constraint-doubleredirect-loop-warning',
		'edit-constraint-doubleredirect-warning',
		'edit-constraint-invalidredirecttarget-warning',
		'edit-constraint-defaulttext-blank-warning',
	];

	public function __construct(
		private readonly MessageLocalizer $localizer,
	) {
	}

	/**
	 * Wrap warning/error messages in styled message boxes.
	 *
	 * @param PageEditStatus $status
	 * @param MessageSpecifier|null $submitButtonLabel If not null, resubmittable warnings will be wrapped
	 * using the "edit-constraint-resubmittable-warning" message.
	 * @return string HTML
	 */
	public function formatStatus(
		PageEditStatus $status,
		?MessageSpecifier $submitButtonLabel = null,
	): string {
		$warningMessages = $status->getMessages( 'warning' );
		if ( $status->canResubmit() && $submitButtonLabel ) {
			$warningMessages = $this->wrapResubmittableWarnings( $warningMessages, $submitButtonLabel );
		}

		return $this->createMessageBox( $status->getMessages( 'error' ), 'error' ) .
			$this->createMessageBox( $warningMessages, 'warning' );
	}

	/**
	 * @param MessageSpecifier[] $messages
	 * @param MessageSpecifier $submitButtonLabel
	 * @return MessageSpecifier[]
	 */
	private function wrapResubmittableWarnings( array $messages, MessageSpecifier $submitButtonLabel ): array {
		return array_map( static function ( MessageSpecifier $msg ) use ( $submitButtonLabel ) {
			if ( !in_array( $msg->getKey(), self::WARNINGS_TO_WRAP ) ) {
				return $msg;
			}
			return MessageValue::new(
				'edit-constraint-resubmittable-warning',
				[
					$msg,
					$submitButtonLabel,
				]
			);
		}, $messages );
	}

	/**
	 * Create a message box containing multiple messages.
	 *
	 * @param MessageSpecifier[] $messages
	 * @param string $type `warning` or `error`
	 * @return string HTML
	 */
	private function createMessageBox( array $messages, string $type ): string {
		if ( !$messages ) {
			// Don't create a box if there are no messages
			return '';
		}

		$html = implode(
			Html::openElement( 'br' ),
			array_map( fn ( $msg ) => $this->localizer->msg( $msg )->parse(), $messages )
		);

		if ( $type === 'warning' ) {
			return Html::warningBox( $html );
		}
		return Html::errorBox( $html );
	}

}
