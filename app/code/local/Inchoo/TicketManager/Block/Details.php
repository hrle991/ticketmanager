<?php

class Inchoo_TicketManager_Block_Details extends Mage_Core_Block_Template
{
    protected $_ticket_id;
    protected $_comments;
    protected $_details;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tickets/details.phtml');
        $this->_ticket_id = $this->getRequest()->getParam('ticket_id');

    }

    public function getTicketDetails()
    {
        return $this->_ticketDetails();
    }

    public function getTicketComments()
    {
        return $this->_ticketComments();
    }

    public function countComments()
    {
        if(!$this->_comments) {
            $this->_ticketComments();
        }

        return count($this->_comments);
    }

    protected function _ticketDetails()
    {
        if(empty($this->_details)) {
            $details = Mage::getModel('inchoo_ticketmanager/ticket')->load($this->_ticket_id);
            $this->_details = $details;
        }

        return $this->_details;
    }

    protected function _ticketComments() {

        if(empty($this->_comments)) {
            $comments = Mage::getResourceModel('inchoo_ticketmanager/comment_collection')->addCustomerName()
                ->addFieldToFilter('ticket_id', $this->_ticket_id);

            $this->_comments = $comments->load();
        }

        return $this->_comments;
    }

    public function getSaveCommentUrl()
    {
        return $this->getUrl('ticket/comment/commentpost');
    }

    public function getCloseTicketUrl()
    {
        return $this->getUrl('ticket/index/closeTicket');
    }

}