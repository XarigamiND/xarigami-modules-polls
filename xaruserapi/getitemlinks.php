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
 * utility function to pass individual item links to whoever
 *
 * @param $args['itemtype'] item type (not relevant here)
 * @param $args['itemids'] array of item ids to get
 * @returns array
 * @return array containing the itemlink(s) for the item(s).
 */
function polls_userapi_getitemlinks($args)
{
    extract($args);

    $itemlinks = array();

    // Get database setup
    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollstable = $xartable['polls'];

    // Get polls
    $sql = "SELECT xar_pid,
                   xar_title,
                   xar_type
            FROM $pollstable
            WHERE xar_pid IN (". join(', ', $itemids) . ")";
    $result = $dbconn->Execute($sql);
    if (!$result) return;

    // Put polls into result array.
    for (; !$result->EOF; $result->MoveNext()) {
        list($pid, $title,$type) = $result->fields;
        if (xarSecurityCheck('ViewPolls',0,'Polls',"$title:$type")) {
             $itemlinks[$pid] = array('url'   => xarModURL('polls', 'user', 'results',
                                                           array('pid' => $pid)),
                                      'title' => xarML('View Poll'),
                                      'label' => xarVarPrepForDisplay($title));
        }
    }
    $result->Close();

    return $itemlinks;
}

?>
