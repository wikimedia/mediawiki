
<?php
/**
 * Body file for extension SectionHide.
 *
 * @file
 * @ingroup Extensions
 */

# hooks class
class SectionHideHooks {
	public static function onParserSectionCreate( $parser, $section, &$sectionContent, $showEditLinks ) {
		if ($showEditLinks && $section > 0) {
			$divstart = '<div class="sectionblocks" id="sectionblock'.$section.'">';
			$divend = '</div id="sectionblock'.$section.'">';
            $sectionContent = preg_replace( '/<\/h[2-6]>/', "$0\n$divstart\n", $sectionContent);
            $sectionContent = $sectionContent."\n$divend\n";
		}
		return true;
	}

	public static function onDoEditSectionLink( $skin, $title, $section, $tooltip, &$result, $lang = false ) {
		if ($section > 0) {
			$hidetext = wfMessage( 'sectionhide-hide' )->plain();
			$showtext = wfMessage( 'sectionhide-show' )->plain();
			$hidelink = '<a href="javascript:;" class="hidelink" onclick=\'toggleSection(this, '.$section.', "'.$showtext.'", "'.$hidetext.'")\'>'
						.$hidetext.'</a>';
			$hidespan = '<span class="mw-editsection"><span class="mw-editsection-bracket">[</span>'. $hidelink .
						'<span class="mw-editsection-bracket">]</span></span>';
			$result = $result . $hidespan;

			if ($section == 1) {
				$hideall = wfMessage( 'sectionhide-hideall' )->plain();
				$showall = wfMessage( 'sectionhide-showall' )->plain();
				$hidelink = '<a href="javascript:;" onclick=\'toggleAllSections(this, "'.$showtext.'", "'.$hidetext.'", "'.$showall.'", "'.$hideall.'")\'>'
							.$hideall.'</a>';
				$hidespan = '<span class="mw-editsection"><span class="mw-editsection-bracket">[</span>'. $hidelink .
							'<span class="mw-editsection-bracket">]</span></span>';
				$result = $result . $hidespan;
			}
		}
		return true;
	}
} # end of SectionHideHooks class


