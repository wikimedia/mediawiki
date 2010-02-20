<?php
/** Lingala (Lingála)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Eruedin
 * @author Moyogo
 */

$fallback = 'fr';

$defaultUserOptionOverrides = array(
	'editfont' => 'sans-serif', # poor font support
);

$linkPrefixExtension = true;

# Same as the French (bug 8485)
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline' => 'Kotíya sulimá na bikangisi :',

# Dates
'monday'        => 'mokɔlɔ ya libosó',
'tuesday'       => 'mokɔlɔ ya míbalé',
'wednesday'     => 'mokɔlɔ ya mísáto',
'thursday'      => 'mokɔlɔ ya mínei',
'friday'        => 'mokɔlɔ ya mítáno',
'sun'           => 'eye',
'mon'           => 'm1',
'tue'           => 'm2',
'wed'           => 'm3',
'thu'           => 'm4',
'fri'           => 'm5',
'sat'           => 'mps',
'january'       => 'yanwáli',
'february'      => 'febwáli',
'march'         => 'mársi',
'april'         => 'apríli',
'may_long'      => 'máyí',
'june'          => 'yúni',
'july'          => 'yúli',
'august'        => 'augústo',
'september'     => 'sɛtɛ́mbɛ',
'october'       => 'ɔkɔtɔ́bɛ',
'november'      => 'novɛ́mbɛ',
'december'      => 'dɛsɛ́mbɛ',
'january-gen'   => 'yanwáli',
'february-gen'  => 'febwáli',
'march-gen'     => 'mársi',
'april-gen'     => 'apríli',
'may-gen'       => 'máyí',
'june-gen'      => 'yúni',
'july-gen'      => 'yúli',
'august-gen'    => 'augústo',
'september-gen' => 'sɛtɛ́mbɛ',
'october-gen'   => 'ɔkɔtɔ́bɛ',
'november-gen'  => 'novɛ́mbɛ',
'december-gen'  => 'dɛsɛ́mbɛ',
'jan'           => 'yan',
'feb'           => 'feb',
'mar'           => 'már',
'apr'           => 'apr',
'may'           => 'máy',
'jun'           => 'yún',
'jul'           => 'yúl',
'aug'           => 'aug',
'sep'           => 'sɛt',
'oct'           => 'ɔkɔ',
'nov'           => 'nov',
'dec'           => 'dɛs',

# Categories related messages
'category_header' => 'Bikakoli o molɔngɔ́ ya bilɔkɔ ya loléngé mɔ̌kɔ́ « $1 »',
'subcategories'   => 'Ndéngé-bǎna',
'category-empty'  => "''Loléngé loye ezalí na ekakola tɛ̂, loléngé-mwǎna tɛ̂ tǒ nkásá mitímediá tɛ̂.''",

'about'      => 'elɔ́kɔ elobámí',
'article'    => 'ekakoli',
'cancel'     => 'Kozóngela',
'mytalk'     => 'Ntembe na ngáí',
'navigation' => 'Botamboli',
'and'        => '&#32;mpé',

# Cologne Blue skin
'qbedit'         => 'kobalusa',
'qbspecialpages' => 'Nkásá ya ndéngé isúsu',

'errorpagetitle'   => 'Mbéba',
'tagline'          => 'Artíclɛ ya {{SITENAME}}.',
'help'             => 'Bosálisi',
'search'           => 'Boluki',
'searchbutton'     => 'Boluki',
'go'               => 'kokɛndɛ',
'searcharticle'    => 'Kɛndɛ́',
'history'          => 'Makambo ya lonkásá',
'history_short'    => 'likambo',
'printableversion' => 'Mpɔ̂ na kofínela',
'permalink'        => 'Ekangeli ya ntángo yɔ́nsɔ',
'print'            => 'kobimisa nkomá',
'edit'             => 'Kobimisela',
'create'           => 'Kokela',
'editthispage'     => 'Kobɔngisa lonkásá óyo',
'create-this-page' => 'Kokela lokásá yango',
'delete'           => 'Kolímwisa',
'protect'          => 'Kobátela',
'unprotect'        => 'Kobátela tɛ̂',
'newpage'          => 'Lonkásá ya sika',
'talkpagelinktext' => 'Ntembe',
'personaltools'    => 'Bisáleli ya moto-mɛ́i',
'talk'             => 'Ntembe',
'views'            => 'Bomɔ́niseli',
'toolbox'          => 'Bisáleli',
'otherlanguages'   => 'Na nkótá isúsu',
'redirectedfrom'   => '(Eyendísí útá $1)',
'redirectpagesub'  => 'Lokásá la boyendisi',
'jumpto'           => 'Kokɛndɛ na:',
'jumptonavigation' => 'bolúki',
'jumptosearch'     => 'boluki',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'elɔ́kɔ elobí {{SITENAME}}',
'aboutpage'            => 'Project:Etalí',
'copyrightpage'        => '{{ns:project}}:Mikokisi',
'currentevents'        => 'Elɔ́kɔ ya sika',
'disclaimers'          => 'Ndelo ya boyanoli',
'edithelp'             => 'Kobimisela bosálisi',
'mainpage'             => 'Lonkásá ya libosó',
'mainpage-description' => 'Lokásá ya libosó',
'portal'               => 'Bísó na bísó',

