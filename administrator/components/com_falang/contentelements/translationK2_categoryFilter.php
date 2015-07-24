<?php
/**
 * @package     Falang for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013 Faboba. All rights reserved.
 */

// Don't allow direct linking
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

class translationK2_categoryFilter extends translationFilter {
    function translationK2_categoryFilter($contentElement) {
        $this->filterNullValue = -1;
        $this->filterType = "catid";
        $this->filterField = $contentElement->getFilter("K2_category");
        parent::translationFilter($contentElement);
    }
    
    function _createFilter() {
        $database = JFactory::getDBO();
        if (!$this->filterField)
            return "";
        $filter = "";

        //since joomla 3.0 filter_value can be '' too not only filterNullValue
        if (isset($this->filter_value) && strlen($this->filter_value) > 0  && $this->filter_value!=$this->filterNullValue){
            $sql = "SELECT tab.id FROM #__k2_items as tab WHERE tab.catid=$this->filter_value";
            $database->setQuery($sql);
            $ids = $database->loadObjectList();
            $idstring = "";
            foreach ($ids as $pid) {
                if (strlen($idstring) > 0)
                    $idstring .= ",";
                $idstring .= $pid->id;
            }
			if (strlen($idstring) > 0) {
              $filter = "c.id IN($idstring)";
			} else {
			  //fake filter to remove all items
              $filter = "c.id IN(0)";
			}
        }
        return $filter;
    }
    
    function _createfilterHTML() {
        if (!$this->filterField) return "";

        $db = JFactory::getDBO();
        $categoryOptions = array();

        if (!FALANG_J30) {
            $categoryOptions[] = JHTML::_('select.option', '-1', JText::_('K2_SELECT_CATEGORY'));
        }

        $sql = "SELECT DISTINCT p.* FROM #__k2_categories as p WHERE p.trash = 0 ORDER BY p.parent, p.ordering";
        $db->setQuery($sql);
        $cats = $db->loadObjectList();

        if ($cats)
        {
            foreach ($cats as $v)
            {
                if (K2_JVERSION != '15')
                {
                    $v->title = $v->name;
                    $v->parent_id = $v->parent;
                }
                $pt = $v->parent;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

        foreach ($list as $item) {
            $item->treename = JString::str_ireplace('&#160;', ' -', $item->treename);
            $categoryOptions[] = JHTML::_('select.option', $item->id, $item->treename);
        }
        $catnameList = array();

        if (FALANG_J30) {
            $catnameList["title"] = JText::_('COM_FALANG_SELECT_CATEGORY');
            $catnameList["position"] = 'sidebar';
            $catnameList["name"]= 'catid_filter_value';
            $catnameList["type"]= 'catid';
            $catnameList["options"] = $categoryOptions;
            $catnameList["html"] = JHTML::_('select.genericlist', $categoryOptions, 'catid_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value );

        } else {
            $catnameList["title"] = JText::_('K2_CATEGORIES');
            $catnameList["html"] = JHTML::_('select.genericlist', $categoryOptions, 'catid_filter_value', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->filter_value);
        }
        return $catnameList;
    }
}
?>
