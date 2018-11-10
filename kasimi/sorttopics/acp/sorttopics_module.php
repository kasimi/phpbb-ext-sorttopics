<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
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

			$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'SORTTOPICS_CONFIG_UPDATED');
			trigger_error($user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$template->assign_vars([
			'SORTTOPICS_UCP_ENABLED'	=> $config['kasimi.sorttopics.ucp_enabled'],
			'U_ACTION'					=> $this->u_action,
		]);
	}
}
