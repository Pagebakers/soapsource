# CakePHP SoapSource

A Soap datasource for CakePHP

---------------------------------------------------------------------

### Requirements

- PHP5 with Soap extension enabled
- Cakephp 2.x

### Usage

Copy the SoapSource.php file to your app/models/datasources/ directory

Add a configuration to your database.php in app/config/

    var $soap = array(
        'datasource' => 'SoapSource',
        'wsdl' => 'http://localhost/soapservice.wsdl', // wsdl mode
        'location' => '', // Required for non wsdl mode
        'uri' => '', // Required for non wsdl mode
        'proxy_host' => 'http://proxy.com', // optional
        'proxy_port' => 3128, // optional
        'curl_off' => true, // optional - turns curl wsdl fetch off
        'headers' => array( // optional - used with 3rd parameter in model
          'ns' => 'namespace',
          'container' => 'session_key',
        )
    );

Then in your model set:

    var $useDbConfig = 'soap';

    var $useTable = false;

And you're ready to go.

In your controller you can now use

    $this->Model->query('SoapMethod', array('mySoapParams'));

or

    $this->Model->SoapMethod(array('mySoapParams'));

or with header data

    $this->Model->query('SoapMethod', array('mySoapParams'), array('mySoapHeaderParams'));
    

## Thanks for updating

- eddiejaoude
- garethellis36
    
## License 

The MIT License (MIT)

Copyright (c) 2014 Pagebakers

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.