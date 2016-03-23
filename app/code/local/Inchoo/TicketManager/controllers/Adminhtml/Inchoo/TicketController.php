<?php

class Inchoo_TicketManager_Adminhtml_Inchoo_TicketController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('report/inchoo_ticketmanager');
        $this->_addContent($this->getLayout()->createBlock('inchoo_ticketmanager/adminhtml_edit'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('inchoo_ticketmanager/adminhtml_edit_grid')->toHtml());
    }

    public function detailsAction($id)
    {
        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('ticket');
        }
        $this->getLayout()->getBlock('head')->setTitle($this->__('Ticket details'));
        $this->renderLayout();
    }

    public function commentPostAction()
    {
        if(!$this->_validateFormKey()) {
            return $this->_redirect('*/*/index');
        }

        $session = $this->_getSession();

        if($this->getRequest()->isPost()) {

            $post = $this->getRequest()->getPost();

            if(!$post) {
                return $this->_redirect('*/*/index');
            }

            try {
                $error = false;

                if (!Zend_Validate::is(trim($post['message']) , 'NotEmpty')) {
                    $error = true;
                }

                if($error) {
                    $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later.'));
                    return $this->_redirect('/');
                }

                $admin_id = Mage::getSingleton('admin/session')->getId();

                $comment = Mage::getModel('inchoo_ticketmanager/comment');
                $comment->setTicket_id($post['ticket_id']);
                $comment->setAuthor_id($admin_id);
                $comment->setMessage($post['message']);
                $comment->setCreated_at(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
                $comment->save();


                $session->addSuccess(Mage::helper('contacts')->__('The comment has been posted successfully!.'));
                return $this->_redirect('*/*/details/ticket_id/'.$post['ticket_id']);

            } catch(Exception $e) {
                $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later.'));
                return $this->_redirect('*/*/details/ticket_id/'.$post['ticket_id']);
            }
        }

        $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later.'));
        return $this->_redirect('*/*/index');
    }

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
            return $this->_redirect('*/*/');
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
            $ticket->setStatus($ticket->getStatus()^1);
            $ticket->save();

            $session->addSuccess(Mage::helper('contacts')->__('Ticket status successfully changed.'));
            $this->_redirect('*/*/details/ticket_id/'.$post['ticket_id']);


        } catch(Exception $e) {
            $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
            return $this->_redirect('/');
        }

    }

}