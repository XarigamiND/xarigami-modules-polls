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
 * delete a poll
 */
function polls_admin_delete()
{
    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid)) return;
    if (!xarVarFetch('confirm', 'isset', $confirm, NULL, XARVAR_DONT_SET)) return;

    if (!isset($pid) && xarCurrentErrorType() != XAR_NO_EXCEPTION) return; // throw back


    $poll = xarModAPIFunc('polls', 'user', 'get',
                           array('pid' => $pid));

    if (!xarSecurityCheck('DeletePolls',1,'Polls',"$poll[title]:$poll[type]")) {return;}
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    // Check for confirmation
    if ($confirm != 1) {
        // No confirmation yet - get one

        $data = array();

        $data['polltitle'] = $poll['title'];
        $data['pid'] = $pid;
        $data['confirm'] = 1;
        $data['authid'] = xarSecGenAuthKey();

        return $data;
    }

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    // Pass to API
    if (xarModAPIFunc('polls', 'admin', 'delete', array('pid' => $pid))) {
        // Success
        xarTplSetMessage(xarML('Poll was successfully deleted.'),'status');

    }

    xarResponseRedirect(xarModURL('polls', 'admin', 'list'));

    return true;
}

?>
