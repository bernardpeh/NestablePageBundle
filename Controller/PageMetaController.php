<?php

namespace Bpeh\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bpeh\NestablePageBundle\Form\PageMetaType;
use Bpeh\NestablePageBundle\Entity\PageMeta;

/**
 * PageMeta controller.
 *
 * @Route("/bpeh_pagemeta")
 */
class PageMetaController extends Controller
{

    private $entity;

    private $pagemeta_type;

    public function init()
    {
        $this->entity = $this->container->getParameter('bpeh_nestable_page.pagemeta_entity');
        $this->pagemeta_type = $this->container->getParameter('bpeh_nestable_page.pagemeta_type');
    }

    /**
     * Lists all PageMeta entities.
     *
     * @Route("/page/{id}", name="bpeh_pagemeta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository($this->entity)->findByPage($id);

        return array(
            'entities' => $entities,
            'pageId' => $id
        );
    }
    /**
     * Creates a new PageMeta entity.
     *
     * @Route("/pagemeta", name="bpeh_pagemeta_create")
     * @Method("POST")
     * @Template("BpehNestablePageBundle:PageMeta:new.html.twig")
     */
    public function createAction(Request $request)
    {

        $entity = new $this->entity();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bpeh_pagemeta_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PageMeta entity.
     *
     * @param PageMeta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PageMeta $entity)
    {
        $form = $this->createForm(new $this->pagemeta_type(), $entity, array(
            'action' => $this->generateUrl('bpeh_pagemeta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PageMeta entity.
     *
     * @Route("/pagemeta/new", name="bpeh_pagemeta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        $entity = new $this->entity();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PageMeta entity.
     *
     * @Route("/pagemeta/{id}", name="bpeh_pagemeta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PageMeta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PageMeta entity.
     *
     * @Route("/pagemeta/{id}/edit", name="bpeh_pagemeta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PageMeta entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a PageMeta entity.
    *
    * @param PageMeta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PageMeta $entity)
    {
        $form = $this->createForm(new $this->pagemeta_type(), $entity, array(
            'action' => $this->generateUrl('bpeh_pagemeta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PageMeta entity.
     *
     * @Route("/pagemeta/{id}", name="bpeh_pagemeta_update")
     * @Method("PUT")
     * @Template("BpehNestablePageBundle:PageMeta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PageMeta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bpeh_pagemeta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PageMeta entity.
     *
     * @Route("/pagemeta/{id}", name="bpeh_pagemeta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->entity)->find($id);
            $pageId = $entity->getPage()->getId();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PageMeta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bpeh_pagemeta', array('id' => $pageId)));
    }

    /**
     * Creates a form to delete a PageMeta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bpeh_pagemeta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
