<?php

namespace Asian\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AsianUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
