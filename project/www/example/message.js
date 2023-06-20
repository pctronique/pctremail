function recupValue(type, message) {
    let url = "./recupMessage.php";
    let data = {
        keyMess: message,
        type: type
    };
    let text = "";
    for (var key in data) {
        text += key + "=" + data[key] + "&";
    }
    dataObject = text.trim("&");
    fetch(url, {
        method: "post",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: dataObject,
    })
    .then((response) => response.text())
    .then(function (response) {
        let resultData = response.split("[#DATA#]");
        if(resultData[0] == "true") {
            let tabJson = JSON.parse(resultData[1]);
            document.getElementById("objet").value = tabJson.obj;
            document.getElementById("message").innerHTML = tabJson.message;
        } else {
            console.log(response);
        }
    })
    .catch((error) => console.error("Error:", error));
}
document.getElementById("contenu-json").addEventListener("change", function(e) {
    recupValue("json", e.target.value);
});
document.getElementById("contenu-ini").addEventListener("change", function(e) {
    recupValue("init", e.target.value);
});