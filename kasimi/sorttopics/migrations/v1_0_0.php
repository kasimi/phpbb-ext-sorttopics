<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\migrations;

class v1_0_0 extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
					'sort_topics_by_created_time'	=> array('TINT:1', 0),
				),
				$this->table_prefix . 'forums' => array(
					'sort_topics_by'				=> array('CHAR:1', 'x'),
					'sort_topics_order'				=> array('CHAR:1', 'd'),
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add config entries
			array('config.add', array('kasimi.sorttopics.version', '1.0.0')),
			array('config.add', array('kasimi.sorttopics.ucp_enabled', 0)),

			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'SORTTOPICS_TITLE'
			)),

			array('module.add', array(
				'acp',
				'SORTTOPICS_TITLE',
				array(
					'module_basename'	=> '\kasimi\sorttopics\acp\sorttopics_module',
					'auth'				=> 'ext_kasimi/sorttopics && acl_a_board',
					'modes'				=> array('settings'),
				),
			)),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'users'	=> array('sort_topics_by_created_time'),
				$this->table_prefix . 'forums'	=> array('sort_topics_by'),
				$this->table_prefix . 'forums'	=> array('sort_topics_order'),
			),
		);
	}
}
