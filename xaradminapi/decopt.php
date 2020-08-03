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
 * decrement poll option position
 * @param $args['pid'] the ID of the poll to decrement
 * @param $args['optnum'] the number of the option to decrement
 * @returns bool
 * @return true on success, false on failure
 */
function polls_adminapi_decopt($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($pid)) || (!isset($opt))) {
        $msg = xarML('Missing poll ID or option');
        throw new EmptyParameterException(null,$msg);
    }

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get',
                           array('pid' => $pid));

    if (empty($poll)) {
               throw new EmptyParameterException(null,$msg);
    }

    // Security check
    if (!xarSecurityCheck('EditPolls',1,'All',"$poll[title]:$poll[type]")) {
        return;
    }


    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollsinfotable = $xartable['polls_info'];
    $pollsinfocolumn = &$xartable['polls_info_column'];

    // Swap positions - three updates
    $sql = "UPDATE $pollsinfotable
            SET xar_optnum = xar_optnum + 900
            WHERE xar_pid = ?
            AND xar_optnum = ?";
    $result = $dbconn->Execute($sql, array((int)$pid, $opt));
    if(!$result){
        return;
    }
    $optplusone=$opt + 1;
    $sql = "UPDATE $pollsinfotable
            SET xar_optnum = ?
            WHERE xar_pid = ?
            AND xar_optnum = ?";
    $result = $dbconn->Execute($sql, array($opt, (int)$pid, $optplusone));
    if(!$result){
        return;
    }
    $optplus=$opt + 900;
    $sql = "UPDATE $pollsinfotable
            SET xar_optnum = xar_optnum - 899
            WHERE xar_pid = ?
            AND xar_optnum =?";
    $result = $dbconn->Execute($sql, array((int)$pid, $optplus));
    if(!$result){
        return;
    }

    return true;
}

?>
