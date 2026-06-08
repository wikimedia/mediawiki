<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform;

/**
 * TextTransformStage is a simple marker interface to indicate a
 * stage that primary performs transformations on the text ("html string")
 * form of the ParserOutput ContentHolder.  This is used to minimize
 * the potential for mistakes when instantiating a
 * ContentHolderTransformStage.
 */
interface TextTransformStage {
}
