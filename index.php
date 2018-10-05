<?php

require_once 'app/core.php';        // Llamado del Core [instancias de clases, helpers].

$Error = NULL;                                 //Control de errores @string.

// Mastercontroller

if (isset($_GET['viewController']))
{

    $modulo = strtolower($_GET['viewController']);

    $folder = 'app/modules/'.$modulo;
    $controller = strtolower($modulo);

    if(is_dir($folder))
    {
        require_once $folder.'/controller.php';

        $class = ucfirst($modulo).'Controller';
        
        if(class_exists($class))
        {
            $viewController = new $class();
            $vista = 'default';

            if(isset($_GET['data']))
            {
                $vista = strtolower($_GET['data']);

                if(strlen($_GET['data']) != 0 && __validate_url__($_GET['data']))
                {
                    $viewController->render($vista);
                }else
                {
                    header('Location: '.web_path().$_GET['viewController']);
                }
            }else
            {
                $viewController->render($vista);
            }
        }else
        {
            print "Clase <b>Controller</b> para modulo <b>".ucfirst($modulo)."</b> no declarada.<a href='".web_path()."'>Atras</a>";
            #redirect('Home');
        }
    }else
    {
        redirect(web_path()."Error");
    }
}else
{
    redirect(web_path()."Login");
}
?>
