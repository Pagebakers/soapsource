<?php
class SoapSource extends DataSource {
    
    public $description = 'Soap Client DataSource';
    
    public $client = null;
    public $connected = false;
        
    public function __construct($config) {
        parent::__construct($config);
        
        $this->connect();
    }
    
    public function connect() {
        $this->client = new SoapClient($this->config['wsdl']);

        if ($this->client) {
            $this->connected = true;
        }

        return $this->connected;
    }
    
    public function close() {
        return true;
    }
    
    public function listSources() {
       return $this->client->__getFunctions();
    }
    
    public function query() {
        $this->error = false;
        
        $args = func_get_args();
        
        $method = null;
        $queryData = null;

        if(count($args) == 2) {
            $method = $args[0];
            $queryData = $args[1];
        } elseif(count($args) > 2 && !empty($args[1])) {
            $method = $args[0];
            $queryData = $args[1][0];
        }
        
        if(!$method || !$queryData) {
            return false;
        }
        
        try {
            $result = $this->client->__soapCall($method, $queryData);
        } catch (SoapFault $fault) {
            $this->error = $fault->faultstring;
        }
        
        if($this->error) {
            $this->showError();
            return false;   
        } else {
            return $result;
        }
    }
    
    public function getResponse() {
       return $this->client->__getLastResponse();
    }
    
    public function getRequest() {
        return $this->client->__getLastRequest();
    }
    
    public function showError($result = null) {
        if(Configure::read() > 0) {
            if($this->error) {
                trigger_error('<span style = "color:Red;text-align:left"><b>SOAP Error:</b> ' . $this->error . '</span>', E_USER_WARNING);
            }
            if($result) {
                e(sprintf("<p><b>Result:</b> %s </p>", $result));
            }
        }
    }
}
?>