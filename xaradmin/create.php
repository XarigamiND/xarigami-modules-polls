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
 * create a new poll
 */
function polls_admin_create()
{
    // Get parameters

    if (!xarVarFetch('polltype',    'str:1:',  $polltype,   'single', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('private',     'int:0:1', $private,    0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('title',       'str:1:',  $title,      NULL, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('start_date',  'str:1:',  $start_date, time(),  XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('end_date',    'str:1:',  $end_date,   NULL, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('pollintro',   'str:1:',  $pollintro,      NULL, XARVAR_NOT_REQUIRED)) return;
    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    if (!xarSecurityCheck('AddPolls')) {
        return;
    }

    if (!isset($title)){
        $msg = xarML('Missing required field title');
        throw new BadParameterException(null,$msg);
    }

    if ($polltype != 'single' && $polltype != 'multi'){
        $msg = xarML('Invalid poll type');
        throw new BadParameterException(null,$msg);
    }
    if ($private != 1){
        $private = 0;
    }

     if (!empty($start_date)) {
        $start_date .= ' GMT';
        $start_date = strtotime($start_date);
        // adjust for the user's timezone offset
        $start_date -= xarMLS_userOffset() * 3600;
        }

     if(!empty($end_date)) {
        $end_date .= ' GMT';
        $end_date = strtotime($end_date);
        // adjust for the user's timezone offset
        $end_date -= xarMLS_userOffset() * 3600;
        }

     if (!isset($pollintro)) $pollintro = '';

    $title = preg_replace("/:/", "&#58", $title);

    // Pass to API
    $pid = xarModAPIFunc('polls', 'admin', 'create', array('title' => $title,
                                        'polltype' => $polltype,
                                        'private' => $private,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                        'pollintro'=> $pollintro));
    if (!$pid) {
        // Something went wrong - return
        $msg = xarML('There was a problem during poll creation. Unable to create poll.');
         xarTplSetMessage($msg,'error');
    }

    $optlimit = xarModGetVar('polls', 'defaultopts');

    for ($i = 1; $i <= $optlimit; $i++) {
        xarVarFetch('option_' . $i, 'isset', $option[$i]);
        if (!empty($option[$i])) {
            xarModAPIFunc('polls',
                         'admin',
                         'createopt',
                         array('pid' => $pid,
                               'option' => $option[$i]));
        }
    }

    // Back to main page
    // Success
    xarTplSetMessage(xarML('Poll Created Successfuly.'),'status');
    xarResponseRedirect(xarModURL('polls', 'admin', 'list'));

    return true;
}

?>
