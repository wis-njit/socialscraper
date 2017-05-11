<!--
    Dummy View for an Object Response
    Issue: Figure out how to get responses into here instead of repeating code
-->
<script src="/js/prettify.js">

</script>
<script>
    var test = '{"name":"Open Graph Test User","id":"138191473374305"}';
    var jsonPretty = JSON.stringify(JSON.parse(test),null,2);
    output(syntaxHighlight(jsonPretty));
</script>

