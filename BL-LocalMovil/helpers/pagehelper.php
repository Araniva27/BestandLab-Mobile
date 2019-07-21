<?php
/**
 * Clase donde contiene las plantillas del menú y pie de la página 
 */
class PageHelper
{
    /**
     * Método para imprimir el encabezado con su título de la página.
     * 
     * @param string $title que es el titulo de la página.
    */
    public static function header($title)
    {
        session_start(); // Reanuadar la sesión
        print '
            <!DOCTYPE html>
            <html>
            
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <title>BLab &middot; '.$title.' </title>
                <link rel="icon" href="../resources/img/logo/icon.png" type="image/png" sizes="16x16">
                <link rel="stylesheet" href="../resources/css/bootstrap.min.css">
                <link rel="stylesheet" href="../resources/css/dataTables.bootstrap4.min.css">
                <link rel="stylesheet" href="../resources/css/gijgo.min.css">
                <link rel="stylesheet" href="../resources/css/poppins.css">
                <link rel="stylesheet" href="../resources/css/mainStyles.css">
                <link rel="stylesheet" href="../resources/css/all.css" />
            </head>
            <body>
        ';
        $filename = basename($_SERVER['PHP_SELF']); //Obtener el nombre de la página
        if(isset($_SESSION['userId'])){
            if($filename == 'index.php' || $filename == 'register.php'){
                header('location: home.php');
            }
        } else {
            if($filename != 'index.php' && $filename != 'register.php'){
                header('location: index.php');
            } 
        }
    }
    /**
     * Método para imprimir el menú de la página.
    */
    public static function navbar()
    {
        print '
            <header>
                <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm p-3 mb-5">
                    <a class="navbar-brand" href="home.php">
                        BestandLab
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse scrollbar-info" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto " id="actionUser">
            
                        </ul>
                        <div class="float-right d-md-none">
                            <a href="editProfile.php">
                                <img data-toggle="tooltip" data-placement="bottom" title="'.$_SESSION['userName'].'" src="../resources/img/user/'.$_SESSION['userPic'].'" class="rounded-circle" alt="Usuario" width="60" height="60" />
                            </a>
                        </div>
                    </div>
                    <div class="float-right d-none d-lg-block">
                        <a href="#" class="dropdown-toggle" id="user-profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img data-toggle="tooltip" data-placement="bottom" title="'.$_SESSION['userName'].'" src="../resources/img/user/'.$_SESSION['userPic'].'" class="rounded-circle" alt="Usuario" width="60" height="60" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="user-profile">
                            <a class="dropdown-item" href="editProfile.php"><i class="fas fa-edit fa-sm fa-fw mr-2"></i>Mis Datos</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="signOff()"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Cerrar Sesión</a>    
                        </div>
                    </div>
                </nav>
            </header>
            <main>
            ';
        }
        /**
         * Método para imprimir el pie de la página
         */
        public static function footer() 
        {
            print '
            <footer class="page-footer fixed-bottom">
                <div class="container-fluid">
                    <div class="row h-100 mx-auto justify-content-center align-items-center">
                    
                        <!-- Contiene la información del sistema y otros textos -->
                    <div class="col-md-4 col-sm-12 mt-2" id="infoText">
                        <h1>BestandLab</h1>
                        <p class="text-justify">Es el sistema de seguimiento y orden de inventario especialmente diseñado de
                            acuerdo a las
                            necesidades
                            y preferencias de FarLab, sirviendo de gran uso a la hora de controlar <b
                                style="font-family: \'Archivo-Bold\';"">AYUDA NO SÉ QUÉ PONER</b>
                        </p>
                    </div>
    
                    <!-- Contiene el logo -->
                    <div class=" col-md-4 col-sm-12 h-100 my-3 id=" infoMisc">
                                <img src="../resources/img/farlab.png" alt="FarLab" class="img-fluid">
                    </div>
    
                    <!-- Contiene datos de contactos y redes sociales -->
                    <div class="col-md-4 col-sm-12 h-100" id="infoContact">
                        <h1>Nuestras redes sociales</h1>
                        <div class="container">
                            <div class="row ">
                                <div class="col-3">
                                    <a href="index.php">
                                        <i class="fab fa-facebook-square fa-2x white-text ml-2"></i>
                                    </a>
                                </div>
                                <div class="col-9">
                                    <a href="index.php">
                                        <p id="infoContactDet">Facebook</p>
                                    </a>
                                </div>
                                <div class="col-3">
                                    <a href="index.php">
                                        <i class="fa fa-desktop fa-2x white-text ml-2"></i>
                                    </a>
                                </div>
                                <div class="col-9">
                                    <a href="index.php">
                                        <p id="infoContactDet">Sitio web</p>
                                    </a>
                                </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
        </footer>
            ';
        }
        /**
         * Método para imprimir los archivos javascript necesarios para la página
         * 
         * @param string $controller que es el nombre del controlador para la página
         */
        public static function script($controller){
            print ' 
            </main>
            <footer>    
                <script src="../resources/js/jquery-3.3.1.min.js"></script>
                <script src="../resources/js/popper.min.js"></script>
                <script src="../resources/js/bootstrap.min.js"></script>
                <script src="../resources/js/sweetalert.min.js"></script>
                <script defer src="../resources/js/solid.js"></script>
                <script src="../resources/js/all.js"></script>
                <script src="../resources/js/sweetalert.min.js"></script>
                <script src="../resources/js/gijgo.min.js"></script>
                <script src="../resources/js/jqBootstrapValidation.js"></script>
                <script src="../core/helpers/components.js"></script>
                <script src="../core/helpers/validator.js"></script>
                <script src="../core/helpers/components.js"></script>
            ';
            $filename = basename($_SERVER['PHP_SELF']); // Obtener el nombre de la página
            if($filename != 'index.php' && $filename != 'register.php'){
                print '
                    <script src="../core/controllers/account.js"></script>
                    <script src="../resources/js/jquery.dataTables.min.js"></script>
                    <script src="../resources/js/dataTables.bootstrap4.min.js"></script>
                    <script src="../core/controllers/account.js"></script>
                ';
            }       
            print '
                <script src="../core/controllers/'.$controller.'"></script>
            </footer>
            </body>
        </html>';
        }
    }

?>