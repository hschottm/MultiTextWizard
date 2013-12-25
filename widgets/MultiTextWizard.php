<?php 

/**
 * @copyright  Helmut Schottmüller 2008-2013
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */

namespace Contao;

/**
 * Class MultiTextWizard
 *
 * Provide methods to handle multitext fields.
 * @copyright  Helmut Schottmüller 2008-2013
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @package    Controller
 */
class MultiTextWizard extends \Widget
{
	protected $arrMultiErrors = array();
	protected $arrColumns = array();
	
	/**
	 * Number of text fields
	 * @var int
	 */
	protected $intColumns = 1;

	/**
	 * Labels for the text fields (count must match $intColumns)
	 * @var array
	 */
	protected $arrLabels = array();

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';
	
	protected $strMultitextTemplate = 'be_multitext';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'labels':
				if (is_array($varValue))
				{
					$this->arrLabels = array_values($varValue);
					foreach ($varValue as $idx => $label)
					{
						if (is_array($this->arrColumns[$idx]))
						{
							$this->arrColumns[$idx]['label'] = $label;
						}
						else
						{
							$this->arrColumns[$idx] = array('label' => $label);
						}
					}
				}
				break;
				
			case 'columns':
				if (is_array($varValue))
				{
					$this->arrColumns = $varValue;
					foreach ($this->arrColumns as $idx => $column)
					{
						if (!array_key_exists('mandatory', $column))
						{
							$this->arrColumns[$idx]['mandatory'] = $this->mandatory;
						}
						if (!array_key_exists('rgxp', $column))
						{
							$this->arrColumns[$idx]['rgxp'] = $this->rgxp;
						}
						if (!array_key_exists('width', $column))
						{
							$this->arrColumns[$idx]['width'] = $this->width;
						}
						if (!array_key_exists('name', $column))
						{
							$this->arrColumns[$idx]['name'] = $idx;
						}
						if (!array_key_exists('unique', $column))
						{
							$this->arrConfiguration[$idx]['unique'] = $this->arrConfiguration[$idx]['unique'] ? true : false;
						}
					}
				}
				else
				{
					$this->intColumns = $varValue;
					if (count($this->arrColumns) != $varValue)
					{
						for ($i = 0; $i < $varValue; $i++)
						{
							array_push($this->arrColumns, array());
						}
					}
				}
				$this->arrColumns = array_values($this->arrColumns); 
				break;

			case 'value':
				$this->varValue = deserialize($varValue);
				break;
				
			case 'rgxp':
				$this->arrConfiguration['rgxp'] = $varValue;
				if (count($this->arrColumns))
				{
					foreach ($this->arrColumns as $idx => $column)
					{
						$this->arrColumns[$idx]['rgxp'] = $varValue;
					}
				}
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				if (count($this->arrColumns))
				{
					foreach ($this->arrColumns as $idx => $column)
					{
						$this->arrColumns[$idx]['mandatory'] = $varValue ? true : false;
					}
				}
				break;

