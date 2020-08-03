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
 * Update the configuration parameters of the
 * module given the information passed back by the modification form
 */
function polls_admin_updateconfig()
{
    // Get parameters

    if (!xarVarFetch('barscale', 'str:1:', $barscale, 1, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('defaultopts', 'str:1:', $defaultopts, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('imggraph', 'str:0:3', $imggraph, 0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('voteinterval', 'str:1:', $voteinterval, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('previewresults', 'str:1:', $previewresults, 'single', XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('showtotalvotes', 'int', $showtotalvotes, 0, XARVAR_NOT_REQUIRED)) return;
    if (!xarVarFetch('shorturl', 'str:1:', $shorturl, 0, XARVAR_NOT_REQUIRED)) return;

    // Confirm authorisation code
    if (!xarSecConfirmAuthKey()) return;

    // Security Check
    if (!xarSecurityCheck('AdminPolls')) {
        return;
    }

    // Check arguments
    if (!is_numeric($barscale) || $barscale <= 0) {
        $msg = xarML("Invalid value for config variable: barscale");
         throw new BadParameterException(null,$msg);
    }

    if (strval(intval($defaultopts)) !== $defaultopts || $defaultopts < 2) {
        $msg = xarML("Invalid value for config variable: defaultopts");
        throw new BadParameterException(null,$msg);
    }

    if (strval(intval($imggraph)) !== $imggraph || $imggraph < 0 || $imggraph > 3) {
        $msg = xarML("Invalid value for config variable: imggraph");
         throw new BadParameterException(null,$msg);
    }
    if (!(($voteinterval == -1) ||
        ($voteinterval == 86400) ||
        ($voteinterval == 604800) ||
        ($voteinterval == 2592000))) {
        $msg = xarML("Invalid value for config variable: voteinterval");
         throw new BadParameterException(null,$msg);
    }
    if ($previewresults != 1) {
        $previewresults = 0;
    }

    // update the data

    xarModSetVar('polls', 'barscale', $barscale);
    xarModSetVar('polls', 'defaultopts', $defaultopts);
    xarModSetVar('polls', 'imggraph', $imggraph);
    xarModSetVar('polls', 'voteinterval', $voteinterval);
    xarModSetVar('polls', 'previewresults', $previewresults);
    xarModSetVar('polls', 'showtotalvotes', $showtotalvotes);
    xarModSetVar('polls', 'SupportShortURLs', $shorturl);

    xarModCallHooks('module','updateconfig','polls',
                    array('module' => 'polls'));

    xarResponseRedirect(xarModURL('polls', 'admin', 'modifyconfig'));

    // Return
    return true;
}

?>
