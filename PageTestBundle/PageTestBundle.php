<?php

namespace Bpeh\NestablePageBundle\PageTestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PageTestBundle extends Bundle
{
	// use a child bundle
	public function getParent()
    {
		return 'BpehNestablePageBundle';
    }
}