			case 'width':
				$this->arrConfiguration['width'] = $varValue;
				if (count($this->arrColumns))
				{
					foreach ($this->arrColumns as $idx => $column)
					{
						$this->arrColumns[$idx]['width'] = $varValue;
					}
				}
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$tmpRgxp = $this->arrConfiguration['rgxp'];
		$tmpMandatory = $this->arrConfiguration['mandatory'];
		$tmpLabel = $this->strLabel;
		$arrInput = deserialize($this->getPost($this->strName));
		if (!is_array($arrInput)) $arrInput = array();
		$allvalues = array();
		foreach ($arrInput as $row => $rowdata)
		{
			foreach ($rowdata as $col => $value)
			{
				if (!is_array($allvalues[$col])) $allvalues[$col] = array();
				$this->arrConfiguration['rgxp'] = $this->arrColumns[$col]['rgxp'];
				$this->arrConfiguration['mandatory'] = $this->arrColumns[$col]['mandatory'];
				$this->arrConfiguration['style'] = $this->arrColumns[$col]['style'];
				$this->strLabel = (strlen($this->arrColumns[$col]['label'])) ? $this->arrColumns[$col]['label'] : $tmpLabel;
				$retvalue = $this->validator($value);
				array_push($allvalues[$col], $value);
				$arrInput[$row][$col] = $retvalue;
				if ($this->hasErrors())
				{
					$this->arrMultiErrors[$row][$col] = array_pop($this->arrErrors);
				}
			}
		}
		foreach ($arrInput as $row => $rowdata)
		{
			foreach ($rowdata as $col => $value)
			{
				if ($this->arrColumns[$col]['unique'] && count(array_unique($allvalues[$col])) != count($allvalues[$col]))
				{
					$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['notunique'], $this->strLabel));
				}
			}
		}

		if (count($this->arrMultiErrors))
		{
			$this->class = 'error';
			$this->arrErrors[] = '';
		}

		$this->arrConfiguration['rgxp'] = $tmpRgxp;
		$this->arrConfiguration['mandatory'] = $tmpMandatory;
		$this->strLabel = $tmpLabel;
		$this->varValue = $arrInput;
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		if (is_array($GLOBALS['TL_JAVASCRIPT']))
		{
			array_insert($GLOBALS['TL_JAVASCRIPT'], 1, 'system/modules/MultiTextWizard/assets/multitext.js');
		}
		else
		{
			$GLOBALS['TL_JAVASCRIPT'] = array('system/modules/MultiTextWizard/assets/multitext.js');
		}

		$arrButtons = array('rnew','rcopy', 'rup', 'rdown', 'rdelete');

		$strCommand = 'cmd_' . $this->strField;
		$emptyarray = array();
		for ($i = 0; $i < count($this->arrColumns); $i++) array_push($emptyarray, '');
		// Change the order
		if (\Input::get($strCommand) && is_numeric(\Input::get('cid')) && \Input::get('id') == $this->currentRecord)
		{
			$this->import('Database');

			switch (\Input::get($strCommand))
			{
				case 'rnew':
					array_insert($this->varValue, \Input::get('cid') + 1, array($emptyarray));
					break;

				case 'rcopy':
					$this->varValue = array_duplicate($this->varValue, \Input::get('cid'));
					break;

				case 'rup':
					$this->varValue = array_move_up($this->varValue, \Input::get('cid'));
					break;

				case 'rdown':
					$this->varValue = array_move_down($this->varValue, \Input::get('cid'));
					break;

				case 'rdelete':
					$this->varValue = array_delete($this->varValue, \Input::get('cid'));
					break;
			}

			$this->Database->prepare("UPDATE " . $this->strTable . " SET " . $this->strField . "=? WHERE id=?")
						   ->execute(serialize($this->varValue), $this->currentRecord);

			$this->redirect(preg_replace('/&(amp;)?cid=[^&]*/i', '', preg_replace('/&(amp;)?' . preg_quote($strCommand, '/') . '=[^&]*/i', '', \Environment::get('request'))));
		}

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array($emptyarray);
		}
		
		$objTemplate = new BackendTemplate($this->strMultitextTemplate);
		$objTemplate->strId = $this->strId;
		$objTemplate->attributes = $this->getAttributes();
		$objTemplate->arrColumns = $this->arrColumns;
		$objTemplate->varValue = $this->varValue;
		$objTemplate->arrMultiErrors = $this->arrMultiErrors;
		$buttons = array();
		$hasTitles = array_key_exists('buttonTitles', $this->arrConfiguration) && is_array($this->arrConfiguration['buttonTitles']);
		foreach ($arrButtons as $button)
		{
			$buttontitle = ($hasTitles && array_key_exists($button, $this->arrConfiguration['buttonTitles'])) ? $this->arrConfiguration['buttonTitles'][$button] : $GLOBALS['TL_LANG'][$this->strTable][$button][0];
			array_push($buttons,
				array(
					'href' => $this->addToUrl('&amp;'.$strCommand.'='.$button.'&amp;cid=%s&amp;id='.$this->currentRecord),
					'title' => specialchars($buttontitle),
					'onclick' => 'MultiText.multitextWizard(this, \''.$button.'\', \'ctrl_'.$this->strId.'\'); return false;',
					'img' => $this->generateImage(substr($button, 1).'.gif', $GLOBALS['TL_LANG'][$this->strTable][$button][0], 'class="tl_multitextwizard_img"')
				)
			);
		}
		$objTemplate->arrButtons = $buttons;
		return $objTemplate->parse();
	}
}

