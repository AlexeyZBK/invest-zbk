<?php 
/* Community Builder Profile Pro component for Joomla! 2.5, 3.x - Version 4.5.1
-------------------------------------------------------------------------------
Copyright (C) 2009-2014 Joomduck. All rights reserved.
Website: www.joomduck.com
E-mail: support@joomduck.com
Developer: Joomduck
Created: December 2014
License: GNU GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$task = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('task') : JRequest::getVar('task'); 
$code = new Code();

$app = JFactory::getApplication('administrator');

switch ($task) {
	case 'delete':
		($code->delete()) ? $app->enqueueMessage(JText::_('CODE_DELETE_SUCCESS_MESSAGE')) : $app->enqueueMessage(JText::_('CODE_DELETE_FAILURE_ERROR'), 'error');
		$app->redirect('index.php?option=com_cbprofilepro&view=code&layout=list&tmpl=component');
		return;
	break; 
	case "save":
	case "insert":
	case "new":
	case "close":
	
		if(version_compare(JVERSION,'3.0.0','ge')) {
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		} else {
			JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		}

		if($code->save()) {

			$app->enqueueMessage(JText::_('CODE_SAVE_SUCCESS_MESSAGE'));
			
			if ($task == 'new') {
			
				$app->redirect('index.php?option=com_cbprofilepro&view=code&layout=item&tmpl=component'); 
				return;
				
			} elseif ($task == 'insert') { 
			
				$js = 'insertCode("'.$code->title.'");';
				
			} elseif ($task == 'close') { 
			
				$js = 'closeWindow();';
				
			}/* elseif ($task == 'save' && !$code->id) {
			
				$code->getIdFromTitle();
				$app->redirect('index.php?option=com_cbprofilepro&view=code&layout=item&cid='.$code->id.'&tmpl=component'); 
				return;
			}*/ 
		} else {
			$app->enqueueMessage(JText::_('CODE_SAVE_FAILURE_ERROR'), 'error');
		}		
	break; 
}

ob_start(); ?>

	<script language="javascript" type="text/javascript"> 

		function validate_required (field, alerttext) 
		{
			with (field)  {
				if (value==null || value=="")	{
					alert(alerttext);
					return false;
				}	else	{
					return true;
				}
			}
		}

		function validate_form (thisform)
		{
			with (thisform)  {
				if (validate_required(title, "<?php echo JText::_('CODE_TITLE_REQUIRED_WARNING'); ?>") == false)  {
					title.focus();
					return false;
				}
			}
		}
		
		function insertCode (title) { 
		
			window.parent.insertOutput('{code '+title+'}');
			return closeWindow();
		}

		function closeWindow () { 
		
			window.parent.document.getElementById("code-list-iframe").contentDocument.location.reload();
			window.parent.SqueezeBox.close();
			return true;
		}
		
		<?php if(isset($js)) echo $js; ?>
		
	</script>

<?php $head_tags = ob_get_clean();
$document = JFactory::getDocument();
$document->addCustomTag($head_tags);
 
class Code {

	public $id;
	public $title;
	public $content;
	public $error = 0;

	public function __construct() {
	
		$this->id = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->getInt('cid') : JRequest::getInt('cid');
		
		if($this->id) $this->loadFromDatabase();
	}
	
	public function loadFromDatabase() {
	
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__cbppmagicwindow_code WHERE id = ".$db->Quote($this->id));
		$code = $db->loadObject();
		
		if ($code) {
			$this->title = htmlspecialchars($code->title);
			$this->content = htmlspecialchars($code->content);
		} else {
			$this->error = 1;
			$app = JFactory::getApplication('administrator');
			$app->enqueueMessage(JText::_('CODE_NOT_FOUND_ERROR'), 'error');
		}
	}
	
	public function save() {
	
		if($this->error) return false;
	
		$this->title = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('title', null, 'RAW') : JRequest::getString('title', null, 'POST', JREQUEST_ALLOWRAW);
		$this->content = (version_compare(JVERSION,'3.0.0','ge')) ? JFactory::getApplication()->input->get('content', null, 'RAW') : JRequest::getString('content', null, 'POST', JREQUEST_ALLOWRAW);
			
		$db = JFactory::getDBO();

		if($this->id) {
			$db->setQuery("UPDATE #__cbppmagicwindow_code SET title = ".$db->Quote($this->title).", content = ".$db->Quote($this->content)." WHERE id = ".$db->Quote($this->id));
		} else {
			$db->setQuery("INSERT INTO #__cbppmagicwindow_code (title, content) VALUES (".$db->Quote($this->title).", ".$db->Quote($this->content).")");
		}
		$result = (version_compare(JVERSION,'3.0.0','ge')) ? $db->execute() : $db->query();
		
		if($result && !$this->id) $this->id = $db->insertid();

		return $result;
	}
		
	public function delete() {

		if(!$this->id || $this->error) return false;
		
		$db = JFactory::getDBO();
		$db->setQuery("DELETE FROM #__cbppmagicwindow_code WHERE id = ".$db->Quote($this->id));
	
		return (version_compare(JVERSION,'3.0.0','ge')) ? $db->execute() : $db->query();
	}
}        
?>
<form style="margin: 0px" action="index.php?option=com_cbprofilepro&view=code&layout=item<?php if($code->id) echo '&cid='.$code->id; ?>&tmpl=component" method="post" name="entryform" id="entryform" onsubmit="return validate_form(entryform)" target="_self"> 
	<h2 style="font-size:18px"><?php echo ($code->id) ? JText::_('CODE_EDIT_HEADING') : JText::_('CODE_NEW_HEADING'); ?></h2>
	<div style="margin-top:10px;">
		<button type="submit" name="task" class="btn" value="save"><?php echo JText::_('CODE_SAVE_BUTTON'); ?></button>
		<button type="submit" name="task" class="btn" value="insert"><?php echo JText::_('CODE_SAVE_INSERT_BUTTON'); ?></button>
		<button type="submit" name="task" class="btn" value="close"><?php echo JText::_('CODE_SAVE_CLOSE_BUTTON'); ?></button>
		<button type="submit" name="task" class="btn" value="new"><?php echo JText::_('CODE_SAVE_NEW_BUTTON'); ?></button>
		<button type="button" onclick="closeWindow();" class="btn"><?php echo JText::_('CODE_CLOSE_BUTTON'); ?></button>
	</div>
	<div style="margin-top:20px;">
		<label for="code-title"><?php echo JText::_('CODE_TITLE_LABEL'); ?></label>
		<input type="text" name="title" id="code-title" size="70" class="input-xlarge" style="width:100%" value="<?php echo $code->title; ?>" />
	</div>
	<div style="margin-top:10px;">
		<label for="code-content"><?php echo JText::_('CODE_CONTENT_LABEL'); ?></label>
		<textarea name="content" id="code-content" cols="70" rows="12" class="input-xlarge" style="width:100%" placeholder="<?php echo JText::_('CODE_CONTENT_PLACEHOLDER'); ?>" /><?php echo $code->content; ?></textarea>
	</div>
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>