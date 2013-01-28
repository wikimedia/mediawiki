<?php
/** Simplified Chinese (中文（简体）‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author A911504820
 * @author Alebcay
 * @author Anakmalaysia
 * @author Arlin
 * @author Bencmq
 * @author Biŋhai
 * @author Breawycker
 * @author Chenxiaoqino
 * @author Chenzw
 * @author Chinalace
 * @author Cicku
 * @author Dimension
 * @author Dingyuang
 * @author Fantasticfears
 * @author Fengchao
 * @author Franklsf95
 * @author Gaoxuewei
 * @author Gzdavidwong
 * @author Happy
 * @author Hercule
 * @author Horacewai2
 * @author Hydra
 * @author Hzy980512
 * @author Jding2010
 * @author Jidanni
 * @author Jimmy xu wrk
 * @author Kaganer
 * @author KaiesTse
 * @author Kuailong
 * @author Liangent
 * @author Linforest
 * @author Mark85296341
 * @author MarkAHershberger
 * @author Mys 721tx
 * @author O
 * @author Onecountry
 * @author PhiLiP
 * @author Shinjiman
 * @author Shirayuki
 * @author Shizhao
 * @author Simon Shek
 * @author Supaiku
 * @author Tommyang
 * @author Waihorace
 * @author Wilsonmess
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author 燃玉
 * @author 阿pp
 */

$fallback8bitEncoding = 'windows-936';

$namespaceNames = array(
	NS_MEDIA            => '媒体文件',
	NS_SPECIAL          => '特殊',
	NS_TALK             => '讨论',
	NS_USER             => '用户',
	NS_USER_TALK        => '用户讨论',
	NS_PROJECT_TALK     => '$1讨论',
	NS_FILE             => '文件',
	NS_FILE_TALK        => '文件讨论',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki讨论',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板讨论',
	NS_HELP             => '帮助',
	NS_HELP_TALK        => '帮助讨论',
	NS_CATEGORY         => '分类',
	NS_CATEGORY_TALK    => '分类讨论',
);

