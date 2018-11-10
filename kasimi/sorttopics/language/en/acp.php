<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, [
	'SORTTOPICS_UCP_ENABLED'				=> 'Allow users to globally sort topics by created time',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Give users the option in the UCP to sort topics by created time in all forums.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Sort topics by',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'A value other than “User default” forces the topics in this forum to be initially sorted by the specified key, disregarding the user’s sorting preferences in the UCP. The user is still able to temporarily change the sorting at the bottom of each viewforum page.',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Sort topics order',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'This option is only in effect if the above option is set to a value other than “User default”.',
	'SORTTOPICS_APPLY_TO_SUBFORUMS'			=> 'Apply this forum’s topic sorting to all sub-forums',
	'SORTTOPICS_APPLY_TO_SUBFORUMS_EXPLAIN'	=> 'If set to “Yes”, the above sorting preferences are applied to this forum and all sub-forums (and their sub-forums).',
	'SORTTOPICS_USER_DEFAULT'				=> 'User default',
]);
