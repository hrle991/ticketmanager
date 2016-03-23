<?php

class Inchoo_TicketManager_CommentController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function commentPostAction()
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

                if (!Zend_Validate::is(trim($post['message']) , 'NotEmpty')) {
                    $error = true;
                }

                if($error) {
                    $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
                    return $this->_redirect('/');
                }

                $author_id = $this->_getSession()->getCustomer()->getId();

                $ticket = Mage::getModel('inchoo_ticketmanager/ticket')->load($post['ticket_id']);

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

                $comment = Mage::getModel('inchoo_ticketmanager/comment');
                $comment->setTicket_id($post['ticket_id']);
                $comment->setAuthor_id($author_id);
                $comment->setMessage($post['message']);
                $comment->setCreated_at(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
                $comment->save();


                $session->addSuccess(Mage::helper('contacts')->__('Your ticket was submitted. We will get in touch with you as soon as possible.'));
                return $this->_redirect('ticket/index/view/ticket_id/'.$post['ticket_id']);

            } catch(Exception $e) {
                $session->addError(Mage::helper('inchoo_ticketmanager')->__('Unable to submit your request. Please, try again later'));
                return $this->_redirect('/');
            }
        }

        return $this->_redirect('*/*/new');
    }
}