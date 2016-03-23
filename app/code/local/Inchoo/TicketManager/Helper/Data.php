<?php

class Inchoo_TicketManager_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function formatDate($date)
    {
        return date("F j, Y, g:i a", strtotime($date));
    }

    public function getStatus(DataObject $row)
    {
        if($this->_getValue($row) == 1)
        {
            return 'Open';
        } else
        {
            return 'Closed';
        }
    }
}