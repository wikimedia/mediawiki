<?php
/** Tulu (ತುಳು)
 *
 * @ingroup Language
 * @file
 *
 * @author NamwikiTL
 * @author VinodSBangera
 */

$fallback = 'kn';

$messages = array(
# User preference toggles
'tog-underline'               => 'ಲಿಂಕ್’ಲೆದ ತಿರ್ತ್ ಗೆರೆ(ಅಂಡರ್ ಲೈನ್) ಪಾಡ್’ಲೆ',
'tog-highlightbroken'         => 'ತುಂಡಾತಿನ ಲಿಂಕ್’ಲೆನ್ <a href="" class="new">ಈ ರೀತಿ</a> (ಅತ್ತ್’ನ್ಡ: ಈ ರೀತಿ<a href="" class="internal">?</a>) ಬದಲ್ ಮಲ್ಪುಲೆ.',
'tog-justify'                 => 'ಪಾರಗ್ರಾಫ್’ದ ಕಡೆನ್ ಸರಿ ಮಲ್ಪುಲೆ',
'tog-hideminor'               => 'ಎಲ್ಯೆಲ್ಯ ಬದಲಾವಣೆಲೆನ್ ದೆಂಗಾಲೆ',
'tog-extendwatchlist'         => 'ಅನ್ವಯಿಸುನಂಚಿನ ಪೂರಾ ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾಯೆರೆ ಪಟ್ಟಿನ್(ವಾಚ್ ಲಿಸ್ಟ್) ಬುಡ್ಪಾಲೆ.',
'tog-usenewrc'                => 'ಪರಿಷ್ಕರಿಸ್ ನ ಬದಲಾವಣೆಲು (JavaScript)',
'tog-numberheadings'          => 'ಹೆಡ್ಡಿಂಗ್’ಲೆಗ್ ಸಂಖ್ಯೆಲೆನ್ ತೊಜ್ಪಾಲೆ',
'tog-showtoolbar'             => 'ಸಂಪಾದನೆದ ಉಪಕರಣನ್(ಎಡಿಟ್ ಟೂಲ್ ಬಾರ್) ತೊಜ್ಪಾಲೆ (JavaScript)',
'tog-editondblclick'          => 'ರಡ್ಡ್ ಸರಿ ಕ್ಲಿಕ್ ಮಲ್ತ್’ದ್ ಪುಟೊನು ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ (JavaScript)',
'tog-editsection'             => 'ಪುಟೊತ ಭಾಗಲೊನ್ [ಸಂಪಾದನೆ] ಲಿಂಕ್’ಲೆನ್ ಒತ್ತ್’ದ್ ಬದಲ್ ಮನ್ಪುಲೆಕ ಉಪ್ಪಡ್',
'tog-editsectiononrightclick' => 'ಪುಟೊತ ವಿಭಾಗೊಲೆನ್ ಐತ ಹೆಡ್ಡಿಂಗ್’ನ್ ರೈಟ್ ಕ್ಲಿಕ್ ಮಲ್ತ್’ದ್ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆಕ ಉಪ್ಪಡ್ (JavaScript)',
'tog-showtoc'                 => 'ಪರಿವಿಡಿನ್(ಟೇಬಲ್ ಆಫ್ ಕಂಟೆಂಟ್ಸ್) ತೊಜ್ಪಾಲೆ (ಮೂಜೆರ್ದ್ ಜಾಸ್ತಿ ಹೆಡ್ಡಿಂಗ್ ಉಪ್ಪುನಂಚಿನ ಪುಟೊಲೆಗ್)',
'tog-rememberpassword'        => 'ಈ ಕಂಪ್ಯೂಟರ್’ಡ್ ಎನ್ನ ಲಾಗಿನ್ನ್ ನೆನಪುಡು ದೀಲ',
'tog-editwidth'               => 'ಸಂಪಾದನೆ ಅಂಕಣ ಪೂರ್ತಿ ಅಗೆಲ ಉಪ್ಪಡ್',
'tog-watchcreations'          => 'ಯಾನ್ ಶುರು ಮಲ್ತಿನ ಪುಟೊಲೆನ್ ಯೆನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾಲೆ',
'tog-watchdefault'            => 'ಯಾನ್ ಸಂಪಾದನೆ ಮನ್ಪುನಂಚಿನ ಪುಟೊಲೆನ್ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾಲೆ',
'tog-watchmoves'              => 'ಯಾನ್ ಮೂವ್ ಮಲ್ತಿನಂಚಿನ ಪುಟೊಲೆನ್ ಎನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾಲೆ',
'tog-watchdeletion'           => 'ಯಾನ್ ಓಚ್ಚಿನ(ಡಿಲೀಟ್ ಮಲ್ತಿನ) ಪುಟೊಲೆನ್ ಎನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾಲೆ',
'tog-minordefault'            => 'ಪೂರಾ ಸಂಪಾದನೆನ್ಲಾ ಎಲ್ಯ ಪಂಡ್’ದ್ ಗುರ್ತ ಮಲ್ಪುಲೆ',
'tog-previewontop'            => 'ಮುನ್ನೋಟನ್ ಸಂಪಾದನೆ ಅಂಕಣದ ಮಿತ್ತ್ ತೊಜ್ಪಾಲೆ',
'tog-previewonfirst'          => 'ಶುರುತ ಬದಲಾವಣೆದ ಬೊಕ್ಕ ಮನ್ನೋಟನ್ ತೊಜ್ಪಾಲೆ',
'tog-nocache'                 => 'ಪುಟೊತ caching ನ್ ಉಂತಾಲೆ',
'tog-enotifwatchlistpages'    => 'ಎನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಡ್ ಉಪ್ಪುನಂಚಿನ ಒವಾಂಡಲ ಪುಟ ಬದಲಾಂಡ ಎಂಕ್ ಇ-ಮೇಲ್ ಮಲ್ಪುಲೆ',
'tog-enotifusertalkpages'     => 'ಎನ್ನ ಚರ್ಚೆ ಪುಟ ಬದಲಾಂಡ ಎಂಕ್ ಇ-ಮೇಲ್ ಕಡಪುಡ್ಲೆ',
'tog-enotifminoredits'        => 'ಎಲ್ಯೆಲ್ಯ ಬದಲಾವಣೆ ಆಂಡಲ ಎಂಕ್ ಇ-ಮೇಲ್ ಮಲ್ಪುಲೆ',
'tog-enotifrevealaddr'        => 'ಪ್ರಕಟಣೆ ಇ-ಮೇಲ್’ಡ್ ಎನ್ನ ಇ-ಮೇಲ್ ವಿಳಾಸನ್ ತೊಜ್ಪಾಲೆ',
'tog-shownumberswatching'     => 'ಪುಟೊನು ತೂವೊಂದುಪ್ಪುನಂಚಿನ ಸದಸ್ಯೆರ್’ನ ಸಂಖ್ಯೆನ್ ತೊಜ್ಪಾಲೆ',
'tog-fancysig'                => 'ಸರಳ ಸಹಿಗಳು (ಲಿಂಕ್ ಇಜ್ಜಂದಿಲೆಕ)',
'tog-externaleditor'          => 'ಯಾಪಲ ಪಿದಯಿದ ಸಂಪಾದನೆ ಉಪಕರಣದ ಉಪಯೋಗ ಮನ್ಪುಲೆ (ಅನುಭವ ಉಪ್ಪುನಂಚಿನ ಸದಸ್ಯೆರೆಗ್ ಮಾತ್ರ, ನಿಕ್ಲೆನ ಕಂಪ್ಯೂಟರ್’ಡ್ ವಿಶೇಷವಾಯಿನ ಬದಲಾವಣೆಲು ಬೋಡಾಪುಂಡು)',
'tog-externaldiff'            => 'ಬಾಹ್ಯ ಮುನ್ನೋಟನ್ ಯಾಪಲ ಉಪಯೋಗ ಮಲ್ಪುಲೆ (ಅನುಭವ ಉಪ್ಪುನಂಚಿನ ಸದಸ್ಯೆರೆಗ್ ಮಾತ್ರ, ನಿಕ್ಲೆನ ಕಂಪ್ಯೂಟರ್’ಡ್ ವಿಶೇಷ ಬದಲಾವಣೆಲು ಬೋಡಾಪುಂಡು)',
'tog-uselivepreview'          => 'ನೇರ ಮುನ್ನೋಟನ್ ಉಪಯೋಗ ಮಲ್ಪುಲೆ (JavaScript) (ಪ್ರಾಯೋಗಿಕ)',
'tog-forceeditsummary'        => 'ಸಂಪಾದನೆ ಸಾರಾಂಶೊನು ಖಾಲಿ ಬುಡ್’ನ್ಡ್ ಎಂಕ್ ನೆನಪು ಮಲ್ಪುಲೆ',
'tog-watchlisthideown'        => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಡ್ ಎನ್ನ ಸಂಪಾದನೆಲೆನ್ ತೊಜ್’ಪಾವೊಚಿ',
'tog-watchlisthidebots'       => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಡ್ ಬಾಟ್ ಸಂಪಾದನೆಲೆನ್ ದೆಂಗಾಲೆ',
'tog-watchlisthideminor'      => 'ಎಲ್ಯ ಬದಲಾವಣೆಲೆನ್ ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆಂಗಾಲೆ',
'tog-watchlisthideliu'        => 'ಲಾಗಿನ್ ಆತಿನಂಚಿನ ಸದಸ್ಯೆರ್’ನ ಸಂಪಾದನೆಲೆನ್ ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆಂಗಾಲೆ',
'tog-watchlisthideanons'      => 'ಪುದರಿಜ್ಜಂದಿನ ಬಳಕೆದಾರನ ಸಂಪಾದನೆಲೆನ್ ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆಂಗಾಲೆ',
'tog-ccmeonemails'            => 'ಯಾನ್ ಬೇತೆ ಸದಸ್ಯೆರೆಗ್ ಕಡಪುಡ್ಪುನಂಚಿನ ಇ-ಮೇಲ್’ಲೆದ ಪ್ರತಿಲೆನ್(copy) ಎಂಕ್ ಕಡಪುಡ್ಲೆ',
'tog-diffonly'                => 'ವ್ಯತ್ಯಾಸದ ತಿರ್ತುಪ್ಪುನಂಚಿನ ಪುಟೊತ ವಿವರೊಲೆನ್ ತೊಜ್’ಪಾವೊಚಿ',
'tog-showhiddencats'          => 'ದೆಂಗಾದಿನ ವರ್ಗೊಲೆನ್ ತೊಜ್ಪಾಲೆ',

'underline-always'  => 'ಯಾಪಲ',
'underline-never'   => 'ಯಾಪಗ್ಲಾ ಇಜ್ಜಿ',
'underline-default' => 'ಬ್ರೌಸರ್’ದ ಯಥಾಸ್ಥಿತಿ',

# Dates
'sunday'        => 'ಐತಾರ',
'monday'        => 'ಸೋಮವಾರ',
'tuesday'       => 'ಅಂಗರೆ',
'wednesday'     => 'ಬುಧವಾರ',
'thursday'      => 'ಗುರುವಾರ',
'friday'        => 'ಶುಕ್ರವಾರ',
'saturday'      => 'ಶನಿವಾರ',
'sun'           => 'ರವಿ',
'mon'           => 'ಸೋಮ',
'tue'           => 'ಮಂಗಳ',
'wed'           => 'ಬುಧ',
'thu'           => 'ಗುರು',
'fri'           => 'ಶುಕ್ರ',
'sat'           => 'ಶನಿ',
'january'       => 'ಜನವರಿ',
'february'      => 'ಫೆಬ್ರವರಿ',
'march'         => 'ಮಾರ್ಚ್',
'april'         => 'ಏಪ್ರಿಲ್',
'may_long'      => 'ಮೇ',
'june'          => 'ಜೂನ್',
'july'          => 'ಜುಲೈ',
'august'        => 'ಆಗೋಸ್ಟ್',
'september'     => 'ಸೆಪ್ಟಂಬರ್',
'october'       => 'ಅಕ್ಟೋಬರ್',
'november'      => 'ನವಂಬರ್',
'december'      => 'ಡಿಸಂಬರ್',
'january-gen'   => 'ಜನವರಿ',
'february-gen'  => 'ಫ್ರೆಬ್ರವರಿ',
'march-gen'     => 'ಮಾರ್ಚ್',
'april-gen'     => 'ಏಪ್ರಿಲ್',
'may-gen'       => 'ಮೇ',
'june-gen'      => 'ಜೂನ್',
'july-gen'      => 'ಜುಲೈ',
'august-gen'    => 'ಆಗೋಸ್ಟ್',
'september-gen' => 'ಸಪ್ಟಂಬರ್',
'october-gen'   => 'ಅಕ್ಟೋಬರ್',
'november-gen'  => 'ನವಂಬರ್',
'december-gen'  => 'ಡಿಸೆಂಬರ್',
'jan'           => 'ಜನವರಿ',
'feb'           => 'ಫೆಬ್ರವರಿ',
'mar'           => 'ಮಾರ್ಚ್',
'apr'           => 'ಏಪ್ರಿಲ್',
'may'           => 'ಮೇ',
'jun'           => 'ಜೂನ್',
'jul'           => 'ಜುಲೈ',
'aug'           => 'ಆಗೋಸ್ಟ್',
'sep'           => 'ಸಪ್ಟಂಬರ್',
'oct'           => 'ಅಕ್ಟೋಬರ್',
'nov'           => 'ನವಂಬರ್',
'dec'           => 'ಡಿಸೆಂಬರ್',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|ವರ್ಗ|ವರ್ಗೊಲು}}',
'category_header'                => '"$1" ವರ್ಗಡುಪ್ಪುನಂಚಿನ ಲೇಖನೊಲು',
'subcategories'                  => 'ಉಪವರ್ಗೊಲು',
'category-media-header'          => '"$1" ವರ್ಗಡುಪ್ಪುನಂಚಿನ ಚಿತ್ರ/ಶಬ್ಧ ಫೈಲ್’ಲು',
'category-empty'                 => "''ಈ ವರ್ಗೊಡು ಸದ್ಯಗ್ ಓವುಲ ಪುಟೊಲಾವಡ್ ಅತ್ತ್’ನ್ಡ ಚಿತ್ರೊಲಾವಡ್ ಇಜ್ಜಿ.''",
'hidden-categories'              => '{{PLURAL:$1|ದೆಂಗಾದ್ ದೀತಿನ ವರ್ಗ|ದೆಂಗಾದ್ ದೀತಿನ ವರ್ಗೊಲು}}',
'hidden-category-category'       => 'ದೆಂಗಾದ್ ದೀತಿನ ವರ್ಗೊಲು', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಉಪವರ್ಗ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಉಪವರ್ಗೊನು|$1 ಉಪವರ್ಗೊಲೆನ್}} ಸೇರಾದ್, ಒಟ್ಟಿಗೆ $2 ಉಂಡು.}}',
'category-subcat-count-limited'  => 'ಈ ವರ್ಗೊಡು ತಿರ್ತ್ ತೊಜ್ಪಾದಿನ {{PLURAL:$1|ಉಪವರ್ಗ|$1 ಉಪವರ್ಗೊಲು}} ಉಂಡು.',
'category-article-count'         => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಖಾಲಿ ಒಂಜಿ ಪುಟ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಪುಟೊನು|$1 ಪುಟೊಲೆನ್}} ಸೇರ್ಪಾದ್, ಒಟ್ಟಿಗೆ $2 ಪುಟೊಲು ಉಂಡು.}}',
'category-article-count-limited' => 'ಪ್ರಸಕ್ತ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಪುಟ ಉಂಡು|$1 ಪುಟೊಲು ಉಂಡು}}.',
'category-file-count'            => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಖಾಲಿ ಒಂಜಿ ಫೈಲ್ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಫೈಲ್’ನ್|$1 ಫೈಲ್’ಲೆನ್}} ಸೇರ್ಪಾದ್, ಒಟ್ಟಿಗೆ $2 ಉಂಡು.}}',
'category-file-count-limited'    => 'ಪ್ರಸಕ್ತ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಫೈಲ್ ಉಂಡು|$1 ಫೈಲ್’ಲು ಉಂಡು}}.',
'listingcontinuesabbrev'         => 'ಮುಂದು.',

