<?php if (!defined('APPLICATION')) exit();

$PluginInfo['MobileSearch'] = array(
	'Name' => 'Mobile Search',
	'Description' => 'Adds a search menu item to the standard Vanilla Mobile theme. You choose whether it is displayed as text or an icon button.',
	'Version' 	=>	 '1.0.3',
	'MobileFriendly' => TRUE,
	'Author' 	=>	 "Matt Sephton",
	'AuthorEmail' => 'matt@gingerbeardman.com',
	'AuthorUrl' =>	 'http://www.vanillaforums.org/profile/matt',
	'License' => 'GPL v2',
	'SettingsUrl' => '/settings/mobilesearch',
	'SettingsPermission' => 'Garden.Settings.Manage',
	'RequiredApplications' => array('Vanilla' => '>=2'),
);

class MobileSearchPlugin implements Gdn_IPlugin {

	public function SettingsController_MobileSearch_Create($Sender, $Args = array()) {
		$Sender->Permission('Garden.Settings.Manage');
		$Sender->SetData('Title', T('Mobile Search'));

		$Cf = new ConfigurationModule($Sender);
		$Cf->Initialize(array(
			'Plugins.MobileSearch.Search' => array('Description' => 'Display the Mobile Search menu item as:', 'Control' => 'DropDown', 
			'Items' => array('' => 'Icon', 'asword' => 'Text'))
		));

		$Sender->AddSideMenu('dashboard/settings/plugins');
		$Cf->RenderAll();
	}

	public function Base_Render_Before($Sender) {
		$this->_MobileSearchSetup($Sender);
		$this->_AddButton($Sender, 'Discussion');
	}
	
	private function _AddButton($Sender, $ButtonType) {
		$Session = Gdn::Session();
		if (IsMobile() && is_object($Sender->Menu)) {
			if ($this->SearchIcon == '' && $Session->IsValid()) {
				$Sender->Menu->AddLink('MobileSearch', Img('plugins/MobileSearch/design/images/search.png', array('alt' => T('Search'))), '/search', '', array('class' => 'MobileSearch'));
			} else {
				$Sender->Menu->AddLink('MobileSearch', 'Search', '/search', '');
			}
		}
	}
	
	private function _MobileSearchSetup($Sender) {
		static $MobileSearch;
		if (!$MobileSearch)
			$this->SearchIcon = C('Plugins.MobileSearch.Search');

		if (IsMobile() && is_object($Sender->Head)) {
			$Sender->AddCssFile('mobilesearch.css', 'plugins/MobileSearch');
			if ($this->SearchIcon == '')
				$Sender->AddCssFile('icon.css', 'plugins/MobileSearch');
		}
	}
	
	public function Setup() {
		return TRUE;
	}
	
}
?>