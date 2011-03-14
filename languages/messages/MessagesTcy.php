<?php
/** Tulu (ತುಳು)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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
'tog-rememberpassword'        => 'ಈ ಕಂಪ್ಯೂಟರ್’ಡ್ ಎನ್ನ ಲಾಗಿನ್ನ್ ನೆನಪುಡು ದೀಲ (for a maximum of $1 {{PLURAL:$1|day|days}})',
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
'hidden-category-category'       => 'ದೆಂಗಾದ್ ದೀತಿನ ವರ್ಗೊಲು',
'category-subcat-count'          => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಉಪವರ್ಗ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಉಪವರ್ಗೊನು|$1 ಉಪವರ್ಗೊಲೆನ್}} ಸೇರಾದ್, ಒಟ್ಟಿಗೆ $2 ಉಂಡು.}}',
'category-subcat-count-limited'  => 'ಈ ವರ್ಗೊಡು ತಿರ್ತ್ ತೊಜ್ಪಾದಿನ {{PLURAL:$1|ಉಪವರ್ಗ|$1 ಉಪವರ್ಗೊಲು}} ಉಂಡು.',
'category-article-count'         => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಖಾಲಿ ಒಂಜಿ ಪುಟ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಪುಟೊನು|$1 ಪುಟೊಲೆನ್}} ಸೇರ್ಪಾದ್, ಒಟ್ಟಿಗೆ $2 ಪುಟೊಲು ಉಂಡು.}}',
'category-article-count-limited' => 'ಪ್ರಸಕ್ತ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಪುಟ ಉಂಡು|$1 ಪುಟೊಲು ಉಂಡು}}.',
'category-file-count'            => '{{PLURAL:$2|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ ಖಾಲಿ ಒಂಜಿ ಫೈಲ್ ಉಂಡು.|ಈ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಫೈಲ್’ನ್|$1 ಫೈಲ್’ಲೆನ್}} ಸೇರ್ಪಾದ್, ಒಟ್ಟಿಗೆ $2 ಉಂಡು.}}',
'category-file-count-limited'    => 'ಪ್ರಸಕ್ತ ವರ್ಗೊಡು ಈ ತಿರ್ತ್’ದ {{PLURAL:$1|ಫೈಲ್ ಉಂಡು|$1 ಫೈಲ್’ಲು ಉಂಡು}}.',
'listingcontinuesabbrev'         => 'ಮುಂದು.',

'mainpagetext'      => "'''ಮೀಡಿಯವಿಕಿ ಯಶಸ್ವಿಯಾದ್ ಇನ್’ಸ್ಟಾಲ್ ಆಂಡ್.'''",
'mainpagedocfooter' => 'ವಿಕಿ ತಂತ್ರಾಂಶನ್ ಉಪಗೋಗ ಮನ್ಪುನ ಬಗ್ಗೆ ಮಾಹಿತಿಗ್ [http://meta.wikimedia.org/wiki/Help:Contents ಸದಸ್ಯೆರ್ನ ನಿರ್ದೇಶನ ಪುಟ] ತೂಲೆ.

== ಎಂಚ ಶುರು ಮಲ್ಪುನಿ ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ ಮೀಡಿಯವಿಕಿ FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'         => 'ಎಂಕ್ಲೆನ ಬಗ್ಗೆ',
'article'       => 'ಲೇಖನ ಪುಟ',
'newwindow'     => '(ಪೊಸ ಕಂಡಿನ್ ಓಪನ್ ಮಲ್ಪುಂಡು)',
'cancel'        => 'ವಜಾ ಮನ್ಪುಲೆ',
'moredotdotdot' => 'ನನಲ...',
'mypage'        => 'ಎನ್ನ ಪುಟ',
'mytalk'        => 'ಎನ್ನ ಚರ್ಚೆ',
'anontalk'      => 'ಈ ಐ.ಪಿ ಗ್ ಪಾತೆರ್’ಲೆ',
'navigation'    => 'ಸಂಚಾರ',
'and'           => '&#32;ಬೊಕ್ಕ',

# Cologne Blue skin
'qbfind'         => 'ನಾಡ್’ಲೆ',
'qbbrowse'       => 'ಬ್ರೌಸ್',
'qbedit'         => 'ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'qbpageoptions'  => 'ಈ ಪುಟ',
'qbpageinfo'     => 'ಸನ್ನಿವೇಶ',
'qbmyoptions'    => 'ಎನ್ನ ಪುಟೊಲು',
'qbspecialpages' => 'ವಿಶೇಷ ಪುಟೊಲು',
'faq'            => 'ಸಾಮಾನ್ಯವಾದ್ ಕೇನುನ ಪ್ರಶ್ನೆಲು',
'faqpage'        => 'Project:ಸಾಮಾನ್ಯವಾದ್ ಕೇನುನ ಪ್ರಶ್ನೆಲು',

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
'protect_change'    => 'ಬದಲಾಲೆ',
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
'lastmodifiedat'    => 'ಈ ಪುಟ ಇಂದೆತ ದುಂಬು $2, $1 ಕ್ ಬದಲಾತ್’ನ್ಡ್.',
'viewcount'         => 'ಈ ಪುಟೊನು {{PLURAL:$1|1 ಸರಿ|$1 ಸರಿ}} ತೂತೆರ್.',
'protectedpage'     => 'ಸಂರಕ್ಷಿತ ಪುಟ',
'jumpto'            => 'ಇಡೆ ಪೋಲೆ:',
'jumptonavigation'  => 'ಸಂಚಾರ',
'jumptosearch'      => 'ನಾಡ್’ಲೆ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ದ ಬಗ್ಗೆ',
'aboutpage'            => 'Project:ನಮ್ಮ ಬಗ್ಗೆ',
'copyright'            => 'ಉಂದು ಈ ಕಾಪಿರೈಟ್‌ಡ್ ಲಭ್ಯವುಂಡು $1.',
'copyrightpage'        => '{{ns:project}}:ಕೃತಿಸ್ವಾಮ್ಯತೆಲು',
'currentevents'        => 'ಇತ್ತೆದ ಸಂಗತಿಲು',
'currentevents-url'    => 'Project:ಇತ್ತೆದ ಸಂಗತಿಲು',
'disclaimers'          => 'ಅಬಾಧ್ಯತೆಲು',
'disclaimerpage'       => 'Project:ಸಾಮಾನ್ಯ ಅಬಾಧ್ಯತೆಲು',
'edithelp'             => 'ಸಂಪಾದನೆ(ಎಡಿಟ್) ಮಲ್ಪೆರೆ ಸಹಾಯ',
'edithelppage'         => 'Help:ಸಂಪಾದನೆ',
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
'editlink'                => 'ಎಡಿಟ್ ಮಲ್ಪುಲೆ',
'viewsourcelink'          => 'ಮೂಲೊನು ತೂಲೆ',
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
'nstab-special'   => 'ವಿಶೇಷ ಪುಟ',
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
'nospecialpagetext' => '<strong>ಈರ್ ಅಸ್ಥಿತ್ವಡ್ ಇಜ್ಜಂದಿನ ವಿಷೇಶ ಪುಟೊನು ಕೇನ್ದರ್.</strong>

ಅಸ್ಥಿತ್ವಡ್ ಉಪ್ಪುನಂಚಿನ ವಿಷೇಶ ಪುಟೊಲ್ದ ಪಟ್ಟಿ [[Special:SpecialPages|{{int:specialpages}}]] ಡ್ ಉಂಡು.',

# General errors
'error'                => 'ದೋಷ',
'databaseerror'        => 'ಡೇಟಾಬೇಸ್ ದೋಷ',
'readonly'             => 'ಡಾಟಾಬೇಸ್ ಲಾಕ್ ಆತ್೦ಡ್',
'missing-article'      => '"$1" $2 ಪುದರ್’ದ ಪುಟ ದೇಟಬೇಸ್’ಡ್ ಇಜ್ಜಿ.

ಡಿಲೀಟ್ ಮಲ್ತಿನ ಪುಟೊಕು ಸಂಪರ್ಕ ಕೊರ್ಪುನ ಇತಿಹಾಸ ಲಿಂಕ್ ಅತ್ತ್’ನ್ಡ ವ್ಯತ್ಯಾಸ ಲಿಂಕ್’ನ್ ಒತ್ತುನೆರ್ದಾದ್ ಈ ದೋಷ ಸಾಧಾರಣವಾದ್ ಬರ್ಪುಂಡು.

ಒಂಜಿ ವೇಳೆ ಅಂಚ ಆದಿಜ್ಜಿಂಡ, ಉಂದು ಒಂಜಿ ಸಾಫ್ಟ್-ವೇರ್ ದೋಷ ಆದುಪ್ಪು.
ಇಂದೆನ್  [[Special:ListUsers/sysop|ವಿಕಿ-ಅಧಿಕಾರಿಗ್]] ತೆರಿಪಾಲೆ.',
'missingarticle-rev'   => '(ಮರು-ಆವೃತ್ತಿ#: $1)',
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
'viewsourcefor'        => '$1 ಪುಟೊಗು',

# Login and logout pages
'yourname'                => 'ಸದಸ್ಯೆರ್ನ ಪುದರ್:',
'yourpassword'            => 'ಪಾಸ್-ವರ್ಡ್:',
'remembermypassword'      => 'ಈ ಕಂಪ್ಯೂಟರ್’ಡ್ ಎನ್ನ ಪ್ರವೇಶ ಪದೊನು ನೆನಪು ದೀಲ',
'login'                   => 'ಲಾಗ್ ಇನ್',
'nav-login-createaccount' => 'ಲಾಗ್-ಇನ್ / ಅಕೌಂಟ್ ಸೃಷ್ಟಿ ಮಲ್ಪುಲೆ',
'userlogin'               => 'ಲಾಗ್-ಇನ್ / ಅಕೌಂಟ್ ಸೃಷ್ಟಿ ಮಲ್ಪುಲೆ',
'logout'                  => 'ಲಾಗ್ ಔಟ್',
'userlogout'              => 'ಲಾಗ್ ಔಟ್',
'mailmypassword'          => 'ಪೊಸ ಪಾಸ್-ವರ್ಡ್’ನ್ ಇ-ಮೇಲ್ ಮಲ್ಪುಲೆ',

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
'summary'                          => 'ಸಾರಾಂಶ:',
'subject'                          => 'ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ:',
'minoredit'                        => 'ಉಂದು ಎಲ್ಯ ಬದಲಾವಣೆ',
'watchthis'                        => 'ಈ ಪುಟೊನು ತೂಲೆ',
'savearticle'                      => 'ಪುಟೊನು ಒರಿಪಾಲೆ',
'preview'                          => 'ಮುನ್ನೋಟ',
'showpreview'                      => 'ಮುನ್ನೋಟ ತೊಜ್ಪಾವ್',
'showlivepreview'                  => 'ಪ್ರತ್ಯಕ್ಷ ಮುನ್ನೋಟ',
'showdiff'                         => 'ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವ್',
'anoneditwarning'                  => "'''ಜಾಗ್ರತೆ:''' ಈರ್ ಇತ್ತೆ ಲಾಗ್ ಇನ್ ಆತಿಜರ್.
ಈರ್ನ ಐ.ಪಿ ಎಡ್ರೆಸ್ ಈ ಪುಟೊತ ಬದಲಾವಣೆ ಇತಿಹಾಸೊಡು ದಾಖಲಾಪು೦ಡು.",
'missingsummary'                   => "'''ಗಮನಿಸಾಲೆ:''' ಈರ್ ಬದಲಾವಣೆದ ಸಾರಾ೦ಶನ್ ಕೊರ್ತಿಜರ್.
ಈರ್ ಪಿರ 'ಒರಿಪಾಲೆ' ಬಟನ್ ನ್ ಒತ್ತ್೦ಡ ಸಾರಾ೦ಶ ಇಜ್ಜ೦ದೆನೇ ಈರ್ನ ಬದಲಾವಣೆ ದಾಖಲಾಪು೦ಡು.",
'missingcommenttext'               => 'ದಯ ಮಲ್ತ್ ದ ಈರ್ನ ಅಭಿಪ್ರಾಯನ್ ತಿರ್ತ್ ಕೊರ್ಲೆ',
'missingcommentheader'             => "'''ಗಮನಿಸಾಲೆ:''' ಈರ್ ಈ ಅಭಿಪ್ರಾಯಗ್ \"ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ\" ದಾಲ ಕೊರ್ತಿಜರ್. ಈರ್ ಪಿರ ’ಒರಿಪಾಲೆ’ ಬಟನ್ ನ್ ಒತ್ತ್೦ಡ ಈರ್ನ ಬದಲಾವಣೆ ವಿಷಯ/ಮುಖ್ಯಾ೦ಶ ಇಜ್ಜ೦ದನೇ ಒರಿಪ್ಪಾವು೦ಡು.",
'summary-preview'                  => 'ಸಾರಾ೦ಶ ಮುನ್ನೋಟ:',
'subject-preview'                  => 'ವಿಷಯ/ಮುಖ್ಯಾ೦ಶದ ಮುನ್ನೋಟ:',
'blockedtitle'                     => 'ಈ ಸದಸ್ಯೆರೆನ್ ತಡೆ ಮಲ್ತ್ ದ್೦ಡ್.',
'newarticle'                       => '(ಪೊಸತ್)',
'newarticletext'                   => "ನನಲ ಅಸ್ಥಿತ್ವಡ್ ಉಪ್ಪಂದಿನ ಪುಟೊಗು ಈರ್ ಬೈದರ್.
ಈ ಪುಟೊನು ಸೃಷ್ಟಿ ಮಲ್ಪೆರೆ ತಿರ್ತ್’ದ ಚೌಕೊಡು ಬರೆಯೆರೆ ಸುರು ಮಲ್ಪುಲೆ.
(ಜಾಸ್ತಿ ಮಾಹಿತಿಗ್ [[{{MediaWiki:Helppage}}|ಸಹಾಯ ಪುಟೊನು]] ತೂಲೆ).
ಈ ಪುಟೊಕು ಈರ್ ತಪ್ಪಾದ್ ಬತ್ತಿತ್ತ್’ನ್ಡ ಇರೆನ ಬ್ರೌಸರ್’ದ '''back''' ಬಟನ್’ನ್ ಒತ್ತ್’ಲೆ.",
'noarticletext'                    => 'ಈ ಪುಟೊಟು ಸದ್ಯಗ್ ಓ ಬರಹಲಾ ಇಜ್ಜಿ, ಈರ್ ಬೇತೆ ಪೂಟೊಲೆಡ್ [[Special:Search/{{PAGENAME}}|ಈ ಲೇಖನೊನು ನಾಡೊಲಿ]] ಅತ್ತ್’ನ್ಡ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ಈ ಪುಟೊನು ಸಂಪಾದನೆ ಮಲ್ಪೊಲಿ].',
'previewnote'                      => "'''ಉಂದು ಕೇವಲ ಮುನ್ನೋಟ; ಪುಟೊನು ನನಲ ಒರಿಪಾದಿಜಿ ಪನ್ಪುನೇನ್ ಮರಪೊರ್ಚಿ!'''",
'editing'                          => '$1 ಲೇಖನೊನು ಈರ್ ಸಂಪಾದನೆ ಮಲ್ತೊಂದುಲ್ಲರ್',
'editingsection'                   => '$1 (ವಿಭಾಗೊನು) ಸಂಪಾದನೆ ಮಲ್ತೊಂದುಲ್ಲರ್',
'copyrightwarning'                 => "ದಯಮಲ್ತ್’ದ್ ಗಮನಿಸ್’ಲೆ: {{SITENAME}} ಸೈಟ್’ಡ್ ಇರೆನ ಪೂರಾ ಕಾಣಿಕೆಲುಲಾ $2 ಅಡಿಟ್ ಬಿಡುಗಡೆ ಆಪುಂಡು (ಮಾಹಿತಿಗ್ $1 ನ್ ತೂಲೆ). ಇರೆನ ಸಂಪಾದನೆಲೆನ್ ಬೇತೆಕುಲು ನಿರ್ಧಾಕ್ಷಿಣ್ಯವಾದ್ ಬದಲ್ ಮಲ್ತ್’ದ್ ಬೇತೆ ಕಡೆಲೆಡ್ ಪಟ್ಟೆರ್. ಇಂದೆಕ್ ಇರೆನ ಒಪ್ಪಿಗೆ ಇತ್ತ್’ನ್ಡ ಮಾತ್ರ ಮುಲ್ಪ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ.<br />
ಅತ್ತಂದೆ ಇರೆನ ಸಂಪಾದನೆಲೆನ್ ಈರ್ ಸ್ವತಃ ಬರೆತರ್, ಅತ್ತ್’ನ್ಡ ಕೃತಿಸ್ವಾಮ್ಯತೆ ಇಜ್ಜಂದಿನ ಕಡೆರ್ದ್ ದೆತೊನ್ದರ್ ಪಂಡ್’ದ್ ಪ್ರಮಾಣಿಸೊಂದುಲ್ಲರ್.
'''ಕೃತಿಸ್ವಾಮ್ಯತೆದ ಅಡಿಟುಪ್ಪುನಂಚಿನ ಕೃತಿಲೆನ್ ಒಪ್ಪಿಗೆ ಇಜ್ಜಂದೆ ಮುಲ್ಪ ಪಾಡೊಚಿ!'''",
'templatesused'                    => 'ಈ ಪುಟೊಟು ಉಪಯೋಗ ಮಲ್ತಿನ ಫಲಕೊಲು:',
'templatesusedpreview'             => 'ಈ ಮುನ್ನೋಟೊಡು ಉಪಯೋಗ ಮಲ್ತಿನ ಟೆಂಪ್ಲೇಟ್’ಲು:',
'template-protected'               => '(ಸಂರಕ್ಷಿತ)',
'template-semiprotected'           => '(ಅರೆ-ಸಂರಕ್ಷಿತ)',
'hiddencategories'                 => 'ಈ ಪುಟ {{PLURAL:$1|೧ ಗುಪ್ತ ವರ್ಗಗ್|$1 ಗುಪ್ತ ವರ್ಗೊಲೆಗ್}} ಸೇರ್ದ್’ನ್ಡ್:',
'permissionserrorstext-withaction' => '$2 ಗ್ ಇರೆಗ್ ಅನುಮತಿ ಇಜ್ಜಿ, ಐಕ್ {{PLURAL:$1|ಕಾರಣ|ಕಾರಣೊಲು}}:',
'moveddeleted-notice'              => 'ಈ ಪೇಜ್ ಅಸ್ತಿತ್ವಡ್ ಇಜ್ಜಿ.
ಪೂಟೊತ ಡಿಲೀಶನ್ ಲಾಗ್’ನ್ ತಿರ್ತ್ ಕೊರ್ತುಂಡು.',

# History pages
'viewpagelogs'           => 'ಈ ಪುಟೊತ ದಾಖಲೆಲೆನ್ ತೂಲೆ',
'currentrev'             => 'ಇತ್ತೆದ ಆವೃತ್ತಿ',
'currentrev-asof'        => '$1 ದ ಮುಟ್ಟ ಇತ್ತೆದ ಆವೃತ್ತಿ',
'revisionasof'           => '$1 ದಿನೊತ ಆವೃತ್ತಿ',
'previousrevision'       => '←ದುಂಬುದ ಆವೃತ್ತಿ',
'nextrevision'           => 'ಪೊಸ ಮರು-ಆವೃತ್ತಿ',
'cur'                    => 'ಸದ್ಯದ',
'last'                   => 'ಕಡೆತ',
'history-fieldset-title' => 'ಇತಿಹಾಸಡ್ ನಾಡ್ಲೆ',
'histfirst'              => 'ಬಾರಿ ದುಂಬುದ',
'histlast'               => 'ಇಂಚಿಪ್ಪದ',

# Revision deletion
'rev-delundel'   => 'ತೊಜ್ಪಾವ್/ದೆಂಗಾವ್',
'revdel-restore' => 'ವಿಸಿಬಿಲಿಟಿನ್ ಬದಲ್ ಮಲ್ಪುಲೆ',

# Merge log
'revertmerge' => 'ಅನ್-ಮರ್ಜ್ ಮಲ್ಪುಲೆ',

# Diffs
'history-title'           => '"$1" ಪುಟೊತ ಆವೃತ್ತಿ ಇತಿಹಾಸ',
'difference'              => '(ಆವೃತ್ತಿಲೆದ ನಡುತ ವ್ಯತ್ಯಾಸ)',
'lineno'                  => '$1 ನೇ ಸಾಲ್:',
'compareselectedversions' => 'ಆಯ್ಕೆ ಮಲ್ತಿನ ಆವೃತ್ತಿಲೆನ್ ಹೊಂದಾಣಿಕೆ ಮಲ್ತ್ ತೂಲೆ',
'editundo'                => 'ದುಂಬುದಲೆಕ',

# Search results
'searchresults'             => 'ನಾಡಟದ ಫಲಿತಾಂಶೊಲು',
'searchresults-title'       => '"$1" ಕ್ ನಾಡಟದ ಫಲಿತಾಂಶೊಲು',
'searchresulttext'          => '{{SITENAME}} ಡ್ ನಾಡಟ ಮಲ್ಪುನ ಬಗ್ಗೆ ಜಾಸ್ತಿ ಮಾಹಿತಿಗ್ [[{{MediaWiki:Helppage}}|{{int:help}}]] ನ್ ತೂಲೆ.',
'searchsubtitle'            => 'ಈರ್ \'\'\'[[:$1]]\'\'\' ನ್ ನಾಡಿಯರ್ ([[Special:Prefixindex/$1|"$1" ರ್ದ್ ಶುರುವಾಪುನ ಪೂರ ಪುಟೊಲು]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ಗ್ ಲಿಂಕ್ ಕೊರ್ಪುನ ಪೂರ ಪುಟೊಲು]])',
'searchsubtitleinvalid'     => "'''$1''' ನ್ ಈರ್ ನಾಡಿಯರ್.",
'notitlematches'            => 'ವಾ ಪುಟೊತ ಶಿರ್ಷಿಕೆಲಾ ಹೊಂದಿಕೆ ಆವೊಂದಿಜ್ಜಿ',
'notextmatches'             => 'ವಾ ಪುಟೊತ ಪಠ್ಯೊಡುಲಾ ಹೋಲಿಕೆ ಇಜ್ಜಿ',
'prevn'                     => 'ದುಂಬುದ {{PLURAL:$1|$1}}',
'nextn'                     => 'ಬೊಕ್ಕದ {{PLURAL:$1|$1}}',
'viewprevnext'              => 'ತೂಲೆ ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:ಪರಿವಿಡಿ',
'search-result-size'        => '$1 ({{PLURAL:$2|೧ ಪದ|$2 ಪದೊಲು}})',
'search-redirect'           => '(ಪುನರ್ನಿರ್ದೇಶನ $1)',
'search-section'            => '(ವಿಭಾಗ $1)',
'search-suggest'            => 'ಇಂದೆನ್ ನಾಡೊಂದುಲ್ಲರೆ: $1',
'search-interwiki-caption'  => 'ಬಳಗದ ಇತರ ಯೋಜನೆಲು',
'search-interwiki-default'  => '$1 ಫಲಿತಾಂಶೊಲು:',
'search-interwiki-more'     => '(ಮಸ್ತ್)',
'search-mwsuggest-enabled'  => 'ಸಲಹೆದೊಟ್ಟಿಗೆ',
'search-mwsuggest-disabled' => 'ಓವು ಸಲಹೆಲಾ ಇಜ್ಜಿ',
'powersearch'               => 'ನಾಡ್’ಲೆ',
'powersearch-legend'        => 'ಅಡ್ವಾನ್ಸ್’ಡ್ ಸರ್ಚ್',
'powersearch-ns'            => 'ನೇಮ್-ಸ್ಪೇಸ್’ಲೆಡ್ ನಾಡ್ಲೆ',
'powersearch-field'         => 'ನಾಡ್ಲೆ:',

# Preferences page
'preferences'   => 'ಪ್ರಾಶಸ್ತ್ಯೊಲು',
'mypreferences' => 'ಎನ್ನ ಪ್ರಾಶಸ್ತ್ಯಲು',

# Groups
'group-sysop' => 'ನಿರ್ವಾಹಕೆರ್',

'grouppage-sysop' => '{{ns:project}}:ನಿರ್ವಾಹಕೆರ್',

# User rights log
'rightslog' => 'ಸದಸ್ಯೆರ್ನ ಹಕ್ಕು ದಾಖಲೆ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ಈ ಪುಟೊನು ಎಡಿಟ್ ಮಲ್ಪುಲೆ',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|ಬದಲಾವಣೆ|ಬದಲಾವಣೆಲು}}',
'recentchanges'                  => 'ಇಂಚಿಪದ ಬದಲಾವಣೆಲು',
'recentchanges-legend'           => 'ಇಂಚಿಪದ ಬದಲಾವಣೆಲು ಆಯ್ಕೆಲು',
'recentchanges-feed-description' => 'ಈ ಫೀಡ್’ಡ್ ವಿಕಿಕ್ ಇಂಚಿಪ್ಪ ಆತಿನಂಚಿನ ಬದಲಾವಣೆಲೆನ್ ಟ್ರ್ಯಾಕ್ ಮಲ್ಪುಲೆ.',
'rcnote'                         => "$4, $5 ಮುಟ್ಟ ದುಂಬುದ {{PLURAL:$2|ದಿನೊಟು|'''$2''' ದಿನೊಲೆಡ್}} ಮಲ್ತ್’ದಿನ {{PLURAL:$1|'''1''' ಬದಲಾವಣೆ|'''$1''' ಬದಲಾವಣೆಲು}} ತಿರ್ತುಂಡು.",
'rclistfrom'                     => '$1 ರ್ದ್ ಶುರುವಾತಿನ ಪೊಸ ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವು',
'rcshowhideminor'                => '$1 ಎಲ್ಯೆಲ್ಯ ಬದಲಾವಣೆಲು',
'rcshowhidebots'                 => '$1 ಬಾಟ್',
'rcshowhideliu'                  => 'ಲಾಗ್-ಇನ್ ಆತಿನಂಚಿನ ಸದಸ್ಯೆರ್ $1',
'rcshowhideanons'                => 'ಅನಾಮಧೇಯ ಸದಸ್ಯೆರ್ $1',
'rcshowhidemine'                 => 'ಎನ್ನ ಸಂಪಾದನೆಲೆನ್ $1',
'rclinks'                        => 'ದುಂಬುದ $2 ದಿನೊಲೆಡ್ ಮಲ್ತಿನ $1 ಕಡೆತ ಬದಲಾವಣೆಲೆನ್ ತೂಲೆ <br />$3',
'diff'                           => 'ವ್ಯತ್ಯಾಸ',
'hist'                           => 'ಇತಿಹಾಸ',
'hide'                           => 'ದೆಂಗಾವು',
'show'                           => 'ತೊಜ್ಪಾವು',
'minoreditletter'                => 'ಚು',
'newpageletter'                  => 'ಪೊ',
'boteditletter'                  => 'ಬಾ',
'rc-enhanced-expand'             => 'ವಿವರೊಲೆನ್ ತೊಜ್ಪಾವು (ಜಾವ ಸ್ಕ್ರಿಪ್ಟ್ ಬೋಡಾಪುಂಡು)',
'rc-enhanced-hide'               => 'ವಿವರೊಲೆನ್ ದೆಂಗಾವು',

# Recent changes linked
'recentchangeslinked'          => 'ಸಂಬಂಧ ಉಪ್ಪುನಂಚಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-feed'     => 'ಸಂಬಂಧ ಉಪ್ಪುನಂಚಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-toolbox'  => 'ಸಂಬಂಧ ಉಪ್ಪುನಂಚಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-title'    => '"$1" ಪುಟೊಟು ಆತಿನ ಬದಲಾವಣೆಲು',
'recentchangeslinked-noresult' => 'ಕೊರ್ತಿನ ಸಮಯೊಡು ಲಿಂಕ್ ಉಪ್ಪುನ ಪುಟೊಲೆಡ್ ಓವುಲಾ ಬದಲಾವಣೆಲು ಆತಿಜಿ.',
'recentchangeslinked-summary'  => "ಒಂಜಿ ನಿರ್ದಿಷ್ಟ ಪುಟೊರ್ದು (ಅತ್ತ್’ನ್ಡ ನಿರ್ದಿಷ್ಟ ವರ್ಗೊಗು ಸೇರ್ದಿನ ಪುಟೊಲೆರ್ದ್) ಸಂಪರ್ಕ ಉಪ್ಪುನ ಪುಟೊಲೆಡ್ ಇಂಚಿಪ ಮಲ್ತಿನಂಚಿನ ಬದಲಾವಣೆಲೆನ್ ತಿರ್ತ್ ಪಟ್ಟಿ ಮಲ್ಪೆರಾತ್’ನ್ಡ್.
[[Special:Watchlist|ಇರೆನ ವೀಕ್ಷಣಾಪಟ್ಟಿಡ್]] ಉಪ್ಪುನ ಪುಟೊಲು '''ದಪ್ಪ ಅಕ್ಷರೊಡು''' ಉಂಡು.",
'recentchangeslinked-page'     => 'ಪುಟೊತ ಪುದರ್:',
'recentchangeslinked-to'       => 'ಇಂದೆತ ಬದಲಿಗ್ ಕೊರ್ತಿನ ಪುಟೊಗು ಲಿಂಕ್ ಉಪ್ಪುನಂಚಿನ ಪುಟೊಲೆದ ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವು',

# Upload
'upload'        => 'ಫೈಲ್ ಅಪ್ಲೋಡ್',
'uploadlogpage' => 'ಅಪ್ಲೋಡ್ ದಾಖಲೆ',
'uploadedimage' => '"[[$1]]" ಅಪ್ಲೋಡ್ ಆಂಡ್',

# File description page
'file-anchor-link'    => 'ಫೈಲ್',
'filehist'            => 'ಫೈಲ್’ದ ಇತಿಹಾಸ',
'filehist-help'       => 'ಫೈಲ್ ಆ ದಿನೊಟು ಎಂಚ ಇತ್ತ್’ನ್ಡ್’ನ್ದ್ ತೂಯೆರೆ ಆ ದಿನ/ಪೊರ್ತುದ ಮಿತ್ತ್ ಕ್ಲಿಕ್ ಮಲ್ಪುಲೆ.',
'filehist-current'    => 'ಪ್ರಸಕ್ತ',
'filehist-datetime'   => 'ದಿನ/ಪೊರ್ತು',
'filehist-thumb'      => 'ಥಂಬ್-ನೈಲ್',
'filehist-thumbtext'  => '$1 ತ ಲೆಕ್ಕ ಆವೃತ್ತಿದ ತಂಬ್-ನೈಲ್',
'filehist-user'       => 'ಸದಸ್ಯೆ',
'filehist-dimensions' => 'ಆಯಾಮೊಲು',
'filehist-filesize'   => 'ಫೈಲ್’ದ ಗಾತ್ರ',
'filehist-comment'    => 'ಕಮೆಂಟ್',
'imagelinks'          => 'ಫೈಲ್ ಲಿಂಕ್’ಲು',
'linkstoimage'        => 'ಈ ಫೈಲ್’ಗ್ ತಿರ್ತ್’ದ ಈ {{PLURAL:$1|ಪುಟ|$1 ಪುಟೊಲು}} ಲಿಂಕ್ ಕೊರ್ಪುಂಡು.',
'sharedupload'        => 'ಈ ಫೈಲ್’ನ್ ಮಸ್ತ್ ಜನ ಪಟ್ಟ್’ದುಲ್ಲೆರ್ ಅಂಚೆನೆ ಉಂದು ಮಸ್ತ್ ಪ್ರೊಜೆಕ್ಟ್’ಲೆಡ್ ಉಪಯೋಗಡುಪ್ಪು.',

# Random page
'randompage' => 'ಯಾದೃಚ್ಛಿಕ ಪುಟ',

# Statistics
'statistics' => 'ಅಂಕಿ ಅಂಶೊಲು',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|ಬೈಟ್|ಬೈಟ್‍ಲು}}',
'nmembers'      => '$1 {{PLURAL:$1|ಸದಸ್ಯೆ|ಸದಸ್ಯೆರ್}}',
'prefixindex'   => 'ಪೂರ್ವನಾಮೊಲ್ದ ಸೂಚಿಕೆ',
'newpages'      => 'ಪೊಸ ಪುಟೊಲು',
'move'          => 'ಮೂವ್(ಸ್ಥಳಾಂತರ) ಮಲ್ಪುಲೆ',
'movethispage'  => 'ಈ ಪುಟೊನು ಮೂವ್ ಮಲ್ಪುಲೆ',
'pager-newer-n' => '{{PLURAL:$1|ಪೊಸ ೧|ಪೊಸ $1}}',
'pager-older-n' => '{{PLURAL:$1|ಪರತ್ತ್ ೧|ಪರತ್ತ್ $1}}',

# Book sources
'booksources'               => 'ಪುಸ್ತಕೊಲ್ದ ಮೂಲ',
'booksources-search-legend' => 'ಪುಸ್ತಕೊದ ಮೂಲೊನು ನಾಡ್ಲ',
'booksources-go'            => 'ಪೋ',

# Special:Log
'log' => 'ದಾಖಲೆಲು',

# Special:AllPages
'allpages'       => 'ಪೂರಾ ಪೂಟೊಲು',
'alphaindexline' => '$1 ರ್ದ್ $2 ಗ್',
'allpagesfrom'   => 'ಇಂದೆರ್ದ್ ಶುರುವಾಪುನ ಪುಟೊಲೆನ್ ತೊಜ್ಪಾವು:',
'allpagesto'     => 'ಇಂದೆರ್ದ್ ಅಂತ್ಯ ಆಪುನ ಪುಟೊಲೆನ್ ತೊಜ್ಪಾವು:',
'allarticles'    => 'ಪೂರಾ ಲೇಖನೊಲು',
'allpagessubmit' => 'ಪೋ',

# Special:Log/newusers
'newuserlogpage'          => 'ಸದಸ್ಯ ರಚನೆ ಲಾಗ್',
'newuserlog-create-entry' => 'ಪೊಸ ಸದಸ್ಯೆರ್ನ ಎಕೌಂಟ್',

# Special:ListGroupRights
'listgrouprights-members' => '(ಸದಸ್ಯೆರ್ನ ಪಟ್ಟಿ)',

# E-mail user
'emailuser' => 'ಈ ಸದಸ್ಯೆರೆಗ್ ಇ-ಮೈಲ್ ಕಡಪುಡ್ಲೆ',

# Watchlist
'watchlist'         => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿ',
'mywatchlist'       => 'ಎನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿ',
'watch'             => 'ತೂಲೆ',
'watchthispage'     => 'ಈ ಪುಟೊನು ತೂಲೆ',
'unwatch'           => 'ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆಪ್ಪು',
'watchlist-options' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿ ಆಯ್ಕೆಲು',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೇರ್ಪಾವೊಂದುಂಡು...',
'unwatching' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿರ್ದ್ ದೆತ್ತೊಂದುಂಡು...',

# Delete
'actioncomplete' => 'ಕಾರ್ಯ ಸಂಪೂರ್ಣ',
'deletedarticle' => '"[[$1]]" ನೆನ್ನ್ ದೆತ್ತ್ ದಾ೦ಡ್',
'dellogpage'     => 'ಡಿಲೀಟ್ ಮಲ್ತಿನ ಫೈಲ್’ಲೆದ ದಾಖಲೆ',

# Rollback
'rollbacklink' => 'ಪಿರ ಪೋಲೆ',

# Protect
'protectlogpage'            => 'ಸಂರಕ್ಷಣೆ ದಿನಚರಿ',
'protectedarticle'          => '"[[$1]]" ಸಂರಕ್ಷಿತವಾದುಂಡು.',
'modifiedarticleprotection' => '"[[$1]]" ಪುಟೊತ ಸಂರಕ್ಷಣೆ ಮಟ್ಟ ಬದಲಾಂಡ್',

# Undelete
'undeletelink'     => 'ದುಂಬುದ ಆವೃತ್ತಿಗ್ ಪೋಲೆ',
'undeletedarticle' => '"[[$1]]" ನ್ ಪಿರಕನತ್’ನ್ಡ್',

# Namespace form on various pages
'namespace'      => 'ನೇಮ್-ಸ್ಪೇಸ್:',
'invert'         => 'ಆಯ್ಕೆನ್ ತಿರ್ಗಾಲೆ',
'blanknamespace' => '(ಮುಖ್ಯ)',

# Contributions
'contributions'       => 'ಸದಸ್ಯೆರ್ನ ಕಾಣಿಕೆಲು',
'contributions-title' => '$1 ಗ್ ಸದಸ್ಯೆರ್ನ ಕಾಣಿಕೆ',
'mycontris'           => 'ಎನ್ನ ಕಾಣಿಕೆಲು',
'contribsub2'         => '$1 ($2) ಗ್',
'uctop'               => ' (ಮಿತ್ತ್)',
'month'               => 'ಈ ತಿಂಗೊಲುರ್ದ್ (ಬೊಕ್ಕ ದುಂಬುದ):',
'year'                => 'ಈ ವರ್ಷೊರ್ದು (ಬೊಕ್ಕ ದುಂಬುದ):',

'sp-contributions-newbies'  => 'ಪೊಸ ಖಾತೆಲೆದ ಕಾಣಿಕೆಲೆನ್ ಮಾತ್ರ ತೊಜ್ಪಾವು',
'sp-contributions-blocklog' => 'ತಡೆಪತ್ತುನ ದಾಖಲೆ',
'sp-contributions-talk'     => 'ಪಾತೆರ',
'sp-contributions-search'   => 'ಕಾಣಿಕೆಲೆನ್ ನಾಡ್ಲೆ',
'sp-contributions-username' => 'ಐ.ಪಿ ವಿಳಾಸ ಅತ್ತ್’ನ್ಡ ಬಳಕೆದ ಪುದರ್:',
'sp-contributions-submit'   => 'ನಾಡ್',

# What links here
'whatlinkshere'            => 'ಇಡೆ ವಾ ಪುಟೊಲು ಲಿಂಕ್ ಕೊರ್ಪುಂಡು',
'whatlinkshere-title'      => '"$1" ಪುಟೊಗು ಲಿಂಕ್ ಕೊರ್ಪುನ ಪುಟೊಲು',
'whatlinkshere-page'       => 'ಪುಟ:',
'linkshere'                => "'''[[:$1]]'''ಗ್ ಈ ತಿರ್ತ್’ದ ಪುಟೊಲು ಲಿಂಕ್ ಕೊರ್ಪುಂಡು.",
'nolinkshere'              => "'''[[:$1]]''' ಗ್ ವಾ ಪುಟೊಲುಲಾ ಲಿಂಕ್ ಕೊರ್ಪುಜಿ.",
'isredirect'               => 'ಪುನರ್ನಿರ್ದೇಶನ ಪುಟ',
'istemplate'               => 'ಸೇರ್ಪಡೆ',
'isimage'                  => 'ಚಿತ್ರ ಕೊಂಡಿ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ದುಂಬುದ|ದುಂಬುದ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ಬೊಕ್ಕದ|ಬೊಕ್ಕದ $1}}',
'whatlinkshere-links'      => '← ಲಿಂಕ್’ಲು',
'whatlinkshere-hideredirs' => '$1 ಪುನರ್ನಿರ್ದೇಶನಗಳು',
'whatlinkshere-hidetrans'  => '$1 ಟ್ರಾನ್ಸ್’ಕ್ಲೂಶನ್ಸ್',
'whatlinkshere-hidelinks'  => '$1 ಕೊಂಡಿಲು',
'whatlinkshere-filters'    => 'ಅರಿಪೆಲು',

# Block/unblock
'blockip'                  => 'ಈ ಸದಸ್ಯೆರೆನ್ ಬ್ಲಾಕ್ ಮಲ್ಪುಲೆ',
'ipboptions'               => '2 ಗಂಟೆಲು:2 hours,1 ದಿನ:1 day,3 ದಿನೊಲು:3 days,1 ವಾರ:1 week,2 ವಾರೊಲು:2 weeks,1 ತಿಂಗೊಲು:1 month,3 ತಿಂಗೊಲು:3 months,6 ತಿಂಗೊಲು:6 months,1 ವರ್ಷ:1 year,ಅನಿರ್ಧಿಷ್ಟ:infinite',
'ipblocklist'              => 'ತಡೆಪತ್ತ್’ದಿನ ಐ.ಪಿ ವಿಳಾಸೊಲು ಅಂಚೆನೆ ಬಳಕೆದ ಪುದರ್’ಲು',
'blocklink'                => 'ಅಡ್ಡ ಪತ್ತ್’ಲೆ',
'unblocklink'              => 'ಅಡ್ಡನ್ ದೆಪ್ಪುಲೆ',
'change-blocklink'         => 'ಬ್ಲಾಕ್’ನ್ ಬದಲಾಲೆ',
'contribslink'             => 'ಕಾಣಿಕೆಲು',
'blocklogpage'             => 'ತಡೆಪತ್ತ್’ದ್’ನ ಸದಸ್ಯೆರ್ನ ದಿನಚರಿ',
'blocklogentry'            => '[[$1]] ಖಾತೆನ್ $2 $3 ಮುಟ್ಟ ತಡೆಪತ್ತ್’ದ್’ನ್ಡ್',
'unblocklogentry'          => '$1 ಖಾತೆನ್ ಅನ್-ಬ್ಲಾಕ್ ಮಲ್ತ್’ನ್ಡ್',
'block-log-flags-nocreate' => 'ಖಾತೆ ಸೃಷ್ಟಿನ್ ತಡೆಪತ್ತ್’ದ್’ನ್ಡ್',

# Move page
'movelogpage' => 'ಸ್ಥಳಾಂತರಿಕೆ ದಾಖಲೆ',
'revertmove'  => 'ದುಂಬುದ ಲೆಕೆ ಮಲ್ಪುಲೆ',

# Export
'export' => 'ಪುಟಲೆನ್ ರಫ್ತು ಮಲ್ಪುಲೆ',

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
'tooltip-ca-addsection'           => 'ಪೊಸ ಸೆಶನ್ನ್ ಶರು ಮಲ್ಪುಲೆ',
'tooltip-ca-viewsource'           => 'ಉಂದೊಂಜಿ ಸಂರಕ್ಷಿತ ಪುಟ.
ಇಂದೆತ ಮೂಲೊನು ಈರ್ ತೂವೊಲಿ.',
'tooltip-ca-history'              => 'ಈ ಪುಟೊತ ಪರತ್ತ್ ಆವೃತ್ತಿಲು',
'tooltip-ca-protect'              => 'ಈ ಪುಟೊನು ಸಂರಕ್ಷಣೆ ಮಲ್ಪುಲೆ',
'tooltip-ca-delete'               => 'ಈ ಪುಟೊನು ಡಿಲೀಟ್ ಮಲ್ಪುಲೆ',
'tooltip-ca-move'                 => 'ಈ ಪೂಟೊನು ಮೂವ್(ಸ್ಥಳಾಂತರ) ಮಲ್ಪುಲೆ',
'tooltip-ca-watch'                => 'ಈ ಪುಟೊನು ಇರೆನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗ್ ಸೆರ್ಪಾಲೆ',
'tooltip-ca-unwatch'              => 'ಈ ಪುಟೊನು ಇರೆನ ವೀಕ್ಷಣಾ ಪಟ್ಟಿರ್ದ್ ದೆಪ್ಪುಲೆ',
'tooltip-search'                  => '{{SITENAME}}ನ್ ನಾಡ್’ಲೆ',
'tooltip-search-go'               => 'ಉಂದುವೇ ಪುದರ್ದ ಪುಟ ಇತ್ತ್’ನ್ಡ ಅಡೆ ಪೋಲ',
'tooltip-search-fulltext'         => 'ಈ ಪಠ್ಯ ಉಪ್ಪುನಂಚಿನ ಪುಟೊಲೆನ್ ನಾಡ್’ಲ',
'tooltip-n-mainpage'              => 'ಮುಖ್ಯ ಪುಟೊನು ತೂಲೆ',
'tooltip-n-portal'                => 'ಪ್ರೊಜೆಕ್ಟ್’ದ ಬಗ್ಗೆ, ಈರ್ ದಾದ ಮಲ್ಪೊಲಿ, ಓಲು ಇಂದೆತ ಬಗ್ಗೆ ತೆರಿಯೊನೊಲಿ',
'tooltip-n-currentevents'         => 'ಪ್ರಸಕ್ತ ಘಟನೆಲ್ದ ಬಗ್ಗೆ ಹಿನ್ನೆಲೆ ಮಾಹಿತಿ ತೆರಿಯೊನ್ಲೆ',
'tooltip-n-recentchanges'         => 'ವಿಕಿಡ್ ದುಂಬುದ ಒಂತೆ ಸಮಯಡ್ ಆತಿನಂಚಿನ ಬದಲಾವಣೆಲ್ದ ಪಟ್ಟಿ',
'tooltip-n-randompage'            => 'ಯಾದೃಚ್ಛಿಕ ಪುಟವೊಂಜೇನ್ ತೊಜ್ಪಾವ್',
'tooltip-n-help'                  => 'ತೆರಿತೊನೆರೆ ಜಾಗ',
'tooltip-t-whatlinkshere'         => 'ಇಡೆ ಲಿಂಕ್ ಕೊರ್ಪುನಂಚಿನ ಪೂರ ವಿಕಿ ಪುಟೊಲ್ದ ಪಟ್ಟಿ',
'tooltip-t-recentchangeslinked'   => 'ಈ ಪುಟೊರ್ದು ಸಂಪರ್ಕ ಉಪ್ಪುನಂಚಿನ ಪುಟೊಲೆಡ್ ಇಂಚಿಪದ ಬದಲಾವಣೆಲು',
'tooltip-feed-rss'                => 'ಈ ಪುಟೊಗು ಆರ್.ಎಸ್.ಎಸ್ ಫೀಡ್',
'tooltip-feed-atom'               => 'ಈ ಪುಟೊಗು Atom ಫೀಡ್',
'tooltip-t-contributions'         => 'ಈ ಸದಸ್ಯೆರ್ನ ಕಾಣಿಕೆಲ್ದ ಪಟ್ಟಿನ್ ತೊಜ್ಪಾವು',
'tooltip-t-emailuser'             => 'ಈ ಸದಸ್ಯೆರೆಗ್ ಇ-ಮೇಲ್ ಕಡಪುಡ್ಲೆ',
'tooltip-t-upload'                => 'ಫೈಲ್’ನ್ ಅಪ್ಲೋಡ್ ಮಲ್ಪುಲೆ',
'tooltip-t-specialpages'          => 'ಪೂರ ವಿಷೇಶ ಪುಟೊಲ್ದ ಪಟ್ಟಿ',
'tooltip-t-print'                 => 'ಈ ಪುಟೊತ ಪ್ರಿಂಟ್ ಆವೃತ್ತಿ',
'tooltip-t-permalink'             => 'ಪುಟೊತ ಈ ಆವೃತ್ತಿಗ್ ಶಾಶ್ವತ ಲಿಂಕ್',
'tooltip-ca-nstab-main'           => 'ಮಾಹಿತಿ ಪುಟೊನು ತೂಲೆ',
'tooltip-ca-nstab-user'           => 'ಸದಸ್ಯೆರ್ನ ಪುಟೊನು ತೂಲೆ',
'tooltip-ca-nstab-special'        => 'ಉಂದೊಂಜಿ ವಿಶೇಷ ಪುಟ, ಇಂದೆನ್ ಈರ್ ಎಡಿಟ್ ಮಲ್ಪೆರೆ ಆಪುಜಿ',
'tooltip-ca-nstab-project'        => 'ಪ್ರೊಜೆಕ್ಟ್ ಪುಟೊನು ತೂಲೆ',
'tooltip-ca-nstab-image'          => 'ಫೈಲ್’ದ ಪುಟೊನು ತೂಲೆ',
'tooltip-ca-nstab-template'       => 'ಟೆಂಪ್ಲೇಟ್’ನ್ ತೂಲೆ',
'tooltip-ca-nstab-category'       => 'ವರ್ಗೊದ ಪುಟೊನು ತೂಲೆ',
'tooltip-minoredit'               => 'ಇಂದೆನ್ ಎಲ್ಯ ಬದಲಾವಣೆ ಪಂಡ್ದ್ ಗುರ್ತ ಮಲ್ಪುಲೆ',
'tooltip-save'                    => 'ಈರ್ ಮಲ್ತ್’ದಿನ ಬದಲಾವಣೆಲೆನ್ ಒರಿಪುಲೆ',
'tooltip-preview'                 => 'ಈರ್ ಮಲ್ತಿನ ಬದಲಾವಣೆತ ಮುನ್ನೋಟ - ಈ ಪುಟನ್ ಒರಿಪಾವುನ ದು೦ಬು ನೇನ್ ತೂಲೆ',
'tooltip-diff'                    => 'ಈ ಲೇಖನೊಗ್ ಮಲ್ತಿನ ಬದಲಾವಣೆಲೆನ್ ತೊಜ್ಪಾವ್',
'tooltip-compareselectedversions' => 'ಈ ಪುಟತ ಆಯ್ಕೆ ಮಲ್ತಿನ ರಡ್ಡ್ ಆವೃತ್ತಿದ ವ್ಯತ್ಯಾಸನ್ ತೂಲೆ',
'tooltip-watch'                   => 'ಈ ಪುಟನ್ ಈರ್ನ ತೂಪುನ ಪಟ್ಟಿಗ್ ಸೇರ್ಸಾಲೆ',
'tooltip-recreate'                => 'ಈ ಪುಟ ಇತ್ತೆ ಇಜ್ಜ೦ಡಲಾ ಐನ್ ಪಿರ ಮಲ್ಪ್',
'tooltip-upload'                  => 'ಅಪ್ಲೋಡ್ ಸುರು ಮಲ್ಪು',
'tooltip-rollback'                => '"Rollback", ಈ ಪುಟದ ಕರಿನ ಬದಾಲವಣೆಗ್ ಒ೦ಜಿ ಕ್ಲಿಕ್ ಡ್ ಕೊನೊಪು೦ಡು',
'tooltip-undo'                    => '"Undo" ಈ ಬದಲಾವಣೆನ್ ದೆತೊನುಜಿ ಬುಕ ಪ್ರಿವ್ಯೂ ಮೋಡ್ ಡ್ ಬದಲಾವಣೆ ಮಲ್ಪೆರ್ ಕೊನೊಪು೦ಡು. ಅ೦ಚೆನೆ ಸಮ್ಮರಿ ಡ್ ಬದಲಾವಣೆ ಗ್ ಕಾರಣ ಕೊರ್ರ್‍ಎ ಆಪು೦ಡು.',

# Browsing diffs
'previousdiff' => '← ದುಂಬುದ ಸಂಪಾದನೆ',
'nextdiff'     => 'ಪೊಸ ಎಡಿಟ್ →',

# Media information
'file-info-size' => '$1 × $2 ಪಿಕ್ಸೆಲ್, ಫೈಲ್’ದ ಗಾತ್ರ: $3, MIME ಪ್ರಕಾರ: $4',
'file-nohires'   => '<small>ಇಂದೆರ್ದ್ ಜಾಸ್ತಿ ವಿವರವಾಯಿನ ನೋಟ ಇಜ್ಜಿ.</small>',
'svg-long-desc'  => 'ಎಸ್.ವಿ.ಜಿ ಫೈಲ್, ಸುಮಾರಾದ್ $1 × $2 ಪಿಕ್ಸೆಲ್, ಫೈಲ್’ದ ಗಾತ್ರ: $3',
'show-big-image' => 'ಪೂರ್ತಿ ರೆಸೊಲ್ಯೂಶನ್',

# Bad image list
'bad_image_list' => 'ವ್ಯವಸ್ಥೆದ ಆಕಾರ ಈ ರೀತಿ ಉಂಡು:

ಪಟ್ಟಿಡುಪ್ಪುನಂಚಿನ ದಾಖಲೆಲೆನ್ (* ರ್ದ್ ಶುರು ಆಪುನ ಸಾಲ್’ಲು) ಮಾತ್ರ ಪರಿಗಣನೆಗ್ ದೆತೊನೆರಾಪುಂಡು.
ಪ್ರತಿ ಸಾಲ್’ದ ಶುರುತ ಲಿಂಕ್ ಒಂಜಿ ದೋಷ ಉಪ್ಪುನಂಚಿನ ಫೈಲ್’ಗ್ ಲಿಂಕಾದುಪ್ಪೊಡು.
ಅವ್ವೇ ಸಾಲ್’ದ ಶುರುತ ಪೂರಾ ಲಿಂಕ್’ಲೆನ್ ಪರಿಗನೆರ್ದ್ ದೆಪ್ಪೆರಾಪುಂಡು, ಪಂಡ ಓವು ಪುಟೊಲೆಡ್ ಫೈಲ್’ದ ಬಗ್ಗೆ ಬರ್ಪುಂಡೋ ಔಲು.',

# Metadata
'metadata'          => 'ಮೇಲ್ದರ್ಜೆ ಮಾಹಿತಿ',
'metadata-help'     => 'ಈ ಫೈಲ್’ಡ್ ಜಾಸ್ತಿ ಮಾಹಿತಿ ಉಂಡು. ಪ್ರಾಯಶಃ ಫೈಲ್’ನ್ ಉಂಡು ಮಲ್ಪೆರೆ ಉಪಯೋಗ ಮಲ್ತಿನ ಡಿಜಿಟಲ್ ಕ್ಯಾಮೆರರ್ದ್ ಅತ್ತ್’ನ್ಡ ಸ್ಕ್ಯಾನರ್ ರ್ದ್ ಈ ಮಾಹಿತಿ ಬೈದ್’ನ್ಡ್.
ಮೂಲಪ್ರತಿರ್ದ್ ಈ ಫೈಲ್ ಬದಲಾದಿತ್ತ್’ನ್ಡ, ಈ ಮಾಹಿತಿ ಬದಲಾತಿನ ಫೈಲ್’ದ ವಿವರೊಲೆಗ್ ಸರಿಯಾದ್ ಹೊಂದಂದೆ ಉಪ್ಪು.',
'metadata-expand'   => 'ವಿಸ್ತಾರವಾಯಿನ ವಿವರೊಲೆನ್ ತೊಜ್ಪಾವು',
'metadata-collapse' => 'ವಿಸ್ತಾರವಾಯಿನ ವಿವರೊಲೆನ್ ದೆಂಗಾವು',
'metadata-fields'   => 'ಈ ಸಂದೇಶೊಡು ಪಟ್ಟಿ ಮಲ್ತಿನಂಚಿನ EXIF ಮೆಟಡೇಟ ಮಾಹಿತಿನ್ ಚಿತ್ರ ಪುಟೊಕು ಸೇರ್ಪಾಯೆರೆ ಆವೊಂದುಂಡು. ಪುಟೊಟು ಮೆಟಡೇಟ ಮಾಹಿತಿದ ಪಟ್ಟಿನ್ ದೆಪ್ಪುನಗ ಉಂದು ತೋಜುಂಡು.
ಒರಿದನವು ಮೂಲಸ್ಥಿತಿಟ್ ಅಗೋಚರವಾದುಪ್ಪುಂಡು.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'ಬಾಹ್ಯ(ಪಿದಯಿದ) ತಂತ್ರಾಶೊನು ಉಪಯೋಗ ಮಲ್ತ್’ದ್ ಇಂದೆನ್ ಸಂಪಾದನೆ ಮಲ್ಪುಲೆ',
'edit-externally-help' => 'ನನಲ ಮಾಹಿತಿಗ್ [http://www.mediawiki.org/wiki/Manual:External_editors ಸೆಟ್-ಅಪ್ ನಿರ್ದೇಶನೊಲೆನ್] ತೂಲೆ.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ಪೂರ',
'namespacesall' => 'ಪೂರಾ',
'monthsall'     => 'ಪೂರಾ',

# Watchlist editing tools
'watchlisttools-edit' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿನ್ ತೂಲೆ ಬೊಕ್ಕ ಎಡಿಟ್ ಮಲ್ಪುಲೆ',

# Special:SpecialPages
'specialpages' => 'ವಿಷೇಶ ಪುಟೊಲು',

);
