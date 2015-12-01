<?php

namespace Umcms;

use Yii;

class Module extends \yii\base\Module {

	public $controllerNamespace = 'Umcms\controllers';
	protected $config = [];

	public function init()
	{
		parent::init();
		$this->registerPathAliases()
			->loadConfig()
			->registerModules();
	}

	protected function registerModules()
	{
		if (array_key_exists('modules', $this->config)) {
			$this->setModules($this->config['modules']);
		}

		return $this;
	}

	protected function loadConfig()
	{
		$pathToConfig = Yii::getAlias('@umcms/config');
		if (file_exists($pathToConfig)) {
			$this->config = include($pathToConfig);
		}

		return $this;
	}

	protected function registerPathAliases()
	{
		Yii::setAlias("@umcms", __DIR__);
		Yii::setAlias("@umcms/config", '@umcms/config/main.php');
		return $this;
	}


}
