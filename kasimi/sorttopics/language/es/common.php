<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'SORTTOPICS_CREATED_TIME' => 'Fecha de creación',
));
