<?php
/**
*
* @package phpBB Extension - New topics need approval
* @copyright (c) 2022 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace sheer\topicapprove\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
/**
* Assign functions defined in this class to event listeners in the core
*
* @return array
* @static
* @access public
*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.permissions'							=> 'add_permission',
			'core.posting_modify_submit_post_after'		=> 'submit_post_after',
			'core.modify_submit_post_data'				=> 'post_modify',
		);
	}

	/** @var auth */
	protected $auth;

	/** @var user */
	protected $user;

	//** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*/
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\user $user,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->auth				= $auth;
		$this->user				= $user;
		$this->phpbb_root_path	= $phpbb_root_path;
		$this->php_ext			= $php_ext;
	}

	public function post_modify($event)
	{		$data = $event['data'];
		if ($event['mode'] == 'post' && !$this->auth->acl_get('f_topic_approve', $event['forum_id']) && !$this->auth->acl_get('m_approve', $event['forum_id']))
		{			$data['post_visibility'] = ITEM_UNAPPROVED;
			$data['topic_visibility'] = ITEM_UNAPPROVED;
			$event['data'] = $data;
		}
	}

	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['f_topic_approve']	= array('lang' => 'ACL_F_TOPIC_APPROVE', 'cat' => 'post');
		$event['permissions'] = $permissions;
	}

	public function submit_post_after($event)
	{		$redirect_url = $event['redirect_url'];
		if ($event['mode'] == 'post' && !$this->auth->acl_get('f_topic_approve', $event['forum_id']) && !$this->auth->acl_get('m_approve', $event['forum_id']))
		{			$message = ($event['mode'] == 'edit') ? $this->user->lang['POST_EDITED_MOD'] : $this->user->lang['POST_STORED_MOD'];
			$message .= (($this->user->data['user_id'] == ANONYMOUS) ? '' : ' '. $this->user->lang['POST_APPROVAL_NOTIFY']);
			$message .= '<br /><br />' . sprintf($this->user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$this->phpbb_root_path}viewforum.$this->php_ext", 'f=' . $event['forum_id']) . '">', '</a>');
			meta_refresh(10, $redirect_url);
			trigger_error($message);
		}
	}
}
