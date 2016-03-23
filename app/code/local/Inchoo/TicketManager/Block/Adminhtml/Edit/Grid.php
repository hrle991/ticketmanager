<?php

class Inchoo_TicketManager_Block_Adminhtml_Edit_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('inchoo_ticketmanager');
        $this->setDefaultSort('created_at');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('inchoo_ticketmanager/ticket')->getCollection()->addCustomerName();
        $collection->addWebsiteName();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('ticket_id', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('ID'),
            'sortable' => true,
            'index' => 'ticket_id',
            'width' => '20px',
        ));

        $this->addColumn('customer', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('Customer'),
            'index' => 'customer_id',
            'format' => '$fullname',
        ));

        $this->addColumn('website_id', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('Website'),
            'index' => 'website_id',
            'format' => '$website_name',
        ));

        $this->addColumn('subject', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('Subject'),
            'index' => 'subject',
            'type' => 'text',
            'escape' => true,
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('Status'),
            'index' => 'status',
            'renderer' => 'Inchoo_TicketManager_Block_Adminhtml_Edit_Status',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('inchoo_ticketmanager')->__('Created at'),
            'index' => 'created_at',
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($item)
    {
        return $this->getUrl('*/*/details', array(
            'ticket_id' => $item->getId(),
        ));
    }



}