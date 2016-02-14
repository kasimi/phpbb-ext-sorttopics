<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\acp;

class sorttopics_info
{
	function module()
	{
		return array(
			'filename'	=> '\kasimi\sorttopics\acp\sorttopics_module',
			'title'		=> 'SORTTOPICS_TITLE',
			'modes'		=> array(
				'settings' => array(
					'title'	=> 'SORTTOPICS_CONFIG',
					'auth'	=> 'ext_kasimi/sorttopics && acl_a_board',
					'cat'	=> array('SORTTOPICS_TITLE'),
				),
			),
		);
	}
}
