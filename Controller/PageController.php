<?php

namespace Songbird\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Songbird\NestablePageBundle\Entity\Page;
use Songbird\NestablePageBundle\Form\PageType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Page controller.
 *
 * @Route("/songbird_page")
 */
class PageController extends Controller
{

    /**
     * Lists all Page entities.
     *
     * @Route("/", name="songbird_page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();

        // $entities = $em->getRepository('SongbirdNestablePageBundle:Page')->findAll();

        // return array(
        //     'entities' => $entities,
        // );
        return $this->redirect($this->generateUrl('songbird_page_list'));
    }

        /**
     * Lists all nested page
     *
     * @Route("/list", name="songbird_page_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $rootMenuItems = $em->getRepository('SongbirdPageBundle:Page')->findParentByLocale($this->get('request')->getLocale());
        $rootMenuItems = $em->getRepository('SongbirdNestablePageBundle:Page')->findParent();

        return array(
            'tree' => $rootMenuItems,
        );
    }

    /**
     * reorder pages
     *
     * @Route("/reorder", name="songbird_page_reorder")
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

        $result = $em->getRepository('SongbirdNestablePageBundle:Page')->reorderElement($id, $parentId, $position); 

        return new JsonResponse(
            array('message' => $this->get('translator')->trans($result[0], array(), 'SongbirdNestablePageBundle')
, 'success' => $result[1])
        );

    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/", name="songbird_page_create")
     * @Method("POST")
     * @Template("SongbirdNestablePageBundle:Page:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Page();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('songbird_page_show', array('id' => $entity->getId())));
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
        $form = $this->createForm(new PageType(), $entity, array(
            'action' => $this->generateUrl('songbird_page_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="songbird_page_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}", name="songbird_page_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

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
     * @Route("/{id}/edit", name="songbird_page_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

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
        $form = $this->createForm(new PageType(), $entity, array(
            'action' => $this->generateUrl('songbird_page_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}", name="songbird_page_update")
     * @Method("PUT")
     * @Template("SongbirdNestablePageBundle:Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('songbird_page_edit', array('id' => $id)));
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
     * @Route("/{id}", name="songbird_page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SongbirdNestablePageBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('songbird_page'));
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
            ->setAction($this->generateUrl('songbird_page_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
