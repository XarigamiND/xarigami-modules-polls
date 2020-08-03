<?php
/**
 * Polls module
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
 * close a poll
 */
function polls_admin_close()
{
    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid)) return;
    if (!xarVarFetch('status', 'int:1:3', $status, 1, XARVAR_NOT_REQUIRED)) return;

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;
    $menulinks = xarModAPIFunc('polls','admin','getmenulinks');
    // Pass to API
    if (!xarModAPIFunc('polls', 'admin', 'close', array('pid' => $pid,'menulinks'=>$menulinks))) return;

    xarResponseRedirect(xarModURL('polls', 'admin', 'list', array('status' => $status,'menulinks'=>$menulinks)));

    return true;
}

?>