This is the SSRS SDK for PHP bin directory, this directory contains the following files and folders:

SSRSReport.php: Contains definition of SSRSReport class.
SSRSReportException.php: Contains definition of SSRSReportException class.
Utility.php: Contains definition of utility class.
Common: Contains common files
 |
 |-- Credentials.php 
 |-- DataSourceCredentials.php
 
Factory: 
 |
 |-- SSRSTypeFactory.php
 
Interface: Contains files which defines interfaces used by framework
 |
 |-- IRenderType.php
 |-- ISSRSBaseType.php
 
RenderType: Contains files which defines different render types
 |
 |-- RenderAsCSV.php 
 |-- RenderAsEXCEL.php 
 |-- RenderAsHTML.php 
 |-- RenderAsIMAGE.php 
 |-- RenderAsMHTML.php 
 |-- RenderAsPDF.php 
 |-- RenderAsWORD.php
 |-- RenderAsXML.php
 |-- RenderBaseType.php

SSRSType: Contains files which defines different SSRS data types
 |
 |-- CatalogItem.php 
 |-- CatalogItemCollection.php 
 |-- DataSourcePrompt.php 
 |-- ExecutionInfo2.php 
 |-- Extension.php 
 |-- ExtensionCollection.php 
 |-- ExtensionTypeEnum.php 
 |-- ItemTypeEnum.php 
 |-- PageCountModeEnum.php  	 
 |-- PageSettings.php 
 |-- ParameterStateEnum.php 
 |-- ParameterTypeEnum.php 
 |-- ParameterValue.php 
 |-- RenderResponse.php 
 |-- RenderStreamResponse.php 
 |-- ReportMargins.php 
 |-- ReportPaperSize.php 
 |-- ReportParameter.php 
 |-- ReportParameterCollection.php 
 |-- Sort2Response.php 
 |-- SortDirectionEnum.php 
 |-- SSRSBaseType.php S
 |-- treamIdCollection.php 
 |-- ValidValue.php 
 |-- Warning.php
 
 TestConnection: Contains a utility php script which can be used to test connection against any report in report server  
 |              
 |-- TestConnection.php