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
 * create new poll option
 */
function polls_admin_createopt()
{
    // Get parameters

    if (!xarVarFetch('pid', 'id', $pid, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('option', 'str:0:', $option, XARVAR_DONT_SET)) return;

    if (!isset($pid) || !isset($option) && xarCurrentErrorType() != XAR_NO_EXCEPTION) return; // throw back

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    $poll = xarModAPIFunc('polls',
                           'user',
                           'get', array('pid' => $pid));

    if (!isset($poll) && xarCurrentErrorType() != XAR_NO_EXCEPTION) return; // throw back

    if (!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")) {
        return;
    }

    // Pass to API
    $created = xarModAPIFunc('polls', 'admin', 'createopt', array('pid' => $pid,
                                              'option' => $option));

    if (!$created) {
         xarTplSetMessage(xarML('There was an error during poll option creation. The option was not created.'),'error');
    }

    // Success
    xarTplSetMessage(xarML('Poll option created.'),'status');

    xarResponseRedirect(xarModURL('polls', 'admin', 'modify',
                        array('pid' => $pid)));

    return true;
}

?>