'mainpagetext'      => "<big>'''ಮೀಡಿಯವಿಕಿ ಯಶಸ್ವಿಯಾದ್ ಇನ್’ಸ್ಟಾಲ್ ಆಂಡ್.'''</big>",
'mainpagedocfooter' => 'ವಿಕಿ ತಂತ್ರಾಂಶನ್ ಉಪಗೋಗ ಮನ್ಪುನ ಬಗ್ಗೆ ಮಾಹಿತಿಗ್ [http://meta.wikimedia.org/wiki/Help:Contents ಸದಸ್ಯೆರ್ನ ನಿರ್ದೇಶನ ಪುಟ] ತೂಲೆ.

== ಎಂಚ ಶುರು ಮಲ್ಪುನಿ ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ ಮೀಡಿಯವಿಕಿ FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'          => 'ಎಂಕ್ಲೆನ ಬಗ್ಗೆ',
'article'        => 'ಲೇಖನ ಪುಟ',
'newwindow'      => '(ಪೊಸ ಕಂಡಿನ್ ಓಪನ್ ಮಲ್ಪುಂಡು)',
'cancel'         => 'ವಜಾ ಮನ್ಪುಲೆ',
'qbfind'         => 'ನಾಡ್’ಲೆ',
'qbbrowse'       => 'ಬ್ರೌಸ್',
'qbedit'         => 'ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'qbpageoptions'  => 'ಈ ಪುಟ',
'qbpageinfo'     => 'ಸನ್ನಿವೇಶ',
'qbmyoptions'    => 'ಎನ್ನ ಪುಟೊಲು',
'qbspecialpages' => 'ವಿಶೇಷ ಪುಟೊಲು',
'moredotdotdot'  => 'ನನಲ...',
'mypage'         => 'ಎನ್ನ ಪುಟ',
'mytalk'         => 'ಎನ್ನ ಚರ್ಚೆ',
'anontalk'       => 'ಈ ಐ.ಪಿ ಗ್ ಪಾತೆರ್’ಲೆ',
'navigation'     => 'ಸಂಚಾರ',
'and'            => 'ಬೊಕ್ಕ',

