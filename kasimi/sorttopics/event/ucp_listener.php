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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ucp_listener extends sort_core implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/**
 	 * Constructor
	 *
	 * @param \phpbb\config\config	$config
	 */
	public function __construct(
		\phpbb\config\config		$config
	)
	{
		$this->config				= $config;
	}

	/**
	 * Register hooks
	 *
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.ucp_prefs_modify_common'			=> 'ucp_prefs_modify_common',
			'core.ucp_prefs_view_data'				=> 'ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_view_update_data',
		);
	}

	/**
	 * Adds the 'Created time' <option> to the template
	 *
	 * @param $event
	 */
	public function ucp_prefs_modify_common($event)
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
	 * @param $event
	 */
	public function ucp_prefs_view_data($event)
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
	 * @param $event
	 */
	public function ucp_prefs_view_update_data($event)
	{
		if ($this->ucp_sortby_created_time !== null)
		{
			$sql_ary = $event['sql_ary'];
			$sql_ary['sort_topics_by_created_time'] = $this->ucp_sortby_created_time;
			$event['sql_ary'] = $sql_ary;
		}
	}
}
