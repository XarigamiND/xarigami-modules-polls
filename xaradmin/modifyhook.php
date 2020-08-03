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
 * modify an entry for a module item - hook for ('item','modify','GUI')
 *
 * @param $args['objectid'] ID of the object
 * @param $args['extrainfo'] extra information
 * @return string hook output in HTML
 * @throws BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function polls_admin_modifyhook($args)
{
    extract($args);

    if (!isset($extrainfo)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'extrainfo', 'admin', 'modifyhook', 'polls');
       throw new BadParameterException(null,$msg);
    }

    if (!isset($objectid) || !is_numeric($objectid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'object ID', 'admin', 'modifyhook', 'polls');
        throw new BadParameterException(null,$msg);
    }

    // When called via hooks, the module name may be empty, so we get it from
    // the current module
    if (empty($extrainfo['module'])) {
        $modname = xarModGetName();
    } else {
        $modname = $extrainfo['module'];
    }

    $modid = xarModGetIDFromName($modname);
    if (empty($modid)) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                    'module name', 'admin', 'modifyhook', 'polls');
       throw new BadParameterException(null,$msg);
    }

    if (!empty($extrainfo['itemtype']) && is_numeric($extrainfo['itemtype'])) {
        $itemtype = $extrainfo['itemtype'];
    } else {
        $itemtype = 0;
    }

    if (!empty($extrainfo['itemid']) && is_numeric($extrainfo['itemid'])) {
        $itemid = $extrainfo['itemid'];
    } else {
        $itemid = $objectid;
    }

// TODO: security check based on calling module + item type + item id ?
    if (!xarSecurityCheck('EditPolls',0)) {
        return '';
    }

    $optcount = xarModGetVar('polls', 'defaultopts');
    if (empty($optcount)) {
        $optcount = 6;
    }

    // get the current poll for this item
    $oldpoll = xarModAPIFunc('polls','user','gethooked',
                             array('modname' => $modname,
                                   'itemtype' => $itemtype,
                                   'objectid' => $itemid));
    if (!empty($oldpoll)) {
        if (!xarSecurityCheck('EditPolls',0,'Polls',"$oldpoll[title]:$oldpoll[type]")) {
            return '';
        }

        $poll = array();
        // set the old values
        if (!isset($oldpoll['options'])) $oldpoll['options'] = array();
        if (!isset($oldpoll['pollintro'])) $oldpoll['pollintro'] = '';
        if (!isset($oldpoll['title'])) $oldpoll['title'] = '';
        foreach (array_keys($oldpoll) as $key) {
            if ($key != 'options') {
                $poll[$key] = $oldpoll[$key];
            } else {
                $poll[$key] = array();
                foreach ($oldpoll[$key] as $id => $option) {
                    if (isset($option['name'])) {
                        $poll[$key][$id] = $option['name'];
                    } else {
                        $poll[$key][$id] = '';
                    }
                }
            }
        }
        // fill in the blanks
        for ($i = 1; $i <= $optcount; $i++) {
            if (!isset($poll['options'][$i])) {
                $poll['options'][$i] = '';
            }
        }
    }


    if (isset($extrainfo['poll'])) {
        $poll = $extrainfo['poll'];
    } else {
        xarVarFetch('poll', 'array', $poll, null, XARVAR_NOT_REQUIRED);
        if (isset($newpoll)) {
            $poll = $newpoll;
        }
    }
    if (empty($poll)) {
        $poll = array('title' => '',
                      'type' => 'single',
                      'private' => '',
                      'pollintro' => '',
                      'options' => array());
        for ($i = 1; $i <= $optcount; $i++) {
            $poll['options'][$i] = '';
        }
    }

    return xarTplModule('polls','admin','modifyhook',
                        array('optcount' => $optcount,
                              'poll' => $poll));
}

?>
