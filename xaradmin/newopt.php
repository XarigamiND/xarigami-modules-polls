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
 * display form for a new poll option
 */
function polls_admin_newopt()
{
    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid, XARVAR_DONT_SET)) return;

    if (!isset($pid)) return; // throw back

    // Start output
    $data = array();
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    $poll = xarModAPIFunc('polls', 'user', 'get',
                           array('pid' => $pid));

    if (!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")) {
        return;
    }

    // Title
    $data['polltitle'] =  xarVarPrepHTMLDisplay($poll['title']);

    $data['authid'] = xarSecGenAuthKey();
    $data['pid'] = xarVarPrepForDisplay($pid);

    $data['buttonlabel'] = xarML('Create Option');
    $data['cancelurl'] = xarModURL('polls', 'admin', 'modify',
                            array('pid' => $pid));

    return $data;
}

?>
