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
 * modify a poll
 */
function polls_admin_modify()
{
    // Get parameters
    if (!xarVarFetch('pid', 'id', $pid)) return;

     if (empty($pid)) {
        $msg = xarML('No poll selected');
        throw new EmptyParameterException(null,$msg);
    }

    // Start output
    $data = array();

    // Get poll information
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    if (!$poll) {
        throw new BadParameterException(null,$msg);
    }

    // Security check

    if (!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")) {
        return;
    }
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    $data['authid'] = xarSecGenAuthKey();
    $data['pid'] = $pid;
    $data['buttonlabel'] = xarML('Modify Poll');
    $data['polltitle'] = xarVarPrepHTMLDisplay($poll['title']);
    $data['polltype'] = $poll['type'];
    $data['private'] = $poll['private'];
    $data['start_date'] = $poll['start_date'];
    $data['end_date'] = $poll['end_date'];
    $data['pollintro'] = $poll['pollintro'];

/* start options */

    $data['uplabel'] = xarML('Up');
    $data['downlabel'] = xarML('Down');
    $data['newurl'] = xarModURL('polls', 'admin', 'newopt', array('pid' => $pid));
    $data['backurl'] = xarModURL('polls', 'admin', 'list');

    $data['options'] = array();

    $rownum = 1;
    foreach ($poll['options'] as $opt => $optinfo) {
        $row = array();
        $row['down'] = NULL;
        $row['up'] = NULL;

        $upurl = xarModURL('polls', 'admin', 'incopt',
                           array('pid' => $pid,
                                 'opt' => $opt,
                                 'authid' => $data['authid']));
        $downurl = xarModURL('polls','admin','decopt',
                             array('pid' => $pid,
                                   'opt' => $opt,
                                   'authid' => $data['authid']));

        if (count($poll['options']) > 1) {
            switch($rownum) {
                case 1:
                    $row['down'] = $downurl;
                    break;
                case count($poll['options']):
                    $row['up'] = $upurl;
                    break;
                default:
                    $row['down'] = $downurl;
                    $row['up'] = $upurl;
            }
        }
        $row['name'] = $optinfo['name'];

        $row['votes'] = $optinfo['votes'];

        if (xarSecurityCheck('EditPolls',0,'Polls',"$poll[title]:$poll[type]")) {
            $row['modify'] = xarModURL('polls', 'admin','modifyopt',
                                               array('pid' => $pid,
                                                     'opt' => $opt));
        }
        if (xarSecurityCheck('EditPolls',0,'Polls',"$poll[title]:$poll[type]")) {
             if (($optinfo['votes'] != 0)) {
                $row['delete_confirm'] = xarML('Option "#(1)" has votes.  Delete anyway?', addslashes($optinfo['name']));
            } else {
            $row['delete_confirm'] = xarML('Are you sure to delete option "#(1)"', addslashes($optinfo['name']));
            }
            $row['delete'] = xarModURL('polls', 'admin', 'deleteopt',
                                        array('pid' => $pid,
                                              'authid' => $data['authid'],
                                              'opt' => $opt,
                                              'votes' => $row['votes']));
        }
        $data['options'][] = $row;
        $rownum++;
        $row = NULL;
    }

/*  end options */

    $item['module'] = 'polls';
    $item['itemid'] = $pid;
    $item['itemtype'] = 0;
    $hooks = xarModCallHooks('item', 'modify', $pid, $item);

    if (empty($hooks)) {
        $data['hooks'] = '';
    } elseif (is_array($hooks)) {
        $data['hooks'] = join('', $hooks);
    } else {
        $data['hooks'] = $hooks;
    }

    return $data;
}

?>
