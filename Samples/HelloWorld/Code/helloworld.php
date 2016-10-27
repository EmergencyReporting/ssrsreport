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

require_once 'SSRSReport.php';
define("REPORT", "/AdventureWorks 2008 Sample Reports/TopStoresBegin");
$settings = parse_ini_file("app.config", 1);

try
{
     $rs = new SSRSReport(new Credentials($settings["UID"], $settings["PASWD"]),$settings["SERVICE_URL"]);
    if (isset($_REQUEST['rs:Command']))
    {
        switch($_REQUEST['rs:Command'])
        {
            case 'Sort':
                $rs->Sort2($_REQUEST['rs:SortId'],
                           $_REQUEST['rs:SortDirection'],
                           $_REQUEST['rs:ClearSort'],
                           PageCountModeEnum::$Estimate,
                           $ReportItem,
                           $ExecutionInfo);
                  break;
            default:
                echo 'Unknown :' . $_REQUEST['rs:Command'];
                exit;
        }
    }
    else
    {
        $executionInfo = $rs->LoadReport2(REPORT, NULL);
        $parameters = array();
        $parameters[0] = new ParameterValue();
        $parameters[0]->Name = "ProductCategory";
        $parameters[0]->Value = "1";
        $parameters[1] = new ParameterValue();
        $parameters[1]->Name = "StartDate";
        $parameters[1]->Value = "1/1/2003";
        $parameters[2] = new ParameterValue();
        $parameters[2]->Name = "EndDate";
        $parameters[2]->Value = "12/31/2003";
        $parameters[3] = new ParameterValue();
        $parameters[3]->Name = "ProductSubcategory";
        $parameters[3]->Value = "2";
        $rs->SetExecutionParameters2($parameters);
    }

    $renderAsHTML = new RenderAsHTML();
    //The ReplcementRoot option of HTML rendering extension is used to
    //redirect all calls to reporting serice server to this php file.
    //The StreamRoot option of HTML rendering extension used instruct
    //HTML rendering extension about how to construct the URLs to images in the
    //report.
    //Please refer description of Sort2, Render2 and RenderStream API in
    //the userguide (./../../../docs/User Guide.html) for more details
    //about these options.
    $renderAsHTML->ReplacementRoot = getPageURL();
    $renderAsHTML->StreamRoot = './images/';
    $result_html = $rs->Render2($renderAsHTML,
                                 PageCountModeEnum::$Actual,
                                 $Extension,
                                 $MimeType,
                                 $Encoding,
                                 $Warnings,
                                 $StreamIds);
    foreach($StreamIds as $StreamId)
    {
        $renderAsHTML->StreamRoot = null;
        $result_png = $rs->RenderStream($renderAsHTML,
                                    $StreamId,
                                    $Encoding,
                                    $MimeType);

        if (!$handle = fopen("./images/" . $StreamId, 'wb'))
        {
            echo "Cannot open file for writing output";
            exit;
        }

        if (fwrite($handle, $result_png) === FALSE)
        {
            echo "Cannot write to file";
            exit;
        }
        fclose($handle);
    }
	echo '<html><body><br/><br/>';
	echo '<div align="center">';
	echo '<div style="overflow:auto; width:700px; height:600px">';
	    echo $result_html;
	echo '</div>';
	echo '</div>';
	echo '</body></html>';
}
catch(SSRSReportException $serviceExcprion)
{
    echo  $serviceExcprion->GetErrorMessage();
}

/**
 *
 * @return <url>
 * This function returns the url of current page.
 */
function getPageURL()
{
    $PageUrl = $_SERVER["HTTPS"] == "on"? 'https://' : 'http://';
    $uri = $_SERVER["REQUEST_URI"];
    $index = strpos($uri, '?');
    if($index !== false)
    {
	$uri = substr($uri, 0, $index);
    }
    $PageUrl .= $_SERVER["SERVER_NAME"] .
                ":" .
                $_SERVER["SERVER_PORT"] .
                $uri;
    return $PageUrl;
}

?>