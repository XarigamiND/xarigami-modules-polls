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
 * delete a poll option
 * @param $args['pid'] ID of poll
 * @param $args['optnum'] poll option number
 * @returns bool
 * @return true on success, false on failure
 */
function polls_adminapi_deleteopt($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($pid))  ||
        (!isset($opt))) {
        $msg = xarML('Missing poll ID or option ID');
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

    $sql = "DELETE FROM $pollsinfotable
            WHERE xar_pid = ?
              AND xar_optnum = ?";

    $result = $dbconn->Execute($sql, array((int)$pid, $opt));

    if (!$result) {
        return;
    }

    // Decrement number of options
    $new_votes = ($poll['votes'] - $votes);
    $pollstable = $xartable['polls'];
    $sql = "UPDATE $pollstable
            SET xar_opts = xar_opts - 1,
            xar_votes = ?
            WHERE xar_pid = ?";

    $result = $dbconn->Execute($sql, array((int)$new_votes,(int)$pid));

    if (!$result) {
        return;
    }

    // Resequence
   // xarModAPIFunc('polls','admin','resequence',array('pid' => $pid));

    return true;
}

?>