'ok'                 => 'Nandimi',
'retrievedfrom'      => 'Ezwámí úta « $1 »',
'youhavenewmessages' => 'Nazweí $1 ($2).',
'newmessageslink'    => 'nsango ya sika',
'editsection'        => 'kobimisela',
'editold'            => 'kokoma',
'editlink'           => 'kobɔngisa',
'editsectionhint'    => 'Kobimisela sɛksíɔ : $1',
'toc'                => 'Etápe',
'showtoc'            => 'komɔ́nisa',
'hidetoc'            => 'kobomba',
'site-rss-feed'      => 'Ebale RSS ya $1',
'site-atom-feed'     => 'Ebale Atom ya $1',
'page-rss-feed'      => 'Ebale RSS ya « $1 »',
'red-link-title'     => '$1 (lonkásá  ezalí tɛ̂)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'lonkásá',
'nstab-user'      => 'lonkásá ya mosáleli',
'nstab-media'     => 'Mediá',
'nstab-special'   => 'Lonkásá gudi',
'nstab-project'   => 'Etalí',
'nstab-image'     => 'Kásá',
'nstab-mediawiki' => 'Liyébísí',
'nstab-template'  => 'Emekoli',
'nstab-help'      => 'Lonkásá ya lisálisi',
'nstab-category'  => 'Katégori',

# Main script and global functions
'nosuchaction'      => 'Ekelá eyébani tɛ̂',
'nosuchspecialpage' => 'Lonkásá gudi yangó ezalí tɛ̂',
'nospecialpagetext' => '<strong>Otúní lonkásá gudi kasi yangó ezalí tɛ̂.</strong>

