<?php
/**
 * File contains a wrapper for curl.
 * class found on php.net
 * http://php.net/manual/en/book.curl.php
 * slightly modified from above class.  
 * @package mycurl
 */

/**
 * class contains wrappers for curl class. 
 * @package mycurl
 */
class mycurl {
    
   /**
    * holding default user agent
    * @var string $_useragent 
    */
    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    
    /**
     * var holding URL to curl
     * @var string $_url
     */
    protected $_url;
    
    /**
     * var holding flag indicating if we follow urls or not
     * @var boolean $_followlocation
     */
    protected $_followlocation;
    
    /**
     * var setting timeout in secs
     * @var int $_timeout
     */
    protected $_timeout;
    
    /**
     * var setting max redirects
     * @var int $_maxRedirects
     */
    protected $_maxRedirects;
    
    /**
     * var setting cooke jar
     * @var string $_cookieFileLocation
     */
    protected $_cookieFileLocation = '/tmp/cookie.txt';
    
    /**
     * flag indication if we are doing any post
     * @var boolean $_post
     */
    protected $_post;
    
    /**
     * var setting vars to post
     * @var array $_postFields
     */
    protected $_postFields;
    
    /**
     * var setting referer
     * @var string $_referer
     */
    protected $_referer ="http://www.google.com";

    /**
     * @ignore
     */
    protected $_session;
    
    /**
     * var holding the curled webpage
     * @var string $_webpage
     */
    public $_webpage;
    
    /**
     * flag indicating if we want to include headers
     * @var boolean $_includeHeader
     */
    protected $_includeHeader;
    
    /**
     * flag indicating if we ignore body of webpage
     * @var boolean $_noBody
     */
    protected $_noBody;
    
    /**
     * var indicating the status of the webpage, e.g. 200
     * @var int $_status
     */
    protected $_status;
    
    /**
     * flag indicating if this is a binary transfer
     * @var boolean $_binaryTransfer 
     */
    protected $_binaryTransfer;
    
    /**
     * var indicating if we use basic auth
     * @var boolean $authentication
     */
    public    $authentication = 0;
    
    /**
     * var holding basic auth username
     * @var string $auth_name
     */
    public    $auth_name      = '';
    
    /**
     * var holding basic auth password
     * @var string $auth_pass
     */
    public    $auth_pass      = '';
    
    /**
     * var holding certificate placement
     * @var string $cert
     */
    public $cert = null;

    /**
     * method setting use auth
     * @param boolean $use 1 or 0 
     */
    public function useAuth($use){
      $this->authentication = 0;
      if($use == true) $this->authentication = 1;
    }

    /**
     * set auth name
     * @param string $name
     */
    public function setName($name){
      $this->auth_name = $name;
    }
    
    /**
     * set auth pass
     * @param string $pass
     */
    public function setPass($pass){
      $this->auth_pass = $pass;
    }

    /**
     * constructor. Sets up curl object
     * @param string  $url
     * @param boolean $followlocation
     * @param int $timeOut
     * @param int $maxRedirecs
     * @param boolean $binaryTransfer
     * @param boolean $includeHeader
     * @param boolean $noBody
     */
    public function __construct(
            $url,
            $followlocation = true,
            $timeOut = 30,
            $maxRedirecs = 4,
            $binaryTransfer = false,
            $includeHeader = false,
            $noBody = false)
    {
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;

        //$this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt';

    }

    /**
     * sets referer
     * @param string $referer 
     */
    public function setReferer($referer){
      $this->_referer = $referer;
    }
 
    /**
     * sets cookie jar
     * @param string $path 
     */
    public function setCookiFileLocation($path) {
        $this->_cookieFileLocation = $path;
    }

     /**
     * sets post fields
     * @param array $postFields
     */
    public function setPost ($postFields){
       $this->_post = true;
       $this->_postFields = $postFields;
    }
 
    /**
     * sets useragent
     * @param string $userAgent
     */
    public function setUserAgent($userAgent){
        $this->_useragent = $userAgent;
    }
     
