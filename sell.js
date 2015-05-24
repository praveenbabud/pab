function sellbook(isbn13, purchasedate, orderid,mybookid)
{
	var doctag = document.getElementById(mybookid + "collectdata");
	doctag.innerHTML = "<center><form><span class=\"atp\"> Please describe the book condition </span> <br><br><textarea name=\"description\"></textarea><br><br> <span class=\"atp\"> Enter Sale Price </span> <input type=\"text\" name=\"sellprice\"/> <br><br> <input type=\"button\" value=\"Confirm Sale\" onclick=\"confirmsellbook('" + isbn13 + "','" + purchasedate + "','" + orderid + "'," + mybookid + ",this.form)\"/> </form></center>";

}
function confirmsellbook(isbn13,purchasedate,orderid,mybookid, form)
{
	var data = "";
	data = "isbn13=" + isbn13 + "&purchasedate=" + purchasedate + "&orderid=" + orderid + "&mybookid=" + mybookid + "&sellprice=" +  form.sellprice.value +  "&description=" + encodeURIComponent(form.description.value);
        getData("sellbook", data,mybookid);
}
function sellbookresponse(htmlresponse,docpart)
{
	var bk = document.getElementById(docpart);
	bk.innerHTML = htmlresponse;
	var bkdata = document.getElementById(docpart + "collectdata");
	bkdata.innerHTML = "";
}
