<?php

/*
 * This file is part of kvothe/reply-to-see.
 *
 * Copyright (c) 2019
 * Original Extension by WiseClock 
 * Updated by Kvothe
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Irony\Login\See;

use Flarum\Extend;
use Flarum\Api\Event\Serializing;
use Illuminate\Contracts\Events\Dispatcher;
use s9e\TextFormatter\Configurator;
use Irony\Login\See\Listeners\Login2See;

return [
	(new Extend\Frontend('forum'))
		->js(__DIR__ . '/js/dist/forum.js')
		->css(__DIR__ . '/resources/less/forum.less'),

	(new Extend\Formatter)
		->configure(function (Configurator $config) {
			$config->BBcodes->addCustom(
				'[LOGIN]{TEXT}[/LOGIN]',
				'<login2see>{TEXT}</login2see>'
			);
		}),

	new Extend\Locales(__DIR__ . '/resources/locale'),

	function (Dispatcher $events) {
		$events->listen(Serializing::class, Login2See::class);
	}


];
