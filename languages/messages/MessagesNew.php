<?php
/** Newari (नेपाल भाषा)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 */

$namespaceNames = array(
	NS_MEDIA            => 'माध्यम',
	NS_SPECIAL          => 'विशेष',
	NS_MAIN             => '',
	NS_TALK             => 'खँलाबँला',
	NS_USER             => 'छ्येलेमि',
	NS_USER_TALK        => 'छ्येलेमि_खँलाबँला',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_खँलाबँला',
	NS_IMAGE            => 'किपा',
	NS_IMAGE_TALK       => 'किपा_खँलाबँला',
	NS_MEDIAWIKI        => 'मिडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकि_खँलाबँला',
	NS_HELP             => 'ग्वाहालि',
	NS_HELP_TALK        => 'ग्वाहालि_खँलाबँला',
	NS_CATEGORY         => 'पुचः',
	NS_CATEGORY_TALK    => 'पुचः_खँलाबँला'
);

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
'about'      => 'विषयक',
'mypage'     => 'जिगु पौ',
'mytalk'     => 'जिगु खं',
'navigation' => 'परिवहन',

'search'           => 'मालादिसं',
'history_short'    => 'इतिहास',
'printableversion' => 'ध्वायेज्युगु संस्करण',
'print'            => 'ध्वानादिसँ',
'edit'             => 'सम्पादन',
'editthispage'     => 'थ्व पौ सम्पादन यानादिसं',
'specialpage'      => 'विषेश पौ',
'personaltools'    => 'निजी ज्याब्व',
'talk'             => 'खँलाबँला',
'toolbox'          => 'ज्याब्व सन्दुक',
'projectpage'      => 'ज्याखँ पौ क्येनादिसँ',
'otherlanguages'   => 'मेमेगु भाषाय्',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage'   => 'मू पौ',
'portal'     => 'सामाजिक दबु',
'portal-url' => '{{ns:project}}:सामाजिक दबु',
'privacy'    => 'दुबिस्ता नियम',

'ok'              => 'ज्यु',
'editsection'     => 'सम्पादन',
'editold'         => 'सम्पादन',
'editsectionhint' => 'खण्ड सम्पादन: $1',

# Login and logout pages
'userlogout'     => 'पिने झासं',
'createaccount'  => 'खाता चायेकादिसं',
'accountcreated' => 'खाता न्ह्येथन',

# Edit pages
'savearticle' => 'पौ मुंकादिसं',
'preview'     => 'स्वयादिसं',
'newarticle'  => '(न्हु)',

# Search results
'powersearch' => 'मालादिसँ',

# Preferences page
'mypreferences' => 'जिगु प्राथमिकता',

# Recent changes
'recentchanges' => 'न्हुगु हिलेज्या',

# Upload
'upload' => 'फाइल अपलोड',

# Statistics
'statistics' => 'तथ्यांक',

# Miscellaneous special pages
'allpages'          => 'सकल पौत',
'randompage'        => 'छगु च्वसुइ येंकादिसं',
'specialpages'      => 'विषेश पौत:',
'newpages-username' => 'छ्येलेमि नां:',

# Special:Allpages
'nextpage'    => 'मेगु पौ ($1)',
'allarticles' => 'सकल च्वसुत',

# Restrictions (nouns)
'restriction-edit' => 'सम्पादन',

# Namespace form on various pages
'namespace'      => 'नेमस्पेस:',
'blanknamespace' => '(मू)',

# Contributions
'mycontris' => 'जिगु योगदान',

# What links here
'whatlinkshere' => 'थन छु स्वाई',

# Attribution
'and'    => 'व',
'others' => 'मेमेगु',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'सकल',

# Auto-summaries
'autosumm-new' => 'न्हुगु पौ: $1',

);
