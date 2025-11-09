<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Gallery;

/**
 * Same as Packed except different CSS is applied to make the
 * caption only show up on hover. If a touch screen is detected,
 * falls back to PackedHoverGallery. Degrades gracefully for
 * screen readers.
 */
class PackedHoverImageGallery extends PackedOverlayImageGallery {
}

/** @deprecated class alias since 1.46 */
class_alias( PackedHoverImageGallery::class, 'PackedHoverImageGallery' );
