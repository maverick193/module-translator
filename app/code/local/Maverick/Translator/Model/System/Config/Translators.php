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
 * Return Available Translators
 */
class Maverick_Translator_Model_System_Config_Translators extends Mage_Core_Model_Abstract
{
    protected $_options;
    const GOOGLE_API 	= 'google_api';
    const BING_API		= 'bing_api';


    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
        	$res = array();
        	foreach ($this->getOptions() as $value => $label) {
        		$res[] = array('value' => $value, 'label' => $label);
        	}
        	
        	array_unshift($res, array('value'=> '', 'label'=> Mage::helper('maverick_translator')->__('-- Please Select --')));
        	$this->_options = $res;
        }
        
        return $this->_options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function getOptions()
    {
    	return array(	self::GOOGLE_API => Mage::helper('maverick_translator')->__('Google Translation API'),
    				   	self::BING_API   => Mage::helper('maverick_translator')->__('Bing Translation API')
    			);
    }

}