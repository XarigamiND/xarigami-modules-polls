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
 * @param $args['pid'] ID of poll
 * @param $args['title'] ID of poll
 */
function polls_adminapi_update($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($pid)) || (!isset($title)) || (!isset($type))) {
        $msg = xarML('Missing poll ID, title, or type');
        throw new BadParameterException(null,$msg);
    }

    if($private != 1){
        $private = 0;
    }

    if (!isset($end_date) || !is_numeric($end_date)) {
        $end_date = 0;
    }

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    // Security check
    if (!xarSecurityCheck('EditPolls',1,'All',"$poll[title]:$poll[type]")) {
        return;
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollstable = $xartable['polls'];
   // $prefix = xarConfigGetVar('prefix');

    $sql = "UPDATE $pollstable
            SET xar_title = ?,
            xar_type = ?,
            xar_private = ?,
            xar_start_date = ?,
            xar_end_date = ?,
            xar_pollintro = ?
            WHERE xar_pid = ?";

    $bindvars = array($title, $type, $private, $start_date, $end_date, $pollintro, (int)$pid);
    $result = $dbconn->Execute($sql, $bindvars);

    if (!$result) {
        return;
    }
    $args['pid'] = $pid;
    $args['module'] = 'polls';
    $args['itemtype'] = 0;
    $args['itemid'] = $pid;

    xarModCallHooks('item', 'update', $pid, $args);

    return true;
}

?>