Ezalí listɛ́ ya nkásá gudi bizalí  na [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'              => 'Mbéba',
'databaseerror'      => 'Zíko ya litákoli ya kabo',
'laggedslavemode'    => "'''Likébisi''' : lonkásá óyo ekokí kokwa mbóngwana ya nsúka nyɔ́nsɔ tɛ̂",
'readonly'           => 'Litákoli ya kabo efúngámí',
'enterlockreason'    => 'Ópésa ntína mpé ntángo ya kokanisa ya bofúngi ya litákoli ya kabo',
'internalerror'      => 'Zíko ya káti',
'internalerror_info' => 'Zíko ya káti : $1',
'perfcached'         => 'Bipeseli byangó bizalí o mobómbisi-lombángu mpé bikokí kozala ya lɛlɔ́ tɛ̂.',
'perfcachedts'       => 'Bipeseli byangó bizalí o mobómbisi-lombángu mpé bikokí kozala ya lɛlɔ́ tɛ̂. Bizalí bya $1.',
'viewsourcefor'      => 'na $1',

# Login and logout pages
'yourname'                => 'Nkómbó ya ekitoli :',
'yourpassword'            => 'Banda nayó:',
'yourpasswordagain'       => 'Banda naíno :',
'login'                   => 'komíkitola (log in)',
'nav-login-createaccount' => 'Komíkomisa tǒ kokɔtɔ',
'userlogin'               => 'Komíkomisa tǒ kokɔtɔ',
'logout'                  => 'kolongwa',
'userlogout'              => 'kolongwa (log out)',
'nologin'                 => "Omíkomísí naíno tɛ̂? '''$1'''.",
'nologinlink'             => 'Míkomísá yɔ̌-mɛ́i',
'gotaccount'              => "Omíkomísí naíno ? '''$1'''.",
'createaccountmail'       => 'na mokánda',

# Edit page toolbar
'bold_sample'     => 'Nkomá ya mbinga',
'bold_tip'        => 'Nkomá ya mbinga',
'italic_sample'   => 'Nkomá ya kotɛ́ngama',
'italic_tip'      => 'Nkomá ya kotɛ́ngama',
'extlink_sample'  => 'http://www.example.com Litɛ́mɛ ya ekangisi',
'headline_sample' => 'Nkomá ya litɛ́mɛ',
'headline_tip'    => 'Litɛ́mɛ ya emeko 2',

# Edit pages
'summary'                => 'Likwé ya mokusé:',
'subject'                => 'Mokonza/litɛ́mɛ:',
'minoredit'              => 'Ezalí mbóngwana ya mokɛ́',
'watchthis'              => 'Kolanda lonkásá óyo',
'savearticle'            => 'kobómbisa lonkásá',
'preview'                => 'Botáli',
'newarticle'             => '(Sika)',
'editing'                => 'Kobimisela « $1 »',
'editingcomment'         => 'Kokoma « $1 » (ndimbola)',
'yourtext'               => 'Nkomá na yɔ̌',
'templatesused'          => '{{PLURAL:$1|Emekoli esálélí|Bimekoli bisálélí}} o lonkásá óyo :',
'templatesusedpreview'   => 'Bimekoli na mosálá o botáli boye :',
'template-protected'     => '(na bobáteli)',
'template-semiprotected' => '(na bobáteli ya ndámbo)',

# History pages
'currentrev'          => 'Lizóngeli na mosálá',
'revisionasof'        => 'Lizóngeli ya $1',
'previousrevision'    => '← Lizóngeli lilekí',
'nextrevision'        => 'Lizóngeli lilandí →',
'currentrevisionlink' => 'Lizóngeli na mosálá',
'cur'                 => 'sika',
'next'                => 'bolɛngɛli',
'last'                => 'ya nsúka',

# Revision deletion
'rev-delundel' => 'komɔ́nisa/kobomba',

# Diffs
'history-title' => 'Makambo ya mazóngeli ya « $1 »',
'lineno'        => 'Mokɔlɔ́tɔ $1 :',
'editundo'      => 'kozóngela',

# Search results
'prevn'               => '{{PLURAL:$1|$1}} ya libosó',
'nextn'               => 'bolɛngɛli {{PLURAL:$1|$1}}',
'viewprevnext'        => 'Komɔ́na ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'  => '$1 ({{PLURAL:$2|lokomá 1|nkomá $2}})',
'search-result-score' => 'Ntína : $1%',
'search-redirect'     => '(bopengoli útá $1)',
'search-section'      => '(sɛksió ya $1)',
'search-suggest'      => 'Ómeka na lokomá : $1',
'powersearch'         => 'Boluki',

# Preferences page
'preferences'       => 'Malúli',
'mypreferences'     => 'Malúli ma ngáí',
'prefs-datetime'    => 'Mokɔlɔ mpé ntángo',
'prefs-rc'          => 'Mbóngwana ya nsúka',
'saveprefs'         => 'kobómbisa',
'searchresultshead' => 'Boluki',
'allowemail'        => 'Enable mokánda from other users',
'youremail'         => 'Mokandá (e-mail) *',
'username'          => 'Nkómbó ya ekitoli :',
'yourrealname'      => 'nkómbó ya sɔ̂lɔ́',
'yourlanguage'      => 'Lokótá',
'email'             => 'Mokánda',

# Groups
'group-sysop' => 'Bayángeli',

'group-sysop-member' => 'Moyángeli',

# Recent changes
'recentchanges'   => 'Mbóngwana ya nsúka',
'rcnote'          => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana '''1'''|izalí mbóngwana '''$1'''}} o {{PLURAL:$2|mokɔlɔ|mikɔlɔ '''$2'''}} ya nsúka, {{PLURAL:$1|etángámí|itángámí}} o $3.",
'rcshowhideminor' => '$1 mbóngwana ya mokɛ́',
'rcshowhidebots'  => '$1 barobot',
'rcshowhidemine'  => '$1 mbóngwana ya ngáí',
'rclinks'         => 'Komɔ́nisa mbóngwana $1 ya nsúka o mikɔ́lɔ $2<br />$3',
'diff'            => 'mbó.',
'hist'            => 'likambo',
'hide'            => 'kobomba',
'show'            => 'Komɔ́nisa',
'minoreditletter' => 'm',
'newpageletter'   => 'S',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked'         => 'Bolandi ekangisi',
'recentchangeslinked-feed'    => 'Bolandi ekangisi',
'recentchangeslinked-toolbox' => 'Bolandi ekangisi',

