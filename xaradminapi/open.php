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
 * reopen a poll
 * @param $args['pid'] ID of poll
 */
function polls_adminapi_open($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($pid)) {
        $msg = xarML('Missing poll');
        throw new BadParameterException(null,$msg);
    }

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    // Security check
    if (!xarSecurityCheck('AdminPolls',1,'All',"$poll[title]:$poll[type]")) {
        return;
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollstable = $xartable['polls'];
    $prefix = xarConfigGetVar('prefix');

    $sql = "UPDATE $pollstable
            SET xar_end_date = ?,
            xar_open = ?
            WHERE xar_pid = ?";
    $result = $dbconn->Execute($sql, array(strtotime('now +1 day'), 1, (int)$pid));

    if (!$result) {
        return;
    }

    return true;
}

?>