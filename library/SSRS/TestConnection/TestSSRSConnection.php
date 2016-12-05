<?php
/**
  *
  * Copyright (c) 2009, Persistent Systems Limited
  *
  * Redistribution and use, with or without modification, are permitted
  *  provided that the following  conditions are met:
  *   - Redistributions of source code must retain the above copyright notice,
  *     this list of conditions and the following disclaimer.
  *   - Neither the name of Persistent Systems Limited nor the names of its contributors
  *     may be used to endorse or promote products derived from this software
  *     without specific prior written permission.
  *
  * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
  * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
  * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
  * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
  * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
  * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
  * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
  * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
  * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
  * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  */

namespace SSRS\TestConnection;

require_once 'SSRSReport.php';

/**
  *
  * class DataSourceCredential
  */
class DataSourceCredential {
    public $dataSourceName;
    public $userName;
    public $password;

    public function DataSourceCredential($dataSourceName, $userName, $password) {
        $this->dataSourceName =$dataSourceName;
        $this->userName = $userName;
        $this->password = $password;
    }
}

/**
  *
  * class TestSSRSConnection
  */
class TestSSRSConnection {
    protected $_dataSources;
    protected $_args;
    protected $_server;
    protected $_report;
    protected $_uid;
    protected $_pwd;

    public function TestSSRSConnection($args)
    {
        $this->args = $args;
    }

    public function Parse()
    {
        if (count($this->args) <  5)
        {
            $this->ShowUsage();
        }

        $this->GetKeyVal($this->args[1], $key, $this->_server);
        if(strcmp($key, '/server') != 0)
        {
             $this->ShowUsage();
        }

        $this->GetKeyVal($this->args[2], $key, $this->_report);
        if(strcmp($key, '/report') != 0)
        {
             $this->ShowUsage();
        }

        $this->GetKeyVal($this->args[3], $key, $this->_uid);
        if(strcmp($key, '/uid') != 0)
        {
             $this->ShowUsage();
        }

        $this->GetKeyVal($this->args[4], $key, $this->_pwd);
        if(strcmp($key, '/pwd') != 0)
        {
             $this->ShowUsage();
        }

        $length = count($this->args) - 1;
        
        for($i = 5; $i <= $length; $i++)
        {
            $this->GetKeyVal($this->args[$i], $key, $value);            
            if(strcmp($key, '/datasource') != 0)
            {
                 $this->ShowUsage();
            }

            if($i >= $length - 1)
            {
                $this->ShowUsage();
            }

            $this->GetKeyVal($this->args[$i+1], $key1, $value1);
            $this->GetKeyVal($this->args[$i+2], $key2, $value2);

            if($key1 != '/uid' || $key2 != '/pwd')
            {
                $this->ShowUsage();
            }
            $i += 2;
            $this->_dataSources[] = new DataSourceCredential($value, $value1, $value2);
        }
    }   

    public function GetKeyVal($arg, &$key, &$value) {
        $key = $value = null;
        $result = explode(':', $arg, 2);

        if(count($result) != 2) {
            $this->ShowUsage();
        }
        $key = strtolower(trim($result[0]));
        $value = trim($result[1]);

        if(empty($key) || empty($value)) {
            $this->ShowUsage();
        }
    }

    public function ShowUsage() {
        echo "Usage:TestSSRSConnection.php /server:<report server uri> /report:<path of report> /uid:<user name> /pwd:<password> [/datasource:<name of datasource> /uid:<user name> /pwd:<password>]\n\n"  ;
        exit;
    }

    public function TestConnection()
    {
        try
        {
            $ssrsReport = new SSRSReport(new Credentials($this->_uid, $this->_pwd),
                                    $this->_server);
            $executionInfo2 = $ssrsReport->LoadReport2($this->_report, NULL);

            if($executionInfo2->CredentialsRequired && count($this->_dataSources) == 0)
            {          
                $dataSourceName = $executionInfo2->DataSourcePrompts[0]->Name;
                echo "The data source '$dataSourceName' used by this report has been configured for credential prompt. Please provide data source credentails\n\n";
                $this->ShowUsage();
            }
            
            $dataSrcCredentials = array();
            if(count($this->_dataSources)) {
               
                foreach($this->_dataSources as $dataSource) {
                    $dataSrcCredential = new DataSourceCredentials();
                    $dataSrcCredential->DataSourceName = $dataSource->dataSourceName;
                    $dataSrcCredential->UserName = $dataSource->userName;
                    $dataSrcCredential->Password = $dataSource->password;
                    $dataSrcCredentials[] = $dataSrcCredential;                    
                }
      
		$ssrsReport->SetExecutionCredentials2($dataSrcCredentials);
            }
          
            
            $renderAsHTML = new renderAsHTML();
            try
            {
                $result = $ssrsReport->Render2($renderAsHTML,
                                          PageCountModeEnum::$Estimate,
                                          $Extension,
                                          $MimeType,
                                          $Encoding,
                                          $Warnings,
                                          $StreamIds);

            }catch(SSRSReportException $exception)
            {
                if($exception->errorCode == 'rsProcessingAborted')
                {
                    throw new SSRSReportException(null, 'The provided data source credentials are not valid', null);
                }
                throw new SSRSReportException($exception->errorCode,
                                           $exception->errorDescription,
                                           $exception->soapFault);
            }

            echo "\n Test Connection Succeeded!!\n\n";
        }
        catch(SSRSReportException $exception)
        {
            $errorMessage = $exception->GetErrorMessage();
            $errorMessage = str_replace("<br/>", "\n", $errorMessage);
            echo ($errorMessage);
            echo "\n\n";
            $this->ShowUsage();            
        }
    }
}

$testSSRSConnection = new TestSSRSConnection($argv);
$testSSRSConnection->Parse();
$testSSRSConnection->TestConnection();
?>
