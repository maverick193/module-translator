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
 * Return Available Modules
 */
class Maverick_Translator_Model_System_Config_Modules extends Mage_Core_Model_Abstract
{
    protected $_options;

    /**
     * Options getter
     *
     * @param bool $empty
     * @return array
     */
    public function toOptionArray($empty = false)
    {
        if (!$this->_options) {
            $modules    = array_keys((array)Mage::getConfig()->getNode('modules')->children());
            $modulesObj = new Varien_Object($modules);          
            $modules    = $modulesObj->toArray();
            $res        = array();
            
            sort($modules);

            foreach ($modules as $moduleName) {
                $res[] = array('value' => $moduleName, 'label' => $moduleName);
            }
            
            if($empty) {
                array_unshift($res, array('value'=> '', 'label'=> Mage::helper('maverick_translator')->__('-- Please Select --')));
            }
            $this->_options = $res;
        }
        
        return $this->_options;
    }
}