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
 * Display poll
 *
 * This is a standard function to provide detailed information on a single poll.
 * However, it does not display the results of a single poll, but it does display
 * the voting form for a poll (it's a bit mixed up).
 * When a user is allowed to vote, a vote link is presented.
 * When a user is allowed to see the results, a result link is presented.
 *
 * @param id $pid Poll id
 */
function polls_user_display($args)
{
    if (!xarVarFetch('pid', 'id', $pid)) return;

    extract($args);

    if (!isset($pid)) {
        $msg = xarML('Missing poll id');
        throw new BadParameterException(null,$msg);
    }

    // Get item
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    if (empty($poll)) {
        $msg = xarML('Error retrieving Poll data');
        throw new BadParameterException(null,$msg);
    }

    $data = $poll;
    $data['returnurl'] =  xarServerGetCurrentURL();

    // See if user is allowed to vote
    if (xarSecurityCheck('VotePolls', 0, 'Polls', "$poll[title]:$poll[type]")) {
        if (xarModAPIFunc('polls', 'user', 'usercanvote', array('pid' => $pid)) && $poll['state'] == 'open') {
            // They have not voted yet, display voting options
            $data['canvote'] = 1;
            $data['resultsurl'] = xarModURL(
                'polls', 'user', 'results',
                array('pid' => $poll['pid'])
            );
            $data['previewresults'] = xarModGetVar('polls', 'previewresults');
            $data['authid'] = xarSecGenAuthKey('polls');
        } else {
            // They have just voted, display current results of that poll.
            return xarModFunc('polls', 'user', 'results', array('pid' => $pid));
        }
    } else {
        $data['canvote'] = 0;
    }

    if ($poll['modid'] == xarModGetIDFromName('polls')) {
        // Let hooks know we're displaying a poll, so they can provide us with related stuff
        $item = $poll;
        $item['module'] = 'polls';
        $item['returnurl'] = xarModURL('polls','user', 'display', array('pid' => $poll['pid']));
        $hooks = xarModCallHooks('item','display', $poll['pid'], $item);

        $data['hookoutput'] = trim(join('', $hooks));
        $data['hooks'] = $hooks;
    } else {
        $data['hookoutput'] = '';
    }

    $data['buttonlabel'] = xarML('Vote');

    // Return data to template.
    return $data;
}

?>