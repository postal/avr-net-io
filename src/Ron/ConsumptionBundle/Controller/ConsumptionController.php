<?php

namespace Ron\ConsumptionBundle\Controller;

use Ron\ConsumptionBundle\Entity\ConsumptionImport;
use Ron\ConsumptionBundle\Model\ConsumptionCalculation;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ron\ConsumptionBundle\Entity\Consumption;
use Ron\ConsumptionBundle\Form\ConsumptionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Consumption controller.
 *
 */
class ConsumptionController extends Controller
{
    /**
     * Lists all Consumption entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var $repo \Ron\ConsumptionBundle\Entity\ConsumptionRepository
         */
        $repo = $em->getRepository('RonConsumptionBundle:Consumption');
        # $entities = $repo->findAll();
        $entities = $repo->findBy(array(), array('createDate' => 'ASC'));
        #$entities = $repo->findBy(array());


        #      var_dump($entities);
        foreach ($entities as $entity) {
            #     echo $entity->getCreateDate()->format();
            $data[] = array(
                $entity->getCreateDate()->format('d.m.Y'),
                round($entity->getWater()),
                round($entity->getEnergy()),
                round($entity->getGas()),
            );
        }

        $calc = new ConsumptionCalculation();
        $dataNew = $calc->getConsumptionMonthly($entities);
        #  var_dump($dataNew);
        #  var_dump($data);

        return $this->render('RonConsumptionBundle:Consumption:index.html.twig', array(
            'entities' => $entities,
            'data' => $data,
            'dataAvg' => $dataNew,
        ));
    }

    /**
     * Creates a new Consumption entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Consumption();
        $form = $this->createForm(new ConsumptionType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('consumption', array('id' => $entity->getId())));
        }


        return $this->render('RonConsumptionBundle:Consumption:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
#            'data' => $data,
        ));
    }

    /**
     * Displays a form to create a new Consumption entity.
     *
     */
    public function newAction()
    {
        $entity = new Consumption();
        $entity->setCreateDate(new \DateTime());
        $form = $this->createForm(new ConsumptionType(), $entity);

        return $this->render('RonConsumptionBundle:Consumption:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Consumption entity.

    RonConsumptionBundle_gas:
    resource: "@RonConsumptionBundle/Resources/config/routing/gas.yml"
    prefix:   /gas
    RonConsumptionBundle_energy:
    resource: "@RonConsumptionBundle/Resources/config/routing/energy.yml"
    prefix:   /energy
    RonConsumptionBundle_water:
    resource: "@RonConsumptionBundle/Resources/config/routing/water.yml"
    prefix:   /water
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Consumption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consumption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Consumption:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Consumption entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Consumption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consumption entity.');
        }

        $editForm = $this->createForm(new ConsumptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Consumption:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Consumption entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Consumption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Consumption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ConsumptionType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('consumption_edit', array('id' => $id)));
        }

        return $this->render('RonConsumptionBundle:Consumption:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Consumption entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RonConsumptionBundle:Consumption')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Consumption entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('consumption'));
    }

    /**
     * Creates a form to delete a Consumption entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    public function importAction(Request $request)
    {
#        $import = new ConsumptionImport();
        $builder = $this->createFormBuilder(array());
        $form = $builder->add('import_file', 'file')
            ->add('name')->setAttribute('csrf', false)->getForm();

        $form->submit($request);

        if ($form->isValid()) {
            $data = $form->getData();
            /**
             * @var $file UploadedFile
             */
            $file = $data['import_file'];
            var_dump($data);
            #$file->move('/tmp/','import.csv');
            #           $file->openFile('r')->fgetcsv(';')
            #var_dump($file);
#$fileContent = file_get_contents('/tmp/import.csv');
            while ($row = $file->openFile()->fgetcsv(';')) {
                var_dump($row);
                $content[] = $row;
            }
            var_dump($content);
#var_dump($file->openFile()->fgetcsv(';'));
            #$file = $form->getExtraData();
#            var_dump($data);
#           var_dump($file);
        } else {
            $form->getErrors();

        }

        return $this->render('RonConsumptionBundle:Consumption:import.html.twig', array('form' => $form->createView()));

    }
}
