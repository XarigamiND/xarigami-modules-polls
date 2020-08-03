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
 * Search for polls
 */

function polls_user_search()
{
    if (!xarVarFetch('q', 'isset', $q, NULL, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('bool', 'isset', $bool, NULL, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('sort','isset', $sort, NULL, XARVAR_DONT_SET)) return;
    if (!xarVarFetch('title', 'int:0:1', $title, 0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('options', 'int:0:1', $options, 0, XARVAR_DONT_SET)) return;


    $data = array();
    $data['title'] = $title;
    $data['options'] = $options;
    if($q == ''){
        return $data;
    }

    // Get poll information
    $data['polls'] = xarModAPIFunc('polls', 'user','search',
                           array('title' => $title,
                                 'options' => $options,
                                 'q' => $q));

    if (empty($data['polls'])){
        $data['status'] = xarML('No Polls Found Matching Search');
    }

    return $data;

}
?>
