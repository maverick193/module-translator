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
 * Translation Main Controller
 */
class Maverick_Translator_Adminhtml_Maverick_TranslationController extends Mage_Adminhtml_Controller_Action
{
	/** Translation CSV path **/
	protected $_translation_path = 'maverick/translator/translation/';
	
    /**
     * Check ACL permissions
     * Check current user permission on resource and privilege
     * 
     * @return  boolean
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('maverick/translator');
    }
    
    /**
     *
     * Load layout and add breadcrumbs to the page
     *
     * @return Maverick_Translator_Adminhtml_Maverick_TranslationController
     */
    public function _initAction()
    {
        $this->loadLayout()
             ->_addBreadcrumb(Mage::helper('maverick_translator')->__('Maverick'), Mage::helper('maverick_translator')->__('Maverick'))
             ->_addBreadcrumb(Mage::helper('maverick_translator')->__('Translator'), Mage::helper('maverick_translator')->__('Translator'));
        return $this;
    }

    /**
     *
     * Init translation
     *
     * @param $requestData
     * @return Maverick_Translator_Adminhtml_Maverick_TranslationController
     */
    public function _initTranslationAction($requestData)
    {
        $translationFormBlock = $this->getLayout()->getBlock('create.translation.form');
        $params               = new Varien_Object();
    
        foreach ($requestData as $key => $value) {
            if (!empty($value)) {
                $params->setData($key, $value);
            }
        }
        
        $translationFormBlock->setCSVData($params); 
        return $this;
    }
    
    /**
     *
     * Display main translation page
     */
    public function indexAction()
    {
        $this->_title($this->__('Maverick'))->_title($this->__('Translator'));
    
        $this->_initAction()
             ->_setActiveMenu('maverick/translator')
             ->_addBreadcrumb($this->__('Maverick'), $this->__('Maverick'))
             ->_addBreadcrumb($this->__('Translator'), $this->__('Translator'));
    
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('data'));
        $this->_initTranslationAction($requestData);
    
        $this->renderLayout();
    }

    /**
     * Create CSV translation file
     */
    public function createAction()
    {
        if($requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('data'))) {
            try {
            	$helper = Mage::helper('maverick_translator/translation');
            	
            	//Check Data
                $this->_checkData($requestData);
                
                //Get words to translate
                $sentences 		= $helper->getSentencesToTranslate($requestData['modulename']);
                
                $autoTranslate 	= 0;
                $translator    	= '';
                
                if(isset($requestData['auto_translators']) && !empty($sentences)) {
                    //@todo set $autoTranslate var using boolean
                	$autoTranslate 	= 1;

                	$translator 	= $requestData['auto_translators'];
                	//check translator configuration
                	if ($helper->checkTranslatorConfig($translator)) {
                    	$sentences 	= $helper->translate($translator, $sentences, $requestData['from_language'], $requestData['to_language']);
                	} else {
                		$this->_getSession()->addError(Mage::helper('maverick_translator')->__('%s translator is not correctly configured, or not yet implemented', $translator));
                	}
                }
                
                $csvPath = Mage::getBaseDir('media') . DS . $this->_translation_path . $requestData['to_language'] . DS;
                $filename = $requestData['modulename'] . '.csv';

                $errors = Mage::helper('maverick_translator')->writeCSV($csvPath, $filename, $sentences);
                                
                if(!empty($errors)) {
                    foreach ($errors as $error) {
                        $this->_getSession()->addError($error);
                    }
                } else {
                    //@todo move history save to Model
                    $history = Mage::getModel('maverick_translator/translation')
                    			->setModulename($requestData['modulename'])
                    			->setAdminstrator(Mage::helper('maverick_translator')->getAdminFullName())
                    			->setAutoTranslate($autoTranslate)
                    			->setTranslator($translator)
                    			->setFilename($filename)
                    			->setPath($csvPath)
                    			->save();
                    
                    $this->_getSession()->addSuccess(Mage::helper('maverick_translator')->__('Translation CSV was successfully created.'));
                }
                
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::log($e->getMessage(), Zend_Log::ERR, 'MaverickTranslator.log', true);
                $this->_getSession()->addError($e->getMessage());
            }
            
        }
        
        $this->_redirect('*/*/index');
        return;
    }

    /**
     * Check if required fields are specified
     * @param array $requestData
     */
    protected function _checkData($requestData)
    {
        if(!isset($requestData['modulename'])) {
            Mage::throwException(Mage::helper('maverick_translator')->__('Module name is a required field'));
        } 
        
        if(!isset($requestData['from_language'])) {
            Mage::throwException(Mage::helper('maverick_translator')->__('From Language is a required field'));
        }
        
        if(!isset($requestData['to_language'])) {
            Mage::throwException(Mage::helper('maverick_translator')->__('To Language is a required field'));
        }
    }

    /**
     * Download translation file
     */
    public function downloadAction()
    {
    	$csv = Mage::getModel('maverick_translator/translation')->load((int)$this->getRequest()->getParam('id'));
    	
    	if (!$csv->fileExists()) {
    		$this->_redirect('*/*/index');
    		$this->_getSession()->addError(Mage::helper('maverick_translator')->__('Unable to find CSV file'));
    		return;
    	}
    	
    	$fileName 	= $csv->getFilename();
    	$path		= $csv->getPath();

    	$this->_prepareDownloadResponse($fileName, null, 'application/octet-stream', filesize($path . $fileName));
    	$this->getResponse()->sendHeaders();
    	$csv->output();
    	exit();
    }
    
    /**
     * Grid action for ajax requests
     */
    public function gridAction() {
    	$this->loadLayout();
    	$this->renderLayout();
    }
    
	public function deleteAction() 
	{
		try {
            $obj = Mage::getModel('maverick_translator/translation')->load((int)$this->getRequest()->getParam('entity_id'));
			if($obj->getId()) {
				
				/** Delete CSV file */
				$path = $obj->getPath();
				$name = $obj->getFilename();
				if(!empty($path) && !empty($name)) {
					$obj->deleteFile();
				}

				$obj->delete();
            	$this->_getSession()->addSuccess(Mage::helper('maverick_translator')->__('CSV file has been successfully deleted.'));
			} else {
				$this->_getSession()->addError(Mage::helper('maverick_translator')->__('Unable to find item to delete'));
			}
        }
        catch (Exception $e) {
			$this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/index');
	}
}