# Upload
'upload'    => 'Kokumbisa (elilingi)',
'uploadbtn' => 'kokumbisa',
'savefile'  => 'kobómbisa kásá-kásá',

# Special:ListFiles
'listfiles_date' => 'Mokɔlɔ',

# File description page
'file-anchor-link' => 'Elilingi',
'filehist'         => 'Mokóló ya kásá',

# File deletion
'filedelete-submit' => 'Kolímwisa',

# Unused templates
'unusedtemplates' => 'Bimekoli na mosálá tɛ̂',

# Random page
'randompage' => 'Lonkásá na mbɛsɛ',

# Statistics
'statistics' => 'Mitúya',

'disambiguations' => 'Bokokani',

'doubleredirects' => 'Boyendisi mbala míbalé',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|okté|baokté}}',
'nmembers'                => '{{PLURAL:$1|ekakoli|bikakoli}} $1',
'uncategorizedpages'      => 'Nkásá izángí loléngé',
'uncategorizedcategories' => 'Ndéngé izángí loléngé',
'uncategorizedimages'     => 'Bilílí bizángí loléngé',
'uncategorizedtemplates'  => 'Bimekoli bizángí loléngé',
'unusedcategories'        => 'Ndéngé na mosálá tɛ̂',
'shortpages'              => 'Nkásá ya mokúsé',
'longpages'               => 'Nkásá ya molaí',
'newpages'                => 'Ekakoli ya sika',
'newpages-username'       => 'Nkómbó ya ekitoli :',
'move'                    => 'Kobóngola nkómbó',

# Book sources
'booksources-go' => 'Kɛndɛ́',

# Special:Log
'log' => 'Bapasɔ́',

# Special:AllPages
'allpages'       => 'Nkásá ínsɔ',
'alphaindexline' => '$1 kina $2',
'nextpage'       => 'Lokásá ya nsima ($1)',
'prevpage'       => 'Lonkasá o libosó ($1)',
'allpagesprev'   => '< ya libosó',
'allpagesnext'   => 'bolɛngɛli >',
'allpagessubmit' => 'kokɛndɛ',

# Special:Categories
'categories' => 'Ndéngé',

# E-mail user
'defemailsubject' => '{{SITENAME}} mokánda',
'emailfrom'       => 'útá',
'emailto'         => 'epái',
'emailmessage'    => 'Nsango',
'emailsend'       => 'kotínda',
'emailsent'       => 'nkandá etíndámá',
'emailsenttext'   => 'Nkandá ya yɔ̌ etíndámá',

# Watchlist
'watchlist'         => 'Nkásá nalandí',
'mywatchlist'       => 'Nkásá nalandí',
'watchlistfor'      => "(mpɔ̂ na moto '''$1''')",
'watch'             => 'Kolanda',
'watchthispage'     => 'Kolanda lonkásá óyo',
'unwatch'           => 'Kolanda tɛ́',
'watchlist-details' => '{{PLURAL:$1|Lonkásá $1 elandámí|Nkásá $1 bilandámí}}, longola nkásá ya ntembe.',
'wlnote'            => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana ya nsúka|izalí mbóngwana '''$1''' ya nsúka}} o {{PLURAL:$2|ngonga|ngonga '''$2'''}} ya nsúka.",
'wlshowlast'        => 'Komɔ́nisa ngónga $1 ya nsúka, mikɔ́lɔ $2 mya nsúka tǒ $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Bonɔ́ngi...',

'created' => 'ekomákí',

# Delete
'deletedarticle' => 'elímwísí "[[$1]]"',
'dellogpage'     => 'Zonálɛ ya bolímwisi',
'deletionlog'    => 'zonálɛ ya bolímwisi',
'deletecomment'  => 'Ntína ya bolímwisi',

