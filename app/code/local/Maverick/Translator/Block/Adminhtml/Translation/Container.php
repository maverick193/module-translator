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
 * Class Maverick_Translator_Block_Adminhtml_Translation_Container
 * Main Block Container
 *
 */
class Maverick_Translator_Block_Adminhtml_Translation_Container extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'maverick_translator';
        $this->_controller = 'adminhtml_translation';
        $this->_headerText = Mage::helper('maverick_translator')->__('Module Translator');

        parent::__construct();

        $this->_removeButton('add');
        $this->addButton('translation_form_submit', array(
            'label'     => Mage::helper('maverick_translator')->__('Create CSV'),
            'onclick'   => 'TranslationFormSubmit()'
        ));
    }

    /**
     * Return Filter Url
     */
    public function getFilterUrl()
    {
        $this->getRequest()->setParam('data', null);
        return $this->getUrl('*/*/create', array('_current' => true));
    }
}