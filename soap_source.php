<?php
/**
 * SoapSource
 * 
 * A SOAP Client Datasource
 * Connects to a SOAP server using the configured wsdl file
 *
 * PHP Version 5
 *
 * Copyright 2008 Pagebakers, www.pagebakers.nl
 *
 * This library is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link        http://github.com/Pagebakers/soapsource/
 * @copyright   Copyright 2008 Pagebakers
 * @license     http://www.gnu.org/licenses/lgpl.html

 *
*/
class SoapSource extends DataSource {
    
    /**
     * Description for this DataSource
     *
     * @var string
     */
    public $description = 'Soap Client DataSource';

    /**
     * The SoapClient instance
     *
     * @var object
     */
    public $client = null;
    
    /**
     * The current connection status
     *
     * @var boolean
     */
    public $connected = false;
        
    /**
     * Constructor
     *
     * @param array $config An array defining the configuration settings
     */
    public function __construct($config) {
        parent::__construct($config);
        
        $this->connect();
    }

    /**
     * Connects to the SOAP server using the wsdl in the configuration
     *
     * @param array $config An array defining the new configuration settings
     * @return boolean True on success, false on failure
     */ 
    public function connect() {
        $this->client = new SoapClient($this->config['wsdl']);

        if ($this->client) {
            $this->connected = true;
        }

        return $this->connected;
    }
    
    /**
     * Sets the SoapClient instance to null
     *
     * @return boolean True
     */
    public function close() {
        $this->client = null;
        $this->connected = false;
        return true;
    }

    /**
     * Returns the available SOAP methods
     *
     * @return array List of SOAP methods
     */
    public function listSources() {
       return $this->client->__getFunctions();
    }
    
    /**
     * Query the SOAP server with the given method and parameters
     *
     * @return mixed Returns the result on success, false on failure
     */
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
    
    /**
     * Returns the last SOAP response
     *
     * @return string The last SOAP response
    */
    public function getResponse() {
       return $this->client->__getLastResponse();
    }
  
    /**
     * Returns the last SOAP request
     *
     * @return string The last SOAP request
    */  
    public function getRequest() {
        return $this->client->__getLastRequest();
    }
    
    /**
     * Shows an error message and outputs the SOAP result if passed
     *
     * @param string $result A SOAP result
     * @return string The last SOAP response
    */
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