<?php

class Inchoo_TicketManager_Model_Resource_Ticket_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('inchoo_ticketmanager/ticket');
    }

    public function addCustomerName()
    {
        $adapter    = $this->getConnection();
        $customer   = Mage::getModel('customer/customer');
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');

        $this->getSelect()
            ->joinLeft(
                array('customer_lastname_table' => $lastname->getBackend()->getTable()),
                $adapter->quoteInto('customer_lastname_table.entity_id=main_table.customer_id
                    AND customer_lastname_table.attribute_id = ?', (int) $lastname->getAttributeId()
                ),
                array('customer_lastname'=>'value')
            )
            ->joinLeft(
                array('customer_firstname_table' => $firstname->getBackend()->getTable()),
                $adapter->quoteInto('customer_firstname_table.entity_id=main_table.customer_id
                    AND customer_firstname_table.attribute_id = ?', (int) $firstname->getAttributeId()
                ),
                array('customer_firstname'=>'value')
            )
            ->columns(new Zend_Db_Expr("CONCAT(customer_firstname_table.value, ' ', customer_lastname_table.value) AS fullname"));

        return $this;
    }

    public function addWebsiteName()
    {
        $this->getSelect()->joinLeft(array('website_name_table' => 'core_website'),
            'main_table.website_id = website_name_table.website_id', array('website_name' => 'website_name_table.name'));

        return $this;
    }

}