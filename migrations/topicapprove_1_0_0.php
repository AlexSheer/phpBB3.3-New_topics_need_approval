<?php
/**
*
* @package phpBB Extension - New topics need approval
* @copyright (c) 2022 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace sheer\topicapprove\migrations;

class topicapprove_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return;
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
		);
	}

	public function revert_schema()
	{
		return array(
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('topicapprove_version', '1.0.0')),
			// Add permissions
			array('permission.add', array('f_topic_approve', false)),
			// Add permissions sets
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_topic_approve', 'role', true)),
			array('permission.permission_set', array('ROLE_FORUM_STANDARD', 'f_topic_approve', 'role', true)),
			array('permission.permission_set', array('ROLE_FORUM_LIMITED', 'f_topic_approve', 'role', false)),
		);
	}
}