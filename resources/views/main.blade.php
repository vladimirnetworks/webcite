<!DOCTYPE html>
<html>

<head>
  <meta name="description" content="Webpage description goes here" />
  <meta charset="utf-8">
  <title>{{$pageTitle}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">


     <link rel="stylesheet" href={{ asset('bs/css/bootstrap.min.css')}}> 

<style>
body {
  direction:rtl;
}
</style>

</head>

<body>

<main class="container">

<div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">websicite name update2</div>


@yield('body')

</main>

  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
$("*[contenteditable]").on("blur keyup paste input",function(e) {


});




$("*[contenteditable]").bind("keyup keydown", function(e){

  var parentelem = $(this).parent();
  while(!parentelem.attr("data-idx")) {
    parentelem  = parentelem.parent();
  }


 if(e.ctrlKey && e.which == 46){
   if (confirm("delete ?")) {
$.ajax({
  url: parentelem.attr("data-api")+"/"+parentelem.attr("data-idx"),
  type: 'DELETE',
  data: jsondata,
  success: function(data) {
    console.log("ok");
  }
});

  parentelem.remove();
   }

  return false;
 }


    if(e.ctrlKey && e.which == 83){


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