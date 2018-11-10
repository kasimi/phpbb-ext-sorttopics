<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\event;

use kasimi\sorttopics\core\sort_core;
use phpbb\config\config;
use phpbb\event\data;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ucp_listener extends sort_core implements EventSubscriberInterface
{
	/** @var user */
	protected $user;

	/** @var config */
	protected $config;

	/** @var bool */
	protected $ucp_sortby_created_time;

	/**
	 * @param user		$user
	 * @param config	$config
	 */
	public function __construct(
		user $user,
		config $config
	)
	{
		$this->user		= $user;
		$this->config	= $config;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.ucp_prefs_modify_common'			=> 'ucp_prefs_modify_common',
			'core.ucp_prefs_view_data'				=> 'ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_view_update_data',
		];
	}

	/**
	 * Adds the 'Created time' <option> to the template
	 *
	 * @param data $event
	 */
	public function ucp_prefs_modify_common(data $event)
	{
		if ($this->config['kasimi.sorttopics.ucp_enabled'] && $event['mode'] == 'view')
		{
			$sort_key = $this->user->data['sort_topics_by_created_time'] ? 'c' : $this->user->data['user_topic_sortby_type'];
			$this->inject_created_time_select_option('S_TOPIC_SORT_KEY', $sort_key);
		}
	}

	/**
	 * Sanitize user input
	 *
	 * @param data $event
	 */
	public function ucp_prefs_view_data(data $event)
	{
		if ($this->config['kasimi.sorttopics.ucp_enabled'] && $event['submit'])
		{
			// If the user submitted 'c' we need to reset it to a known value
			// so that data validation in ucp_prefs.php doesn't fail
			$this->ucp_sortby_created_time = $event['data']['topic_sk'] == 'c';
			if ($this->ucp_sortby_created_time)
			{
				$data = $event['data'];
				$data['topic_sk'] = 't';
				$event['data'] = $data;
			}
		}
	}

	/**
	 * Store user input
	 *
	 * @param data $event
	 */
	public function ucp_prefs_view_update_data(data $event)
	{
		if ($this->ucp_sortby_created_time !== null)
		{
			$sql_ary = $event['sql_ary'];
			$sql_ary['sort_topics_by_created_time'] = $this->ucp_sortby_created_time;
			$event['sql_ary'] = $sql_ary;
		}
	}
}
