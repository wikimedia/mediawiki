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
'newwindow'     => '(ouvè nan yon dòt fenèt)',
'cancel'        => 'Anile',
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
'edithelppage'   => 'Èd:koman ou ap modifye yon paj',
'mainpage'       => 'Akèy',
'privacy'        => 'Politik konfidansyalite',
'privacypage'    => 'Project:Konfidansyalite',

'retrievedfrom'   => 'Rekipere depi « $1 »',
'editsection'     => 'modifye',
'editold'         => 'modifye',
'editsectionhint' => 'Modifye seksyon : $1',
'toc'             => 'Kontni yo',
'showtoc'         => 'montre',
'hidetoc'         => 'kache',
'site-rss-feed'   => 'Flow RSS depi $1',
'site-atom-feed'  => 'Flow Atom depi $1',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-image' => 'Fichye',

# General errors
'viewsource' => 'Wè kòd tèks sa a',

# Login and logout pages
'userlogin'  => 'Kreye yon kont oubyen konekte ou',
'userlogout' => 'Dekoneksyon',

# Edit page toolbar
'bold_sample'     => 'Tèks fonse',
'bold_tip'        => 'Tèks fonse',
'italic_sample'   => 'Tèks italik',
'italic_tip'      => 'Tèks italik',
'link_sample'     => 'Lyen pou tit an',
'link_tip'        => 'Lyen andidan',
'extlink_sample'  => 'http://www.example.com yon tit pou lyen an',
'extlink_tip'     => 'Lyen dewò (pa blye prefiks http:// an)',
'headline_sample' => 'Tèks pou tit',
'headline_tip'    => 'Sou-tit nivo 2',
'math_sample'     => 'Antre fòmil ou an isit',
'math_tip'        => 'Fòmil matematik (LaTeX)',
'nowiki_sample'   => 'Antre tèks ki pa fòmate a',
'nowiki_tip'      => 'Pa konte sentaks wiki an',
'image_tip'       => 'Imaj an mete',
'media_tip'       => 'Lyen pou yon fichye medya',
'sig_tip'         => 'Siyati ou ak dat an',
'hr_tip'          => 'Liy orizontal (pa abize)',

# Edit pages
'summary'            => 'Somè&nbsp;',
'subject'            => 'Sijè/tit',
'minoredit'          => 'Modifikasyon sa a pa enpòtan',
'watchthis'          => 'Swiv paj sa a',
'showpreview'        => 'Previzyalizasyon',
'showdiff'           => 'Montre chanjman yo',
'anoneditwarning'    => "'''Pòte atansyon :''' ou pa idantifye nan sistèm an. Adrès IP ou a ap anrejistre nan istorik paj sa a.",
'noarticletext'      => 'Poko genyen tèks nan paj sa a, ou mèt [[{{ns:special}}:Search/{{PAGENAME}}|fè yon rechèch, fouye ak non paj sa a]] oubyen [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifye li].',
'editing'            => 'Modifikasyon pou $1',
'copyrightwarning'   => 'Souple, raple ou ke tout piblikasyon ki fèt nan {{SITENAME}} piblye anba kontra $2 an (wè $1 pou konnen plis). Si ou pa ta vle ke sa ou ekri pataje oubyen  modifye, ou pa dwèt soumèt yo isit.<br />
Ou ap pwomèt tou ke sa ou ap ekri a se ou menm menm ki ekri li oubyen ke ou kopye li de yon sous ki nan domèn piblik, ou byen you sous ki lib. <strong>PA ITILIZE TRAVAY MOUN KI PA BAY OTORIZASYON PA LI TOUTBON !</strong>',
'template-protected' => '(pwoteje)',

# History pages
'revisionasof'     => 'Vèsyon jou $1',
'previousrevision' => '← Vèsyon presedan',
'cur'              => 'kounye a',
'last'             => 'dènye',

# Diffs
'lineno'   => 'Liy $1 :',
'editundo' => 'Defè, anile',

