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
 * Helper Data
 */

class Maverick_Translator_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Write CSV File
     * @param string $path
     * @param string $filename
     * @param array $data
     * @return array
     */
    public function writeCSV($path, $filename, $data)
	{
        $errors = array();
		if($this->createDir($path)) {
			$csv = new Varien_File_Csv(); 
			$csv->saveData($path . $filename, $data);
		} else {
            $errors[] = $this->__('A problem was encountered while creating translation CSV file, please check your media directory permissions ');
        }
		
		return $errors;
	}
	
    /**
     *
     * Create and Set Permission directory
     * @param string $path
     * @return string
     */
    public function createDir($path) {
        if(!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        return is_dir($path);
    }

    /**
     * Get current administrator full name
     * @return string
     */
    public function getAdminFullName()
    {
    	$currentUser = Mage::getSingleton('admin/session')->getUser();
    	if($currentUser->getId()) {
    		return $currentUser->getFirstname() . ' ' . $currentUser->getLastname();
    	}
    	
    	return $this->__('Unknown');
    }
}