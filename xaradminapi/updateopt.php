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
 * update a poll option
 * @param $args['pid'] ID of poll
 * @param $args['optnum'] number of poll option
 * @param $args['optname'] name of poll option
 */
function polls_adminapi_updateopt($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($pid)) || (!isset($opt)) || (!isset($option))) {
        $msg = xarML('Missing poll ID, option ID, or option text');
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
    $pollsinfocolumn = &$xartable['polls_info_column'];

    $sql = "UPDATE $pollsinfotable
            SET xar_optname = ?
            WHERE xar_pid = ?
              AND xar_optnum = ?";
    $bindvars = array($option, (int)$pid, $opt);
    $result = $dbconn->Execute($sql, $bindvars);

    if (!$result) {
        return;
    }

    return true;
}

?>
