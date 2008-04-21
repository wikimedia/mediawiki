<?php
/** Pontic (Ποντιακά)
 *
 * @addtogroup Language
 *
 * @author Consta
 * @author Sinopeus
 * @author Siebrand
 */

$namespaceNames = array(
	NS_MEDIA          => 'Μέσον',
	NS_SPECIAL        => 'Ειδικόν',
	NS_TALK           => 'Καλάτσεμαν',
	NS_USER           => 'Χρήστες',
	NS_USER_TALK      => 'Καλάτσεμαν_χρήστε',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_καλάτσεμαν',
	NS_IMAGE          => 'Εικόναν',
	NS_IMAGE_TALK     => 'Καλάτσεμαν_εικόνας',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
	NS_TEMPLATE       => 'Πρότυπον',
	NS_TEMPLATE_TALK  => 'Καλάτσεμαν_πρότυπι',
	NS_HELP           => 'Βοήθειαν',
	NS_HELP_TALK      => 'Καλάτσεμαν_βοήθειας',
	NS_CATEGORY       => 'Κατηγορίαν',
	NS_CATEGORY_TALK  => 'Καλάτσεμαν_κατηγορίας',
);

$messages = array(
# User preference toggles
'tog-showhiddencats' => 'Δείξον κρυμμένα κατηγορίας',

'underline-always' => 'Πάντα',
'underline-never'  => 'Καμίαν',

'skinpreview' => '(Πρώτον τέρεμα)',

# Dates
'sunday'        => 'Κερεκήν',
'monday'        => 'Δευτέραν',
'tuesday'       => 'Τριτ',
'wednesday'     => 'Τετάρτ',
'thursday'      => 'Πεφτ',
'friday'        => 'Παρασκευήν',
'saturday'      => 'Σάββαν',
'sun'           => 'Κυρ',
'mon'           => 'Δευ',
'tue'           => 'Τρι',
'wed'           => 'Τετ',
'thu'           => 'Πεμ',
'fri'           => 'Παρ',
'sat'           => 'Σαβ',
'january'       => 'Καλαντάρτς',
'february'      => 'Κούντουρος',
'march'         => 'Μαρτς',
'april'         => 'Απρίλτς',
'may_long'      => 'Καλομηνάς',
'june'          => 'Κερασινός',
'july'          => 'Χορτοθέρτς',
'august'        => 'Aύγουστον',
'september'     => 'Σταυρίτες',
'october'       => 'Τρυγομηνάς',
'november'      => 'Αεργίτες',
'december'      => 'Χριστουγενάρτς',
'january-gen'   => 'Καλανταρί',
'february-gen'  => 'Κούντουρονος',
'march-gen'     => 'Μαρτ',
'april-gen'     => 'Απρίλτ',
'may-gen'       => 'Καλομηνά',
'june-gen'      => 'Κερασινού',
'july-gen'      => 'Χορτοθερί',
'august-gen'    => 'Αύγουστονος',
'september-gen' => 'Σταυρίτ',
'october-gen'   => 'Τρυγομηνά',
'november-gen'  => 'Αεργίτ',
'december-gen'  => 'Χριστουγενάρτ',
'jan'           => 'Καλαντ',
'feb'           => 'Κουντ',
'mar'           => 'Μάρ',
'apr'           => 'Απρ',
'may'           => 'Καλομ',
'jun'           => 'Κερ',
'jul'           => 'Χορτ',
'aug'           => 'Αύγ',
'sep'           => 'Σταυ',
'oct'           => 'Τρυγ',
'nov'           => 'Αεργ',
'dec'           => 'Χριστ',

# Categories related messages
'categories'                     => 'Κατηγορίας',
'categoriespagetext'             => 'Τα κατηγορίας αφκά καικά έχνε σελίδας και μέσα.',
'pagecategories'                 => '{{PLURAL:$1|Κατηγορίαν|Κατηγορίας}}',
'category_header'                => 'Σελίδας τη κατηγορίας "$1"',
'subcategories'                  => 'Υποκατηγορίας',
'category-media-header'          => 'Μέσα απές σην κατηγορίαν "$1"',
'category-empty'                 => "''Ατή η κατηγορίαν πα 'κ εχ' νέ σελίδας νέ μέσα.''",
'hidden-categories'              => '{{PLURAL:$1|Κρυμμένον κατηγορίαν|Κρυμμένα κατηγορίας}}',
'hidden-category-category'       => 'Κρυμμέν κατηγορίας', # Name of the category where hidden categories will be listed
'category-subcat-count-limited'  => "Η κατηγορίαν ατή έχ' αφκά καικά {{PLURAL:$1|την υποκατηγορίαν|$1 τα υποκατηγορίας}}.",
'category-article-count'         => "{{PLURAL:$2|Αυτή η κατηγορίαν έχ' την αφκά καικά σελίδαν μαναχόν.| Αφκά καικά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είναι}} σην κατηγορίαν ατέν, απές σο σύνολον τη $2.}}",
'category-article-count-limited' => 'Αφκά καικά {{PLURAL:$1|η σελίδαν εν|$1 τα σελίδας είναι}} σην κατηγορίαν ατέν.',
'category-file-count'            => "{{PLURAL:$2|Αυτή η κατηγορίαν έχ' το αφκά καικά αρχείον μαναχόν.| Αφκά καικά {{PLURAL:$1|το αρχείον εν|$1 τα αρχεία είναι}} σην κατηγορίαν ατέν, απές σο σύνολον τη $2.}}",
'category-file-count-limited'    => "{{PLURAL:$1|Τ' αρχείον|$1 Τ' αρχεία}} αφκά καικά είν' σην κατηγορίαν.",
'listingcontinuesabbrev'         => 'συνεχίζεται...',

'cancel'         => "Άφ'σον",
'qbfind'         => 'Εύρον',
'qbedit'         => 'Άλλαξον',
'qbpageoptions'  => 'Ατή η σελίδαν',
'qbmyoptions'    => "Τ' εμά τα σελίδας",
'qbspecialpages' => 'Ειδικά σελίδας',
'mypage'         => "Τ' εμόν η σελίδαν",
'mytalk'         => "Τ' εμόν το καλάτσεμαν",
'anontalk'       => "Καλάτσεμα για τ'ατό το IP",
'and'            => 'και',

'returnto'          => 'Επιστροφήν σο $1.',
'tagline'           => 'Ασό {{SITENAME}}',
'help'              => 'Βοήθειαν',
'search'            => 'αράεμαν',
'searchbutton'      => 'Εύρον',
'go'                => 'Δέβα',
'searcharticle'     => 'Δέβα',
'history'           => 'Ιστορίαν τη σελίδας',
'history_short'     => 'Ιστορίαν',
'printableversion'  => 'Μορφή εκτύπωσης',
'edit'              => 'Άλλαξον',
'create'            => 'Ποίσον',
'editthispage'      => 'Άλλαξον τη σελίδαν ατέν',
'create-this-page'  => 'Ποίσον τη σελίδαν',
'delete'            => 'Σβήσον',
'deletethispage'    => 'Σβήσον τη σελίδαν',
'protect'           => 'Ασπάλιγμα',
'protect_change'    => "Άλλαγμα τ' ασπάλιγματη",
'protectthispage'   => 'Ασπάλιγμα ατουνού τη σελίδας',
'unprotect'         => 'Άνοιγμα',
'unprotectthispage' => 'Άνοιγμα ατουνού τη σελίδας',
'newpage'           => 'Νέον σελίδαν',
'talkpage'          => 'Καλάτσεμαν για τη σελίδαν ατέν',
'talkpagelinktext'  => 'Καλάτσεμαν',
'specialpage'       => 'Ειδικόν σελίδαν',
'personaltools'     => 'Προσωπικά εργαλεία',
'postcomment'       => 'Ποίσον σχόλιον',
'talk'              => 'Καλάτσεμαν',
'views'             => 'Τερέματα',
'toolbox'           => 'Εργαλεία',
'userpage'          => 'Τέρεν σελίδαν χρήστε',
'otherlanguages'    => "Σ' άλλα γλώσσας",
'protectedpage'     => 'Ασπαλιζμένον σελίδαν',
'jumpto'            => 'Δέβα σο:',
'jumptosearch'      => 'αράεμαν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutpage'            => 'Project:Σχετικά',
'copyrightpage'        => '{{ns:project}}:Δικαιώματα Πνευματή',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Αρχικόν σελίδα',
'mainpage-description' => 'Αρχικόν σελίδα',
'portal'               => 'Πύλην τη κοινότητας',
'portal-url'           => 'Project:Πύλη κοινότητας',
'sitesupport'          => 'Δωρεάς',

'retrievedfrom'       => 'Ασο "$1"',
'youhavenewmessages'  => 'Έχετε $1 ($2).',
'newmessageslink'     => 'καινούρεα μενέματα',
'newmessagesdifflink' => 'υστερνόν αλλαγήν',
'editsection'         => 'άλλαξον',
'editold'             => 'άλλαξον',
'editsectionhint'     => 'Άλλαξον φελίν: $1',
'toc'                 => 'Περιεχόμενα',
'showtoc'             => 'δείξον',
'hidetoc'             => 'κρύψον',
'site-rss-feed'       => '$1 RSS Συνδρομή',
'site-atom-feed'      => '$1 Atom Συνδρομή',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Σελίδαν',
'nstab-user'      => 'Σελίδα χρήστε',
'nstab-special'   => 'Ειδικόν',
'nstab-image'     => 'Εικόναν',
'nstab-mediawiki' => 'Μένεμα',
'nstab-template'  => 'Πρότυπον',
'nstab-category'  => 'Κατηγορίαν',

# Main script and global functions
'nosuchaction'      => "Αΐκον ενέργειαν 'κ εχ'.",
'nosuchspecialpage' => "Αΐκον ειδικόν σελίδαν 'κ εχ'.",

# General errors
'laggedslavemode'   => "Ωρία: Η σελίδαν ίσως ξάι 'κ εχ' τα υστερνά τα αλλαγάς.",
'badarticleerror'   => "Ατή η ενέργειαν 'κ επορεί να ίνεται σ'ατήν τη σελίδαν.",
'viewsourcefor'     => 'για $1',
'protectedpagetext' => "Ατή η σελίδαν εν ασπαλιγμένη και 'κ αλλάζεται.",
'viewsourcetext'    => "Μπορείτε να τερείτε και να αντιγράφετε το κείμενον τ' ατεινές τη σελίδας:",

# Login and logout pages
'yourname'                => 'Όνεμα χρήστε:',
'yourpassword'            => 'Σημάδι:',
'yourpasswordagain'       => "Ξαν' γράψτεν το σημάδι:",
'login'                   => 'Εμπάτε',
'nav-login-createaccount' => 'Εμπάτε / Ποίστεν λογαριασμόν',
'userlogin'               => 'Εμπάτε / Ποίστεν λογαριασμόν',
'logout'                  => 'οξουκά',
'userlogout'              => 'Οξουκά',
'notloggedin'             => 'Ευρίσκεζνε οξουκά ασή Βικιπαίδειαν',
'nologin'                 => "Λογαριασμόν 'κ έχετε; $1.",
'nologinlink'             => 'Ποίστεν λογαριασμόν',
'createaccount'           => 'Ποίσον λογαριασμόν',
'gotaccount'              => 'Λογαριασμόν έχετε; $1.',
'gotaccountlink'          => 'Εμπάτε',
'badretype'               => "Τα σημάδε ντ' εγράψετεν 'κ ταιριάζνε.",
'username'                => 'Όνεμα χρήστε:',
'yourlanguage'            => "Τ' εσόν η γώσσαν:",
'loginerror'              => 'Σφάλμα εγγραφής',
'loginsuccesstitle'       => "Έντον τ' εσέβεμαν",
'loginsuccess'            => "'''Εσήβετεν σο {{SITENAME}} ους \"\$1\".'''",
'mailmypassword'          => 'Αποστολή κωδικού',
'accountcreated'          => 'Ο λογαριασμόν έντον',
'createaccount-title'     => 'Δημιουργίαν λογαριασμού για {{SITENAME}}',
'loginlanguagelabel'      => 'Γλώσσαν: $1',

# Edit page toolbar
'link_sample'     => 'Τίτλος σύνδεσμονος',
'link_tip'        => 'Εσωτερικόν σύνδεσμον',
'extlink_sample'  => 'http://www.paradeigma.com τίτλος σύνδεσμονος',
'extlink_tip'     => 'Εξωτερικόν σύνδεσμος (να μην ανασπάλλετε το πρόθεμαν http:// )',
'headline_sample' => 'Κείμενον τίτλονος',
'math_sample'     => 'Αδά εισάγετε την φόρμουλαν',
'nowiki_sample'   => 'Αδακά πα να εισάγετε το μη μορφοποιημένον κείμενον.',
'nowiki_tip'      => "Ξάι 'κ να τερείται η μορφοποίηση Wiki.",
'sig_tip'         => 'Η υπογραφήν εσούν με ώραν κι ημερομηνίαν',

# Edit pages
'summary'            => 'Σύνοψη',
'subject'            => 'Θέμα/επικεφαλίδα',
'minoredit'          => 'Μικρόν αλλαγήν',
'watchthis'          => 'Ωρία τη σελίδαν ατέν',
'savearticle'        => 'Κρα τη σελίδαν',
'preview'            => 'Πρώτον τέρεμα',
'showpreview'        => 'Δείξον το πρώτον τέρεμα',
'showdiff'           => "Δείξον τ' αλλαγάς",
'newarticle'         => '(Νέον)',
'previewnote'        => "<strong>Ατό πα πρώτον τέρεμαν εν και μόνον.
Τ' αλλαγάς 'κ εκρατέθαν!</strong>",
'editing'            => 'Αλλαγήν $1',
'editingsection'     => 'Αλλαγήν $1 (τμήμα)',
'editingold'         => "<strong>ΩΡΙΑ: Εφτάτε αλλαγάς σε παλαιόν έκδοσην τη σελίδας. 
Εάν θα κρατείτε ατά, ούλ' τ' επεκεί αλλαγάς θα χάνταν.</strong>",
'template-protected' => '(ασπαλιγμένον)',

# History pages
'revisionasof'     => 'Μορφήν τη $1',
'previousrevision' => '←Παλαιόν μορφήν',
'histfirst'        => "Ασ' όλεα παλαιόν",
'histlast'         => "Ασ' όλεα καινούρ'",

# Revision feed
'history-feed-item-nocomment' => '$1 σο $2', # user at time

# Diffs
'lineno'                  => 'Γραμμή $1:',
'compareselectedversions' => 'Γαρσουλαεύτε τα εκδώσεις',

# Search results
'noexactmatch' => "'''Η Βικιπαίδειαν 'κ εχ' σελίδαν με τ' όνεμα \"\$1\".'''
Μπορείτε να [[:\$1|εφτάτε τη σελίδαν]].",
'prevn'        => '$1 προηγουμένων',
'nextn'        => '$1 επομένων',
'viewprevnext' => 'Τέρεν ($1) ($2) ($3)',
'powersearch'  => 'Αναλυτικόν αράεμαν',

# Preferences page
'preferences'   => 'Προτιμήσεις',
'mypreferences' => "Τ' εμά τα προτιμήσεις",

# Recent changes
'recentchanges'   => 'Υστερνά αλλαγάς',
'rcshowhideminor' => '$1 τα μικρά αλλαγάς',
'rclinks'         => "Δείξον τα $1 υστερνά τ' αλλαγάς α σα $2 υστερνά τα ημέρας<br />$3",
'diff'            => 'διαφορά',
'hist'            => 'ιστ.',
'hide'            => 'Κρύψον',
'show'            => 'Δείξον',
'minoreditletter' => 'μ',
'newpageletter'   => 'Ν',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked-title' => 'Αλλαγάς τη "$1"',

# Upload
'upload' => 'Φόρτωμα αρχείου',

# Image description page
'filehist'            => 'Ιστορικό αρχείου',
'filehist-current'    => 'υστερινά',
'filehist-datetime'   => 'Ώραν/Ημερομ.',
'filehist-user'       => 'Χρήστες',
'filehist-dimensions' => 'Διαστάσεις',
'filehist-filesize'   => 'Μέγεθος',
'filehist-comment'    => 'Σχόλιον',
'imagelinks'          => 'Σύνδεσμοι',
'linkstoimage'        => "Ατά τα σελίδας δεκνίζ'νε σην εικόναν:",

# MIME search
'mimesearch' => 'Αράεμαν MIME',

# Random page
'randompage' => 'Ήντιναν σελίδα',

# Statistics
'statistics' => 'Στατιστικήν',

# Miscellaneous special pages
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'lonelypages'             => 'Ορφανά σελίδας',
'uncategorizedpages'      => "Σελίδας ντο 'κ έχνε κατηγορίαν",
'uncategorizedcategories' => "Κατηγορίας ντο 'κ έχνε κατηγορίας",
'unusedcategories'        => 'Εύκαιρα κατηγορίας',
'wantedcategories'        => 'Κατηγορίας το θέλουμε',
'wantedpages'             => 'Σελίδας το θέλουμε',
'mostcategories'          => "Σελίδας με τ' ασ' όλτς πολλά κατηγορίας",
'shortpages'              => 'Μικρά σελίδας',
'longpages'               => 'Τρανά σελίδας',
'deadendpages'            => 'Αδιέξοδα σελίδας',
'protectedpages'          => 'Ασπαλιγμένα σελίδας',
'listusers'               => 'Κατάλογον χρήστιων',
'specialpages'            => 'Ειδικά σελίδας',
'newpages'                => 'Καινούρεα σελίδας',
'ancientpages'            => 'Ασ’ όλιον παλαιά σελίδας',
'move'                    => 'Ετεροχλάεμαν',
'movethispage'            => "Άλλαξον τ' όνεμα τη σελίδας",

# Book sources
'booksources-go' => 'Δέβα',

# Special:Log
'specialloguserlabel'  => 'Χρήστες:',
'speciallogtitlelabel' => 'Τίτλος:',

# Special:Allpages
'allpages'       => 'Όλεα τα σελίδας',
'alphaindexline' => '$1 ους $2',
'nextpage'       => 'Επόμενον σελίδα ($1)',
'prevpage'       => 'Προηγούμενον σελίδα ($1)',
'allarticles'    => 'Όλεα τα σελίδας',
'allpagessubmit' => 'Δέβα',

# E-mail user
'emailuser' => 'Στείλον μένεμαν σον χρήστεν ατόν.',

# Watchlist
'watchlist'            => "Σελίδας ντ' ωριάζω",
'mywatchlist'          => "Σελίδας ντ' ωριάζω",
'watchlistfor'         => "(για '''$1''')",
'removedwatchtext'     => 'Η σελίδαν "[[:$1]]" νεβζινέθεν ασ\' τ\'εσόν τον κατάλογον.',
'watch'                => 'Ωρίαγμαν',
'watchthispage'        => 'Ωρίαν τη σελίδαν',
'unwatch'              => 'Τέλος τη ωρίαγματη',
'watchlist-hide-bots'  => "Κρύψον τ' αλλαγάς τη bots",
'watchlist-hide-own'   => "Κρύψον τ' αλλαγάς 'ιμ",
'watchlist-hide-minor' => 'Κρύψον τα μικρά αλλαγάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ωριάζω...',
'unwatching' => "'κ ωριάζω...",

# Delete/protect/revert
'deletepage'              => 'Σβήσον τη σελίδαν',
'actioncomplete'          => 'Η ενέργειαν ετελέθεν',
'deletedarticle'          => 'νεβζινέθεν η "[[$1]]"',
'deletecomment'           => 'Λόγον για το σβήσιμο:',
'deleteotherreason'       => 'Άλλον/αλλομίαν λόγον:',
'deletereasonotherlist'   => 'Άλλον λόγον',
'protectcomment'          => 'Σχόλιον:',
'protect-default'         => '(προεπιλεγμένον)',
'protect-summary-cascade' => 'διαδοχικόν',
'protect-expiring'        => 'λήγει στις $1 (UTC)',
'restriction-type'        => 'Δικαίωμαν:',
'restriction-level'       => 'Επίπεδον περιορισμού:',

# Namespace form on various pages
'namespace'      => 'Περιοχήν:',
'blanknamespace' => '(Αρχικόν περιοχή)',

# Contributions
'contributions' => "Δουλείας ντ' εποίκε ο χρήστες",
'mycontris'     => "Δουλείας ντ' εποίκα",
'contribsub2'   => 'Για τον/την $1 ($2)',
'uctop'         => '(υστερνά)',

'sp-contributions-newbies-sub' => 'Για τα καινούρεα τοι λογαριασμούς',

# What links here
'whatlinkshere'       => "Ντο δεκνίζ' αδακές",
'whatlinkshere-title' => 'Σελίδας ντο συνδέουν σο $1',
'whatlinkshere-page'  => 'Σελίδαν:',
'linklistsub'         => "(Κατάλογον με τοι συνδέσμ')",
'linkshere'           => "Αυτά τα σελίδας συνδέουν ση σελίδαν '''[[:$1]]''':",
'istemplate'          => 'ενσωμάτωση',
'whatlinkshere-prev'  => '{{PLURAL:$1|προτεσνή|προτεσνά $1}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'contribslink' => "Δουλείαν ατ'",

# Move page
'movearticle'      => 'Ετεροχλάεμαν σελίδας:',
'newtitle'         => 'Νέον τίτλον:',
'move-watch'       => 'Αβούτη τη σελίδαν ωρία',
'movepagebtn'      => 'Ποίσον ετεροχλάεμαν τη σελίδαν',
'pagemovedsub'     => 'Ετερχλαεύτεν',
'movedto'          => 'ετεροχλαεύτεν σο',
'movetalk'         => 'Ετεροχλάεμαν τη σελίδας καλάτσεματι',
'talkpagemoved'    => 'Ετερχλαεύτεν και η σελίδαν καλάτσεματι.',
'talkpagenotmoved' => "Η σελίδαν καλάτσεματιν ατ' <strong>'κ</strong> ετερχλαεύτεν.",
'1movedto2'        => '[[$1]] ετερχλαεύτεν σο [[$2]]',
'movereason'       => 'Λόγον:',
'revertmove'       => 'επαναφορά',

# Export
'export' => 'Εξαγωγήν σελίδιων',

# Namespace 8 related
'allmessages' => 'Μενέματα σύστηματη',

# Thumbnails
'thumbnail-more'  => 'Ποίσον κι άλλο τρανόν',
'thumbnail_error' => 'Έντον λάθος ση δημιουργίαν τη μικρογραφίας: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Τ' εμόν η σελίδαν",
'tooltip-pt-mytalk'               => "Σελίδαν με τ' εμά τα καλατσέματα",
'tooltip-pt-preferences'          => "Τ' εμά τα προτιμήσεις",
'tooltip-pt-watchlist'            => "Λίστα με τα σελίδας ντ' ωριάζω",
'tooltip-pt-mycontris'            => "Λίστα με τα δουλείας ντ' εποίκα",
'tooltip-pt-login'                => "Μπορείτε νε εφτάτε έναν λογαριασμόν αλλά 'κ πρεπ'.",
'tooltip-pt-logout'               => 'Απιδεβένετεν τη Βικιπαίδειαν',
'tooltip-ca-talk'                 => "Γονούσεμαν γι' αβούτον τ' άρθρον",
'tooltip-ca-edit'                 => "Εμπορείτε ν' αλλάζετε τη σελίδαν, άμαν τερέστεν τ' αλλαγάς πριν θα κρατείτε ατά.",
'tooltip-ca-addsection'           => "Βαλέστε σχόλιον σ' αβούτο το γουνούσεμα.",
'tooltip-ca-viewsource'           => "Ατό η σελίδαν εν ασπαλιγμένον. Άμαν μπορείτε να τερείτε το κείμενον ατ'ς.",
'tooltip-ca-protect'              => 'Ασπάλιγμα τη σελίδας',
'tooltip-ca-delete'               => 'Σβήσον τη σελίδαν',
'tooltip-ca-move'                 => "Κότζεμαν τη σελίδας ας έναν τίτλον σ' άλλον.",
'tooltip-search'                  => 'Εύρον σο {{SITENAME}}',
'tooltip-n-mainpage'              => 'Τερέστεν το αρχικόν τη σελίδαν',
'tooltip-n-portal'                => 'Σχετικά με το Wiκi - πώς μπορείτε να εφτάτε γιαρτήμ, πού θα ευρίετε πράγματα',
'tooltip-n-recentchanges'         => 'Η λίστα με τα υστερνά αλλαγάς σο wiki.',
'tooltip-n-randompage'            => 'Κατά τύχην εύρον σελίδαν και δείξον ατέν',
'tooltip-n-help'                  => "Αδά θα ευρίετε τα απαντήσεις ντ' αραεύετε.",
'tooltip-n-sitesupport'           => 'Βοηθέστεν το έργον.',
'tooltip-t-whatlinkshere'         => "Ούλ' τ' άρθρα ντο δεκνίζνε σο παρόν το άρθρον",
'tooltip-t-upload'                => 'Φόρτωμα αρχείων',
'tooltip-t-specialpages'          => 'Κατάλογον με τα ειδικά σελίδας',
'tooltip-ca-nstab-user'           => 'Τέρεν τη σελίδαν τη χρήστε',
'tooltip-ca-nstab-image'          => 'Τερέστεν την εικόναν',
'tooltip-ca-nstab-template'       => 'Τερέστεν τα πρότυπα',
'tooltip-ca-nstab-help'           => 'Τερέστεν τη σελίδα βοήθειας',
'tooltip-ca-nstab-category'       => 'Τέρεν το σελίδαν τη κατηγορίας',
'tooltip-minoredit'               => 'Όντες εφτάτε μικρόν αλλαγήν',
'tooltip-save'                    => "Κρα τ' αλλαγάς",
'tooltip-preview'                 => "Τέρεν τ' αλλαγάς πριχού να κρατείς τη σελίδαν!",
'tooltip-diff'                    => "Δείξον τ' αλλαγάς ντ' εποίκες σο κείμενον.",
'tooltip-compareselectedversions' => "Τερέστε τα διαφοράς τ' εκδωσίων τη σελίδας",

# Metadata
'metadata'          => 'Μεταδεδομένα',
'metadata-expand'   => 'Δείξον τα λεπτομέρειας',
'metadata-collapse' => 'Κρύψον τα λεπτομέρειας',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ούλαι',
'namespacesall' => 'όλεα',
'monthsall'     => 'ούλαι',

# Multipage image navigation
'imgmultigo' => 'Δέβα!',

# Table pager
'table_pager_limit_submit' => 'Δέβα',

# Special:Version
'version' => 'Έκδοση', # Not used as normal message but as header for the special page itself

);
