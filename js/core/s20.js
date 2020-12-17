function m20buyer_createobj( xau, xlg_buyer)
{
    ajax_post("p20_buyers.php?a=1",
        {
            XAU: xau,
            XLG_BUYER: xlg_buyer
        }, function (data)

        {
            menu7_click(20);
        }
	 );
}