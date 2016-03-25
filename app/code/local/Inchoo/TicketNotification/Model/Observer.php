<?php

class Inchoo_TicketNotification_Model_Observer
{
    public function newTicket($observer)
    {
        $object = $observer->getEvent()->getDataObject();

        if($object->isObjectNew())
        {
            try {

                $helper = Mage::helper('inchoo_ticketnotification');
                $storeId = Mage::app()->getStore()->getId();

                if($helper->isModuleEnabled('Inchoo_TicketNotification', $storeId)) // beton?
                {
                    $emailInfo = Mage::getModel('core/email_info');
                    $mailer = Mage::getModel('core/email_template_mailer');

                    $adminMail = Mage::getStoreConfig('trans_email/ident_general/email');
                    $adminName = Mage::getStoreConfig('trans_email/ident_general/name');
                    $emailInfo->addTo($adminMail, $adminName);
                    $mailer->addEmailInfo($emailInfo);

                    $mailer->setSender($adminMail);
                    $mailer->setStoreId($storeId);
                    $template = $helper->getEmailTemplate($storeId);
                    $mailer->setTemplateId($template);
                    $mailer->setTemplateParams(array(
                        'ticket' => $object->getData(),
                        'recipient' => $adminName,
                    ));

                    $mailer->send();
                }

            } catch(Exception $e) {
                Mage::log($e);
            }
        }
    }
}