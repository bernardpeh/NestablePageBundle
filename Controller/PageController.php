<?php

namespace Bpeh\NestablePageBundle\Controller;

use Bpeh\NestablePageBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Page controller.
 *
 * @Route("/bpeh_page")
 */
class PageController extends Controller
{

    private $entity;

	private $entity_meta;

    private $page_form_type;

	private $page_view_list;

	private $page_view_new;

	private $page_view_edit;

	private $page_view_show;

    public function init()
    {
    	$this->entity = $this->container->getParameter('bpeh_nestable_page.page_entity');
	    $this->entity_meta = $this->container->getParameter('bpeh_nestable_page.pagemeta_entity');
        $this->page_form_type = $this->container->getParameter('bpeh_nestable_page.page_form_type');
	    $this->page_view_list = $this->container->getparameter('bpeh_nestable_page.page_view_list');
	    $this->page_view_new = $this->container->getparameter('bpeh_nestable_page.page_view_new');
	    $this->page_view_edit = $this->container->getparameter('bpeh_nestable_page.page_view_edit');
	    $this->page_view_show = $this->container->getparameter('bpeh_nestable_page.page_view_show');
    }

	/**
	 * Lists all Page entities.
	 *
	 * @Route("/", name="bpeh_page")
	 * @Method("GET")
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
	 *
	 * @return array
	 */
    public function listAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $rootMenuItems = $em->getRepository($this->entity)->findParent();

        return $this->render($this->page_view_list, array(
            'tree' => $rootMenuItems,
        ));
    }

	/**
	 * reorder pages
	 *
	 * @Route("/reorder", name="bpeh_page_reorder")
	 * @Method("POST")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function reorderAction(Request $request)
    {
	    $em = $this->getDoctrine()->getManager();
	    // id of affected element
	    $id = $request->get('id');
	    // parent Id
	    $parentId = ($request->get('parentId') == '') ? null : $request->get('parentId');
	    // new sequence of this element. 0 means first element.
	    $position = $request->get('position');

	    $result = $em->getRepository($this->entity)->reorderElement($id, $parentId, $position);

	    return new JsonResponse(
		    array('message' => $this->get('translator')->trans($result[0], array(), 'BpehNestablePageBundle')
		    , 'success' => $result[1])
	    );

    }

	/**
	 * Creates a new Page entity.
	 *
	 * @Route("/new", name="bpeh_page_new")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 *
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function newAction(Request $request)
    {
	    $page = new $this->entity();
	    $form = $this->createForm($this->page_form_type, $page);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		    $em = $this->getDoctrine()->getManager();
		    $em->persist($page);
		    $em->flush();

		    return $this->redirectToRoute('bpeh_page_show', array('id' => $page->getId()));
	    }

	    return $this->render($this->page_view_new, array(
		    'page' => $page,
		    'form' => $form->createView(),
	    ));
    }

	/**
	 * Finds and displays a Page entity.
	 *
	 * @Route("/{id}", name="bpeh_page_show")
	 * @Method("GET")
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function showAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$page = $em->getRepository($this->entity)->find($request->get('id'));

		$pageMeta = $em->getRepository($this->entity_meta)->findPageMetaByLocale($page,$request->getLocale());

		$deleteForm = $this->createDeleteForm($page);

		return $this->render($this->page_view_show, array(
			'page' => $page,
			'pageMeta' => $pageMeta,
			'delete_form' => $deleteForm->createView(),
		));

	}

	/**
	 * Displays a form to edit an existing Page entity.
	 *
	 * @Route("/{id}/edit", name="bpeh_page_edit")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 *
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function editAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$page = $em->getRepository($this->entity)->find($request->get('id'));
		$deleteForm = $this->createDeleteForm($page);
		$editForm = $this->createForm($this->page_form_type, $page);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($page);
			$em->flush();

			return $this->redirectToRoute('bpeh_page_edit', array('id' => $page->getId()));
		}

		return $this->render($this->page_view_edit, array(
			'page' => $page,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		));

	}

	/**
	 * Deletes a Page entity.
	 *
	 * @Route("/{id}", name="bpeh_page_delete")
	 * @Method("DELETE")
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$page = $em->getRepository($this->entity)->find($request->get('id'));
		$form = $this->createDeleteForm($page);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($page);
			$em->flush();
		}

		return $this->redirectToRoute('bpeh_page_list');
	}

	/**
	 * Creates a form to delete a Page entity.
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Page $page)
	{
		return $this->createFormBuilder()
		            ->setAction($this->generateUrl('bpeh_page_delete', array('id' => $page->getId())))
		            ->setMethod('DELETE')
		            ->getForm()
			;
	}

}
