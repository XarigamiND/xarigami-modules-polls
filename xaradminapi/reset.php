<?php
/*
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
 * @param $args['pid'] ID of poll
 * @returns bool
 * @return true on success, false on failure
 */
function polls_adminapi_reset($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($pid)) {
        $msg = xarML('Missing poll ID');
        throw new BadParameterException(null,$msg);
    }

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    // Security check
    if (!xarSecurityCheck('EditPolls',1,'All',"$poll[title]:$poll[type]")) {
        return;
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollsinfotable = $xartable['polls_info'];
    $prefix = xarConfigGetVar('prefix');

    $sql = "UPDATE $pollsinfotable
            SET xar_votes = 0
            WHERE xar_pid = ?";
    $result = $dbconn->Execute($sql, array((int)$pid));

    if (!$result) {
        return;
    }

    $pollstable = $xartable['polls'];

    $sql = "UPDATE $pollstable
            SET xar_votes = 0,
            xar_reset = ".time()."
            WHERE xar_pid = ?";
    $result = $dbconn->Execute($sql, array((int)$pid));

    if (!$result) {
        return;
    }

    return true;
}

?>
