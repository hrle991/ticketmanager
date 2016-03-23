<?php

class Inchoo_TicketManager_Block_Adminhtml_Edit_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        if($value == 0)
        {
            return 'Closed';
        } else {
            return 'Open';
        }
    }

}