# Restrictions (nouns)
'restriction-edit' => 'Kokoma',
'restriction-move' => 'Kobóngola nkómbó',

# Namespace form on various pages
'namespace'      => 'Ntáká ya nkómbó :',
'blanknamespace' => '(Ya libosó)',

# Contributions
'contributions'       => 'Mosálá ya moto óyo',
'contributions-title' => 'Mosálá ya moto mpɔ̂ na $1',
'mycontris'           => 'Nkásá nakomí',

'sp-contributions-talk' => 'Ntembe',

# What links here
'whatlinkshere'       => 'Ekangísí áwa',
'isredirect'          => 'Lonkásá ya bopengoli',
'whatlinkshere-links' => '← ekangisi',

# Block/unblock
'contribslink' => 'bíteni ya mosálá',

# Move page
'movearticle'             => 'Kobóngola nkómbó ya ekakoli :',
'move-watch'              => 'Kolánda lonkásá ya líziba mpé lonkásá ya tíndamelo',
'movepagebtn'             => 'Kobóngola lokásá',
'movedto'                 => 'nkómbó ya sika',
'revertmove'              => 'kozóngela',
'delete_and_move'         => 'Kolímwisa mpé kobóngola nkómbó',
'delete_and_move_confirm' => 'Boye, kolímwisa lokásá',
'delete_and_move_reason'  => 'Ntína ya bolímwisi mpé bobóngoli bwa nkómbó',

# Export
'export'        => 'komɛmɛ na...',
'export-submit' => 'Komɛmɛ',

# Thumbnails
'thumbnail-more' => 'Koyéisa monɛ́nɛ',

# Special:Import
'import' => 'koútisa...',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Lonkásá na ngáí',
'tooltip-pt-mytalk'              => 'Lokásá ntembe la ngáí',
'tooltip-pt-preferences'         => 'Malúli ma ngáí',
'tooltip-pt-watchlist'           => 'Nkásá nalandí mpɔ̂ na mbóngwana',
'tooltip-pt-mycontris'           => 'Nkásá nakomí',
'tooltip-pt-logout'              => 'Ombémbóí',
'tooltip-ca-move'                => 'Kobóngola nkómbó ya lonkásá óyo',
'tooltip-ca-watch'               => 'Kobakisa na nkásá olandaka',
'tooltip-ca-unwatch'             => 'Kolongola na nkásá olandaka',
'tooltip-search'                 => 'Boluki {{SITENAME}}',
'tooltip-search-go'              => 'Kokɛndɛ na lonkásá na nkómbó óyo sɔ́kí ezalí',
'tooltip-search-fulltext'        => 'Koluka nkásá na nkomá yangó.',
'tooltip-p-logo'                 => 'Lokásá ya libosó',
'tooltip-n-mainpage'             => 'Kokɛndɛ na Lonkásá ya libosó',
'tooltip-n-mainpage-description' => 'Kokɛndɛ na lonkásá ya libosó',
'tooltip-n-portal'               => "etalí ''projet'' óyo",
'tooltip-n-randompage'           => 'Tómbisa lonkásá na mbɛsɛ',
'tooltip-n-help'                 => 'Lisálisi',
'tooltip-ca-nstab-template'      => 'Komɔ́nisela emekoli',
'tooltip-ca-nstab-category'      => 'Komɔ́nisela lonkásá ya katégori',

# Browsing diffs
'previousdiff' => '← diff ya libosó',

# Special:NewFiles
'ilsubmit' => 'Boluki',

# EXIF tags
'exif-artist' => 'Mokeli',

'exif-subjectdistancerange-2' => 'kokanga view',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'nyɔ́nsɔ',
'namespacesall' => 'Nyɔ́nsɔ',

# action=purge
'confirm_purge_button' => 'Nandimi',

# Multipage image navigation
'imgmultigo' => 'Kɛndɛ́!',

# Table pager
'table_pager_limit_submit' => 'kokɛndɛ',

# Watchlist editing tools
'watchlisttools-view' => 'Komɔ́nisela mbóngwana ya ntína',
'watchlisttools-edit' => 'Komɔ́nisela mpé kobimisela nkásá nalandí',
'watchlisttools-raw'  => 'Kobimisela nkásá nalandí (na pɛpɛ)',

# Special:SpecialPages
'specialpages' => 'Nkásá ya ndéngé mosúsu',

);
