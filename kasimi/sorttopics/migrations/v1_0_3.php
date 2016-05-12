<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\migrations;

class v1_0_3 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\kasimi\sorttopics\migrations\v1_0_2');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('kasimi.sorttopics.version', '1.0.3')),
		);
	}
}
