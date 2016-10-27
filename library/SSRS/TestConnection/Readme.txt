This folder contains a utility script (TestConnection.php) which can be used to test connection against any report in a report server.

Usage:TestSSRSConnection.php /server:<report server uri> /report:<path of report> /uid:<user name> /pwd:<password> [/datasource:<name of datasource> /uid:<user name> /pwd:<password>]

commandline arguments:
/server:<report server uri>: Path to report server ex:http://localhost/ReportServer_SQLEXPRESS
/report:<path of Report>: Full path of report ex:/AdventureWorks 2008 Sample Reports/TopStoresBegin
/uid:<user name> /pwd:<pawwsord>: User credentials

The following parameters are required only if any of the data source in the report are configured
for prompt for credential, for example if any of the report data source configured to use sql server
credenials then report server automatically prompt for credenials.
 
/datasource:<name of data source>: ex: AdventureWorks2008
/uid:<user name>:ex:sa
/pwd:<password> ex:sa123