    /**
     * sets useragent
     * @param string $userAgent
     */
    public function setCert($cert){
        $this->cert = $cert;
    }

    /**
     * sets a curl option with curl_setopt
     * @param type $s curl handle
     * @param type $option curl option to set
     * @param type $value value of option
     */
    public function setOption ($s, $option, $value) {
         curl_setopt($s, $option, $value);
        //echo "$option: " . $res . "\n";
    }
     
    /**
     * create curl
     * @param string $url = 'nul'
     */
    public function createCurl($url = 'nul'){
        if($url != 'nul'){
            $this->_url = $url;
        }
        
        $s = curl_init();
             
        $this->setOption($s,CURLOPT_URL,$this->_url);
        $this->setOption($s,CURLOPT_HTTPHEADER,array('Expect:'));
        $this->setOption($s,CURLOPT_TIMEOUT,$this->_timeout);
        $this->setOption($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
        $this->setOption($s,CURLOPT_RETURNTRANSFER,true);
        $this->setOption($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
        $this->setOption($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
        $this->setOption($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);
        //curl_setopt($s, CURLOPT_VERBOSE, true);
 
         if ($this->cert == null) {
             //curl_setopt($s,CURLOPT_SSL_VERIFYPEER, false);
             //$this->setOption($s, CURLOPT_SSL_VERIFYHOST, 0);
             //$this->setOption($s, CURLOPT_SSL_VERIFYPEER, 0);
             curl_setopt($s, CURLOPT_SSL_VERIFYHOST, false);
             curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

             //echo "false ok";
        } else {
            //echo $this->cert;
            $this->setOption($s, CURLOPT_SSL_VERIFYPEER, true);
            //$this->setOption($s, CURLOPT_SSL_VERIFYHOST, 1);
            //$this->setOption($s, CURLOPT_CAPATH, "/home/dennis/noiphp");
            $this->setOption($s, CURLOPT_CAINFO, $this->cert);
        }
         
         if($this->authentication == 1){
           $this->setOption($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
         }
         
         if($this->_post){
             $this->setOption($s,CURLOPT_POST,true);
             $this->setOption($s,CURLOPT_POSTFIELDS,$this->_postFields);
         }
 
         if($this->_includeHeader){
               $this->setOption($s,CURLOPT_HEADER,true);
         }
 
         if($this->_noBody){
             $this->setOption($s,CURLOPT_NOBODY,true);
         }
         
         if($this->_binaryTransfer){
             $this->setOption($s,CURLOPT_BINARYTRANSFER,true);
         }

         $this->setOption($s,CURLOPT_USERAGENT,$this->_useragent);
         $this->setOption($s,CURLOPT_REFERER,$this->_referer);
 
         $this->_webpage = curl_exec($s);
         $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);
         curl_close($s);
    }
 
    /**
     * get http status of curl operation
     */
    public function getHttpStatus(){
        return $this->_status;
    }
 
    /** 
     * magic method. returns webpage as string
     */
    public function __tostring(){
        return $this->_webpage;
    }
   
    /**
     * return webpage from curl operation
     */
    public function getWebPage () {
        return $this->_webpage;
    }
}

/**
 * 
 * From: http://www.php.net/manual/de/book.curl.php#102885
 * checks if we get an pre defined http response
 * @param string $url
 * @param int $status e.g. 404
 * @param int $wait time to wait
 * @return boolean $res  
 */
function http_response($url, $status = null, $wait = 3)
{
    $time = microtime(true);
    $expire = $time + $wait;

    // we fork the process so we don't have to wait for a timeout
    $pid = pcntl_fork();
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // we are the parent
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
       
        if(!$head)
        {
            return FALSE;
        }
       
        if($status === null)
        {
            if($httpCode < 400)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        elseif($status == $httpCode)
        {
            return TRUE;
        }
       
        return FALSE;
        pcntl_wait($status); //Protect against Zombie children
    } else {
        // we are the child
        while(microtime(true) < $expire)
        {
        sleep(0.5);
        }
        return FALSE;
    }
} 
 

