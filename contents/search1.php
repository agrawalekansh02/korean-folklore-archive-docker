<?php 
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');



?>
<head>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
  <script>
 var wordlist= [ "about", "above", "across", "after", "against",
                "along", "among", "around", "at", "before", 
                "behind", "below", "beneath", "beside", "between", 
                "beyond", "but", "by", "despite", "down", "during", 
                "except", "for", "from", "in", "inside", "into", 
                "like", "near", "of", "off", "on", "onto", "out", 
                "outside", "over", "past", "since", "through", 
                "throughout", "till", "to", "toward", "under", 
                "underneath", "until", "up", "upon", "with", 
                "within", "without"] ; 

$("#autocomplete").autocomplete({
    // The source option can be an array of terms.  In this case, if
    // the typed characters appear in any position in a term, then the
    // term is included in the autocomplete list.
    // The source option can also be a function that performs the search,
    // and calls a response function with the matched entries.
    source: function(req, responseFn) {
        var re = $.ui.autocomplete.escapeRegex(req.term);
        var matcher = new RegExp( "^" + re, "i" );
        var a = $.grep( wordlist, function(item,index){
            return matcher.test(item);
        });
        responseFn( a );
    }
});
  </script>
</head>
  
<input id="autocomplete" />

