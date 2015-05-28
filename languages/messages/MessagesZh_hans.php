<?php
/** Simplified Chinese (中文（简体）‎)
 *
 * To improve a translation please visit https://translatewiki.net
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
 * @author Byfserag
 * @author Chenxiaoqino
 * @author Chenzw
 * @author Chinalace
 * @author Cicku
 * @author Cwek
 * @author Dimension
 * @author Dingyuang
 * @author Fantasticfears
 * @author Fengchao
 * @author Franklsf95
 * @author Gakmo
 * @author Gaoxuewei
 * @author GeneralNFS
 * @author Gzdavidwong
 * @author Happy
 * @author Hercule
 * @author Horacewai2
 * @author Hydra
 * @author Hzy980512
 * @author Jding2010
 * @author Jetlag
 * @author Jidanni
 * @author Jienus
 * @author Jimmy xu wrk
 * @author Kaganer
 * @author KaiesTse
 * @author Kuailong
 * @author Li3939108
 * @author Liangent
 * @author Linforest
 * @author Liuxinyu970226
 * @author M13253
 * @author Makecat
 * @author Mark85296341
 * @author MarkAHershberger
 * @author Mys 721tx
 * @author Nemo bis
 * @author O
 * @author Onecountry
 * @author PhiLiP
 * @author Qiyue2001
 * @author Shinjiman
 * @author Shirayuki
 * @author Shizhao
 * @author Simon Shek
 * @author Slboat
 * @author StephDC
 * @author Stevenliuyi
 * @author Supaiku
 * @author Tommyang
 * @author User670839245
 * @author Waihorace
 * @author Wilsonmess
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author Zoglun
 * @author 乌拉跨氪
 * @author 御坂美琴
 * @author 燃玉
 * @author 范
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
	'AllMyUploads'              => array( '我上传的所有文件', '我的所有文件' ),
	'Allpages'                  => array( '所有页面' ),
	'ApiHelp'                   => array( 'Api帮助' ),
	'Ancientpages'              => array( '最老页面' ),
	'Badtitle'                  => array( '错误标题', '无效标题' ),
	'Blankpage'                 => array( '空白页面' ),
	'Block'                     => array( '封禁', '封禁IP', '封禁用户', '封' ),
	'Booksources'               => array( '网络书源' ),
	'BrokenRedirects'           => array( '受损重定向' ),
	'Categories'                => array( '页面分类' ),
	'ChangeEmail'               => array( '修改邮箱地址' ),
	'ChangePassword'            => array( '修改密码', '重置密码', '找回密码' ),
	'ComparePages'              => array( '对比页面' ),
	'Confirmemail'              => array( '确认电子邮件' ),
	'Contributions'             => array( '用户贡献', '贡献' ),
	'CreateAccount'             => array( '创建账户' ),
	'Deadendpages'              => array( '断链页面' ),
	'DeletedContributions'      => array( '已删除的用户贡献' ),
	'Diff'                      => array( '编辑差异' ),
	'DoubleRedirects'           => array( '双重重定向', '两次重定向' ),
	'EditWatchlist'             => array( '编辑监视列表' ),
	'Emailuser'                 => array( '电邮联系' ),
	'ExpandTemplates'           => array( '展开模板' ),
	'Export'                    => array( '导出页面', '导出' ),
	'Fewestrevisions'           => array( '版本最少页面', '最少修订页面' ),
	'FileDuplicateSearch'       => array( '搜索重复文件' ),
	'Filepath'                  => array( '文件路径' ),
	'Import'                    => array( '导入页面', '导入' ),
	'Invalidateemail'           => array( '无效电邮地址' ),
	'JavaScriptTest'            => array( 'JavaScript测试' ),
	'BlockList'                 => array( '封禁列表', 'IP封禁列表' ),
	'LinkSearch'                => array( '搜索网页链接' ),
	'Listadmins'                => array( '管理员列表' ),
	'Listbots'                  => array( '机器人列表' ),
	'Listfiles'                 => array( '文件列表', '图像列表' ),
	'Listgrouprights'           => array( '用户组权限' ),
	'Listredirects'             => array( '重定向页列表' ),
	'ListDuplicatedFiles'       => array( '重复文件列表' ),
	'Listusers'                 => array( '用户列表' ),
	'Lockdb'                    => array( '锁定数据库' ),
	'Log'                       => array( '日志' ),
	'Lonelypages'               => array( '孤立页面' ),
	'Longpages'                 => array( '长页面' ),
	'MediaStatistics'           => array( '媒体统计' ),
	'MergeHistory'              => array( '合并历史' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'Mostcategories'            => array( '最多分类页面' ),
	'Mostimages'                => array( '最多链接文件' ),
	'Mostinterwikis'            => array( '最多跨wiki链接页面' ),
	'Mostlinked'                => array( '最多链接页面' ),
	'Mostlinkedcategories'      => array( '最多链接分类' ),
	'Mostlinkedtemplates'       => array( '最多嵌入页面', '最多链接模板', '最多使用模板' ),
	'Mostrevisions'             => array( '最多修订页面' ),
	'Movepage'                  => array( '移动页面' ),
	'Mycontributions'           => array( '我的贡献' ),
	'MyLanguage'                => array( '我的语言' ),
	'Mypage'                    => array( '我的用户页' ),
	'Mytalk'                    => array( '我的讨论页', '我的对话页' ),
	'Myuploads'                 => array( '我上传的文件', '我的文件' ),
	'Newimages'                 => array( '新建文件', '新建图像' ),
	'Newpages'                  => array( '新建页面' ),
	'PagesWithProp'             => array( '带属性的页面', '基于属性的页面' ),
	'PageLanguage'              => array( '页面语言' ),
	'PasswordReset'             => array( '重设密码' ),
	'PermanentLink'             => array( '固定链接', '永久链接' ),
	'Preferences'               => array( '参数设置', '设置' ),
	'Prefixindex'               => array( '前缀索引' ),
	'Protectedpages'            => array( '已保护页面' ),
	'Protectedtitles'           => array( '已保护标题' ),
	'Randompage'                => array( '随机', '随机页面' ),
	'RandomInCategory'          => array( '分类内随机' ),
	'Randomredirect'            => array( '随机重定向', '随机重定向页' ),
	'Recentchanges'             => array( '最近更改' ),
	'Recentchangeslinked'       => array( '最近链出更改', '相关更改' ),
	'Redirect'                  => array( '重定向' ),
	'ResetTokens'               => array( '重置权标' ),
	'Revisiondelete'            => array( '删除修订', '恢复修订' ),
	'RunJobs'                   => array( '运行工作' ),
	'Search'                    => array( '搜索' ),
	'Shortpages'                => array( '短页面' ),
	'Specialpages'              => array( '特殊页面' ),
	'Statistics'                => array( '统计信息' ),
	'Tags'                      => array( '标签' ),
	'TrackingCategories'        => array( '追踪分类' ),
	'Unblock'                   => array( '解除封禁', '解封' ),
	'Uncategorizedcategories'   => array( '未分类分类' ),
	'Uncategorizedimages'       => array( '未分类文件', '未分类图像' ),
	'Uncategorizedpages'        => array( '未分类页面' ),
	'Uncategorizedtemplates'    => array( '未分类模板' ),
	'Undelete'                  => array( '恢复被删页面' ),
	'Unlockdb'                  => array( '解除数据库锁定' ),
	'Unusedcategories'          => array( '未使用分类' ),
	'Unusedimages'              => array( '未使用文件', '未使用图像' ),
	'Unusedtemplates'           => array( '未使用模板' ),
	'Unwatchedpages'            => array( '未受监视页面' ),
	'Upload'                    => array( '上传文件' ),
	'UploadStash'               => array( '上传藏匿' ),
	'Userlogin'                 => array( '用户登录', '登录' ),
	'Userlogout'                => array( '用户退出', '退出' ),
	'Userrights'                => array( '用户权限' ),
	'Version'                   => array( '版本', '版本信息' ),
	'Wantedcategories'          => array( '需要的分类', '待撰分类' ),
	'Wantedfiles'               => array( '需要的文件' ),
	'Wantedpages'               => array( '需要的页面', '待撰页面', '受损链接' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Watchlist'                 => array( '监视列表' ),
	'Whatlinkshere'             => array( '链入页面' ),
	'Withoutinterwiki'          => array( '无跨wiki', '无跨wiki链接页面' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#重定向', '#REDIRECT' ),
	'notoc'                     => array( '0', '__无目录__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__无图库__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__强显目录__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__目录__', '__TOC__' ),
	'noeditsection'             => array( '0', '__无编辑段落__', '__无段落编辑__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', '本月', '本月2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', '本月1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', '本月名', '本月名称', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', '本月名属格', '本月名称属格', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', '本月简称', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', '今天', 'CURRENTDAY' ),
	'currentday2'               => array( '1', '今天2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', '星期', '今天名', '今天名称', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', '今年', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', '当前时间', '此时', 'CURRENTTIME' ),
	'currenthour'               => array( '1', '当前小时', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', '本地月', '本地月2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', '本地月1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', '本地月份名', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', '本地月历', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', '本地月缩写', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', '本地日', 'LOCALDAY' ),
	'localday2'                 => array( '1', '本地日2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', '本地日名', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', '本地年', 'LOCALYEAR' ),
	'localtime'                 => array( '1', '本地时间', 'LOCALTIME' ),
	'localhour'                 => array( '1', '本地小时', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', '页面数', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', '条目数', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', '文件数', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', '用户数', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', '活跃用户数', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', '编辑数', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', '页名', '页面名', '页面名称', 'PAGENAME' ),
	'pagenamee'                 => array( '1', '页面名等同', '页面名称等同', 'PAGENAMEE' ),
	'namespace'                 => array( '1', '名字空间', 'NAMESPACE' ),
	'namespacee'                => array( '1', '名字空间等同', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', '名字空间编号', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', '讨论空间', '讨论名字空间', 'TALKSPACE' ),
	'talkspacee'                => array( '1', '讨论空间等同', '讨论名字空间等同', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', '主名字空间', '条目名字空间', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', '主名字空间等同', '条目名字空间等同', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', '页面全称', '完整页面名称', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', '完整页面名称等同', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', '子页面名称', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', '子页面名称等同', 'SUBPAGENAMEE' ),
	'rootpagename'              => array( '1', '根页面名称', 'ROOTPAGENAME' ),
	'rootpagenamee'             => array( '1', '根页面名称等同', 'ROOTPAGENAMEE' ),
	'basepagename'              => array( '1', '基础页面名称', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', '基础页面名称等同', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', '讨论页面名称', '对话页面名称', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', '讨论页面名称等同', '对话页面名称等同', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', '主名字空间页面名称', '条目页面名称', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', '主名字空间页面名称等同', '条目页面名称等同', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'subst'                     => array( '0', '替代:', 'SUBST:' ),
	'safesubst'                 => array( '0', '安全替代:', 'SAFESUBST:' ),
	'img_thumbnail'             => array( '1', '缩略图', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', '缩略图=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', '右', 'right' ),
	'img_left'                  => array( '1', '左', 'left' ),
	'img_none'                  => array( '1', '无', 'none' ),
	'img_width'                 => array( '1', '$1像素', '$1px' ),
	'img_center'                => array( '1', '居中', 'center', 'centre' ),
	'img_framed'                => array( '1', '有框', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', '无框', 'frameless' ),
	'img_lang'                  => array( '1', '语言=$1', 'lang=$1' ),
	'img_page'                  => array( '1', '页数=$1', '$1页', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', '右上', '右上=$1', '右上$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', '边框', 'border' ),
	'img_baseline'              => array( '1', '基线', 'baseline' ),
	'img_sub'                   => array( '1', '子', 'sub' ),
	'img_super'                 => array( '1', '超', 'super', 'sup' ),
	'img_top'                   => array( '1', '顶部', 'top' ),
	'img_text_top'              => array( '1', '文字顶部', 'text-top' ),
	'img_middle'                => array( '1', '中间', 'middle' ),
	'img_bottom'                => array( '1', '底部', 'bottom' ),
	'img_text_bottom'           => array( '1', '文字底部', 'text-bottom' ),
	'img_link'                  => array( '1', '链接=$1', 'link=$1' ),
	'img_alt'                   => array( '1', '替代=$1', '替代文本=$1', 'alt=$1' ),
	'img_class'                 => array( '1', '类=$1', 'class=$1' ),
	'int'                       => array( '0', '界面:', 'INT:' ),
	'sitename'                  => array( '1', '站点名称', 'SITENAME' ),
	'ns'                        => array( '0', '名称空间:', 'NS:' ),
	'nse'                       => array( '0', '名称空间E:', 'NSE:' ),
	'localurl'                  => array( '0', '本地URL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', '本地URLE:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', '条目路径', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', '页面ID', 'PAGEID' ),
	'server'                    => array( '0', '服务器', 'SERVER' ),
	'servername'                => array( '0', '服务器名', 'SERVERNAME' ),
	'scriptpath'                => array( '0', '脚本路径', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', '样式路径', 'STYLEPATH' ),
	'grammar'                   => array( '0', '语法:', 'GRAMMAR:' ),
	'gender'                    => array( '0', '性别:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__不转换标题__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__不转换内容__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', '本周', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', '当前DOW', 'CURRENTDOW' ),
	'localweek'                 => array( '1', '本地周', 'LOCALWEEK' ),
	'localdow'                  => array( '1', '本地DOW', 'LOCALDOW' ),
	'revisionid'                => array( '1', '修订ID', 'REVISIONID' ),
	'revisionday'               => array( '1', '修订日', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', '修订日2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', '修订月', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', '修订月1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', '修订年', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', '修订时间戳', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', '修订用户', 'REVISIONUSER' ),
	'revisionsize'              => array( '1', '修订大小', 'REVISIONSIZE' ),
	'plural'                    => array( '0', '复数:', 'PLURAL:' ),
	'fullurl'                   => array( '0', '完整URL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', '完整URL等同:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', '规范URL:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', '规范URL等同:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', '小写首字:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', '大写首字:', 'UCFIRST:' ),
	'lc'                        => array( '0', '小写:', 'LC:' ),
	'uc'                        => array( '0', '大写:', 'UC:' ),
	'raw'                       => array( '0', '原始:', 'RAW:' ),
	'displaytitle'              => array( '1', '显示标题', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__新段落链接__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__无新段落链接__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', '当前版本', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URL编码:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', '锚编码', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', '当前时间戳', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', '本地时间戳', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', '方向标记', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#语言:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', '内容语言', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', '名字空间中页面数:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', '管理员数', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', '格式化数字', 'FORMATNUM' ),
	'padleft'                   => array( '0', '左填充', 'PADLEFT' ),
	'padright'                  => array( '0', '右填充', 'PADRIGHT' ),
	'special'                   => array( '0', '特殊', 'special' ),
	'speciale'                  => array( '0', '特殊等同', 'speciale' ),
	'defaultsort'               => array( '1', '默认排序:', '默认排序关键字:', '默认分类排序:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', '文件路径:', 'FILEPATH:' ),
	'tag'                       => array( '0', '标记', 'tag' ),
	'hiddencat'                 => array( '1', '__隐藏分类__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', '分类中页面数', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', '页面大小', 'PAGESIZE' ),
	'index'                     => array( '1', '__索引__', '__INDEX__' ),
	'noindex'                   => array( '1', '__无索引__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', '组中用户数', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__静态重定向__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', '保护级别', 'PROTECTIONLEVEL' ),
	'cascadingsources'          => array( '1', '级联来源', 'CASCADINGSOURCES' ),
	'formatdate'                => array( '0', '格式化日期', '日期格式化', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', '路径', 'PATH' ),
	'url_query'                 => array( '0', '查询', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', '不报错', 'noerror' ),
	'defaultsort_noreplace'     => array( '0', '不替换', 'noreplace' ),
	'displaytitle_noerror'      => array( '0', '无错误', 'noerror' ),
	'displaytitle_noreplace'    => array( '0', '无代替', 'noreplace' ),
	'pagesincategory_all'       => array( '0', '所有', 'all' ),
	'pagesincategory_pages'     => array( '0', '页面', 'pages' ),
	'pagesincategory_subcats'   => array( '0', '子分类', 'subcats' ),
	'pagesincategory_files'     => array( '0', '文件', 'files' ),
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
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亚马逊' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'卓越亚马逊' => 'http://www.amazon.cn/mn/advancedSearchApp?isbn=$1',
	'当当网' => 'http://search.dangdang.com/search.aspx?key=$1',
	'博客来书店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民书店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下书店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新丝路书店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

