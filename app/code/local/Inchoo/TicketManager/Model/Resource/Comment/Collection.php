<?php

class Inchoo_TicketManager_Model_Resource_Comment_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('inchoo_ticketmanager/comment');
    }

    public function addCustomerName()
    {
        $adapter    = $this->getConnection();
        $customer   = Mage::getModel('customer/customer');
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');
        $middlename = $customer->getAttribute('middlename');

        $this->getSelect()
            ->joinLeft(
                array('customer_lastname_table' => $lastname->getBackend()->getTable()),
                $adapter->quoteInto('customer_lastname_table.entity_id=main_table.author_id
                    AND customer_lastname_table.attribute_id = ?', (int) $lastname->getAttributeId()
                ),
                array('customer_lastname'=>'value')
            )
            ->joinLeft(
                array('customer_middlename_table' => $middlename->getBackend()->getTable()),
                $adapter->quoteInto('customer_middlename_table.entity_id=main_table.author_id
                    AND customer_middlename_table.attribute_id = ?', (int) $middlename->getAttributeId()
                ),
                array('customer_middlename'=>'value')
            )
            ->joinLeft(
                array('customer_firstname_table' => $firstname->getBackend()->getTable()),
                $adapter->quoteInto('customer_firstname_table.entity_id=main_table.author_id
                    AND customer_firstname_table.attribute_id = ?', (int) $firstname->getAttributeId()
                ),
                array('customer_firstname'=>'value')
            );

        return $this;
    }
}