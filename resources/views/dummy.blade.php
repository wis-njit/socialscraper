<script src="/js/prettify.js">

</script>
<script>

    //var json = '[{"_id":"58e865130b52b3df931bd7f3","index":0,"guid":"945e6721-aeda-46ee-a18a-05c47204923a","isActive":false,"balance":"$3,527.48","picture":"http://placehold.it/32x32","age":31,"eyeColor":"brown","name":"Mullins Gonzales","gender":"male","company":"SUPPORTAL","email":"mullinsgonzales@supportal.com","phone":"+1 (982) 440-3038","address":"203 Stryker Street, Richmond, Hawaii, 1436","about":"Labore sint aute nisi dolore quis occaecat aliquip deserunt exercitation non. Do do anim incididunt cupidatat ut deserunt irure proident nostrud eu. Pariatur dolor eiusmod incididunt ad. Consectetur eu consectetur cillum reprehenderit excepteur nostrud Lorem cillum nulla labore ipsum sint id. Dolore aute do mollit anim laborum.\r\n","registered":"2014-01-17T01:13:52 +05:00","latitude":75.037488,"longitude":74.109363,"tags":["ullamco","pariatur","ea","nostrud","commodo","voluptate","eiusmod"],"friends":[{"id":0,"name":"Ferguson Cherry"},{"id":1,"name":"Bartlett Hurley"},{"id":2,"name":"Goodwin Owens"}],"greeting":"Hello, Mullins Gonzales! You have 3 unread messages.","favoriteFruit":"strawberry"},{"_id":"58e865134bc9ba54b661f6c7","index":1,"guid":"fe471976-bdf0-4ae9-b8ca-b8a567f4deef","isActive":true,"balance":"$2,448.26","picture":"http://placehold.it/32x32","age":25,"eyeColor":"blue","name":"Eula Irwin","gender":"female","company":"BITENDREX","email":"eulairwin@bitendrex.com","phone":"+1 (829) 426-2055","address":"610 Holly Street, Wattsville, Montana, 7208","about":"Commodo esse culpa id eu quis in qui. Dolore Lorem non sunt fugiat. Ad id ipsum excepteur occaecat occaecat officia sit cillum ipsum adipisicing velit velit occaecat cillum. Qui minim enim sint cillum fugiat Lorem.\r\n","registered":"2016-03-02T03:54:37 +05:00","latitude":5.627543,"longitude":61.147339,"tags":["consequat","dolor","ad","velit","nulla","laboris","adipisicing"],"friends":[{"id":0,"name":"Kirsten Bailey"},{"id":1,"name":"Jefferson Petersen"},{"id":2,"name":"Marylou Clements"}],"greeting":"Hello, Eula Irwin! You have 1 unread messages.","favoriteFruit":"banana"}]';
    //var obj = {a:1, 'b':'foo', c:[false,'false',null, 'null', {d:{e:1.3e5,f:'1.3e5'}}]};
    var test = '{"name":"Open Graph Test User","id":"138191473374305"}';
    //var str = JSON.stringify(obj, undefined, 4);
    var jsonPretty = JSON.stringify(JSON.parse(test),null,2);
    //var str = '{"userid_friendlists ":[ { "id":1, "first_name":"Raymond", "last_name":"Ramos", "email":"rramos0@alexa.com", "gender":"Male", "ip_address":"165.64.230.96" }, { "id":2, "first_name":"Mildred", "last_name":"Wood", "email":"mwood1@joomla.org", "gender":"Female", "ip_address":"110.252.219.18" }, { "id":3, "first_name":"Frances", "last_name":"Greene", "email":"fgreene2@free.fr", "gender":"Female", "ip_address":"154.145.59.188" } ] }';
    output(syntaxHighlight(jsonPretty));

</script>

