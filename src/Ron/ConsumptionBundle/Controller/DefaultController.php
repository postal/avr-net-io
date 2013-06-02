<?php

namespace Ron\ConsumptionBundle\Controller;

use Ron\ConsumptionBundle\Entity\Consumption;
use Ron\ConsumptionBundle\Entity\Energy;
use Ron\ConsumptionBundle\Entity\Gas;
use Ron\ConsumptionBundle\Entity\Water;
use Ron\ConsumptionBundle\Form\ConsumptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $energyList = $em->getRepository('RonConsumptionBundle:Energy')->findAll();
        $gasList = $em->getRepository('RonConsumptionBundle:Gas')->findAll();
        $waterList = $em->getRepository('RonConsumptionBundle:Water')->findAll();

        $data = array(
          'energy' => $energyList,
          'gas' => $gasList,
          'water' => $waterList,
        );

        return $this->render('RonConsumptionBundle:Consumption:index.html.twig', $data);
    }

    public function newAction()
    {
        $form = $this->createForm(new ConsumptionType());
        $data = array(
            'form' => $form->createView()
        );
        return $this->render('RonConsumptionBundle:Consumption:new.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $entity = new Consumption();
        $form = $this->createForm(new ConsumptionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $gas = new Gas();
            $gas->setCreatedAt(new \DateTime());
            $gas->setVerbrauch($data->getGas());
            $energy = new Energy();
            $energy->setCreatedAt(new \DateTime());
            $energy->setValue($data->getEnergy());
            $water = new Water();
            $water->setValue($data->getWater());
            $water->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($gas);
            $em->persist($energy);
            $em->persist($water);
            $em->flush();

            return $this->redirect($this->generateUrl('RonConsumptionBundle_index'));
        }

        return $this->render('RonConsumptionBundle:Consumption:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
