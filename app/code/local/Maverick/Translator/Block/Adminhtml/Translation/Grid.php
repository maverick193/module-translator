<?php
/**
 * Maverick_Translator Extension
 *
 * NOTICE OF LICENSE
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @version
 * @category    Maverick
 * @package     Maverick_Translator
 * @author      Mohammed NAHHAS <m.nahhas@live.fr>
 * @copyright   Copyright (c) 2013 Mohammed NAHHAS
 * @licence     OSL - Open Software Licence 3.0
 *
 */

/**
 * Class Maverick_Translator_Block_Adminhtml_Translation_Grid
 * Translation History Grid Block
 */
class Maverick_Translator_Block_Adminhtml_Translation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('maverick_translator_translation_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }
    
    /**
     * Prepare Grid Columns
     * @see Mage_Adminhtml_Block_Widget_Grid::_prepareColumns
     */ 
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'        => Mage::helper('maverick_translator')->__('ID'),
            'index'         => 'entity_id',
            'width'         => 100,
            'sortable'      => true,
        ));

        $this->addColumn('modulename', array(
            'header'    => Mage::helper('maverick_translator')->__('Module Name'),
            'index'     => 'modulename',
            'type'      => 'name',
            'sortable'  => true,
        ));
        
        $this->addColumn('auto_translate', array(
            'header'    => Mage::helper('maverick_translator')->__('Automatic Translation'),
            'index'     => 'auto_translate',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('maverick_translator')->__('No'),
                1 => Mage::helper('maverick_translator')->__('Yes')
            ),
        ));
        $this->addColumn('translator', array(
            'header'    => Mage::helper('maverick_translator')->__('Translator API'),
        	'type'		=> 'options',
            'index'     => 'translator',
        	'options'	=> array('' => Mage::helper('maverick_translator')->__('None')) + Mage::getSingleton('maverick_translator/system_config_translators')->getOptions(),
        ));
        
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('maverick_translator')->__('Created At'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));
        
        $this->addColumn('adminstrator', array(
            'header'    => Mage::helper('maverick_translator')->__('Administrator'),
            'index'     => 'adminstrator',
            'type'      => 'name',
            'sortable'  => true,
        ));
        
        $this->addColumn('integration_report', array(
            'header'    => Mage::helper('maverick_translator')->__('Download'),
            'format'    => '<a href="' . $this->getUrl('*/*/download', array('id' => '$entity_id')) .'">' . Mage::helper('maverick_translator')->__('Download CSV') . '</a>',
            'index'     => 'type',
        	'width'     => '150px',
            'sortable'  => false,
            'filter'    => false
        ));
        
        $this->addColumn('action', array(
        		'header'    => Mage::helper('maverick_translator')->__('Action'),
        		'type'      => 'action',
        		'width'     => '150px',
        		'filter'    => false,
        		'sortable'  => false,
        		'actions'   => array(array(
			        						'url'       => $this->getUrl('*/*/delete', array('entity_id' => '$entity_id')),
			        						'caption'   => Mage::helper('maverick_translator')->__('Delete CSV and history'),
			        						'confirm'   => Mage::helper('maverick_translator')->__('This action will delete the history and the CSV file (if there is any), Are you sure?')
			        				 )
        		),
        		'index'     => 'type',
        		'sortable'  => false
       ));
        
        $this->addExportType('*/*/exportAlertCsv', Mage::helper('maverick_translator')->__('CSV'));
        $this->addExportType('*/*/exportAlertExcel', Mage::helper('maverick_translator')->__('Excel XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * Prepare product stock alert collection
     * @see Mage_Adminhtml_Block_Widget_Grid::_prepareCollection()
     */
    protected function _prepareCollection()
    {
        //$filterData = $this->getFilterData();               
        $collection = Mage::getResourceModel('maverick_translator/translation_collection');
        $this->setCollection($collection);

        parent::_prepareCollection();
    }
    
    /**
     * Return Grid URL for AJAX query
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}