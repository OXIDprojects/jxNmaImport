<?php

/*
 *    This file is part of the module jxNmaImport for OXID eShop Community Edition.
 *
 *    The module jxNmaImport for OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    The module jxNmaImport for OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxNmaImport
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2013
 *
 */
 
class jxnmaimport extends oxAdminView
{
    protected $_sThisTemplate = "jxnmaimport.tpl";

    public function render()
    {
        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);
        
        $myConfig = oxRegistry::get("oxConfig");
        $sDelimeter = $myConfig->getConfigParam("sJxNmaImportDelimeter");
        switch ($sDelimeter) {
            case 'comma':
                $sDeliChar = ',';
                break;
            case 'semicolon':
                $sDeliChar = ';';
                break;
            case 'tabulator':
                $sDeliChar = chr(9);
                break;
            default:
                $sDeliChar = ',';
                break;
        }

        $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );

        $sSql = "DROP TEMPORARY TABLE IF EXISTS tmparticles";
        $rs = $oDb->Execute($sSql);
        $sSql = "CREATE TEMPORARY TABLE tmparticles ( jxartnum VARCHAR(255) )";
        $rs = $oDb->Execute($sSql);
        
        if ($_FILES["uploadfile"]["tmp_name"] != '') {
            $fh = fopen($_FILES["uploadfile"]["tmp_name"],"r");
            while (($aRow = fgetcsv($fh, 1000, $sDeliChar)) !== FALSE) {
                $sSql = "INSERT INTO tmparticles (jxartnum) VALUES ('%{$aRow[0]}%') ";
                $rs = $oDb->Execute($sSql);
            }
            fclose($fh);
        
        
            $sSql = "SHOW TABLES LIKE 'jxinvarticles' ";
            $rs = $oDb->Execute($sSql);
            if (!$rs) {
                $sSql = "SELECT a.oxid, a.oxactive, a.oxartnum, a.oxmpn, IF(a.oxparentid='',a.oxtitle,(SELECT CONCAT(b.oxtitle,', ',a.oxvarselect) FROM oxarticles b WHERE a.oxparentid=b.oxid)) AS oxtitle, a.oxean, a.oxstock, a.oxstockflag, a.oxprice "
                        . "FROM oxarticles a, tmparticles t "
                        . "WHERE (a.oxartnum LIKE t.jxartnum OR a.oxmpn LIKE t.jxartnum) AND a.oxactive = 1 ";
                $oSmarty->assign("bJxInvarticles",FALSE);
            }
            else {
                $sSql = "SELECT a.oxid, a.oxactive, a.oxartnum, a.oxmpn, IF(a.oxparentid='',a.oxtitle,(SELECT CONCAT(b.oxtitle,', ',a.oxvarselect) FROM oxarticles b WHERE a.oxparentid=b.oxid)) AS oxtitle, a.oxean, i.jxinvstock, a.oxstock, a.oxstockflag, a.oxprice "
                        . "FROM oxarticles a "
                        . "INNER JOIN tmparticles t ON (a.oxartnum LIKE t.jxartnum OR a.oxmpn LIKE t.jxartnum) "
                        . "LEFT JOIN (jxinvarticles i) ON (a.oxid = i.jxartid) "
                        . "WHERE a.oxactive = 1 ";
                $oSmarty->assign("bJxInvarticles",TRUE);
            }

            $aArticles = array();
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aArticles, $rs->fields);
                $rs->MoveNext();
            }
        }
        
        $oSmarty->assign("aArticles",$aArticles);

        return $this->_sThisTemplate;
    }


    
    public function deactivateArticles ()
    {
        $aSelOxid = oxConfig::getParameter( "jxnmaimport_oxid" ); 
        
        $sSql = "UPDATE oxarticles SET oxactive=0 WHERE oxid IN('" . implode("','", $aSelOxid) . "') ";
        $oDb = oxDb::getDb();
        $ret = $oDb->Execute($sSql);

        return;
    }

}
?>