<?php

class Inchoo_TicketNotification_Helper_Data extends Mage_Core_Helper_Abstract
{
    CONST XML_PATH_ACTIVE = 'tickets/inchoo_ticketnotification/enable';
    CONST XML_PATH_EMAIL_TEMPLATE = 'tickets/inchoo_ticketnotification/ticket_email_template';

    public function isModuleEnabled($moduleName = null, $storeId)
    {
        if((int)Mage::getStoreConfig(self::XML_PATH_ACTIVE, $storeId) != 1)
        {
            return false;
        }

        return parent::isModuleEnabled($moduleName);
    }

    public function getEmailTemplate($storeId)
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
    }

}