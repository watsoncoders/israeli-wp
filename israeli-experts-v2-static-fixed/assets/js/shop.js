
var xmlRequest = new xmlObj(false);
var globalLang;
var add2cartMsg;
/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_addProduct																												*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_addProduct (productId, quantity, variationId1, variationId2, variationId3)
{
	if (quantity == undefined)	
		quantity = 1;

	if (variationId1 == undefined)	
		variationId1 = 0;

	if (variationId2 == undefined)	
		variationId2 = 0;

	if (variationId3 == undefined)	
		variationId3 = 0;

	xml = "<data>"	+
				"<command>shop.addProduct</command>"    +
				"<productId>"  + productId 	 	    	+ "</productId>"  +
				"<quantity>"  +  quantity 	 	    	+ "</quantity>"  +
				"<variationId1>"  + variationId1 	 	 	+ "</variationId1>"  +
				"<variationId2>"  + variationId2 	 	+ "</variationId2>"  +
				"<variationId3>"  + variationId3 	 	+ "</variationId3>"  +
		  "</data>";
	
	xmlRequest.init (xml);
	
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_addProduct_response");

	return false;
}

var add2cartMsg_x;
var add2cartMsg_y;

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_addProduct_response																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_addProduct_response (i)
{ 
	xmlRequest.init(commonDecode(asyncHttpObjs[i].responseText));
	done = xmlRequest.getValue("done");

	if (done == "1")
	{
		// To use a float popup instead of an alert message you should:
		// 1. Include javascripts/floatWindow.js
		// 2. Define a div with id=product which is position:relative
		// 3. Define in your private javascript: var add2cartMsg = 1;
		// 4. In your css define the design of the popup with add2cartMsgId
		// 5. you can define the x,y position by vars (add2cartMsg_x, add2cartMsg_y)
		if (add2cartMsg != undefined)
		{
			var html = tailJS["productAddedToCart"] + "<br /><br /><a href=\"javascript:(add2cartMsg.close())\">" + tailJS["productAddedToCartClose"] + "</a>";
			add2cartMsg = new floatWindow;

			if (add2cartMsg_x != undefined && add2cartMsg_y != undefined) 
				add2cartMsg.create("product", "add2cartMsgId", html, add2cartMsg_x, add2cartMsg_y);
			else
				add2cartMsg.create("product", "add2cartMsgId", html, "50px", "100px");

			add2cartMsg.show();
		} 
		else
		{
			if (tailJS["productAddedToCart"] != "")
				alert (tailJS["productAddedToCart"]);
		}
	}

	shop_showCart ();
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_showCart																												*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_showCart ()
{
	xml = "<data>"	+
				"<command>shop.getCartProducts</command>" +
				"<lang>" + globalLang + "</lang>" +
		  "</data>";
	
	xmlRequest.init (xml);
	
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_showCart_response");

	return false;
}

var asyncHttpObj;

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_showCart_response																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_showCart_response (i)
{
	asyncHttpObj = asyncHttpObjs[i];

	xmlRequest.init (commonDecode(asyncHttpObj.responseText));

	xmlRequest.loadTableData ("cartProductsXml", "items");
	xmlRequest.loadTableData ("cartTotalsXml",   "totals");

	if (xmlRequest.getValue("count") == "0")
	{
		if (document.getElementById("boxCartHeader") != undefined)
        {
			document.getElementById("boxCartHeader").style.display  	= "none";
			document.getElementById("boxCartContent").style.display  	= "none";
			document.getElementById("boxCartTotal").style.display 	 	= "none";
			document.getElementById("boxCartButtons").style.display 	= "none";
			document.getElementById("boxCartEmpty").style.display 		= "";
		}
	}
	else
	{
		if (document.getElementById("boxCartHeader") != undefined)
	    {
			document.getElementById("boxCartHeader").style.display  	= "";
			document.getElementById("boxCartContent").style.display  	= "";
			document.getElementById("boxCartTotal").style.display 	 	= "";
			document.getElementById("boxCartButtons").style.display 	= "";
			document.getElementById("boxCartEmpty").style.display 		= "none";
		}
	}
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_showProduct																												*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_showProduct (col, productPageId)
{
	if (productPageId == 0) return;

	// get product and category ids
	var index 		= col.parentNode.rowIndex;
	var productId   = cartProductsXml.getElementsByTagName("productId").item(index).text;
	var categoryId  = cartProductsXml.getElementsByTagName("categoryId").item(index).text;

	window.location.href = "index2.php?id=" + productPageId + "&productId=" + productId + "&catId=" + categoryId;
}


var globalReload = false;

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_emptyCart																												*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_emptyCart (reload)
{
	globalReload = reload;

	xml = "<data>"	+
				"<command>shop.emptyCart</command>" +
		  "</data>";

	xmlRequest.init (xml);

	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_emptyCart_response");
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_emptyCart_response																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_emptyCart_response ()
{
	if (globalReload)
		window.location.reload ();
	else
		shop_showCart ();
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_selectShipment																											*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_selectShipment ()
{
	try
	{
		var cartTotal	   	= document.getElementById("cartTotal_spn").innerHTML*1;
		var cartCount	   	= document.getElementById("cartCount_spn").innerHTML*1;

		var shipmentSelect 	= document.getElementById("shipment");
		var shipmentPrice  	= shipmentSelect.value;

		var shipmentOption 	= shipmentSelect.options[shipmentSelect.selectedIndex];

		var freeByAmount	= shipmentOption.getAttribute("freeByAmount")*1;
		var freeByQuantity	= shipmentOption.getAttribute("freeByQuantity")*1;

		if ((freeByAmount   != 0 && cartTotal >= freeByAmount) ||
		    (freeByQuantity != 0 && cartCount >= freeByQuantity))
		{
			shipmentPrice = 0;
		}

		document.getElementById('shipmentPrice_spn').innerHTML = shipmentPrice;

		var orderTotal = shop_formatSum(Math.round(shipmentPrice*100 + cartTotal*100) / 100);

		document.getElementById('orderTotal_spn').innerHTML    = orderTotal;

		var	vat = document.getElementById("orderVAT_spn");
		if (vat != undefined)
		{
			var orderTotal	 = document.getElementById("orderTotal_spn").innerHTML * 100;
			var vatFraction	 = document.getElementById("vatPercentage").innerHTML / 100;
			vat.innerHTML = shop_formatSum(Math.round(orderTotal * vatFraction) / 100);
			document.getElementById("orderToPay_spn").innerHTML	= shop_formatSum(Math.round(orderTotal * (1+vatFraction)) / 100);
		}
	}
	catch (e)
	{
	}
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_formatSum																												*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_formatSum (sum)
{
	sum = "" + sum;
	if (sum.indexOf(".") == -1)
		sum += ".00";
	else if (sum.indexOf(".") == sum.length-2)
		sum += "0";

	return (sum);
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_increaseQuantity																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_increaseQuantity (index, productId, variationId1, variationId2, variationId3)
{
	// get curr quantity
	var currQuantity = document.getElementById("productQuantity_spn" + index).innerHTML*1;

	newQuantity = currQuantity + 1;
	
	// save new quantity in DB
	shop_updateQuantityInDB (productId, newQuantity, variationId1, variationId2, variationId3);

	// update quantity & totals in page
	shop_updateQuantityInPage (index, newQuantity, +1);
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_decreaseQuantity																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_decreaseQuantity (index, productId, variationId1, variationId2, variationId3)
{
	// get curr quantity
	var currQuantity = document.getElementById("productQuantity_spn" + index).innerHTML*1;

	newQuantity = currQuantity - 1;
	
	if (newQuantity == 0) return;

	// save new quantity in DB
	shop_updateQuantityInDB (productId, newQuantity, variationId1, variationId2, variationId3);

	// update quantity & totals in page
	shop_updateQuantityInPage (index, newQuantity, -1);
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_updateQuantityInPage																									*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_updateQuantityInPage (index, quantity, by)
{
	document.getElementById("productQuantity_spn" + index).innerHTML = quantity;

	var productPrice = Math.round(document.getElementById("productPrice_spn" + index).innerHTML*100);
	var cartCount	 = document.getElementById("cartCount_spn").innerHTML*1;
	var cartTotal	 = Math.round(document.getElementById("cartTotal_spn").innerHTML*100);
	var orderTotal	 = Math.round(document.getElementById("orderTotal_spn").innerHTML*100);

	document.getElementById("cartCount_spn").innerHTML				 = cartCount + by;
	document.getElementById("productTotal_spn" + index).innerHTML    = shop_formatSum((quantity * productPrice) / 100);
	document.getElementById("cartTotal_spn").innerHTML    			 = shop_formatSum((cartTotal   + (by*productPrice)) / 100);
	document.getElementById("orderTotal_spn").innerHTML    			 = shop_formatSum((orderTotal  + (by*productPrice)) / 100);

	var vat = document.getElementById("orderVAT_spn");
	if (vat != undefined)
	{
			var orderTotal	 = document.getElementById("orderTotal_spn").innerHTML * 100;
			var vatFraction	 = document.getElementById("vatPercentage").innerHTML / 100;
			vat.innerHTML = shop_formatSum(Math.round(orderTotal * vatFraction) / 100);
			document.getElementById("orderToPay_spn").innerHTML	= shop_formatSum((vat.innerHTML*100 + orderTotal) / 100);
	}

	shop_selectShipment ();
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_updateQuantityInDB																										*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_updateQuantityInDB (productId, quantity, variationId1, variationId2, variationId3)
{
	if (variationId1 == undefined)	
		variationId1 = 0;

	if (variationId2 == undefined)	
		variationId2 = 0;

	if (variationId3 == undefined)	
		variationId3 = 0;

	xml = "<data>"	+
				"<command>shop.updateProductQuantity</command>"	+
				"<productId>"  + productId	  + "</productId>" 	+
				"<quantity>"   + quantity     + "</quantity>"  	+
				"<variationId1>"  + variationId1 	 	 	+ "</variationId1>"  +
				"<variationId2>"  + variationId2 	 	+ "</variationId2>"  +
				"<variationId3>"  + variationId3 	 	+ "</variationId3>"  +
		  "</data>";
	
	xmlRequest.init (xml);
	
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_updateQuantity_response");

	return false;
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_updateQuantity_response																									*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_updateQuantity_response ()
{
	shop_showCart ()
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_removeProduct																											*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_removeProduct (index, productId, variationId1, variationId2, variationId3)
{
	if (variationId1 == undefined)	
		variationId1 = 0;

	if (variationId2 == undefined)	
		variationId2 = 0;

	if (variationId3 == undefined)	
		variationId3 = 0;

	xml = "<data>"	+
				"<command>shop.removeProductFromCart</command>"	+
				"<productId>"  + productId	  + "</productId>" 	+
				"<variationId1>"  + variationId1 	 	 	+ "</variationId1>"  +
				"<variationId2>"  + variationId2 	 	+ "</variationId2>"  +
				"<variationId3>"  + variationId3 	 	+ "</variationId3>"  +
		  "</data>";
	
	xmlRequest.init (xml);
	
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_removeProduct_response");

	return false;
}

/* ---------------------------------------------------------------------------------------------------------------------------- */
/* shop_removeProduct_response																									*/
/* ---------------------------------------------------------------------------------------------------------------------------- */
function shop_removeProduct_response ()
{
	window.location.reload ();
}

/* ----------------------------------------------------------------------------------------------------------------	*/
/* shop_checkCoupon																								*/
/* ----------------------------------------------------------------------------------------------------------------	*/
function shop_checkCoupon (oField, gotoPageUrl)
{
	xml = "<data>"	+
				"<command>shop.checkCoupon</command>" +
				"<coupon>" + oField.value 	 + "</coupon>" +
				"<gotoPageUrl>" + gotoPageUrl 	 + "</gotoPageUrl>" +
		  "</data>";
	
	xmlRequest.init (xml);
	
	xmlRequest.sendAsyncRequest ("server.php", xmlRequest.obj, "shop_afterCheckCoupon");

	return false;
}

/* ----------------------------------------------------------------------------------------------------------------	*/
/* shop_afterCheckCoupon																							*/
/* ----------------------------------------------------------------------------------------------------------------	*/
function shop_afterCheckCoupon (i)
{
	xmlRequest.init(commonDecode(asyncHttpObjs[i].responseText));

	try
	{
		alert (xmlRequest.getValue("errorMsg"));

		var returnCode = xmlRequest.getValue("returnCode");

		if (returnCode == "OK")
		{
			var coupon = xmlRequest.getValue("coupon");

			commonSetCookie ("cookie_couponCode", coupon);

			location.href = decodeURIComponent(xmlRequest.getValue("gotoPageUrl"));
		}
	}
	catch (e)
	{
	}

	return false;
}