# Search results
'noexactmatch' => "'''Pa genyen pyès paj ki genyen non sa a « $1 ».''' Ou mèt [[:$1|kreye atik sa a]].",
'viewprevnext' => 'Wè ($1) ($2) ($3).',
'powersearch'  => 'Fouye',

# Preferences page
'mypreferences' => 'Preferans yo',

# Recent changes
'recentchanges'   => 'Modifikasyon yo ki fèk fèt',
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
'recentchangeslinked'         => 'Swivi pou lyen yo',
'recentchangeslinked-title'   => 'Chanjman ki an relasyon ak $1',
'recentchangeslinked-summary' => "Paj espesyal sa a ap monter dènye chanjman nan paj ki genyen lyen. Paj yo ki nan lis swivi ou an ap ekri '''fonse'''",

# Upload
'upload' => 'Chaje fichye an',

# Image list
'filehist'            => 'Istorik fichye a',
'filehist-help'       => 'Klike anlè yon dat epi yon lè pou fichye a jan li te ye nan moman sa a.',
'filehist-current'    => 'Kounye a',
'filehist-datetime'   => 'Dat ak lè',
'filehist-user'       => 'Itilizatè',
'filehist-dimensions' => 'Grandè yo',
'filehist-filesize'   => 'Lajè fichye a',
'filehist-comment'    => 'Komantè',
'imagelinks'          => 'Paj yo ki genyen imaj an',
'linkstoimage'        => 'Paj ki ap swiv yo genyen imaj sa a :',

# Random page
'randompage' => 'Yon paj o aza',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|okte|okte}}',
'nmembers'     => '$1 {{PLURAL:$1|manm|manm yo}} andidan',
'specialpages' => 'Paj espesyal yo',
'move'         => 'Renonmen',

'alphaindexline' => '$1 jiska $2',

# Special:Allpages
'allpagessubmit' => 'Ale',

# Watchlist
'mywatchlist' => 'Lis swivi',
'watch'       => 'Swiv',
'unwatch'     => 'Pa swiv ankò',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Swiv...',
'unwatching' => 'Fini swiv paj sa a...',

# Namespace form on various pages
'namespace'      => 'Espas non :',
'blanknamespace' => '(Prensipal)',

# Contributions
'mycontris' => 'Kontribisyon mwen yo',

# What links here
'whatlinkshere'       => 'Paj ki lye nan paj sa a',
'whatlinkshere-title' => 'Paj ki genyen lyen ki ap mennen nan $1',
'linklistsub'         => '(Lis lyen yo)',
'whatlinkshere-links' => '← lyen yo',

# Block/unblock
'blocklink'    => 'Bloke',
'contribslink' => 'Kontribisyon yo',

# Thumbnails
'thumbnail-more'  => 'Agrandi',
'thumbnail_error' => 'Erè nan kreyasyon minyati : $1',

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
'tooltip-ca-move'         => 'Renonmen paj sa a',
'tooltip-ca-watch'        => 'Ajoute paj sa a nan lis swivi ou a',
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
'tooltip-ca-nstab-image'  => 'Wè paj imaj an',
'tooltip-save'            => 'Sove modifikasyon ou yo',
'tooltip-preview'         => 'Souple, gade paj ou an (previzyalize li) anvan ou anrejistre li',
'tooltip-diff'            => 'Montre ki chanjman ou fè nan tèks an.',

# Media information
'file-info-size' => '($1 × $2 piksèl, lajè fichye a : $3, tip MIME li ye : $4)',

# Bad image list
'bad_image_list' => 'Fòma a, se konsa li ye :

Sèl liy ki komanse pa * ke konte.  Premye lyen nan liy an se sa ki ap mene kote yon move imaj.
Lòt lyen yo nan menm liy an ke ap konsidere tankou eksepsyon, pa egzanp atik nan kilès yo imaj an dwèt parèt.',

# Metadata
'metadata' => 'Metadone',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'Tout',

);
