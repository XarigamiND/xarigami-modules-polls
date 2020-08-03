<?php
/**
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 *
 * @subpackage Xarigami Polls Module
 * @copyright (C) 2008-2011 2skies.com
 * @link http://xarigami.com/project/xarigami_polls
 * @author Polls Module Development team
 */

/**
 * main user display function
 *
 * Redirect user to the listing of polls
 */
function polls_user_main($args)
{
    $polls =  xarModFunc('polls', 'user', 'list', $args);
    return $polls;
}

?>
