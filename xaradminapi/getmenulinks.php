<?php
/**
 * Polls Module
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
 * utility function pass individual menu items to the main menu
 *
 * @author the Polls module development team
 * @return array containing the menulinks for the main menu items.
 */
function polls_adminapi_getmenulinks()
{
    $menulinks = array();

    if (xarSecurityCheck('EditPolls',0)) {
        $menulinks[] = Array('url'   => xarModURL('polls','admin','list'),
                              'title' => xarML('View, edit or create Polls'),
                              'label' => xarML('Manage Polls'),
                              'active'=> array('list','delete','modify','modifyopt')
                              );
    }

    if (xarSecurityCheck('AddPolls',0)) {

        $menulinks[] = Array('url'   => xarModURL('polls','admin','new'),
                              'title' => xarML('Create a New Poll'),
                              'label' => xarML('New Poll'),
                              'active'=> array('new')
                              );
    }


    if (xarSecurityCheck('AdminPolls',0)) {
        $menulinks[] = Array('url' => xarModURL('polls', 'admin','modifyconfig'),
                              'title' => xarML('Modify Polls configuration'),
                              'label' => xarML('Modify Config'),
                                'active'=> array('modifyconfig')
                              );
    }
    return $menulinks;
}

?>
