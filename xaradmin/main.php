<?php
/**
 * Polls Module main administration function
 *
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
 * Redirect to list function
 * @return bool true on success of redirect
 */
function polls_admin_main()
{
    // Security check
    if(!xarSecurityCheck('AdminPolls')) return;
    // redirect
    xarResponseRedirect(xarModURL('polls', 'admin', 'list'));
    // success
    return true;
}

?>
