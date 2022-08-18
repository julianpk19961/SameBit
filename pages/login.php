<?php 
include './generales/header.php';
?>
<body class="align">
    <div class="grid">
        <!-- Logo -->
        <div class="top_imagen">
            <img src="<?php echo $index;?>img/SameinLogo.png" alt="logo" width="200px">
        </div>
        <!-- Welcome Message -->
        <!-- Login Form -->
        <form action="" id="login" method="POST" class="form login">
        <div class="welcome">
            <h1>BIENVENIDO</h1>
        </div>
            <div class="form__field">
                <label for="login__username"><svg class="icon">
                        <use xlink:href="#icon-user"></use>
                    </svg><span class="hidden">Username</span></label>
                <input autocomplete="username" id="login__username" type="text" name="username" class="form__input"
                    placeholder="Usuario" required>
            </div>

            <div class="form__field">
                <label for="login__password"><svg class="icon">
                        <use xlink:href="#icon-lock"></use>
                    </svg><span class="hidden">Password</span></label>
                <input id="login__password" type="password" name="password" class="form__input"
                    placeholder="Contrase&ntilde;a" required>
            </div>

            <div class="form__field">
            <button class="primary" type="submit" id="login" value="Iniciar Sesión">Iniciar Sesión</button>
            </div>
        </form>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" class="icons">

        <symbol id="icon-lock" viewBox="0 0 1792 1792">
            <path
                d="M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z" />
        </symbol>
        <symbol id="icon-user" viewBox="0 0 1792 1792">
            <path
                d="M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z" />
        </symbol>
    </svg>


    <script src="../Js/Login.Js"></script>

</body>

<div id="loader" class="lds-dual-ring hidden overlay"></div>

</html>

