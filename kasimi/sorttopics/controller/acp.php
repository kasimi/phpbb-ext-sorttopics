<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\controller;

use phpbb\config\config;
use phpbb\language\language;
use phpbb\log\log_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;

class acp extends base
{
	/** @var user */
	protected $user;

	/** @var language */
	protected $lang;

	/** @var request_interface */
	protected $request;

	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var log_interface */
	protected $log;

	public function __construct(
		user $user,
		language $lang,
		request_interface $request,
		config $config,
		template $template,
		log_interface $log
	)
	{
		$this->user		= $user;
		$this->lang		= $lang;
		$this->request	= $request;
		$this->config	= $config;
		$this->template	= $template;
		$this->log		= $log;
	}

	public function main(string $id, string $mode, string $u_action): void
	{
		$ext_name = 'kasimi/sorttopics';

		$this->tpl_name = 'acp_sorttopics';
		$this->page_title = 'SORTTOPICS_TITLE';

		$this->lang->add_lang('acp', $ext_name);

		add_form_key($ext_name);

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($ext_name))
			{
				trigger_error($this->lang->lang('FORM_INVALID') . adm_back_link($u_action));
			}

			$this->config->set('kasimi.sorttopics.ucp_enabled', $this->request->variable('sorttopics_ucp_enabled', 0));

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SORTTOPICS_CONFIG_UPDATED');
			trigger_error($this->lang->lang('CONFIG_UPDATED') . adm_back_link($u_action));
		}

		$this->template->assign_vars([
			'SORTTOPICS_UCP_ENABLED'	=> $this->config['kasimi.sorttopics.ucp_enabled'],
			'U_ACTION'					=> $u_action,
		]);
	}
}
