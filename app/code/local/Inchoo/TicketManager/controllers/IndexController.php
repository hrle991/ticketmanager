<?php

class Inchoo_TicketManager_IndexController extends Mage_Core_Controller_Front_Action
{

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticket');
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Tickets'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('form');
    }

    public function formAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticket');
        }
        $this->renderLayout();
    }

    public function viewAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');

        $ticket = Mage::getModel('inchoo_ticketmanager/ticket')->load($this->getRequest()->getParam('ticket_id'));

        Mage::register('current_ticket', $ticket); // TO DO (block)

        $author_id = $this->_getSession()->getCustomer()->getId();

        if(!$ticket->getId()) {
            $this->_getSession()->addError($this->__('This ticket no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }

        if($ticket->getCustomer_id() != $author_id)
        {
            $this->_getSession()->addError($this->__('You\'re not authorize to comment this ticket.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }

        if ($navigationBlock) {
            $navigationBlock->setActive('ticket');
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Ticket details'));
        $this->renderLayout();
    }

    // new ticket post method
    public function formPostAction()
    {
        if(!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }

        $session = $this->_getSession();

        if($this->getRequest()->isPost()) {

            $post = $this->getRequest()->getPost();

            if(!$post) {
                return $this->_redirect('*/*/new');
            }

            try {
                $error = false;

                if (!Zend_Validate::is(trim($post['subject']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['message']) , 'NotEmpty')) {
                    $error = true;
                }

                if($error) {
                    $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
                    return $this->_redirect('/');
                }

                $website_id = Mage::app()->getWebsite()->getId();
                $ticket = Mage::getModel('inchoo_ticketmanager/ticket');
                $ticket->setCustomer_id($session->getCustomer()->getId());
                $ticket->setWebsite_id($website_id);
                $ticket->setSubject($post['subject']);
                $ticket->setMessage($post['message']);
                $ticket->setCreated_at(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
                $ticket->save();


                $session->addSuccess(Mage::helper('contacts')->__('Your ticket was submitted. We will get in touch with you as soon as possible.'));
                $this->_redirect('*/*/view/ticket_id/'.$ticket->getId());

            } catch(Exception $e) {
                $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
                return $this->_redirect('/');
            }
        } else {
            return $this->_redirect('*/*/new');
        }
    }

    // change ticket status
    public function closeTicketAction()
    {
        if(!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }

        if(!$this->getRequest()->isPost()) {
            return $this->_redirect('*/*/');
        }

        $post = $this->getRequest()->getPost();

        if(!$post) {
            return $this->_redirect('*/*/new');
        }

        $session = $this->_getSession();

        try {
            $error = false;

            if (!Zend_Validate::is(trim($post['ticket_id']) , 'NotEmpty')) {
                $error = true;
            }

            if($error) {
                $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
                return $this->_redirect('/');
            }

            $ticket = Mage::getModel('inchoo_ticketmanager/ticket')->load($post['ticket_id']);

            if($ticket->getCustomer_id() != $session->getCustomer()->getId())
            {
                $this->_getSession()->addError($this->__('You\'re not authorize to change this ticket status.'));
                $this->_redirect('*/*/');
                $this->setFlag('', self::FLAG_NO_DISPATCH, true);
                return false;
            }

            $ticket->setStatus($ticket->getStatus()^1);
            $ticket->save();

            $session->addSuccess(Mage::helper('contacts')->__('Ticket status successfully changed.'));
            $this->_redirect('*/*/view/ticket_id/'.$post['ticket_id']);


        } catch(Exception $e) {
            $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
            return $this->_redirect('/');
        }

    }

}