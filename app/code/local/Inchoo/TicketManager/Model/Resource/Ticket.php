<?php

class Inchoo_TicketManager_Model_Resource_Ticket extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('inchoo_ticketmanager/tickets', 'ticket_id');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->joinLeft(array('website_name_table' => 'core_website'), $this->getMainTable().'.website_id = website_name_table.website_id',
            array('website_name' => 'website_name_table.name'));

        return $select;
    }
}