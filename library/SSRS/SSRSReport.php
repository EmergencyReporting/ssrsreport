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

namespace SSRS;

use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;
use SSRS\Factory\SSRSTypeFactory;
use SSRS\RenderType\RenderAsHTML;

SSRSTypeFactory::RegisterType('SSRS\SSRSType\ExecutionInfo2');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ReportParameter');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ReportParameterCollection');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\DataSourcePrompt');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\PageSettings');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ReportMargins');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ValidValue');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ReportPaperSize');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\Extension');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\RenderResponse');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\RenderStreamResponse');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\Warning');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\CatalogItem');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\CatalogItemCollection');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\StreamIdCollection');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ExtensionCollection');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\Sort2Response');
SSRSTypeFactory::RegisterType('SSRS\SSRSType\ToggleItemResponse');
SSRSTypeFactory::RegisterEnum('SSRS\SSRSType\ParameterTypeEnum');
SSRSTypeFactory::RegisterEnum('SSRS\SSRSType\ParameterStateEnum');
SSRSTypeFactory::RegisterEnum('SSRS\SSRSType\ExtensionTypeEnum');
SSRSTypeFactory::RegisterEnum('SSRS\SSRSType\ItemTypeEnum');
SSRSTypeFactory::RegisterEnum('SSRS\SSRSType\PageCountModeEnum');

/**
 *
 * class SSRSReport
 */
class SSRSReport {
    /**
     *
     * @var String
     */
    protected $_BaseUrl;

    /**
     *
     * @var Credential
     */
    protected $_credentials;

    /**
     *
     * @var SoapClient
     */
    protected $_soapHandle_Exe;

    /**
     *
     * @var SoapClient
     */
    protected $_soapHandle_Mgt;

    /**
     *
     * @var ExecutionInfo2
     */
    public $ExecutionInfo2;

    /**
     *
     * Reporing service namespace
     */
    const NAMESPACE_REPORTSERVICE =
        'http://schemas.microsoft.com/sqlserver/2005/06/30/reporting/reportingservices';

    /**
     *
     * Execution header format
     */
    const EXECUTIONHEADER_FORMAT =
        '<ExecutionHeader xmlns="%s"><ExecutionID>%s</ExecutionID></ExecutionHeader>';

    /**
     *
     * Execution Service End Point
     */
    const ExecutionService = "ReportExecution2005.asmx?wsdl";

    /**
     *
     * Management Service End Point
     */
    const ManagementService = "ReportService2005.asmx?wsdl";
    const ManagementService2010 = "ReportService2010.asmx?wsdl";

    /**
     * Default constructor for ReportExecutionService.
     * @param Common\Credentials $credentials Object holding user credentials.
     * @param string $url Url of Report Server.
     * @param Common\Proxy $proxy
     * @param int $ssrsVersion
     * @throws SSRSReportException
     */
    public function __construct($credentials, $url, $proxy = null, $ssrsVersion = 2005) {
        $this->_BaseUrl = ($url[strlen($url) - 1] == '/') ? $url : $url . '/';
        $executionServiceUrl = $this->_BaseUrl . self::ExecutionService;
        $managementServiceUrl = $this->_BaseUrl . self::ManagementService;
        if ($ssrsVersion > 2009) {
            $managementServiceUrl = $this->_BaseUrl . self::ManagementService2010;
        }

        $options = $credentials->getCredentials();
        $stream_context_params = array(
            'http' =>
                array(
                    'header' =>
                        array($credentials->getBase64Auth())
                )
        );
        if (isset($proxy)) {
            $options = array_merge($options, $proxy->getProxy());
            $stream_context_params['http']['proxy'] = 'tcp://' .
                                                     $proxy->getHost() .
                                                     ':' .
                                                     $proxy->getPort();
            if ($proxy->getLogin() != null) {
                $stream_context_params['http']['header'][1] = $proxy->getBase64Auth();
            }
        }

        /**
         * If the SoapClient call fails, we cannot catch exception or supress warning
         * since it throws php fatal exception.
         * http://bugs.php.net/bug.php?id=34657
         * So try to load the wsdl by
         * calling file_get_contents (with warning supressed i.e. using @ symbol
         * infront of the function call)
         * http://stackoverflow.com/questions/272361/how-can-i-handle-the-warning-of-filegetcontents-function-in-php
         */
        $context = stream_context_create($stream_context_params);
        $content = @file_get_contents($executionServiceUrl, false, $context);
        if ($content === FALSE) {
            throw new SSRSReportException("",
                "Failed to connect to Reporting Service  <br/> Make sure " .
                "that the url ($this->_BaseUrl) and credentials are correct!");
        }

        $this->_soapHandle_Exe = new SoapClient ($executionServiceUrl, $options);
        $this->_soapHandle_Mgt = new SoapClient ($managementServiceUrl, $options);
        $this->ClearRequest();
    }

