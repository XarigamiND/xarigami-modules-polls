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
/*
 * get a specific poll
 * @param $args['pid'] id of poll to get (optional)
 * @returns array
 * @return item array, or false on failure
 */
function polls_userapi_get($args)
{
    // Restrict to a single poll.
    $args['fetchone'] = true;

    // Always fetch the options.
    $args['getoptions'] = true;

    // If not fetching on the PID, then get the latest open poll.
    // TODO: we need to change this, to make it more explicit.
    if (empty($args['pid']) && !isset($status)) {
        $args['status'] = 1;
        $args['modid'] = xarModGetIDFromName('polls');
    }

    $items = xarModAPIfunc('polls', 'user', 'getall', $args);

    if (!empty($items)) {
        $item = reset($items);
    } else {
        $item = array();
    }

    return $item;
}

?>