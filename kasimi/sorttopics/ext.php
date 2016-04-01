<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi/sorttopics;

class ext extends \phpbb\extension\base
{
	/**
	 * Requires phpBB 3.1.4 due to required event data in core.viewforum_get_topic_data
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return phpbb_version_compare($config['version'], '3.1.4', '>=');
	}
}
