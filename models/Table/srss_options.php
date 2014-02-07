<?php
class Table_srss_options extends Omeka_Db_Table
{

    public function findSrss_optionsByItem($item)
    {
    
        $db = get_db();
        
        if (($item instanceof Item) && !$item->exists()) {
            return array();
        } else if (is_array($item) && !count($item)) {
            return array();
        }
        
        $alias = $this->getTableAlias();
        
        // Create a SELECT statement for the srss_options table
        $select = $db->select()->from(array($alias => $db->srss_options), "$alias.*");
        
        if (is_array($item)) {
            $itemIds = array();
            foreach ($item as $it) {
                $itemIds[] = (int)(($it instanceof Item) ? $it->id : $it);
            }
            $select->where("$alias.item_id IN (?)", $itemIds);
        } else {
            $itemId = (int)(($item instanceof Item) ? $item->id : $item);
            $select->where("$alias.item_id = ?", $itemId);
        }
        $srss_options = $this->fetchObjects($select);
        
        $ft=$_POST['fieldtrip_feed'];
        $srss_options->setPostData($ft);
        return current($srss_options);
  
    }
    
}