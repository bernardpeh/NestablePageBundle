<?php

namespace Bpeh\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Bpeh\NestablePageBundle\Model\PageMetaBase as PageMeta;

/**
 * PageMeta controller.
 *
 * @Route("/bpeh_pagemeta")
 */
class PageMetaController extends Controller
{

	private $entity;

	private $entity_meta;

	private $page_meta_form_type;

	private $pagemeta_view_index;

	private $pagemeta_view_new;

	private $pagemeta_view_edit;

	private $pagemeta_view_show;

	public function init()
	{
		$this->entity = $this->container->getParameter('bpeh_nestable_page.page_entity');
		$this->entity_meta = $this->container->getParameter('bpeh_nestable_page.pagemeta_entity');
		$this->page_meta_form_type = $this->container->getParameter('bpeh_nestable_page.pagemeta_form_type');
		$this->pagemeta_view_index = $this->container->getparameter('bpeh_nestable_page.pagemeta_view_index');
		$this->pagemeta_view_new = $this->container->getparameter('bpeh_nestable_page.pagemeta_view_new');
		$this->pagemeta_view_edit = $this->container->getparameter('bpeh_nestable_page.pagemeta_view_edit');
		$this->pagemeta_view_show = $this->container->getparameter('bpeh_nestable_page.pagemeta_view_show');
	}

	/**
	 * Lists all PageMeta entities.
	 *
	 * @Route("/", name="bpeh_pagemeta_index")
	 * @Method("GET")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();

		$pageMetas = $em->getRepository($this->entity_meta)->findAll();

		return $this->render($this->pagemeta_view_index, array(
			'pageMetas' => $pageMetas,
		));

	}

	/**
	 * Creates a new PageMeta entity.
	 *
	 * @Route("/new", name="bpeh_pagemeta_new")
	 * @Method({"GET", "POST"})
	 */
	public function newAction(Request $request)
	{
		$pageMeta = new $this->entity_meta();
		$form = $this->createForm($this->page_meta_form_type, $pageMeta);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			if ( $em->getRepository( $this->entity_meta )->findPageMetaByLocale( $pageMeta->getPage(), $pageMeta->getLocale() ) ) {
				$this->get('session')->getFlashBag()->add( 'error', $this->get('translator')->trans('one_locale_per_pagemeta_only', array(), 'BpehNestablePageBundle') );
			} else {
				$em->persist( $pageMeta );
				$em->flush();
				return $this->redirectToRoute( 'bpeh_pagemeta_show', array( 'id' => $pageMeta->getId() ) );
			}
		}

		return $this->render($this->pagemeta_view_new, array(
			'pageMeta' => $pageMeta,
			'form' => $form->createView(),
		));

	}

	/**
	 * Finds and displays a PageMeta entity.
	 *
	 * @Route("/{id}", name="bpeh_pagemeta_show")
	 * @Method("GET")
	 */
	public function showAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$pageMeta = $em->getRepository($this->entity_meta)->find($request->get('id'));

		$deleteForm = $this->createDeleteForm($pageMeta);

		return $this->render($this->pagemeta_view_show, array(
			'pageMeta' => $pageMeta,
			'delete_form' => $deleteForm->createView(),
		));
	}

	/**
	 * Displays a form to edit an existing PageMeta entity.
	 *
	 * @Route("/{id}/edit", name="bpeh_pagemeta_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$pageMeta = $em->getRepository($this->entity_meta)->find($request->get('id'));
		$origId = $pageMeta->getPage()->getId();
		$origLocale = $pageMeta->getLocale();

		$deleteForm = $this->createDeleteForm($pageMeta);
		$editForm = $this->createForm($this->page_meta_form_type, $pageMeta);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {

			$error = false;

			// if page and local is the same, dont need to check locale count
			if ($origLocale == $pageMeta->getLocale() && $origId == $pageMeta->getPage()->getId()) {
				// all good
			}
			elseif ( $em->getRepository( $this->entity_meta )->findPageMetaByLocale( $pageMeta->getPage(), $pageMeta->getLocale(), true ) ) {
				$this->get('session')->getFlashBag()->add( 'error', $this->get('translator')->trans('one_locale_per_pagemeta_only', array(), 'BpehNestablePageBundle') );
				$error = true;
			}

			// if everything is successful
			if (!$error) {
				$em->persist( $pageMeta );
				$em->flush();
				return $this->redirectToRoute( 'bpeh_pagemeta_edit', array( 'id' => $pageMeta->getId() ) );
			}
		}

		return $this->render($this->pagemeta_view_edit, array(
			'pageMeta' => $pageMeta,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		));
	}

	/**
	 * Deletes a PageMeta entity.
	 *
	 * @Route("/{id}", name="bpeh_pagemeta_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$pageMeta = $em->getRepository($this->entity_meta)->find($request->get('id'));
		$form = $this->createDeleteForm($pageMeta);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($pageMeta);
			$em->flush();
		}

		return $this->redirectToRoute('bpeh_pagemeta_index');
	}

	/**
	 * Creates a form to delete a PageMeta entity.
	 *
	 * @param PageMeta $pageMetum The PageMeta entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(PageMeta $pageMeta)
	{
		return $this->createFormBuilder()
		            ->setAction($this->generateUrl('bpeh_pagemeta_delete', array('id' => $pageMeta->getId())))
		            ->setMethod('DELETE')
		            ->getForm()
			;
	}
}
