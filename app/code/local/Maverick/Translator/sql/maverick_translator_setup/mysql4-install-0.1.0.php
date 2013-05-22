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
 * Install Script
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * drop table 'maverick_translator_csv_translation' if it exists
 */
$installer->getConnection()->dropTable($installer->getTable('maverick_translator/translation'));

/**
 * Create table 'maverick_translator_csv_translation'
 */
$table = $installer->getConnection()
            ->newTable($installer->getTable('maverick_translator/translation'))
            ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
            ), 'Id')
            ->addColumn('modulename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                'nullable'	=> false,
            ), 'Module Name')
            ->addColumn('adminstrator', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                'nullable'	=> false,
            ), 'Administrator Name')
            ->addColumn('auto_translate', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => 0
            ), 'Translation Automatic')
            ->addColumn('translator', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                'nullable'	=> false,
            ), 'Translator Name')
            ->addColumn('filename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                'nullable'	=> false,
            ), 'File Name')
            ->addColumn('path', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
                'nullable'	=> false,
            ), 'Path To File')
            ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
                'nullable'	=> false,
            ), 'Created At')
            ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
                'nullable'	=> false,
            ), 'Updated At')
            ->setComment('CSV Translation History');

$installer->getConnection()->createTable($table);

$installer->endSetup();