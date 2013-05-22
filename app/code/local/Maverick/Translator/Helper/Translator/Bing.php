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
 * Bing Translator
 */
class Maverick_Translator_Helper_Translator_Bing extends Mage_Core_Helper_Data
{
    /**
     * Get the access token.
     *
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */
    function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl)
    {
        //Initialize the Curl Session.
        $ch = curl_init();
        //Create the request Array.
        $paramArr = array (
                'grant_type'    => $grantType,
                'scope'         => $scopeUrl,
                'client_id'     => $clientID,
                'client_secret' => $clientSecret
        );
        //Create an Http Query.//
        $paramArr = http_build_query($paramArr);
        //Set the Curl URL.
        curl_setopt($ch, CURLOPT_URL, $authUrl);
        //Set HTTP POST Request.
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //Set data to POST in HTTP "POST" Operation.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //Execute the  cURL session.
        $strResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if($curlErrno){
            $curlError = curl_error($ch);
            Mage::throwException($curlError);
        }
        //Close the Curl Session.
        curl_close($ch);
        //Decode the returned JSON string.
        $objResponse = json_decode($strResponse);
        $objResponse = (array)$objResponse;
        
        if (isset($objResponse['error']) && isset($objResponse['error_description'])) {
            Mage::throwException($objResponse['error_description']);
        }
        return $objResponse['access_token'];
    }
    
   /**
    * Create and execute the HTTP CURL request.
    *
    * @param string $url        HTTP Url.
    * @param string $authHeader Authorization Header string.
    * @param string $postData   Data to post.
    *
    * @return string.
    *
    */
    function curlRequest($url, $authHeader, $postData=''){
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        if($postData) {
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            Mage::throwException($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $curlResponse;
    }
    
   /**
    * Create Request XML Format.
    *
    * @param string $fromLanguage   Source language Code.
    * @param string $toLanguage     Target language Code.
    * @param string $contentType    Content Type.
    * @param string $inputStrArr    Input String Array.
    *
    * @return string.
    */
    function createReqXML($fromLanguage,$toLanguage,$contentType,$inputStrArr) {
        //Create the XML string for passing the values.
        $requestXml = "<TranslateArrayRequest>".
                "<AppId/>".
                "<From>$fromLanguage</From>".
                "<Options>" .
                "<Category xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                "<ContentType xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\">$contentType</ContentType>" .
                "<ReservedFlags xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                "<State xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                "<Uri xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                "<User xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                "</Options>" .
                "<Texts>";
        foreach ($inputStrArr as $inputStr)
            $requestXml .=  "<string xmlns=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">$inputStr</string>" ;
        $requestXml .= "</Texts>".
                "<To>$toLanguage</To>" .
                "</TranslateArrayRequest>";
        return $requestXml;
    }
    

    public function bing_api($sentences, $from, $to)
    {
    	$translatedSentences = array();
    
    	//Client ID of the application.
    	$clientID       = trim(Mage::getStoreConfig('translation/bing_api/client_id'));
    	//Client Secret key of the application.
    	$clientSecret = trim(Mage::getStoreConfig('translation/bing_api/client_secret'));
    	//OAuth Url.
    	$authUrl      = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
    	//Application Scope Url
    	$scopeUrl     = "http://api.microsofttranslator.com";
    	//Application grant type
    	$grantType    = "client_credentials";
    
    	//Get the Access token.
    	$accessToken  = $this->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
    
    	//Create the authorization Header string.
    	$authHeader = "Authorization: Bearer ". $accessToken;
    
    	//Set the params.//
    	$fromLanguage = current(explode('_', $from));
    	$toLanguage   = current(explode('_', $to));
    	 
    	$contentType  = 'text/plain';
    	$category     = 'general';
    
    	foreach ($sentences as $inputStr) {
    		$params = "text=".urlencode($inputStr)."&to=".$toLanguage."&from=".$fromLanguage;
    		$translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
    
    		//Get the curlResponse.
    		$curlResponse = $this->curlRequest($translateUrl, $authHeader);
    
    		//Interprets a string of XML into an object.
    		$xmlObj = simplexml_load_string($curlResponse);
    		foreach((array)$xmlObj[0] as $val){
    			$translatedSentences[$inputStr] = $val;
    		}
    	}
    	
    	return $translatedSentences;
    }
    
    /**
     * Check Bin API Configuration
     * @return boolean
     */
    public function checkConf()
    {
    	// not finished yet so return false !!
    	return false;
    	
    	if (!Mage::getStoreConfig('translation/bing_api/enable') || 
    			!Mage::getStoreConfig('translation/bing_api/client_id') || !Mage::getStoreConfig('translation/bing_api/client_secret')) {
    		return false;
    	}
    	return true;
    }
}