function validateForm() {
  var a = document.forms["DataConvergenceTool"]["MeterNumber"].value;
  var b = document.forms["DataConvergenceTool"]["Installation"].value;
  var c = document.forms["DataConvergenceTool"]["Material"].value;
  var d = document.forms["DataConvergenceTool"]["RegisterGroup"].value;
  var e = document.forms["DataConvergenceTool"]["RateCategory"].value;
  var f = document.forms["DataConvergenceTool"]["BillingClass"].value;
  var g = document.forms["DataConvergenceTool"]["MRUnit"].value;

  if ((a == null || a == "") && (b == null || b == "") && (c == null || c == "") && (d == null || d == "") && (e == null || e == "") && (f == null || f == "") && (g == null || g == "")) {
    alert("Please enter value(s) in any one of the fields");
    return false;
  }
}