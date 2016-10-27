<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <form name="reportForm" id="reportForm" method="POST" action="GetReports.php">
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
//load config file variables
$settings = parse_ini_file("app.config", 1);

try
{
	 

     $ssrs_report = new SSRSReport(new Credentials($settings["UID"], $settings["PASWD"]),$settings["SERVICE_URL"]);
	 $result_html = null;
     $controls = null;
	 
     //check are process 'params'request parameter 
     parsePostBack();
     
     //We need to get the report parameters, create controls, and fill in values
     if(key_exists("reportName", $_REQUEST))
     {
     	 
         $query = $_REQUEST["reportName"];            
         $parmVals = getReportParameters();
         
         //this makes it easier to access the stored values below
         $arr = array();
         if(!empty ($parmVals))
         {
             foreach ($parmVals as $key => $val)
             {
                 //error checking code to print the values retrieved
                 //echo "\n<br />parameters[$key]=$val->Name:$val->Value";

                 $arr[$val->Name] = $val->Value;
             }
         }

         //get report parameters based on either defaults or changed values
         $reportParameters = $ssrs_report->GetReportParameters($query, null, true, $parmVals, null);
         $i=0;
         $controls .= "\n<div style='float: right; width: 100px;'>";
         $controls .= "\n<input type='button' onclick='renderReport();' value='Submit' style='float: right;' />";
         $controls .= getExportFormats($ssrs_report);
         $controls .= "\n<a style='float: right;' href='GetReports.php'>Choose Report</a>";
         $controls .= "\n</div>";
         $controls .= "\n<table border='black 1px solid' >";
         foreach($reportParameters as $reportParameter)
         {
             //are we opening or continuing a row?
             if($i%2 == 0)
                 $controls .= "\n<tr><td>";
             else
                 $controls .= "<td>";

             //get the default value
             $default = null;
             foreach($reportParameter->DefaultValues as $vals)
                 foreach($vals as $key=>$def)
                     $default = $def;

             $controls .= $reportParameter->Name . "</td><td>";
             //If there is a list, then it needs to be a Select box
             if(sizeof($reportParameter->ValidValues) > 0){
                 $dependencies = empty($reportParameter->Dependencies) ? "onchange='getParameters();'" : "";
                 $controls .= "\n<select name='$reportParameter->Name' $dependencies>";
                 foreach($reportParameter->ValidValues as $values)
                 {
                     //choose the default value only if nothing is set
                     if($parmVals == null)
                         $selected = ($values->Value == $default)
                                         ? "selected='selected'"
                                         : "";
                     else
                         $selected = (key_exists($reportParameter->Name, $arr) && $values->Value == $arr[$reportParameter->Name])
                                         ? "selected='selected'"
                                         : "";
                     $controls .= "\n<option value='" . $values->Value . "' label='" . $values->Label . "' $selected/>";
                 }
                 $controls .= "\n</select\n>";
             }
             //Boolean needs to be a CheckBox
             else if($reportParameter->Type == "Boolean")
             {
                 //choose the default value only if nothing is set
                 if($parmVals == null)
                     $selected = (!empty($default) && $default != "False")
                                     ? "checked='checked'"
                                     : "";
                 else
                     $selected = (key_exists($reportParameter->Name, $arr) && !empty($arr[$reportParameter->Name]))
                                     ? "checked='checked'"
                                     : "";
                 $controls .= "\n<input name='$reportParameter->Name' type='checkbox' $selected/>";
             }
             //the other types should be entered in TextBoxes (DateTime, Integer, Float)
             else
             {
                 //choose the default value only if nothing is set
                 if($parmVals == null)
                     $selected = (!empty($default))
                                     ? "value='" . $default . "'"
                                     : "";
                 else
                     $selected = (key_exists($reportParameter->Name, $arr) && !empty($arr[$reportParameter->Name]))
                                     ? "value='" . $arr[$reportParameter->Name] . "'"
                                     : "";
                 $controls .= "\n<input name='$reportParameter->Name' type='text' $selected/>";
             }


             //same deal, are we continuing or closing a row?
             if($i%2 == 0)
                 $controls .= "</td>";
             else
                 $controls .= "</td></tr>";
             $i++;
         }
         $controls .= "\n</table>";
         $controls .= "\n<input type='hidden' value='' name='parameters' id='parameters' />";
         $controls .= "\n<div id='exportReportDiv' style='visibility: hidden; background: gray; width: 700px;' >";
         $controls .= "\nExport Name: <input name='exportName' type='text' onkeypress='submitenter(event);' />";
         $controls .= "\n</div>";
     }
     //We need to get the list of available reports
     //Play with the Types to create a hierarchical menu
     else
     {
         $catalogItems = $ssrs_report->ListChildren("/", true);
         $reports = array();
         $controls = "Report Name: <select id='report' name='report' onchange='setReport(value);'>";
         $controls .= "\n<option value='' label='Select Report' />";
         foreach ($catalogItems as $catalogItem)
         {
             if($catalogItem->Type == "Report")
                     $controls .= "\n<option value='$catalogItem->Path' label='$catalogItem->Name' />";
         }
         $controls .= "\n</select>";
     }

     if((isset($_REQUEST['rs:Command']))
             || (key_exists("reportName", $_REQUEST) && (key_exists("parameters", $_REQUEST) && $_REQUEST["parameters"] != 'true')))
     {
        if (isset($_REQUEST['rs:ShowHideToggle']))
        {
            $ssrs_report->ToggleItem($_REQUEST['rs:ShowHideToggle']);            
        }
        else if (isset($_REQUEST['rs:Command']))
        {
            switch($_REQUEST['rs:Command'])
            {
                case 'Sort':
                    $ssrs_report->Sort2($_REQUEST['rs:SortId'],
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
            $query = $_REQUEST["reportName"];
            $parameters = getReportParameters();
           
            if (isset($_REQUEST['ps:OrginalUri']))
            {
                $length = strlen($settings["SERVICE_URL"]);
                $query = substr($_REQUEST['ps:OrginalUri'], $length);
                $parameters = getReportParametersFromGet();
            }

             $executionInfo = $ssrs_report->LoadReport2($query, NULL); 
             //Use these if the SSRS DataSource is configured to use user prompt credentials
//           $dsCredential = new DataSourceCredentials();
//           $dsCredential->DataSourceName = $settings["DATA_SOURCE"]; /*AdventureWorks*/
//           $dsCredential->UserName = $settings["UID"]; /*PHPDemoUser*/
//           $dsCredential->Password = $settings["PASWD"]; /*Passw0rd!*/
//           $ssrs_report->SetExecutionCredentials2(array($dsCredential));
             $ssrs_report->SetExecutionParameters2($parameters);
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
        $params = getStreamRootParams();     
        $renderAsHTML->ReplacementRoot = getPageURL();
        //append form params with ReplacementRoot for preserving them
        //upon sort/toggle clicks.
        $renderAsHTML->ReplacementRoot .= $params;
        $renderAsHTML->StreamRoot = './images/';
        $result_html = $ssrs_report->Render2($renderAsHTML,
                                     PageCountModeEnum::$Actual,
                                     $Extension,
                                     $MimeType,
                                     $Encoding,
                                     $Warnings,
                                     $StreamIds);
        foreach($StreamIds as $StreamId)
        {
            $renderAsHTML->StreamRoot = null;
            $result_png = $ssrs_report->RenderStream($renderAsHTML,
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
     }

     echo "\n" . '<div align="center">';
     echo "\n" . '<div style="overflow:auto; width:800px; height:600px">';
     echo "\n<div style='background-color:gray; width:700px; height: 50px;' align='left'>";
        echo $controls;
     echo "\n</div>";
        echo $result_html;
     echo "\n" . '</div>';
     echo "\n" . '</div>';
}
catch(SSRSReportException $serviceExcprion)
{
    echo  "\n<br/>" . $serviceExcprion->GetErrorMessage();
    $trace = str_replace("#", "<br />", $serviceExcprion->getTraceAsString());
    echo  "<br />" . $trace;
}
echo "\n";

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

/**
 * Parse params varible and populate the 
 * $_REQUEST object.
 */
function parsePostBack()
{
	if(!key_exists("params", $_REQUEST))
	{
		return;
	}
	
	//Check for Drill down, means user navigate to a new report so the params
	//are not valid. We can assume user moved to new page if ps:OrginalUri
	//is set and no sort or toggle flags are on.
	if(key_exists("ps:OrginalUri", $_REQUEST) &&
	   !key_exists("rs:Command", $_REQUEST) &&
	   !key_exists("rs:ShowHideToggle", $_REQUEST))  
	{
  		unset($_REQUEST['params']);
  		$settings = parse_ini_file("app.config", 1);
  		$length = strlen($settings["SERVICE_URL"]);  		
  		$query = substr($_REQUEST['ps:OrginalUri'], $length + 2); //adding for ?
  		$_REQUEST['reportName'] = $query;  		
	}

	$parameters = array();
	$params = explode('$$', $_REQUEST['params']);
    foreach($params as $param)
	{
		$keyval = explode('=', $param);
		if(count($keyval) == 2)
		{
				$_REQUEST[$keyval[0]] = $keyval[1];					
		}
	}
	unset($_REQUEST['params']);
}

/**
 * The RenderAsHTML::StreamRoot memeber variable can be used to preserve
 * form variables upon sort/toggle clicks. This function will create a string
 * of keyvalue pairs (form variable name and value) seperated by '$$' symbol.
 */
function getStreamRootParams()
{
        $params = null;
         $i=0;
         foreach($_REQUEST as $key => $post)
         {             
             if($key == "params")
                 continue;
             if(strpos($key,'rc:') === 0)
              	continue;
             if(strpos($key,'rs:') === 0)
              	continue;
             if(strpos($key,'ps:') === 0)
              	continue;
                 
             if(!empty($post))
             {
                 $params .= $key . '=' . $post . '$$';                 
                 $i++;
             }
             if($i > 100)
                 break;
         }
         
         return ($params == null ? null: '?params=' . $params);             
}

function getReportParameters()
{	
	if(key_exists("parameters", $_REQUEST))
    {       
    	 $parameters = array(); 
         $i=0;
         foreach($_REQUEST as $key => $post)
         {
             if($key == "reportName")
                 continue;
             if($key == "parameters")
                 continue;
             if($key == "exportSelect")
                 continue;
             if($key == "exportName")
                 continue;
             if($key == "params")
                 continue;
             if(strpos($key,'rc:') === 0)
              	continue;
             if(strpos($key,'rs:') === 0)
              	continue;
             if(strpos($key,'ps:') === 0)
             	continue;
             if(!empty($post))
             {
                 $parameters[$i] = new ParameterValue();
                 $parameters[$i]->Name = $key;
                 $parameters[$i]->Value = $post;
                 $i++;
             }
             if($i > 100)
                 break;
         }
         return $parameters;
    }
    else
        return null;
}

function getReportParametersFromGet()
{
    $parameters = array();
    $i=0;
    foreach($_GET as $key => $post)
    {
        if(strrpos($key, ":"))
            continue;
        if(!empty($post))
        {
            $parameters[$i] = new ParameterValue();
            $key = substr($key, strpos($key, ";") + 1);
            $parameters[$i]->Name = $key;
            $parameters[$i]->Value = $post;
            $i++;
        }
    }

    return $parameters;
}

function getExportFormats($ssrs_report)
{
    $extensions = $ssrs_report->ListRenderingExtensions();
    $result = array();
    foreach($extensions as $extension)
    {
        $result[] = $extension->Name;
    }

    $controls = "Export Format: <select id='exportSelect' name='exportSelect' onchange='exportType(value)' >";
    foreach ($result as $format)
    {
        $selected = ($format == "HTML4.0")
                        ? "selected='selected'"
                        : "";

        if($format != "RGDI" && $format != "RPL")
            $controls .= "\n<option value='$format' label='$format' $selected />";
    }
    $controls .= "\n</select>";
    return $controls;
}

?>
            <input type="hidden" id="reportName" name="reportName"
                   value="<?php if(key_exists("reportName", $_REQUEST)) echo $_REQUEST["reportName"]; ?>" />
        </form>

        <script type="text/javascript">
            function exportType(value)
            {
                if(value.match("HTML."))
                    exportReportDiv.style.visibility = 'hidden';
                else
                    exportReportDiv.style.visibility = '';
            }

            function getParameters()
            {
                reportForm.parameters.value = true;
                reportForm.submit();
            }

            function setReport(report)
            {
                if(report != "")
                {
                    reportForm.reportName.value = report;
                    reportForm.submit();
                }
            }

            function renderReport()
            {
                value = reportForm.exportSelect.value;
                reportForm.parameters.value = false;
                if(reportForm.exportName.value == "" && !value.match("HTML."))
                {
                    alert("Please enter a name for the report!");
                    return;
                }

                if(value.match("HTML."))
                    reportForm.action = "GetReports.php";
                else
                    reportForm.action = "Download.php";
                reportForm.submit();
            }

            function submitenter(e)
            {
                var keycode;
                if (window.event) keycode = window.event.keyCode;
                else if (e) keycode = e.which;
                else return true;

                if (keycode == 13)
                {
                    renderReport();
                    return false;
                }
                else
                    return true;
            }
          
        </script>
    </body>
</html>
