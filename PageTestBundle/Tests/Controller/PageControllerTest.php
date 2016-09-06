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
	 * scenario 1.11
	 *
	 * Test list action
	 */
	public function testListPages()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/pagetest/list');
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
	 * scenario 1.12
	 *
	 * Test show action
	 */
	public function testShowContactUsPage()
	{
		$client = static::createClient();
		// go to main listing page
		$crawler = $client->request('GET', '/pagetest/list');
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
	 * scenario 1.13
	 *
	 * We simulate ajax submission by reordering menu
	 */
	public function testReorderHomePage()
	{
		$client = static::createClient();

		// home is dragged under about and in the second position
		$crawler = $client->request(
			'POST',
			'/songbird_page/reorder',
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
		$crawler = $client->request('GET', '/pagetest/list');
		// there should be 2 parent menus
		$nodes = $crawler->filterXPath('//div[@id="nestable"]/ol');
		$this->assertEquals(count($nodes->children()), 2);
		// there should 3 items under the about menu
		$nodes = $crawler->filterXPath('//li[@data-id="2"]/ol');
		$this->assertEquals(count($nodes->children()), 3);
	}

	/**
	 * scenario 1.14
	 *
	 * Test edit action
	 */
	public function testEditHomePage()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/pagetest/1/edit');

		$form = $crawler->selectButton('Edit')->form(array(
			'page[slug]'  => 'home1',
		));

		$client->submit($form);

		// go back to the list again and i should see the slug updated
		$crawler = $client->request('GET', '/pagetest/list');
		$this->assertContains(
			'home1',
			$client->getResponse()->getContent()
		);
	}

	/**
	 * scenario 1.15
	 *
	 * Test new and delete action
	 */
	public function testCreateDeleteTestPage()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/pagetest/new');

		$form = $crawler->selectButton('Create')->form(array(
			'page[slug]'  => 'test_page',
			'page[isPublished]'  => true,
			'page[sequence]'  => 1,
			'page[parent]'  => 2,
		));

		$client->submit($form);

		// go back to the list again and i should see the slug updated
		$crawler = $client->request('GET', '/songbird_page/list');
		$this->assertContains(
			'test_page',
			$client->getResponse()->getContent()
		);

		$crawler = $client->click($crawler->selectLink('Create New PageMeta')->link());
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
		$crawler = $client->request('GET', '/pagetest_pagemeta');

		$this->assertNotContains(
			'test page title',
			$client->getResponse()->getContent()
		);
	}

	/**
	 * scenario 17.16
	 */
	public function testDeleteContactUsPage()
	{
		$client = static::createClient();
		// now if we remove contact_us page, ie id 5, all its page meta should be deleted
		$crawler = $client->request('GET', '/pagetest/5');
		$form = $crawler->selectButton('Delete')->form();
		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		$this->assertNotContains(
			'contact_us',
			$client->getResponse()->getContent()
		);

		// we now connect to do and make sure the related pagemetas are no longer in the pagemeta table.
		$res = $client->getContainer()->get('doctrine')->getRepository('SongbirdNestablePageBundle:PageMeta')->findByPage(5);
		$this->assertEquals(0, count($res));
	}
}