$namespaceAliases = array(
	'媒体'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'对话'  => NS_TALK,
	'讨论'	=> NS_TALK,
	'用户'  => NS_USER,
	'用户对话' => NS_USER_TALK,
	'用户讨论' => NS_USER_TALK,
	'图像' => NS_FILE,
	'档案' => NS_FILE,
	'文件' => NS_FILE,
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
	'图像对话' => NS_FILE_TALK,
	'图像讨论' => NS_FILE_TALK,
	'档案对话' => NS_FILE_TALK,
	'档案讨论' => NS_FILE_TALK,
	'文件对话' => NS_FILE_TALK,
	'文件讨论' => NS_FILE_TALK,
	'模板'	=> NS_TEMPLATE,
	'模板对话' => NS_TEMPLATE_TALK,
	'模板讨论' => NS_TEMPLATE_TALK,
	'帮助'	=> NS_HELP,
	'帮助对话' => NS_HELP_TALK,
	'帮助讨论' => NS_HELP_TALK,
	'分类'	=> NS_CATEGORY,
	'分类对话' => NS_CATEGORY_TALK,
	'分类讨论' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( '活跃用户' ),
	'Allmessages'               => array( '所有信息' ),
	'Allpages'                  => array( '所有页面' ),
	'Ancientpages'              => array( '最早页面' ),
	'Badtitle'                  => array( '无效标题' ),
	'Blankpage'                 => array( '空白页面' ),
	'Block'                     => array( '封禁用户' ),
	'Blockme'                   => array( '自我封禁' ),
	'Booksources'               => array( '网络书源' ),
	'BrokenRedirects'           => array( '损坏的重定向页' ),
	'Categories'                => array( '页面分类' ),
	'ChangeEmail'               => array( '修改邮箱' ),
	'ChangePassword'            => array( '修改密码' ),
	'ComparePages'              => array( '比较页面' ),
	'Confirmemail'              => array( '确认电子邮件' ),
	'Contributions'             => array( '用户贡献' ),
	'CreateAccount'             => array( '创建账户' ),
	'Deadendpages'              => array( '断链页面' ),
	'DeletedContributions'      => array( '已删除的用户贡献' ),
	'Disambiguations'           => array( '消歧义页' ),
	'DoubleRedirects'           => array( '双重重定向页', '两次重定向页' ),
	'EditWatchlist'             => array( '编辑监视列表' ),
	'Emailuser'                 => array( '电子邮件用户' ),
	'Export'                    => array( '导出页面' ),
	'Fewestrevisions'           => array( '最少修订页面' ),
	'FileDuplicateSearch'       => array( '搜索重复文件' ),
	'Filepath'                  => array( '文件路径' ),
	'Import'                    => array( '导入页面' ),
	'Invalidateemail'           => array( '无效电邮地址' ),
	'JavaScriptTest'            => array( 'JavaScript测试' ),
	'BlockList'                 => array( '封禁列表' ),
	'LinkSearch'                => array( '搜索网页链接' ),
	'Listadmins'                => array( '管理员列表' ),
	'Listbots'                  => array( '机器人列表' ),
	'Listfiles'                 => array( '文件列表' ),
	'Listgrouprights'           => array( '用户组权限' ),
	'Listredirects'             => array( '重定向页列表' ),
	'Listusers'                 => array( '用户列表' ),
	'Lockdb'                    => array( '锁定数据库' ),
	'Log'                       => array( '日志' ),
	'Lonelypages'               => array( '孤立页面' ),
	'Longpages'                 => array( '长页面' ),
	'MergeHistory'              => array( '合并历史' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'Mostcategories'            => array( '最多分类页面' ),
	'Mostimages'                => array( '最多链接文件' ),
	'Mostlinked'                => array( '最多链接页面' ),
	'Mostlinkedcategories'      => array( '最多链接分类' ),
	'Mostlinkedtemplates'       => array( '最多链接模板' ),
	'Mostrevisions'             => array( '最多修订页面' ),
	'Movepage'                  => array( '移动页面' ),
	'Mycontributions'           => array( '我的贡献' ),
	'Mypage'                    => array( '我的用户页' ),
	'Mytalk'                    => array( '我的讨论页' ),
	'Myuploads'                 => array( '我上传的文件' ),
	'Newimages'                 => array( '新建文件' ),
	'Newpages'                  => array( '新建页面' ),
	'PasswordReset'             => array( '重设密码' ),
	'PermanentLink'             => array( '永久链接' ),
	'Popularpages'              => array( '热点页面' ),
	'Preferences'               => array( '参数设置', '系统设置' ),
	'Prefixindex'               => array( '前缀索引' ),
	'Protectedpages'            => array( '已保护页面' ),
	'Protectedtitles'           => array( '已保护标题' ),
	'Randompage'                => array( '随机页面' ),
	'Randomredirect'            => array( '随机重定向页' ),
	'Recentchanges'             => array( '最近更改' ),
	'Recentchangeslinked'       => array( '链出更改' ),
	'Revisiondelete'            => array( '删除或恢复修订' ),
	'RevisionMove'              => array( '修订版本移动' ),
	'Search'                    => array( '搜索' ),
	'Shortpages'                => array( '短页面' ),
	'Specialpages'              => array( '特殊页面' ),
	'Statistics'                => array( '统计信息' ),
	'Tags'                      => array( '标签' ),
	'Unblock'                   => array( '解除封禁' ),
	'Uncategorizedcategories'   => array( '无分类分类' ),
	'Uncategorizedimages'       => array( '无分类文件' ),
	'Uncategorizedpages'        => array( '无分类页面' ),
	'Uncategorizedtemplates'    => array( '无分类模板' ),
	'Undelete'                  => array( '恢复被删页面' ),
	'Unlockdb'                  => array( '解除数据库锁定' ),
	'Unusedcategories'          => array( '未使用分类' ),
	'Unusedimages'              => array( '未使用文件' ),
	'Unusedtemplates'           => array( '未使用模板' ),
	'Unwatchedpages'            => array( '未受监视页面' ),
	'Upload'                    => array( '上传文件' ),
	'UploadStash'               => array( '上传藏匿' ),
	'Userlogin'                 => array( '用户登录', '用户登入' ),
	'Userlogout'                => array( '用户退出', '用户登出' ),
	'Userrights'                => array( '用户权限' ),
	'Version'                   => array( '版本信息' ),
	'Wantedcategories'          => array( '待撰分类' ),
	'Wantedfiles'               => array( '需要的文件' ),
	'Wantedpages'               => array( '待撰页面' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Watchlist'                 => array( '监视列表' ),
	'Whatlinkshere'             => array( '链入页面' ),
	'Withoutinterwiki'          => array( '无跨维基链接页面' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#重定向', '#REDIRECT' ),
	'notoc'                     => array( '0', '__无目录__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__无图库__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__强显目录__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__目录__', '__TOC__' ),
	'noeditsection'             => array( '0', '__无段落编辑__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', '本月', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', '本月1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', '本月名称', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', '本月简称', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', '今天', 'CURRENTDAY' ),
	'currentday2'               => array( '1', '今天2', 'CURRENTDAY2' ),
	'currentyear'               => array( '1', '今年', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', '此时', '当前时间', 'CURRENTTIME' ),
	'numberofpages'             => array( '1', '页面数', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', '条目数', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', '文件数', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', '用户数', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', '活跃用户数', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', '编辑数', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', '访问数', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', '页面名', 'PAGENAME' ),
	'fullpagename'              => array( '1', '完整页面名', 'FULLPAGENAME' ),
	'img_thumbnail'             => array( '1', '缩略图', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', '缩略图=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', '右', 'right' ),
	'img_left'                  => array( '1', '左', 'left' ),
	'img_none'                  => array( '1', '无', 'none' ),
	'img_width'                 => array( '1', '$1像素', '$1px' ),
	'img_center'                => array( '1', '居中', 'center', 'centre' ),
	'img_page'                  => array( '1', '页数=$1', '$1页', 'page=$1', 'page $1' ),
	'img_link'                  => array( '1', '链接=$1', 'link=$1' ),
	'img_alt'                   => array( '1', '替代文本=$1', 'alt=$1' ),
	'newsectionlink'            => array( '1', '__新段落链接__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__无新段落链接__', '__NONEWSECTIONLINK__' ),
	'language'                  => array( '0', '#语言:', '#LANGUAGE:' ),
	'tag'                       => array( '0', '标记', 'tag' ),
	'pagesize'                  => array( '1', '页面大小', 'PAGESIZE' ),
);

$linkTrail = '/^()(.*)$/sD';

$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'zh';
$dateFormats = array(
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
);

$bookstoreList = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亚马逊' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'卓越亚马逊' => 'http://www.amazon.cn/mn/advancedSearchApp?isbn=$1',
	'当当网' => 'http://search.dangdang.com/search.aspx?key=$1',
	'博客来书店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民书店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下书店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新丝路书店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

$messages = array(
# User preference toggles
'tog-underline' => '链接下划线：',
'tog-justify' => '段落对齐',
'tog-hideminor' => '在最近更改中隐藏小编辑',
'tog-hidepatrolled' => '在最近更改中隐藏已巡查编辑',
'tog-newpageshidepatrolled' => '在新页面列表中隐藏已巡查页面',
'tog-extendwatchlist' => '扩大监视列表以显示所有更改而不仅是最近更改',
'tog-usenewrc' => '根据页面分组最近更改和监视列表（需要JavaScript）',
'tog-numberheadings' => '标题自动编号',
'tog-showtoolbar' => '显示编辑工具条（需要JavaScript）',
'tog-editondblclick' => '双击时编辑页面（需要JavaScript）',
'tog-editsection' => '启用[编辑]链接编辑段落',
'tog-editsectiononrightclick' => '启用右击段落标题编辑段落（需要JavaScript）',
'tog-showtoc' => '显示目录（对于有多于3个标题的页面）',
'tog-rememberpassword' => '在浏览器上记住我的登录状态（最长$1天）',
'tog-watchcreations' => '添加我创建的页面和上传的文件至我的监视列表',
'tog-watchdefault' => '添加我编辑的页面和文件至我的监视列表',
'tog-watchmoves' => '将我移动的页面和文件添加到我的监视列表',
'tog-watchdeletion' => '添加我删除的页面和文件至我的监视列表',
'tog-minordefault' => '默认标记编辑为小编辑',
'tog-previewontop' => '在编辑框上方显示预览',
'tog-previewonfirst' => '首次编辑时显示预览',
'tog-nocache' => '禁用浏览器页面缓存',
'tog-enotifwatchlistpages' => '当我的监视列表中的页面或文件更改时发送电子邮件通知我',
'tog-enotifusertalkpages' => '当我的讨论页更改时发送电子邮件通知我',
'tog-enotifminoredits' => '当页面和文件有小编辑时发送电子邮件通知我',
'tog-enotifrevealaddr' => '在通知电子邮件中显示我的电子邮件地址',
'tog-shownumberswatching' => '显示监视用户数',
'tog-oldsig' => '当前签名：',
'tog-fancysig' => '将签名以wiki文本对待（不产生自动链接）',
'tog-externaleditor' => '默认使用外部编辑器（供高级用户使用，需要在您的计算机上作出一些特别设置。[//www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-externaldiff' => '默认使用外部差异分析（供高级用户使用，需要在您的计算机上作出一些特别设置。[//www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-showjumplinks' => '启用“跳转到”访问链接',
'tog-uselivepreview' => '使用实时预览（需要 Javascript 支持）（实验功能）',
'tog-forceeditsummary' => '未输入编辑摘要时提醒我',
'tog-watchlisthideown' => '在监视列表中隐藏我的编辑',
'tog-watchlisthidebots' => '在监视列表中隐藏机器人的编辑',
'tog-watchlisthideminor' => '在监视列表中隐藏小编辑',
'tog-watchlisthideliu' => '在监视列表中隐藏登录用户',
'tog-watchlisthideanons' => '在监视列表中隐藏匿名用户',
'tog-watchlisthidepatrolled' => '在监视列表中隐藏已巡查的编辑',
'tog-ccmeonemails' => '把我给其他用户发送的电子邮件的副本发送给我',
'tog-diffonly' => '对比差异时不显示页面内容',
'tog-showhiddencats' => '显示隐藏分类',
'tog-noconvertlink' => '停用链接文字转换',
'tog-norollbackdiff' => '执行回退后不显示差异',

'underline-always' => '总是使用',
'underline-never' => '从不使用',
'underline-default' => '浏览器默认设置',

# Font style option in Special:Preferences
'editfont-style' => '编辑区字体样式：',
'editfont-default' => '浏览器默认',
'editfont-monospace' => '等宽字体',
'editfont-sansserif' => '无衬线字体',
'editfont-serif' => '衬线字体',

# Dates
'sunday' => '星期日',
'monday' => '星期一',
'tuesday' => '星期二',
'wednesday' => '星期三',
'thursday' => '星期四',
'friday' => '星期五',
'saturday' => '星期六',
'sun' => '日',
'mon' => '一',
'tue' => '二',
'wed' => '三',
'thu' => '四',
'fri' => '五',
'sat' => '六',
'january' => '1月',
'february' => '2月',
'march' => '3月',
'april' => '4月',
'may_long' => '5月',
'june' => '6月',
'july' => '7月',
'august' => '8月',
'september' => '9月',
'october' => '10月',
'november' => '11月',
'december' => '12月',
'january-gen' => '一月',
'february-gen' => '二月',
'march-gen' => '三月',
'april-gen' => '四月',
'may-gen' => '五月',
'june-gen' => '六月',
'july-gen' => '七月',
'august-gen' => '八月',
'september-gen' => '九月',
'october-gen' => '十月',
'november-gen' => '十一月',
'december-gen' => '十二月',
'jan' => '1月',
'feb' => '2月',
'mar' => '3月',
'apr' => '4月',
'may' => '5月',
'jun' => '6月',
'jul' => '7月',
'aug' => '8月',
'sep' => '9月',
'oct' => '10月',
'nov' => '11月',
'dec' => '12月',

# Categories related messages
'pagecategories' => '$1个分类',
'category_header' => '分类“$1”中的页面',
'subcategories' => '子分类',
'category-media-header' => '分类“$1”中的媒体文件',
'category-empty' => "''本分类尚未包含任何页面或媒体文件。''",
'hidden-categories' => '$1个隐藏分类',
'hidden-category-category' => '隐藏分类',
'category-subcat-count' => '{{PLURAL:$2|本分类只有下列一个子分类。|本分类包含下列$1个子分类，共$2个子分类。}}',
'category-subcat-count-limited' => '本分类包含下列$1个子分类。',
'category-article-count' => '{{PLURAL:$2|本分类只有下列一个页面。|本分类包含下列$1个页面，共有$2个页面。}}',
'category-article-count-limited' => '本分类包含下列$1个页面。',
'category-file-count' => '{{PLURAL:$2|本分类只有下列文件。|本分类包含下列$1个文件，共$2个文件。}}',
'category-file-count-limited' => '本分类包含下列$1个文件。',
'listingcontinuesabbrev' => '续',
'index-category' => '允许索引的页面',
'noindex-category' => '禁止索引的页面',
'broken-file-category' => '包含损坏的文件链接的页面',

'about' => '关于',
'article' => '内容页面',
'newwindow' => '（将于新窗口中打开）',
'cancel' => '取消',
'moredotdotdot' => '更多',
'mypage' => '页面',
'mytalk' => '讨论',
'anontalk' => '该IP地址的讨论',
'navigation' => '导航',
'and' => '和',

# Cologne Blue skin
'qbfind' => '查找',
'qbbrowse' => '浏览',
'qbedit' => '编辑',
'qbpageoptions' => '页面选项',
'qbpageinfo' => '页面信息',
'qbmyoptions' => '我的页面',
'qbspecialpages' => '特殊页面',
'faq' => '常见问题',
'faqpage' => 'Project:常见问题',

# Vector skin
'vector-action-addsection' => '添加话题',
'vector-action-delete' => '删除',
'vector-action-move' => '移动',
'vector-action-protect' => '保护',
'vector-action-undelete' => '恢复',
'vector-action-unprotect' => '更改保护',
'vector-simplesearch-preference' => '启用简化搜索栏（仅Vector皮肤）',
'vector-view-create' => '创建',
'vector-view-edit' => '编辑',
'vector-view-history' => '查看历史',
'vector-view-view' => '阅读',
'vector-view-viewsource' => '查看源代码',
'actions' => '操作',
'namespaces' => '名字空间',
'variants' => '变换',

'errorpagetitle' => '错误',
'returnto' => '返回到$1。',
'tagline' => '来自{{SITENAME}}',
'help' => '帮助',
'search' => '搜索',
'searchbutton' => '搜索',
'go' => '进入',
'searcharticle' => '提交',
'history' => '页面历史',
'history_short' => '历史',
'updatedmarker' => '我上次访问之后的更新',
'printableversion' => '打印版本',
'permalink' => '永久链接',
'print' => '打印',
'view' => '查看',
'edit' => '编辑',
'create' => '创建',
'editthispage' => '编辑本页',
'create-this-page' => '创建本页',
'delete' => '删除',
'deletethispage' => '删除本页',
'undelete_short' => '恢复$1个被删除的编辑',
'viewdeleted_short' => '查看$1个被删除的编辑',
'protect' => '保护',
'protect_change' => '更改',
'protectthispage' => '保护本页',
'unprotect' => '更改保护',
'unprotectthispage' => '更改本页面的保护',
'newpage' => '新页面',
'talkpage' => '讨论本页',
'talkpagelinktext' => '讨论',
'specialpage' => '特殊页面',
'personaltools' => '个人工具',
'postcomment' => '新段落',
'articlepage' => '查看内容页面',
'talk' => '讨论',
'views' => '查看',
'toolbox' => '工具箱',
'userpage' => '查看用户页面',
'projectpage' => '查看项目页面',
'imagepage' => '查看文件页面',
'mediawikipage' => '查看消息页面',
'templatepage' => '查看模板页面',
'viewhelppage' => '查看帮助页面',
'categorypage' => '查看分类页面',
'viewtalkpage' => '查看讨论页面',
'otherlanguages' => '其他语言',
'redirectedfrom' => '（重定向自$1）',
'redirectpagesub' => '重定向页',
'lastmodifiedat' => '本页面最后修改于$1 $2。',
'viewcount' => '此页面已被浏览过$1次。',
'protectedpage' => '受保护页面',
'jumpto' => '跳转至：',
'jumptonavigation' => '导航',
'jumptosearch' => '搜索',
'view-pool-error' => '抱歉，服务器超负荷运转。
过多用户正尝试查看本页面。
请在再次尝试访问本页面之前稍等片刻。

$1',
'pool-timeout' => '等待锁超时',
'pool-queuefull' => '请求队列已满',
'pool-errorunknown' => '未知错误',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '关于{{SITENAME}}',
'aboutpage' => 'Project:关于',
'copyright' => '本站全部文字内容使用$1授权。',
'copyrightpage' => '{{ns:project}}:著作权',
'currentevents' => '新闻动态',
'currentevents-url' => 'Project:新闻动态',
'disclaimers' => '免责声明',
'disclaimerpage' => 'Project:免责声明',
'edithelp' => '编辑帮助',
'edithelppage' => 'Help:编辑',
'helppage' => 'Help:目录',
'mainpage' => '首页',
'mainpage-description' => '首页',
'policy-url' => 'Project:方针',
'portal' => '社区专页',
'portal-url' => 'Project:社区专页',
'privacy' => '隐私权政策',
'privacypage' => 'Project:隐私权政策',

'badaccess' => '权限错误',
'badaccess-group0' => '您被禁止执行您刚才请求的操作。',
'badaccess-groups' => '您刚才请求的操作只有{{PLURAL:$2|这个用户组|以下用户组}}中的用户才能使用： $1',

'versionrequired' => '需要版本为$1的MediaWiki',
'versionrequiredtext' => '需要版本为$1的MediaWiki才能使用本页。
请见[[Special:Version|版本页面]]。',

'ok' => '确定',
'retrievedfrom' => '来自“$1”',
'youhavenewmessages' => '您有$1（$2）。',
'newmessageslink' => '新信息',
'newmessagesdifflink' => '最后更改',
'youhavenewmessagesfromusers' => '您有来自{{PLURAL:$3| 另一位用户| $3位用户}}的$1（$2）。',
'youhavenewmessagesmanyusers' => '您有来自多位用户的$1（$2）。',
'newmessageslinkplural' => '{{PLURAL:$1|一条新信息|新信息}}',
'newmessagesdifflinkplural' => '最新{{PLURAL:$1|更改}}',
'youhavenewmessagesmulti' => '您在$1有新信息',
'editsection' => '编辑',
'editold' => '编辑',
'viewsourceold' => '查看源代码',
'editlink' => '编辑',
'viewsourcelink' => '查看源代码',
'editsectionhint' => '编辑章节：$1',
'toc' => '目录',
'showtoc' => '显示',
'hidetoc' => '隐藏',
'collapsible-collapse' => '折叠',
'collapsible-expand' => '展开',
'thisisdeleted' => '查看或恢复$1？',
'viewdeleted' => '查看$1？',
'restorelink' => '$1个被删除的编辑',
'feedlinks' => '订阅：',
'feed-invalid' => '无效的订阅类型。',
'feed-unavailable' => '不提供联合订阅源',
'site-rss-feed' => '$1的RSS订阅',
'site-atom-feed' => '$1的Atom',
'page-rss-feed' => '“$1”的RSS订阅',
'page-atom-feed' => '“$1”的Atom订阅',
'red-link-title' => '$1（页面不存在）',
'sort-descending' => '降序',
'sort-ascending' => '升序',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => '页面',
'nstab-user' => '用户页面',
'nstab-media' => '媒体页面',
'nstab-special' => '特殊页面',
'nstab-project' => '项目页面',
'nstab-image' => '文件',
'nstab-mediawiki' => '信息',
'nstab-template' => '模板',
'nstab-help' => '帮助页面',
'nstab-category' => '分类',

# Main script and global functions
'nosuchaction' => '这个命令不存在',
'nosuchactiontext' => 'URL 指定的命令无效。您可能误输入了 URL 地址，或者点击了错误的链接。这一错误也有可能是由{{SITENAME}}所使用软件自身的错误导致的。',
'nosuchspecialpage' => '此特殊页面不存在',
'nospecialpagetext' => '<strong>您请求的特殊页面无效。</strong>

[[Special:SpecialPages|{{int:specialpages}}]]中列出了所有有效的特殊页面。',

# General errors
'error' => '错误',
'databaseerror' => '数据库错误',
'dberrortext' => '发生数据库查询语法错误。
可能是由于软件自身的错误所引起。
最后一次数据库查询指令是：
<blockquote><code>$1</code></blockquote>
来自于函数“<code>$2</code>”。
数据库返回错误“<samp>$3: $4</samp>”。',
'dberrortextcl' => '发生了数据库查询语法错误。最后一次数据库查询指令是：
“$1”
来自函数“$2”内。数据库返回错误“$3: $4”。',
'laggedslavemode' => "'''警告'''：页面可能未包含最近的更新。",
'readonly' => '数据库被锁定',
'enterlockreason' => '请输入锁定的原因，包括预计重新开放的时间',
'readonlytext' => '数据库目前禁止输入新内容及更改，
这很可能是由于数据库正在维修，完成后即可恢复。

管理员有如下解释：$1',
'missing-article' => '数据库找不到预期的页面文字：“$1”$2。

这通常是由于点击了链向旧有差异或历史的链接，而原有修订已被删除导致的。

如果情况不是这样，您可能找到了软件的一个内部错误。请记录下URL地址，并向[[Special:ListUsers/sysop|管理员]]报告。',
'missingarticle-rev' => '（修订版本编号：$1）',
'missingarticle-diff' => '（差异：$1，$2）',
'readonly_lag' => '从数据库服务器正在从主服务器上更新，数据库已被自动锁定',
'internalerror' => '内部错误',
'internalerror_info' => '内部错误：$1',
'fileappenderrorread' => '当附加时无法读取"$1"。',
'fileappenderror' => '无法将“$1”附加到“$2”。',
'filecopyerror' => '无法将文件“$1”复制到“$2”。',
'filerenameerror' => '无法将文件“$1”重命名为“$2”。',
'filedeleteerror' => '无法删除文件“$1”。',
'directorycreateerror' => '无法创建目录“$1”。',
'filenotfound' => '找不到文件“$1”。',
'fileexistserror' => '无法写入文件“$1”：文件已存在',
'unexpected' => '非正常值：“$1”=“$2”。',
'formerror' => '错误：无法提交表单',
'badarticleerror' => '无法在此页进行该操作。',
'cannotdelete' => '无法删除页面或图像“$1”。
它可能已被其他人删除了。',
'cannotdelete-title' => '无法删除“$1”',
'delete-hook-aborted' => '删除被扩展钩子取消。钩子并没有给出解释。',
'badtitle' => '错误的标题',
'badtitletext' => '所请求页面的标题是无效的、不存在，跨语言或跨wiki链接的标题错误。它可能包含一个或更多的不能用于标题的字符。',
'perfcached' => '下列数据已缓存，但可能已过时。最高{{PLURAL:$1|一个结果|$1个结果}}在缓存中可用。',
'perfcachedts' => '下列数据已缓存，最后更新于$1。缓存中最多可有{{PLURAL:$4|1个结果|$4个结果}}。',
'querypage-no-updates' => '当前禁止对此页面进行更新。
此处的数据将不能被立即刷新。',
'wrong_wfQuery_params' => '错误的参数被传递到 wfQuery（）<br />
函数：$1<br />
查询：$2',
'viewsource' => '查看源代码',
'viewsource-title' => '查看$1的源代码',
'actionthrottled' => '操作被限制',
'actionthrottledtext' => '基于反垃圾的考量，您被限制在短时间内多次重复该操作，但您已超过此上限。请在数分钟后再尝试。',
'protectedpagetext' => '该页面已被保护以防止编辑和其他操作。',
'viewsourcetext' => '您可以查看并复制此页面的源代码：',
'viewyourtext' => "您可以查看并复制'''您对此页面作出编辑后'''的源代码：",
'protectedinterface' => '该页提供此wiki软件的界面文字，它已被保护以防止恶意修改。
如欲修改所有wiki的翻译，请到[//translatewiki.net/ translatewiki.net]上的MediaWiki本地化计划。',
'editinginterface' => "'''警告：'''您正在编辑的页面是用于提供软件的界面文字。
改变此页将影响其他在此wiki上的用户界面外观。
如欲修改所有wiki的翻译，请到[//translatewiki.net/ translatewiki.net]上的MediaWiki本地化计划。",
'sqlhidden' => '（SQL查询已隐藏）',
'cascadeprotected' => '此页面已被保护，因为这个页面被以下已标注“联锁保护”的{{PLURAL:$1|一个|多个}}被保护页面包含：
$2',
'namespaceprotected' => "您没有权限编辑'''$1'''名字空间内的页面。",
'customcssprotected' => '您没有权限编辑此CSS页面，因为它包含另一位用户的个人设置。',
'customjsprotected' => '您没有权限编辑此JavaScript页面，因为它包含另一位用户的个人设置。',
'ns-specialprotected' => '您不能编辑特殊页面。',
'titleprotected' => '此标题已被[[User:$1|$1]]保护以防止创建。理由是“$2”。',
'filereadonlyerror' => '因为媒体库$2处于只读模式而无法修改文件$1。

执行锁定的管理员给出如下解释：$3。',
'invalidtitle-knownnamespace' => '使用名字空间“$2”和文本“$3”的无效标题',
'invalidtitle-unknownnamespace' => '使用未知名字空间编号$1和文本“$2”的无效标题',
'exception-nologin' => '尚未登录',
'exception-nologin-text' => '此操作需要您先登录。',

# Virus scanner
'virus-badscanner' => "错误的配置：未知的病毒扫描程序：''$1''",
'virus-scanfailed' => '扫描失败（代码 $1）',
'virus-unknownscanner' => '未知的反病毒软件：',

# Login and logout pages
'logouttext' => "'''您现在已经退出。'''

您可以继续以匿名方式使用{{SITENAME}}，或再次以相同或不同用户身份[[Special:UserLogin|登录]]。请注意一些页面可能仍然显示您为登录状态，直到您清空您的浏览器缓存为止。",
'welcomecreation' => '== 欢迎，$1！ ==
你的账户已创建。请别忘记更改你的[[Special:Preferences|{{SITENAME}}系统设置]]。',
'yourname' => '用户名：',
'yourpassword' => '密码：',
'yourpasswordagain' => '再次输入密码：',
'remembermypassword' => '自动登录（最长$1{{PLURAL:$1|天|天}}）',
'securelogin-stick-https' => '登录后继续使用 HTTPS 连接',
'yourdomainname' => '您的域名：',
'password-change-forbidden' => '您不能在此 wiki 上修改密码。',
'externaldberror' => '这可能是由于验证数据库错误或您被禁止更新您的外部账号。',
'login' => '登录',
'nav-login-createaccount' => '登录/创建账户',
'loginprompt' => '您必须启用 Cookies 才能登录{{SITENAME}}。',
'userlogin' => '登录/创建账户',
'userloginnocreate' => '登录',
'logout' => '退出',
'userlogout' => '退出',
'notloggedin' => '未登录',
'nologin' => '没有账户？$1。',
'nologinlink' => '创建账户',
'createaccount' => '创建账户',
'gotaccount' => '已经拥有账户？请$1。',
'gotaccountlink' => '登录',
'userlogin-resetlink' => '忘记了您的登录信息？',
'createaccountmail' => '通过电子邮件',
'createaccountreason' => '原因：',
'badretype' => '您所输入的密码并不相同。',
'userexists' => '用户名已存在。请使用其他名称。',
'loginerror' => '登录错误',
'createaccounterror' => '无法建立账户：$1',
'nocookiesnew' => '已成功创建新账户！检测到您已禁用 Cookies，请先启用然后登录。',
'nocookieslogin' => '本站使用 Cookies 进行登录，检测到您已禁用 Cookies，请先启用然后重试。',
'nocookiesfornew' => '该用户账户尚未创建，因为我们不能确认它的来源。
请确保您已经启用 Cookies，刷新本页后重试。',
'noname' => '您没有输入有效的用户名。',
'loginsuccesstitle' => '登录成功',
'loginsuccess' => "'''“$1”，欢迎登录{{SITENAME}}。'''",
'nosuchuser' => '找不到用户“$1”。用户名对大小写和繁简体是区分的。请检查您的拼写是否有错误，或者[[Special:UserLogin/signup|注册]]。',
'nosuchusershort' => '找不到用户“$1”。请检查您的拼写是否有错误。',
'nouserspecified' => '您需要指定一个用户名。',
'login-userblocked' => '该用户已被封禁，禁止登录。',
'wrongpassword' => '密码错误，请重试。',
'wrongpasswordempty' => '您没有输入密码，请重试！',
'passwordtooshort' => '您的密码至少需要$1个字符。',
'password-name-match' => '您的密码必须和您的用户名不相同。',
'password-login-forbidden' => '这个用户名称及密码的使用是被禁止的。',
'mailmypassword' => '用电子邮件发送新密码',
'passwordremindertitle' => '{{SITENAME}}的新临时密码',
'passwordremindertext' => '有人（可能是您，来自IP地址$1）已请求{{SITENAME}}的新密码（$4）。
用户“$2”的一个新临时密码现在已被设置好为“$3”。
如果这个动作是您所指示的，您便需要立即登录并选择一个新的密码。
您的临时密码会于$5天内过期。

如果是其他人发出了该请求，或者您已经记起了您的密码并不准备改变它，
您可以忽略此消息并继续使用您的旧密码。',
'noemail' => '用户"$1"没有登记电子邮件地址。',
'noemailcreate' => '您需要提供一个有效的电子邮件地址',
'passwordsent' => '用户"$1"的新密码已经寄往所登记的电子邮件地址。
请在收到后再登录。',
'blocked-mailpassword' => '您的 IP 地址已被禁止编辑，同时为了防止密码恢复功能被滥用，已禁用该功能。',
'eauthentsent' => '一封确认信已经发送到推荐的地址。在发送其它邮件到此账户前，您必须首先依照这封信中的指导确认这个电子邮箱真实有效。',
'throttled-mailpassword' => '密码提醒已在最近$1小时内发送。为了安全起见，在每$1小时内只能发送一个密码提醒。',
'mailerror' => '发送邮件错误：$1',
'acct_creation_throttle_hit' => '抱歉！您已经创建了$1个账号，已经达到最大允许注册数量。目前使用本 IP 的来访者将不能再创建任何账户。',
'emailauthenticated' => '您的电子邮箱地址已经于$2 $3确认有效。',
'emailnotauthenticated' => '您的邮箱地址<strong>尚未被认证</strong>。下列功能将不会发送任何邮件。',
'noemailprefs' => '指定一个电子邮箱地址以使用此功能。',
'emailconfirmlink' => '确认您的邮箱地址',
'invalidemailaddress' => '邮箱地址格式不正确，请输入正确的邮箱地址或清空该输入框。',
'cannotchangeemail' => '本wiki不允许对账户的电子邮件地址进行更改。',
'emaildisabled' => '此站点不能发送电子邮件。',
'accountcreated' => '已建立账户',
'accountcreatedtext' => '$1的账户已经被创建。',
'createaccount-title' => '在{{SITENAME}}中创建新账户',
'createaccount-text' => '有人在{{SITENAME}}中利用您的邮箱创建了一个名为 "$2" 的新帐户（$4），密码是 "$3" 。您应该立即登录并更改密码。

如果该账户创建错误的话，您可以忽略此信息。',
'usernamehasherror' => '用户名中不可包含哈希（hash）字符',
'login-throttled' => '您最近已经尝试多次登录。
请稍后重试。',
'login-abort-generic' => '登录失败 - 已终止',
'loginlanguagelabel' => '语言：$1',
'suspicious-userlogout' => '注销请求被拒绝，因为它似乎是由有设计缺陷的浏览器或缓存代理发出的。',

# E-mail sending
'php-mail-error-unknown' => '在 PHP 的 mail() 函数中的未知错误',
'user-mail-no-addy' => '尝试发送邮件而不附带电子邮件地址。',

# Change password dialog
'resetpass' => '更改密码',
'resetpass_announce' => '您是通过发送到电子邮箱的临时密码登录的。要完成登录，请设定一个新的密码：',
'resetpass_text' => '<!-- 在这里添加文字 -->',
'resetpass_header' => '更改账户密码',
'oldpassword' => '旧密码：',
'newpassword' => '新密码：',
'retypenew' => '确认密码：',
'resetpass_submit' => '设定密码并登录',
'resetpass_success' => '您已经修改了您的密码！正在为您登录……',
'resetpass_forbidden' => '无法更改密码',
'resetpass-no-info' => '您必须登录后直接进入这个页面。',
'resetpass-submit-loggedin' => '更改密码',
'resetpass-submit-cancel' => '取消',
'resetpass-wrong-oldpass' => '临时密码或当前密码无效。您可能已经更改了您的密码，或者请求了新的临时密码。',
'resetpass-temp-password' => '临时密码：',

# Special:PasswordReset
'passwordreset' => '重置密码',
'passwordreset-text' => '完成此表格以接收一封包含您的帐户详情的提醒邮件。',
'passwordreset-legend' => '重置密码',
'passwordreset-disabled' => '此wiki已经禁用密码重置。',
'passwordreset-pretext' => '{{PLURAL:$1||输入下面的数据块之一}}',
'passwordreset-username' => '用户名：',
'passwordreset-domain' => '域：',
'passwordreset-capture' => '查看生成的电子邮件吗？',
'passwordreset-capture-help' => '如果您选中此框，电子邮件（包括临时密码）将显示，并发送给用户。',
'passwordreset-email' => '电子邮件地址：',
'passwordreset-emailtitle' => '在 {{SITENAME}} 的帐户详细信息',
'passwordreset-emailtext-ip' => '有人通过IP地址 $1 （可能是您）请求获取 {{SITENAME}} ($4)上相关账户的密码提示。{{PLURAL:$3|以下账户|此账户}}与该电子邮件地址关联：

$2

{{PLURAL:$3|这个|这个}}临时密码将会在{{PLURAL:$5|一天|$5 天}}后过期。请立即登录并设置新的密码。如果请求是其他人发出的，或者您已回忆起您的旧密码并不再需要更改，您可以忽略本条消息并继续使用原密码。',
'passwordreset-emailtext-user' => '用户 $1 请求获取 {{SITENAME}} ($4)上您的账户的密码提示。{{PLURAL:$3|以下账户|此账户}}与该电子邮件地址关联：

$2

{{PLURAL:$3|这个|这个}}临时密码将会在{{PLURAL:$5|一天|$5 天}}后过期。请立即登录并设置新的密码。如果请求是其他人发出的，或者您已回忆起您的旧密码并不再需要更改，您可以忽略本条消息并继续使用原密码。',
'passwordreset-emailelement' => '用户名：$1
临时密码：$2',
'passwordreset-emailsent' => '已发送提醒电子邮件。',
'passwordreset-emailsent-capture' => '提醒电子邮件已发送，并在下面显示。',
'passwordreset-emailerror-capture' => '生成的提醒电子邮件如下所示，但发送失败：$1',

# Special:ChangeEmail
'changeemail' => '更改电子邮件地址',
'changeemail-header' => '更改帐户的电子邮件地址',
'changeemail-text' => '完成此窗体可以更改您的电子邮件地址。您将需要输入您的密码以确认此更改。',
'changeemail-no-info' => '
您必须登录以直接访问本页。',
'changeemail-oldemail' => '当前的电子邮件地址：',
'changeemail-newemail' => '新的电子邮件地址：',
'changeemail-none' => '（无）',
'changeemail-submit' => '更改电子邮箱',
'changeemail-cancel' => '取消',

# Edit page toolbar
'bold_sample' => '粗体文字',
'bold_tip' => '粗体文字',
'italic_sample' => '斜体文字',
'italic_tip' => '斜体文字',
'link_sample' => '链接文字',
'link_tip' => '内部链接',
'extlink_sample' => 'http://www.example.com 链接文字',
'extlink_tip' => '外部链接（加前缀 http://）',
'headline_sample' => '大标题文字',
'headline_tip' => '2级标题文字',
'nowiki_sample' => '在此插入非格式文本',
'nowiki_tip' => '插入非格式文本',
'image_sample' => '示例.jpg',
'image_tip' => '插入文件',
'media_sample' => '示例.ogg',
'media_tip' => '文件链接',
'sig_tip' => '带时间戳的签名',
'hr_tip' => '水平线（请小心使用）',

# Edit pages
'summary' => '摘要：',
'subject' => '标题：',
'minoredit' => '标记为小编辑',
'watchthis' => '监视本页',
'savearticle' => '保存本页',
'preview' => '预览',
'showpreview' => '显示预览',
'showlivepreview' => '实时预览',
'showdiff' => '显示差异',
'anoneditwarning' => "'''警告：'''您没有登录。
您的IP地址将记录在此页的编辑历史中。",
'anonpreviewwarning' => "''您没有登录。保存页面将会把您的IP地址记录在此页的编辑历史中。''",
'missingsummary' => "'''提示：'''您没有提供编辑摘要。如果您再次点击“{{int:savearticle}}”，您的编辑将不带编辑摘要保存。",
'missingcommenttext' => '请在下面输入评论。',
'missingcommentheader' => "'''提示：''' 您还没有为此评论提供一个标题。如果您再次点击“{{int:savearticle}}”，您的编辑将不带标题保存。",
'summary-preview' => '摘要预览：',
'subject-preview' => '标题预览：',
'blockedtitle' => '用户被封禁',
'blockedtext' => "'''您的用户名或 IP 地址已被封禁。'''

本次封禁操作由$1完成，封禁原因为''$2''。

* 开始时间：$8
* 结束时间：$6
* 拟封禁对象：$7

您可以联系$1或其他的[[{{MediaWiki:Grouppage-sysop}}|管理员]]以申诉此次封禁。若您已在[[Special:Preferences|个人设置]]中设置了一个有效的电子邮件地址，且未被封禁电子邮件功能，则您可通过“发送邮件”功能来联系相关管理员。您当前的 IP 地址为$3，此次封禁的 ID 为#$5。请在您的查询中注明上述所有信息。",
'autoblockedtext' => "您的 IP 地址因与另一位已封禁用户的相同而被自动封禁，该用户是由$1封禁的。封禁原因如下：

:''$2''

* 开始时间：$8
* 结束时间：$6
* 拟封禁对象：$7

您可以联系$1或其他的[[{{MediaWiki:Grouppage-sysop}}|管理员]]以申诉此次封禁。若您已在[[Special:Preferences|个人设置]]中设置了一个有效的电子邮件地址，且未被封禁电子邮件功能，则您可通过“发送邮件”功能来联系相关管理员。您当前的 IP 地址是$3，此次封禁的 ID 为#$5。请在您的查询中注明上述所有信息。",
'blockednoreason' => '无给出原因',
'whitelistedittext' => '您必须先$1才可编辑页面。',
'confirmedittext' => '你必须确认你的电子邮件地址才能编辑页面。请通过[[Special:Preferences|系统设置]]设置并确认你的电子邮件地址。',
'nosuchsectiontitle' => '没有这个段落',
'nosuchsectiontext' => '您尝试编辑的章节并不存在。
可能是在您查看页面时已经移动或删除。',
'loginreqtitle' => '需要登录',
'loginreqlink' => '登录',
'loginreqpagetext' => '您必须$1才能查看其它页面。',
'accmailtitle' => '密码已寄出',
'accmailtext' => "'$1'的密码已经被发送到$2。",
'newarticle' => '（新页面）',
'newarticletext' => '您进入了一个尚未创建的页面。
要创建该页面，请在下面的编辑框中输入内容（详情参见[[{{MediaWiki:Helppage}}|帮助页]]）。
如果您误入此页，请点击浏览器中的“返回”按钮。',
'anontalkpagetext' => "---- ''这是一个还未建立账户的匿名用户的讨论页, 因此我们只能用IP地址来与他或她联络。该IP地址可能由几名用户共享。如果您是一名匿名用户并认为此页上的评语与您无关，请[[Special:UserLogin/signup|创建新账户]]或[[Special:UserLogin|登录]]以避免在未来与其他匿名用户混淆。''",
'noarticletext' => '本页面目前没有内容。您可以在其他页面中[[Special:Search/{{PAGENAME}}|搜索该页标题]]、<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜索相关日志]或[{{fullurl:{{FULLPAGENAME}}|action=edit}} 编辑本页]。</span>',
'noarticletext-nopermission' => '此页目前没有内容。
您可以在其它页[[Special:Search/{{PAGENAME}}|搜寻此页标题]]，或<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜寻有关日志]</span>，但您没有权限建立此页。',
'missing-revision' => '“{{PAGENAME}}”的修订#$1不存在。

这通常是因为进入了一个已被删除的页面的历史链接。
详细信息可以在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中找到。',
'userpage-userdoesnotexist' => '用户账户"$1"尚未注册。
请在创建／编辑该页之前进行核对。',
'userpage-userdoesnotexist-view' => '用户账户“$1”未曾创建。',
'blocked-notice-logextract' => '这位用户目前已被封禁。以下提供最近的封禁日志以供参考：',
'clearyourcache' => "'''注意：'''保存之后，您必须清除浏览器缓存才能看到做出的更改。
* '''火狐（Firefox）/Safari：'''按住“Shift”，同时点击“刷新”，或按“Ctrl-F5”或“Ctrl-R”（Mac为“⌘-R”）
* '''谷歌浏览器（Google Chrome）：'''按“Ctrl-Shift-R”（Mac为“⌘-Shift-R”）
* '''Internet Explorer：'''按住“Ctrl”，同时点击“刷新”，或按“Ctrl-F5”
* '''Opera：'''在“工具→首选项”中清除缓存",
'usercssyoucanpreview' => "'''提示：''' 在保存前请用“{{int:showpreview}}”按钮来测试您新的 CSS 。",
'userjsyoucanpreview' => "'''提示：''' 在保存前请用“{{int:showpreview}}”按钮来测试您新的 JavaScript 。",
'usercsspreview' => "'''记住您只是在预览您的个人 CSS。'''
'''还没有保存！'''",
'userjspreview' => "'''记住您只是在测试／预览您的个人 JavaScript。'''
'''还没有保存！'''",
'sitecsspreview' => "'''记住您现在只是预览此 CSS。'''
'''尚未保存！'''",
'sitejspreview' => "'''记住您现在只是预览此 JavaScript 代码。'''
'''尚未保存！'''",
'userinvalidcssjstitle' => "'''警告：''' 不存在皮肤\"\$1\"。注意自定义的 .css 和 .js 页要使用小写标题，例如，{{ns:user}}:Foo/vector.css 不同于 {{ns:user}}:Foo/Vector.css。",
'updated' => '（已更新）',
'note' => "'''注意：'''",
'previewnote' => "'''请记住这仅为预览。'''您的更改还未保存！",
'continue-editing' => '往编辑框',
'previewconflict' => '这个预览显示了上面文字编辑区中的内容。它们将在您保存后出现。',
'session_fail_preview' => "'''抱歉！由于会话数据丢失，我们不能处理您的编辑。'''请重试。如果仍然失败，请尝试[[Special:UserLogout|注销]]然后重新登录。",
'session_fail_preview_html' => "'''抱歉！我们不能处理您在会话数据丢失时的编辑。'''

''由于{{SITENAME}}允许使用原始的 HTML，为了防范 JavaScript 攻击，预览已被隐藏。''

'''如果这是一次合法的编辑，请尝试重试。'''如果依然不行，请[[Special:UserLogout|注销]]并重新登录。",
'token_suffix_mismatch' => "'''由于您用户端中的编辑令牌毁损了一些标点符号字元，您的编辑已经被拒绝。'''
此次编辑被拒绝以防止页面文本损坏。
这种情况通常在您使用含有故障的网页式匿名代理服务的时候出现。",
'edit_form_incomplete' => "'''编辑表单的某些部分没有传送至服务器；请检查您的编辑内容是否完整并重试。'''",
'editing' => '编辑“$1”',
'creating' => '创建 $1',
'editingsection' => '编辑“$1”（段落）',
'editingcomment' => '编辑“$1”（新段落）',
'editconflict' => '编辑冲突：$1',
'explainconflict' => "有人在您开始编辑后更改了页面。
上面的文字框内显示的是目前本页的内容。
您所做的修改显示在下面的文字框中。
您应当将您的修改加入至现有的内容中。
'''只有'''在上面文字框中的内容会在您点击“{{int:savearticle}}”后被保存。",
'yourtext' => '您的文字',
'storedversion' => '已保存的版本',
'nonunicodebrowser' => "'''警告：您的浏览器不兼容Unicode编码。'''这里有一个工作区将使您能安全地编辑页面：非ASCII字符将以十六进制编码方式出现在编辑框中。",
'editingold' => "'''警告：您正在编辑的是本页的旧版本。'''
如果您保存它的话，在旧版本之后的任何修改都将丢失。",
'yourdiff' => '差异',
'copyrightwarning' => "请注意您对{{SITENAME}}的所有贡献都被认为是在$2下发布，请查看在$1的细节。
如果您不希望您的文字被任意修改和再散布，请不要提交。<br />
您同时也要向我们保证您所提交的内容是您自己所作，或得自一个不受版权保护或相似自由的来源。
'''不要在未获授权的情况下发表！'''<br />",
'copyrightwarning2' => "请注意您对{{SITENAME}}的所有贡献
都可能被其他贡献者编辑，修改或删除。
如果您不希望您的文字被任意修改和再散布，请不要提交。<br />
您同时也要向我们保证您所提交的内容是您自己所作，或得自一个不受版权保护或相似自由的来源（参阅$1的细节）。
'''不要在未获授权的情况下发表！'''",
'longpageerror' => "'''错误：您所提交的文本长度有{{PLURAL:$1|1|$1}}KB，这大于{{PLURAL:$2|1|$2}}KB的最大值。'''
因此，该文本无法保存。",
'readonlywarning' => "'''警告：数据库被锁定以进行维护，所以您目前将无法保存您的修改。'''您或许希望将本段文字先复制并保存到文本文件，并在稍后进行修改。

锁定数据库的管理员有如下解释：$1",
'protectedpagewarning' => "'''警告：本页面已被保护，只有拥有管理员权限的用户可以编辑。'''下面提供最后的日志条目以供参考：",
'semiprotectedpagewarning' => "'''注意：'''本页面已被保护，只有注册用户可以编辑。下面提供最后的日志条目以供参考：",
'cascadeprotectedwarning' => "'''警告：'''本页面已被保护，只有拥有管理员权限的用户可以编辑，因为其包含于以下受连锁保护的{{PLURAL:$1|页面}}：",
'titleprotectedwarning' => "'''警告：本页面已被保护，创建本页面需要[[Special:ListGroupRights|特定权限]]。'''下面提供最后的日志条目以供参考：",
'templatesused' => '该页面使用的{{PLURAL:$1|模板}}：',
'templatesusedpreview' => '本预览使用的{{PLURAL:$1|模板}}：',
'templatesusedsection' => '该段落使用的{{PLURAL:$1|模板}}：',
'template-protected' => '（保护）',
'template-semiprotected' => '（半保护）',
'hiddencategories' => '本页面属于$1个隐藏分类：',
'edittools' => '<!-- 这里的文字将显示在编辑和上传表格下面。 -->',
'nocreatetitle' => '创建页面受限',
'nocreatetext' => '{{SITENAME}}限制了创建新页面功能。您可以返回并编辑已有的页面，或者[[Special:UserLogin|登录或创建新账户]]。',
'nocreate-loggedin' => '您没有权限创建新页面。',
'sectioneditnotsupported-title' => '段落编辑不支持',
'sectioneditnotsupported-text' => '本页面不支持段落编辑。',
'permissionserrors' => '权限错误',
'permissionserrorstext' => '因为下列{{PLURAL:$1|原因}}，您没有权限执行该操作：',
'permissionserrorstext-withaction' => '因为以下{{PLURAL:$1|原因}}，你没有权限$2：',
'recreate-moveddeleted-warn' => "'''警告：你正在重新创建曾经被删除的页面。'''

你应该考虑继续编辑本页是否合适。这里提供本页的删除和移动记录以供参考：",
'moveddeleted-notice' => '本页面已被删除。下面提供本页的删除和移动日志以供参考。',
'log-fulllog' => '查看完整日志',
'edit-hook-aborted' => '编辑被hook指令取消。
无解释。',
'edit-gone-missing' => '不能更新页面。
它可能刚刚被删除。',
'edit-conflict' => '编辑冲突。',
'edit-no-change' => '由于文字无任何改动，您的编辑已被忽略。',
'edit-already-exists' => '不可以建立一个新页面。
它已经存在。',
'defaultmessagetext' => '默认消息文本',

# Parser/template warnings
'expensive-parserfunction-warning' => '警告：这个页面有太多高昂的语法功能调用。

它应该少过$2次呼叫，现在有$1次呼叫。',
'expensive-parserfunction-category' => '页面中有太多耗费的语法功能呼叫',
'post-expand-template-inclusion-warning' => '警告：包含模板大小过大。
一些模板将不会包含。',
'post-expand-template-inclusion-category' => '模板包含上限已经超过的页面',
'post-expand-template-argument-warning' => '警告：这个页面有最少一个模参数有过大扩展大小。
这些参数会被略过。',
'post-expand-template-argument-category' => '包含着略过模板参数的页面',
'parser-template-loop-warning' => '检查到模板循环：[[$1]]',
'parser-template-recursion-depth-warning' => '模板递归深度越限（$1）',
'language-converter-depth-warning' => '字词转换器深度越限（$1）',
'node-count-exceeded-category' => '页面的节点数超出限制',
'node-count-exceeded-warning' => '页面超出了节点数',
'expansion-depth-exceeded-category' => '扩展深度超出限制的页面',
'expansion-depth-exceeded-warning' => '页面超过了扩展深度',
'parser-unstrip-loop-warning' => '检测到回圈',
'parser-unstrip-recursion-limit' => '递归超过限制 ($1)',
'converter-manual-rule-error' => '手动语言转换规则中检测到错误',

# "Undo" feature
'undo-success' => '此编辑可被撤销。请检查以下对比以核实这正是您想做的，然后保存以下更改完成撤销编辑。',
'undo-failure' => '因存在冲突的中间编辑，本编辑不能撤销。',
'undo-norev' => '由于其修订版本不存在或已删除，此编辑不能撤销。',
'undo-summary' => '撤销由[[Special:Contributions/$2|$2]]（[[User talk:$2|讨论]]）所作出的修订$1',

# Account creation failure
'cantcreateaccounttitle' => '无法创建账户',
'cantcreateaccount-text' => "从该IP地址（'''$1'''）创建账户已被[[User:$3|$3]]禁止。

$3的理由是''$2''",

# History pages
'viewpagelogs' => '查看本页面的日志',
'nohistory' => '本页面没有编辑历史记录。',
'currentrev' => '最后版本',
'currentrev-asof' => '$1的最后版本',
'revisionasof' => '$1的版本',
'revision-info' => '$1$2的版本',
'previousrevision' => '←上一版本',
'nextrevision' => '下一版本→',
'currentrevisionlink' => '最后版本',
'cur' => '当前',
'next' => '后继',
'last' => '先前',
'page_first' => '最早',
'page_last' => '最后',
'histlegend' => "差异选择：选出需要对比的版本，按“回车键”或下方的按钮进行对比。<br />
说明：'''（{{int:cur}}）'''=与最后版本之间的差异，'''（{{int:last}}）'''=与上一版本之间的差异，'''{{int:minoreditletter}}'''=小编辑。",
'history-fieldset-title' => '浏览历史',
'history-show-deleted' => '仅已删除的',
'histfirst' => '最早',
'histlast' => '最后',
'historysize' => '（$1字节）',
'historyempty' => '（空）',

# Revision feed
'history-feed-title' => '版本历史',
'history-feed-description' => '本wiki的该页面的版本历史',
'history-feed-item-nocomment' => '$2 $1',
'history-feed-empty' => '所请求的页面不存在。它可能已被删除或重命名。
尝试[[Special:Search|搜索本站]]获得相关的新建页面。',

# Revision deletion
'rev-deleted-comment' => '（编辑摘要被删除）',
'rev-deleted-user' => '（用户名被删除）',
'rev-deleted-event' => '（日志条目被删除）',
'rev-deleted-user-contribs' => '[用户名或IP地址被删除 - 编辑在贡献中隐藏]',
'rev-deleted-text-permission' => "本页面版本已被'''删除'''。详情请见[{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} 删除日志]。",
'rev-deleted-text-unhide' => "本页面的修订版已被'''删除'''。详情请见[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]。您可以[$1 查看当前版本]以继续。",
'rev-suppressed-text-unhide' => "该页面修订已经被'''监督隐藏'''。在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 监督日志]中可以找到详细的信息。如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-deleted-text-view' => "本页面的修订版已被'''删除'''。您可以查看它，详情请见[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]。",
'rev-suppressed-text-view' => "该页面修订已经被'''监督隐藏'''。您可以查看它。在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 监督日志]中可以找到详细的信息。",
'rev-deleted-no-diff' => "因为其中一次修订已被'''删除'''，您不可以查看这个差异。
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中可以找到更多的信息。",
'rev-suppressed-no-diff' => "该页面的其中一次修订版已被'''删除'''，您无法查看这个对比。",
'rev-deleted-unhide-diff' => "该差异对比其中的一个修订版本已经被'''删除'''。在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中可以找到更多的信息。如果您想继续的话，您仍然可以[$1 查看这次修订]。",
'rev-suppressed-unhide-diff' => "该页面的其中一次修订已经被'''监督隐藏'''。
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 监督日志]中可以找到更多的资料。如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-deleted-diff-view' => "差异对比中的一次修订已被'''删除'''。您可以对比此差异。详细信息可在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中找到。",
'rev-suppressed-diff-view' => "差异对比中的一次修订已被'''监督隐藏'''。您可以对比此差异。详细信息可在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 监督日志]中找到。",
'rev-delundel' => '显示/隐藏',
'rev-showdeleted' => '显示',
'revisiondelete' => '删除/恢复版本',
'revdelete-nooldid-title' => '无效目标版本',
'revdelete-nooldid-text' => '您尚未指定一个目标修订去进行这个功能、
所指定的修订不存在，或者您尝试去隐藏现时的修订。',
'revdelete-nologtype-title' => '没有给出日志类型',
'revdelete-nologtype-text' => '您尚未指定一种日志类型去做这个动作。',
'revdelete-nologid-title' => '无效的日志项目',
'revdelete-nologid-text' => '您尚未指定一个目标日志项目去进行这个动作或指定的项目不存在。',
'revdelete-no-file' => '指定的文件不存在。',
'revdelete-show-file-confirm' => '您是否想查看文件“<nowiki>$1</nowiki>”于$2 $3时的已被删除的修订版本？',
'revdelete-show-file-submit' => '是',
'revdelete-selected' => "'''选取'''[[:$1]]'''的$2次修订：'''",
'logdelete-selected' => "'''{{PLURAL:$1|选取的日志项目}}：'''",
'revdelete-text' => "'''删除的修订仍将显示在页面历史中, 但它们的文本内容已不能被公众访问。'''
在{{SITENAME}}的其他管理员将仍能访问隐藏的内容并通过与此相同的界面恢复删除，除非站点工作者进行了一些附加的限制。",
'revdelete-confirm' => '请确认您肯定去做的话，您就要明白到后果，以及这个程序符合[[{{MediaWiki:Policy-url}}|政策]]。',
'revdelete-suppress-text' => "'''只有'''出现下列情况下才应阻止访问：
* 可能虚假的个人信息
*: ''家庭地址、电话号码、身份证号码等等。''",
'revdelete-legend' => '设置可见性之限制',
'revdelete-hide-text' => '隐藏版本文字',
'revdelete-hide-image' => '隐藏文件内容',
'revdelete-hide-name' => '隐藏动作和目标',
'revdelete-hide-comment' => '隐藏编辑摘要',
'revdelete-hide-user' => '隐藏编辑者的用户名/IP地址',
'revdelete-hide-restricted' => '同时阻止管理员与其他用户查看数据',
'revdelete-radio-same' => '（不要更改）',
'revdelete-radio-set' => '是',
'revdelete-radio-unset' => '否',
'revdelete-suppress' => '同时阻止管理员与其他用户查看数据',
'revdelete-unsuppress' => '在已恢复的修订中移除限制',
'revdelete-log' => '原因：',
'revdelete-submit' => '应用于选中的{{PLURAL:$1|修订}}',
'revdelete-success' => "'''修订的可见性已经成功更新。'''",
'revdelete-failure' => "'''修订的可见性无法更新：'''
$1",
'logdelete-success' => "'''事件的可见性已经成功设置。'''",
'logdelete-failure' => "'''事件的可见性无法设置：'''
$1",
'revdel-restore' => '更改可见性',
'revdel-restore-deleted' => '已删除的版本',
'revdel-restore-visible' => '可见的版本',
'pagehist' => '页面历史',
'deletedhist' => '已删除历史',
'revdelete-hide-current' => '正在隐藏于$1 $2之项目错误：这个是现时的修订，不可以隐藏。',
'revdelete-show-no-access' => '正在显示于$1 $2之项目错误：这个项目已经标示为"已限制"，您对它并无通行权。',
'revdelete-modify-no-access' => '正在更改于$1 $2之项目错误：这个项目已经标示为"已限制"，您对它并无通行权。',
'revdelete-modify-missing' => '正在更改项目ID $1错误：它在资料库中遗失！',
'revdelete-no-change' => '警告：于$1 $2之项目已经请求了可见性的设置。',
'revdelete-concurrent-change' => '正在更改于$1 $2之项目错误：当我们尝试更改它的设置时，已经被另一些人更改过。请检查纪录。',
'revdelete-only-restricted' => '在隐藏$1 $2的项目时发生错误：您不能在选择了另一可见性选项后废止管理员查看该项目。',
'revdelete-reason-dropdown' => '*常用删除理由
** 侵犯版权
** 不合适的个人资料
** 潜在毁谤性信息',
'revdelete-otherreason' => '其他/附加原因：',
'revdelete-reasonotherlist' => '其他原因',
'revdelete-edit-reasonlist' => '编辑删除埋由',
'revdelete-offender' => '修订版本编辑者：',

# Suppression log
'suppressionlog' => '监督日志',
'suppressionlogtext' => '该列表列出了管理员隐藏的删除与封禁。另参见[[Special:BlockList|封禁列表]]查询当前的封禁列表。',

# History merging
'mergehistory' => '合并页面历史',
'mergehistory-header' => '这一页可以让您将来源页面的修订历史合并到新页面中去。
请确保此次更改能继续保持历史页面的连续性。',
'mergehistory-box' => '合并两个页面的修订历史：',
'mergehistory-from' => '来源页面：',
'mergehistory-into' => '目的页面：',
'mergehistory-list' => '可以合并的编辑历史',
'mergehistory-merge' => '下列[[:$1]]的修订可以合并到[[:$2]]。用该选项按钮列去合并只有在指定时间以前所创建的修订。要留意的是使用导航链接便会重设这一栏。',
'mergehistory-go' => '显示可以合并的编辑',
'mergehistory-submit' => '合并版本',
'mergehistory-empty' => '没有可以合并的版本。',
'mergehistory-success' => '[[:$1]]的$3个版本成功合并至[[:$2]]。',
'mergehistory-fail' => '不可以进行历史合并，请重新检查该页面以及时间参数。',
'mergehistory-no-source' => '来源页面$1不存在。',
'mergehistory-no-destination' => '目的页面$1不存在。',
'mergehistory-invalid-source' => '来源页面必须是一个有效的标题。',
'mergehistory-invalid-destination' => '目的页面必须是一个有效的标题。',
'mergehistory-autocomment' => '合并[[:$1]]至[[:$2]]',
'mergehistory-comment' => '合并[[:$1]]至[[:$2]]：$3',
'mergehistory-same-destination' => '来源页面与目的页面不可以相同',
'mergehistory-reason' => '原因：',

# Merge log
'mergelog' => '合并日志',
'pagemerge-logentry' => '合并[[$1]]至[[$2]]（版本截至$3）',
'revertmerge' => '解除合并',
'mergelogpagetext' => '下面是最近的页面历史合并的列表。',

# Diffs
'history-title' => '“$1”的版本历史',
'difference-title' => '“$1”的版本间的差异',
'difference-title-multipage' => '页面“$1”与“$2”之间的差异',
'difference-multipage' => '（页面间的差异）',
'lineno' => '第$1行：',
'compareselectedversions' => '对比选择的版本',
'showhideselectedversions' => '显示/隐藏选择的版本',
'editundo' => '撤销',
'diff-multi' => '（未显示$2个用户的$1个中间版本）',
'diff-multi-manyusers' => '（未显示超过$2个用户的$1个中间版本）',
'difference-missing-revision' => '此差异对比的{{PLURAL:$2|一个修订|$2个修订}}（$1）{{PLURAL:$2|没有}}找到。

这通常是因为进入了一个已被删除的页面的修订差异对比链接。
详细信息可以在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中找到。',

# Search results
'searchresults' => '搜索结果',
'searchresults-title' => '“$1”的搜索结果',
'searchresulttext' => '有关搜索{{SITENAME}}的更多信息，参见[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle' => '搜索\'\'\'[[:$1]]\'\'\'（[[Special:Prefixindex/$1|所有以 "$1" 开头的页面]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|所有链接到“$1”的页面]]）',
'searchsubtitleinvalid' => "搜索'''$1'''",
'toomanymatches' => '找到的匹配结果过多，请尝试不同的查询词',
'titlematches' => '页面标题匹配',
'notitlematches' => '没有找到匹配页面题目',
'textmatches' => '页面内容匹配',
'notextmatches' => '没有页面内容匹配',
'prevn' => '前$1个',
'nextn' => '后$1个',
'prevn-title' => '前$1个结果',
'nextn-title' => '后$1个结果',
'shown-title' => '每页显示$1项结果',
'viewprevnext' => '查看（$1{{int:pipe-separator}}$2）（$3）',
'searchmenu-legend' => '搜索选项',
'searchmenu-exists' => "'''本wiki上有名为“[[:$1]]”的页面。'''",
'searchmenu-new' => "'''在本wiki上新建名为“[[:$1]]”的页面！'''",
'searchhelp-url' => 'Help:目录',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|去浏览以此为首的页面]]',
'searchprofile-articles' => '内容页面',
'searchprofile-project' => '帮助和项目页面',
'searchprofile-images' => '多媒体',
'searchprofile-everything' => '全部',
'searchprofile-advanced' => '高级',
'searchprofile-articles-tooltip' => '在$1中搜索',
'searchprofile-project-tooltip' => '在$1中搜索',
'searchprofile-images-tooltip' => '搜索文件',
'searchprofile-everything-tooltip' => '搜索所有内容（包括讨论页面）',
'searchprofile-advanced-tooltip' => '在自定义名字空间中搜索',
'search-result-size' => '$1（$2个字）',
'search-result-category-size' => '$1个成员（$2个子分类，$3个文件）',
'search-result-score' => '相关度：$1%',
'search-redirect' => '（重定向自“$1”）',
'search-section' => '（“$1”段落）',
'search-suggest' => '您是不是要找：$1',
'search-interwiki-caption' => '姊妹项目',
'search-interwiki-default' => '$1项结果：',
'search-interwiki-more' => '（更多）',
'search-relatedarticle' => '相关',
'mwsuggest-disable' => '禁用AJAX建议',
'searcheverything-enable' => '在所有名字空间中搜索',
'searchrelated' => '相关页面',
'searchall' => '所有',
'showingresults' => "下面显示从第'''$2'''条结果开始的'''$1'''条结果。",
'showingresultsnum' => "下面显示从第'''$2'''条结果开始的'''$3'''条结果。",
'showingresultsheader' => "关于'''$4'''的{{PLURAL:$5|第'''$1'''条至第'''$3'''条结果|第'''$1'''条至第'''$2'''条结果，共'''$3'''条结果}}",
'nonefound' => "'''注意'''：只有部分名字空间的页面会被默认搜索。尝试在您的搜索语句前添加“all:”前缀，这样可以搜索全部页面（包括讨论页、模板等），或者您也可使用所需名字空间作为前缀。",
'search-nonefound' => '找不到和查询相匹配的结果。',
'powersearch' => '高级搜索',
'powersearch-legend' => '高级搜索',
'powersearch-ns' => '在以下的名字空间中搜索：',
'powersearch-redir' => '重定向页列表',
'powersearch-field' => '搜索',
'powersearch-togglelabel' => '选择：',
'powersearch-toggleall' => '全选',
'powersearch-togglenone' => '全不选',
'search-external' => '外部搜索',
'searchdisabled' => '{{SITENAME}}的搜索已被禁用。您可以暂时使用Google进行搜索，须注意他们索引的{{SITENAME}}内容可能会过时。',

# Quickbar
'qbsettings' => '快速导航栏',
'qbsettings-none' => '无',
'qbsettings-fixedleft' => '左侧固定',
'qbsettings-fixedright' => '右侧固定',
'qbsettings-floatingleft' => '左侧漂移',
'qbsettings-floatingright' => '右侧漂移',
'qbsettings-directionality' => '根据您的语言文本方向固定位置',

# Preferences page
'preferences' => '系统设置',
'mypreferences' => '系统设置',
'prefs-edits' => '编辑数量：',
'prefsnologin' => '尚未登录',
'prefsnologintext' => '您必须先<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 登录]</span>才能设置个人参数。',
'changepassword' => '更改密码',
'prefs-skin' => '皮肤',
'skin-preview' => '预览',
'datedefault' => '默认格式',
'prefs-beta' => '测试特性',
'prefs-datetime' => '日期时间',
'prefs-labs' => '实验室特性',
'prefs-user-pages' => '用户页面',
'prefs-personal' => '用户资料',
'prefs-rc' => '最近更改',
'prefs-watchlist' => '监视列表',
'prefs-watchlist-days' => '监视列表中显示的天数：',
'prefs-watchlist-days-max' => '最多$1天',
'prefs-watchlist-edits' => '扩展监视列表中显示的最大更改数：',
'prefs-watchlist-edits-max' => '最大数量：1000',
'prefs-watchlist-token' => '监视列表权标：',
'prefs-misc' => '其他',
'prefs-resetpass' => '更改密码',
'prefs-changeemail' => '更改电子邮件地址',
'prefs-setemail' => '设置电子邮件地址',
'prefs-email' => '电子邮件',
'prefs-rendering' => '显示',
'saveprefs' => '保存',
'resetprefs' => '清除未保存的更改',
'restoreprefs' => '恢复所有默认设置',
'prefs-editing' => '编辑',
'prefs-edit-boxsize' => '编辑框尺寸',
'rows' => '行：',
'columns' => '列：',
'searchresultshead' => '搜索',
'resultsperpage' => '每页显示链接数：',
'stub-threshold' => '<a href="#" class="stub">短页面链接</a>格式阈值（字节）：',
'stub-threshold-disabled' => '已禁用',
'recentchangesdays' => '最近更改中显示的天数：',
'recentchangesdays-max' => '最多$1天',
'recentchangescount' => '默认显示的编辑数：',
'prefs-help-recentchangescount' => '该项包含最近更改、页面历史和日志。',
'prefs-help-watchlist-token' => '此栏填写的密钥可以生成您监视列表的RSS源。任何知晓本栏密钥的人都能阅读您的监视列表，因此请使用安全的数值。这里已提供了一个随机生成的数值供您选择：$1',
'savedprefs' => '你的系统设置已经保存。',
'timezonelegend' => '时区：',
'localtime' => '当地时间：',
'timezoneuseserverdefault' => '使用wiki默认值（$1）',
'timezoneuseoffset' => '其它（指定时差）',
'timezoneoffset' => '时差¹：',
'servertime' => '服务器时间：',
'guesstimezone' => '使用浏览器设置',
'timezoneregion-africa' => '非洲',
'timezoneregion-america' => '美洲',
'timezoneregion-antarctica' => '南极洲',
'timezoneregion-arctic' => '北极',
'timezoneregion-asia' => '亚洲',
'timezoneregion-atlantic' => '大西洋',
'timezoneregion-australia' => '澳大利亚',
'timezoneregion-europe' => '欧洲',
'timezoneregion-indian' => '印度洋',
'timezoneregion-pacific' => '太平洋',
'allowemail' => '接受来自其他用户的邮件',
'prefs-searchoptions' => '搜索',
'prefs-namespaces' => '名字空间',
'defaultns' => '否则在这些名字空间中搜索：',
'default' => '默认',
'prefs-files' => '文件',
'prefs-custom-css' => '自定义 CSS',
'prefs-custom-js' => '自定义 JavaScript',
'prefs-common-css-js' => '所有皮肤共用的CSS/JavaScript：',
'prefs-reset-intro' => '您可以通过本页面重置您的系统设置为默认值。此操作不可撤销。',
'prefs-emailconfirm-label' => '电子邮件确认：',
'prefs-textboxsize' => '编辑框大小',
'youremail' => '电子邮件：',
'username' => '用户名：',
'uid' => '用户ID：',
'prefs-memberingroups' => '{{PLURAL:$1|用户组}}：',
'prefs-registration' => '注册时间：',
'yourrealname' => '真实姓名：',
'yourlanguage' => '语言：',
'yourvariant' => '内容语言变种：',
'prefs-help-variant' => '您希望用于显示本站内容的语种或拼写语系。',
'yournick' => '新签名：',
'prefs-help-signature' => '讨论页面上的评论应使用“<nowiki>~~~~</nowiki>”签名，它会自动转换为您的签名和时间戳。',
'badsig' => '错误的原始签名。请检查HTML标签。',
'badsiglength' => '签名过长。请不超过$1个字符。',
'yourgender' => '性别：',
'gender-unknown' => '不指明',
'gender-male' => '男',
'gender-female' => '女',
'prefs-help-gender' => '选填项目。使软件使用正确的性别称呼。该信息将会公开。',
'email' => '电子邮件',
'prefs-help-realname' => '真实姓名是可选的项目。如果您选择提供它，它将会用于贡献署名。',
'prefs-help-email' => '电子邮件地址是选填项目。但是在你忘记密码需要重置密码时需要电子邮件地址。',
'prefs-help-email-others' => '你亦可以选择让其他用户通过你的用户页或讨论页上的链接用电子邮件联系你。其他用户联系你时你的电子邮件地址不会显示。',
'prefs-help-email-required' => '电子邮件地址是必填项目。',
'prefs-info' => '基本信息',
'prefs-i18n' => '界面语言',
'prefs-signature' => '签名',
'prefs-dateformat' => '日期格式',
'prefs-timeoffset' => '时差',
'prefs-advancedediting' => '高级选项',
'prefs-advancedrc' => '高级选项',
'prefs-advancedrendering' => '高级选项',
'prefs-advancedsearchoptions' => '高级选项',
'prefs-advancedwatchlist' => '高级选项',
'prefs-displayrc' => '显示',
'prefs-displaysearchoptions' => '显示',
'prefs-displaywatchlist' => '显示',
'prefs-diffs' => '差异对比',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => '电子邮件地址有效',
'email-address-validity-invalid' => '请输入有效的电子邮件地址',

# User rights
'userrights' => '用户权限管理',
'userrights-lookup-user' => '管理用户组',
'userrights-user-editname' => '输入用户名：',
'editusergroup' => '编辑用户组',
'editinguser' => "更改用户'''[[User:$1|$1]]'''的用户权限$2",
'userrights-editusergroup' => '编辑用户组',
'saveusergroups' => '保存用户组',
'userrights-groupsmember' => '用户组：',
'userrights-groupsmember-auto' => '自动用户组：',
'userrights-groups-help' => '您可以更改该用户的用户组：
* 选中的选项框表示该用户属于该用户组。
* 未选中的选项框表示该用户不属于该用户组。
* 星号（*）表示一旦添加该用户组后不能删除，反之亦然。',
'userrights-reason' => '原因：',
'userrights-no-interwiki' => '您并没有权限去编辑在其它wiki上的用户权限。',
'userrights-nodatabase' => '数据库$1不存在或并非为本地的。',
'userrights-nologin' => '您必须要以管理员帐户[[Special:UserLogin|登录]]之后才可以指定用户权限。',
'userrights-notallowed' => '您的帐户无添加或删除用户权限的权限。',
'userrights-changeable-col' => '您可以更改的用户组',
'userrights-unchangeable-col' => '您不能更改的用户组',

# Groups
'group' => '用户组：',
'group-user' => '用户',
'group-autoconfirmed' => '自动确认用户',
'group-bot' => '机器人',
'group-sysop' => '管理员',
'group-bureaucrat' => '行政员',
'group-suppress' => '监督',
'group-all' => '（全部）',

'group-user-member' => '{{GENDER:$1|用户}}',
'group-autoconfirmed-member' => '自动确认用户',
'group-bot-member' => '机器人',
'group-sysop-member' => '{{GENDER:$1|管理员}}',
'group-bureaucrat-member' => '行政员',
'group-suppress-member' => '监督员',

'grouppage-user' => '{{ns:project}}:用户',
'grouppage-autoconfirmed' => '{{ns:project}}:自动确认用户',
'grouppage-bot' => '{{ns:project}}:机器人',
'grouppage-sysop' => '{{ns:project}}:管理员',
'grouppage-bureaucrat' => '{{ns:project}}:行政员',
'grouppage-suppress' => '{{ns:project}}:监督员',

# Rights
'right-read' => '阅读页面',
'right-edit' => '编辑页面',
'right-createpage' => '创建页面（非讨论页面）',
'right-createtalk' => '创建讨论页面',
'right-createaccount' => '创建新用户账户',
'right-minoredit' => '标记小编辑',
'right-move' => '移动页面',
'right-move-subpages' => '移动页面及其子页面',
'right-move-rootuserpages' => '移动根用户页面',
'right-movefile' => '移动文件',
'right-suppressredirect' => '移动页面时不创建来自来源页面的重定向',
'right-upload' => '上传文件',
'right-reupload' => '覆盖现存文件',
'right-reupload-own' => '覆盖自己上传的文件',
'right-reupload-shared' => '本地覆盖共享文件库的文件',
'right-upload_by_url' => '从URL上传文件',
'right-purge' => '无确认清除页面缓存',
'right-autoconfirmed' => '编辑半保护页面',
'right-bot' => '被视为自动过程',
'right-nominornewtalk' => '不使小编辑在讨论页面引发新信息提示',
'right-apihighlimits' => '在API查询中使用更高的限制',
'right-writeapi' => '使用书写API',
'right-delete' => '删除页面',
'right-bigdelete' => '删除有大型历史的页面',
'right-deletelogentry' => '删除和恢复特定的日志项目',
'right-deleterevision' => '删除和恢复页面的特定版本',
'right-deletedhistory' => '查看被删除的历史条目，无其相关文字',
'right-deletedtext' => '查看被删除的版本间的被删除的文字和更改',
'right-browsearchive' => '搜索被删除的页面',
'right-undelete' => '恢复页面',
'right-suppressrevision' => '审查和恢复管理员隐藏的版本',
'right-suppressionlog' => '查看非公开日志',
'right-block' => '阻止其他用户编辑',
'right-blockemail' => '阻止用户发送邮件',
'right-hideuser' => '封禁并隐藏用户名',
'right-ipblock-exempt' => '避开IP封禁、自动封禁和IP段封禁',
'right-proxyunbannable' => '避开代理服务器的自动封禁',
'right-unblockself' => '自己解封',
'right-protect' => '更改保护级别和编辑受保护页面',
'right-editprotected' => '编辑保护页面（无连锁保护）',
'right-editinterface' => '编辑用户界面',
'right-editusercssjs' => '编辑其他用户的CSS和JavaScript文件',
'right-editusercss' => '编辑其他用户的CSS文件',
'right-edituserjs' => '编辑其他用户的JavaScript文件',
'right-rollback' => '快速回退最后编辑特定页面的用户的编辑',
'right-markbotedits' => '标记回退编辑为机器人编辑',
'right-noratelimit' => '不受速率限制影响',
'right-import' => '从其他wiki导入页面',
'right-importupload' => '从文件上传导入页面',
'right-patrol' => '标记他人的编辑为已巡查',
'right-autopatrol' => '使自己的编辑自动标记为已巡查',
'right-patrolmarks' => '查看最近更改的巡查标记',
'right-unwatchedpages' => '查看未受监视页面的列表',
'right-mergehistory' => '合并页面历史',
'right-userrights' => '编辑所有用户的权限',
'right-userrights-interwiki' => '编辑其它wiki的用户的用户权限',
'right-siteadmin' => '锁定和解锁数据库',
'right-override-export-depth' => '导出含有链接页面深度为5的页面',
'right-sendemail' => '向其他用户发送邮件',
'right-passwordreset' => '查看密码重置电子邮件',

# User rights log
'rightslog' => '用户权限日志',
'rightslogtext' => '这是用户权限更改的日志。',
'rightslogentry' => '将$1的用户组由$2更改为$3',
'rightslogentry-autopromote' => '被自动提升自$2至$3',
'rightsnone' => '（无）',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => '阅读本页',
'action-edit' => '编辑本页',
'action-createpage' => '创建页面',
'action-createtalk' => '创建讨论页面',
'action-createaccount' => '创建该用户账户',
'action-minoredit' => '标记该编辑为小编辑',
'action-move' => '移动本页',
'action-move-subpages' => '移动本页及其子页面',
'action-move-rootuserpages' => '移动根用户页面',
'action-movefile' => '移动本文件',
'action-upload' => '上传本文件',
'action-reupload' => '覆盖本文件',
'action-reupload-shared' => '覆盖共享文件库的本文件',
'action-upload_by_url' => '从URL上传本文件',
'action-writeapi' => '使用书写API',
'action-delete' => '删除本页',
'action-deleterevision' => '删除本版本',
'action-deletedhistory' => '查看本页面被删除的历史',
'action-browsearchive' => '搜索被删除的页面',
'action-undelete' => '恢复本页',
'action-suppressrevision' => '审查和恢复本隐藏版本',
'action-suppressionlog' => '查看本非公开日志',
'action-block' => '阻止该用户编辑',
'action-protect' => '更改本页面的保护级别',
'action-rollback' => '快速回退最后编辑特定页面的用户的编辑',
'action-import' => '从其他wiki导入本页面',
'action-importupload' => '从文件上传导入本页面',
'action-patrol' => '标记他人的编辑为已巡查',
'action-autopatrol' => '使你的编辑标记为已巡查',
'action-unwatchedpages' => '查看未受监视页面的列表',
'action-mergehistory' => '合并本页面的历史',
'action-userrights' => '编辑所有用户的权限',
'action-userrights-interwiki' => '编辑其它wiki的用户的用户权限',
'action-siteadmin' => '锁定或解锁数据库',
'action-sendemail' => '通过邮件联系其他用户',

# Recent changes
'nchanges' => '$1次更改',
'recentchanges' => '最近更改',
'recentchanges-legend' => '最近更改选项',
'recentchanges-summary' => '跟踪这个wiki上的最新更改。',
'recentchanges-feed-description' => '跟踪订阅本wiki的最近更改。',
'recentchanges-label-newpage' => '这次编辑建立了一个新页面',
'recentchanges-label-minor' => '这是一个小编辑',
'recentchanges-label-bot' => '这次编辑是由机器人进行',
'recentchanges-label-unpatrolled' => '该编辑尚未巡查',
'rcnote' => "下面是最后'''$2'''天的最后'''$1'''个更改，截至$4 $5。",
'rcnotefrom' => "下面是自'''$2'''起的更改（最多显示'''$1'''个）。",
'rclistfrom' => '显示自$1起的新更改',
'rcshowhideminor' => '$1小编辑',
'rcshowhidebots' => '$1机器人的编辑',
'rcshowhideliu' => '$1登录用户的编辑',
'rcshowhideanons' => '$1匿名用户的编辑',
'rcshowhidepatr' => '$1巡查过的编辑',
'rcshowhidemine' => '$1我的编辑',
'rclinks' => '显示最后$2天的最后$1个更改<br />$3',
'diff' => '差异',
'hist' => '历史',
'hide' => '隐藏',
'show' => '显示',
'minoreditletter' => '小',
'newpageletter' => '新',
'boteditletter' => '机',
'number_of_watching_users_pageview' => '[$1个关注用户]',
'rc_categories' => '分类限制（用“|”分隔）',
'rc_categories_any' => '任意',
'rc-change-size-new' => '更改后$1字节',
'newsectionsummary' => '/* $1 */ 新段落',
'rc-enhanced-expand' => '显示细节（需JavaScript支持）',
'rc-enhanced-hide' => '隐藏细节',
'rc-old-title' => '最初被创建为" $1 "',

# Recent changes linked
'recentchangeslinked' => '相关更改',
'recentchangeslinked-feed' => '相关更改',
'recentchangeslinked-toolbox' => '相关更改',
'recentchangeslinked-title' => '与“$1”有关的更改',
'recentchangeslinked-noresult' => '在这一段时间中链接的页面并无更改。',
'recentchangeslinked-summary' => "这一个特殊页面列示''由''所给出的一个页面之链接到页面的最近更改（或者是对于指定分类的成员）。
在[[Special:Watchlist|您的监视列表]]中的页面会以'''粗体'''显示。",
'recentchangeslinked-page' => '页面名称：',
'recentchangeslinked-to' => '显示链到所给出的页面',

# Upload
'upload' => '上传文件',
'uploadbtn' => '上传文件',
'reuploaddesc' => '取消上传并返回上传表单',
'upload-tryagain' => '提交修改后的文件描述',
'uploadnologin' => '未登录',
'uploadnologintext' => '您必须先[[Special:UserLogin|登录]]才能上传文件。',
'upload_directory_missing' => '上传目录（$1）遗失，不能由网页服务器建立。',
'upload_directory_read_only' => '上传目录（$1）不存在或无写权限。',
'uploaderror' => '上传错误',
'upload-recreate-warning' => "'''警告：一个相同名字的文件曾经被删除或者移动至别处。'''

这个页面的删除和移动日志在这里提供以便参考：",
'uploadtext' => "请使用下面的表格上传文件。要查看或搜索以前上传的文件，可以进入[[Special:FileList|文件上传列表]]，（重新）上传也将在[[Special:Log/upload|上传日志]]中记录，而删除将在[[Special:Log/delete|删除日志]]中记录。

要在页面中加入文件，请使用一种以下形式的链接：
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>'''使用文件的完整版本
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|替换文字]]</nowiki></code>'''使用放置于左侧的一个框内的200像素宽的图片，同时使用“替换文字”作为描述
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>'''直接链接到文件而不显示文件",
'upload-permitted' => '允许的文件类型：$1。',
'upload-preferred' => '建议的文件类型：$1。',
'upload-prohibited' => '禁止的文件类型：$1。',
'uploadlog' => '上传日志',
'uploadlogpage' => '上传日志',
'uploadlogpagetext' => '下面是最近的文件上传的列表。图像概览请见[[Special:NewFiles|新文件库]]。',
'filename' => '文件名',
'filedesc' => '文件说明',
'fileuploadsummary' => '摘要：',
'filereuploadsummary' => '文件更改：',
'filestatus' => '著作权状况：',
'filesource' => '来源：',
'uploadedfiles' => '已上传文件',
'ignorewarning' => '忽视警告并继续保存文件',
'ignorewarnings' => '忽略所有警告',
'minlength1' => '文件名至少要有一个字符。',
'illegalfilename' => '文件名“$1”包含在页面标题中不允许使用的字符。请重命名该文件，然后重新上传。',
'filename-toolong' => '文件名不能超过240字节。',
'badfilename' => '文件名已被改为“$1”。',
'filetype-mime-mismatch' => '文件扩展名“.$1”与检测到的文件MIME类型（$2）不匹配。',
'filetype-badmime' => '“$1”类型的文件已被禁止上传。',
'filetype-bad-ie-mime' => '无法上传该文件，因为Internet Explorer会将它检测为“$1”，这是一种禁止且带有潜在危险的文件类型。',
'filetype-unwanted-type' => "'''\".\$1\"'''是一种不需要的文件类型。
建议的{{PLURAL:\$3|一种|多种}}文件类型有\$2。",
'filetype-banned-type' => '\'\'\'".$1"\'\'\'{{PLURAL:$4|不是一个允许的文件类型|不是一个允许的文件类型}}。
允许 {{PLURAL:$3|文件类型是|文件类型是}} $2。',
'filetype-missing' => '该文件名称并没有扩展名（例如“.jpg”）。',
'empty-file' => '您所提交的文件为空文件。',
'file-too-large' => '您所提交的文件过大。',
'filename-tooshort' => '文件名过短。',
'filetype-banned' => '此类文件被禁止。',
'verification-error' => '文件未通过验证。',
'hookaborted' => '您所尝试的修改被插件钩子舍弃。',
'illegal-filename' => '文件名非法。',
'overwrite' => '不允许覆盖现有文件。',
'unknown-error' => '发生未知错误。',
'tmp-create-error' => '无法创建临时文件。',
'tmp-write-error' => '临时文件写入发生错误。',
'large-file' => '建议文件大小不能超过 $1；本文件大小为 $2。',
'largefileserver' => '这个文件的大小比服务器配置允许的大小还要大。',
'emptyfile' => '您所上传的文件不存在。这可能是由于文件名键入错误。请检查您是否真的要上传此文件。',
'windows-nonascii-filename' => '本wiki不支持在文件名中使用特殊字符。',
'fileexists' => '已存在相同名称的文件，如果您无法确定您是否要改变它，请检查<strong><strong>[[:$1]]</strong></strong>。 [[$1|thumb]]',
'filepageexists' => '这个文件的描述页已经于<strong><strong>[[:$1]]</strong></strong>创建，但是这个名称的文件尚不存在。
您输入的摘要不会显示在该描述页中。
要令该摘要在该处中出现，您需要手动地编辑该页。
[[$1|thumb]]',
'fileexists-extension' => '一个相似名称的文件已经存在: [[$2|thumb]]
* 上传文件的文件名：<strong>[[:$1]]</strong>
* 现有文件的文件名：<strong>[[:$2]]</strong>
请选择一个不同的名字。',
'fileexists-thumbnail-yes' => "此文件可能是另一幅图像的缩小版本''（缩略图）''。 [[$1|thumb]]
请仔细检查该文件<strong>[[:$1]]</strong>。
如果被检查文件与原始大小的图像是同一幅图像，您无需上传多余的缩略图。",
'file-thumbnail-no' => "文件名以<strong>$1</strong>开头。它可能是另一幅图像的缩小版本''（缩略图）''。
如果您有该图像完整分辨率的版本，请上传该完整版本。否则请修改文件名。",
'fileexists-forbidden' => '已存在相同名称的文件，且不能覆盖；请返回并用一个新的名称来上传此文件。[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '在共享文件库中已存在同名文件。
如果您仍然想继续上传，请返回并使用一个新的文件名来上传此文件。[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => '这个文件与下列{{PLURAL:$1|一|多}}个文件重复：',
'file-deleted-duplicate' => '一个相同名称的文件 （[[:$1]]） 在先前删除过。您应该在重新上传之前检查一下该文件之删除纪录。',
'uploadwarning' => '上传警告',
'uploadwarning-text' => '请修改下面的文件说明并重试。',
'savefile' => '保存文件',
'uploadedimage' => '上传“[[$1]]”',
'overwroteimage' => '上传“[[$1]]”的新版本',
'uploaddisabled' => '上传己禁用。',
'copyuploaddisabled' => '通过网址上传功能已禁用。',
'uploadfromurl-queued' => '上传已被列入队列。',
'uploaddisabledtext' => '文件上传已禁用。',
'php-uploaddisabledtext' => 'PHP 设置已禁用文件上传功能。请检查 file_uploads 设置。',
'uploadscripted' => '该文件包含可能被网络浏览器错误解释的 HTML 或脚本代码。',
'uploadvirus' => '该文件包含病毒！
详情：$1',
'uploadjava' => '该文件是 ZIP 文件，其中包含 Java 的.class 文件。
不允许上传的 Java 文件，因为他们可能会跳过的安全限制。',
'upload-source' => '来源文件',
'sourcefilename' => '源文件名：',
'sourceurl' => '来源网址：',
'destfilename' => '目标文件名：',
'upload-maxfilesize' => '文件最大限制大小: $1',
'upload-description' => '文件说明',
'upload-options' => '上传选项',
'watchthisupload' => '监视这个文件',
'filewasdeleted' => '之前已经有一个同名文件被上传后又被删除了。在上传此文件之前您需要检查$1。',
'filename-bad-prefix' => '您上传的文件名称是以<strong>“$1”</strong>作为开头，通常这种没有含意的文件名称是由数码相机中自动编排。请在您的文件中重新选择一个更加有意义的文件名称。',
'upload-success-subj' => '上传成功',
'upload-success-msg' => '您在[$2]的上传已经成功，可以在这里找到：[[:{{ns:file}}:$1]]',
'upload-failure-subj' => '上传问题',
'upload-failure-msg' => '您在[$2]的上传出现了问题：

$1',
'upload-warning-subj' => '上传警告',
'upload-warning-msg' => '您自[$2]的上传出错。您可以返回[[Special:Upload/stash/$1|上传表单]]并更正问题。',

'upload-proto-error' => '协议错误',
'upload-proto-error-text' => '远程上传要求 URL 以 <code>http://</code> 或 <code>ftp://</code> 开头。',
'upload-file-error' => '内部错误',
'upload-file-error-text' => '当试图在服务器上创建临时文件时发生内部错误。请与[[Special:ListUsers/sysop|管理员]]联系。',
'upload-misc-error' => '未知的上传错误',
'upload-misc-error-text' => '在上传时发生未知的错误。请确认您使用了正确并可访问的URL，然后进行重试。如果问题仍然存在，请与[[Special:ListUsers/sysop|管理员]]联系。',
'upload-too-many-redirects' => '在网址中有太多重新定向',
'upload-unknown-size' => '未知大小',
'upload-http-error' => '发生HTTP错误：$1',
'upload-copy-upload-invalid-domain' => '不能从该域名上载文件副本。',

# File backend
'backend-fail-stream' => '无法流传送文件$1。',
'backend-fail-backup' => '无法备份文件$1。',
'backend-fail-notexists' => '条目$1不存在。',
'backend-fail-hashes' => '比较无法获取文件hashes',
'backend-fail-notsame' => '$1已存在不同的文件。',
'backend-fail-invalidpath' => '$1不是有效的存储路径。',
'backend-fail-delete' => '无法删除文件“$1”。',
'backend-fail-alreadyexists' => '“$1”页面已存在',
'backend-fail-store' => '无法在$2存储文件$1。',
'backend-fail-copy' => '无法复制文件$1到$2。',
'backend-fail-move' => '无法移动文件$1到$2。',
'backend-fail-opentemp' => '无法打开临时文件。',
'backend-fail-writetemp' => '无法写临时文件。',
'backend-fail-closetemp' => '无法创建临时文件。',
'backend-fail-read' => '找不到文件“$1”。',
'backend-fail-create' => '无法写入文件 $1 。',
'backend-fail-maxsize' => '无法写入文件 $1，因为它大于$2字节。',
'backend-fail-readonly' => '“$1”存储后端目前在只读模式，因为：“$2”',
'backend-fail-synced' => '文件"$1"在内部存储后端之中处于不一致状态',
'backend-fail-connect' => '无法连接到存储后端“$1。',
'backend-fail-internal' => '存储后端“$1”发生了一个未知错误。',
'backend-fail-contenttype' => '无法判断文件的内容类型来储存于“$1”。',
'backend-fail-batchsize' => '存储后端被给予了一批$1个文件{{PLURAL:$1|操作|操作}}；限值为$2个{{PLURAL:$2|操作|操作}}。',
'backend-fail-usable' => '权限不足或缺少目录/贮存器，无法读取或写入文件“$1”。',

# File journal errors
'filejournal-fail-dbconnect' => '无法连接到后端存储的日志数据库“$1”。',
'filejournal-fail-dbquery' => '无法更新后端存储的日志数据库“$1”。',

# Lock manager
'lockmanager-notlocked' => '无法解锁“$1”；它没有被锁定。',
'lockmanager-fail-closelock' => '无法关闭“$1”的锁文件。',
'lockmanager-fail-deletelock' => '无法删除“$1”的锁文件。',
'lockmanager-fail-acquirelock' => '无法为“$1”获取锁。',
'lockmanager-fail-openlock' => '无法打开“$1”的锁文件。',
'lockmanager-fail-releaselock' => '无法为“$1”释放锁。',
'lockmanager-fail-db-bucket' => '不能在$1池中联系到足够锁数据库。',
'lockmanager-fail-db-release' => '不能在数据库$1上释放锁。',
'lockmanager-fail-svr-acquire' => '无法在服务器 $1 上获得锁',
'lockmanager-fail-svr-release' => '不能在服务器$1上释放锁。',

# ZipDirectoryReader
'zip-file-open-error' => '打开文件的 ZIP 检查时遇到一个错误。',
'zip-wrong-format' => '指定的文件不是一个 ZIP 文件。',
'zip-bad' => '该文件是已损坏或以其它方式无法读取的 ZIP 文件。
不能正确检查安全。',
'zip-unsupported' => '该文件是 ZIP 文件，其中使用 MediaWiki 不支持的ZIP功能。
不能正确检查安全。',

# Special:UploadStash
'uploadstash' => '文件贮藏',
'uploadstash-summary' => '这个页面提供已经上传（或者上传中）但未发布到wiki之文件存取。这些文件除了上传的用户之外不会被其他人可见。',
'uploadstash-clear' => '清除贮藏文件',
'uploadstash-nofiles' => '您没有已贮藏的文件。',
'uploadstash-badtoken' => '执行操作不成功，或者您的编辑信息已经过期。请重试。',
'uploadstash-errclear' => '清除文件不成功。',
'uploadstash-refresh' => '更新文件清单',
'invalid-chunk-offset' => '无效区块偏移量',

# img_auth script messages
'img-auth-accessdenied' => '拒绝访问',
'img-auth-nopathinfo' => 'PATH_INFO缺失。
您的服务器尚未设置传送该信息。
它可能基于CGI，因而不支持img_auth。
请参见 [https://www.mediawiki.org/wiki/Manual:Image_Authorization 图片授权]。',
'img-auth-notindir' => '在已设置的上传目录中找不到请求的路径。',
'img-auth-badtitle' => '无法为“$1”创建合法的标题。',
'img-auth-nologinnWL' => '您尚未登录，且“$1”不在白名单上。',
'img-auth-nofile' => '文件“$1”不存在。',
'img-auth-isdir' => '您正试图访问目录“$1”。您只能访问文件。',
'img-auth-streaming' => '流式化“$1”中。',
'img-auth-public' => 'img_auth.php 的功能是从私有 wiki 输出文件。但本 wiki 已被设置为公共 wiki。出于安全考虑，img_auth.php 已被禁用。',
'img-auth-noread' => '用户无权读取“$1”。',
'img-auth-bad-query-string' => 'URL 有一个无效的查询字符串。',

# HTTP errors
'http-invalid-url' => '无效URL：$1',
'http-invalid-scheme' => '不支持带有“$1”的URL',
'http-request-error' => '未知的错误令到HTTP请求失败。',
'http-read-error' => 'HTTP读取错误。',
'http-timed-out' => 'HTTP请求已过时。',
'http-curl-error' => '撷取URL时出错：$1',
'http-host-unreachable' => '无法到达URL。',
'http-bad-status' => '进行HTTP请求时出现问题：$1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => '无法访问URL',
'upload-curl-error6-text' => '无法访问所提供的URL。请复查该URL是否正确，及其网站是否在线。',
'upload-curl-error28' => '上传超时',
'upload-curl-error28-text' => '站点响应时间过长。请检查此网站的访问是否正常，过一会再进行尝试。您可能需要在网络访问空闲时间再次进行尝试。',

'license' => '授权协议：',
'license-header' => '授权协议',
'nolicense' => '未选定',
'license-nopreview' => '（无预览可用）',
'upload_source_url' => '（有效、可以公开访问的URL）',
'upload_source_file' => '（您计算机上的一个文件）',

# Special:ListFiles
'listfiles-summary' => '本特殊页面显示所有上传的文件。当按用户过滤时，只显示输入的用户最后上传的文件版本。',
'listfiles_search_for' => '按媒体名称搜索：',
'imgfile' => '文件',
'listfiles' => '文件列表',
'listfiles_thumb' => '缩略图',
'listfiles_date' => '日期',
'listfiles_name' => '名称',
'listfiles_user' => '用户',
'listfiles_size' => '大小',
'listfiles_description' => '说明',
'listfiles_count' => '版本',

# File description page
'file-anchor-link' => '文件',
'filehist' => '文件历史',
'filehist-help' => '查看某一时刻的文件，请点击相应的日期/时间。',
'filehist-deleteall' => '删除全部',
'filehist-deleteone' => '删除',
'filehist-revert' => '恢复',
'filehist-current' => '当前',
'filehist-datetime' => '日期/时间',
'filehist-thumb' => '缩略图',
'filehist-thumbtext' => '$1的版本的缩略图',
'filehist-nothumb' => '没有缩略图',
'filehist-user' => '用户',
'filehist-dimensions' => '大小',
'filehist-filesize' => '文件大小',
'filehist-comment' => '评论',
'filehist-missing' => '文件遗失',
'imagelinks' => '文件用途',
'linkstoimage' => '下列$1个页面链接到本文件：',
'linkstoimage-more' => '多于$1个页面连接到这个文件。
下面的列表只列示了连去这个文件的最首$1个页面。
一个[[Special:WhatLinksHere/$2|完整的列表]]可以提供。',
'nolinkstoimage' => '没有页面链接到本文件。',
'morelinkstoimage' => '查看连接到这个文件的[[Special:WhatLinksHere/$1|更多链接]]。',
'linkstoimage-redirect' => '$1（文件重定向）$2',
'duplicatesoffile' => '下列$1个文件与该文件重复（[[Special:FileDuplicateSearch/$2|更多细节]]）：',
'sharedupload' => '该文件来自于$1，它可能在其它计划项目中被应用。',
'sharedupload-desc-there' => '该文件来自于$1，它可能在其它计划项目中被应用。
请参阅在[$2 文件描述页面]以了解其相关信息。',
'sharedupload-desc-here' => '该文件来自于$1，它可能在其它计划项目中被应用。
它在[$2 文件描述页面]那边上的描述于下面显示。',
'sharedupload-desc-edit' => '该文件来自$1，它可能在其它计划项目中被使用。
或许您可以在其[$2 文件描述页面]上编辑说明。',
'sharedupload-desc-create' => '此文件来自$1并可能由其他项目使用。
也许您想在其[$2 文件描述页面]编辑描述信息。',
'filepage-nofile' => '不存在此名称的文件。',
'filepage-nofile-link' => '不存在此名称的文件，但您可以[$1 上传它]。',
'uploadnewversion-linktext' => '上传该文件的新版本',
'shared-repo-from' => '出自$1',
'shared-repo' => '一个共用文件库',
'shared-repo-name-wikimediacommons' => '维基共享资源',
'upload-disallowed-here' => '您不可以覆盖此文件。',

# File reversion
'filerevert' => '恢复$1',
'filerevert-legend' => '恢复文件',
'filerevert-intro' => "您正在将文件'''[[Media:$1|$1]]'''恢复到[$4 于$2 $3的版本]。",
'filerevert-comment' => '原因：',
'filerevert-defaultcomment' => '已经恢复到于$1 $2的版本',
'filerevert-submit' => '恢复',
'filerevert-success' => "'''[[Media:$1|$1]]'''已经恢复到[$4 于$2 $3的版本]。",
'filerevert-badversion' => '文件并无所请求时间戳下的早期本地版本。',

# File deletion
'filedelete' => '删除$1',
'filedelete-legend' => '删除文件',
'filedelete-intro' => "您将删除文件'''[[Media:$1|$1]]'''。",
'filedelete-intro-old' => "您正在删除'''[[Media:$1|$1]]'''于[$4 $2 $3]的版本。",
'filedelete-comment' => '原因：',
'filedelete-submit' => '删除',
'filedelete-success' => "'''$1'''已经删除。",
'filedelete-success-old' => "'''[[Media:$1|$1]]'''于 $2 $3 的版本已经删除。",
'filedelete-nofile' => "'''$1'''不存在。",
'filedelete-nofile-old' => "在已指定属性的情况下，这里没有'''$1'''的保存版本。",
'filedelete-otherreason' => '其他/附加原因：',
'filedelete-reason-otherlist' => '其他原因',
'filedelete-reason-dropdown' => '
*常用删除理由
** 侵犯版权
** 重复文件',
'filedelete-edit-reasonlist' => '编辑删除埋由',
'filedelete-maintenance' => '处于维护时将暂时禁用文件删除和恢复。',
'filedelete-maintenance-title' => '无法删除文件',

# MIME search
'mimesearch' => 'MIME搜索',
'mimesearch-summary' => '本页面启用文件MIME类型过滤器。输入：内容类型/子类型，如 <code>image/jpeg</code>。',
'mimetype' => 'MIME 类型：',
'download' => '下载',

# Unwatched pages
'unwatchedpages' => '未被监视的页面',

# List redirects
'listredirects' => '重定向页列表',

# Unused templates
'unusedtemplates' => '未使用模板',
'unusedtemplatestext' => '此页面列出{{ns:template}}名字空间下所有未被其它页面使用的页面。请在删除这些模板前检查其它链入该模板的页面。',
'unusedtemplateswlh' => '其它链接',

# Random page
'randompage' => '随机页面',
'randompage-nopages' => '在以下{{PLURAL:$2|名字空间|名字空间}}中没有页面：$1。',

# Random redirect
'randomredirect' => '随机重定向页',
'randomredirect-nopages' => '在 "$1" 名字空间中没有重定向页面。',

# Statistics
'statistics' => '统计',
'statistics-header-pages' => '页面统计',
'statistics-header-edits' => '编辑统计',
'statistics-header-views' => '查看统计',
'statistics-header-users' => '用户统计',
'statistics-header-hooks' => '其它统计',
'statistics-articles' => '内容页面',
'statistics-pages' => '页面',
'statistics-pages-desc' => '本wiki的所有页面，包括讨论页面、重定向页等',
'statistics-files' => '已上传文件',
'statistics-edits' => '自{{SITENAME}}建立以来的页面编辑数',
'statistics-edits-average' => '每页平均编辑数',
'statistics-views-total' => '查看总数',
'statistics-views-total-desc' => '不存在页面和特殊页面的查看数未计入',
'statistics-views-peredit' => '每次编辑查看数',
'statistics-users' => '注册[[Special:ListUsers|用户]]',
'statistics-users-active' => '活跃用户',
'statistics-users-active-desc' => '在前$1天中操作过的用户',
'statistics-mostpopular' => '浏览最多的页面',

'disambiguations' => '链接至消歧义页的页面',
'disambiguationspage' => 'Template:消歧义',
'disambiguations-text' => "以下的页面都有到'''消歧义页'''的链接，但它们可能可以链接到更适当的页面。<br />一个页面如果使用了[[MediaWiki:Disambiguationspage]]内的模板，则会被视为消歧义页。",

'doubleredirects' => '双重重定向页',
'doubleredirectstext' => '此页列出了所有重定向到另一重定向页面的页面。每一行都包含有到第一和第二个重定向页面的链接，以及第二个重定向页面的目标——通常就是“真正的”目标页面，也就是第一个重定向页面应该指向的页面。<del>已划去</del>的条目是已经解决的项目。',
'double-redirect-fixed-move' => '[[$1]]已被移动。它现在重定向至[[$2]]。',
'double-redirect-fixed-maintenance' => '修复双重重定向自[[$1]]至[[$2]]。',
'double-redirect-fixer' => '重定向页修复器',

'brokenredirects' => '损坏的重定向页',
'brokenredirectstext' => '以下的重定向页面指向的是不存在的页面：',
'brokenredirects-edit' => '编辑',
'brokenredirects-delete' => '删除',

'withoutinterwiki' => '无语言链接的页面',
'withoutinterwiki-summary' => '以下的页面是未有语言链接到其它语言版本。',
'withoutinterwiki-legend' => '前缀',
'withoutinterwiki-submit' => '显示',

'fewestrevisions' => '最少版本页面',

# Miscellaneous special pages
'nbytes' => '$1字节',
'ncategories' => '$1个分类',
'ninterwikis' => '$1个跨语言链接',
'nlinks' => '$1个链接',
'nmembers' => '$1个成员',
'nrevisions' => '$1个版本',
'nviews' => '$1次浏览',
'nimagelinks' => '用于$1个页面中',
'ntransclusions' => '用于$1个页面中',
'specialpage-empty' => '该报告结果为空。',
'lonelypages' => '孤立页面',
'lonelypagestext' => '以下页面尚未被{{SITENAME}}中的其它页面链接或被之包含。',
'uncategorizedpages' => '未归类页面',
'uncategorizedcategories' => '未归类分类',
'uncategorizedimages' => '未归类文件',
'uncategorizedtemplates' => '未归类模板',
'unusedcategories' => '未使用分类',
'unusedimages' => '未使用图像',
'popularpages' => '热点页面',
'wantedcategories' => '需要的分类',
'wantedpages' => '待撰页面',
'wantedpages-badtitle' => '在结果组上的无效标题：$1',
'wantedfiles' => '需要的文件',
'wantedfiletext-cat' => '下列被使用的文件并不存在。已列出可能存在外部媒体库中的文件。任何此类误报将被<del>剔除</del>。此外，[[:$1]]列出列出了嵌入不存在文件的页面。',
'wantedfiletext-nocat' => '下列被使用的文件并不存在。已列出可能存在外部媒体库中的文件。任何此类误报将被<del>剔除</del>。',
'wantedtemplates' => '需要的模板',
'mostlinked' => '最多链接页面',
'mostlinkedcategories' => '最多链接分类',
'mostlinkedtemplates' => '最多链接模板',
'mostcategories' => '最多分类页面',
'mostimages' => '最多链接文件',
'mostinterwikis' => '最多跨语言链接页面',
'mostrevisions' => '最多版本页面',
'prefixindex' => '所有有前缀的页面',
'prefixindex-namespace' => '所有有前缀的页面（$1名字空间）',
'shortpages' => '短页面',
'longpages' => '长页面',
'deadendpages' => '断链页面',
'deadendpagestext' => '以下页面没有链接到{{SITENAME}}中的其它页面。',
'protectedpages' => '受保护页面',
'protectedpages-indef' => '仅无限期保护',
'protectedpages-cascade' => '仅连锁保护',
'protectedpagestext' => '以下页面受到保护，不能移移或编辑',
'protectedpagesempty' => '在这些参数下没有页面正在保护。',
'protectedtitles' => '受保护标题',
'protectedtitlestext' => '以下的页面已经被保护以防止创建',
'protectedtitlesempty' => '在这些参数之下并无标题正在保护。',
'listusers' => '用户列表',
'listusers-editsonly' => '只显示有编辑的用户',
'listusers-creationsort' => '按建立日期排序',
'usereditcount' => '$1次编辑',
'usercreated' => '$1 $2{{GENDER:$3|创建}}',
'newpages' => '新页面',
'newpages-username' => '用户名：',
'ancientpages' => '最早页面',
'move' => '移动',
'movethispage' => '移动本页',
'unusedimagestext' => '下列文件已存在，但并未插入任何页面。
请注意其它网站可能会直接通过URL链接此文件，因此下面列出的文件依然有可能被使用。',
'unusedcategoriestext' => '虽然没有被其它页面或者分类所采用，但列表中的分类页依然存在。',
'notargettitle' => '无目标',
'notargettext' => '您还没有指定一个目标页面或用户以进行此项操作。',
'nopagetitle' => '无目标页面',
'nopagetext' => '您所指定的目标页面并不存在。',
'pager-newer-n' => '前$1个',
'pager-older-n' => '后$1个',
'suppress' => '监督',
'querypage-disabled' => '此特殊页面由于性能原因已被禁用。',

# Book sources
'booksources' => '网络书源',
'booksources-search-legend' => '搜索网络书源',
'booksources-isbn' => 'ISBN:',
'booksources-go' => '提交',
'booksources-text' => '以下是一些网络书店的链接列表，其中可能有您要找的书籍的更多信息：',
'booksources-invalid-isbn' => '提供的ISBN号码并不正确，请检查原始复制来源号码是否有误。',

# Special:Log
'specialloguserlabel' => '执行者：',
'speciallogtitlelabel' => '目标（标题或用户）：',
'log' => '日志',
'all-logs-page' => '所有公开日志',
'alllogstext' => '所有{{SITENAME}}公开日志的联合展示。你可以通过选择日志类型、输入用户名（区分大小写）或相关页面（区分大小写）筛选日志条目。',
'logempty' => '在日志中不存在匹配项。',
'log-title-wildcard' => '搜索以该文字开头的标题',
'showhideselectedlogentries' => '显示/隐藏所选日志项',

# Special:AllPages
'allpages' => '所有页面',
'alphaindexline' => '$1到$2',
'nextpage' => '下一页（$1）',
'prevpage' => '上一页（$1）',
'allpagesfrom' => '显示从此处开始的页面：',
'allpagesto' => '显示从此处结束的页面：',
'allarticles' => '所有页面',
'allinnamespace' => '所有页面（$1名字空间）',
'allnotinnamespace' => '所有页面（非$1名字空间）',
'allpagesprev' => '前',
'allpagesnext' => '后',
'allpagessubmit' => '提交',
'allpagesprefix' => '显示具有此前缀（名字空间）的页面：',
'allpagesbadtitle' => '给定的页面标题是非法的，或者具有一个内部语言或内部 wiki 的前缀。它可能包含一个或更多的不能用于标题的字符。',
'allpages-bad-ns' => '在{{SITENAME}}中没有一个叫做"$1"的名字空间。',
'allpages-hide-redirects' => '隐藏重定向页',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => '您正在浏览本页的缓存版本，至多可能存在 $1 的延迟。',
'cachedspecial-viewing-cached-ts' => '您正浏览此页的缓存版本，不一定是最新的完整版本。',
'cachedspecial-refresh-now' => '查看最新的。',

# Special:Categories
'categories' => '分类',
'categoriespagetext' => '以下的{{PLURAL:$1|分类}}中包含了页面或媒体。
[[Special:UnusedCategories|未用分类]]不会在这里列示。
请同时参阅[[Special:WantedCategories|需要的分类]]。',
'categoriesfrom' => '显示由此项起之分类：',
'special-categories-sort-count' => '按数量排列',
'special-categories-sort-abc' => '按字母排列',

# Special:DeletedContributions
'deletedcontributions' => '已删除的用户贡献',
'deletedcontributions-title' => '已删除的用户贡献',
'sp-deletedcontributions-contribs' => '贡献',

# Special:LinkSearch
'linksearch' => '外部链接搜索',
'linksearch-pat' => '搜索网址：',
'linksearch-ns' => '名字空间：',
'linksearch-ok' => '搜索',
'linksearch-text' => '制作可以使用类似“*.wikipedia.org”的通配符。必须至少是顶级域名，例如“*.org”。<br />
支持的协议：<code>$1</code>（如果没有设置协议则默认为<nowiki>http://</nowiki>）。',
'linksearch-line' => '$1 链自 $2',
'linksearch-error' => '通配符仅可在主机名称的开头使用。',

# Special:ListUsers
'listusersfrom' => '给定显示用户条件：',
'listusers-submit' => '显示',
'listusers-noresult' => '找不到用户。',
'listusers-blocked' => '（已封禁）',

# Special:ActiveUsers
'activeusers' => '活跃用户列表',
'activeusers-intro' => '这个列表列出了最近$1天进行过操作的用户。',
'activeusers-count' => '最近$3天内有$1次编辑',
'activeusers-from' => '显示用户开始于：',
'activeusers-hidebots' => '隐藏机器人',
'activeusers-hidesysops' => '隐藏管理员',
'activeusers-noresult' => '找不到用户。',

# Special:Log/newusers
'newuserlogpage' => '用户创建日志',
'newuserlogpagetext' => '这是用户创建的日志。',

# Special:ListGroupRights
'listgrouprights' => '用户组权限',
'listgrouprights-summary' => '以下面是一个在这个wiki中定义出来的用户权限列表，以及它们的访问权。
更多有关个别权限的细节可以在[[{{MediaWiki:Listgrouprights-helppage}}|这里]]找到。',
'listgrouprights-key' => '* <span class="listgrouprights-granted">被授予的权限</span>
* <span class="listgrouprights-revoked">被取消的权限</span>',
'listgrouprights-group' => '用户组',
'listgrouprights-rights' => '权限',
'listgrouprights-helppage' => 'Help:用户组权限',
'listgrouprights-members' => '（成员列表）',
'listgrouprights-addgroup' => '添加{{PLURAL:$2|用户组}}：$1',
'listgrouprights-removegroup' => '删除{{PLURAL:$2|用户组}}：$1',
'listgrouprights-addgroup-all' => '添加所有用户组',
'listgrouprights-removegroup-all' => '删除所有用户组',
'listgrouprights-addgroup-self' => '添加{{PLURAL:$2|用户组}}至自己的账户：$1',
'listgrouprights-removegroup-self' => '删除自己的账户的{{PLURAL:$2|用户组}}：$1',
'listgrouprights-addgroup-self-all' => '添加所有用户组至自己的账户',
'listgrouprights-removegroup-self-all' => '删除自己的账户的所有用户组',

# E-mail user
'mailnologin' => '没有发送地址',
'mailnologintext' => '你必须[[Special:UserLogin|登录]]并在你的[[Special:Preferences|系统设置]]中拥有有效的电子邮件地址才能向其他用户发送电子邮件。',
'emailuser' => '电邮联系',
'emailuser-title-target' => '邮件联系该{{GENDER:$1|用户}}',
'emailuser-title-notarget' => '邮件联系',
'emailpage' => '电邮联系',
'emailpagetext' => '您可以使用下面的表单向该{{GENDER:$1|用户}}发送电子邮件消息。
您在[[Special:Preferences|个人设置]]中输入的电子邮件地址将显示为该邮件的“发件人”地址，所以收件人可以直接回复给您。',
'usermailererror' => 'Mail 对象返回错误：',
'defemailsubject' => '{{SITENAME}}来自用户“$1”的电子邮件',
'usermaildisabled' => '邮件发送功能已禁用',
'usermaildisabledtext' => '您不可以给这个 wiki 上的其他用户发送邮件',
'noemailtitle' => '无电子邮件地址',
'noemailtext' => '该用户还没有指定一个有效的电子邮件地址。',
'nowikiemailtitle' => '禁止电子邮件',
'nowikiemailtext' => '这位用户选择不接收其他用户的电子邮件。',
'emailnotarget' => '收件人不存在或无效的用户名。',
'emailtarget' => '输入收件人的用户名',
'emailusername' => '用户名：',
'emailusernamesubmit' => '提交',
'email-legend' => '发一封电子邮件至另一位{{SITENAME}}用户',
'emailfrom' => '发件人：',
'emailto' => '收件人：',
'emailsubject' => '主题：',
'emailmessage' => '信息：',
'emailsend' => '发送',
'emailccme' => '将我的消息的副本发送一份到我的邮箱。',
'emailccsubject' => '您发送给$1的消息的副本：$2',
'emailsent' => '电子邮件已发送',
'emailsenttext' => '您的电子邮件已经发出。',
'emailuserfooter' => '本邮件由{{SITENAME}}的“电邮用户”功能从$1发送至$2。',

# User Messenger
'usermessage-summary' => '留下系统信息。',
'usermessage-editor' => '系统信息编辑器',

# Watchlist
'watchlist' => '监视列表',
'mywatchlist' => '监视列表',
'watchlistfor2' => '$1的监视列表$2',
'nowatchlist' => '您的监视列表为空。',
'watchlistanontext' => '请$1以查看或编辑您的监视列表。',
'watchnologin' => '未登录',
'watchnologintext' => '您必须先[[Special:UserLogin|登录]]才能更改您的监视列表。',
'addwatch' => '添加至监视列表',
'addedwatchtext' => '页面“[[:$1]]”已添加至您的[[Special:Watchlist|监视列表]]。
本页面及其讨论页面的新增更改将会列入监视列表。',
'removewatch' => '从监视列表中删除',
'removedwatchtext' => '页面“[[:$1]]”已从[[Special:Watchlist|您的监视列表]]中删除。',
'watch' => '监视',
'watchthispage' => '监视本页',
'unwatch' => '取消监视',
'unwatchthispage' => '停止监视',
'notanarticle' => '非内容页面',
'notvisiblerev' => '上次由不同用户所作的修订版本已经删除',
'watchnochange' => '在显示的时间段内您所监视的页面没有更改。',
'watchlist-details' => '不包括讨论页面，您的监视列表中有$1个页面。',
'wlheader-enotif' => '* 已经启动电子邮件通知功能。',
'wlheader-showupdated' => "* 在您上次查看后有被修改过的页面会以'''粗体'''的形式被显示",
'watchmethod-recent' => '检查被监视页面的最近编辑',
'watchmethod-list' => '查看监视页中的最新修改',
'watchlistcontains' => '您的监视列表包含$1个页面。',
'iteminvalidname' => "页面'$1'错误，无效命名...",
'wlnote' => "下面是最后'''$2'''小时的最后'''$1'''个更改，截至$3 $4。",
'wlshowlast' => '显示最近$1小时、$2天或$3的更改',
'watchlist-options' => '监视列表选项',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => '正在监视...',
'unwatching' => '正在取消监视...',
'watcherrortext' => '更改“$1”的监视列表设置时出错。',

'enotif_mailer' => '{{SITENAME}}通知发送器',
'enotif_reset' => '标记所有页面为已访问',
'enotif_newpagetext' => '该页面为新页面。',
'enotif_impersonal_salutation' => '{{SITENAME}}用户',
'changed' => '更改',
'created' => '创建',
'enotif_subject' => '{{SITENAME}}页面“$PAGETITLE”已被$PAGEEDITOR$CHANGEDORCREATED',
'enotif_lastvisited' => '请浏览 $1 查看从您上次访问后的所有更改。',
'enotif_lastdiff' => '请浏览 $1 查看该更改。',
'enotif_anon_editor' => '匿名用户$1',
'enotif_body' => '亲爱的$WATCHINGUSERNAME：

你好！

{{SITENAME}}页面$PAGETITLE已于$PAGEEDITDATE被$PAGEEDITOR $CHANGEDORCREATED，请浏览 $PAGETITLE_URL 查看当前版本。
$NEWPAGE
编辑摘要：$PAGESUMMARY $PAGEMINOREDIT

你可以通过以下方式联系编者：
电子邮件：$PAGEEDITOR_EMAIL
用户页面：$PAGEEDITOR_WIKI

在你访问该页面之前，我们不会发送新增更改的通知。
你也可以重设你的监视列表中所有监视页面的通知标志。

友好的{{SITENAME}}通知系统

--
更改邮件通知设置：
{{canonicalurl:{{#special:Preferences}}}}
更改监视列表设置：
{{canonicalurl:{{#special:EditWatchlist}}}}
从监视列表中删除该页面：
$UNWATCHURL
反馈与其他帮助:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => '删除页面',
'confirm' => '确认',
'excontent' => '内容：“$1”',
'excontentauthor' => '内容：“$1”（唯一贡献者为“[[Special:Contributions/$2|$2]]”）',
'exbeforeblank' => '被清空前的内容为：“$1”',
'exblank' => '页面为空',
'delete-confirm' => '删除“$1”',
'delete-legend' => '删除',
'historywarning' => '警告：您将要删除的页面有约$1个{{PLURAL:$1|修订|修订}}版本的历史：',
'confirmdeletetext' => '您即将删除一个页面或图像以及其历史。
请确定您要进行此项操作，并且了解其后果，同时您的行为符合[[{{MediaWiki:Policy-url}}]]。',
'actioncomplete' => '操作完成',
'actionfailed' => '操作失败',
'deletedtext' => '“$1”已经被删除。最近删除的记录请参见$2。',
'dellogpage' => '删除日志',
'dellogpagetext' => '下面是最近的删除的列表。',
'deletionlog' => '删除记录',
'reverted' => '恢复到早期版本',
'deletecomment' => '原因：',
'deleteotherreason' => '其他/附加原因：',
'deletereasonotherlist' => '其他原因',
'deletereason-dropdown' => '*常见删除原因
** 作者申请
** 侵犯著作权
** 破坏行为',
'delete-edit-reasonlist' => '编辑删除原因',
'delete-toobig' => '这个页面有一个十分大量的编辑历史，超过$1次修订。删除此类页面的动作已经被限制，以防止在{{SITENAME}}上的意外扰乱。',
'delete-warning-toobig' => '这个页面有一个十分大量的编辑历史，超过$1次修订。删除它可能会扰乱{{SITENAME}}的数据库操作；在继续此动作前请小心。',

# Rollback
'rollback' => '回退编辑',
'rollback_short' => '回退',
'rollbacklink' => '回退',
'rollbacklinkcount' => '回退$1次编辑',
'rollbacklinkcount-morethan' => '回退超过$1次的编辑',
'rollbackfailed' => '回退失败',
'cantrollback' => '无法恢复编辑。最后的贡献者是本文的唯一作者。',
'alreadyrolled' => '无法回退[[User:$2|$2]]（[[User talk:$2|讨论]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]）对[[:$1]]的编辑，其他人已经编辑或者回退了该页。

本页最后的编辑者是[[User:$3|$3]]（[[User talk:$3|讨论]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]）。',
'editcomment' => '编辑摘要："<i>$1</i>"。',
'revertpage' => '已恢复[[Special:Contributions/$2|$2]]（[[User talk:$2|讨论]]）的编辑至[[User:$1|$1]]的最后一个修订版本',
'revertpage-nouser' => '恢复由（移除了的用户名）的编辑到[[User:$1|$1]]的最后一个修订版本',
'rollback-success' => '已恢复$1的编辑，更改回$2的最后修订版本。',

# Edit tokens
'sessionfailure-title' => '会话无效',
'sessionfailure' => '似乎您的登录会话有问题；
为了防止会话劫持，这个操作已经被取消。
请返回先前的页面，重新载入该页面，然后重试。',

# Protect
'protectlogpage' => '保护日志',
'protectlogtext' => '下面是页面保护更改的列表。请见[[Special:ProtectedPages|受保护页面列表]]查看目前正在进行的页面保护的列表。',
'protectedarticle' => '保护“[[$1]]”',
'modifiedarticleprotection' => '更改“[[$1]]”的保护等级',
'unprotectedarticle' => '解除“[[$1]]”的保护',
'movedarticleprotection' => '移动保护设置自“[[$2]]”至“[[$1]]”',
'protect-title' => '更改“$1”的保护等级',
'protect-title-notallowed' => '查看“$1”的保护等级',
'prot_1movedto2' => '[[$1]]移动至[[$2]]',
'protect-badnamespace-title' => '不可被保护的名字空间',
'protect-badnamespace-text' => '这个名字空间内的页面无法被保护。',
'protect-legend' => '确认保护',
'protectcomment' => '原因：',
'protectexpiry' => '到期：',
'protect_expiry_invalid' => '输入的终止时间无效。',
'protect_expiry_old' => '终止时间已过去。',
'protect-unchain-permissions' => '解除锁定更多的保护选项',
'protect-text' => '您可以在这里浏览和修改对页面<strong>$1</strong>的保护级别。',
'protect-locked-blocked' => "您不能在被封禁时更改保护级别。以下是页面'''$1'''的当前设置：",
'protect-locked-dblock' => "您不能在数据库锁定时更改保护级别。以下是页面'''$1'''的当前设置：",
'protect-locked-access' => "您的帐户没有足够的权限去更改保护级别。以下是页面'''$1'''的当前设置：",
'protect-cascadeon' => '以下的{{PLURAL:$1|一个|多个}}页面包含  本页面的同时，启动了连锁保护，因此本页面目前也被保护，未能编辑。您可以设置本页面的保护级别，但这并不会对连锁保护有所影响。',
'protect-default' => '允许所有用户',
'protect-fallback' => '仅允许拥有“$1”权限的用户',
'protect-level-autoconfirmed' => '仅允许自动确认用户',
'protect-level-sysop' => '仅允许管理员',
'protect-summary-cascade' => '联锁',
'protect-expiring' => '终止于$1（UTC）',
'protect-expiring-local' => '$1到期',
'protect-expiry-indefinite' => '无限期',
'protect-cascade' => '保护本页中包含的页面（连锁保护）',
'protect-cantedit' => '您无法更改这个页面的保护等级，因为您没有权限去编辑它。',
'protect-othertime' => '其它时间：',
'protect-othertime-op' => '其它时间',
'protect-existing-expiry' => '现时到期之时间：$2 $3',
'protect-otherreason' => '其他/附加原因：',
'protect-otherreason-op' => '其他原因',
'protect-dropdown' => '*常见保护原因
** 过度破坏
** 过多垃圾信息
** 负面的编辑战
** 高流量页面',
'protect-edit-reasonlist' => '编辑保护原因',
'protect-expiry-options' => '1小时:1 hour,1天:1 day,1周:1 week,2周:2 weeks,1个月:1 month,3个月:3 months,6个月:6 months,1年:1 year,无限期:infinite',
'restriction-type' => '权限：',
'restriction-level' => '限制级别：',
'minimum-size' => '最小大小',
'maximum-size' => '最大大小：',
'pagesize' => '（字节）',

# Restrictions (nouns)
'restriction-edit' => '编辑',
'restriction-move' => '移动',
'restriction-create' => '创建',
'restriction-upload' => '上传',

# Restriction levels
'restriction-level-sysop' => '全保护',
'restriction-level-autoconfirmed' => '半保护',
'restriction-level-all' => '任何级别',

# Undelete
'undelete' => '查看被删除页面',
'undeletepage' => '浏览及恢复被删页面',
'undeletepagetitle' => "'''以下包含[[:$1]]的已删除之修订版本'''。",
'viewdeletedpage' => '查看被删页面',
'undeletepagetext' => '以下{{PLURAL:$1|页面|$1个页面}}已被删除，但依然在归档中并可以被恢复。归档可能会被定时清理。',
'undelete-fieldset-title' => '恢复版本',
'undeleteextrahelp' => "恢复整个编辑历史时，请清除所有复选框后点击'''''{{int:undeletebtn}}'''''。恢复特定版本时，请选择相应版本前的复选框后点击'''''{{int:undeletebtn}}'''''。",
'undeleterevisions' => '$1版本存档',
'undeletehistory' => '如果您恢复了该页面，所有版本都会被恢复到修订历史中。
如果本页删除后有一个同名的新页面建立，被恢复的版本将会出现在先前的历史中。',
'undeleterevdel' => '如果把最新修订部分删除，反删除将会无法进行。如果遇到这种情况，您必须反选或反隐藏最新已删除的修订。',
'undeletehistorynoadmin' => '这个页面已被删除。删除原因显示在下方编辑摘要中，被删除前的所有修订文本连同删除前贡献用户的细节信息只对管理员可见。',
'undelete-revision' => '$1由$3（在$4 $5）所编写的已删除修订版本：',
'undeleterevision-missing' => '无效或丢失的修订版本。您可能使用了错误的链接，或者此修订版本已经被从存档中恢复或移除。',
'undelete-nodiff' => '找不到先前的修订版本。',
'undeletebtn' => '恢复',
'undeletelink' => '查看/恢复',
'undeleteviewlink' => '查看',
'undeletereset' => '重设',
'undeleteinvert' => '反向选择',
'undeletecomment' => '原因：',
'undeletedrevisions' => '$1个版本已恢复',
'undeletedrevisions-files' => '$1个版本和$2个文件已恢复',
'undeletedfiles' => '$1个文件已经被恢复',
'cannotundelete' => '恢复删除失败；可能已有其他人先行恢复了此页面。',
'undeletedpage' => "'''$1已经被恢复'''

参考[[Special:Log/delete|删除日志]]查看删除及恢复记录。",
'undelete-header' => '如要查询最近的记录请参阅[[Special:Log/delete|删除日志]]。',
'undelete-search-title' => '搜索已删除页面',
'undelete-search-box' => '搜索已删除页面',
'undelete-search-prefix' => '显示页面自：',
'undelete-search-submit' => '搜索',
'undelete-no-results' => '删除记录里没有符合的结果。',
'undelete-filename-mismatch' => '不能删除带有时间戳的文件修订$1：文件不匹配',
'undelete-bad-store-key' => '不能删除带有时间戳的文件修订$1：文件在删除前遗失。',
'undelete-cleanup-error' => '删除无用的存档文件“$1”时发生错误。',
'undelete-missing-filearchive' => '由于文件存档 ID $1 不在数据库中，不能在文件存档中恢复。它可能已经被恢复了。',
'undelete-error' => '恢复已删除页面时出错',
'undelete-error-short' => '恢复被删文件时发生错误：$1',
'undelete-error-long' => '恢复被删除的文件时出错：

$1',
'undelete-show-file-confirm' => '确定要查看在 $2 $3 ，"<nowiki>$1</nowiki>"的已删除修订版本吗？',
'undelete-show-file-submit' => '是',

# Namespace form on various pages
'namespace' => '名字空间：',
'invert' => '反选',
'tooltip-invert' => '选中此复选框来隐藏选定名字空间（及其相关名字空间，若该选项也被选中）范围内的页面更改',
'namespace_association' => '相关名字空间',
'tooltip-namespace_association' => '选中此复选框可包括与选定名字空间相关的讨论页或子页面',
'blanknamespace' => '（主要）',

# Contributions
'contributions' => '用户贡献',
'contributions-title' => '$1的用户贡献',
'mycontris' => '贡献',
'contribsub2' => '$1的贡献（$2）',
'nocontribs' => '没有找到符合特征的更改。',
'uctop' => '（最后更改）',
'month' => '截止月份：',
'year' => '截止年份：',

'sp-contributions-newbies' => '只显示新账户的贡献',
'sp-contributions-newbies-sub' => '新手',
'sp-contributions-newbies-title' => '新手的用户贡献',
'sp-contributions-blocklog' => '封禁日志',
'sp-contributions-deleted' => '已删除的用户贡献',
'sp-contributions-uploads' => '上传',
'sp-contributions-logs' => '日志',
'sp-contributions-talk' => '讨论',
'sp-contributions-userrights' => '用户权限管理',
'sp-contributions-blocked-notice' => '这位用户现时正在被封锁中。
最近的封锁日志项目在下面提供以便参考：',
'sp-contributions-blocked-notice-anon' => '这个IP地址现时正在被封锁中。
最近的封锁日志项目在下面提供以便参考：',
'sp-contributions-search' => '搜索贡献',
'sp-contributions-username' => 'IP地址或用户名：',
'sp-contributions-toponly' => '只显示最后修订版本的编辑',
'sp-contributions-submit' => '搜索',

# What links here
'whatlinkshere' => '链入页面',
'whatlinkshere-title' => '链接至“$1”的页面',
'whatlinkshere-page' => '页面：',
'linkshere' => "以下页面链接至'''[[:$1]]'''：",
'nolinkshere' => "没有页面链接至'''[[:$1]]'''。",
'nolinkshere-ns' => "在所选的名字空间内没有页面链接到'''[[:$1]]'''。",
'isredirect' => '重定向页',
'istemplate' => '包含',
'isimage' => '文件链接',
'whatlinkshere-prev' => '上$1个',
'whatlinkshere-next' => '下$1个',
'whatlinkshere-links' => '←链入页面',
'whatlinkshere-hideredirs' => '$1重定向',
'whatlinkshere-hidetrans' => '$1包含',
'whatlinkshere-hidelinks' => '$1链接',
'whatlinkshere-hideimages' => '$1个文件链接',
'whatlinkshere-filters' => '过滤器',

# Block/unblock
'autoblockid' => '自动封禁#$1',
'block' => '封禁用户',
'unblock' => '解封用户',
'blockip' => '封禁用户',
'blockip-title' => '封禁用户',
'blockip-legend' => '封禁用户',
'blockiptext' => '使用下方的表单来禁止来自特定IP地址或用户名的写访问。
只有在为了防止破坏，并符合[[{{MediaWiki:Policy-url}}|方针]]的情况下才可采取此行动。
请在下面输入一个具体的理由（例如引述一个被破坏的页面）。',
'ipadressorusername' => 'IP地址或用户名：',
'ipbexpiry' => '期限：',
'ipbreason' => '原因：',
'ipbreasonotherlist' => '其他原因',
'ipbreason-dropdown' => '*常见封禁原因
** 插入虚假信息
** 删除页面内容
** 添加垃圾外部链接
** 插入无意义文字
** 恐吓行为/骚扰
** 滥用多个账户
** 不能接受的用户名',
'ipb-hardblock' => '阻止登录用户使用该IP地址编辑',
'ipbcreateaccount' => '阻止创建新账号',
'ipbemailban' => '阻止用户发送邮件',
'ipbenableautoblock' => '自动封禁该用户最后使用的IP地址，以及他们随后试图用于编辑的所有IP地址',
'ipbsubmit' => '封禁该用户',
'ipbother' => '其它时间：',
'ipboptions' => '2小时:2 hours,1天:1 day,3天:3 days,1周:1 week,2周:2 weeks,1个月:1 month,3个月:3 months,6个月:6 months,1年:1 year,无限期:infinite',
'ipbotheroption' => '其他',
'ipbotherreason' => '其他/附加原因：',
'ipbhidename' => '在编辑及列表中隐藏用户名',
'ipbwatchuser' => '监视该用户的用户页面和讨论页面',
'ipb-disableusertalk' => '阻止用户在封禁期间编辑自己的讨论页面',
'ipb-change-block' => '使用这些设置重新封禁用户',
'ipb-confirm' => '确认封禁',
'badipaddress' => '无效IP地址',
'blockipsuccesssub' => '封禁成功',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]]已被封禁。<br />
参见[[Special:BlockList|封禁列表]]以复核封禁。',
'ipb-blockingself' => '您将要封禁自己！确实要这样做吗？',
'ipb-confirmhideuser' => '您即将在封禁用户的同时启用“隐藏账户”功能。这将从所有列表和日志记录中隐藏这个用户名。您确认这样做吗？',
'ipb-edit-dropdown' => '编辑封禁原因',
'ipb-unblock-addr' => '解封$1',
'ipb-unblock' => '解封用户名或IP地址',
'ipb-blocklist' => '查看现有封禁',
'ipb-blocklist-contribs' => '$1的贡献',
'unblockip' => '解封用户',
'unblockiptext' => '使用下列表单来恢复之前被封禁的IP地址或用户名的写权限。',
'ipusubmit' => '解除此封禁',
'unblocked' => '[[User:$1|$1]]已经被解封',
'unblocked-range' => '$1已被解封',
'unblocked-id' => '封禁$1已被解除',
'blocklist' => '被封禁用户',
'ipblocklist' => '被封禁用户',
'ipblocklist-legend' => '查找被封禁用户',
'blocklist-userblocks' => '隐藏帐户封禁',
'blocklist-tempblocks' => '隐藏临时封禁',
'blocklist-addressblocks' => '隐藏单个IP封禁',
'blocklist-rangeblocks' => '隐藏IP段封禁',
'blocklist-timestamp' => '时间',
'blocklist-target' => '目标',
'blocklist-expiry' => '到期',
'blocklist-by' => '封禁管理员',
'blocklist-params' => '封禁范围',
'blocklist-reason' => '原因',
'ipblocklist-submit' => '搜索',
'ipblocklist-localblock' => '本地封禁',
'ipblocklist-otherblocks' => '其他{{PLURAL:$1|封禁}}',
'infiniteblock' => '无限期',
'expiringblock' => '$1 $2到期',
'anononlyblock' => '仅匿名用户',
'noautoblockblock' => '自动封禁已禁用',
'createaccountblock' => '创建帐户已禁用',
'emailblock' => '发送邮件已禁用',
'blocklist-nousertalk' => '不能编辑自己的讨论页面',
'ipblocklist-empty' => '封禁列表为空。',
'ipblocklist-no-results' => '请求的IP地址或用户名没有被封禁。',
'blocklink' => '封禁',
'unblocklink' => '解封',
'change-blocklink' => '更改封禁',
'contribslink' => '贡献',
'emaillink' => '发送邮件',
'autoblocker' => '由于您与“[[User:$1|$1]]”共享一个IP地址而被自动封禁。
$1被封禁的理由是：“$2”',
'blocklogpage' => '封禁日志',
'blocklog-showlog' => '该用户曾被封禁。下面提供封禁日志以供参考：',
'blocklog-showsuppresslog' => '该用户曾被封禁并隐藏。下面提供封锁日志以供参考：',
'blocklogentry' => '封禁[[$1]]，到期时间为$2$3',
'reblock-logentry' => '更改[[$1]]的封禁设置，到期时间为$2$3',
'blocklogtext' => '这是用户封禁和解封操作的日志。自动封禁IP地址没有列出。请见[[Special:BlockList|封禁列表]]查看目前正在进行的阻止和封禁的列表。',
'unblocklogentry' => '解封$1',
'block-log-flags-anononly' => '仅限匿名用户',
'block-log-flags-nocreate' => '账户创建停用',
'block-log-flags-noautoblock' => '自动封禁已禁用',
'block-log-flags-noemail' => '邮件功能已禁用',
'block-log-flags-nousertalk' => '不能编辑自己的讨论页面',
'block-log-flags-angry-autoblock' => '已启用增强型自动封禁',
'block-log-flags-hiddenname' => '隐藏用户名',
'range_block_disabled' => '管理员执行段封禁的权限已被禁用。',
'ipb_expiry_invalid' => '无效的终止时间。',
'ipb_expiry_temp' => '隐藏用户名的封禁必须是永久性的。',
'ipb_hide_invalid' => '无法隐藏此账户，它可能有太多编辑。',
'ipb_already_blocked' => '“$1”已被封禁',
'ipb-needreblock' => '$1已被封禁。您是否想更改封禁设置？',
'ipb-otherblocks-header' => '其他{{PLURAL:$1|封禁|封禁}}',
'unblock-hideuser' => '您无法取消封禁该用户，因为他们的用户名已被隐藏。',
'ipb_cant_unblock' => '错误：找不到封禁ID$1。可能已经解除封禁。',
'ipb_blocked_as_range' => '错误：IP地址$1未被直接封禁，故无法解除封禁。然而，它位于IP地址段$2的封禁范围内，后者可被解除封禁。',
'ip_range_invalid' => '无效的IP地址段。',
'ip_range_toolarge' => '不允许大于/$1的段封禁。',
'blockme' => '封禁我',
'proxyblocker' => '代理封禁器',
'proxyblocker-disabled' => '此功能已禁用。',
'proxyblockreason' => '您的IP地址为已被封禁的公开代理。请联系您的互联网服务提供商或技术支持者，并告知他们此严重的安全问题。',
'proxyblocksuccess' => '完成。',
'sorbsreason' => '在{{SITENAME}}使用的DNSBL中，您的IP地址被列为公开代理。',
'sorbs_create_account_reason' => '在{{SITENAME}}使用的DNSBL中，您的IP地址被列为公开代理，因此您不能创建新账户。',
'cant-block-while-blocked' => '您无法在封禁期内封禁其他用户。',
'cant-see-hidden-user' => '您尝试封禁的用户已被封禁并隐藏。
由于您尚无隐藏用户的权限，您无法查看或编辑此用户的封禁。',
'ipbblocked' => '您无法封禁或解封其他用户，因为您自己已被封禁',
'ipbnounblockself' => '您无法自我解除封禁',

# Developer tools
'lockdb' => '锁定数据库',
'unlockdb' => '解锁数据库',
'lockdbtext' => '锁定数据库将禁止所有用户编辑页面、更改参数、编辑监视列表以及其他需要更改数据库的操作。请确认您的决定，并确保在维护工作结束后会重新开放数据库。',
'unlockdbtext' => '解锁数据库将会恢复所有用户编辑页面、修改参数、编辑监视列表以及其他需要更改数据库的操作。请确认您的决定。',
'lockconfirm' => '是，我的确想要锁定数据库。',
'unlockconfirm' => '是，我的确想要解锁数据库。',
'lockbtn' => '数据库锁定',
'unlockbtn' => '解锁数据库',
'locknoconfirm' => '您没有勾选确认框。',
'lockdbsuccesssub' => '数据库锁定成功',
'unlockdbsuccesssub' => '数据库解锁成功',
'lockdbsuccesstext' => '数据库已锁定。<br />请记住在维护工作完成后[[Special:UnlockDB|解锁数据库]]。',
'unlockdbsuccesstext' => '数据库已解锁。',
'lockfilenotwritable' => '数据库锁定文件不可写。要锁定和解锁数据库，该文件必须对网络服务器可写。',
'databasenotlocked' => '数据库没有锁定。',
'lockedbyandtime' => '（由 {{GENDER:$1|$1}} 于$2 $3执行）',

# Move page
'move-page' => '移动$1',
'move-page-legend' => '移动页面',
'movepagetext' => "您可以使用下面的表单来重命名一个页面，同时将其修订历史移动到新页面。
同时老的条目将会被重定向到新条目。
您可以自动地将重定向更新到原条目。
如果您不选择这样做的话，请检查[[Special:DoubleRedirects|双重]]或[[Special:BrokenRedirects|损坏重定向]]链接。
您有责任确保链接会被正确指向他们应该被指向的地方。

注意：即使新条目已经有对应页面，此页面也'''不会'''被移动，除非新页面无任何编辑历史或是重定向页。
这意味着您可在误操作后将页面移回原处，同时，您也无法覆盖现有页面。

'''警告！'''
对这样一个经常被访问的页面而言这可能是一个重大且唐突的更改；
请在行动前先了解您的修改可能带来的一切后果。",
'movepagetext-noredirectfixer' => "用下面的表单来重命名一个页面，并将其修订历史同时移动到新页面。
老的页面将成为新页面的重定向页。
请检查[[Special:DoubleRedirects|双重重定向]]或[[Special:BrokenRedirects|损坏重定向]]链接。
您应当负责确定所有链接依然会链到指定的页面。

注意如果新页面已经有内容的话，页面将'''不会'''被移动，
除非新页面无内容或是重定向页，而且没有修订历史。
这意味着您再必要时可以在移动到新页面后再移回老的页面，
同时您也无法覆盖现有页面。

'''警告！'''
对一个经常被访问的页面而言这可能是一个重大与唐突的更改；
请在行动前先确定您了解其所可能带来的后果。",
'movepagetalktext' => "有关的讨论页将被自动与该页面一起移动，'''除非''':
*新页面已经有一个包含内容的讨论页，或者
*您不勾选下面的复选框。

在这些情况下，您在必要时必须手工移动或合并页面。",
'movearticle' => '移动页面：',
'moveuserpage-warning' => "'''警告：'''您将移动一个用户页面。请注意，只有该页面会被移动，该用户''不''会被更名。",
'movenologin' => '未登录',
'movenologintext' => '您必须是一名登记用户并且[[Special:UserLogin|登录]]
后才可移动一个页面。',
'movenotallowed' => '您没有权限移动页面。',
'movenotallowedfile' => '您没有权限移动文件。',
'cant-move-user-page' => '您没有权限移动用户页面（子页面除外）。',
'cant-move-to-user-page' => '您没有权限移动页面至用户页面（用户子页面除外）。',
'newtitle' => '新标题：',
'move-watch' => '监视来源页面和目标页面',
'movepagebtn' => '移动页面',
'pagemovedsub' => '移动成功',
'movepage-moved' => "'''“$1”已移动到“$2”'''",
'movepage-moved-redirect' => '重定向已创建。',
'movepage-moved-noredirect' => '重定向的创建已被禁用。',
'articleexists' => '该名称的页面已存在，或者您使用的名称无效。请另选一名。',
'cantmove-titleprotected' => '您无法将页面移动到该位置，因为新标题已被保护以防止创建。',
'talkexists' => "'''页面本身移动成功，但由于新标题下已有讨论页存在，故讨论页无法移动。请手工合并这两个页面。'''",
'movedto' => '移动到',
'movetalk' => '移动关联的讨论页',
'move-subpages' => '移动子页面（上至$1页）',
'move-talk-subpages' => '如果可能，移动子对话页面（上至$1页）',
'movepage-page-exists' => '页面$1已存在，无法自动覆盖。',
'movepage-page-moved' => '页面$1已经移动到$2。',
'movepage-page-unmoved' => '页面$1无法移动到$2。',
'movepage-max-pages' => '所移动$1个页面的数量已达最大限额，无法同时自动移动更多页面。',
'movelogpage' => '移动日志',
'movelogpagetext' => '下面是所有页面移动的列表。',
'movesubpage' => '{{PLURAL:$1|子页面}}',
'movesubpagetext' => '该页面有$1个子页面在下面展示。',
'movenosubpage' => '这个页面没有子页面。',
'movereason' => '原因：',
'revertmove' => '恢复',
'delete_and_move' => '删除并移动',
'delete_and_move_text' => '==　需要删除　==

目标页面“[[:$1]]”已存在。是否确认删除该页面以便进行移动？',
'delete_and_move_confirm' => '是，删除该页面',
'delete_and_move_reason' => '删除以便移动[[$1]]',
'selfmove' => '原始标题和目标标题相同，无法对页面进行自我移动。',
'immobile-source-namespace' => '无法移动名字空间为“$1”的页面',
'immobile-target-namespace' => '无法将页面移动到“$1”名字空间',
'immobile-target-namespace-iw' => '在移动页面时，跨wiki链接不是有效的目标。',
'immobile-source-page' => '此页面不能移动。',
'immobile-target-page' => '无法移动至该目标标题。',
'imagenocrossnamespace' => '无法将文件移动到非文件名字空间',
'nonfile-cannot-move-to-file' => '无法将非文件移动到文件名字空间',
'imagetypemismatch' => '该新扩展名与其类型不匹配',
'imageinvalidfilename' => '目标文件名称无效',
'fix-double-redirects' => '更新所有指向原始标题的重定向',
'move-leave-redirect' => '保留重定向',
'protectedpagemovewarning' => "'''警告：'''本页面已被保护，只有拥有管理员权限的用户可以移动。下面提供最后的日志条目以供参考：",
'semiprotectedpagemovewarning' => "'''注意：'''本页面已被保护，只有注册用户可以移动。下面提供最后的日志条目以供参考：",
'move-over-sharedrepo' => '== 文件已存在 ==
[[:$1]]已于共享资源存在，将文件移动到此标题会覆盖共享资源中的文件。',
'file-exists-sharedrepo' => '同名文件已于共享资源存在。
请选择另一个文件名。',

# Export
'export' => '导出页面',
'exporttext' => '您可以将特定页面或一组页面的文本以及编辑历史以 XML 格式导出；这样可以将有关页面通过“[[Special:Import|导入页面]]”导入到另一个运行 MediaWiki 的网站。

要导出页面，请在下面的文本框中输入页面标题，每行一个标题，并选择您是否需要导出带有页面历史的以前的修订版本，或是只选择导出带有最后一次编辑信息的当前修订版本。

此外您还可以利用链接导出文件，例如您可以使用[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]导出“[[{{MediaWiki:Mainpage}}]]”页面。',
'exportall' => '导出所有页面',
'exportcuronly' => '仅包含当前的修订，而不是全部的历史。',
'exportnohistory' => "----
'''注意：'''由于性能原因，从此表单导出页面的全部历史已被禁用。",
'exportlistauthors' => '为每个页面包含贡献者的完整列表',
'export-submit' => '导出',
'export-addcattext' => '从分类添加页面：',
'export-addcat' => '添加',
'export-addnstext' => '从名字空间添加页面：',
'export-addns' => '添加',
'export-download' => '另存为文件',
'export-templates' => '包含模板',
'export-pagelinks' => '包含链接页面的搜索深度：',

# Namespace 8 related
'allmessages' => '系统信息',
'allmessagesname' => '名称',
'allmessagesdefault' => '默认信息文字',
'allmessagescurrent' => '当前消息文本',
'allmessagestext' => '此处列出了MediaWiki名字空间下的所有有效系统消息。
如果想为MediaWiki的本地化贡献翻译，请访问[//www.mediawiki.org/wiki/Localisation MediaWiki本地化]和[//translatewiki.net translatewiki.net]。',
'allmessagesnotsupportedDB' => "此页面无法使用，因为'''\$wgUseDatabaseMessages'''已被设置关闭。",
'allmessages-filter-legend' => '过滤',
'allmessages-filter' => '以自定状况过滤：',
'allmessages-filter-unmodified' => '未修改',
'allmessages-filter-all' => '所有',
'allmessages-filter-modified' => '曾修改',
'allmessages-prefix' => '以前缀过滤：',
'allmessages-language' => '语言：',
'allmessages-filter-submit' => '提交',

# Thumbnails
'thumbnail-more' => '放大',
'filemissing' => '无法找到文件',
'thumbnail_error' => '生成缩略图错误：$1',
'djvu_page_error' => 'DjVu页面超出范围',
'djvu_no_xml' => '无法在DjVu文件中获取XML',
'thumbnail-temp-create' => '无法创建临时缩略图文件',
'thumbnail-dest-create' => '无法将缩略图保存到目标地点',
'thumbnail_invalid_params' => '不正确的缩略图参数',
'thumbnail_dest_directory' => '无法建立目标目录',
'thumbnail_image-type' => '图像类型不支持',
'thumbnail_gd-library' => '未完成的GD设置：功能遗失 $1',
'thumbnail_image-missing' => '文件可能丢失：$1',

# Special:Import
'import' => '导入页面',
'importinterwiki' => '跨wiki导入',
'import-interwiki-text' => '选择要导入的wiki和页面标题，导入修订的日期和编辑者名称会被保存。所有的跨wiki导入操作都将记录到[[Special:Log/import|导入日志]]。',
'import-interwiki-source' => '来源wiki／页面：',
'import-interwiki-history' => '复制此页的所有历史修订版本',
'import-interwiki-templates' => '包含所有模板',
'import-interwiki-submit' => '导入',
'import-interwiki-namespace' => '目标名字空间：',
'import-interwiki-rootpage' => '目的根页（可选）：',
'import-upload-filename' => '文件名：',
'import-comment' => '注释：',
'importtext' => '请使用[[Special:Export|导出功能]]从源 wiki 导出文件，
保存到您的电脑并上传到这里。',
'importstart' => '正在导入页面...',
'import-revision-count' => '$1个版本',
'importnopages' => '没有导入的页面。',
'imported-log-entries' => '导入了$1项日志记录。',
'importfailed' => '导入失败：<nowiki>$1</nowiki>',
'importunknownsource' => '未知的源导入类型',
'importcantopen' => '无法打开导入文件',
'importbadinterwiki' => '无效的跨wiki链接',
'importnotext' => '空或没有文本',
'importsuccess' => '导入完成！',
'importhistoryconflict' => '存在冲突的修订历史（可能在之前已经导入过此页面）',
'importnosources' => '跨Wiki导入源没有定义，同时不允许直接的历史上传。',
'importnofile' => '没有上传导入文件。',
'importuploaderrorsize' => '上传导入文件失败。文件大于可以允许的上传大小。',
'importuploaderrorpartial' => '上传导入文件失败。文件只有部份已经上传。',
'importuploaderrortemp' => '上传导入文件失败。临时文件夹已遗失。',
'import-parse-failure' => 'XML导入语法失败',
'import-noarticle' => '没有页面作导入！',
'import-nonewrevisions' => '所有的修订之前曾已导入。',
'xml-error-string' => '$1于行$2，列$3（$4字节）：$5',
'import-upload' => '上传XML数据',
'import-token-mismatch' => '会话数据遗失。请重试。',
'import-invalid-interwiki' => '不能从指定的wiki导入。',
'import-error-edit' => '"$1"页面不导入，因为您不准对其进行编辑。',
'import-error-create' => '"$1"页面不导入，因为您不准创建它。',
'import-error-interwiki' => '页面“$1”未能导入，因为它的名称需要使用外部跨wiki链接。',
'import-error-special' => '页面“$1”未导入，因为它需要使用一个不能创建页面的特殊名字空间。',
'import-error-invalid' => '页面“$1”未能导入，因为它的名字无效。',
'import-options-wrong' => '{{PLURAL:$2|选项}}出错：<nowiki>$1</nowiki>',
'import-rootpage-invalid' => '根页面的标题无效。',
'import-rootpage-nosubpage' => '名字空间为“$1”的根页面不允许子页面。',

# Import log
'importlogpage' => '导入日志',
'importlogpagetext' => '管理性导入在其他wiki上有编辑历史的页面。',
'import-logentry-upload' => '以文件上传导入[[$1]]',
'import-logentry-upload-detail' => '$1个版本',
'import-logentry-interwiki' => '跨wiki$1',
'import-logentry-interwiki-detail' => '来自$2的$1个修订',

# JavaScriptTest
'javascripttest' => 'JavaScript测试',
'javascripttest-disabled' => '该wiki站点上尚未启用此功能。',
'javascripttest-title' => '运行$1测试',
'javascripttest-pagetext-noframework' => '此页面被保留用作执行 JavaScript 测试。',
'javascripttest-pagetext-unknownframework' => '未知的框架“$1”。',
'javascripttest-pagetext-frameworks' => '请选择以下的框架之一：$1',
'javascripttest-pagetext-skins' => '选择外观来运行测试：',
'javascripttest-qunit-intro' => '请浏览 mediawiki.org 查看[$1 测试文档]。',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit 测试套件',

# Tooltip help for the actions
'tooltip-pt-userpage' => '您的用户页面',
'tooltip-pt-anonuserpage' => '您用于编辑的 IP 地址的用户页面',
'tooltip-pt-mytalk' => '您的讨论页面',
'tooltip-pt-anontalk' => '有关本IP地址的编辑的讨论',
'tooltip-pt-preferences' => '您的系统设置',
'tooltip-pt-watchlist' => '您正在监视的页面的列表',
'tooltip-pt-mycontris' => '您的贡献列表',
'tooltip-pt-login' => '我们鼓励你登录，不过这不是强制的',
'tooltip-pt-anonlogin' => '我们鼓励你登录，不过这不是强制的',
'tooltip-pt-logout' => '注销',
'tooltip-ca-talk' => '有关内容页面的讨论',
'tooltip-ca-edit' => '你可以编辑本页面。请在保存前使用预览按钮。',
'tooltip-ca-addsection' => '开始新段落',
'tooltip-ca-viewsource' => '本页面受保护。您可以查看它的源代码。',
'tooltip-ca-history' => '本页面的历史版本',
'tooltip-ca-protect' => '保护本页',
'tooltip-ca-unprotect' => '更改本页面的保护',
'tooltip-ca-delete' => '删除本页',
'tooltip-ca-undelete' => '将这个页面恢复到被删除以前的状态',
'tooltip-ca-move' => '移动本页',
'tooltip-ca-watch' => '添加本页面至您的监视列表',
'tooltip-ca-unwatch' => '从您的监视列表中删除本页',
'tooltip-search' => '在{{SITENAME}}中搜索',
'tooltip-search-go' => '如果相同的标题存在的话便直接前往该页面',
'tooltip-search-fulltext' => '搜索含这些文字的页面',
'tooltip-p-logo' => '访问首页',
'tooltip-n-mainpage' => '访问首页',
'tooltip-n-mainpage-description' => '访问首页',
'tooltip-n-portal' => '关于本项目，你可以做什么，在哪里找到你需要的事物',
'tooltip-n-currentevents' => '查看当前事件的背景信息',
'tooltip-n-recentchanges' => '本wiki的最近更改列表',
'tooltip-n-randompage' => '载入一个随机页面',
'tooltip-n-help' => '查找帮助的地方',
'tooltip-t-whatlinkshere' => '所有链入本页的wiki页面列表',
'tooltip-t-recentchangeslinked' => '本页链入的页面的最近更改',
'tooltip-feed-rss' => '本页面的RSS源',
'tooltip-feed-atom' => '本页面的Atom源',
'tooltip-t-contributions' => '查看该用户的贡献列表',
'tooltip-t-emailuser' => '给该用户发送电子邮件',
'tooltip-t-upload' => '上传文件',
'tooltip-t-specialpages' => '所有特殊页面的列表',
'tooltip-t-print' => '本页面的可打印版本',
'tooltip-t-permalink' => '本页面该版本的永久链接',
'tooltip-ca-nstab-main' => '查看内容页面',
'tooltip-ca-nstab-user' => '查看用户页面',
'tooltip-ca-nstab-media' => '查看媒体文件页面',
'tooltip-ca-nstab-special' => '本页为特殊页面，你不能编辑本页',
'tooltip-ca-nstab-project' => '查看项目页面',
'tooltip-ca-nstab-image' => '查看文件页面',
'tooltip-ca-nstab-mediawiki' => '查看系统信息',
'tooltip-ca-nstab-template' => '查看模板',
'tooltip-ca-nstab-help' => '查看帮助页面',
'tooltip-ca-nstab-category' => '查看分类页面',
'tooltip-minoredit' => '标记本编辑为小编辑',
'tooltip-save' => '保存您的更改',
'tooltip-preview' => '预览您的更改，请在保存前使用此功能！',
'tooltip-diff' => '显示您对该文字所做的更改',
'tooltip-compareselectedversions' => '查看此页面两个选定的修订版本间的差异。',
'tooltip-watch' => '添加本页面至您的监视列表',
'tooltip-watchlistedit-normal-submit' => '删除标题',
'tooltip-watchlistedit-raw-submit' => '更新监视列表',
'tooltip-recreate' => '重建该页面，无论是否被删除。',
'tooltip-upload' => '开始上传',
'tooltip-rollback' => '单击“回退”恢复上一位贡献者对本页的编辑',
'tooltip-undo' => '“撤销”可以恢复该编辑并在预览模式下打开编辑表单。它允许在摘要中加入原因。',
'tooltip-preferences-save' => '保存设置',
'tooltip-summary' => '请输入简短的摘要',

# Stylesheets
'common.css' => '/* 此处的 CSS 将应用于所有的皮肤 */',
'standard.css' => '/* 此处的 CSS 将影响使用标准皮肤的用户 */',
'nostalgia.css' => '/* 此处的 CSS 将影响使用怀旧皮肤的用户 */',
'cologneblue.css' => '/* 此处的 CSS 将影响使用科隆香水蓝皮肤的用户 */',
'monobook.css' => '/* 此处的 CSS 将影响使用 Monobook 皮肤的用户 */',
'myskin.css' => '/* 此处的 CSS 将影响使用 MySkin 皮肤的用户 */',
'chick.css' => '/* 此处的 CSS 将影响使用 Chick 皮肤的用户 */',
'simple.css' => '/* 此处的 CSS 将影响使用 Simple 皮肤的用户 */',
'modern.css' => '/* 此处的 CSS 将影响使用 Modern 皮肤的用户 */',
'vector.css' => '/* 此处的 CSS 将影响使用 Vector 皮肤的用户 */',
'print.css' => '/* 此处的 CSS 将影响打印输出 */',
'handheld.css' => '/* 此处的 CSS 将影响在 $wgHandheldStyle 设置手提装置面板 */',
'noscript.css' => '/* 此处的 CSS 将影响没有启用 JavaScript 的用户 */',
'group-autoconfirmed.css' => '/* 此处的 CSS 将只会影响自动确认用户 */',
'group-bot.css' => '/* 此处的 CSS 将只会影响机器人 */',
'group-sysop.css' => '/* 此处的 CSS 将只会影响管理员 */',
'group-bureaucrat.css' => '/* 此处的 CSS 将只会影响行政员 */',

# Scripts
'common.js' => '/* 此处的JavaScript将加载于所有用户每一个页面。 */',
'standard.js' => '/* 此处的JavaScript将加载于使用标准皮肤的用户 */',
'nostalgia.js' => '/* 此处的JavaScript将加载于使用怀旧皮肤的用户 */',
'cologneblue.js' => '/* 此处的JavaScript将加载于使用科隆香水蓝皮肤的用户 */',
'monobook.js' => '/* 此处的JavaScript将加载于使用Monobook皮肤的用户 */',
'myskin.js' => '/* 此处的JavaScript将加载于使用MySkin皮肤的用户 */',
'chick.js' => '/* 此处的JavaScript将加载于使用Chick皮肤的用户 */',
'simple.js' => '/* 此处的JavaScript将加载于使用Simple皮肤的用户 */',
'modern.js' => '/* 此处的JavaScript将加载于使用Modern皮肤的用户 */',
'vector.js' => '/* 此处的JavaScript将加载于使用Vector皮肤的用户 */',

# Metadata
'notacceptable' => '该网站服务器不能提供您的客户端能识别的数据格式。',

# Attribution
'anonymous' => '{{SITENAME}}匿名{{PLURAL:$1|用户}}',
'siteuser' => '{{SITENAME}}用户$1',
'anonuser' => '{{SITENAME}}匿名用户$1',
'lastmodifiedatby' => '本页面被$3最后修改于$1 $2。',
'othercontribs' => '基于$1的工作。',
'others' => '其他',
'siteusers' => '{{SITENAME}}{{PLURAL:$2|用户}}$1',
'anonusers' => '{{SITENAME}}匿名{{PLURAL:$2|用户}}$1',
'creditspage' => '页面编辑名单',
'nocredits' => '本页面没有编辑名单信息。',

# Spam protection
'spamprotectiontitle' => '垃圾链接过滤器',
'spamprotectiontext' => '您要保存的文本被垃圾过滤器阻止。
这可能是由于一个链往匹配黑名单的外部站点的链接引起的。',
'spamprotectionmatch' => '以下文本触发了我们的垃圾链接过滤器：$1',
'spambot_username' => 'MediaWiki垃圾链接清理器',
'spam_reverting' => '恢复到不包含链接的最近修订版本$1',
'spam_blanking' => '消隐所有包含链接至$1的修订',
'spam_deleting' => '正在删除所有包含至$1的版本',

# Info page
'pageinfo-title' => '“$1”的信息',
'pageinfo-not-current' => '只能显示当前修订版本的信息。',
'pageinfo-header-basic' => '基本信息',
'pageinfo-header-edits' => '编辑历史',
'pageinfo-header-restrictions' => '页面保护',
'pageinfo-header-properties' => '页面属性',
'pageinfo-display-title' => '显示的标题',
'pageinfo-default-sort' => '默认排序字',
'pageinfo-length' => '页面长度（字节）',
'pageinfo-article-id' => '页面ID',
'pageinfo-robot-policy' => '搜索引擎状态',
'pageinfo-robot-index' => '可索引',
'pageinfo-robot-noindex' => '不可索引',
'pageinfo-views' => '查看次数',
'pageinfo-watchers' => '页面监视者人数',
'pageinfo-redirects-name' => '重定向到本页',
'pageinfo-subpages-name' => '本页的子页面',
'pageinfo-subpages-value' => '$1 （$2个重定向；$3个非重定向）',
'pageinfo-firstuser' => '页面创建者',
'pageinfo-firsttime' => '页面创建日期',
'pageinfo-lastuser' => '最后编辑',
'pageinfo-lasttime' => '最后编辑的日期',
'pageinfo-edits' => '总编辑次数',
'pageinfo-authors' => '不同编者总计',
'pageinfo-recent-edits' => '最近的编辑数（$1内）',
'pageinfo-recent-authors' => '最近的不同编者数',
'pageinfo-magic-words' => '魔术字（$1）',
'pageinfo-hidden-categories' => '隐藏分类（$1）',
'pageinfo-templates' => '使用的模板（$1）',

# Skin names
'skinname-standard' => '标准',
'skinname-nostalgia' => '怀旧',
'skinname-cologneblue' => '科隆香水蓝',
'skinname-simple' => '简单',
'skinname-modern' => '现代',

# Patrolling
'markaspatrolleddiff' => '标记为已巡查',
'markaspatrolledtext' => '标记此页面为已巡查',
'markedaspatrolled' => '标记为已检查',
'markedaspatrolledtext' => '[[:$1]]的已选中修订版本已被标识为已巡查。',
'rcpatroldisabled' => '最新更改检查被关闭',
'rcpatroldisabledtext' => '最新更改检查的功能目前已关闭。',
'markedaspatrollederror' => '不能标志为已检查',
'markedaspatrollederrortext' => '您需要指定某个版本才能标记为已检查。',
'markedaspatrollederror-noautopatrol' => '您无法将自己所作的更改标记为已检查。',

# Patrol log
'patrol-log-page' => '巡查日志',
'patrol-log-header' => '这是已巡查版本的日志。',
'log-show-hide-patrol' => '$1巡查纪录',

# Image deletion
'deletedrevision' => '已删除旧版本$1',
'filedeleteerror-short' => '删除文件发生错误：$1',
'filedeleteerror-long' => '删除文件时出错：

$1',
'filedelete-missing' => '文件“$1”不存在而无法删除。',
'filedelete-old-unregistered' => '所指定的文件修订“$1”在数据库中不存在。',
'filedelete-current-unregistered' => '所指定的文件“$1”在数据库中不存在。',
'filedelete-archive-read-only' => '存档目录“$1”在网页服务器中不可写。',

# Browsing diffs
'previousdiff' => '←上一编辑',
'nextdiff' => '下一编辑→',

# Media information
'mediawarning' => "'''警告'''：该类型的文件可能包含恶意代码。执行后您的系统可能会受损。",
'imagemaxsize' => '图像大小限制：<br /><u>（文件描述页）</u>',
'thumbsize' => '缩略图大小：',
'widthheightpage' => '$1×$2，$3页',
'file-info' => '文件大小：$1，MIME类型：$2',
'file-info-size' => '$1×$2像素，文件大小：$3，MIME类型：$4',
'file-info-size-pages' => '$1×$2像素，文件大小：$3，MIME类型：$4，$5页',
'file-nohires' => '没有更高的分辨率。',
'svg-long-desc' => 'SVG文件，图像大小：$1 × $2像素，文件大小：$3',
'svg-long-desc-animated' => '动画SVG文件，图像大小为$1 × $2像素，文件大小：$3',
'show-big-image' => '完全分辨率',
'show-big-image-preview' => '本预览的大小：$1。',
'show-big-image-other' => '其他{{PLURAL:$2|分辨率}}：$1。',
'show-big-image-size' => '$1×$2像素',
'file-info-gif-looped' => '循环',
'file-info-gif-frames' => '$1帧',
'file-info-png-looped' => '循环',
'file-info-png-repeat' => '已播放$1遍',
'file-info-png-frames' => '$1帧',
'file-no-thumb-animation' => "'''注意：由于技术限制，该文件的缩略图无法进行动画处理。'''",
'file-no-thumb-animation-gif' => "'''注意：由于技术限制，高分辨率GIF图像的缩略图无法进行动画处理。'''",

# Special:NewFiles
'newimages' => '新文件库',
'imagelisttext' => "以下是按$2排列的'''$1'''个文件列表。",
'newimages-summary' => '本特殊页面展示最后上传的文件。',
'newimages-legend' => '过滤',
'newimages-label' => '文件名（或它的一部份）：',
'showhidebots' => '（$1机器人）',
'noimages' => '无可查看文件。',
'ilsubmit' => '搜索',
'bydate' => '按日期',
'sp-newimages-showfrom' => '从$1 $2开始显示新文件',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '$1秒',
'minutes' => '$1分',
'hours' => '$1小时',
'days' => '$1天',
'ago' => '$1前',

# Bad image list
'bad_image_list' => '请按照下列格式编写：

只有（以 * 开头）列出的项目会被考虑。每一行的第一个链接必须是不雅文件的链接。
然后同一行后方的链接会被视为例外，即是该文件可以在哪些页面内被显示。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '简体',
'variantname-zh-hant' => '繁体',
'variantname-zh-cn' => '大陆简体',
'variantname-zh-tw' => '台湾正体',
'variantname-zh-hk' => '香港繁体',
'variantname-zh-mo' => '澳门繁体',
'variantname-zh-sg' => '新加坡简体',
'variantname-zh-my' => '马来西亚简体',
'variantname-zh' => '不转换',

# Variants for Gan language
'variantname-gan-hans' => '‪中文(简体)',
'variantname-gan-hant' => '‪中文(繁体)',

# Variants for Kazakh language
'variantname-kk-cyrl' => '',

# Metadata
'metadata' => '原始数据',
'metadata-help' => '此文件中包含有扩展的信息。这些信息可能是由数码相机或扫描仪在创建或数字化过程中所添加的。

如果此文件的源文件已经被修改，一些信息在修改后的文件中将不能完全反映出来。',
'metadata-expand' => '显示详细资料',
'metadata-collapse' => '隐藏详细资料',
'metadata-fields' => '在本信息中所列出的 EXIF 元数据域将包含在图片显示页面，当元数据表损坏时只显示以下信息。
其他的元数据默认为隐藏。
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => '宽度',
'exif-imagelength' => '高度',
'exif-bitspersample' => '每像素字节数',
'exif-compression' => '压缩方式',
'exif-photometricinterpretation' => '像素构成',
'exif-orientation' => '方位',
'exif-samplesperpixel' => '像素数',
'exif-planarconfiguration' => '数据排列',
'exif-ycbcrsubsampling' => '黄色洋红的二次采样比例',
'exif-ycbcrpositioning' => '黄色和洋红配置',
'exif-xresolution' => '水平分辨率',
'exif-yresolution' => '垂直分辨率',
'exif-stripoffsets' => '图像数据区',
'exif-rowsperstrip' => '每带行数',
'exif-stripbytecounts' => '每压缩带字节数',
'exif-jpeginterchangeformat' => 'JPEG SOI偏移',
'exif-jpeginterchangeformatlength' => 'JPEG数据字节',
'exif-whitepoint' => '白点色度',
'exif-primarychromaticities' => '主要色度',
'exif-ycbcrcoefficients' => '色彩空间转换矩阵系数',
'exif-referenceblackwhite' => '黑白参照值对',
'exif-datetime' => '文件修改日期时间',
'exif-imagedescription' => '图像标题',
'exif-make' => '相机制造商',
'exif-model' => '相机型号',
'exif-software' => '使用软件',
'exif-artist' => '作者',
'exif-copyright' => '著作权所有人',
'exif-exifversion' => 'Exif版本',
'exif-flashpixversion' => '支持的Flashpix版本',
'exif-colorspace' => '色彩空间',
'exif-componentsconfiguration' => '各部分含义',
'exif-compressedbitsperpixel' => '图像压缩模式',
'exif-pixelydimension' => '图像宽度',
'exif-pixelxdimension' => '图像高度',
'exif-usercomment' => '用户评论',
'exif-relatedsoundfile' => '相关声音文件',
'exif-datetimeoriginal' => '数据生成日期时间',
'exif-datetimedigitized' => '数字化日期时间',
'exif-subsectime' => '修改时间厘秒数',
'exif-subsectimeoriginal' => '数据生成时间厘秒数',
'exif-subsectimedigitized' => '数字化时间厘秒数',
'exif-exposuretime' => '曝光时间',
'exif-exposuretime-format' => '$1秒（$2）',
'exif-fnumber' => '光圈值',
'exif-exposureprogram' => '曝光程序',
'exif-spectralsensitivity' => '感光',
'exif-isospeedratings' => '感光度（ISO）',
'exif-shutterspeedvalue' => 'APEX快门速度',
'exif-aperturevalue' => 'APEX光圈',
'exif-brightnessvalue' => 'APEX 亮度',
'exif-exposurebiasvalue' => '曝光补偿',
'exif-maxaperturevalue' => '最大陆地光圈',
'exif-subjectdistance' => '物距',
'exif-meteringmode' => '测光模式',
'exif-lightsource' => '光源',
'exif-flash' => '闪光灯',
'exif-focallength' => '焦距',
'exif-subjectarea' => '主体区域',
'exif-flashenergy' => '闪光灯强度',
'exif-focalplanexresolution' => '焦平面X分辨率',
'exif-focalplaneyresolution' => '焦平面Y分辨率',
'exif-focalplaneresolutionunit' => '焦平面分辨率单位',
'exif-subjectlocation' => '主题位置',
'exif-exposureindex' => '曝光指数',
'exif-sensingmethod' => '感光模式',
'exif-filesource' => '文件源',
'exif-scenetype' => '场景类型',
'exif-customrendered' => '图像处理',
'exif-exposuremode' => '曝光模式',
'exif-whitebalance' => '白平衡',
'exif-digitalzoomratio' => '数字变焦比率',
'exif-focallengthin35mmfilm' => '35 mm胶片焦距',
'exif-scenecapturetype' => '场景模式',
'exif-gaincontrol' => '场景控制',
'exif-contrast' => '对比度',
'exif-saturation' => '饱和度',
'exif-sharpness' => '锐化',
'exif-devicesettingdescription' => '设备设定描述',
'exif-subjectdistancerange' => '主体距离范围',
'exif-imageuniqueid' => '唯一图像ID',
'exif-gpsversionid' => 'GPS标签版本',
'exif-gpslatituderef' => '北纬或南纬',
'exif-gpslatitude' => '纬度',
'exif-gpslongituderef' => '东经或西经',
'exif-gpslongitude' => '经度',
'exif-gpsaltituderef' => '海拔正负参照',
'exif-gpsaltitude' => '海拔',
'exif-gpstimestamp' => 'GPS时间（原子钟）',
'exif-gpssatellites' => '测量使用的卫星',
'exif-gpsstatus' => '接收器状态',
'exif-gpsmeasuremode' => '测量模式',
'exif-gpsdop' => '测量精度',
'exif-gpsspeedref' => '速度单位',
'exif-gpsspeed' => 'GPS接收器速度',
'exif-gpstrackref' => '运动方位参照',
'exif-gpstrack' => '运动方位',
'exif-gpsimgdirectionref' => '图像方位参照',
'exif-gpsimgdirection' => '图像方位',
'exif-gpsmapdatum' => '使用地理测绘数据',
'exif-gpsdestlatituderef' => '目标纬度参照',
'exif-gpsdestlatitude' => '目标纬度',
'exif-gpsdestlongituderef' => '目标经度的参照',
'exif-gpsdestlongitude' => '目标经度',
'exif-gpsdestbearingref' => '目标方位参照',
'exif-gpsdestbearing' => '目标方位',
'exif-gpsdestdistanceref' => '目标距离参照',
'exif-gpsdestdistance' => '目标距离',
'exif-gpsprocessingmethod' => 'GPS处理方法名称',
'exif-gpsareainformation' => 'GPS区域名称',
'exif-gpsdatestamp' => 'GPS日期',
'exif-gpsdifferential' => 'GPS差动修正',
'exif-jpegfilecomment' => 'JPEG 文件注释',
'exif-keywords' => '关键词',
'exif-worldregioncreated' => '照片中的世界区域',
'exif-countrycreated' => '在拍摄图片的国家',
'exif-countrycodecreated' => '在拍摄图片的国家代码',
'exif-provinceorstatecreated' => '省或拍摄图片中的状态',
'exif-citycreated' => '照片中的城市',
'exif-sublocationcreated' => '照片拍摄地点在城市中的位置',
'exif-worldregiondest' => '世界区域显示',
'exif-countrydest' => '所示的国家',
'exif-countrycodedest' => '国家代码',
'exif-provinceorstatedest' => '省或州',
'exif-citydest' => '所示的城市',
'exif-sublocationdest' => '显示城市中的详细地点',
'exif-objectname' => '简称',
'exif-specialinstructions' => '特别说明',
'exif-headline' => '标题',
'exif-credit' => '提供人',
'exif-source' => '来源',
'exif-editstatus' => '编辑状态的图像',
'exif-urgency' => '紧急性',
'exif-fixtureidentifier' => '夹具名称',
'exif-locationdest' => '位置描述',
'exif-locationdestcode' => '位置所示的代码',
'exif-objectcycle' => '媒体文件使用时间要求',
'exif-contact' => '联系信息',
'exif-writer' => '作者',
'exif-languagecode' => '语言',
'exif-iimversion' => 'IIM 版本',
'exif-iimcategory' => '类别',
'exif-iimsupplementalcategory' => '补充的类别',
'exif-datetimeexpires' => '使用截止日期',
'exif-datetimereleased' => '发表',
'exif-originaltransmissionref' => '传输位置的原代码',
'exif-identifier' => '标识符',
'exif-lens' => '使用的镜头',
'exif-serialnumber' => '相机序列号',
'exif-cameraownername' => '相机所有人',
'exif-label' => '标签',
'exif-datetimemetadata' => '原始数据最后修改日期',
'exif-nickname' => '非正式的图像的名称',
'exif-rating' => '分级（最高为5）',
'exif-rightscertificate' => '权利管理证书',
'exif-copyrighted' => '著作权状况',
'exif-copyrightowner' => '著作权所有人',
'exif-usageterms' => '使用条款',
'exif-webstatement' => '在线著作权声明',
'exif-originaldocumentid' => '原始文件唯一ID',
'exif-licenseurl' => '著作权授权协议的URL',
'exif-morepermissionsurl' => '其他授权协议信息',
'exif-attributionurl' => '二次使用本作品时，请链接至',
'exif-preferredattributionname' => '二次使用本作品时，请署名',
'exif-pngfilecomment' => 'PNG文件注释',
'exif-disclaimer' => '免责声明',
'exif-contentwarning' => '内容的警告',
'exif-giffilecomment' => 'GIF文件注释',
'exif-intellectualgenre' => '项目类型',
'exif-subjectnewscode' => '主题代码',
'exif-scenecode' => 'IPTC 现场代码',
'exif-event' => '事件描述',
'exif-organisationinimage' => '组织描述',
'exif-personinimage' => '描述的人',
'exif-originalimageheight' => '裁剪前的图像高度',
'exif-originalimagewidth' => '裁剪前的图像宽度',

# EXIF attributes
'exif-compression-1' => '未压缩',
'exif-compression-2' => 'CCITT第3组一维修改霍夫曼游程编码',
'exif-compression-3' => 'CCITT第3组传真编码',
'exif-compression-4' => 'CCITT第4组传真编码',
'exif-compression-6' => 'JPEG（旧）',

'exif-copyrighted-true' => '版权',
'exif-copyrighted-false' => '公共领域',

'exif-unknowndate' => '未知日期',

'exif-orientation-1' => '标准',
'exif-orientation-2' => '水平翻转',
'exif-orientation-3' => '旋转180°',
'exif-orientation-4' => '垂直翻转',
'exif-orientation-5' => '逆时针旋转90°并垂直翻转',
'exif-orientation-6' => '逆时针旋转90°',
'exif-orientation-7' => '顺时针旋转90°并垂直翻转',
'exif-orientation-8' => '顺时针旋转90°',

'exif-planarconfiguration-1' => '矮胖格式',
'exif-planarconfiguration-2' => '平面格式',

'exif-colorspace-65535' => '无标定',

'exif-componentsconfiguration-0' => '不存在',

'exif-exposureprogram-0' => '未定义',
'exif-exposureprogram-1' => '手动',
'exif-exposureprogram-2' => '标准程序',
'exif-exposureprogram-3' => '光圈优先模式',
'exif-exposureprogram-4' => '快门优先模式',
'exif-exposureprogram-5' => '艺术程序（景深优先）',
'exif-exposureprogram-6' => '运动程序（高快门速度优先）',
'exif-exposureprogram-7' => '肖像模式（适用于背景在焦距以外的近距摄影）',
'exif-exposureprogram-8' => '风景模式（适用于背景在焦距上的风景照片）',

'exif-subjectdistance-value' => '$1米',

'exif-meteringmode-0' => '未知',
'exif-meteringmode-1' => '平均水平',
'exif-meteringmode-2' => '中心加权平均测量',
'exif-meteringmode-3' => '点测',
'exif-meteringmode-4' => '多点测',
'exif-meteringmode-5' => '模式测量',
'exif-meteringmode-6' => '局部测量',
'exif-meteringmode-255' => '其他',

'exif-lightsource-0' => '未知',
'exif-lightsource-1' => '日光灯',
'exif-lightsource-2' => '荧光灯',
'exif-lightsource-3' => '钨丝灯（白炽灯）',
'exif-lightsource-4' => '闪光灯',
'exif-lightsource-9' => '晴天',
'exif-lightsource-10' => '多云',
'exif-lightsource-11' => '深色调阴影',
'exif-lightsource-12' => '日光荧光灯（色温 D 5700 – 7100K）',
'exif-lightsource-13' => '日温白色荧光灯（N 4600 – 5400K）',
'exif-lightsource-14' => '冷白色荧光灯（W 3900 – 4500K）',
'exif-lightsource-15' => '白色荧光 （WW 3200 – 3700K）',
'exif-lightsource-17' => '标准灯光A',
'exif-lightsource-18' => '标准灯光B',
'exif-lightsource-19' => '标准灯光C',
'exif-lightsource-24' => 'ISO摄影棚钨灯',
'exif-lightsource-255' => '其他光源',

# Flash modes
'exif-flash-fired-0' => '闪光灯未点亮',
'exif-flash-fired-1' => '闪光灯开启',
'exif-flash-return-0' => '无频闪观测器功能',
'exif-flash-return-2' => '频闪观测器未侦测到光',
'exif-flash-return-3' => '频闪观测器侦测到光',
'exif-flash-mode-1' => '闪光灯强制开启',
'exif-flash-mode-2' => '闪光灯强制关闭',
'exif-flash-mode-3' => '自动模式',
'exif-flash-function-1' => '无闪光灯功能',
'exif-flash-redeye-1' => '防红眼模式',

'exif-focalplaneresolutionunit-2' => '英寸',

'exif-sensingmethod-1' => '未定义',
'exif-sensingmethod-2' => '一块彩色区域传感器',
'exif-sensingmethod-3' => '两块彩色区域传感器',
'exif-sensingmethod-4' => '三块彩色区域传感器',
'exif-sensingmethod-5' => '连续彩色区域传感器',
'exif-sensingmethod-7' => '三线传感器',
'exif-sensingmethod-8' => '连续彩色线性传感器',

'exif-filesource-3' => '数码相机',

'exif-scenetype-1' => '直接照像图片',

'exif-customrendered-0' => '标准处理',
'exif-customrendered-1' => '自定义处理',

'exif-exposuremode-0' => '自动曝光',
'exif-exposuremode-1' => '手动曝光',
'exif-exposuremode-2' => '自动曝光感知调节',

'exif-whitebalance-0' => '自动白平衡',
'exif-whitebalance-1' => '手动白平衡',

'exif-scenecapturetype-0' => '标准',
'exif-scenecapturetype-1' => '风景',
'exif-scenecapturetype-2' => '肖像',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => '无',
'exif-gaincontrol-1' => '低增益',
'exif-gaincontrol-2' => '高增益',
'exif-gaincontrol-3' => '低减益',
'exif-gaincontrol-4' => '高减益',

'exif-contrast-0' => '标准',
'exif-contrast-1' => '低',
'exif-contrast-2' => '高',

'exif-saturation-0' => '标准',
'exif-saturation-1' => '低饱和度',
'exif-saturation-2' => '高饱和度',

'exif-sharpness-0' => '标准',
'exif-sharpness-1' => '低',
'exif-sharpness-2' => '高',

'exif-subjectdistancerange-0' => '未知',
'exif-subjectdistancerange-1' => '宏程序',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '远景',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北纬',
'exif-gpslatitude-s' => '南纬',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '东经',
'exif-gpslongitude-w' => '西经',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '海拔$1{{PLURAL:$1|米}}',
'exif-gpsaltitude-below-sealevel' => '海拔-$1{{PLURAL:$1|米}}',

'exif-gpsstatus-a' => '测量过程',
'exif-gpsstatus-v' => '互动测量',

'exif-gpsmeasuremode-2' => '二维测量',
'exif-gpsmeasuremode-3' => '三维测量',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '千米每小时',
'exif-gpsspeed-m' => '英里每小时',
'exif-gpsspeed-n' => '海里每小时（节）',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => '公里',
'exif-gpsdestdistance-m' => '英里',
'exif-gpsdestdistance-n' => '海里',

'exif-gpsdop-excellent' => '优秀（$1）',
'exif-gpsdop-good' => '好（$1）',
'exif-gpsdop-moderate' => '中度（$1）',
'exif-gpsdop-fair' => '平等（$1）',
'exif-gpsdop-poor' => '不好（$1）',

'exif-objectcycle-a' => '仅上午（AM）',
'exif-objectcycle-p' => '仅下午（PM）',
'exif-objectcycle-b' => '上午（AM）下午（PM）皆可',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真实方位',
'exif-gpsdirection-m' => '地磁方位',

'exif-ycbcrpositioning-1' => '居中',
'exif-ycbcrpositioning-2' => '色相定位',

'exif-dc-contributor' => '贡献者',
'exif-dc-coverage' => '介质的空间或时间范围',
'exif-dc-date' => '日期',
'exif-dc-publisher' => '发布者',
'exif-dc-relation' => '相关文件',
'exif-dc-rights' => '权利',
'exif-dc-source' => '原始文件',
'exif-dc-type' => '介质类型',

'exif-rating-rejected' => '拒绝',

'exif-isospeedratings-overflow' => '大于65535',

'exif-iimcategory-ace' => '艺术、 文化和娱乐',
'exif-iimcategory-clj' => '犯罪和法律',
'exif-iimcategory-dis' => '灾害和事故',
'exif-iimcategory-fin' => '经济与商业',
'exif-iimcategory-edu' => '教育',
'exif-iimcategory-evn' => '环境',
'exif-iimcategory-hth' => '健康',
'exif-iimcategory-hum' => '人类利益',
'exif-iimcategory-lab' => '劳动',
'exif-iimcategory-lif' => '生活方式和休闲',
'exif-iimcategory-pol' => '政治',
'exif-iimcategory-rel' => '宗教和信仰',
'exif-iimcategory-sci' => '科学和技术',
'exif-iimcategory-soi' => '社会问题',
'exif-iimcategory-spo' => '体育',
'exif-iimcategory-war' => '战争、 冲突和动乱',
'exif-iimcategory-wea' => '天气',

'exif-urgency-normal' => '普通（$1）',
'exif-urgency-low' => '低（$1）',
'exif-urgency-high' => '高（$1）',
'exif-urgency-other' => '用户定义的优先级（$1）',

# External editor support
'edit-externally' => '用外部应用程序编辑本文件',
'edit-externally-help' => '（更多信息请见[//www.mediawiki.org/wiki/Manual:External_editors 安装说明]）',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => '全部',
'namespacesall' => '全部',
'monthsall' => '全部',
'limitall' => '全部',

# E-mail address confirmation
'confirmemail' => '确认邮箱地址',
'confirmemail_noemail' => '你还没有在你的[[Special:Preferences|系统设置]]中设置有效的电子邮件地址。',
'confirmemail_text' => '{{SITENAME}}要求您在使用邮件功能之前验证您的邮箱地址。
点击以下按钮可向您的邮箱发送一封确认邮件。该邮件包含有一行代码链接；
请在您的浏览器中加载此链接以确认您的邮箱地址是有效的。',
'confirmemail_pending' => '一个确认码已经被发送到您的邮箱，您可能需要等几分钟才能收到。如果无法收到，请再申请一个新的确认码。',
'confirmemail_send' => '邮件发送确认代码',
'confirmemail_sent' => '确认邮件已发送。',
'confirmemail_oncreate' => '一个确认代码已被发送到您的邮箱。登录时无需使用该代码，但若要启用在此wiki上的电子邮件相关功能，则必须先提交此代码。',
'confirmemail_sendfailed' => '{{SITENAME}}不能发送确认邮件，请检查您的邮箱地址是否包含无效字符。

邮件发送器的返回信息：$1',
'confirmemail_invalid' => '无效的确认码，该代码可能已经过期。',
'confirmemail_needlogin' => '您需要$1以确认您的邮箱地址。',
'confirmemail_success' => '您的邮箱已经被确认。您现在可以[[Special:UserLogin|登录]]并使用此网站了。',
'confirmemail_loggedin' => '您的邮箱地址现在已被确认。',
'confirmemail_error' => '在确认您的过程中发生错误。',
'confirmemail_subject' => '来自{{SITENAME}}的电子邮件地址确认函',
'confirmemail_body' => '来自IP地址$1的用户（可能是您）在{{SITENAME}}上创建了账户“$2”，并提交了您
的电子邮箱地址。

请确认这个账户是属于您的，并同时激活在{{SITENAME}}上的电子邮件功能。请在浏
览器中打开下面的链接：

$3

如果您*未曾*注册账户，请打开下面的链接去取消电子邮件确认：

$5

确认码会在$4过期。',
'confirmemail_body_changed' => '拥有IP地址$1的用户（可能是您）在{{SITENAME}}更改了账户“$2”的电子邮箱地址。

请确认这个账户是属于您的，并同时重新激活在{{SITENAME}}上的电子邮件功能。请
在浏览器中打开下面的链接：

$3

如果这个账户*不是*属于您的，请打开下面的链接去取消电子邮件确认：

$5

确认码会在$4过期。',
'confirmemail_body_set' => '拥有IP地址$1的用户（可能是您）在{{SITENAME}}将账户“$2”的电子邮箱地址设置
到了这个电子邮件地址。

请确认这个账户是属于您的，并同时重新激活在{{SITENAME}}上的电子邮件功能。请
在浏览器中打开下面的链接：

$3

如果这个账户*不是*属于您的，请打开下面的链接去取消电子邮件确认：

$5

确认码会在$4过期。',
'confirmemail_invalidated' => '邮件地址确认已取消',
'invalidateemail' => '取消邮件地址确认',

# Scary transclusion
'scarytranscludedisabled' => '[跨网站的编码转换不可用]',
'scarytranscludefailed' => '[提取$1失败]',
'scarytranscludetoolong' => '[URL过长]',

# Delete conflict
'deletedwhileediting' => "'''警告'''：此页在您开始编辑之后已经被删除！",
'confirmrecreate' => "在您开始编辑这个页面后，用户[[User:$1|$1]] （[[User talk:$1|讨论]]）以下列原因删除了这个页面：
: ''$2''
请确认在您重新创建页面前三思。",
'confirmrecreate-noreason' => '用户 [[User:$1|$1]]（[[User talk:$1|talk]]） 在您开始编辑之后删除此页面。请确认您确实要重新创建此页面。',
'recreate' => '重建',

# action=purge
'confirm_purge_button' => '确定',
'confirm-purge-top' => '要清除此页面的缓存吗?',
'confirm-purge-bottom' => '清理一页将会清除快取以及强迫显示最现时之修订版本。',

# action=watch/unwatch
'confirm-watch-button' => '确定',
'confirm-watch-top' => '将此页添加到您的监视吗？',
'confirm-unwatch-button' => '确定',
'confirm-unwatch-top' => '从监视列表中删除此页吗？',

# Separators for various lists, etc.
'comma-separator' => '、',
'colon-separator' => '：',
'word-separator' => '',
'parentheses' => '（$1）',

# Multipage image navigation
'imgmultipageprev' => '← 上一页',
'imgmultipagenext' => '下一页 →',
'imgmultigo' => '提交！',
'imgmultigoto' => '到第$1页',

# Table pager
'ascending_abbrev' => '升',
'descending_abbrev' => '降',
'table_pager_next' => '下一页',
'table_pager_prev' => '上一页',
'table_pager_first' => '首页',
'table_pager_last' => '末页',
'table_pager_limit' => '每页显示$1项',
'table_pager_limit_label' => '每页项目数：',
'table_pager_limit_submit' => '提交',
'table_pager_empty' => '没有结果',

# Auto-summaries
'autosumm-blank' => '清空页面',
'autosumm-replace' => '以“$1”替换内容',
'autoredircomment' => '重定向页面至[[$1]]',
'autosumm-new' => '以“$1”为内容创建页面',

# Size units
'size-bytes' => '$1字节',

# Live preview
'livepreview-loading' => '正在载入...',
'livepreview-ready' => '正在载入... 完成！',
'livepreview-failed' => '实时预览失败！
尝试标准预览。',
'livepreview-error' => '连接失败：$1“$2”。
尝试标准预览。',

# Friendlier slave lag warnings
'lag-warn-normal' => '过去$1秒内的更改未必会在这个列表中显示。',
'lag-warn-high' => '由于数据库的过度延迟，过去$1秒的更改未必会在这个列表中显示。',

# Watchlist editor
'watchlistedit-numitems' => '不包括讨论页面，您的监视列表包含$1个标题。',
'watchlistedit-noitems' => '您的监视列表中没有标题。',
'watchlistedit-normal-title' => '编辑监视列表',
'watchlistedit-normal-legend' => '删除监视列表中的标题',
'watchlistedit-normal-explain' => '您的监视列表中的标题会显示在下方。要删除标题，请勾选它前面的选择框并点击“{{int:Watchlistedit-normal-submit}}”。您也可以[[Special:EditWatchlist/raw|编辑原始列表]]。',
'watchlistedit-normal-submit' => '删除标题',
'watchlistedit-normal-done' => '已从您的监视列表删除了$1个标题：',
'watchlistedit-raw-title' => '编辑原始监视列表',
'watchlistedit-raw-legend' => '编辑原始监视列表',
'watchlistedit-raw-explain' => '您的监视列表中的标题在下面显示，同时也可以可以通过编辑这个表去加入以及移除标题；一行一个标题。当完成以后，点击{{int:Watchlistedit-raw-submit}}。您也可以使用[[Special:EditWatchlist|标准编辑器]]。',
'watchlistedit-raw-titles' => '标题：',
'watchlistedit-raw-submit' => '更新监视列表',
'watchlistedit-raw-done' => '您的监视列表已经更新。',
'watchlistedit-raw-added' => '$1个标题被添加：',
'watchlistedit-raw-removed' => '$1个标题被删除：',

# Watchlist editing tools
'watchlisttools-view' => '查看监视更改',
'watchlisttools-edit' => '查看并编辑监视列表',
'watchlisttools-raw' => '编辑原始监视列表',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]]（[[{{ns:user_talk}}:$1|讨论]]）',

# Core parser functions
'unknown_extension_tag' => '不明的扩展标签“$1”',
'duplicate-defaultsort' => "'''警告：'''默认排序关键字“$2”覆盖了之前的默认排序关键字“$1”。",

# Special:Version
'version' => '版本',
'version-extensions' => '已安装的扩展程序',
'version-specialpages' => '特殊页面',
'version-parserhooks' => '解析器钩',
'version-variables' => '变量',
'version-antispam' => '垃圾阻止',
'version-skins' => '皮肤',
'version-other' => '其他',
'version-mediahandlers' => '媒体文件处理器',
'version-hooks' => '钩',
'version-extension-functions' => '扩展函数',
'version-parser-extensiontags' => '解析器扩展标签',
'version-parser-function-hooks' => '解析器函数钩',
'version-hook-name' => '钩名',
'version-hook-subscribedby' => '署名',
'version-version' => '（版本$1）',
'version-license' => '授权协议',
'version-poweredby-credits' => "本Wiki由'''[//www.mediawiki.org/ MediaWiki]'''驱动，版权所有 © 2001-$1 $2。",
'version-poweredby-others' => '其他',
'version-license-info' => 'MediaWiki 是一款自由软件；您可根据自由软件基金会所发表的 GNU 通用公共许可证条款规定，无论您依据的是本授权的第二版或（您自行选择的）任一日后发行的版本，将本程序再发布与／或做出修改。

MediaWiki 是基于使用目的而加以发布，然而不负任何担保责任；也没有对适售性或特定目的适用性所为的默示性担保。详情请参照 GNU 通用公共许可证条款。

您应已收到附随于本程序的[{{SERVER}}{{SCRIPTPATH}}/COPYING GNU 通用公共许可证副本]；如果没有，请发送邮件至自由软件基金会：51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA，或[//www.gnu.org/licenses/old-licenses/gpl-2.0.html 在线阅读]。',
'version-software' => '已安装的软件',
'version-software-product' => '产品',
'version-software-version' => '版本',
'version-entrypoints' => '接入点URL',
'version-entrypoints-header-entrypoint' => '接入点',
'version-entrypoints-header-url' => 'URL',
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath 条目路径]',
'version-entrypoints-scriptpath' => '[https://www.mediawiki.org/wiki/Manual:$wgScriptPath 脚本路径]',

# Special:FilePath
'filepath' => '文件路径',
'filepath-page' => '文件名：',
'filepath-submit' => '提交',
'filepath-summary' => '此特殊页面返回文件的完整路径。图像会以完整的分辨率显示，其它的文件类型也将直接通过关联的应用程序打开。',

# Special:FileDuplicateSearch
'fileduplicatesearch' => '搜索重复文件',
'fileduplicatesearch-summary' => '根据哈希（hash）值搜索重复文件。',
'fileduplicatesearch-legend' => '搜索重复文件',
'fileduplicatesearch-filename' => '文件名：',
'fileduplicatesearch-submit' => '搜索',
'fileduplicatesearch-info' => '$1×$2像素<br />文件大小：$3<br />MIME类型：$4',
'fileduplicatesearch-result-1' => '文件“$1”没有完全相同的重复副本。',
'fileduplicatesearch-result-n' => '文件“$1”有$2项完全相同的重复副本。',
'fileduplicatesearch-noresults' => '没有文件命名为"$1"发现。',

# Special:SpecialPages
'specialpages' => '特殊页面',
'specialpages-note' => '----
*普通特殊页面。
*<span class="mw-specialpagerestricted">非公开特殊页面。</span>',
'specialpages-group-maintenance' => '维护报告',
'specialpages-group-other' => '其它特殊页面',
'specialpages-group-login' => '登录/创建账户',
'specialpages-group-changes' => '最近更改与日志',
'specialpages-group-media' => '媒体文件报告与上传',
'specialpages-group-users' => '用户与权限',
'specialpages-group-highuse' => '高度使用页面',
'specialpages-group-pages' => '页面列表',
'specialpages-group-pagetools' => '页面工具',
'specialpages-group-wiki' => 'Wiki数据与工具',
'specialpages-group-redirects' => '重定向特殊页面',
'specialpages-group-spam' => '反垃圾链接工具',

# Special:BlankPage
'blankpage' => '空白页面',
'intentionallyblankpage' => '这个页面被故意留为空白',

# External image whitelist
'external_image_whitelist' => ' #请原样保留本行文字<pre>
#在下方书写正则表达式片段（//中间的部份）
#这些规则将与外部（盗链）图像的URL匹配
#匹配的URL将被显示为图像，否则只会显示链向图像的链接
#以#开头的行视为评论
#不区分大小写

#在本行上面输入所有正则表达式。请原样保留本行文字</pre>',

# Special:Tags
'tags' => '有效的更改标签',
'tag-filter' => '[[Special:Tags|标签]]过滤器：',
'tag-filter-submit' => '过滤器',
'tags-title' => '标签',
'tags-intro' => '本页面列出了软件可能用于标记编辑的标签和它们的含义。',
'tags-tag' => '标签名称',
'tags-display-header' => '更改列表中的表现形式',
'tags-description-header' => '完整含义说明',
'tags-hitcount-header' => '标记的更改数',
'tags-edit' => '编辑',
'tags-hitcount' => '$1个更改',

# Special:ComparePages
'comparepages' => '对比页面',
'compare-selector' => '对比页面版本',
'compare-page1' => '页面1',
'compare-page2' => '页面2',
'compare-rev1' => '版本1',
'compare-rev2' => '版本2',
'compare-submit' => '对比',
'compare-invalid-title' => '您指定的标题无效。',
'compare-title-not-exists' => '您指定的标题不存在。',
'compare-revision-not-exists' => '您指定的修订版本不存在。',

# Database error messages
'dberr-header' => '本wiki出现了问题',
'dberr-problems' => '抱歉！
本网站出现了一些技术问题。',
'dberr-again' => '请等待几分钟后重试。',
'dberr-info' => '（无法连接到数据库服务器：$1）',
'dberr-usegoogle' => '在此期间您可以尝试用 Google 来搜索。',
'dberr-outofdate' => '须注意他们索引出来的内容可能不是最新的。',
'dberr-cachederror' => '这是所请求页面的缓存副本，可能不是最新的。',

# HTML forms
'htmlform-invalid-input' => '您输入的内容存在问题',
'htmlform-select-badoption' => '您指定的值不是有效选项。',
'htmlform-int-invalid' => '您指定的值不是整数。',
'htmlform-float-invalid' => '您指定的值不是数字。',
'htmlform-int-toolow' => '您指定的值小于最小值$1',
'htmlform-int-toohigh' => '您指定的值大于最大值$1',
'htmlform-required' => '本值必填',
'htmlform-submit' => '提交',
'htmlform-reset' => '撤销更改',
'htmlform-selectorother-other' => '其他',

# SQLite database support
'sqlite-has-fts' => '带全文搜索的版本$1',
'sqlite-no-fts' => '不带全文搜索的版本$1',

# New logging system
'logentry-delete-delete' => '$1删除页面$3',
'logentry-delete-restore' => '$1恢复页面$3',
'logentry-delete-event' => '$1已更改$3中$5项日志的可见性：$4',
'logentry-delete-revision' => '$1已更改$3中{{PLURAL:$5|$5个历史版本|$5个历史版本}}的可见性：$4',
'logentry-delete-event-legacy' => '$1已更改$3中日志的可见性',
'logentry-delete-revision-legacy' => '$1已更改$3中历史版本的可见性',
'logentry-suppress-delete' => '$1已隐藏页面$3',
'logentry-suppress-event' => '$1已不可见地更改$3中{{PLURAL:$5|$5项日志|$5项日志}}的可见性：$4',
'logentry-suppress-revision' => '$1已不可见地更改$3中{{PLURAL:$5|$5个历史版本|$5个历史版本}}的可见性：$4',
'logentry-suppress-event-legacy' => '$1已不可见地更改$3中日志的可见性',
'logentry-suppress-revision-legacy' => '$1已不可见地更改$3中历史版本的可见性',
'revdelete-content-hid' => '隐藏内容',
'revdelete-summary-hid' => '隐藏编辑摘要',
'revdelete-uname-hid' => '隐藏用户名',
'revdelete-content-unhid' => '恢复内容',
'revdelete-summary-unhid' => '恢复编辑摘要',
'revdelete-uname-unhid' => '恢复用户名',
'revdelete-restricted' => '已将限制应用到管理员',
'revdelete-unrestricted' => '已移除对管理员的限制',
'logentry-move-move' => '$1移动$3页面至$4',
'logentry-move-move-noredirect' => '$1移动$3页面至$4，不留重定向',
'logentry-move-move_redir' => '$1移动页面$3至$4覆盖重定向',
'logentry-move-move_redir-noredirect' => '$1通过重定向移动$3页面至$4，不留重定向',
'logentry-patrol-patrol' => '$1标记页面$3的版本$4为已巡查',
'logentry-patrol-patrol-auto' => '$1自动标记页面$3的版本$4为已巡查',
'logentry-newusers-newusers' => '已创建用户帐户 $1',
'logentry-newusers-create' => '创建用户帐户$1',
'logentry-newusers-create2' => '创建用户帐户 $3 由 $1',
'logentry-newusers-autocreate' => '账户$1被自动创建',
'newuserlog-byemail' => '密码已用电子邮件发送',

# Feedback
'feedback-bugornote' => '如果您准备好详细描述一个技术问题，请[$1 报告bug]。或者您也可以使用下面的简单表格。您的评论将被添加至页面“[$3 $2]”，附有您的用户名和所使用的浏览器。',
'feedback-subject' => '主题：',
'feedback-message' => '信息：',
'feedback-cancel' => '取消',
'feedback-submit' => '提交反馈',
'feedback-adding' => '正在添加反馈至页面...',
'feedback-error1' => '错误：从API返回无法识别的结果',
'feedback-error2' => '错误：编辑失败',
'feedback-error3' => '错误：API没有响应',
'feedback-thanks' => '谢谢！您的反馈已发布至页面“[$2 $1]”。',
'feedback-close' => '完成',
'feedback-bugcheck' => '请检查本bug是否为[$1 已知bug]。',
'feedback-bugnew' => '我检查了。报告新bug',

# Search suggestions
'searchsuggest-search' => '搜索',
'searchsuggest-containing' => '含有...',

# API errors
'api-error-badaccess-groups' => '您没有将文件上传到此 wiki 的权限。',
'api-error-badtoken' => '内部错误：会话无效。',
'api-error-copyuploaddisabled' => '通过URL上传的功能已被此服务器禁用。',
'api-error-duplicate' => '在网站上已经具有相同内容的{{PLURAL:$1|[$2 另一个文件]|[$2 另一些文件]}}。',
'api-error-duplicate-archive' => '在网站上曾经具有相同内容的{{PLURAL:$1|[$2 另一个文件]|[$2 另一些文件]}}，但已被删除。',
'api-error-duplicate-archive-popup-title' => '已被删的除重复{{PLURAL:$1|文件|文件}}',
'api-error-duplicate-popup-title' => '重复的 {{PLURAL:$1|文件|文件}}',
'api-error-empty-file' => '您提交的文件是空的。',
'api-error-emptypage' => '不能创建没有内容的新页面。',
'api-error-fetchfileerror' => '内部错误：获取文件时发生错误。',
'api-error-fileexists-forbidden' => '名为$1的文件已经存在而且无法覆盖。',
'api-error-fileexists-shared-forbidden' => '名为$1的文件已经存在于共享媒体库中而且无法覆盖。',
'api-error-file-too-large' => '您提交的文件过大。',
'api-error-filename-tooshort' => '文件名过短。',
'api-error-filetype-banned' => '此类文件被禁止。',
'api-error-filetype-banned-type' => '$1{{PLURAL:$4|不是允许的文件类型}}。允许的{{PLURAL:$3|文件类型是|文件类型有}}$2。',
'api-error-filetype-missing' => '该文件没有扩展名。',
'api-error-hookaborted' => '您试图进行的修改被一个扩展钩子终止。',
'api-error-http' => '内部错误：无法连接到服务器。',
'api-error-illegal-filename' => '文件名非法。',
'api-error-internal-error' => '内部错误：此wiki在处理您的上传数据时出现了错误。',
'api-error-invalid-file-key' => '内部错误：找不到临时文件。',
'api-error-missingparam' => '内部错误：请求中缺少参数。',
'api-error-missingresult' => '内部错误：无法确定是否复制成功。',
'api-error-mustbeloggedin' => '您必须登录后再上传文件。',
'api-error-mustbeposted' => '内部错误：请求需要HTTP POST',
'api-error-noimageinfo' => '上传成功，但服务器没有给我们任何该文件的信息。',
'api-error-nomodule' => '内部错误：缺少上传模块集。',
'api-error-ok-but-empty' => '内部错误：服务器没有响应。',
'api-error-overwrite' => '不允许覆盖现有文件。',
'api-error-stashfailed' => '内部错误：服务器保存临时文件失败。',
'api-error-timeout' => '服务器没有在预期内响应。',
'api-error-unclassified' => '出现未知错误。',
'api-error-unknown-code' => '未知错误：$1',
'api-error-unknown-error' => '内部错误：尝试上传文件时出错。',
'api-error-unknown-warning' => '未知的警告：$1',
'api-error-unknownerror' => '未知错误：$1。',
'api-error-uploaddisabled' => '该 wiki 禁用上传功能。',
'api-error-verification-error' => '该文件可能损坏或扩展名错误。',

# Durations
'duration-seconds' => '$1秒',
'duration-minutes' => '$1分',
'duration-hours' => '$1小时',
'duration-days' => '$1天',
'duration-weeks' => '$1周',
'duration-years' => '$1年',
'duration-decades' => '$10年',
'duration-centuries' => '$1个世纪',
'duration-millennia' => '$1千年',

);
