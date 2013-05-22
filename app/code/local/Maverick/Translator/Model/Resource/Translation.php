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
 * Translation Resource Model
 */
class Maverick_Translator_Model_Resource_Translation extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * 'entity_id' is the FK of "maverick_dev_csv_translation" table
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     */
    protected function _construct()
    {
        $this->_init('maverick_translator/translation','entity_id');
    }

    /**
     * set created at and updated at dates
     * @see Mage_Core_Model_Resource_Db_Abstract::_beforeSave()
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (! $object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }
}