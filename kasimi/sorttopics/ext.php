<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics;

class ext extends \phpbb\extension\base
{
	/**
	 * @return bool
	 */
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.1', '>=');
	}
}
