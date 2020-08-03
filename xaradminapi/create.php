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
 * create a poll
 * @param $args['title'] title of poll
 * @param $args['polltype'] type of poll ('single' for select one
 *                                      'multi' for select many)
 * @param $args['time'] time when the poll was created (import only)
 * @param $args['votes'] number of votes for this poll (import only)
 * @param $args['module'] module of the item this poll relates to (hooks only)
 * @param $args['itemtype'] itemtype of the item this poll relates to (hooks only)
 * @param $args['itemid'] itemid of the item this poll relates to (hooks only)
 * @returns int
 * @return ID of poll, false on failure
 */
function polls_adminapi_create($args)
{
    // Get arguments from argument array
    extract($args);

    // Argument check
    if ((!isset($title)) || (!isset($polltype))) {
        $msg = xarML('Missing poll title or type');
         throw new BadParameterException(null,$msg);
    }
    if ($private != 1){
        $private = 0;
    }
    if (!isset($start_date) || !is_numeric($start_date)) {
        $start_date = time();
        }
    if (!isset($end_date) || !is_numeric($end_date)) {
        $end_date = 0;
        }
    if (!isset($pollintro)) $pollintro = '';
    // Security check
    if (!xarSecurityCheck('AddPolls')) {
        return;
    }

    $dbconn = xarDBGetConn();
    $xartable = xarDBGetTables();
    $pollstable = $xartable['polls'];
    $prefix = xarConfigGetVar('prefix');

    $nextId = $dbconn->GenId($pollstable);

    if (empty($time)) {
        $time = time();
    }
    if (empty($votes)) {
        $votes = 0;
    }
    if (empty($module)) {
        $module = 'polls';
    }
    $modid = xarModGetIDFromName($module);
    if (empty($itemtype)) {
        $itemtype = 0;
    }
    if (empty($itemid)) {
        $itemid = 0;
    }
    $sql = "INSERT INTO $pollstable (
              xar_pid,
              xar_title,
              xar_type,
              xar_open,
              xar_private,
              xar_votes,
              xar_modid,
              xar_itemtype,
              xar_itemid,
              xar_start_date,
              xar_end_date,
              xar_reset,
              xar_pollintro)
            VALUES (?,?,?,1,?,?,?,?,?,?,?,?,?)";

    $bindvars = array($nextId, $title, $polltype, $private, $votes, (int)$modid, $itemtype, $itemid, $start_date, $end_date, $time, $pollintro);
    $result = $dbconn->Execute($sql, $bindvars);


    if (!$result) {
        return;
    }
    $pid = $dbconn->PO_Insert_ID($pollstable, 'xar_pid');

    $args['pid'] = $pid;
    $args['module'] = 'polls';
    $args['itemtype'] = 0;
    $args['itemid'] = $pid;

    xarModCallHooks('item', 'create', $pid, $args);

    return $pid;
}

?>
