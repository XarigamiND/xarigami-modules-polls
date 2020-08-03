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
 * Overview displays standard Overview page
 *
 * Only used if you actually supply an overview link in your adminapi menulink function
 * and used to call the template that provides display of the overview
 *
 * @return array xarTplModule with $data containing template data
 * @since 2 Oct 2005
 */
function polls_admin_overview()
{
   /* Security Check */
    if (!xarSecurityCheck('AdminPolls',0)) return;

    $data=array();

    /* if there is a separate overview function return data to it
     * else just call the main function that usually displays the overview
     */
    $data['menulinks'] = xarModAPIFunc('polls','admin','getmenulinks');
    return xarTplModule('polls', 'admin', 'main', $data,'main');
}

?>
