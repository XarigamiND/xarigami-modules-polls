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
 * @deprecated 2005?
 */
function polls_admin_display()
{
    if (!xarVarFetch('pid', 'id', $pid)) return;

    // Check arguments
    if (empty($pid)) {
        $msg = xarML('No poll selected');
       throw new EmptyParameterException(null,$msg);
    }

    $data = array();
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    // Get info on this poll
    $poll = xarModAPIFunc('polls', 'user', 'get',
                           array('pid' => $pid));

    if (!$poll) {
        throw new BadParameterException(null,$msg);
    }

    // Security check
    if(!xarSecurityCheck('EditPolls',1,'Polls',"$poll[title]:$poll[type]")){
        return;
    }

    $authid = xarSecGenAuthKey();

    $data['polltitle'] = $poll['title'];
    $data['type'] = $poll['type'];
    $data['authid'] = $authid;
    $data['uplabel'] = xarML('Up');
    $data['downlabel'] = xarML('Down');
    $data['newurl'] = xarModURL('polls', 'admin', 'newopt',
                                array('pid' => $pid));
    $data['backurl'] = xarModURL('polls','admin','list');

    $data['options'] = array();

    $rownum = 1;
    foreach ($poll['options'] as $opt => $optinfo) {
        $row = array();
        $row['down'] = NULL;
        $row['up'] = NULL;

        $upurl = xarModURL('polls', 'admin','incopt',
                                array('pid' => $pid,
                                      'opt' => $opt,
                                      'authid' => $authid));
        $downurl = xarModURL('polls', 'admin', 'decopt',
                                  array('pid' => $pid,
                                        'opt' => $opt,
                                        'authid' => $authid));

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
            $row['modify'] = xarModURL('polls',
                                               'admin',
                                               'modifyopt',
                                               array('pid' => $pid,
                                                     'opt' => $opt));
        }
        if (xarSecurityCheck('EditPolls',0,'Polls',"$poll[title]:$poll[type]")) {
            $row['delete'] = xarModURL('polls',
                                               'admin',
                                               'deleteopt',
                                               array('pid' => $pid,
                                                     'opt' => $opt));
        }
        $data['options'][] = $row;
        $rownum++;
        $row = NULL;
    }

    if ($poll['open']) {
        $data['newopturl'] = xarModURL('polls',
                                'admin',
                                'newopt',
                                array('pid' => $pid));
    }

    return $data;
}

?>
