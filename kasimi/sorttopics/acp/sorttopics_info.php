<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\acp;

class sorttopics_info
{
	function module()
	{
		return [
			'filename'	=> '\kasimi\sorttopics\acp\sorttopics_module',
			'title'		=> 'SORTTOPICS_TITLE',
			'modes'		=> [
				'settings' => [
					'title'	=> 'SORTTOPICS_CONFIG',
					'auth'	=> 'ext_kasimi/sorttopics && acl_a_board',
					'cat'	=> ['SORTTOPICS_TITLE'],
				],
			],
		];
	}
}
