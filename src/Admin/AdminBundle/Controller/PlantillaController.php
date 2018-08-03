<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\AdminBundle\Entity\LogProductos;
use Admin\AdminBundle\Html2Pdf\html2pdfs as html2pdf;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager as Manager;


class PlantillaController extends Controller
{
    //Dibujo la plantilla basica.
    public function plantillaBasicaAction($idlog)
    {
        //Traigo el log.
        $log  = $this->getDoctrine()
                     ->getRepository('AdminBundle:LogProductos')
                     ->find($idlog);

        //Si se encontro info.
        if ($log!=null)
        {
            //Decodifico la lista de codigos de barras de un json.        
            $productos = $log->getDatos();
            $productos = json_decode($productos);
            
            //Reordeno el array.
            $productos = array_chunk($productos, 3);

            //Si hay datos.
            if ($productos!=null)
            {
                //Obtengo el html del template.
                $html = $this->renderView('AdminBundle:Default:plantilllaCodes.html.twig',array('productos'=>$productos));

                //Gnero el pdf.
                $this->renderPdf($html);
            }
            else
            {
                echo 'No se encontro el log.';
                exit;                
            }
        }
        else
        {
            echo 'No se encontro el log.';
            exit;
        }       
    }

    //Convertir a pdf usando html2pdf bundle.
    private function renderPdf($html)
    {
        $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'en', true, 'UTF-8', array(10, 15, 10, 15));
        $html2pdf->WriteHTML($html);
        $html2pdf->Output('exemple.pdf');
        exit;
    }
}