# Metadata in edit box
'metadata_help' => 'ಮೂಲಮಾಹಿತಿ:',

'errorpagetitle'    => 'ದೋಷ',
'returnto'          => '$1 ಗ್ ಪಿರ ಪೋಲೆ.',
'tagline'           => '{{SITENAME}} ರ್ದ್',
'help'              => 'ಸಹಾಯ',
'search'            => 'ನಾಡ್',
'searchbutton'      => 'ನಾಡ್',
'go'                => 'ಪೋ',
'searcharticle'     => 'ಪೋ',
'history'           => 'ಪುಟೊತ ಚರಿತ್ರೆ',
'history_short'     => 'ಇತಿಹಾಸ',
'updatedmarker'     => 'ಎನ್ನ ಅಕೇರಿದ ವೀಕ್ಷಣೆ ಡ್ದ್ ಬುಕ್ಕ ಆಯಿನ ಬದಲಾವಣೆಲು',
'info_short'        => 'ಮಾಹಿತಿ',
'printableversion'  => 'ಪ್ರಿಂಟ್ ಆವೃತ್ತಿ',
'permalink'         => 'ಸ್ಥಿರ ಸಂಪರ್ಕ',
'print'             => 'ಪ್ರಿ೦ಟ್ ಮನ್ಪುಲೆ',
'edit'              => 'ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ(Edit this page)',
'create'            => 'ಸೃಷ್ಟಿಸಾಲೆ',
'editthispage'      => 'ಈ ಪುಟೊನು ಬದಲಾಯಿಸಾಲೆ',
'create-this-page'  => 'ಈ ಪುಟೊನು ಸೃಷ್ಟಿಸಾಲೆ',
'delete'            => 'ದೆತ್ತ್ ಪಾಡ್ಲೆ',
'deletethispage'    => 'ಈ ಪುಟೊನು ದೆತ್ತ್ ಪಾಡ್ಲೆ',
'undelete_short'    => 'ಪಿರ ಪಾಡ್ಲೆ {{PLURAL:$1|ಒ೦ಜಿ ಬದಲಾವಣೆ|$1 ಬದಲಾವಣೆಲು}}',
'protect'           => 'ಸ೦ರಕ್ಷಿಸಾಲೆ',
'protect_change'    => 'ಸ೦ರಕ್ಷಿಣೆನ್ ಬದಲಾಯಿಸಾಲೆ',
'protectthispage'   => 'ಈ ಪುಟೊನು ಸ೦ರಕ್ಷಿಸಾಲೆ',
'unprotect'         => 'ಸ೦ರಕ್ಷಣೆ ದೆಪ್ಲೆ',
'unprotectthispage' => 'ಈ ಪುಟೊತ ಸ೦ರಕ್ಷಣೆನ್ ದೆಪ್ಲೆ',
'newpage'           => 'ಪೊಸ ಪುಟೊ',
'talkpage'          => 'ಪುಟದ ಬಗ್ಗೆ ಚರ್ಚೆ ಮನ್ಪುಲೆ',
'talkpagelinktext'  => 'ಪಾತೆರ',
'specialpage'       => 'ವಿಶೇಷ ಪುಟ',
'personaltools'     => 'ವೈಯಕ್ತಿಕ ಉಪಕರಣಲು',
'postcomment'       => 'ಕಮೆಂಟ್ ಬರೆಲೆ',
'articlepage'       => 'ಲೇಖನ ಪುಟೊನು ತೂಲೆ',
'talk'              => 'ಚರ್ಚೆ',
'views'             => 'ನೋಟಲು',
'toolbox'           => 'ಉಪಕರಣ(ಟೂಲ್)',
'userpage'          => 'ಸದಸ್ಯೆರ್ನ ಪುಟೊನು ತೂಲೆ',
'projectpage'       => 'ಪ್ರೊಜೆಕ್ಟ್ ಪುಟೊನು ತೂಲೆ',
'imagepage'         => 'ಮೀಡಿಯ ಪುಟೊನು ತೂಲೆ',
'mediawikipage'     => 'ಸಂದೇಶ ಪುಟೊನು ತೂಲೆ',
'templatepage'      => 'ಟೆಂಪ್ಲೇಟ್ ಪುಟೊನು ತೂಲೆ',
'viewhelppage'      => 'ಸಹಾಯ ಪುಟೊನು ತೂಲೆ',
'categorypage'      => 'ವರ್ಗ ಪುಟೊನು ತೂಲೆ',
'viewtalkpage'      => 'ಚರ್ಚೆನ್ ತೂಲೆ',
'otherlanguages'    => 'ಬೇತೆ ಭಾಷೆಲೆಡ್',
'redirectedfrom'    => '($1 ರ್ದ್ ಪುನರ್ನಿರ್ದೇಶಿತ)',
'redirectpagesub'   => 'ಪುನರ್ನಿರ್ದೇಶನ ಪುಟ',
'lastmodifiedat'    => 'ಈ ಪುಟ ಇಂದೆತ ದುಂಬು $2, $1 ಕ್ ಬದಲಾತ್’ನ್ಡ್.', # $1 date, $2 time
'viewcount'         => 'ಈ ಪುಟೊನು {{PLURAL:$1|1 ಸರಿ|$1 ಸರಿ}} ತೂತೆರ್.',
'protectedpage'     => 'ಸಂರಕ್ಷಿತ ಪುಟ',
'jumpto'            => 'ಇಡೆ ಪೋಲೆ:',
'jumptonavigation'  => 'ಸಂಚಾರ',
'jumptosearch'      => 'ನಾಡ್’ಲೆ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ದ ಬಗ್ಗೆ',
'aboutpage'            => 'Project:ನಮ್ಮ ಬಗ್ಗೆ',
'bugreports'           => 'ದೋಷ ವರದಿಲು',
'bugreportspage'       => 'Project:ದೋಷ ವರದಿಲು',
'copyright'            => 'ಉಂದು ಈ ಕಾಪಿರೈಟ್‌ಡ್ ಲಭ್ಯವುಂಡು $1.',
'copyrightpagename'    => '{{SITENAME}} ಕಾಪಿರೈಟ್',
'copyrightpage'        => '{{ns:project}}:ಕೃತಿಸ್ವಾಮ್ಯತೆಲು',
'currentevents'        => 'ಇತ್ತೆದ ಸಂಗತಿಲು',
'currentevents-url'    => 'Project:ಇತ್ತೆದ ಸಂಗತಿಲು',
'disclaimers'          => 'ಅಬಾಧ್ಯತೆಲು',
'disclaimerpage'       => 'Project:ಸಾಮಾನ್ಯ ಅಬಾಧ್ಯತೆಲು',
'edithelp'             => 'ಸಂಪಾದನೆ(ಎಡಿಟ್) ಮಲ್ಪೆರೆ ಸಹಾಯ',
'edithelppage'         => 'Help:ಸಂಪಾದನೆ',
'faq'                  => 'ಸಾಮಾನ್ಯವಾದ್ ಕೇನುನ ಪ್ರಶ್ನೆಲು',
'faqpage'              => 'Project:ಸಾಮಾನ್ಯವಾದ್ ಕೇನುನ ಪ್ರಶ್ನೆಲು',
'helppage'             => 'Help:ಪರಿವಿಡಿ',
'mainpage'             => 'ಮುಖ್ಯ ಪುಟ',
'mainpage-description' => 'ಮುಖ್ಯ ಪುಟ',
'policy-url'           => 'Project:ನಿಯಮಾವಳಿ',
'portal'               => 'ಸಮುದಾಯ ಪುಟ',
'portal-url'           => 'Project:ಸಮುದಾಯ ಪುಟ',
'privacy'              => 'ಖಾಸಗಿ ನಿಯಮಾವಳಿ',
'privacypage'          => 'Project:ಖಾಸಗಿಮಾಹಿತಿ ನಿಯಮ',

