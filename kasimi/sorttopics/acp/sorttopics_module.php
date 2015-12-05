<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\acp;

class sorttopics_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $config, $request, $template, $user, $phpbb_log;

		$user->add_lang('acp/common');
		$this->tpl_name = 'acp_sorttopics';
		$this->page_title = $user->lang('SORTTOPICS_TITLE');

		add_form_key('acp_sorttopics');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acp_sorttopics'))
			{
				trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action));
			}

			$config->set('kasimi.sorttopics.ucp_enabled', $request->variable('sorttopics_ucp_enabled', 0));

			$user_id = (empty($user->data)) ? ANONYMOUS : $user->data['user_id'];
			$user_ip = (empty($user->ip)) ? '' : $user->ip;
			$phpbb_log->add('admin', $user_id, $user_ip, 'SORTTOPICS_CONFIG_UPDATED');
			trigger_error($user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'SORTTOPICS_VERSION'				=> isset($config['kasimi.sorttopics.version']) ? $config['kasimi.sorttopics.version'] : 'X.Y.Z',
			'SORTTOPICS_UCP_ENABLED'			=> isset($config['kasimi.sorttopics.ucp_enabled']) ? $config['kasimi.sorttopics.ucp_enabled'] : 0,
			'U_ACTION'							=> $this->u_action,
		));
	}
}
