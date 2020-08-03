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
 * resequence a poll's options
 */
function polls_adminapi_resequence($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if (!isset($pid)) {
        $msg = xarML('Missing poll ID');
        throw new BadParameterException(null,$msg);
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollsinfotable = $xartable['polls_info'];

    // Get the information
    $sql = "SELECT xar_optnum
            FROM $pollsinfotable
            WHERE xar_pid = ?
            ORDER BY xar_optnum";
    $result = $dbconn->Execute($sql, array((int)$pid));

    // Fix sequence numbers
    $seq=1;
    while(list($optnum) = $result->fields) {
        $result->MoveNext();

        if ($optnum != $seq) {
            $query = "UPDATE $pollsinfotable
                SET xar_optnum= ?
                WHERE xar_pid= ?
                AND xar_optnum= ?";
            $result1 = $dbconn->Execute($query, array($seq, (int)$pid, $optnum));
            if(!$result1){
                return;
            }

        }
        $seq++;
    }
    $result->Close();

    return;
}

?>
