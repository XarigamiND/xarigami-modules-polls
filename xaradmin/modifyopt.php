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
 * Modify options
 */
function polls_admin_modifyopt()
{
    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('opt', 'int:0:', $opt, XARVAR_DONT_SET)) return;

    // Check arguments
    if (empty($pid) || empty($opt)) {
        $msg = xarML('No poll or option specified');
        throw new BadParameterException(null,$msg);
    }

    // Start output
    $data = array();
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    if (!$poll) {
       throw new BadParameterException(null,$msg);
    }

    // Security check
    if (!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")) {
        return;
    }

    // Title
    $data['polltitle'] = $poll['title'];
    $data['authid'] = xarSecGenAuthKey();
    $data['pid'] = xarVarPrepHTMLDisplay($pid);
    $data['opt'] = $opt;

    // Name
    $data['option'] = xarVarPrepHTMLDisplay($poll['options'][$opt]['name']);

    $data['cancelurl'] = xarModURL('polls', 'admin','modify',
                            array('pid' => $pid));

    return $data;
}

?>
