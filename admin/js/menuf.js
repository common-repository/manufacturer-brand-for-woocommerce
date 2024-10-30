// JavaScript Document
function check()
{
	var cond=true;
	if(document.manufa.heading.value==0)
	{
		alert("Please Enter Heading.");
		if(cond==true)
		{
			document.manufa.heading.focus();
		}
		cond=false;
		return false;
	}	
	if(document.manufa.manuf.value==0)
	{
		alert("Please Enter Name.");
		if(cond==true)
		{
			document.manufa.manuf.focus();
		}
		cond=false;
		return false;
	}	
	if(document.manufa.desc.value==0)
	{
		alert("Please Enter Description.");
		if(cond==true)
		{
			document.manufa.desc.focus();
		}
		cond=false;
		return false;
	}	
	
	
	
	
	
	
	
}