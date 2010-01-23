<?php

/**
 * Variant of QueryPage which uses a gallery to output results, thus
 * suited for reports generating images
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class ImageQueryPage extends QueryPage {

	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @param $out OutputPage to print to
	 * @param $skin Skin: user skin to use
	 * @param $dbr Database (read) connection to use
	 * @param $res Integer: result pointer
	 * @param $num Integer: number of available result rows
	 * @param $offset Integer: paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		if( $num > 0 ) {
			$gallery = new ImageGallery();
			$gallery->useSkin( $skin );

			# $res might contain the whole 1,000 rows, so we read up to
			# $num [should update this to use a Pager]
			for( $i = 0; $i < $num && $row = $dbr->fetchObject( $res ); $i++ ) {
				$image = $this->prepareImage( $row );
				if( $image ) {
					$gallery->add( $image->getTitle(), $this->getCellHtml( $row ) );
				}
			}

			$out->addHTML( $gallery->toHtml() );
		}
	}

	/**
	 * Prepare an image object given a result row
	 *
	 * @param $row Object: result row
	 * @return Image
	 */
	private function prepareImage( $row ) {
		$namespace = isset( $row->namespace ) ? $row->namespace : NS_FILE;
		$title = Title::makeTitleSafe( $namespace, $row->title );
		return ( $title instanceof Title && $title->getNamespace() == NS_FILE )
			? wfFindFile( $title )
			: null;
	}

	/**
	 * Get additional HTML to be shown in a results' cell
	 *
	 * @param $row Object: result row
	 * @return String
	 */
	protected function getCellHtml( $row ) {
		return '';
	}

}
