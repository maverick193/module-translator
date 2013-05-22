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
 * Translation Model Class
 */

class Maverick_Translator_Model_Translation extends Mage_Core_Model_Abstract
{
    /**
     * Bind class to resource model
     * @see Varien_Object::_construct()
     */
    protected function _construct()
    {
        $this->_init('maverick_translator/translation');
    }

    /**
     * Check if file exists
     * @return bool
     */
    public function fileExists() {
    	return is_file($this->getPath() . $this->getFilename());
    }

    public function output() {
    	if (!$this->fileExists()) {
    		return ;
    	}
    
    	$ioAdapter = new Varien_Io_File();
    	$ioAdapter->open(array('path' => $this->getPath()));
    
    	$ioAdapter->streamOpen($this->getFilename(), 'r');
    	while ($buffer = $ioAdapter->streamRead()) {
    		echo $buffer;
    	}
    	$ioAdapter->streamClose();
    }

    /**
     * Delete file
     * @return mixed bool || Maverick_Translator_Model_Translation
     */
    public function deleteFile() {
    	if (!$this->fileExists()) {
    		return false;
    	}
    
    	$ioProxy = new Varien_Io_File();
    	$ioProxy->open(array('path'=> $this->getPath()));
    	$ioProxy->rm($this->getPath() . $this->getFilename());
    	return $this;
    }
}