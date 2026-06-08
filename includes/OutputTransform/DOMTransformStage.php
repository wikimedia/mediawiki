<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

/**
 * DOMTransformStage is a simple marker interface to indicate a
 * stage that primary performs transformations on the DOM
 * form of the ParserOutput ContentHolder.  This is used to minimize
 * the potential for mistakes when instantiating a
 * ContentHolderTransformStage.
 */
interface DOMTransformStage {
}
