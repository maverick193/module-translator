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
 * Class Maverick_Translator_Block_Adminhtml_Translation_Create_Form
 * Translator Form
 */
class Maverick_Translator_Block_Adminhtml_Translation_Create_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/create');
        $form = new Varien_Data_Form(
            array('id' => 'translation_form', 'action' => $actionUrl, 'method' => 'get')
        );
        
        $htmlIdPrefix = 'maverick_translator_translation';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('maverick_translator')->__('Create New CSV Translation')));

        $fieldset->addField('modulename', 'select', array(
            'name'      => 'modulename',
            'values'    => Mage::getSingleton('maverick_translator/system_config_modules')->toOptionArray(true),
            'required'  => true,
            'label'     => Mage::helper('maverick_translator')->__('Module')
        ));
        
        $fieldset->addField('from_language', 'select', array(
            'name'      => 'from_language',
            'values'    => Mage::getSingleton('maverick_translator/system_config_languages')->toOptionArray(true),
        	'required'  => true,
            'label'     => Mage::helper('maverick_translator')->__('From Language')
        ));
        
        $fieldset->addField('to_language', 'select', array(
            'name'      => 'to_language',
            'values'    => Mage::getSingleton('maverick_translator/system_config_languages')->toOptionArray(true),
            'required'  => true,
            'label'     => Mage::helper('maverick_translator')->__('To Language')
        ));
                
        $fieldset->addField('auto_translators', 'select', array(
            'name'      => 'auto_translators',
            'values'    => Mage::getSingleton('maverick_translator/system_config_translators')->toOptionArray(true),
            'label'     => Mage::helper('maverick_translator')->__('Translate Sentences Using')
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}