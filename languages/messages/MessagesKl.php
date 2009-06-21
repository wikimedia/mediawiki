<?php
/** Kalaallisut (Kalaallisut)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaare
 * @author Piivaat
 * @author לערי ריינהארט
 */

$fallback = 'da';

$namespaceNames = array(
	NS_SPECIAL          => 'Immikkut',
	NS_TALK             => 'Oqallinneq',
	NS_USER             => 'Atuisoq',
	NS_USER_TALK        => 'Atuisup oqalliffia',
	NS_PROJECT_TALK     => '$1ip oqalliffia',
	NS_FILE             => 'Fiileq',
	NS_FILE_TALK        => 'Fiilip oqalliffia',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Mediawikip oqalliffia',
	NS_TEMPLATE         => 'Ilisserut',
	NS_TEMPLATE_TALK    => 'Ilisserummi oqallinneq',
	NS_HELP             => 'Ikiuutit',
	NS_HELP_TALK        => 'Ikiuutini oqallinneq',
	NS_CATEGORY         => 'Sumut atassuseq',
	NS_CATEGORY_TALK    => 'Sumut atassusermi oqallinneq',
);

$namespaceAliases = array(
	'Speciel' => NS_SPECIAL,
	'Diskussion' => NS_TALK,
	'Bruger' => NS_USER,
	'Brugerdiskussion' => NS_USER_TALK,
	'$1-diskussion' => NS_PROJECT_TALK,
	'Fil' => NS_FILE,
	'Fildiskussion' => NS_FILE_TALK,
	'Billede' => NS_FILE,
	'Billeddiskussion' => NS_FILE_TALK,
	'MediaWiki' => NS_MEDIAWIKI,
	'MediaWiki-diskussion' => NS_MEDIAWIKI_TALK,
	'Skabelon' => NS_TEMPLATE,
	'Skabelondiskussion' => NS_TEMPLATE_TALK,
	'Hjælp' => NS_HELP,
	'Hjælp-diskussion' => NS_HELP_TALK,
	'Kategori' => NS_CATEGORY,
	'Kategoridiskussion' => NS_CATEGORY_TALK
);

$messages = array(
# Dates
'sunday'        => 'sapaat',
'monday'        => 'ataasinngorneq',
'tuesday'       => 'marlunngorneq',
'wednesday'     => 'pingasunngorneq',
'thursday'      => 'sisamanngorneq',
'friday'        => 'tallimanngorneq',
'saturday'      => 'arfininngorneq',
'sun'           => 'sap',
'mon'           => 'ata',
'tue'           => 'mar',
'wed'           => 'pin',
'thu'           => 'sis',
'fri'           => 'tal',
'sat'           => 'arf',
'january'       => 'januari',
'february'      => 'februari',
'march'         => 'martsi',
'april'         => 'aprili',
'may_long'      => 'maji',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'augustusi',
'september'     => 'septemberi',
'october'       => 'oktoberi',
'november'      => 'novemberi',
'december'      => 'decemberi',
'january-gen'   => 'Januaari',
'february-gen'  => 'Februaari',
'march-gen'     => 'Marsi',
'april-gen'     => 'Apriili',
'may-gen'       => 'Maaji',
'june-gen'      => 'Juuni',
'july-gen'      => 'Juuli',
'august-gen'    => 'Aggusti',
'september-gen' => 'Septembari',
'october-gen'   => 'Oktobari',
'november-gen'  => 'Novembari',
'december-gen'  => 'Decembari',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

'about'  => 'Pillugu',
'cancel' => 'Sussa',

# Cologne Blue skin
'qbedit' => 'Aaqqissuutiguk',

'errorpagetitle'   => 'Kukkuneq',
'tagline'          => 'Matumannga {{SITENAME}}',
'help'             => 'Ikiortissamik',
'search'           => 'Ujarlerit',
'searchbutton'     => 'Ujarlerit',
'go'               => 'Ikunnarit',
'searcharticle'    => 'Tassunngarit',
'history'          => 'Oqaluttuassartaa',
'history_short'    => 'Oqaluttuassartaa',
'edit'             => 'Aaqqissuuguk',
'protect'          => 'Illersorpaa',
'talkpagelinktext' => 'Oqallinneq',
'talk'             => 'Oqallinneq',
'toolbox'          => 'Sannataasivik',
'otherlanguages'   => 'Oqaatsit allat',
'jumptonavigation' => 'navigationi',
'jumptosearch'     => 'ujarlerit',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} pillugu',
'aboutpage'            => 'Project:Pillugu',
'currentevents'        => 'Maannakkut pisut',
'edithelp'             => 'Ikiuutit',
'edithelppage'         => 'Help:Aaqqissuussineq',
'helppage'             => 'Help:Ikiuutit',
'mainpage'             => 'Saqqaa',
'mainpage-description' => 'Saqqaa',
'portal'               => 'Allaatiginnittartup saqqai',

