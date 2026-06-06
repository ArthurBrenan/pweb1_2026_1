<!doctype html>
<html lang="pt-BR">
    <head>
     <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: #212529;">
    <div class="row d-flex justify-content-center align-items-center rounded bg-dark p-3 text-white shadow-lg">

        <div class="col-3 ">
            <header class="d-flex rounded justify-content-left">
                <a class="text-decoration-none" href="#"
                    style=" font-family: sans-serif; font-size: 2rem; color: #e0e0d1; letter-spacing: 0,2em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">POST
                    MORTEM
                </a>
            </header>
        </div>


        <div class="col-3"></div>
        <div class="col-6">
            <nav class="navbar d-flex justify-content-between rounded">
                <div class="nav-scroller d-flex justify-content-around w-100"
                    style=" font-family: sans-serif; font-size: 1rem; color: #e0e0d1; letter-spacing: 0,1em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">
                    <a class="text-decoration-none" href="index.html" style="color: #e0e0d1;">PÁGINA INICIAL</a>
                    <a class="text-decoration-none" href="paginas/sobre.html" style="color: #e0e0d1;">SOBRE NÓS</a>
                    <a class="text-decoration-none" href="paginas/contato.html" style="color: #e0e0d1;">CONTATO</a>
                    <a class="text-decoration-none" href="paginas/diversa.html" style="color: #e0e0d1;">DIVERSA</a>
                </div>
            </nav>
        </div>
    </div>
        />
    </head>

    <?php

      if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

        function redirect($page, $time = 1500){
            echo "<script>
                setTimeout(()=>window.location.href='$page', $time);
            </script>";
        }


        function actionMessage($succes = "", $error = ""){
            if(!empty($success)){
                echo"<div class='alert alert-success' role='alert'><strong>$success</strong></div>"; 
            }if(!empty($error)){
                echo"<div class='alert alert-danger' role='alert'><strong>$error</strong></div>"; 
            }

        }

        function showValidationError($errors = []){
            if(!empty($errors)){
                echo "<div class='alert alert-danger' role='alert'><ul>";
                echo "<strong>Erros nos campos:</strong>";
                foreach ($errors as $error){
                    echo $error;
                }
                echo "</ul></div>";
            }
        }

        function getFormValue($field){
            return isset($_POST['nome']) ? $_POST['nome'] : '';
        }
    ?>
    

    <body>
        <div class="container">
            <div class="row">
               
