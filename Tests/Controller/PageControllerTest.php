<?php

namespace Bpeh\NestablePageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class PageControllerTest extends WebTestCase
{
	protected static $application;

	protected function setUp()
	{
		self::getApplication()->run(new StringInput('doctrine:database:drop --force'));
		self::getApplication()->run(new StringInput('doctrine:database:create'));
		self::getApplication()->run(new StringInput('doctrine:schema:create'));
		self::getApplication()->run(new StringInput('doctrine:fixtures:load -n'));
	}
	protected static function getApplication()
	{
		if (null === self::$application) {
			$client = static::createClient();
			self::$application = new Application($client->getKernel());
			self::$application->setAutoExit(false);
		}
		return self::$application;
	}

	/**
	 * Test page list action
	 *
	 * scenario 1.11
	 */
	public function testListPages()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/bpeh_page/list');
		// i should see why_songbird text
		$this->assertContains(
			'why_songbird',
			$client->getResponse()->getContent()
		);
		// there should be 3 parent menus
		$nodes = $crawler->filterXPath('//div[@id="nestable"]/ol');
		$this->assertEquals(count($nodes->children()), 3);
		// there should be 2 entries under the about menu
		$nodes = $crawler->filterXPath('//li[@data-id="2"]/ol');
		$this->assertEquals(count($nodes->children()), 2);
	}

	/**
	 * Test page show action
	 *
	 * scenario 1.12
	 */
	public function testShowContactUsPage()
	{
		$client = static::createClient();
		// go to main listing page
		$crawler = $client->request('GET', '/bpeh_page/list');
		// click on contact_us link
		$crawler = $client->click($crawler->selectLink('contact_us')->link());
		// i should see "contact_us"
		$this->assertContains(
			'contact_us',
			$client->getResponse()->getContent()
		);
		// i should see "Created"
		$this->assertContains(
			'Created',
			$client->getResponse()->getContent()
		);
	}

	/**
	 * Test ajax submission by reordering menu
	 *
	 * scenario 1.13
	 */
	public function testReorderHomePage()
	{
		$client = static::createClient();
		// home is dragged under about and in the second position
		$crawler = $client->request(
			'POST',
			'/bpeh_page/reorder',
			array(
				'id' => 1,
				'parentId' => 2,
				'position' => 1
			),
			array(),
			array('HTTP_X-Requested-With' => 'XMLHttpRequest')
		);
		// i should get a success message in the returned content
		$this->assertContains(
			'menu has been reordered successfully',
			$client->getResponse()->getContent()
		);
		// go back to page list again
		$crawler = $client->request('GET', '/bpeh_page/list');
		// there should be 2 parent menus
		$nodes = $crawler->filterXPath('//div[@id="nestable"]/ol');
		$this->assertEquals(count($nodes->children()), 2);
		// there should 3 items under the about menu
		$nodes = $crawler->filterXPath('//li[@data-id="2"]/ol');
		$this->assertEquals(count($nodes->children()), 3);
	}

	/**
	 * Test page edit action
	 *
	 * scenario 1.14
	 */
	public function testEditHomePage()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/bpeh_page/1/edit');
		$form = $crawler->selectButton('Edit')->form(array(
			'page[slug]'  => 'home1',
		));
		$client->submit($form);
		// go back to the list again and i should see the slug updated
		$crawler = $client->request('GET', '/bpeh_page/list');
		$this->assertContains(
			'home1',
			$client->getResponse()->getContent()
		);
	}

	/**
	 * Test new and delete action for both page and pagemeta
	 *
	 * scenario 1.15
	 */
	public function testCreateDeleteTestPage()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/bpeh_page/new');
		$form = $crawler->selectButton('Create')->form(array(
			'page[slug]'  => 'test_page',
			'page[isPublished]'  => true,
			'page[sequence]'  => 1,
			'page[parent]'  => 2,
		));
		$client->submit($form);
		// we should now be in the show page
		$crawler = $client->followRedirect();
		$this->assertContains(
			'test_page',
			$client->getResponse()->getContent()
		);
		// we should see a new Add PageMeta link
		$crawler = $client->click($crawler->selectLink('Add PageMeta')->link());
		// at create new pagemeta page. new test_page is id 6
		$form = $crawler->selectButton('Create')->form(array(
			'page_meta[page_title]'  => 'test page title',
			'page_meta[menu_title]'  => 'test menu title',
			'page_meta[short_description]'  => 'short content',
			'page_meta[content]'  => 'long content',
			'page_meta[page]'  => 6,
		));
		$crawler = $client->submit($form);
		// follow redirect to show pagemeta
		$crawler = $client->followRedirect();
		$this->assertContains(
			'short content',
			$client->getResponse()->getContent()
		);
		// at show pagemeta, click delete
		$form = $crawler->selectButton('Delete')->form();
		$crawler = $client->submit($form);
		// go back to the pagemeta list again and i should NOT see the test_page anymore
		$crawler = $client->request('GET', '/bpeh_pagemeta');
		$this->assertNotContains(
			'test page title',
			$client->getResponse()->getContent()
		);
	}

	/**
	 * If we remove a page, the associated pagemeta should be removed as well.
	 *
	 * scenario 1.16
	 */
	public function testDeleteContactUsPage()
	{
		$client = static::createClient();
		// now if we remove contact_us page, ie id 5, all its page meta should be deleted
		$crawler = $client->request('GET', '/bpeh_page/5');
		$form = $crawler->selectButton('Delete')->form();
		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();
		$this->assertNotContains(
			'contact_us',
			$client->getResponse()->getContent()
		);
		// we now connect to do and make sure the related pagemetas are no longer in the pagemeta table.
		$res = $client->getContainer()->get('doctrine')->getRepository('BpehNestablePageBundle:PageMeta')->findByPage(5);
		$this->assertEquals(0, count($res));
	}

	/**
	 * check that there should only be 1 locale of each type per pagemeta
	 *
	 * scenario 1.16
	 */
	public function testSingleLocalePerPageMeta()
	{
		$client = static::createClient();
		// go to about page
		$crawler = $client->request('GET', '/bpeh_page/2');
		$crawler = $client->click($crawler->selectLink('Add PageMeta')->link());
		// for existing data, we know that en and fr has already been defined. if we try adding a new pagemeta now, it should fail
		$form = $crawler->selectButton('Create')->form(array(
			'page_meta[page_title]'  => 'test page title',
			'page_meta[menu_title]'  => 'test menu title',
			'page_meta[short_description]'  => 'short content',
			'page_meta[content]'  => 'long content',
			'page_meta[page]'  => 2,
		));
		$crawler = $client->submit($form);
		$this->assertContains(
			'Sorry, there is already a pagemeta with this locale',
			$client->getResponse()->getContent()
		);
	}
}

