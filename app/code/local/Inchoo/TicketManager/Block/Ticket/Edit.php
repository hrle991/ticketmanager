<?php

class Inchoo_TicketManager_Block_Ticket_Edit extends Mage_Directory_Block_Data
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('inchoo_ticketmanager')->__('Create new ticket'));
    }

    public function getSaveUrl()
    {
        return Mage::getUrl('*/*/formPost', array('_secure' => true));
    }
}