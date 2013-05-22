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
 * Translation Helper
 */

class Maverick_Translator_Helper_Translation extends Mage_Core_Helper_Data
{
    protected $_sentences = array();
    
    /**
     * Get all words and sentences which need to be translated
     * 
     * @param string $modulename
     * @return array $sentences
     */
    public function getSentencesToTranslate($modulename)
    {
        $config    	= Mage::getConfig();
        $codePool  	= (string)$config->getModuleConfig($modulename)->codePool;        
        $moduleDir 	= $config->getOptions()->getCodeDir() . DS . $codePool . DS . uc_words($modulename, DS);
        $xmlFiles	= array('config.xml', 'system.xml', 'adminhtml.xml');
        
        $this->recursiveSearch($moduleDir, $xmlFiles);
        
        // Translate system.xml, adminhtml.xml
        $this->getSentencesFromXML($moduleDir, $xmlFiles);

        // Translate layouts and templates
        //$this->getSentencesFromLayout($moduleDir);

        $sentences = $this->prepareSentences();
        
        return $sentences;
        
    }

    /**
     * Browse recursivly module directories to retrieve words and sentences
     *
     * @param string $src
     * @param array $except
     * @internal param string $string
     */
    public function recursiveSearch($src, $except = array())
    {
        $dir = opendir($src);
        $res = array();
        
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..') && !in_array($file, $except)) {
                 if (is_dir($src . DS . $file)) {
                     $this->recursiveSearch($src . DS . $file, $except);
                 } else {
                 	$this->getStringToTranslate(file_get_contents($src . DS . $file));
                 }
            }
        }
        
        closedir($dir);
    }
    
    /**
     * Extract words and sentences from $string
     * 
     * @param unknown $string
     * @return void
     */
    public function getStringToTranslate($string) 
    {
        $translations = array();
        
        preg_match_all('#(?:__|translate)\(((?:"([^"\\\\]*(?:\\\\.[^"\\\\]*)*(?![^\\\\]\\\\))")|(?:\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*(?![^\\\\]\\\\))\'))#', $string, $translations);
        $translations = $translations[1];

        if (count($translations) > 0) {

            foreach ($translations as $translation) {
                $translation = substr($translation, 1, -1);
                $translation = str_replace(array('\\\'','\\\"'), array('\'','\"'), $translation);
                if (!in_array($translation, $this->_sentences)) {
                    $this->_sentences[] = $translation;
                }
            }
        }
    }
        
    /**
     * Prepare words as array to write them in CSV File
     * using Varien_File_Csv
     * 
     * @return array
     */
    public function prepareSentences()
    {
    	$sentences 			= array_unique($this->_sentences);
    	$formatedSentences 	= array();
    	
    	foreach ($sentences as $sentence) {
    		$formatedSentences[] = array($sentence, '');
    	}
    	return $formatedSentences;
    }
       
    /**
     * Retrieve Words and sentences to translate from XML files
     * 
     * @param string $moduleDir
     * @param array $xmlFiles
     */
    function getSentencesFromXML($moduleDir, $xmlFiles) 
    {    
    	foreach ($xmlFiles as $xml) {
    		$file 	= $moduleDir . DS . 'etc' . DS . $xml;    		
    		if(!is_file($file)) {
    			continue;
    		}
    		
			//@todo
            $xslDoc     = new DOMDocument();
            $xslFile    = Mage::getModuleDir('etc', 'Maverick_Translator') . DS . 'translate.xsl';
            // use § as separator
            $xslDoc->load($xslFile);
            $xmlDoc     = new DOMDocument();
            $xmlDoc->load($file);
            $processor  = new XSLTProcessor();
            $processor->importStylesheet($xslDoc);

            $translations = $processor->transformToXML($xmlDoc);

            if (strlen($translations) > 5) {

                $translations = explode('§', substr(trim($translations), 0, -2));

                foreach ($translations as $translation) {
                        //$translation = html_entity_decode($translation);
                        if (!in_array($translation, $this->_sentences)) {
                            $this->_sentences[] = $translation;
                        }
                }
            }
    	}   
    }
    
    /**
     * Call Translator API
     *
     * @param string $translator
     * @param array  $sentences
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function translate($translator, $sentences, $from, $to)
    {
    	$translatorName = current(explode('_', $translator));
    	$helper 		= Mage::helper('maverick_translator/translator_' . $translatorName);
    	$res 			= false;
    
    	if (is_object($helper) && method_exists($helper, $translator)) {
    		$res = $helper->$translator($sentences, $from, $to);
    	}
    	return $res;
    }
    
    /**
     * Check Translator Configuration
     * 
     * @param string $translator
     * @return bool
     */
    public function checkTranslatorConfig($translator)
    {
    	$conf 			= false;
    	$translatorName = current(explode('_', $translator));
    	$helper 		= Mage::helper('maverick_translator/translator_' . $translatorName);
    	
    	if (is_object($helper) && method_exists($helper, 'checkConf')) {
    		$conf = Mage::helper('maverick_translator/translator_' . $translatorName)->checkConf();
    	}
    	return $conf;
    }

    public function getSentencesFromLayout($moduleDir)
    {
        $configXml  = $moduleDir . DS . 'etc' . DS . 'config.xml';

        if (is_file($configXml)) {
            $files = $this->_searchLayoutInConfig($configXml);


            // Translate Layouts if there is any
            if (!empty($files)) {
                $obj        = Mage::getModel('core/config_base');
                $layoutFile = Mage::getModel('core/design_package')->getFilename($files['frontend'], array('_type' => 'layout'));
                if (is_file($layoutFile)) {
                    $xml = $obj->loadFile($layoutFile)->getNode();
                    unset($xml['@attributes']);
                }

                $adminLayoutFile = Mage::getBaseDir('design') . DS . 'adminhtml' . DS . 'default' . DS . 'default' . DS . 'layout' . DS . $files['adminhtml'];
                if (isset($files['adminhtml']) && is_file($adminLayoutFile)) {
                    $obj->loadFile($adminLayoutFile);
                }
            }
        }
    }

    protected function _searchLayoutInConfig($configXml)
    {
        $files = array();
        $configBase = Mage::getModel('core/config_base');

        if ($configBase->loadFile($configXml)) {
            // Frontend Layout
            if ($configBase->getNode('frontend/layout/updates')) {
                $layouts = (array) $configBase->getNode('frontend/layout/updates');
                foreach ($layouts as $layout) {
                    $layout = (array)$layout;
                    if (isset($layout['file'])) {
                        $files['frontend'] = $layout['file'];
                    }
                }
            }

            // Adminhtml Layout
            if ($configBase->getNode('adminhtml/layout/updates')) {
                $layouts = (array) $configBase->getNode('frontend/layout/updates');
                foreach ($layouts as $layout) {
                    $layout = (array)$layout;
                    if (isset($layout['file'])) {
                        $files['adminhtml'] = $layout['file'];
                    }
                }
            }
        }

        return $files;
    }
}