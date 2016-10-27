SELECT top 5
    S.Name as Store, C.StoreID, COUNT(SOH.SalesOrderID) as NSalesOrders, SUM(DET.LineTotal) as SaleAmount 
FROM Sales.SalesOrderHeader SOH
	INNER JOIN [Sales].[Customer] C	ON C.[CustomerID] = SOH.[CustomerID]
	INNER JOIN [Sales].[Store] S ON C.StoreID = S.BusinessEntityID
	INNER JOIN Sales.SalesOrderDetail DET ON  DET.SalesOrderID = SOH.SalesOrderID
	INNER JOIN Production.Product P ON P.ProductID = DET.ProductID
	INNER JOIN Production.ProductSubcategory PS ON P.ProductSubcategoryID = PS.ProductSubcategoryID 
WHERE C.StoreID IS NOT NULL
   AND PS.ProductCategoryID = (@ProductCategory) 
   AND PS.ProductSubcategoryID IN (@ProductSubcategory)
   AND (SOH.OrderDate > (@StartDate) OR (@StartDate) is null)
   AND (SOH.OrderDate < (@EndDate) OR (@EndDate) is null) 
GROUP BY  S.Name, C.StoreID
ORDER BY    SUM(DET.LineTotal) DESC 