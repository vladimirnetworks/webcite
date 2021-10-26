<!DOCTYPE html>
<html>

<head>
  <meta name="description" content="Webpage description goes here" />
  <meta charset="utf-8">
  <title>{{$pageTitle}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
body {
  direction:rtl;
}
</style>

</head>

<body>



@yield('body')



<script>
$("*[contenteditable]").on("blur keyup paste input",function(e) {


});




$("*[contenteditable]").bind("keyup keydown", function(e){
    if(e.ctrlKey && e.which == 83){
         var parentelem = $(this).parent();
  while(!parentelem.attr("data-idx")) {
    parentelem  = parentelem.parent();
  }

  console.log(parentelem.attr("data-idx")+"->"+$(this).attr("data-field")+' : '+$(this).html());



var jsondata = {};
jsondata[$(this).attr("data-field")] = $(this).html();

$.ajax({
  url: parentelem.attr("data-api")+"/"+parentelem.attr("data-idx"),
  type: 'PUT',
  data: jsondata,
  success: function(data) {
    console.log("ok");
  }
});

 
   //$(this).effect( "highlight" );


  return false;

    }
});



</script>
</body>
</html>