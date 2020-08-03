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
 * update a poll
 */
function polls_admin_update()
{

    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid)) return;
    if (!xarVarFetch('polltype', 'str:1:', $type, 'single', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('private', 'int:0:1', $private, 0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('title', 'str:1:', $title, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('pollintro', 'str:1:', $pollintro, '', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('start_date', 'str:1:', $start_date, time(), XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('end_date', 'str:1:', $end_date, NULL, XARVAR_NOT_REQUIRED)) return;

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

     if (!isset($title)){
        $msg = xarML('Missing required field title');
         throw new BadParameterException(null,$msg);
    }

    if($private != 1){
        $private = 0;
    }

    if (!empty($start_date)) {
        $start_date .= ' GMT';
        $start_date = strtotime($start_date);
        // adjust for the user's timezone offset
        $start_date -= xarMLS_userOffset() * 3600;
        }


    if (!empty($end_date)) {
        $end_date .= ' GMT';
        $end_date = strtotime($end_date);
        // adjust for the user's timezone offset
        $end_date -= xarMLS_userOffset() * 3600;
    }
    if (!isset($pollintro)) $pollintro = '';
    // Get poll info
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    if (!$poll) {
        throw new BadParameterException(null,$msg);
    }

    // security check
    if (!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")) {
        return;
    }
    $options = $poll['options'];

    $title = preg_replace("/:/", "&#58", $title);

    // Pass to API
    $updated = xarModAPIFunc('polls', 'admin', 'update',
                      array('pid' => $pid,
                           'title' => $title,
                           'type' => $type,
                           'private' => $private,
                           'start_date' => $start_date,
                           'end_date' => $end_date,
                           'pollintro'=> $pollintro));
    if(!$updated){
       return false;
    }

    // Success
    xarTplSetMessage( xarML('Poll Updated'),'status');

    xarResponseRedirect(xarModURL('polls', 'admin', 'list'));

    return true;
}

?>
