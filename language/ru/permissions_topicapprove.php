<?php
/**
*
* @package phpBB Extension - New topics need approval
* @copyright (c) 2022 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACL_F_TOPIC_APPROVE'		=> 'Может открывать новые темы без одобрения',
));
