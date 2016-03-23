<?php

class Inchoo_TicketManager_Block_Ticket extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tickets/mytickets.phtml');
        $tickets = Mage::getResourceModel('inchoo_ticketmanager/ticket_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addFieldToFilter('website_id', Mage::app()->getWebsite()->getId())
            ->setOrder('created_at', 'desc');

        $this->setTickets($tickets);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'inchoo.ticketmanager.ticket.edit')
            ->setCollection($this->getTickets());
        $this->setChild('pager', $pager);
        $this->getTickets()->load();
        return $this;
    }

    public function getAddTicketUrl()
    {
        return $this->getUrl('*/*/new', array('_secure'=>true));
    }

    public function getViewUrl($ticket)
    {
        return $this->getUrl('*/*/view', array('ticket_id' => $ticket->getId()));
    }

}