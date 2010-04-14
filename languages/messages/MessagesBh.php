<?php
/** Bihari (भोजपुरी)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ganesh
 */

$digitTransformTable = array(
	'0' => '०', # &#x0966;
	'1' => '१', # &#x0967;
	'2' => '२', # &#x0968;
	'3' => '३', # &#x0969;
	'4' => '४', # &#x096a;
	'5' => '५', # &#x096b;
	'6' => '६', # &#x096c;
	'7' => '७', # &#x096d;
	'8' => '८', # &#x096e;
	'9' => '९', # &#x096f;
);

$messages = array(
# Dates
'january'   => 'जनवरी',
'february'  => 'फरवरी',
'march'     => 'मार्च',
'april'     => 'अप्रिल',
'may_long'  => 'मई',
'june'      => 'जून',
'july'      => 'जुलाई',
'august'    => 'अगस्त',
'september' => 'सितम्बर',
'october'   => 'अक्टूबर',
'november'  => 'नवम्बर',
'december'  => 'दिसम्बर',

'help'             => 'मदद',
'newpage'          => 'नया पन्ना',
'talkpagelinktext' => 'बात-चीत',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} के बारे में',
'aboutpage'            => 'Project:बारे में',
'copyright'            => 'सामग्री $1 के तहत उपलब्ध बा।',
'copyrightpage'        => '{{ns:project}}:लेखाधिकार',
'currentevents'        => 'हाल के घटना',
'currentevents-url'    => 'Project:हाल के घटना',
'disclaimers'          => 'अस्विकरण',
'disclaimerpage'       => 'Project:सामान्य अस्विकरण',
'mainpage'             => 'पहिलका पन्ना',
'mainpage-description' => 'पहिलका पन्ना',
'privacy'              => 'गोपनीयता निती',
'privacypage'          => 'Project:गोपनीयता निती',

'badaccess'        => 'अनुमति त्रुटी',
'badaccess-group0' => 'रउआ जवन कार्रवाई खातिर अनुरोध कईले बानी उ के निष्पादन करे के अनुमति नईखे।',

'red-link-title' => '$1 (पन्ना मौजूद नईखे)।',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'पन्ना',
'nstab-user'    => 'प्रयोगकर्ता पन्ना',
'nstab-media'   => 'मिडीया पन्ना',
'nstab-special' => 'विशेष पन्ना',
'nstab-project' => 'परियोजना पन्ना',

# Login and logout pages
'yourname'     => 'प्रयोगकर्ता नाम',
'yourpassword' => 'गुप्त शब्द',
'login'        => 'लाँग इन',
'logout'       => 'लाँग आउट',

# Search results
'searchresults-title' => 'खोज परिणाम',

# Recent changes linked
'recentchangeslinked' => 'सम्बन्धित बदलाव',

# Namespace form on various pages
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions'       => 'प्रयोगकर्ता योगदान',
'contributions-title' => ' $1 खातिर प्रयोगकर्ता योगदान',
'mycontris'           => 'हमार योगदान',
'nocontribs'          => 'ई मानदंड से मिलत जुलत कौनो बदलाव ना मिलल।',
'uctop'               => '(शीर्ष)',
'month'               => 'महिना से (आ उ से पहिले):',
'year'                => 'साल से (आ उ से पहिले):',

'sp-contributions-newbies'        => 'खाली नया खाता के योगदान देखीं।',
'sp-contributions-newbies-sub'    => 'नया खाता खातिर',
'sp-contributions-newbies-title'  => 'नया खाता खातिर प्रयोगकर्ता के योगदान।',
'sp-contributions-blocklog'       => 'निष्क्रीय लौग',
'sp-contributions-deleted'        => 'नष्ट प्रयोगकर्ता के योगदान।',
'sp-contributions-logs'           => 'लौग',
'sp-contributions-talk'           => 'बात-चीत',
'sp-contributions-userrights'     => 'प्रयोगकर्ता अधिकार प्रबन्धन',
'sp-contributions-blocked-notice' => 'ई प्रयोगकर्ता के ई समय निष्क्रीय करल गईल बा।
नविनतम नष्ट लौग प्रविष्टी उद्धरण खातिर निचे दिहल बा:',

# Tooltip help for the actions
'tooltip-t-print'     => 'ई पन्ना के छापे लायक संस्करण।',
'tooltip-t-permalink' => 'ई पन्ना के संसोधन खातिर स्थायी लिंक।',

);
