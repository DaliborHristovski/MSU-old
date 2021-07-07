
 //let actual_JSON;
  var novaPrijava = document.getElementById("selMenu");
  var btn = document.getElementById("btn");
  var data = JSON.parse(decodeURIComponent(getCookieValue('predmeti')));
  
  
/* this is the old way We used to get our subjects that were available for us
  function loadJSON(callback) {   
    var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        xobj.open('GET', 'dozvoleniPredmeti.json', true); // Replace 'appDataServices' with the path to your file
    xobj.onreadystatechange = function () {
          if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
          }
    };
    xobj.send(null);  
 }
 function init() {
    loadJSON(function(response) {
     // Parsing JSON string into object
       var actual_JSON = JSON.parse(response);
       novaPrijava.insertAdjacentHTML("beforeend", renderHTML(actual_JSON));
    });
   }
*/
  
function getCookieValue(name) {
    let result = document.cookie.match("(^|[^;]+)\\s*" + name + "\\s*=\\s*([^;]+)")
    return result ? result.pop() : ""
}
function init() {
      novaPrijava.insertAdjacentHTML("beforeend", renderHTML(data));
}

  btn.addEventListener("click", function(){  
    init();
    hideBtn();
  });
  function hideBtn(){
    btn.style.display ="none";
  }
  function renderHTML(ajson){
      var htmlString = "<form method=\"POST\" action=\"inputSubject_query.php\"><select class=\"select\" name=\"taskOption\">";
    //ajson[0]['AP_ID']
        for(i=0;i<ajson.length;i++){
       htmlString += "<option value =\""+ajson[i]["AP_ID"]+"\">"+ajson[i]["ImeNaPredmet"]+"</option>";
        }
        htmlString += "</select><input name=\"sub\" type=\"submit\" class=\"btn btn-secondary btn-sm\" value=\"Пријави\"></form>";
       return htmlString;   
  }
  