    /**
     * Gets a list of children of a specified folder.
     * @param string $Item The full path name of the parent folder.
     * @param bool $Recursive A Boolean expression that indicates whether to
     *         return the entire tree of child items below the specified item.
     *         The default value is false.
     * @return CatalogItem[] An array of CatalogItem objects. If no children
     *         exist, this method returns an empty CatalogItem object.
     */
    public function ListChildren($Item = "/", $Recursive = true) {
        $parameters = array(
            'Item' => $Item,
            'Recursive' => $Recursive
        );
        try {
            $stdObject = $this->_soapHandle_Mgt->ListChildren($parameters);
            $catalogItemCollection = SSRSTypeFactory::CreateSSRSObject(
                'CatalogItemCollection',
                $stdObject);
            return $catalogItemCollection->CatalogItems;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Retrives report parameter properties for a specified report.
     *         this method can also be used to validate parameter values against
     *         parameters for a specified report
     * @param string $Report The full path name of the report.
     * @param string $HistoryID The ID of the report history snapshot.
     * @param bool $ForRendering
     * @param SSRSType/ParameterValue[] $Values The parameter values that can be
     *        validated against the parameters of a report that is managed
     *        by the report server
     * @param Common/DataSourceCredentials[] $Credentials The data source credentials
     *        that can be used to validate query parameters
     * @return SSRSType/ReportParameter[] An array of ReportParameter objects that lists
     *        the prameters for the report
     */
    public function GetReportParameters($Report,
                                        $HistoryID = null,
                                        $ForRendering = false,
                                        $Values = null,
                                        $Credentials = null) {
        $parameters = array(
            'Report' => $Report,
            'HistoryID' => $HistoryID,
            'ForRendering' => $ForRendering,
            'Values' => $Values,
            'Credentials' => $Credentials
        );
        try {
            $stdObject = $this->_soapHandle_Mgt->GetReportParameters($parameters);
            $reportParameterCollection = SSRSTypeFactory::CreateSSRSObject(
                'ReportParameterCollection',
                $stdObject);
            return $reportParameterCollection->Parameters;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Retrieves the report definition for a report.
     * @param string $Report The full path name of the report
     * @return byte[] A byte array of the report definition.
     */
    public function GetReportDefinition($Report) {
        $parameters = array(
            'Report' => $Report,
        );
        try {
            $stdObject = $this->_soapHandle_Mgt->GetReportDefinition($parameters);
            $objectVars = get_object_vars($stdObject);
            return $objectVars['Definition'];
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Creates a report execution from a report definition supplied by the client.
     * @param byte[] $Definition A byte stream containing the Report Definition
     *               Language (RDL) for the report.
     * @param Warning[] $warnings A collection of Warning objects containing
     *               warnings that may have occurred during report publishing.
     * @return SSRSType/ExecutionInfo2 An ExecutionInfo object containing information for
     *               the report execution.
     */
    public function LoadReportDefinition2($Definition, &$warnings) {
        $parameters = array(
            'Definition' => $Definition
        );
        try {
            $stdObject = $this->_soapHandle_Exe->LoadReportDefinition2($parameters);
            $this->ExecutionInfo2 = SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);
            return $this->ExecutionInfo2;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Loads a report from the report server into a new execution.
     * @param string $report The full name of the report.
     * @param string $historyID The history ID of the snapshot.
     * @return SSRSType/ExecutionInfo2 An ExecutionInfo object containing information
     *                                                 for the loaded report.
     */
    public function LoadReport2($report,
                                $historyID = null) {
        $parameters = array(
            'Report' => $report,
            'HistoryID' => $historyID
        );
        try {
            $stdObject = $this->_soapHandle_Exe->LoadReport2($parameters);
            $this->ExecutionInfo2 = SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);

            return $this->ExecutionInfo2;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Returns information about the report execution.
     * @return SSRSType/ExecutionInfo2 An  ExecutionInfo object containing information
     *                                            about the report execution.
     */
    public function GetExecutionInfo2() {
        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->GetExecutionInfo2();
            return SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Returns a list of rendering extensions.
     * @return Extensions[] An array of  Extension objects that contains
     *                                the available rendering extensions.
     */
    public function ListRenderingExtensions() {
        try {
            $stdObject = $this->_soapHandle_Exe->ListRenderingExtensions();
            $extensionCollection = SSRSTypeFactory::CreateSSRSObject(
                'ExtensionCollection',
                $stdObject);
            return $extensionCollection->Extensions;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Sets and validates parameter values associated with
     *                                          the current report execution.
     * @param SSRSType/ParameterValue[] $parameters An array of ParameterValue objects.
     * @param string $parameterLanguage locale identifier
     * @return SSRSType/ExecutionInfo2 An  ExecutionInfo object containing the new execution.
     */
    public function SetExecutionParameters2($parameters,
                                            $parameterLanguage = "en-us") {
        $parameters = array(
            "Parameters" => $parameters,
            "ParameterLanguage" => $parameterLanguage
        );
        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->SetExecutionParameters2($parameters);
            $this->ExecutionInfo2 = SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);
            return $this->ExecutionInfo2;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Processes a specific report and renders it in the specified format.
     * @param $renderType
     * @param $PaginationMode
     * @param string $Extension [out] The file extension corresponding to the
     *                                                          output stream.
     * @param string $MimeType [out] The MIME type of the rendered report
     * @param string $Encoding [out] The encoding used when report server
     *                                  renders the contents of the report.
     * @param string $Warnings [out] An array of  Warning objects that
     *                                  describes any warnings that occurred
     *                                  during report processing.
     * @param StreamIdCollection $StreamIds .
     * @return byte[] A byte array of the report in the specified format.
     * @internal param $RenderType /RenderBaseType $renderType Object holding Format and DeviceInfo.
     */
    public function Render2($renderType, $PaginationMode, &$Extension,
                            &$MimeType, &$Encoding, &$Warnings, &$StreamIds) {
        if ($renderType instanceof RenderAsHTML &&
            isset($renderType->ReplacementRoot)
        ) {
            $renderType->ReplacementRoot .=
                strpos($renderType->ReplacementRoot, '?') !== false ?
                    '&amp;amp;' :
                    '?amp;';

            $renderType->ReplacementRoot .= 'ps%3aSessionID=' .
                                            $this->ExecutionInfo2->ExecutionID .
                                            '&amp;amp;ps%3aOrginalUri=';
        }

        $parameters = array(
            "Format" => $renderType->GetFormat(),
            "DeviceInfo" => $renderType->GetDevInfoXML(),
            "PaginationMode" => $PaginationMode
        );

        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->Render2($parameters);

            $renderResponse = SSRSTypeFactory::CreateSSRSObject(
                'RenderResponse',
                $stdObject);
            $Extension = $renderResponse->Extension;
            $MimeType = $renderResponse->MimeType;
            $Encoding = $renderResponse->Encoding;
            $Warnings = $renderResponse->Warnings;
            $StreamIds = $renderResponse->StreamIds->string;
            return $renderResponse->Result;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Gets a secondary rendering stream associated with a processed report.
     * @param RenderType/RenderBaseType $renderType $renderType Object
     *                        holding Format and DeviceInfo.
     * @param string $StreamID The stream identifier.
     * @param string $Encoding [out] The encoding class name.
     * @param string $MimeType [out]The MIME type of the stream.
     * @return byte[] A Byte[] array of the stream in the specified format
     */
    public function RenderStream($renderType, $StreamID, &$Encoding, &$MimeType) {
        $parameters = array(
            "Format" => $renderType->GetFormat(),
            "StreamID" => $StreamID,
            "DeviceInfo" => $renderType->GetDevInfoXML()
        );

        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->RenderStream($parameters);
            $renderStreamResponse = SSRSTypeFactory::CreateSSRSObject(
                'RenderStreamResponse',
                $stdObject);
            $Encoding = $renderStreamResponse->Encoding;
            $MimeType = $renderStreamResponse->MimeType;
            return $renderStreamResponse->Result;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     *
     * @param Common/DataSourceCredentials[] $Credentials An array of DataSourceCredentials.
     * @return SSRSType/ExecutionInfo2 An ExecutionInfo object containing the new execution.
     */
    public function SetExecutionCredentials2($Credentials) {
        $parameters = array(
            "Credentials" => $Credentials
        );
        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->SetExecutionCredentials2($parameters);
            $this->ExecutionInfo2 = SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);
            return $this->ExecutionInfo2;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Resets the current report execution by clearing the snapshot
     *                              and resetting the session state.
     * @return SSRSType/ExecutionInfo2 An ExecutionInfo object
     */
    public function ResetExecution2() {
        try {
            $this->SetSessionId();
            $stdObject = $this->_soapHandle_Exe->ResetExecution2();
            $this->ExecutionInfo2 = SSRSTypeFactory::CreateSSRSObject(
                'ExecutionInfo2',
                $stdObject);
            return $this->ExecutionInfo2;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     *
     * @param <string> $SortItem The ID of the report item on which to sort.
     * @param <SortDirectionEnum> $Direction A SortDirectionEnum value
     *                          containing the direction for the sort.
     * @param <bool> $Clear A Boolean value that indicates whether all
     *                          other existing sorts should be cleared.
     * @param <PaginationModeEnum> $PaginationMode The mode by which
     *                          the report is processed.
     * @param <string> $ReportItem The ID of the item on the page used
     *                          for positioning in the viewing area.
     * @return <ExecutionInfo2>
     */
    public function Sort2($SortItem, $Direction, $Clear,
                          $PaginationMode, &$ReportItem, &$ExecutionInfo) {
        $parameters = array(
            "SortItem" => $SortItem,
            "Direction" => $Direction,
            "Clear" => $Clear,
            "PaginationMode" => $PaginationMode
        );
        try {
            $this->SetSessionId(true);
            $stdObject = $this->_soapHandle_Exe->Sort2($parameters);
            $sort2Response = SSRSTypeFactory::CreateSSRSObject(
                'Sort2Response',
                $stdObject);
            $ReportItem = $sort2Response->ReportItem;
            $ExecutionInfo = $sort2Response->ExecutionInfo;
            return $sort2Response->PageNumber;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     *
     * @param <string> $ToggleID The ID of the item to toggle.
     */
    public function ToggleItem($ToggleID) {
        $parameters = array(
            "ToggleID" => $ToggleID
        );
        try {
            $this->SetSessionId(true);
            $stdObject = $this->_soapHandle_Exe->ToggleItem($parameters);
            $toggleItemResponse = SSRSTypeFactory::CreateSSRSObject(
                'ToggleItemResponse',
                $stdObject);
            return $toggleItemResponse->Found;
        }
        catch (SoapFault $soapFault) {
            self::ThrowReportException($soapFault);
        }
    }

    /**
     * Set Soap execution header.
     */
    protected function SetSessionId($idFromRequest = false) {
        if ($idFromRequest &&
            isset($_REQUEST) &&
            isset($_REQUEST['rc:ReplacementRoot']) &&
            isset($_REQUEST['ps:SessionID'])
        ) {
            $this->ExecutionInfo2->ExecutionID = $_REQUEST['ps:SessionID'];
        }

        $headerStr = sprintf(self::EXECUTIONHEADER_FORMAT,
            self::NAMESPACE_REPORTSERVICE,
            $this->ExecutionInfo2->ExecutionID);
        $soapVar = new SoapVar($headerStr, XSD_ANYXML, null, null, null);
        $soapHeader = new SoapHeader(self::NAMESPACE_REPORTSERVICE,
            'ExecutionHeader',
            $soapVar);
        $this->_soapHandle_Exe->__setSoapHeaders(array($soapHeader));
    }

    /**
     * Removes all amp; in the $_REQUEST array
     */
    protected function ClearRequest() {
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key, 'amp;') === 0) {
                $newKey = substr($key, 4);
                $_REQUEST[$newKey] = $_REQUEST[$key];
                unset($_REQUEST[$key]);
            }
        }
    }

    /**
     * create and throw SSRSReportException from SoapFault object
     * @param SoapFault $soapFault
     */
    protected function ThrowReportException($soapFault) {
        if (isset($soapFault->detail) && is_object($soapFault->detail)) {
            throw new SSRSReportException($soapFault->detail->ErrorCode,
                $soapFault->detail->Message,
                $soapFault);
        }
        else if (property_exists($soapFault, 'detail') && !empty($soapFault->detail) && is_string($soapFault->detail)) {
            throw new SSRSReportException('', $soapFault->detail, $soapFault);
        }
        else {
            $lines = explode("\n", $soapFault->getMessage());
            throw new SSRSReportException('', $lines[0], $soapFault);
        }
    }
}

?>
