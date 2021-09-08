diff --git a/extensions/PdfHandler/includes/PdfHandler.php b/extensions/PdfHandler/includes/PdfHandler.php
index d6272aa2..a51e4871 100644
--- a/extensions/PdfHandler/includes/PdfHandler.php
+++ b/extensions/PdfHandler/includes/PdfHandler.php
@@ -80,6 +80,116 @@ class PdfHandler extends ImageHandler {
 		return false;
 	}
 
+	/**
+	 * @inheritDoc
+	 * @stable to override
+	 * @param File $image
+	 * @param array &$params
+	 * @return bool
+	 */
+	public function normaliseParams( $image, &$params ) {
+		$mimeType = $image->getMimeType();
+
+		if ( !isset( $params['width'] ) ) {
+			return false;
+		}
+
+		if ( !isset( $params['page'] ) ) {
+			$params['page'] = 1;
+		} else {
+			$params['page'] = intval( $params['page'] );
+			if ( $params['page'] > $image->pageCount() ) {
+				$params['page'] = $image->pageCount();
+			}
+
+			if ( $params['page'] < 1 ) {
+				$params['page'] = 1;
+			}
+		}
+
+		$srcWidth = $image->getWidth( $params['page'] );
+		$srcHeight = $image->getHeight( $params['page'] );
+		// HW: Make sure src params are set
+		if ( !$srcHeight ) {
+			$srcHeight = $params['height'];
+		}
+		if ( !$srcWidth ) {
+			$srcWidth = $params['width'];
+		}
+		if ( isset( $params['height'] ) && $params['height'] != -1 ) {
+			# Height & width were both set
+			if ( $params['width'] * $srcHeight > $params['height'] * $srcWidth ) {
+				# Height is the relative smaller dimension, so scale width accordingly
+				$params['width'] = self::fitBoxWidth( $srcWidth, $srcHeight, $params['height'] );
+
+				if ( $params['width'] == 0 ) {
+					# Very small image, so we need to rely on client side scaling :(
+					$params['width'] = 1;
+				}
+
+				$params['physicalWidth'] = $params['width'];
+			} else {
+				# Height was crap, unset it so that it will be calculated later
+				unset( $params['height'] );
+			}
+		}
+
+		if ( !isset( $params['physicalWidth'] ) ) {
+			# Passed all validations, so set the physicalWidth
+			$params['physicalWidth'] = $params['width'];
+		}
+
+		# Because thumbs are only referred to by width, the height always needs
+		# to be scaled by the width to keep the thumbnail sizes consistent,
+		# even if it was set inside the if block above
+		$params['physicalHeight'] = File::scaleHeight( $srcWidth, $srcHeight,
+			$params['physicalWidth'] );
+		# Set the height if it was not validated in the if block higher up
+		if ( !isset( $params['height'] ) || $params['height'] == -1 ) {
+			$params['height'] = $params['physicalHeight'];
+		}
+
+		if ( !$this->validateThumbParams( $params['physicalWidth'],
+			$params['physicalHeight'], $srcWidth, $srcHeight, $mimeType )
+		) {
+			return false;
+		}
+
+		return true;
+	}
+
+	/**
+	 * Validate thumbnail parameters and fill in the correct height
+	 *
+	 * @param int &$width Specified width (input/output)
+	 * @param int &$height Height (output only)
+	 * @param int $srcWidth Width of the source image
+	 * @param int $srcHeight Height of the source image
+	 * @param string $mimeType Unused
+	 * @return bool False to indicate that an error should be returned to the user.
+	 */
+	private function validateThumbParams( &$width, &$height, $srcWidth, $srcHeight, $mimeType ) {
+		$width = intval( $width );
+
+		# Sanity check $width
+		if ( $width <= 0 ) {
+			wfDebug( __METHOD__ . ": Invalid destination width: $width" );
+			return false;
+		}
+		if ( $srcWidth <= 0 ) {
+			wfDebug( __METHOD__ . ": Invalid source width: $srcWidth" );
+			return false;
+		}
+
+		$height = File::scaleHeight( $srcWidth, $srcHeight, $width );
+		if ( $height == 0 ) {
+			# Force height to be at least 1 pixel
+			$height = 1;
+		}
+
+		return true;
+	}
+
 	/**
 	 * @param array $params
 	 * @return bool|string
