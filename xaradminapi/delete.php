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
 * delete a poll
 * @param $args['pid'] ID of poll
 * @return bool true on success, false on failure
 */
function polls_adminapi_delete($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($pid)) {
        $msg = xarML('Missing poll ID');
       throw new EmptyParameterException(null,$msg);
    }

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    // Security check
    if (!xarSecurityCheck('DeletePolls',1,'All',"$poll[title]:$poll[type]")) {
        return;
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollsinfotable = $xartable['polls_info'];
    $prefix = xarConfigGetVar('prefix');

    $sql = "DELETE FROM $pollsinfotable
            WHERE xar_pid = ?";
    $result = $dbconn->Execute($sql, array((int)$pid));

    if (!$result) {
        return;
    }

    $pollstable = $xartable['polls'];

    $sql = "DELETE FROM $pollstable
            WHERE xar_pid = ?";
    $result = $dbconn->Execute($sql, array((int)$pid));
    if (!$result) {
        return;
    }

    $args['pid'] = $pid;
    $args['module'] = 'polls';
    $args['itemtype'] = 0;
    $args['itemid'] = $pid;

    xarModCallHooks('item', 'delete', $pid, $args);

    return true;
}

?>
