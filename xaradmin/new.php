<?php
/**
 * Polls module
 *
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
 * display form for a new poll
 */
function polls_admin_new()
{

    if (!xarSecurityCheck('AddPolls')) {
        return;
    }

    $data = array();
    $data['authid'] = xarSecGenAuthKey();
    $data['optcount'] = xarModGetVar('polls', 'defaultopts');
    $data['start_date']= time();
    $data['end_date']= NULL;
    $data['pollintro']= '';
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');

    $item = array();
    $item['module'] = 'polls';
    $item['itemtype'] = 0;
    $hooks = xarModCallHooks('item', 'new', '', $item);

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