'badaccess'        => 'ಅನುಮತಿ ದೋಷ',
'badaccess-group0' => 'ಈರ್ ಕೇನಿನ ಬೇಲೆನ್ ಮಲ್ಪೆರೆ ಇರೆಗ್ ಅನುಮತಿ ಇಜ್ಜಿ.',
'badaccess-groups' => 'ಈರ್ ಕೇನಿನಂಚಿನ ಕ್ರಿಯೆ ಖಾಲಿ $1 ಗುಂಪುಲೆಡ್ ಒಂಜೆಕ್ ಸೇರ್ದುಪ್ಪುನ ಬಳಕೆದಾರೆರೆಗ್ ಮಾತ್ರ.',

'versionrequired'     => 'ಮೀಡಿಯವಿಕಿಯದ $1 ನೇ ಅವೃತ್ತಿ ಬೋಡು',
'versionrequiredtext' => 'ಈ ಪುಟೊನು ತೂಯೆರೆ ಮೀಡಿಯವಿಕಿಯದ $1 ನೇ ಆವೃತ್ತಿ ಬೋಡು. 
[[Special:Version|ಆವೃತ್ತಿ]] ಪುಟನು ತೂಲೆ.',

'ok'                      => 'ಸರಿ',
'retrievedfrom'           => '"$1" ರ್ದ್ ದೆತ್ತಿನಂಚಿನ',
'youhavenewmessages'      => 'ಇರೆಗ್ $1 ಉಂಡು ($2).',
'newmessageslink'         => 'ಪೊಸ ಸಂದೇಶಲು',
'newmessagesdifflink'     => 'ಕಡೆತ ಬದಲಾವಣೆ',
'youhavenewmessagesmulti' => '$1 ಡ್ ಇರೆಗ್ ಪೊಸ ಸಂದೇಶೊಲು ಉಂಡು',
'editsection'             => 'ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'editsection-brackets'    => '[$1]',
'editold'                 => 'ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'viewsourceold'           => 'ಮೂಲೊನು ತೂಲೆ',
'editsectionhint'         => '$1 ವಿಭಾಗದ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'toc'                     => 'ಪರಿವಿಡಿ',
'showtoc'                 => 'ತೊಜ್ಪಾವು',
'hidetoc'                 => 'ದೆಂಗಾವು',
'thisisdeleted'           => '$1 ನ್ ತೂವೊಡೆ ಅತ್ತ್ ದುಂಬುದ ಲೆಕೆ ಮಲ್ಪೊಡೆ?',
'viewdeleted'             => '$1 ನ್ ತೂವೊಡೆ?',
'restorelink'             => '{{PLURAL:$1|1 ಡಿಲೀಟ್ ಆತಿನ ಸಂಪಾದನೆ|$1 ಡಿಲೀಟ್ ಆತಿನ ಸಂಪಾದನೆಲು}}',
'feedlinks'               => 'ಫೀಡ್:',
'feed-invalid'            => 'ಇನ್ವಾಲಿಡ್ ಸಬ್ಸ್’ಕ್ರಿಪ್ಶನ್ ಫೀಡ್ ಟೈಪ್.',
'feed-unavailable'        => '{{SITENAME}} ಡ್ ಸಿಂಡಿಕೇಶನ್ ಫೀಡ್ ಲಭ್ಯವಿಜ್ಜಿ',
'site-rss-feed'           => '$1 RSS ಫೀಡ್',
'site-atom-feed'          => '$1 ಆಟಮ್ ಫೀಡ್',
'page-rss-feed'           => '"$1" RSS ಫೀಡ್',
'page-atom-feed'          => '"$1" ಪುಟೊತ Atom ಫೀಡ್',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (ಈ ಪುಟ ನನಲ ಅಸ್ತಿತ್ವಡ್ ಇಜ್ಜಿ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ಪುಟ',
'nstab-user'      => 'ಸದಸ್ಯೆರ್ನ ಪುಟ',
'nstab-media'     => 'ಮೀಡಿಯ ಪುಟ',
'nstab-special'   => 'ವಿಶೇಷ',
'nstab-project'   => 'ಪ್ರೊಜೆಕ್ಟ್ ಪುಟ',
'nstab-image'     => 'ಫೈಲ್',
'nstab-mediawiki' => 'ಸಂದೇಶ',
'nstab-template'  => 'ಫಲಕ',
'nstab-help'      => 'ಸಹಾಯ ಪುಟ',
'nstab-category'  => 'ವರ್ಗ',

# Main script and global functions
'nosuchaction'      => 'ಈ ರೀತಿದ ಓವು ಕ್ರಿಯೆಲಾ(ಆಕ್ಶನ್) ಇಜ್ಜಿ',
'nosuchactiontext'  => 'ಈ URLದ ಒಟ್ಟಿಗೆ ಉಪ್ಪುನ ಕ್ರಿಯೆನ್ ವಿಕಿ ಗುರ್ತ ಪತ್ತುಜಿ',
'nosuchspecialpage' => 'ಈ ಪುದರ್’ದ ಒವುಲಾ ವಿಷೇಶ ಪುಟ ಇಜ್ಜಿ',
'nospecialpagetext' => "<big>'''ಈರ್ ಅಸ್ಥಿತ್ವಡ್ ಇಜ್ಜಂದಿನ ವಿಷೇಶ ಪುಟೊನು ಕೇನ್ದರ್.'''</big>

ಅಸ್ಥಿತ್ವಡ್ ಉಪ್ಪುನಂಚಿನ ವಿಷೇಶ ಪುಟೊಲ್ದ ಪಟ್ಟಿ [[Special:SpecialPages|{{int:specialpages}}]] ಡ್ ಉಂಡು.",

# General errors
'error'                => 'ದೋಷ',
'databaseerror'        => 'ಡೇಟಾಬೇಸ್ ದೋಷ',
'noconnect'            => 'ವಿಕಿ ಡ್ ಕೆಲವು ತಾ೦ತ್ರಿಕ ದೋಶೊಲು ತೋಜೊ೦ದು೦ಡು ಬುಕ ಡಾಟಾಬೇಸ್ ಸರ್ವರ್ ನ್ ಸ೦ಪರ್ಕ ಮಲ್ಪೆರ್ ಆವೊ೦ದಿಜ್ಜಿ.<br />
$1',
'nodb'                 => 'ಡಾಟಾಬೇಸ್ $1 ನ್ ಆಯ್ಕೆ ಮಲ್ತೊನೆರೆ ಆಯಿಜಿ',
'internalerror'        => 'ಆ೦ತರಿಕ ದೋಷ',
'internalerror_info'   => 'ಆಂತರಿಕ ದೋಷ: $1',
'filecopyerror'        => 'ಫೈಲ್ "$1" ನ್ "$2" ಗ್ ನಕಲ್ ಮಲ್ಪೆರೆ ಆಯಿಜಿ',
'filerenameerror'      => '"$1" ಫೈಲ್ ನ್ "$2"ಗ್ ಪುನರ್ನಾಮಕರಣ ಮಲ್ಪೆರೆ ಆಯಿಜಿ.',
'filedeleteerror'      => '"$1" ಫೈಲ್ ನ್ ದೆತ್ತ್ ಪಾಡೆರೆ ಆವೊ೦ದಿಜ್ಜಿ.',
'directorycreateerror' => '"$1" ಡೈರೆಕ್ಟರಿನ್ ಉ೦ಡು ಮಲ್ಪೆರೆ ಆವೊ೦ದಿಜ್ಜಿ.',
'filenotfound'         => '"$1" ಫೈಲ್ ನ್ ನಾಡಿಯೆರೆ ಆಯಿಜಿ.',
'fileexistserror'      => '"$1" ಫೈಲ್ ಗ್ ಬರೆಯೆರೆ ಆವೊ೦ದಿಜ್ಜಿ: ಈ ಫೈಲ್ ದು೦ಬೇ ಉ೦ಡು.',
'unexpected'           => 'ಅನಿರೀಕ್ಷಿತ ಮೌಲ್ಯ: "$1"="$2".',
'formerror'            => 'ದೋಷ: ಅರ್ಜಿನ್ ಕಡಪುಡಿಯೆರ್ ಆಯಿಜಿ',
'viewsource'           => 'ಮೂಲ ಬರಹೊನು ತೂಲೆ',

# Login and logout pages
'nav-login-createaccount' => 'ಲಾಗ್-ಇನ್ / ಅಕೌಂಟ್ ಸೃಷ್ಟಿ ಮಲ್ಪುಲೆ',
'userlogin'               => 'ಲಾಗ್-ಇನ್ / ಅಕೌಂಟ್ ಸೃಷ್ಟಿ ಮಲ್ಪುಲೆ',
'logout'                  => 'ಲಾಗ್ ಔಟ್',
'userlogout'              => 'ಲಾಗ್ ಔಟ್',

# Edit page toolbar
'bold_sample'     => 'ದಪ್ಪ ಅಕ್ಷರ',
'bold_tip'        => 'ದಪ್ಪ ಅಕ್ಷರೊಲು',
'italic_sample'   => 'ಓರೆ ಅಕ್ಷರೊಲು',
'italic_tip'      => 'ಓರೆ ಅಕ್ಷರೊಲು',
'link_sample'     => 'ಲಿಂಕ್’ದ ಪುದರ್',
'link_tip'        => 'ಉಲಯಿದ ಲಿಂಕ್',
'extlink_sample'  => 'http://www.example.com ಲಿಂಕ್’ದ ಪುದರ್',
'extlink_tip'     => 'ಪಿದಯಿದ ಲಿಂಕ್ (http:// ರ್ದ್ ಶುರು ಮಲ್ಪೆರೆ ಮರಪೊಚಿ)',
'headline_sample' => 'ಹೆಡ್-ಲೈನ್’ದ ಬರಹ',
'headline_tip'    => '2ನೇ ಲೆವೆಲ್ದ ಹೆಡ್-ಲೈನ್',
'math_sample'     => 'ಮುಲ್ಪ ಸೂತ್ರೊನು ಪಾಡ್’ಲೆ',
'math_tip'        => 'ಗಣಿತ ಸೂತ್ರ (LaTeX)',
'nowiki_sample'   => 'ಈ ಜಾಗೆಡ್ ಬರೆತಿನಂಚಿನ ಬರಹ ವಿಕೀಕರಣ ಆಪುಜಿ',
'nowiki_tip'      => 'ವಿಕಿ ರಚನಾಕ್ರಮೊನು(ಫೋರ್ಮ್ಯಾಟ್) ಅಳವಡಿಸೊಚಿ',
'image_tip'       => 'ಎ೦ಬೆಡ್ ಮಲ್ತಿನ ಫೈಲ್',
'media_tip'       => 'ಫೈಲ್ ದ ಲಿ೦ಕ್',
'sig_tip'         => 'ಸಮಯಮುದ್ರೆದೊಟ್ಟಿಗೆ ಇರ್ನ ಸಹಿ',
'hr_tip'          => 'ಅಡ್ಡ ಗೆರೆ(ಆಯಿನಾತ್ ಕಮ್ಮಿ ಉಪಯೋಗಿಸಾಲೆ)',

# Edit pages
'summary'              => 'ಸಾರಾಂಶ',
'subject'              => 'ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ',
'minoredit'            => 'ಉಂದು ಎಲ್ಯ ಬದಲಾವಣೆ',
'watchthis'            => 'ಈ ಪುಟೊನು ತೂಲೆ',
'savearticle'          => 'ಪುಟೊನು ಒರಿಪಾಲೆ',
'preview'              => 'ಮುನ್ನೋಟ',
'showpreview'          => 'ಮುನ್ನೋಟ ತೊಜ್ಪಾವ್',
'showlivepreview'      => 'ಪ್ರತ್ಯಕ್ಷ ಮುನ್ನೋಟ',
'showdiff'             => 'ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವ್',
'anoneditwarning'      => "'''ಜಾಗ್ರತೆ:''' ಈರ್ ಇತ್ತೆ ಲಾಗ್ ಇನ್ ಆತಿಜರ್.
ಈರ್ನ ಐ.ಪಿ ಎಡ್ರೆಸ್ ಈ ಪುಟೊತ ಬದಲಾವಣೆ ಇತಿಹಾಸೊಡು ದಾಖಲಾಪು೦ಡು.",
'missingsummary'       => "'''ಗಮನಿಸಾಲೆ:''' ಈರ್ ಬದಲಾವಣೆದ ಸಾರಾ೦ಶನ್ ಕೊರ್ತಿಜರ್.
ಈರ್ ಪಿರ 'ಒರಿಪಾಲೆ' ಬಟನ್ ನ್ ಒತ್ತ್೦ಡ ಸಾರಾ೦ಶ ಇಜ್ಜ೦ದೆನೇ ಈರ್ನ ಬದಲಾವಣೆ ದಾಖಲಾಪು೦ಡು.",
'missingcommenttext'   => 'ದಯ ಮಲ್ತ್ ದ ಈರ್ನ ಅಭಿಪ್ರಾಯನ್ ತಿರ್ತ್ ಕೊರ್ಲೆ',
'missingcommentheader' => "'''ಗಮನಿಸಾಲೆ:''' ಈರ್ ಈ ಅಭಿಪ್ರಾಯಗ್ \"ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ\" ದಾಲ ಕೊರ್ತಿಜರ್. ಈರ್ ಪಿರ ’ಒರಿಪಾಲೆ’ ಬಟನ್ ನ್ ಒತ್ತ್೦ಡ ಈರ್ನ ಬದಲಾವಣೆ ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ ಇಜ್ಜ೦ದನೇ ಒರಿಪ್ಪಾವು೦ಡು.",
'summary-preview'      => 'ಸಾರಾ೦ಶ ಮುನ್ನೋಟ',
'subject-preview'      => 'ವಿಷಯ/ಮುಖ್ಯಾ೦ಶದ ಮುನ್ನೋಟ',
'blockedtitle'         => 'ಈ ಸದಸ್ಯೆರೆನ್ ತಡೆ ಮಲ್ತ್ ದ್೦ಡ್.',
'newarticletext'       => "ನನಲ ಅಸ್ಥಿತ್ವಡ್ ಉಪ್ಪಂದಿನ ಪುಟೊಗು ಈರ್ ಬೈದರ್.
ಈ ಪುಟೊನು ಸೃಷ್ಟಿ ಮಲ್ಪೆರೆ ತಿರ್ತ್’ದ ಚೌಕೊಡು ಬರೆಯೆರೆ ಸುರು ಮಲ್ಪುಲೆ.
(ಜಾಸ್ತಿ ಮಾಹಿತಿಗ್ [[{{MediaWiki:Helppage}}|ಸಹಾಯ ಪುಟೊನು]] ತೂಲೆ).
ಈ ಪುಟೊಕು ಈರ್ ತಪ್ಪಾದ್ ಬತ್ತಿತ್ತ್’ನ್ಡ ಇರೆನ ಬ್ರೌಸರ್’ದ '''back''' ಬಟನ್’ನ್ ಒತ್ತ್’ಲೆ.",
'noarticletext'        => 'ಈ ಪುಟೊಟು ಸದ್ಯಗ್ ಓ ಬರಹಲಾ ಇಜ್ಜಿ, ಈರ್ ಬೇತೆ ಪೂಟೊಲೆಡ್ [[Special:Search/{{PAGENAME}}|ಈ ಲೇಖನೊನು ನಾಡೊಲಿ]] ಅತ್ತ್’ನ್ಡ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ಈ ಪುಟೊನು ಸಂಪಾದನೆ ಮಲ್ಪೊಲಿ].',
'editing'              => '$1 ಲೇಖನೊನು ಈರ್ ಸಂಪಾದನೆ ಮಲ್ತೊಂದುಲ್ಲರ್',
'copyrightwarning'     => 'ದಯಮಲ್ತ್’ದ್ ಗಮನಿಸ್’ಲೆ: {{SITENAME}} ಸೈಟ್’ಡ್ ಇರೆನ ಪೂರಾ ಕಾಣಿಕೆಲುಲಾ $2 ಅಡಿಟ್ ಬಿಡುಗಡೆ ಆಪುಂಡು (ಮಾಹಿತಿಗ್ $1 ನ್ ತೂಲೆ). ಇರೆನ ಸಂಪಾದನೆಲೆನ್ ಬೇತೆಕುಲು ನಿರ್ಧಾಕ್ಷಿಣ್ಯವಾದ್ ಬದಲ್ ಮಲ್ತ್’ದ್ ಬೇತೆ ಕಡೆಲೆಡ್ ಪಟ್ಟೆರ್. ಇಂದೆಕ್ ಇರೆನ ಒಪ್ಪಿಗೆ ಇತ್ತ್’ನ್ಡ ಮಾತ್ರ ಮುಲ್ಪ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ.<br />
ಅತ್ತಂದೆ ಇರೆನ ಸಂಪಾದನೆಲೆನ್ ಈರ್ ಸ್ವತಃ ಬರೆತರ್, ಅತ್ತ್’ನ್ಡ ಕೃತಿಸ್ವಾಮ್ಯತೆ ಇಜ್ಜಂದಿನ ಕಡೆರ್ದ್ ದೆತೊನ್ದರ್ ಪಂಡ್’ದ್ ಪ್ರಮಾಣಿಸೊಂದುಲ್ಲರ್.
<strong>ಕೃತಿಸ್ವಾಮ್ಯತೆದ ಅಡಿಟುಪ್ಪುನಂಚಿನ ಕೃತಿಲೆನ್ ಒಪ್ಪಿಗೆ ಇಜ್ಜಂದೆ ಮುಲ್ಪ ಪಾಡೊಚಿ!</strong>',
'template-protected'   => '(ಸಂರಕ್ಷಿತ)',

# History pages
'revisionasof'     => '$1 ದಿನೊತ ಆವೃತ್ತಿ',
'previousrevision' => '←ದುಂಬುದ ಆವೃತ್ತಿ',
'cur'              => 'ಸದ್ಯದ',
'last'             => 'ಕಡೆತ',

# Diffs
'lineno'   => '$1 ನೇ ಸಾಲ್:',
'editundo' => 'ದುಂಬುದಲೆಕ',

# Search results
'noexactmatch' => "'''\"\$1\". ಅ೦ಚಿನ ವಾ ಪುಟಲಾ ಇಜ್ಜಿ. '''
ಈರ್ [[:\$1| ಐನ್ ಸುರು ಮಲ್ಪೊಲಿ]].",
'viewprevnext' => 'ತೂಲೆ ($1) ($2) ($3)',
'powersearch'  => 'ನಾಡ್’ಲೆ',

# Preferences page
'mypreferences' => 'ಎನ್ನ ಪ್ರಾಶಸ್ತ್ಯಲು',

# Recent changes
'recentchanges'   => 'ಇಂಚಿಪದ ಬದಲಾವಣೆಲು',
'rcnote'          => "$4, $5 ಮುಟ್ಟ ದುಂಬುದ {{PLURAL:$2|ದಿನೊಟು|'''$2''' ದಿನೊಲೆಡ್}} ಮಲ್ತ್’ದಿನ {{PLURAL:$1|'''1''' ಬದಲಾವಣೆ|'''$1''' ಬದಲಾವಣೆಲು}} ತಿರ್ತುಂಡು.",
'rcshowhideminor' => '$1 ಎಲ್ಯೆಲ್ಯ ಬದಲಾವಣೆಲು',
'rclinks'         => 'ದುಂಬುದ $2 ದಿನೊಲೆಡ್ ಮಲ್ತಿನ $1 ಕಡೆತ ಬದಲಾವಣೆಲೆನ್ ತೂಲೆ <br />$3',
'diff'            => 'ವ್ಯತ್ಯಾಸ',
'hist'            => 'ಇತಿಹಾಸ',
'hide'            => 'ದೆಂಗಾವು',
'minoreditletter' => 'ಚು',
'newpageletter'   => 'ಪೊ',
'boteditletter'   => 'ಬಾ',

# Recent changes linked
'recentchangeslinked'          => 'ಸಂಬಂಧ ಉಪ್ಪುನಂಚಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-title'    => '"$1" ಪುಟೊಟು ಆತಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-noresult' => 'ಕೊರ್ತಿನ ಸಮಯೊಡು ಲಿಂಕ್ ಉಪ್ಪುನ ಪುಟೊಲೆಡ್ ಓವುಲಾ ಬದಲಾವಣೆಲು ಆತಿಜಿ.',
'recentchangeslinked-summary'  => "ಒಂಜಿ ನಿರ್ದಿಷ್ಟ ಪುಟೊರ್ದು (ಅತ್ತ್’ನ್ಡ ನಿರ್ದಿಷ್ಟ ವರ್ಗೊಗು ಸೇರ್ದಿನ ಪುಟೊಲೆರ್ದ್) ಸಂಪರ್ಕ ಉಪ್ಪುನ ಪುಟೊಲೆಡ್ ಇಂಚಿಪ ಮಲ್ತಿನಂಚಿನ ಬದಲಾವಣೆಲೆನ್ ತಿರ್ತ್ ಪಟ್ಟಿ ಮಲ್ಪೆರಾತ್’ನ್ಡ್. 
[[Special:Watchlist|ಇರೆನ ವೀಕ್ಷಣಾಪಟ್ಟಿಡ್]] ಉಪ್ಪುನ ಪುಟೊಲು '''ದಪ್ಪ ಅಕ್ಷರೊಡು''' ಉಂಡು.",

# Upload
'upload' => 'ಫೈಲ್ ಅಪ್ಲೋಡ್',

# Image description page
'filehist'            => 'ಫೈಲ್’ದ ಇತಿಹಾಸ',
'filehist-help'       => 'ಫೈಲ್ ಆ ದಿನೊಟು ಎಂಚ ಇತ್ತ್’ನ್ಡ್’ನ್ದ್ ತೂಯೆರೆ ಆ ದಿನ/ಪೊರ್ತುದ ಮಿತ್ತ್ ಕ್ಲಿಕ್ ಮಲ್ಪುಲೆ.',
'filehist-current'    => 'ಪ್ರಸಕ್ತ',
'filehist-datetime'   => 'ದಿನ/ಪೊರ್ತು',
'filehist-user'       => 'ಸದಸ್ಯೆ',
'filehist-dimensions' => 'ಆಯಾಮೊಲು',
'filehist-filesize'   => 'ಫೈಲ್’ದ ಗಾತ್ರ',
'filehist-comment'    => 'ಕಮೆಂಟ್',
'imagelinks'          => 'ಲಿಂಕ್’ಲು',
'linkstoimage'        => 'ಈ ಫೈಲ್’ಗ್ ತಿರ್ತ್’ದ ಈ {{PLURAL:$1|ಪುಟ|$1 ಪುಟೊಲು}} ಲಿಂಕ್ ಕೊರ್ಪುಂಡು.',

# Random page
'randompage' => 'ಯಾದೃಚ್ಛಿಕ ಪುಟ',

# Miscellaneous special pages
'nbytes'   => '$1 {{PLURAL:$1|ಬೈಟ್|ಬೈಟ್‍ಲು}}',
'nmembers' => '$1 {{PLURAL:$1|ಸದಸ್ಯೆ|ಸದಸ್ಯೆರ್}}',
'move'     => 'ಮೂವ್(ಸ್ಥಳಾಂತರ) ಮಲ್ಪುಲೆ',

# Special:AllPages
'alphaindexline' => '$1 ರ್ದ್ $2 ಗ್',
'allpagessubmit' => 'ಪೋ',

# Watchlist
'mywatchlist' => 'ಎನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿ',
'watch'       => 'ತೂಲೆ',
'unwatch'     => 'ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆಪ್ಪು',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾವೊಂದುಂಡು...',
'unwatching' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆತ್ತೊಂದುಂಡು...',

# Namespace form on various pages
'namespace'      => 'ನೇಮ್-ಸ್ಪೇಸ್:',
'blanknamespace' => '(ಮುಖ್ಯ)',

# Contributions
'mycontris' => 'ಎನ್ನ ಕಾಣಿಕೆಲು',

# What links here
'whatlinkshere'       => 'ಇಡೆ ವಾ ಪುಟೊಲು ಲಿಂಕ್ ಕೊರ್ಪುಂಡು',
'whatlinkshere-title' => '"$1" ಪುಟೊಗು ಲಿಂಕ್ ಕೊರ್ಪುನ ಪುಟೊಲು',
'linkshere'           => "'''[[:$1]]'''ಗ್ ಈ ತಿರ್ತ್’ದ ಪುಟೊಲು ಲಿಂಕ್ ಕೊರ್ಪುಂಡು.",
'isredirect'          => 'ಪುನರ್ನಿರ್ದೇಶನ ಪುಟ',
'istemplate'          => 'ಸೇರ್ಪಡೆ',
'whatlinkshere-prev'  => '{{PLURAL:$1|ದುಂಬುದ|ದುಂಬುದ $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ಬೊಕ್ಕದ|ಬೊಕ್ಕದ $1}}',
'whatlinkshere-links' => '← ಲಿಂಕ್’ಲು',

# Block/unblock
'blocklink'    => 'ಅಡ್ಡ ಪತ್ತ್’ಲೆ',
'contribslink' => 'ಕಾಣಿಕೆಲು',

# Thumbnails
'thumbnail-more'  => 'ಮಲ್ಲ ಮಲ್ಪುಲೆ',
'thumbnail_error' => 'ಮುನ್ನೋಟ ಚಿತ್ರೊನು ಸೃಷ್ಟಿ ಮನ್ಪುನಗ ದೋಷ: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ಎನ್ನ ಸದಸ್ಯ ಪುಟ',
'tooltip-pt-mytalk'               => 'ಎನ್ನ ಚರ್ಚೆ ಪುಟ',
'tooltip-pt-preferences'          => 'ಎನ್ನ ಇಷ್ಟೊಲು',
'tooltip-pt-watchlist'            => 'ಈರ್ ಬದಲಾವಣೆಗಾದ್ ನಿಗಾ ದೀತಿನಂಚಿನ ಪುಟೊಲ್ದ ಪಟ್ಟಿ',
'tooltip-pt-mycontris'            => 'ಎನ್ನ ಕಾಣಿಕೆಲ್ದ ಪಟ್ಟಿ',
'tooltip-pt-login'                => 'ಈರ್ ಲಾಗ್ ಇನ್ ಆವೊಡುಂದು ಕೋರೊಂದುಲ್ಲ, ಆಂಡ ಉಂದು ದಾಲ ಕಡ್ಡಾಯ ಅತ್ತ್.',
'tooltip-pt-logout'               => 'ಲಾಗ್ ಔಟ್',
'tooltip-ca-talk'                 => 'ಮಾಹಿತಿ ಪುಟೊತ ಬಗ್ಗೆ ಚರ್ಚೆ',
'tooltip-ca-edit'                 => 'ಈ ಪುಟೊನು ಈರ್ ಸಂಪಾದನೆ ಮಲ್ಪೊಲಿ. ಸೇವ್ ಮಲ್ಪುನ ದುಂಬು ಮುನ್ನೋಟದ ಉಪಯೊಗ ಮನ್ತೊನ್ಲೆ.',
'tooltip-ca-viewsource'           => 'ಉಂದೊಂಜಿ ಸಂರಕ್ಷಿತ ಪುಟ.
ಇಂದೆತ ಮೂಲೊನು ಈರ್ ತೂವೊಲಿ.',
'tooltip-ca-move'                 => 'ಈ ಪೂಟೊನು ಮೂವ್(ಸ್ಥಳಾಂತರ) ಮಲ್ಪುಲೆ',
'tooltip-ca-watch'                => 'ಈ ಪುಟೊನು ಇರೆನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೆರ್ಪಾಲೆ',
'tooltip-search'                  => '{{SITENAME}}ನ್ ನಾಡ್’ಲೆ',
'tooltip-n-mainpage'              => 'ಮುಖ್ಯ ಪುಟೊನು ತೂಲೆ',
'tooltip-n-portal'                => 'ಪ್ರೊಜೆಕ್ಟ್’ದ ಬಗ್ಗೆ, ಈರ್ ದಾದ ಮಲ್ಪೊಲಿ, ಓಲು ಇಂದೆತ ಬಗ್ಗೆ ತೆರಿಯೊನೊಲಿ',
'tooltip-n-currentevents'         => 'ಪ್ರಸಕ್ತ ಘಟನೆಲ್ದ ಬಗ್ಗೆ ಹಿನ್ನೆಲೆ ಮಾಹಿತಿ ತೆರಿಯೊನ್ಲೆ',
'tooltip-n-recentchanges'         => 'ವಿಕಿಡ್ ದುಂಬುದ ಒಂತೆ ಸಮಯಡ್ ಆತಿನಂಚಿನ ಬದಲಾವಣೆಲ್ದ ಪಟ್ಟಿ',
'tooltip-n-randompage'            => 'ಯಾದೃಚ್ಛಿಕ ಪುಟವೊಂಜೇನ್ ತೊಜ್ಪಾವ್',
'tooltip-n-help'                  => 'ತೆರಿತೊನೆರೆ ಜಾಗ',
'tooltip-t-whatlinkshere'         => 'ಇಡೆ ಲಿಂಕ್ ಕೊರ್ಪುನಂಚಿನ ಪೂರ ವಿಕಿ ಪುಟೊಲ್ದ ಪಟ್ಟಿ',
'tooltip-t-upload'                => 'ಫೈಲ್’ನ್ ಅಪ್ಲೋಡ್ ಮಲ್ಪುಲೆ',
'tooltip-t-specialpages'          => 'ಪೂರ ವಿಷೇಶ ಪುಟೊಲ್ದ ಪಟ್ಟಿ',
'tooltip-ca-nstab-image'          => 'ಫೈಲ್’ದ ಪುಟೊನು ತೂಲೆ',
'tooltip-ca-nstab-category'       => 'ವರ್ಗೊದ ಪುಟೊನು ತೂಲೆ',
'tooltip-save'                    => 'ಈರ್ ಮಲ್ತ್’ದಿನ ಬದಲಾವಣೆಲೆನ್ ಒರಿಪುಲೆ',
'tooltip-preview'                 => 'ಈರ್ ಮಲ್ತಿನ ಬದಲಾವಣೆತ ಮುನ್ನೋಟ - ಈ ಪುಟನ್ ಒರಿಪಾವುನ ದು೦ಬು ನೇನ್ ತೂಲೆ',
'tooltip-diff'                    => 'ಈ ಲೇಖನೊಗ್ ಮಲ್ತಿನ ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವ್',
'tooltip-compareselectedversions' => 'ಈ ಪುಟತ ಆಯ್ಕೆ ಮಲ್ತಿನ ರಡ್ಡ್ ಆವೃತ್ತಿದ ವ್ಯತ್ಯಾಸನ್ ತೂಲೆ',
'tooltip-watch'                   => 'ಈ ಪುಟನ್ ಈರ್ನ ತೂಪುನ ಪಟ್ಟಿಗ್ ಸೇರ್ಸಾಲೆ',
'tooltip-recreate'                => 'ಈ ಪುಟ ಇತ್ತೆ ಇಜ್ಜ೦ಡಲಾ ಐನ್ ಪಿರ ಮಲ್ಪ್',
'tooltip-upload'                  => 'ಅಪ್ಲೋಡ್ ಸುರು ಮಲ್ಪು',
'tooltip-rollback'                => '"Rollback", ಈ ಪುಟದ ಕರಿನ ಬದಾಲವಣೆಗ್ ಒ೦ಜಿ ಕ್ಲಿಕ್ ಡ್ ಕೊನೊಪು೦ಡು',
'tooltip-undo'                    => '"Undo" ಈ ಬದಲಾವಣೆನ್ ದೆತೊನುಜಿ ಬುಕ ಪ್ರಿವ್ಯೂ ಮೋಡ್ ಡ್ ಬದಲಾವಣೆ ಮಲ್ಪೆರ್ ಕೊನೊಪು೦ಡು. ಅ೦ಚೆನೆ ಸಮ್ಮರಿ ಡ್ ಬದಲಾವಣೆ ಗ್ ಕಾರಣ ಕೊರ್ರ್‍ಎ ಆಪು೦ಡು.',

# Media information
'file-info-size'       => '($1 × $2 ಪಿಕ್ಸೆಲ್, ಫೈಲ್’ದ ಗಾತ್ರ: $3, MIME ಪ್ರಕಾರ: $4)',
'show-big-image'       => 'ಪೂರ್ತಿ ರೆಸೊಲ್ಯೂಶನ್',
'show-big-image-thumb' => '<small>ಈ ಮುನ್ನೋಟದ ಗಾತ್ರ: $1 × $2 ಪಿಕ್ಸೆಲ್</small>',

# Bad image list
'bad_image_list' => 'ವ್ಯವಸ್ಥೆದ ಆಕಾರ ಈ ರೀತಿ ಉಂಡು:

ಪಟ್ಟಿಡುಪ್ಪುನಂಚಿನ ದಾಖಲೆಲೆನ್ (* ರ್ದ್ ಶುರು ಆಪುನ ಸಾಲ್’ಲು) ಮಾತ್ರ ಪರಿಗಣನೆಗ್ ದೆತೊನೆರಾಪುಂಡು.
ಪ್ರತಿ ಸಾಲ್’ದ ಶುರುತ ಲಿಂಕ್ ಒಂಜಿ ದೋಷ ಉಪ್ಪುನಂಚಿನ ಫೈಲ್’ಗ್ ಲಿಂಕಾದುಪ್ಪೊಡು.
ಅವ್ವೇ ಸಾಲ್’ದ ಶುರುತ ಪೂರಾ ಲಿಂಕ್’ಲೆನ್ ಪರಿಗನೆರ್ದ್ ದೆಪ್ಪೆರಾಪುಂಡು, ಪಂಡ ಓವು ಪುಟೊಲೆಡ್ ಫೈಲ್’ದ ಬಗ್ಗೆ ಬರ್ಪುಂಡೋ ಔಲು.',

# Metadata
'metadata' => 'ಮೇಲ್ದರ್ಜೆ ಮಾಹಿತಿ',

# External editor support
'edit-externally'      => 'ಬಾಹ್ಯ(ಪಿದಯಿದ) ತಂತ್ರಾಶೊನು ಉಪಯೋಗ ಮಲ್ತ್’ದ್ ಇಂದೆನ್ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'edit-externally-help' => 'ಹೆಚ್ಚಿನ ಮಾಹಿತಿಗ್ [http://www.mediawiki.org/wiki/Manual:External_editors ಸೆಟ್-ಅಪ್ ನಿರ್ದೇಶನೊಲೆನ್] ತೂಲೆ.',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ಪೂರಾ',

# Special:SpecialPages
'specialpages' => 'ವಿಷೇಶ ಪುಟೊಲು',

);
