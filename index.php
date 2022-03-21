<html>
    <head>
        <meta charset="UTF-8">
        <title>Extreme Birdwatching</title>
        <link rel="stylesheet" type="text/css" href="theme.css">
    </head>
    <script>
        var formValid = [false, false, false, false, false];
        //window.setTimeout(function(){document.getElementById("strengthBar").style.width = "74%";}, 1000);
function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;

    // award every unique letter until 5 repetitions
    var letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
    }

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 10;

    return parseInt(score);
}
        function updatePassword(value){
            score = Math.min(scorePassword(value), 100);
            document.getElementById("strengthBar").style.width = score+"%";
            document.getElementById("strengthBar").style.backgroundColor = ("rgb("+(255-score*2.5)+", "+(score*2.5)+", 0)");
            document.getElementById("strengthBar").innerHTML = "<p>"+score+"</p>";
            display = "error";
            if (score == 0) document.getElementById("strengthDisplay").innerHTML = "";
            else if (score < 21) document.getElementById("strengthDisplay").innerHTML = "Password strength: Very weak (Valid: 71+)";
            else if (score < 51) document.getElementById("strengthDisplay").innerHTML = "Password strength: Weak (Valid: 71+)";
            else if (score < 71) document.getElementById("strengthDisplay").innerHTML = "Password strength: Average (Valid: 71+)";
            else if (score < 100) document.getElementById("strengthDisplay").innerHTML = "Password strength: Strong (Valid: 71+)";
            else  document.getElementById("strengthDisplay").innerHTML = "Password strength: Very strong (Valid: 71+)";
            if (score < 71) {
                document.getElementById("passworde").className = "error";
                formValid[3] = false;
                if (!formValid.every(validateForm)) document.getElementById("submit").setAttribute("disabled", true);
            }
            else {
                document.getElementById("passworde").className = "error-hidden"
                formValid[3] = true;
                if (formValid.every(validateForm)) document.getElementById("submit").removeAttribute("disabled");
            }
            //console.log(score + " " + value);
            
            passwordScore = score;

        }

        function validateForm(value) {return value};

        function emailTaken(email){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    formValid[4] = (this.responseText == "1");
                    document.getElementById("emaile").className = (this.responseText == "1") ? "error-hidden" : "error";
                    if (formValid.every(validateForm)) document.getElementById("submit").removeAttribute("disabled");
                    else document.getElementById("submit").setAttribute("disabled", true);
                    //console.log(formValid.every(validateForm));
                    //console.log(formValid[0] + formValid[1] + formValid[2] + formValid[3] + formValid[4])
                    //console.log(formValid);
                    //console.log(formValid);
                }
            };
            xhttp.open("POST", "./validateEmail.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(("email="+email));
        }

        function validateEmail(id) {
            email = document.getElementById(id);
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            result = re.test(String(email.value).toLowerCase());
            document.getElementById(id + "e").className = (formValid.every(validateForm)) ? "error-hidden" : "error";
            if (result) emailTaken(email.value);
            formValid[2] = result;
            if (formValid.every(validateForm)) document.getElementById("submit").removeAttribute("disabled");
            else document.getElementById("submit").setAttribute("disabled", true);
        }
        function validateField(id){
            if(document.getElementById(id).value == "") {
                document.getElementById(id+"e").className = "error";
                document.getElementById(id).focus();
                formValid[(id == "name") ? 0 : 1] = false;
                if (!formValid.every(validateForm)) document.getElementById("submit").setAttribute("disabled", true);
            } else {
                document.getElementById(id + "e").className = "error-hidden";
                formValid[(id == "name") ? 0 : 1] = true;
                if (formValid.every(validateForm)) document.getElementById("submit").removeAttribute("disabled");
            }
        }
    </script>
<body>

    <div class="mainContent">
        <h1>Extreme Birdwatching Sign Up</h1>
        <form action="welcome.php" method="post">
            <div class="row">
                <div class="col-25">
                    <p>Name: </p>
                </div>
                <div class="col-75">
                    <input id="name" type="text" name="name" placeholder="Enter Name" onkeyup="validateField(this.id);"><p id="namee" class="error-hidden">A name is required.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <p>Username: </p>
                </div>
                <div class="col-75">
                    <input id="username" type="text" name="username" placeholder="Enter Username" onkeyup="validateField(this.id);"><p id="usernamee" class="error-hidden">A username is required.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <p>Email: </p>
                </div>
                <div class="col-75">
                    <input id="email" type="text" name="email" placeholder="Enter Email" onchange="validateEmail(this.id);"><p id="emaile" class="error-hidden">A valid email (that isn't already taken) is required.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <p>Password: </p>
                </div>
                <div class="col-75">
                    <input id="password" onkeyup="updatePassword(this.value)" type="password" name="pswd" placeholder="Enter Password"><p id="passworde" class="error-hidden">A "Strong" or better password strength is required.</p>
                </div>
                <div id="strengthBar"></div><p id="strengthDisplay"></p>
            </div>
            <input id="submit" type="submit" disabled><p id="urlerror" class="error"><?php
$found=(isset($_GET["e"]));
echo ($found) ? htmlspecialchars(stripslashes(trim($_GET["e"]))) : "";
?></p>
        </form>
        <p class="userLogin">Already have an account? <a href="./userLogin">Login here</a></p>
    </div>

</body>
</html>