function validateForm()
{
var a=document.forms["DataMiningTool"]["MeterNumber"].value;
var b=document.forms["DataMiningTool"]["Installation"].value;
var c=document.forms["DataMiningTool"]["Material"].value;
var d=document.forms["DataMiningTool"]["RegisterGroup"].value;
var e=document.forms["DataMiningTool"]["RateCategory"].value;
var f=document.forms["DataMiningTool"]["BillingClass"].value;
var g=document.forms["DataMiningTool"]["MRUnit"].value;

if ((a==null || a=="") && (b==null || b=="") && (c==null || c=="") && (d==null || d=="") && (e==null || e=="") && (f==null || f=="") && (g==null || g==""))
  {
  alert("Please enter value(s) in any one of the fields");
  return false;
  }
}