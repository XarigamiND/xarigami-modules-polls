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
 * increment position for a poll option
 */
function polls_admin_incopt()
{
    // Get parameters

    if (!xarVarFetch('pid', 'id', $pid, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('opt', 'int:0:', $opt, XARVAR_DONT_SET)) return;

    if (!isset($pid) || !isset($opt)) return; // throw back

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    // Pass to API
    $incremented = xarModAPIFunc('polls', 'admin', 'incopt', array('pid' => $pid,
                                                         'opt' => $opt));

    if (!$incremented) return; // throw back

    // Redirect
    xarResponseRedirect(xarModURL('polls','admin', 'modify',
                        array('pid' => $pid)));
    return true;
}

?>
