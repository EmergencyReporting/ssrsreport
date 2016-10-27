 This folder contains the following files and folder:

 |-- Report Designer & HelloWorld Sample walkthrough.htm: document explaining how to create a report using Report Designer and consume it using the SSRS SDK for PHP
 |-- setup
 |    |
 |    |-- Client_setup.htm: Contains the instructions to setup all the requires components for the HelloWorld application.
 |    |-- Server_setup.htm: Contains document which explains how to configure Report server on your machine and sample deploy report to the SQL Server Reporting server.
 |     
 |-- Code
 |    |
 |    |-- HelloWorld.php: Hello Word Application
 |    |-- GetReports.php: sample application that shows how to query for the list of reports, report parameters and 
 |    |-- render Reports in HTML format.
 |    |-- images: folder used by the HelloWord application to store the report images.
 |
 |-- Assets     
      |
      |-- TopStoresBegin.rdl: Sample report, this report will be used as a starting point to show how to design a report using report designer
      |-- TopStoresQuery.sql: sample query used for the sample report


Follow the below steps to run the HelloWorld sample application
----------------------------------------------------------------------
1 . Complete the Prerequisites mentioned in setup/Client_Setup.htm
    This document and associated shell scripts help for configure the following:
        a. INSTALLING IIS 7
        b. INSTALLING PHP FOR WINDOWS
        c. ADDING THE HANDLER MAPPING FOR PHP REQUESTS IN IIS7
        d. INSTALLING SQL SERVER 2008 EXPRESS WITH ADVANCED SERVICES
        e. ENABLING SOAP EXTENSION FOR PHP
        f. INSTALLING THE ADVENTUREWORKS SAMPLE DATABASES
        g. MICROSOFT SQL SERVER BUSINESS INTELLIGENCE DEVELOPMENT STUDIO
        h. MICROSOFT SQL SERVER 2008 REPORTING SERVICES SAMPLES
        i. SETTING UP PREPORT DEMO WEB SITE
        j. CREATING DEMO USER
        
                
2. Complete the Prerequisites mentioned in setup/Server_Setup.htm.
   This document help to configure the reporting service and to deploy
   SQL server sample report to reporting server.


---------------------------------------------------------------------------------------------------------------------