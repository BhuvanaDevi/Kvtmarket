<html>
    <head>
        <title>Document</title>
       <link href="~/Scripts/jquery-ui-1.10.2.css" rel="stylesheet" />

  <script src="~/Scripts/modernizr-2.6.2.js"></script>
  <script src="~/Scripts/chosen.jquery.min.js"></script>
  <script src="~/Scripts/jquery-ui-1.10.2.js"></script>
  
  <script src="~/Scripts/jquery-1.10.2.js"></script>
  <script src="~/Scripts/bootstrap.min.js"></script>
        <!-- jquery -->
        <!--<script src="assests/jquery/jquery.min.js"></script>-->
        <!-- jquery ui -->
        <!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>-->
        <!--<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>-->
        <script type="text/javascript">
            $('#clientName').autocomplete({
                source: 'ajax_city_search.php',
                minLength: 2,
                response: function(event, ui) {
                    // ui.content is the array that's about to be sent to the response callback.
                    if (ui.content.length === 0) {
                        $("#empty-message").text("No results found");
                        }
                    else {
                        $("#empty-message").empty();
                    }
                }
            });
        </script>
    </head>
    <body>
        <input type="text" id="clientName" class="form-control" autocomplete="on">
    </body>

</html>


