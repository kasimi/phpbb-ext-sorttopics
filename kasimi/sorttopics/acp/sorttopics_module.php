<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\acp;

class sorttopics_module extends base
{
	protected function get_controller_service_id(): string
	{
		return 'kasimi.sorttopics.acp_controller';
	}
}
