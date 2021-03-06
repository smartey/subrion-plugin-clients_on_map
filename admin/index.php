<?php

class iaBackendController extends iaAbstractControllerPluginBackend
{
	protected $_name = 'clients_on_map';

	protected $_table = 'clients_on_map';

	protected function _indexPage(&$iaView)
	{
		$iaView->grid('_IA_URL_plugins/' . $this->getPluginName() . '/js/admin/index');
	}

	protected function _gridQuery($columns, $where, $order, $start, $limit)
	{
		$sql =
			'SELECT SQL_CALC_FOUND_ROWS '.
			'g.`id`, g.`client`, g.`status`, 1 `update`, 1 `delete` '.
			'FROM `:prefix:table_clients_on_map` g '.
			'LIMIT :start, :limit';

		$sql = iaDb::printf($sql, array(
			'prefix' => $this->_iaDb->prefix,
			'table_clients_on_map' => $this->getTable(),
			'start' => $start,
			'limit' => $limit
		));

		return $this->_iaDb->getAll($sql);
	}

	protected function _preSaveEntry(array &$entry, array $data, $action)
	{
		parent::_preSaveEntry($entry, $data, $action);

		iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

		if (!utf8_is_valid($entry['client']))
		{
			$entry['client'] = utf8_bad_replace($entry['client']);
		}
		if (empty($entry['client']))
		{
			$this->addMessage('title_is_empty');
		}

		if ($this->getMessages())
		{
			return false;
		}

		return true;
	}

	protected function _setDefaultValues(array &$entry)
	{
		$entry['lang'] = $this->_iaCore->iaView->language;
		$entry['lat'] = 37.09024;
		$entry['lng'] = -95.71289100000001;
	}

}