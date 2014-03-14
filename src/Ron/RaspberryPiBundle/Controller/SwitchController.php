<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 06.03.14
 * Time: 18:03
 */

namespace Ron\RaspberryPiBundle\Controller;


use Ron\RaspberryPiBundle\Form\SwitchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SwitchController extends Controller
{

    public function indexAction(Request $request)
    {
        $form = $this->createForm(new SwitchType());
        $form->handleRequest($request);

        if ($form->isValid()) {

            $i = 1;
            foreach ($form->getData() as $switch) {
                if ($switch) {
                    exec('sudo send ' . $this->container->getParameter('raspi_switch_code') . ' ' . $i . ' 1');
                } else {
                    exec('sudo send ' . $this->container->getParameter('raspi_switch_code') . ' ' . $i . ' 0');
                }
                $i++;
            }
            var_dump($form->getData());
        }

        $viewData = array(
            'form' => $form->createView()
        );
        return $this->render('RonRaspberryPiBundle:Switch:index.html.twig', $viewData);
    }
} 