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
 * vote on a poll
 *
 * @param id $pid
 * @param returnurl
 * @param callingmod the module where the user comes from
 */
function polls_user_vote($args)
{
    // Get parameters

    if (!xarVarFetch('pid', 'id', $pid, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('returnurl', 'str', $returnurl, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('callingmod', 'str', $callingmod, XARVAR_DONT_SET)) return;

    extract($args);

    if(empty($pid)){
        $msg = xarML('No poll specified');
        throw new BadParameterException(null,$msg);
    }

    // Get the poll
    $poll = xarModAPIFunc('polls', 'user', 'get', array('pid' => $pid));

    if (empty($poll)) {
        $msg = xarML('Error retrieving Poll data');
        throw new BadParameterException(null,$msg);
    }

    $canvote = xarModAPIFunc('polls', 'user', 'usercanvote', array('poll' => $poll));

    if (!$canvote || $poll['state'] == 'closed') {
        xarSessionSetVar('polls_statusmsg', xarML('You cannot vote at this time.', 'polls'));

        if (!empty($returnurl)) {
            xarResponseRedirect($returnurl);
        } else {
            xarResponseRedirect(xarModURL('polls', 'user', 'results', array('pid' => $pid)));
        }

        return true;
    }

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    $options = array();
    // Get selected options
    if ($poll['type'] == 'single') {
        xarVarFetch('option', 'isset', $opt, XARVAR_DONT_SET);
        $options[$opt] = $opt;
    } elseif ($poll['type'] == 'multi') {
        for ($i = 1; $i <= $poll['opts']; $i++) {
            xarVarFetch('option_' . $i, 'isset', $opt[$i], XARVAR_DONT_SET);
            if ($opt[$i] == $i) {
                $options[$i] = $i;
            }
            $opt = '';
        }
    }

    if (count($options) == 0) {
        $msg = xarML('No vote received');
        throw new EmptyParameterException(null,$msg);
    }

    if (count($options) > 1 && $poll['type'] == 'single') {
        $msg = xarML('Multiple votes not allowed on this Poll.');
       throw new BadParameterException(null,$msg);
    }

    // Pass vote to API
    $vote = xarModAPIFunc('polls', 'user', 'vote',
        array('poll' => $poll, 'options' => $options)
    );

    if (empty($vote)) {
        $msg = xarML('Error recording vote');
        throw new BadParameterException(null,$msg);
    }

    // CHECKME: find some cleaner way to update the page cache if necessary
    if (function_exists('xarOutputFlushCached')) {
        if (isset($callingmod) &&
            xarModGetVar('xarcachemanager', 'FlushOnNewPollvote')) {
            xarPageFlushCached("$callingmod-user-display-");
        } else {
            xarOutputFlushCached("polls-user-");
        }
    }

    // Success, Redirect
    if (!empty($returnurl)) {
        xarResponseRedirect($returnurl);
    } else {
        xarResponseRedirect(xarModURL('polls', 'user', 'results', array('pid' => $pid)));
    }

    return true;
}

?>