'editsection'     => 'aaqqissuuguk',
'editold'         => 'aaqqissuuguk',
'editsectionhint' => 'Aaqqissuuguk immikkoortoq: $1',
'site-rss-feed'   => '$1 RSS Feed',
'site-atom-feed'  => '$1 Atom Feed',
'page-rss-feed'   => '"$1" RSS Feed',
'red-link-title'  => '$1 (Qupperneq suli allaffigineqanngilaq)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-image'    => 'Assiliaq',
'nstab-template' => 'Ilisserut',
'nstab-help'     => 'Ikiuutit',
'nstab-category' => 'Sumut atassuseq',

# Login and logout pages
'yourname'                   => 'Pineqartoqateq:',
'acct_creation_throttle_hit' => 'Konto-mik pilersitsereersimagavit pilersitseqqissinnaanngilatit, IP-adressit malillugu.
Taamaattumik maannakkorpiaq kontomik pilersitsisinnaanngilatit.',

# Edit pages
'summary'      => 'Qanoq issusersiuineq:',
'savearticle'  => 'Toqqoruk',
'preview'      => 'Isikkua',
'showpreview'  => 'Isikkua takuuk',
'showdiff'     => 'Allannguutit',
'accmailtitle' => 'Password-i nassiunneqarsimavoq.',
'accmailtext'  => 'Password-i "$1" $2-mut nassiunneqarsimavoq.',
'newarticle'   => '(Nuutaq)',
'previewnote'  => 'Eqqaamallugu isikkua takutinneqaannarpoq, toqqorneqanngilaq suli!',

# Search results
'powersearch' => 'Ujarlerit',

# Recent changes
'recentchanges'   => 'Allannguutit kingulliit',
'hist'            => 'hist',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked'         => 'Allannguutit naleqqiussat',
'recentchangeslinked-feed'    => 'Allannguutit naleqqiussat',
'recentchangeslinked-toolbox' => 'Allannguutit naleqqiussat',

# Upload
'upload' => 'Læg en fil op',

# File description page
'file-anchor-link' => 'Assiliaq',
'filehist-user'    => 'Pineqartoq',

# MIME search
'mimesearch' => 'MIME ujaarlerit',

# Random page
'randompage' => 'Nalaatsorluni atuagassianut ilanngutassiaq',

# Statistics
'statistics' => 'Kisitsisinngorlugit paasissutissat',

# Miscellaneous special pages
'move' => 'Nuunneq',

# Special:Log
'specialloguserlabel' => 'Pineqartoq:',

# Special:AllPages
'allpagessubmit' => 'Tassunngarit',

# Watchlist
'addedwatch'     => 'Nakkutilliinermi allattorsimaffimmut ilanngunneqarsimavoq',
'addedwatchtext' => "Qupperneq \"[[:\$1]]\" ilanngunneqarsimavoq [[Special:Watchlist|nakkutilliinermut allattorsimaffimmut]] ilanngunneqarsimavoq. Matumani quppernermi siunissami allannguutit, aammalu oqallinnermi qupperneq, maani saqqummersinneqassapput, quppernerlu '''erseqqissagaasoq''' inisseqqassalluni [[Special:RecentChanges|allattorsimaffik kingullermi allannguutinik imalik]] ajornannginnerussammat nassaariniarnissaanut.

Qupperneq nakkutilliinermi allattorsimaffik kingusinnerusukkut piissagukku, taava quppernerup sinaatungaani \"Nakkutilliinermi allattorsimaffik peeruk\" tooruk.",
'watch'          => 'Ersippoq',
'unwatch'        => 'Ersitsinnagu',

# Delete
'actioncomplete' => 'Naammassineqareersimavoq',

# Protect
'prot_1movedto2' => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',

# Contributions
'uctop' => '(kaarfa)',

'sp-contributions-talk' => 'Oqallinneq',

# What links here
'whatlinkshere' => 'Suna maangamut innersuussisoq',

# Block/unblock
'blocklink' => 'aporfeqarneq',

# Move page
'1movedto2'       => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',
'1movedto2_redir' => '[[$1]] nuunneqarsimavoq [[$2]]-mut adresse-ia aqqutigalugu allanngortillugu',

# Tooltip help for the actions
'tooltip-search' => 'Ujaarlerit {{SITENAME}}',

# Special:SpecialPages
'specialpages' => 'Quppernerit immikkut ittut',

);
