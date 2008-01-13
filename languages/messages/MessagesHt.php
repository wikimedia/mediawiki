<?php
/** Haitian (Kreyòl ayisyen)
 *
 * @addtogroup Language
 *
 * @author Masterches
 * @author Siebrand
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_MEDIA          => 'Medya',
	NS_SPECIAL        => 'Espesyal',
	NS_MAIN           => '',
	NS_TALK           => 'Diskite',
	NS_USER           => 'Itilizatè',
	NS_USER_TALK      => 'Diskisyon_Itilizatè',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Diskisyon_$1',
	NS_IMAGE          => 'Imaj',
	NS_IMAGE_TALK     => 'Diskisyon_Imaj',
	NS_MEDIAWIKI      => 'MedyaWiki',
	NS_MEDIAWIKI_TALK => 'Diskisyon_MedyaWiki',
	NS_TEMPLATE       => 'Modèl',
	NS_TEMPLATE_TALK  => 'Diskisyon_Modèl',
	NS_HELP           => 'Èd',
	NS_HELP_TALK      => 'Diskisyon_Èd',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Diskisyon_Kategori'
);

$linkTrail = '/^([a-zàèòÀÈÒ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'       => 'Souliyen lyen yo :',
'tog-highlightbroken' => 'Afiche <a href="" class="new">nan koulè wouj</a> lyen yo ki ap mene nan paj ki pa egziste (oubyen : tankou <a href="" class="internal">?</a>)',
'tog-justify'         => 'Aliyen paragraf yo',
'tog-hideminor'       => 'Kache tout modifikasyon resan yo ki pa enpòtan',
'tog-extendwatchlist' => 'Itilize lis swivi ki miyò a',
'tog-usenewrc'        => 'Itilize lis swivi ki miyò a (JavaScript)',
'tog-numberheadings'  => 'Nimewote otomatiman tit yo',
'tog-showtoolbar'     => 'Montre panèl meni modifikasyon an',
'tog-watchcreations'  => 'Ajoute paj yo ke mwen ap kreye nan lis swivi mwen.',

'underline-always' => 'Toujou',
'underline-never'  => 'Jamè',

'skinpreview' => '(Voye kout zye)',

# Dates
'sunday'        => 'dimanch',
'monday'        => 'lendi',
'tuesday'       => 'madi',
'wednesday'     => 'mèkredi',
'thursday'      => 'jedi',
'friday'        => 'vandredi',
'saturday'      => 'samdi',
'sun'           => 'dim',
'mon'           => 'len',
'tue'           => 'mad',
'wed'           => 'mèr',
'thu'           => 'jed',
'fri'           => 'van',
'sat'           => 'sam',
'january'       => 'janvye',
'february'      => 'fevriye',
'march'         => 'mas',
'april'         => 'avril',
'may_long'      => 'me',
'june'          => 'jen',
'july'          => 'jiyè',
'august'        => 'out',
'september'     => 'septanm',
'october'       => 'oktòb',
'november'      => 'novanm',
'december'      => 'desanm',
'january-gen'   => 'janvye',
'february-gen'  => 'fevriye',
'march-gen'     => 'mas',
'april-gen'     => 'avril',
'may-gen'       => 'me',
'june-gen'      => 'jen',
'july-gen'      => 'jiyè',
'august-gen'    => 'out',
'september-gen' => 'septanm',
'october-gen'   => 'oktòb',
'november-gen'  => 'novanm',
'december-gen'  => 'desanm',
'jan'           => 'jan',
'feb'           => 'fev',
'mar'           => 'mas',
'apr'           => 'avr',
'may'           => 'me',
'jun'           => 'jen',
'jul'           => 'jiy',
'aug'           => 'out',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Bits of text used by many pages
'subcategories' => 'Pitit kategori yo',

'about'         => 'Apwopo',
'article'       => 'Atik',
'qbpageoptions' => 'Opsyon Paj sa a',
'mytalk'        => 'Paj diskisyon mwen an',

'errorpagetitle'   => 'Erè',
'tagline'          => 'Yon atik de {{SITENAME}}.',
'help'             => 'Èd',
'search'           => 'Chache',
'searchbutton'     => 'Fouye',
'searcharticle'    => 'Wè',
'history'          => 'istorik paj an',
'printableversion' => 'Vèsyon pou enprime',
'permalink'        => 'Lyen pou tout tan',
'edit'             => 'Modifye',
'talkpagelinktext' => 'Diskite',
'personaltools'    => 'Zouti pèsonèl yo',
'talk'             => 'Diskisyon',
'views'            => 'Afichay yo',
'toolbox'          => 'Bwat zouti yo',
'redirectedfrom'   => '(Redirije depi $1)',
'jumpto'           => 'Ale nan:',
'jumptonavigation' => 'Navigasyon',
'jumptosearch'     => 'Fouye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'Apwopo {{SITENAME}}',
'aboutpage'      => 'Project:Apwopo',
'copyrightpage'  => '{{ns:project}}:Copyright',
'disclaimers'    => 'Avètisman',
'disclaimerpage' => 'Project:Avètisman jeneral yo',
'edithelp'       => 'Edite paj èd an',
'mainpage'       => 'Akèy',
'privacy'        => 'Politik konfidansyalite',
'privacypage'    => 'Project:Konfidansyalite',

'retrievedfrom'   => 'Rekipere depi « $1 »',
'editsection'     => 'modifye',
'editsectionhint' => 'Modifye seksyon : $1',
'toc'             => 'Kontni yo',
'showtoc'         => 'montre',
'hidetoc'         => 'kache',
'site-rss-feed'   => 'Flow RSS depi $1',
'site-atom-feed'  => 'Flow Atom depi $1',

# Login and logout pages
'userlogin'  => 'Kreye yon kont oubyen konekte ou',
'userlogout' => 'Dekoneksyon',

# Edit pages
'summary'     => 'Somè&nbsp;',
'showpreview' => 'Previzyalizasyon',

# History pages
'revisionasof'     => 'Vèsyon jou $1',
'previousrevision' => '← Vèsyon presedan',
'cur'              => 'kounye a',
'last'             => 'dènye',

# Diffs
'lineno'   => 'Liy $1 :',
'editundo' => 'Defè, anile',

# Search results
'powersearch' => 'Fouye',

# Preferences page
'mypreferences' => 'Preferans yo',

# Recent changes
'rcnote'          => 'Men {{PLURAL:$1|dènye modifikasyon an|dènye $1 modifikasyon yo}} depi {{PLURAL:$2|dènye jou|<b>$2</b> dènye jou yo}}, tankou $3.',
'rcshowhideminor' => '$1 modifiksayon yo ki pa enpòtan',
'rclinks'         => 'Afiche dènye $1 modifikasyon ki fèt nan $2 dènye jou sa yo<br />$3.',
'diff'            => 'diferans',
'hist'            => 'istorik',
'hide'            => 'Kache',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked' => 'Swivi pou lyen yo',

# Upload
'upload' => 'Chaje fichye an',

# Image list
'filehist'   => 'Istorik fichye a',
'imagelinks' => 'Paj yo ki genyen imaj an',

# Random page
'randompage' => 'Yon paj o aza',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|okte|okte}}',
'specialpages' => 'Paj espesyal yo',

'alphaindexline' => '$1 jiska $2',

# Watchlist
'mywatchlist' => 'Lis swivi',
'watch'       => 'Swiv',
'unwatch'     => 'Pa swiv ankò',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Swiv...',
'unwatching' => 'Fini swiv paj sa a...',

# Namespace form on various pages
'blanknamespace' => '(Prensipal)',

# Contributions
'mycontris' => 'Kontribisyon mwen yo',

# What links here
'whatlinkshere'       => 'Paj ki lye nan paj sa a',
'whatlinkshere-links' => '← lyen yo',

# Block/unblock
'blocklink'    => 'Bloke',
'contribslink' => 'Kontribisyon yo',

# Thumbnails
'thumbnail-more' => 'Agrandi',

# Tooltip help for the actions
'tooltip-pt-userpage'     => 'Paj itilizatè mwen an',
'tooltip-pt-mytalk'       => 'Paj diskisyon mwen an',
'tooltip-pt-preferences'  => 'Preferans mwen yo',
'tooltip-pt-watchlist'    => 'Lis paj ou ap swiv yo',
'tooltip-pt-mycontris'    => 'Lis kontribisyon mwen yo',
'tooltip-pt-login'        => 'Ou mèt idantifye ou mè tande ke li pa nesesè.',
'tooltip-pt-logout'       => 'Dekonekte ou',
'tooltip-ca-talk'         => 'Diskisyon apwopo kontni paj sa a',
'tooltip-ca-edit'         => 'Ou mèt modifye paj sa a. Souple, previzyalize mesaj ou an anvan ou anrejistre li.',
'tooltip-search'          => 'Fouye nan wiki sa a',
'tooltip-n-mainpage'      => 'Vizite paj prensipal an',
'tooltip-n-portal'        => 'Apwopo pwojè a, sa ou kapab fè, ki kote ou mèt twouve kèk bagay',
'tooltip-n-currentevents' => 'Twouve enfòmasyon yo anlè evènman ki ap fèt kounye a',
'tooltip-n-recentchanges' => 'Lis modifikasyon ki fèk fèt nan wiki a',
'tooltip-n-randompage'    => 'Afiche yon paj o aza',
'tooltip-n-help'          => 'Èd',
'tooltip-n-sitesupport'   => 'Soutni pwojè a',
'tooltip-t-whatlinkshere' => 'Lis paj yo ki lye ak paj sa a',
'tooltip-t-upload'        => 'Chaje yon imaj oubyen yon fichye medya anlè sèvè a',
'tooltip-t-specialpages'  => 'Lis tout paj espesyal yo',

# Bad image list
'bad_image_list' => 'Fòma a, se konsa li ye :

Sèl liy ki komanse pa * ke konte.  Premye lyen nan liy an se sa ki ap mene kote yon move imaj.
Lòt lyen yo nan menm liy an ke ap konsidere tankou eksepsyon, pa egzanp atik nan kilès yo imaj an dwèt parèt.',

);
