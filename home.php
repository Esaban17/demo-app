<?php
?>

<!DOCTYPE html>
<html>

<head>
    <title>Productos y Servicios</title>
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/sweetalert2.min.css">
    <link href="./assets/fonts/font.css" rel="stylesheet">
    <script src="./assets/sweetalert2.min.js"></script>
    <meta name="viewport"
        content="width=device-width, user-scalable=no,initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="image-container">
            <div class="top-container">
                <img data-src="./assets/images/logo.svg" alt="Logo BAM" class="logo">
                <h4 class="title">¡Bienvenido al Wifi Bam!</h4>
            </div>
            <div class="welcome-container">
                <h1 id="greeting"></h1>
            </div>
            <div class="center-container">
                <h4>Ya estás conectado (a). Solicita nuestros productos.</h4>
            </div>
            <div class="card-container">
                <div class="card">
                    <img src="./assets/images/card-1.png" alt="Regístrate en nuestra App" class="card-img-1">
                    <div class="card-description" style="background-color: #00C389;">
                        <h3 style="margin-bottom:0; margin-top: 0;">Regístrate en nuestra App</h3>
                        <button id="btn-registerApp" class="card-button" onclick="redirectRegister()">CONOCE
                            MÁS</button>
                    </div>
                </div>
                <div class="card">
                    <img src="./assets/images/card-2.png" alt="Abre tu Cuenta Fácil" class="card-img-2">
                    <div class="card-description" style="background-color: #FF7F41;">
                        <h3 style="margin-bottom:0; margin-top: 0;">Abre tu Cuenta Fácil</h3>
                        <button class="card-button" onclick="redirectCuenta()">CONOCE MÁS</button>
                    </div>
                </div>
                <div class="card">
                    <img data-src="./assets/images/card-3.png" alt="Solicita tu Crédito Fácil" class="card-img-3">
                    <div class="card-description" style="background-color: #F5B6CD;">
                        <h3 style="margin-bottom:0; margin-top: 0;">Solicita tu Crédito Fácil</h3>
                        <button class="card-button" onclick="redirectCredito()">CONOCE MÁS</button>
                    </div>
                </div>
                <div class="card">
                    <img data-src="./assets/images/card-4.png" alt="Solicita tu Tarjeta de Crédito" class="card-img-4">
                    <div class="card-description" style="background-color: #59CBE8;">
                        <h3 style="margin-bottom:0; margin-top: 0;">Solicita tu Tarjeta de Crédito</h3>
                        <button class="card-button" onclick="redirectTarjeta()">CONOCE MÁS</button>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button class="nav-button" id="btnNavegar" onclick="navigate()">QUIERO NAVEGAR <i
                        class="icon"></i></button>
            </div>
        </div>
        <div class="footer-container">
            <hr />
            <a href="#" class="privacy-link">Política de aceptación de datos personales</a>
        </div>
    </div>

    <script>
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

    function deleteCookie(name) {
        console.log(name);
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + window.location
            .hostname;
    }

    function navigate() {
        //let urlMist = getCookie("url")
        //window.location.href = urlMist;
        window.location.href = "https://www.google.com/";
    }

    function redirectRegister() {
        window.location.href = "https://prs.bam.com.gt/login#no-back-button";
    }

    function redirectCuenta() {
        window.location.href = "https://www.bam.com.gt/personas/cuentas/cuenta-facil/";
    }

    function redirectCredito() {
        window.location.href = "https://www.bam.com.gt/personas/prestamos/credito-facil/";
    }

    function redirectTarjeta() {
        window.location.href = "https://www.bam.com.gt/personas/tarjeta-de-credito/";
    }

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

        //Obteniendo el valor de la cookie "alertShow"
        let cookieShow = getCookie("alertShow");
        console.log(cookieShow);

        if (cookieShow != "") {
            Swal.fire({
                title: "Conexión exitosa",
                text: "Bienvenido a la red WiFi.",
                iconHtml: '<img src="./assets/images/check.svg">',
                icon: "success",
                width: 500,
                height: 200,
                confirmButtonText: "ENTENDIDO",
                confirmButtonColor: "#FDDA24",
                customClass: {
                    confirmButton: "confirm-button"
                },
                showCloseButton: true,
            }).then(function() {
                setCookie("alertShow", "", 1);
                deleteCookie("alertShow");
            });
        }

    });
    </script>
    <script>
    var cookieName = getCookie("name");
    document.getElementById("greeting").innerHTML = "Hola Velveth" + cookieName;
    </script>
</body>

</html>