<?php

namespace Bpeh\NestablePageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
	 * @Route("/pagemeta", name="bpeh_pagemeta_index")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();

		$pageMetas = $em->getRepository($this->entity)->findAll();

		return array(
			'pageMetas' => $pageMetas,
		);
	}

	/**
	 * Creates a new PageMeta entity.
	 *
	 * @Route("/pagemeta/new", name="bpeh_pagemeta_new")
	 * @Method({"GET", "POST"})
	 * @Template()
	 */
	public function newAction(Request $request)
	{
		$pageMetum = new PageMeta();
		$form = $this->createForm('Bpeh\NestablePageBundle\Form\PageMetaType', $pageMetum);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($pageMetum);
			$em->flush();

			return $this->redirectToRoute('bpeh_pagemeta_show', array('id' => $pageMetum->getId()));
		}

		return array(
			'pageMetum' => $pageMetum,
			'form' => $form->createView(),
		);
	}

	/**
	 * Finds and displays a PageMeta entity.
	 *
	 * @Route("/pagemeta/{id}", name="bpeh_pagemeta_show")
	 * @Method({"GET", "POST"})
	 * @Template()
	 */
	public function showAction(PageMeta $pageMetum)
	{
		$deleteForm = $this->createDeleteForm($pageMetum);

		return array(
			'pageMetum' => $pageMetum,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Displays a form to edit an existing PageMeta entity.
	 *
	 * @Route("/pagemeta/{id}/edit", name="bpeh_pagemeta_edit")
	 * @Method({"GET", "POST"})
	 * @Template()
	 */
	public function editAction(Request $request, PageMeta $pageMetum)
	{
		$deleteForm = $this->createDeleteForm($pageMetum);
		$editForm = $this->createForm('Bpeh\NestablePageBundle\Form\PageMetaType', $pageMetum);
		$editForm->handleRequest($request);

		if ($editForm->isSubmitted() && $editForm->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($pageMetum);
			$em->flush();

			return $this->redirectToRoute('bpeh_pagemeta_edit', array('id' => $pageMetum->getId()));
		}

		return array(
			'pageMetum' => $pageMetum,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a PageMeta entity.
	 *
	 * @Route("/pagemeta/{id}", name="bpeh_pagemeta_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, PageMeta $pageMetum)
	{
		$form = $this->createDeleteForm($pageMetum);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($pageMetum);
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
	private function createDeleteForm(PageMeta $pageMetum)
	{
		return $this->createFormBuilder()
		            ->setAction($this->generateUrl('bpeh_pagemeta_delete', array('id' => $pageMetum->getId())))
		            ->setMethod('DELETE')
		            ->getForm()
			;
	}
}
