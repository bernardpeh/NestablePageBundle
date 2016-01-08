<?php

namespace Bpeh\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Bpeh\NestablePageBundle\Entity\Page;

/**
 * Page controller.
 *
 * @Route("/bpeh_page")
 */
class PageController extends Controller
{

    private $entity;

    private $page_type;

    public function init()
    {
        $this->entity = $this->container->getParameter('bpeh_nestable_page.page_entity');
        $this->page_type = $this->container->getParameter('bpeh_nestable_page.page_type');
    }
    
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="bpeh_page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('bpeh_page_list'));
    }

        /**
     * Lists all nested page
     *
     * @Route("/list", name="bpeh_page_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rootMenuItems = $em->getRepository($this->entity)->findParent();

        return array(
            'tree' => $rootMenuItems,
        );
    }

    /**
     * reorder pages
     *
     * @Route("/reorder", name="bpeh_page_reorder")
     * @Method("POST")
     * @Template()
     */
    public function reorderAction()
    {
        $em = $this->getDoctrine()->getManager();
        // id of affected element
        $id = $this->get('request')->get('id');
        // parent Id
        $parentId = ($this->get('request')->get('parentId') == '') ? null : $this->get('request')->get('parentId');
        // new sequence of this element. 0 means first element.
        $position = $this->get('request')->get('position');

        $result = $em->getRepository($this->entity)->reorderElement($id, $parentId, $position); 

        return new JsonResponse(
            array('message' => $this->get('translator')->trans($result[0], array(), 'BpehNestablePageBundle')
, 'success' => $result[1])
        );

    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/", name="bpeh_page_create")
     * @Method("POST")
     * @Template("BpehNestablePageBundle:Page:new.html.twig")
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

            return $this->redirect($this->generateUrl('bpeh_page_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Page entity.
     *
     * @param Page $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Page $entity)
    {
        $form = $this->createForm(new $this->page_type(), $entity, array(
            'action' => $this->generateUrl('bpeh_page_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="bpeh_page_new")
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
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="bpeh_page_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="bpeh_page_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
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
    * Creates a form to edit a Page entity.
    *
    * @param Page $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Page $entity)
    {
        $form = $this->createForm(new $this->page_type(), $entity, array(
            'action' => $this->generateUrl('bpeh_page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}", name="bpeh_page_update")
     * @Method("PUT")
     * @Template("BpehNestablePageBundle:Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->entity)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bpeh_page_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}", name="bpeh_page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($this->entity)->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bpeh_page'));
    }

    /**
     * Creates a form to delete a Page entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bpeh_page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
