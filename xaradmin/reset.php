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
 * reset a poll
 */
function polls_admin_reset()
{
    // Get parameters

    if (!xarVarFetch('pid', 'id', $pid)) return;
    if (!xarVarFetch('confirm', 'isset', $confirm, NULL, XARVAR_DONT_SET)) return;

    if (!isset($pid)) return; // throw back

    $poll = xarModAPIFunc('polls', 'user', 'get',
                           array('pid' => $pid));

    if (!xarSecurityCheck('AdminPolls',1,'Poll',"$poll[title]:$poll[type]")) {
        return;
    }

    // Check for confirmation
    if ($confirm != 1) {
        // No confirmation yet - get one
        $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
        $data = array();

        $data['polltitle'] = $poll['title'];
        $data['pid'] = $pid;
        $data['confirm'] = 1;
        $data['authid'] = xarSecGenAuthKey();
        $data['buttonlabel'] = 'Reset Poll';

        return $data;
    }

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    // Pass to API
    if (xarModAPIFunc('polls', 'admin', 'reset', array('pid' => $pid))) {
        // Success
        xarTplSetMessage( xarML('Poll reset'),'status');

    }

    xarResponseRedirect(xarModURL('polls', 'admin', 'list'));

    return true;
}


?>
