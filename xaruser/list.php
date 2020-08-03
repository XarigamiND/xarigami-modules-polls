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
 * list polls
 */
function polls_user_list($args)
{
    extract($args);

    if (!xarVarFetch('catid', 'str', $catid, 0, XARVAR_DONT_SET)) return;

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing
    if (!xarSecurityCheck('ListPolls', 1)) {
        return;
    }

    $items = xarModAPIFunc('polls', 'user', 'getall',
        array('modid' => xarModGetIDFromName('polls'), 'catid' => $catid)
    );
    $data = array();
    $data['catid'] = $catid;

    if (!$items) {
        return $data;
    }

    // The return value of the function is checked here, and if the function
    // suceeded then an appropriate message is posted.  Note that if the
    // function did not succeed then the API function should have already
    // posted a failure message so no action is required

    $data['previewresults'] = xarModGetVar('polls', 'previewresults');
    $data['showtotalvotes'] = xarModGetVar('polls', 'showtotalvotes');
    $data['polls'] = array();

    // TODO - loop through each item and display it
    foreach ($items as $item) {
        $poll = $item;

        if (xarSecurityCheck('VotePolls', 0, 'Polls', "$item[title]:$item[type]")) {
            $poll['canvote'] = xarModAPIFunc('polls', 'user', 'usercanvote',
                array('poll' => $item)
            );

            $poll['action_vote'] = xarModURL('polls', 'user', 'display',
                array('pid' => $item['pid'])
            );
        } else {
            // We have no privilege to vote on this poll.
            $poll['canvote'] = 0;

            // If the poll is still open, and a preview of results is
            // not allowed, then we actually have nothing to view on this
            // poll at all.
            if (empty($data['previewresults']) && $poll['open']) {
                continue;
            }
        }

        $poll['action_results'] = xarModURL('polls', 'user', 'results',
            array('pid' => $item['pid'])
        );

        $data['polls'][] = $poll;
    }
    return $data;
}

?>