<?php
	/*
	  These parameters are sent by Mist on the 302 redirect to this portal page:
	    wlan_id - WLAN object's UUID
	    ap_mac - MAC address of the AP
	    client_mac - MAC address of the client device
	    url - Originally requested url by the client, ie: http://www.mist.com
	    ap_name - Name of the AP
	    site_name - Name of the Site
	
	  If you want to send the guest to a content page after authorization, configure the $url instead of using the valued that is passed as a parameter.
	*/

	$wlan_id = $_GET['wlan_id'];
	$ap_mac = $_GET['ap_mac'];
	$client_mac = $_GET['client_mac'];
	$url = $_GET['url'];
	$ap_name = $_GET['ap_name'];
	$site_name = $_GET['site_name'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>¡Bienvenido al Wifi Bam!</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="./assets/fonts/font.css" rel="stylesheet">
    <meta name="viewport"
        content="width=device-width, user-scalable=no,initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <img data-src="./assets/images/logo.svg" alt="Imagen de lado izquierdo" class="logo-img">
            <div class="sub-header">
                <h1>Permítenos conocerte</h1>
            </div>
            <h4>¡Bienvenido al Wifi Bam!</h4>
            <form id="form-data">
                <input type="hidden" name="wlan_id" value="<?php echo($wlan_id) ?>" />
                <input type="hidden" name="ap_mac" value="<?php echo($ap_mac) ?>" />
                <input type="hidden" name="client_mac" value="<?php echo($client_mac) ?>" />
                <input type="hidden" name="url" value="<?php echo($url) ?>" />
                <input type="hidden" name="ap_name" value="<?php echo($ap_name) ?>" />
                <input type="hidden" name="site_name" value="<?php echo($site_name) ?>" />

                <p style="font-family: 'Open Sans Normal'">Ingresa tus datos</p>
                <input type="text" id="name" name="name" placeholder="Nombre" autocomplete="off"
                    onblur="validateForm()">
                <input type="email" id="email" name="email" placeholder="Correo electrónico" autocomplete="off"
                    onblur="validateForm()">
            </form>
            <button class="nav-button" id="btnNavegar" onclick="submitForm()">INGRESAR<i class="icon"></i></button>
        </div>
        <div class="right-section">
            <img data-src="./assets/images/r1.png" alt="Imagen de lado derecho" class="responsive-img">
        </div>
        <div class="footer-container">
            <hr />
            <a href="#" class="privacy-link">Política de aceptación de datos personales</a>
        </div>
    </div>
    <iframe id="miIframe" style="display:none;"></iframe>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var images = document.querySelectorAll('img[data-src]');
        var config = {
            rootMargin: '50px 0px',
            threshold: 0.01
        };

        var observer = new IntersectionObserver(function(entries, self) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var image = entry.target;
                    image.src = image.getAttribute('data-src');
                    self.unobserve(image);
                }
            });
        }, config);

        images.forEach(function(image) {
            observer.observe(image);
        });
    });

    async function submitForm() {

        try {
            const form = document.getElementById('form-data');
            const name = form.elements.name.value;
            const email = form.elements.email.value;
            const wlan_id = form.elements.wlan_id.value;
            const ap_mac = form.elements.ap_mac.value;
            const client_mac = form.elements.client_mac.value;
            const url = form.elements.url.value;
            const ap_name = form.elements.ap_name.value;
            const site_name = form.elements.site_name.value;

            let data = {
                name: name,
                email: email,
                wlan_id: wlan_id,
                ap_mac: ap_mac,
                client_mac: client_mac,
                url: url,
                ap_name: ap_name,
                site_name: site_name
            }

            fetch('authme2.php', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(response.statusText);
                }
            })
            .then(data => {
                console.log(data);

                // Establecer una cookie con el nombre "name" , el correo "email y el valor que expire en 1 día.
                setCookie("name", data.name, 1);
                setCookie("email", data.email, 1);
                //setCookie("url", data.url, 1);
                setCookie("alertShow", true, 1);


                window.location.href = "home.php";
                
                /*
                var iframe = document.getElementById("miIframe");
                iframe.src = data.url;
                setTimeout(function () {
                    window.location.href = "https://gruporamle.com/portal/home.php";
                }, 3000); //redireccionar después de 4 segundos
                */
            })
            .catch(error => console.log(error));      
        } catch(error) {
            console.log(error);
        }
        
    }


    function validateForm() {
        var name = document.getElementById("name").value;
        var email = document.getElementById("email").value;
        var submitButton = document.getElementById("btnNavegar");
        var loginSVG = document.getElementById("login-svg");

        if (name != "" && email != "") {
            submitButton.disabled = false;
            submitButton.style.cursor = "pointer";
            submitButton.style.backgroundColor = "#FDDA24";
            submitButton.style.color = "#2C2A29";
            loginSVG.style.fill = "#2C2A29";

            return true;
        } else {
            submitButton.disabled = true;
            submitButton.style.cursor = "default";
            submitButton.style.backgroundColor = "#CCCCCC";
            submitButton.style.color = "#6d6c6b";

            return false;
        }
    }

    //Función para establecer una cookie
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    //Función para obtener una cookie
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    </script>
</body>

</html>