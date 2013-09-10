<?php
/**
 * @package AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die();

class ArsDispatcher extends FOFDispatcher
{
	public $defaultView = 'cpanels';

	public function onBeforeDispatch()
	{
		// You can't fix stupid… but you can try working around it
		if ((!function_exists('json_encode')) || (!function_exists('json_decode')))
		{
			require_once JPATH_ADMINISTRATOR . '/components/' . $this->component . '/helpers/jsonlib.php';
		}

		$result = parent::onBeforeDispatch();

		if (!$result)
		{
			return $result;
		}

		$liveupdate_path = JPATH_ADMINISTRATOR . '/components/' . $this->component . '/liveupdate';
		// Live Update translation
		$jlang			 = JFactory::getLanguage();
		$jlang->load('liveupdate', $liveupdate_path, 'en-GB', true);
		$jlang->load('liveupdate', $liveupdate_path, $jlang->getDefault(), true);
		$jlang->load('liveupdate', $liveupdate_path, null, true);

		// Load Akeeba Strapper
		include_once JPATH_ROOT . '/media/akeeba_strapper/strapper.php';
		AkeebaStrapper::bootstrap();
		AkeebaStrapper::jQueryUI();
		AkeebaStrapper::addCSSfile('media://com_ars/css/backend.css');
		//AkeebaStrapper::addJSfile('media://com_ars/js/backend.js');

		return true;
	}

	public function dispatch()
	{
		// Handle Live Update requests
		if (!class_exists('LiveUpdate'))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_ars/liveupdate/liveupdate.php';
			if (($this->input->getCmd('view', '') == 'liveupdate'))
			{
				LiveUpdate::handleRequest();
				return true;
			}
		}

		parent::dispatch();
	}
}