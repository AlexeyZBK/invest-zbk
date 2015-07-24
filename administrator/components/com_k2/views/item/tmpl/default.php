<?php
/**
 * @version		2.6.x
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2014 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addScriptDeclaration("
	Joomla.submitbutton = function(pressbutton){
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (\$K2.trim(\$K2('#title').val()) == '') {
			alert( '".JText::_('K2_ITEM_MUST_HAVE_A_TITLE', true)."' );
		}
		else if (\$K2.trim(\$K2('#catid').val()) == '0') {
			alert( '".JText::_('K2_PLEASE_SELECT_A_CATEGORY', true)."' );
		}
		else {
			syncExtraFieldsEditor();
			var validation = validateExtraFields();
			if(validation === true) {
				\$K2('#selectedTags option').attr('selected', 'selected');
				submitform( pressbutton );
			}
		}
	}
");

?>
<style>
.button{border:1px solid #E0E0E0;padding:3px 6px;margin:0;background-color:#F0F0F0;font-size:14px;text-decoration:none;font-family:Georgia, "Times New Roman",Times,serif;color:#666;font-weight:normal;outline:0}#modalka,#modalkaZ{display:block;position: fixed;top:30px;left:-240%;height:100%;width:100%;background-color:rgba(0,0,0,0.8);z-index:20;-ms-transition:left 1.3s;-webkit-transition:left 1.3s;transition:left 1.3s;overflow:scroll}#closer,#closerZ{position:absolute;top:100px;left:90%;padding:50px;color:#F9F9F9;font-size:400%;-ms-transition:transform .5s;-webkit-transition:transform .5s;transition:transform .5s;}#closer:hover,#closerZ:hover{-ms-transform:rotate(30deg);-webkit-transform:rotate(30deg);transform:rotate(30deg);}input.inputtxt{display:block;height:26px !important;width:600px !important;font-size:20px !important;margin:10px auto !important;padding:0!important}.areatxt{display:block;width:600px;font-size:20px;margin:10px auto;height:460px}input.inputDate{display:block !important;margin:10px auto !important;height:38px !important;width:320px !important;font-size:26px !important;}.cornfieldH{font-size:24px;font-weight:700;border-bottom:1px solid #444444;padding-bottom:8px}.sense,.cornfield{font-size:18px;line-height:24px;font-family:sans-serif}.cornfield{font-weight:700;color:#333333}.sense{color:#222222}#buttonSender,#buttonSenderZ{display:block;background-color:transparent;color:#FFFFFF;font-family:monospace;font-size:20px;border-left:none;  border-right: none;border-top:1px solid rgba(255,255,255,1);border-bottom:1px solid rgba(255,255,255,1);padding:8px 12px;margin:10px auto;-ms-transition:all .5s;-webkit-transition:all .5s;transition:all .5s;}#buttonSender:hover,#buttonSenderZ:hover{padding:14px 16px;border-bottom-color:rgba(255,255,255,0);border-top-color:rgba(255,255,255,0);margin-top:4px}.wrappp{position:relative;top:70px;width:70%;margin-left:auto;margin-right:auto;margin-bottom:200px}
</style>
<script>
function closer () {var modalka=document.getElementById('modalka');modalka.style.left="-240%";}
function openForm (){var modalka = document.getElementById('modalka');modalka.style.left = "0%";}
function closerZ () {var modalkaZ=document.getElementById('modalkaZ');modalkaZ.style.left="-240%";}
function openFormZ (){var modalkaZ = document.getElementById('modalkaZ');modalkaZ.style.left = "0%";}
</script>
<script>
function copy (){
var inputType = document.getElementById('type').value;
var inputOtrasle = document.getElementById('otrasle').value;
var inputCategory = document.getElementById('category').value;
var inputStatus = document.getElementById('status8').value;
var inputLocation = document.getElementById('location').value;
var inputAnnotation = document.getElementById('annotation').value;
var inputPrice = document.getElementById('price').value;
var inputNeedprice = document.getElementById('needprice').value;
var inputDeadline = document.getElementById('deadline').value;
var inputRealization = document.getElementById('realization').value;
var inputDiagrammFrom = document.getElementById('diagrammFrom').value;
var inputDiagrammTo = document.getElementById('diagrammTo').value;
var inputNpv = document.getElementById('npv').value;
var inputIrr = document.getElementById('irr').value;
var inputPp = document.getElementById('pp').value;
var inputRinok = document.getElementById('rinok').value;
var inputSite = document.getElementById('site').value;
var inputOkved = document.getElementById('okved').value;
var inputFormatF = document.getElementById('formatF').value;
var inputDocs = document.getElementById('docs').value;
var inputGosprogs = document.getElementById('gosprogs').value;
var inputIniciator = document.getElementById('iniciator').value;
var inputOgrn = document.getElementById('ogrn').value;
var inputInn = document.getElementById('inn').value;
var inputTelephon = document.getElementById('telephon').value;
var inputMail = document.getElementById('mail').value;
var inputSite2 = document.getElementById('site2').value;
var inputAddr = document.getElementById('addr').value;
var inputLicocontact = document.getElementById('licocontact').value;
var inputCurator = document.getElementById('curator').value;
var inputCuratororgan = document.getElementById('curatororgan').value;
var inputFiocuratororgan = document.getElementById('fiocuratororgan').value;
var inputDolzhncuratororgan = document.getElementById('dolzhncuratororgan').value;
var inputTelephoncuratora = document.getElementById('telephoncuratora').value;
var inputMailcuratora = document.getElementById('mailcuratora').value;
var frameEditor = document.getElementById('frameEditor');
frameEditor.contentWindow.document.body.innerHTML = '<p><strong>Тип: </strong><span class="sense">'+inputType+'</span></p><p><strong>Отрасль: </strong><span class="sense">'+inputOtrasle+'</span></p><p><strong>Категория: </strong><span class="sense">'+inputCategory+'</span></p><p><strong>Статус: </strong><span class="sense">'+inputStatus+'</span></p><p><strong>Место реализации: </strong><span class="sense">'+inputLocation+'</span></p><p><strong>Полная стоимость проекта: </strong><span class="sense">'+inputPrice+'</span></p><p><strong>Потребность в инвестициях: </strong><span class="sense">'+inputNeedprice+'</span></p><hr id="system-readmore" /><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Аннотация: </strong><span class="sense">'+inputAnnotation+'</span></p><p><strong>Срок реализации: </strong><span class="sense">'+inputDeadline+'</span></p><p><strong>Этапы реализации проекта: </strong><span class="sense">'+inputRealization+'</span></p><p><strong>Диаграмма реализации этапов: </strong><span class="sense">с '+inputDiagrammFrom+' по '+inputDiagrammTo+'</span></p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Показатели эффективности проекта</strong></p><p><strong>Чистый дисконтированный доход (NPV): </strong><span class="sense">'+inputNpv+' рублей</span></p><p><strong>Внутренняя норма доходности (IRR): </strong><span class="sense">'+inputIrr+' %</span></p><p><strong>Срок окупаемости инвестиций (PP): </strong><span class="sense">'+inputPp+' лет</span></p><p><strong>Рынок: </strong><br><span class="sense"></span>'+inputRinok+'</p><p><strong>Промо сайт: </strong><span class="sense"></span>'+inputSite+'</p><p><strong>ОКВЭД: </strong><span class="sense"></span>'+inputOkved+'</p><p><strong>Формат финансирования: </strong><span class="sense"></span>'+inputFormatF+'</p><p><strong>Наличие документации: </strong><span class="sense"></span>'+inputDocs+'</p><p><strong>Участие в государственных программах: </strong><span class="sense"></span>'+inputGosprogs+'</p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Инициатор проекта</strong></p><p><strong>Наименование (ФИО): </strong><span class="sense"></span>'+inputIniciator+'</p><p><strong>ОГРН / ИНН: </strong><span class="sense"></span>'+inputOgrn+' / '+inputInn+'</p><p><strong>Телефон/факс: </strong><span class="sense"></span>'+inputTelephon+'</p><p><strong>Email: </strong><span class="sense"></span>'+inputMail+'</p><p><strong>Сайт: </strong><span class="sense"></span>'+inputSite2+'</p><p><strong>Адрес: </strong><span class="sense"></span>'+inputAddr+'</p><p><strong>Контактное лицо: </strong><span class="sense"></span>'+inputLicocontact+'</p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Куратор проекта</strong></p><p><strong>Наличие: </strong><span class="sense"></span>'+inputCurator+'</p><p><strong>Курирующий орган государственной исполнительной власти: </strong><span class="sense"></span>'+inputCuratororgan+'</p><p><strong>ФИО куратора: </strong><span class="sense"></span>'+inputFiocuratororgan+'</p><p><strong>Должность куратора: </strong><span class="sense"></span>'+inputDolzhncuratororgan+'</p><p><strong>Телефон: </strong><span class="sense"></span>'+inputTelephoncuratora+'</p><p class="cornfield"><span>Email: </strong><span class="sense"></span>'+inputMailcuratora+'</p>';}
</script>
<script>
function copyZ () {
var inputTypeZ = document.getElementById('typeZ').value;
var inputCategoryZ = document.getElementById('categoryZ').value;
var inputPloshadZ = document.getElementById('ploshadZ').value;
var inputPlaceZ = document.getElementById('placeZ').value;
var inputInjZ = document.getElementById('injZ').value;
var inputUsloviyaZ = document.getElementById('usloviyaZ').value;
var inputPriceZ = document.getElementById('priceZ').value;
var inputAnnotationZ = document.getElementById('annotationZ').value;
var inputUdalennostZ = document.getElementById('udalennostZ').value;
var inputKilom = document.getElementById('kilom').value;
var inputPutiZ = document.getElementById('putiZ').value;
var inputInjenerZ = document.getElementById('injenerZ').value;
var inputOwnerZ = document.getElementById('ownerZ').value;
var inputOwnerZZ = document.getElementById('ownerZz').value;
var inputFioZ = document.getElementById('fioZ').value;
var inputPhonZ = document.getElementById('phonZ').value;
var inputEmailZ = document.getElementById('emailZ').value;
var frameEditorZ = document.getElementById('frameEditor');
frameEditorZ.contentWindow.document.body.innerHTML = '<p><strong>Тип: </strong><span class="sense">'+inputTypeZ+'</span></p><p><strong>Категория земельного участка: </strong><span class="sense">'+inputCategoryZ+'</span></p><p><strong>Площадь земельного участка: </strong><span class="sense">'+inputPloshadZ+' м²</span></p><p><strong>Место расположения: </strong><span class="sense">'+inputPlaceZ+'</span></p><p><strong>Наличие инженерных сетей: </strong><span class="sense">'+inputInjZ+'</span></p><p><strong>Условия использования площадки: </strong><span class="sense">'+inputUsloviyaZ+'</span></p><p><strong>Стоимость: </strong><span class="sense">'+inputPriceZ+' руб./м2</span></p><hr id="system-readmore" /><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Описание площадки</strong><span class="sense">'+inputAnnotationZ+'</span></p><p><strong>Удаленность площадки</strong><br><span class="sense">'+inputUdalennostZ+' '+inputKilom+'</span></p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Подъездные пути: </strong><span class="sense">'+inputPutiZ+'</span></p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Описание инженерных путей: </strong><span class="sense">'+inputInjenerZ+'</span></p><p><strong>Информация о владельце: </strong><span class="sense">'+inputOwnerZ+'</span></p><p><strong>Информация о собственнике: </strong><span class="sense">'+inputOwnerZZ+'</span></p><p style="margin-bottom:12px;"><strong style="display:block;font-size:22px;font-weight:700;border-bottom:1px solid #9B3881;padding-bottom:8px;margin:16px 0;color:#000000;">Контактное лицо</strong><span class="sense">ФИО: '+inputFioZ +'<br>Телефон: '+inputPhonZ+'<br>Email: '+inputEmailZ+'</span></p>';}
</script>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
	<?php if($this->mainframe->isSite()): ?>
	<div id="k2FrontendContainer">
		<div id="k2Frontend">
			<table class="k2FrontendToolbar" cellpadding="2" cellspacing="4">
				<tr>
					<td id="toolbar-save" class="button">
						<a class="toolbar" href="#" onclick="Joomla.submitbutton('save'); return false;"> <span title="<?php echo JText::_('K2_SAVE'); ?>" class="icon-32-save icon-save"></span> <?php echo JText::_('K2_SAVE'); ?> </a>
					</td>
					<td id="toolbar-cancel" class="button">
						<a class="toolbar" href="#"> <span title="<?php echo JText::_('K2_CANCEL'); ?>" class="icon-32-cancel icon-cancel"></span> <?php echo JText::_('K2_CLOSE'); ?> </a>
					</td>
				</tr>
			</table>
			<div id="k2FrontendEditToolbar">
				<h2 class="header icon-48-k2">
					<?php echo (JRequest::getInt('cid')) ? JText::_('K2_EDIT_ITEM') : JText::_('K2_ADD_ITEM'); ?>
				</h2>
			</div>
			<div class="clr"></div>
			<hr class="sep" />
			<?php if(!$this->permissions->get('publish')): ?>
			<div id="k2FrontendPermissionsNotice">
				<p><?php echo JText::_('K2_FRONTEND_PERMISSIONS_NOTICE'); ?></p>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			<div id="k2ToggleSidebarContainer"> <a href="#" id="k2ToggleSidebar"><?php echo JText::_('K2_TOGGLE_SIDEBAR'); ?></a> </div>
			<table cellspacing="0" cellpadding="0" border="0" class="adminFormK2Container table">
				<tbody>
					<tr>
						<td>
							<table class="adminFormK2 table">
								<tr>
									<td class="adminK2LeftCol">
										<label for="title"><?php echo JText::_('K2_TITLE'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<input class="text_area k2TitleBox" type="text" name="title" id="title" maxlength="250" value="<?php echo $this->row->title; ?>" />
									</td>
								</tr>
								<tr>
									<td class="adminK2LeftCol">
										<label for="alias"><?php echo JText::_('K2_TITLE_ALIAS'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<input class="text_area k2TitleAliasBox" type="text" name="alias" id="alias" maxlength="250" value="<?php echo $this->row->alias; ?>" />
									</td>
								</tr>
								<tr>
									<td class="adminK2LeftCol">
										<label><?php echo JText::_('K2_CATEGORY'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<?php echo $this->lists['categories']; ?>
									</td>
								</tr>
								<tr>
									<td class="adminK2LeftCol">
										<label><?php echo JText::_('K2_TAGS'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<?php if($this->params->get('taggingSystem')): ?>
										<!-- Free tagging -->
										<ul class="tags">
											<?php if(isset($this->row->tags) && count($this->row->tags)): ?>
											<?php foreach($this->row->tags as $tag): ?>
											<li class="tagAdded">
												<?php echo $tag->name; ?>
												<span title="<?php echo JText::_('K2_CLICK_TO_REMOVE_TAG'); ?>" class="tagRemove">x</span>
												<input type="hidden" name="tags[]" value="<?php echo $tag->name; ?>" />
											</li>
											<?php endforeach; ?>
											<?php endif; ?>
											<li class="tagAdd">
												<input type="text" id="search-field" />
											</li>
											<li class="clr"></li>
										</ul>
										<span class="k2Note"> <?php echo JText::_('K2_WRITE_A_TAG_AND_PRESS_RETURN_OR_COMMA_TO_ADD_IT'); ?> </span>
										<?php else: ?>
										<!-- Selection based tagging -->
										<?php if( !$this->params->get('lockTags') || $this->user->gid>23): ?>
										<div style="float:left;">
											<input type="text" name="tag" id="tag" />
											<input type="button" id="newTagButton" value="<?php echo JText::_('K2_ADD'); ?>" />
										</div>
										<div id="tagsLog"></div>
										<div class="clr"></div>
										<span class="k2Note"> <?php echo JText::_('K2_WRITE_A_TAG_AND_PRESS_ADD_TO_INSERT_IT_TO_THE_AVAILABLE_TAGS_LISTNEW_TAGS_ARE_APPENDED_AT_THE_BOTTOM_OF_THE_AVAILABLE_TAGS_LIST_LEFT'); ?> </span>
										<?php endif; ?>
										<table cellspacing="0" cellpadding="0" border="0" id="tagLists">
											<tr>
												<td id="tagListsLeft">
													<span><?php echo JText::_('K2_AVAILABLE_TAGS'); ?></span> <?php echo $this->lists['tags'];	?>
												</td>
												<td id="tagListsButtons">
													<input type="button" id="addTagButton" value="<?php echo JText::_('K2_ADD'); ?> &raquo;" />
													<br />
													<br />
													<input type="button" id="removeTagButton" value="&laquo; <?php echo JText::_('K2_REMOVE'); ?>" />
												</td>
												<td id="tagListsRight">
													<span><?php echo JText::_('K2_SELECTED_TAGS'); ?></span> <?php echo $this->lists['selectedTags']; ?>
												</td>
											</tr>
										</table>
										<?php endif; ?>
									</td>
								</tr>
								<?php if($this->mainframe->isAdmin() || ($this->mainframe->isSite() && $this->permissions->get('publish'))): ?>
								<tr>
									<td class="adminK2LeftCol">
										<label for="featured"><?php echo JText::_('K2_IS_IT_FEATURED'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<?php echo $this->lists['featured']; ?>
									</td>
								</tr>
								<tr>
									<td class="adminK2LeftCol">
										<label><?php echo JText::_('K2_PUBLISHED'); ?></label>
									</td>
									<td class="adminK2RightCol">
										<?php echo $this->lists['published']; ?>
									</td>
								</tr>
								<?php endif; ?>
							</table>

							<!-- Required extra field warning -->
							<div id="k2ExtraFieldsValidationResults">
								<h3><?php echo JText::_('K2_THE_FOLLOWING_FIELDS_ARE_REQUIRED'); ?></h3>
								<ul id="k2ExtraFieldsMissing">
									<li><?php echo JText::_('K2_LOADING'); ?></li>
								</ul>
							</div>

							<!-- Tabs start here -->
							<div class="simpleTabs" id="k2Tabs">
								<ul class="simpleTabsNavigation">
									<li id="tabContent"><a href="#k2Tab1"><?php echo JText::_('K2_CONTENT'); ?></a></li>
									<?php if ($this->params->get('showImageTab')): ?>
									<li id="tabImage"><a href="#k2Tab2"><?php echo JText::_('K2_IMAGE'); ?></a></li>
									<?php endif; ?>
									<?php if ($this->params->get('showImageGalleryTab')): ?>
									<li id="tabImageGallery"><a href="#k2Tab3"><?php echo JText::_('K2_IMAGE_GALLERY'); ?></a></li>
									<?php endif; ?>
									<?php if ($this->params->get('showVideoTab')): ?>
									<li id="tabVideo"><a href="#k2Tab4"><?php echo JText::_('K2_MEDIA'); ?></a></li>
									<?php endif; ?>
									<?php if ($this->params->get('showExtraFieldsTab')): ?>
									<li id="tabExtraFields"><a href="#k2Tab5"><?php echo JText::_('K2_EXTRA_FIELDS'); ?></a></li>
									<?php endif; ?>
									<?php if ($this->params->get('showAttachmentsTab')): ?>
									<li id="tabAttachments"><a href="#k2Tab6"><?php echo JText::_('K2_ATTACHMENTS'); ?></a></li>
									<?php endif; ?>

<div id="modalka">
<a id="closer" onclick="closer()" href="#"><i class="fa fa-times"></i></a>
<div class="wrappp">
<input class="inputtxt" id="type" list="textType" placeholder="Выберите тип проекта">
<datalist id="textType">
<option value="Бизнес - идея">1</option>
<option value="Инвестиционный проект">2</option>
</datalist>
<br><br>
<input class="inputtxt" id="otrasle" list="textOtrasle" placeholder="Выберите отрасль к которой он относится">
<datalist id="textOtrasle">
<option value="Сельское хозяйство, охота и лесное хозяйство">A</option>
<option value="Рыболовство, рыбоводство">B</option>
<option value="Добыча полезных ископаемых">C</option>
<option value="Добыча топливно-энергетических полезных ископаемых">CA</option>
<option value="Добыча полезных ископаемых, кроме топливно-энергетических">CB</option>
<option value="Обрабатывающие производства">D</option>
<option value="Производство пищевых продуктов, включая напитки, и табака">DA</option>
<option value="Текстильное и швейное производство">DB</option>
<option value="Производство кожи, изделий из кожи и производство обуви">DC</option>
<option value="Обработка древисины и производство изделий из дерева">DD</option>
<option value="Целлюлозно - бумажное производство; издательская и полиграфическая деятельность">DE</option>
<option value="Производство кокса, нефтепродуктов и ядерных материалов">DF</option>
<option value="Химическое производство">DG</option>
<option value="Производство резиновых и пластмассовых изделий">DH</option>
<option value="Производство прочих неметаллических минеральных продуктов">DI</option>
<option value="Металлургическое производство и производство готовых металлических изделий">DJ</option>
<option value="Производство машин и оборудования">DK</option>
<option value="Производство электрооборудования, электронного и оптического оборудования">DL</option>
<option value="Производство транспортных средств и оборудования">DM</option>
<option value="Прочие производства">DN</option>
<option value="Производство и распределение электроэнергии, газа и воды">E</option>
<option value="Строительство">F</option>
<option value="Оптовая и розничная торговля; ремонт автотранспортных средств, мотоциклов, бытовых изделий и предметов личного пользования">G</option>
<option value="Гостиницы и рестораны">H</option>
<option value="Транспорт и связь">I</option>
<option value="Финансовая деятельность">J</option>
<option value="Операции с недвижимым имуществом, аренда и предоставление услуг">K</option>
<option value="Государственное управление и обеспечение военной безопасности; обязательное социальное обеспечение">L</option>
<option value="Образование">M</option>
<option value="Здравоохранение и предоставление социальных услуг">N</option>
<option value="Предоставление прочих коммунальных, социальных и персональных услуг">O</option>
<option value="Предоставление услуг по ведению домашнего хозяйства">P</option>
<option value="Деятельность экстерриториальных организаций">Q</option>
</datalist>
<br><br>
<input class="inputtxt" id="category" list="textCategory" placeholder="Выберите категорию из списка">
<datalist id="textCategory">
<option value="Поиск инвестора">1</option>
<option value="Поиск партнера">2</option>
<option value="Успешный пример">3</option>
</datalist>
<br><br>
<input class="inputtxt" id="status8" list="textStatus" placeholder="Выберите статус реализации">
<datalist id="textStatus">
<option value="Реализуется">1</option>
<option value="Реализован">2</option>
</datalist>
<br><br>
<input class="inputtxt" id="location" list="textLocation" placeholder="Выберите местоположение">
<datalist id="textLocation">
<option value="Агинский район">1</option>
<option value="Акшинский район">2</option>
<option value="Александрово-Заводский район">3</option>
<option value="Балейский район">4</option>
<option value="Борзинский район">5</option>
<option value="Газимуро-Заводский район">6</option>
<option value="Забайкальский район">7</option>
<option value="Каларский район">8</option>
<option value="Калганский район">9</option>
<option value="Карымский район">10</option>
<option value="Краснокаменский район">11</option>
<option value="Красночикойский район">12</option>
<option value="Кыринский район">13</option>
<option value="Могойтуйский район">14</option>
<option value="Могочинский район">15</option>
<option value="Нерчинский район">16</option>
<option value="Нерчинско-Заводский район">17</option>
<option value="Оловяннинский район">18</option>
<option value="Ононский район">19</option>
<option value="Петровск-Забайкальский район">20</option>
<option value="Приаргунский район">21</option>
<option value="Сретенский район">22</option>
<option value="Тунгиро-Олекминский район">23</option>
<option value="Тунгокоченский район">24</option>
<option value="Улетовский район">25</option>
<option value="Хилокский район">26</option>
<option value="Чернышевский район">27</option>
<option value="Читинский район">28</option>
<option value="Шелопугинский район">29</option>
<option value="Шилкинский район">30</option>
</datalist>
<br><br>
<input type="number" min="0" class="inputtxt" id="price" placeholder="Полная стоимость проекта">
<br><br>
<input type="number" min="0" class="inputtxt" id="needprice" placeholder="Потребность в инвестициях">
<br><br>
<textarea class="areatxt" id="annotation" placeholder="Резюме проекта (краткое текстовое описание сущности и основных преимуществ)"></textarea>
<br><br>
<input type="number" min="0" class="inputtxt" id="deadline" placeholder="Срок реализации">
<br><br>
<textarea class="areatxt" id="realization" placeholder="Этапы реализации проекта (аннотация этапов, даты их реализации )"></textarea>
<br><br>
<input type="date" class="inputDate" id="diagrammFrom" placeholder="Сроки реализации  с">
<input type="date" class="inputDate" id="diagrammTo" placeholder="по">
<br><br>
<input type="number" min="0" class="inputtxt" id="npv" placeholder="Чистый дисконтированный доход (NPV)">
<br><br>
<input type="number" min="0" class="inputtxt" id="irr" placeholder="Внутренняя норма доходности (IRR)">
<br><br>
<input type="number" min="0" class="inputtxt" id="pp" placeholder="Срок окупаемости инвестиций (PP)">
<br><br>
<textarea class="areatxt" id="rinok" placeholder="Рынок (виды продукции,планируемые объемы и т.д.)"></textarea>
<br><br>
<input type="url" class="inputtxt" id="site" placeholder="Адрес сайта">
<br><br>
<input  class="inputtxt" id="okved" placeholder="Код по ОКВЭД">
<br><br>
<input class="inputtxt" id="formatF" list="textFormatF" placeholder="Формат финансирования">
<datalist id="textFormatF">
<option value="Участие в уставном капитале">1</option>
<option value="Реализация опционов">2</option>
<option value="Продажа акций">3</option>
<option value="Займы">4</option>
<option value="Кредиты">5</option>
<option value="Другое">6</option>
</datalist>
<br><br>
<input class="inputtxt" id="docs" list="textDocs" placeholder="Наличие документации">
<datalist id="textDocks">
<option value="Бизнес - план">1</option>
<option value="ТЭО">2</option>
<option value="Научное обоснование">3</option>
<option value="Лицензия">4</option>
<option value="Иное">5</option>
<option value="Нет">6</option>
</datalist>
<br><br>
<input class="inputtxt" id="gosprogs" list="textGosprogs" placeholder="Участие в государственных программах">
<datalist id="textGosprogs">
<option value="Российской Федерации или Забайкальского края">1</option>
<option value="Федеральной адресной инвестиционной программе (ФАИП)">2</option>
<option value="Краевой адресной инвестиционной программе (КАИП)">3</option>
</datalist>
<br><br>
<input type="text" class="inputtxt" id="iniciator" placeholder="Инициатор проекта (ФИО)">
<br><br>
<input type="number" min="0" pattern="[0-9]{13}" class="inputtxt" id="ogrn" size="13" placeholder="ОГРН">
<br><br>
<input type="number" min="0" pattern="[0-9]{10}" class="inputtxt" id="inn" size="10" placeholder="ИНН">
<br><br>
<input type="number" min="0" pattern="[0-9]{11}" class="inputtxt" id="telephon" size="11" placeholder="Телефон / факс">
<br><br>
<input type="email" class="inputtxt" id="mail" placeholder="Введите email">
<br><br>
<input  class="inputtxt" id="site2" placeholder="Наличие сайта (адрес при наличии)">
<br><br>
<input  class="inputtxt" id="addr" placeholder="Адрес">
<br><br>
<input  class="inputtxt" id="licocontact" placeholder="Контактное лицо">
<br><br>
<input class="inputtxt" id="curator" list="textCurator" placeholder="Наличие куратора проекта">
<datalist id="textCurator">
<option value="Да">1</option>
<option value="Нет">2</option>
</datalist>
<br><br>
<input  class="inputtxt" id="curatororgan" placeholder="Курирующий орган государственной исполнительной власти">
<br><br>
<input  class="inputtxt" id="fiocuratororgan" placeholder="ФИО куратолра">
<br><br>
<input  class="inputtxt" id="dolzhncuratororgan" placeholder="Должность куратолра">
<br><br>
<input type="number" min="0" pattern="[0-9]{11}" class="inputtxt" id="telephoncuratora" size="11" placeholder="Телефон куратора">
<br><br>
<input type="email" class="inputtxt" id="mailcuratora" placeholder="Email куратора">
<br><br>
<button id="buttonSender" type="button" onclick="copy();">Заполнить поля</button>
</div>
</div>

<div id="modalkaZ">
<a id="closerZ" onclick="closerZ()" href="#"><i class="fa fa-times"></i></a>
<div class="wrappp">
<input class="inputtxt" id="typeZ" list="TypeZz" placeholder="Выберите тип площадки или объекта">
<datalist id="TypeZz">
<option value="Промышленно-производственные">площадки</option>
<option value="Агропромышленные">площадки</option>
<option value="Туристическо-рекреационные">площадки</option>
<option value="Земельный участок">объект</option>
<option value="Строение">объект</option>
<option value="Помещение">объект</option>
</datalist>
<br><br>
<input class="inputtxt" id="categoryZ" list="CategoryZz" placeholder="Категория земельного участка">
<datalist id="CategoryZz">
<option value="Земли промышленности">1</option>
<option value="Земли населенных пунктов">2</option>
<option value="земли сельскохозяйственного назначения">3</option>
<option value="Земли запаса">4</option>
</datalist>
<br><br>
<input type="number" class="inputtxt" id="ploshadZ" placeholder="Площадь земельного участка м²">
<br><br>
<input class="inputtxt" id="placeZ" placeholder="Район, населенный пункт">
<br><br>
<input class="inputtxt" id="injZ" list="injZz" placeholder="Наличие инженерных сетей">
<datalist id="injZz">
<option value="Есть в наличие">1</option>
<option value="Отсутствуют">2</option>
</datalist>
<br><br>
<input class="inputtxt" id="usloviyaZ" list="usloviyaZz" placeholder="Условия использования площадки">
<datalist id="usloviyaZz">
<option value="Аренда">1</option>
<option value="Продажа">2</option>
</datalist>
<br><br>
<input type="number" class="inputtxt" id="priceZ" placeholder="Стоимость">
<br><br>
<textarea class="areatxt" id="annotationZ" placeholder="Описание площадки"></textarea>
<br><br>
<input class="inputtxt" id="udalennostZ" list="udalennostZz" placeholder="Удаленность площадки, отсчет от">
<datalist id="udalennostZz">
<option value="От центра населенного пункта">1</option>
<option value="От автомагистрали">2</option>
<option value="От железнодорожной станци">3</option>
<option value="От речного порта">4</option>
<option value="От аэропорта">5</option>
<option value="От г. Читы ">6</option>
</datalist>
<input type="number" class="inputtxt" id="kilom" placeholder="километров">
<br><br>
<textarea class="areatxt" id="putiZ" placeholder="Описание подъездных путей"></textarea>
<br><br>
<textarea class="areatxt" id="injenerZ" placeholder="Описание инженерных сетей"></textarea>
<br><br>
<input class="inputtxt" id="ownerZ" placeholder="Информация о владельце">
<br><br>
<input class="inputtxt" id="ownerZz" placeholder="Информация о собственнике">
<br><br>
<input class="inputtxt" id="fioZ" placeholder="Контактное лицо - ФИО">
<br><br>
<input class="inputtxt" id="phonZ" placeholder="Контактное лицо - телефон">
<br><br>
<input class="inputtxt" id="emailZ" placeholder="Еmail контактного лица">
<br><br>
<button id="buttonSenderZ" type="button" onclick="copyZ();">Заполнить поля</button>
</div>
</div>
<button type="button" onclick="openForm()" class="button">Проекты</button>
<button type="button" onclick="openFormZ()" class="button">Площадки и объекты</button>

									<?php if(count(array_filter($this->K2PluginsItemOther)) && $this->params->get('showK2Plugins')): ?>
									<li id="tabPlugins"><a href="#k2Tab7"><?php echo JText::_('K2_PLUGINS'); ?></a></li>
									<?php endif; ?>
								</ul>

								<!-- Tab content -->
								<div class="simpleTabsContent" id="k2Tab1">
									<?php if($this->params->get('mergeEditors')): ?>
									<div class="k2ItemFormEditor"> <?php echo $this->text; ?>
										<div class="dummyHeight"></div>
										<div class="clr"></div>
									</div>
									<?php else: ?>
									<div class="k2ItemFormEditor"> <span class="k2ItemFormEditorTitle"> <?php echo JText::_('K2_INTROTEXT_TEASER_CONTENTEXCERPT'); ?> </span> <?php echo $this->introtext; ?>
										<div class="dummyHeight"></div>
										<div class="clr"></div>
									</div>
									<div class="k2ItemFormEditor"> <span class="k2ItemFormEditorTitle"> <?php echo JText::_('K2_FULLTEXT_MAIN_CONTENT'); ?> </span> <?php echo $this->fulltext; ?>
										<div class="dummyHeight"></div>
										<div class="clr"></div>
									</div>
									<?php endif; ?>
									<?php if (count($this->K2PluginsItemContent)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemContent as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
									<div class="clr"></div>
								</div>
								<?php if ($this->params->get('showImageTab')): ?>
								<!-- Tab image -->
								<div class="simpleTabsContent" id="k2Tab2">
									<table class="admintable table">
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ITEM_IMAGE'); ?>
											</td>
											<td>
												<input type="file" name="image" class="fileUpload" />
												<i>(<?php echo JText::_('K2_MAX_UPLOAD_SIZE'); ?>: <?php echo ini_get('upload_max_filesize'); ?>)</i>
												<br />
												<br />
												<?php echo JText::_('K2_OR'); ?>
												<br />
												<br />
												<input type="text" name="existingImage" id="existingImageValue" class="text_area" readonly />
												<input type="button" value="<?php echo JText::_('K2_BROWSE_SERVER'); ?>" id="k2ImageBrowseServer"  />
												<br />
												<br />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ITEM_IMAGE_CAPTION'); ?>
											</td>
											<td>
												<input type="text" name="image_caption" size="30" class="text_area" value="<?php echo $this->row->image_caption; ?>" />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ITEM_IMAGE_CREDITS'); ?>
											</td>
											<td>
												<input type="text" name="image_credits" size="30" class="text_area" value="<?php echo $this->row->image_credits; ?>" />
											</td>
										</tr>
										<?php if (!empty($this->row->image)): ?>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ITEM_IMAGE_PREVIEW'); ?>
											</td>
											<td>
												<a class="modal" rel="{handler: 'image'}" href="<?php echo $this->row->image; ?>" title="<?php echo JText::_('K2_CLICK_ON_IMAGE_TO_PREVIEW_IN_ORIGINAL_SIZE'); ?>">
													<img alt="<?php echo $this->row->title; ?>" src="<?php echo $this->row->thumb; ?>" class="k2AdminImage" />
												</a>
												<input type="checkbox" name="del_image" id="del_image" />
												<label for="del_image"><?php echo JText::_('K2_CHECK_THIS_BOX_TO_DELETE_CURRENT_IMAGE_OR_JUST_UPLOAD_A_NEW_IMAGE_TO_REPLACE_THE_EXISTING_ONE'); ?></label>
											</td>
										</tr>
										<?php endif; ?>
									</table>
									<?php if (count($this->K2PluginsItemImage)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemImage as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if ($this->params->get('showImageGalleryTab')): ?>
								<!-- Tab image gallery -->
								<div class="simpleTabsContent" id="k2Tab3">
									<?php if ($this->lists['checkSIG']): ?>
									<table class="admintable table" id="item_gallery_content">
										<tr>
											<td align="right" valign="top" class="key">
												<?php echo JText::_('K2_COM_BE_ITEM_ITEM_IMAGE_GALLERY'); ?>
											</td>
											<td valign="top">
												<?php if($this->sigPro): ?>
												<a class="modal" rel="{handler: 'iframe', size: {x: 940, y: 560}}" href="index.php?option=com_sigpro&view=galleries&task=create&newFolder=<?php echo $this->sigProFolder; ?>&type=k2&tmpl=component"><?php echo JText::_('K2_COM_BE_ITEM_SIGPRO_UPLOAD'); ?></a> <i>(<?php echo JText::_('K2_COM_BE_ITEM_SIGPRO_UPLOAD_NOTE'); ?>)</i>
												<input name="sigProFolder" type="hidden" value="<?php echo $this->sigProFolder; ?>" />
												<br />
												<br />
												<?php echo JText::_('K2_OR'); ?>
												<?php endif; ?>
												<?php echo JText::_('K2_UPLOAD_A_ZIP_FILE_WITH_IMAGES'); ?> <input type="file" name="gallery" class="fileUpload" /> <span class="hasTip k2GalleryNotice" title="<?php echo JText::_('K2_UPLOAD_A_ZIP_FILE_HELP_HEADER'); ?>::<?php echo JText::_('K2_UPLOAD_A_ZIP_FILE_HELP_TEXT'); ?>"><?php echo JText::_('K2_UPLOAD_A_ZIP_FILE_HELP'); ?></span> <i>(<?php echo JText::_('K2_MAX_UPLOAD_SIZE'); ?>: <?php echo ini_get('upload_max_filesize'); ?>)</i>
												<br />
												<br />
												<?php echo JText::_('K2_OR_ENTER_A_FLICKR_SET_URL'); ?><?php echo JText::_('K2_OR_ENTER_A_FLICKR_SET_URL'); ?>
												<input type="text" name="flickrGallery" size="50" value="<?php echo ($this->row->galleryType == 'flickr') ? $this->row->galleryValue : ''; ?>" /> <span class="hasTip k2GalleryNotice" title="<?php echo JText::_('K2_VALID_FLICK_API_KEY_HELP_HEADER'); ?>::<?php echo JText::_('K2_VALID_FLICK_API_KEY_HELP_TEXT'); ?>"><?php echo JText::_('K2_UPLOAD_A_ZIP_FILE_HELP'); ?></span>

												<?php if (!empty($this->row->gallery)): ?>
												<!-- Preview -->
												<div id="itemGallery">
													<?php echo $this->row->gallery; ?>
													<br />
													<input type="checkbox" name="del_gallery" id="del_gallery" />
													<label for="del_gallery"><?php echo JText::_('K2_CHECK_THIS_BOX_TO_DELETE_CURRENT_IMAGE_GALLERY_OR_JUST_UPLOAD_A_NEW_IMAGE_GALLERY_TO_REPLACE_THE_EXISTING_ONE'); ?></label>
												</div>
												<?php endif; ?>
											</td>
										</tr>
									</table>
									<?php else: ?>
										<?php if (K2_JVERSION == '15'): ?>
										<dl id="system-message">
											<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
											<dd class="notice message fade">
												<ul>
													<li><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_SIMPLE_IMAGE_GALLERY_PRO_PLUGIN_IF_YOU_WANT_TO_USE_THE_IMAGE_GALLERY_FEATURES_OF_K2'); ?></li>
												</ul>
											</dd>
										</dl>
										<?php elseif(K2_JVERSION == '25'): ?>
										<div id="system-message-container">
											<dl id="system-message">
												<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
												<dd class="notice message">
													<ul>
														<li><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_SIMPLE_IMAGE_GALLERY_PRO_PLUGIN_IF_YOU_WANT_TO_USE_THE_IMAGE_GALLERY_FEATURES_OF_K2'); ?></li>
													</ul>
												</dd>
											</dl>
										</div>
										<?php else: ?>
										<div class="alert">
											<h4 class="alert-heading"><?php echo JText::_('K2_NOTICE'); ?></h4>
											<div><p><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_SIMPLE_IMAGE_GALLERY_PRO_PLUGIN_IF_YOU_WANT_TO_USE_THE_IMAGE_GALLERY_FEATURES_OF_K2'); ?></p></div>
										</div>
										<?php endif; ?>
									<?php endif; ?>
									<?php if (count($this->K2PluginsItemGallery)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemGallery as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if ($this->params->get('showVideoTab')): ?>
								<!-- Tab video -->
								<div class="simpleTabsContent" id="k2Tab4">
									<?php if ($this->lists['checkAllVideos']): ?>
									<table class="admintable table" id="item_video_content">
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_SOURCE'); ?>
											</td>
											<td>
												<div id="k2VideoTabs" class="simpleTabs">
													<ul class="simpleTabsNavigation">
														<li><a href="#k2VideoTab1"><?php echo JText::_('K2_UPLOAD'); ?></a></li>
														<li><a href="#k2VideoTab2"><?php echo JText::_('K2_BROWSE_SERVERUSE_REMOTE_MEDIA'); ?></a></li>
														<li><a href="#k2VideoTab3"><?php echo JText::_('K2_MEDIA_USE_ONLINE_VIDEO_SERVICE'); ?></a></li>
														<li><a href="#k2VideoTab4"><?php echo JText::_('K2_EMBED'); ?></a></li>
													</ul>
													<div id="k2VideoTab1" class="simpleTabsContent">
														<div class="panel" id="Upload_video">
															<input type="file" name="video" class="fileUpload" />
															<i>(<?php echo JText::_('K2_MAX_UPLOAD_SIZE'); ?>: <?php echo ini_get('upload_max_filesize'); ?>)</i> </div>
													</div>
													<div id="k2VideoTab2" class="simpleTabsContent">
														<div class="panel" id="Remote_video"> <a id="k2MediaBrowseServer" href="index.php?option=com_k2&view=media&type=video&tmpl=component&fieldID=remoteVideo"><?php echo JText::_('K2_BROWSE_VIDEOS_ON_SERVER')?></a> <?php echo JText::_('K2_OR'); ?> <?php echo JText::_('K2_PASTE_REMOTE_VIDEO_URL'); ?>
															<br />
															<br />
															<input type="text" size="50" name="remoteVideo" id="remoteVideo" value="<?php echo $this->lists['remoteVideo'] ?>" />
														</div>
													</div>
													<div id="k2VideoTab3" class="simpleTabsContent">
														<div class="panel" id="Video_from_provider"> <?php echo JText::_('K2_SELECT_VIDEO_PROVIDER'); ?> <?php echo $this->lists['providers']; ?> <br/><br/> <?php echo JText::_('K2_AND_ENTER_VIDEO_ID'); ?>
															<input type="text" size="50" name="videoID" value="<?php echo $this->lists['providerVideo'] ?>" />
															<br />
															<br />
															<a class="modal" rel="{handler: 'iframe', size: {x: 990, y: 600}}" href="http://www.joomlaworks.net/allvideos-documentation"><?php echo JText::_('K2_READ_THE_ALLVIDEOS_DOCUMENTATION_FOR_MORE'); ?></a> </div>
													</div>
													<div id="k2VideoTab4" class="simpleTabsContent">
														<div class="panel" id="embedVideo">
															<?php echo JText::_('K2_PASTE_HTML_EMBED_CODE_BELOW'); ?>
															<br />
															<textarea name="embedVideo" rows="5" cols="50" class="textarea"><?php echo $this->lists['embedVideo']; ?></textarea>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_CAPTION'); ?>
											</td>
											<td>
												<input type="text" name="video_caption" size="50" class="text_area" value="<?php echo $this->row->video_caption; ?>" />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_CREDITS'); ?>
											</td>
											<td>
												<input type="text" name="video_credits" size="50" class="text_area" value="<?php echo $this->row->video_credits; ?>" />
											</td>
										</tr>
										<?php if($this->row->video): ?>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_PREVIEW'); ?>
											</td>
											<td>
												<?php echo $this->row->video; ?>
												<br />
												<input type="checkbox" name="del_video" id="del_video" />
												<label for="del_video"><?php echo JText::_('K2_CHECK_THIS_BOX_TO_DELETE_CURRENT_VIDEO_OR_USE_THE_FORM_ABOVE_TO_REPLACE_THE_EXISTING_ONE'); ?></label>
											</td>
										</tr>
										<?php endif; ?>
									</table>
									<?php else: ?>
										<?php if (K2_JVERSION == '15'): ?>
										<dl id="system-message">
											<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
											<dd class="notice message fade">
												<ul>
													<li><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_ALLVIDEOS_PLUGIN_IF_YOU_WANT_TO_USE_THE_FULL_VIDEO_FEATURES_OF_K2'); ?></li>
												</ul>
											</dd>
										</dl>
										<?php elseif(K2_JVERSION == '25'): ?>
										<div id="system-message-container">
											<dl id="system-message">
												<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
												<dd class="notice message">
													<ul>
														<li><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_ALLVIDEOS_PLUGIN_IF_YOU_WANT_TO_USE_THE_FULL_VIDEO_FEATURES_OF_K2'); ?></li>
													</ul>
												</dd>
											</dl>
										</div>
										<?php else: ?>
										<div class="alert">
											<h4 class="alert-heading"><?php echo JText::_('K2_NOTICE'); ?></h4>
											<div><p><?php echo JText::_('K2_NOTICE_PLEASE_INSTALL_JOOMLAWORKS_ALLVIDEOS_PLUGIN_IF_YOU_WANT_TO_USE_THE_FULL_VIDEO_FEATURES_OF_K2'); ?></p></div>
										</div>
										<?php endif; ?>
									<table class="admintable table" id="item_video_content">
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_SOURCE'); ?>
											</td>
											<td>
												<div id="k2VideoTabs" class="simpleTabs">
													<ul class="simpleTabsNavigation">
														<li><a href="#k2VideoTab4"><?php echo JText::_('K2_EMBED'); ?></a></li>
													</ul>
													<div class="simpleTabsContent" id="k2VideoTab4">
														<div class="panel" id="embedVideo">
															<?php echo JText::_('K2_PASTE_HTML_EMBED_CODE_BELOW'); ?>
															<br />
															<textarea name="embedVideo" rows="5" cols="50" class="textarea"><?php echo $this->lists['embedVideo']; ?></textarea>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_CAPTION'); ?>
											</td>
											<td>
												<input type="text" name="video_caption" size="50" class="text_area" value="<?php echo $this->row->video_caption; ?>" />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_CREDITS'); ?>
											</td>
											<td>
												<input type="text" name="video_credits" size="50" class="text_area" value="<?php echo $this->row->video_credits; ?>" />
											</td>
										</tr>
										<?php if($this->row->video): ?>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_MEDIA_PREVIEW'); ?>
											</td>
											<td>
												<?php echo $this->row->video; ?>
												<br />
												<input type="checkbox" name="del_video" id="del_video" />
												<label for="del_video"><?php echo JText::_('K2_USE_THE_FORM_ABOVE_TO_REPLACE_THE_EXISTING_VIDEO_OR_CHECK_THIS_BOX_TO_DELETE_CURRENT_VIDEO'); ?></label>
											</td>
										</tr>
										<?php endif; ?>
									</table>
									<?php endif; ?>
									<?php if (count($this->K2PluginsItemVideo)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemVideo as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if ($this->params->get('showExtraFieldsTab')): ?>
								<!-- Tab extra fields -->
								<div class="simpleTabsContent" id="k2Tab5">
									<div id="extraFieldsContainer">
										<?php if (count($this->extraFields)): ?>
										<table class="admintable table" id="extraFields">
											<?php foreach($this->extraFields as $extraField): ?>
											<tr>
												<?php if($extraField->type == 'header'): ?>
												<td colspan="2" ><h4 class="k2ExtraFieldHeader"><?php echo $extraField->name; ?></h4></td>
												<?php else: ?>
												<td align="right" class="key">
													<label for="K2ExtraField_<?php echo $extraField->id; ?>"><?php echo $extraField->name; ?></label>
												</td>
												<td>
													<?php echo $extraField->element; ?>
												</td>
												<?php endif; ?>
											</tr>
											<?php endforeach; ?>
										</table>
										<?php else: ?>
											<?php if (K2_JVERSION == '15'): ?>
												<dl id="system-message">
													<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
													<dd class="notice message fade">
														<ul>
															<li><?php echo JText::_('K2_PLEASE_SELECT_A_CATEGORY_FIRST_TO_RETRIEVE_ITS_RELATED_EXTRA_FIELDS'); ?></li>
														</ul>
													</dd>
												</dl>
											<?php elseif (K2_JVERSION == '25'): ?>
											<div id="system-message-container">
												<dl id="system-message">
													<dt class="notice"><?php echo JText::_('K2_NOTICE'); ?></dt>
													<dd class="notice message">
														<ul>
															<li><?php echo JText::_('K2_PLEASE_SELECT_A_CATEGORY_FIRST_TO_RETRIEVE_ITS_RELATED_EXTRA_FIELDS'); ?></li>
														</ul>
													</dd>
												</dl>
											</div>
											<?php else: ?>
											<div class="alert">
												<h4 class="alert-heading"><?php echo JText::_('K2_NOTICE'); ?></h4>
												<div>
													<p><?php echo JText::_('K2_PLEASE_SELECT_A_CATEGORY_FIRST_TO_RETRIEVE_ITS_RELATED_EXTRA_FIELDS'); ?></p>
												</div>
											</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<?php if (count($this->K2PluginsItemExtraFields)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemExtraFields as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if ($this->params->get('showAttachmentsTab')): ?>
								<!-- Tab attachements -->
								<div class="simpleTabsContent" id="k2Tab6">
									<div class="itemAttachments">
										<?php if (count($this->row->attachments)): ?>
										<table class="adminlist table">
											<tr>
												<th>
													<?php echo JText::_('K2_FILENAME'); ?>
												</th>
												<th>
													<?php echo JText::_('K2_TITLE'); ?>
												</th>
												<th>
													<?php echo JText::_('K2_TITLE_ATTRIBUTE'); ?>
												</th>
												<th>
													<?php echo JText::_('K2_DOWNLOADS'); ?>
												</th>
												<th>
													<?php echo JText::_('K2_OPERATIONS'); ?>
												</th>
											</tr>
											<?php foreach($this->row->attachments as $attachment): ?>
											<tr>
												<td class="attachment_entry">
													<?php echo $attachment->filename; ?>
												</td>
												<td>
													<?php echo $attachment->title; ?>
												</td>
												<td>
													<?php echo $attachment->titleAttribute; ?>
												</td>
												<td>
													<?php echo $attachment->hits; ?>
												</td>
												<td>
													<a href="<?php echo $attachment->link; ?>"><?php echo JText::_('K2_DOWNLOAD'); ?></a> <a class="deleteAttachmentButton" href="<?php echo JURI::base(true); ?>/index.php?option=com_k2&amp;view=item&amp;task=deleteAttachment&amp;id=<?php echo $attachment->id?>&amp;cid=<?php echo $this->row->id; ?>"><?php echo JText::_('K2_DELETE'); ?></a>
												</td>
											</tr>
											<?php endforeach; ?>
										</table>
										<?php endif; ?>
									</div>
									<div id="addAttachment">
										<input type="button" id="addAttachmentButton" value="<?php echo JText::_('K2_ADD_ATTACHMENT_FIELD'); ?>" />
										<i>(<?php echo JText::_('K2_MAX_UPLOAD_SIZE'); ?>: <?php echo ini_get('upload_max_filesize'); ?>)</i> </div>
									<div id="itemAttachments"></div>
									<?php if (count($this->K2PluginsItemAttachments)): ?>
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemAttachments as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if(count(array_filter($this->K2PluginsItemOther)) && $this->params->get('showK2Plugins')): ?>
								<!-- Tab other plugins -->
								<div class="simpleTabsContent" id="k2Tab7">
									<div class="itemPlugins">
										<?php foreach($this->K2PluginsItemOther as $K2Plugin): ?>
										<?php if(!is_null($K2Plugin)): ?>
										<fieldset>
											<legend><?php echo $K2Plugin->name; ?></legend>
											<?php echo $K2Plugin->fields; ?>
										</fieldset>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
								<?php endif; ?>
							</div>
							<!-- Tabs end here -->

							<input type="hidden" name="isSite" value="<?php echo (int)$this->mainframe->isSite(); ?>" />
							<?php if($this->mainframe->isSite()): ?>
							<input type="hidden" name="lang" value="<?php echo JRequest::getCmd('lang'); ?>" />
							<?php endif; ?>
							<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
							<input type="hidden" name="option" value="com_k2" />
							<input type="hidden" name="view" value="item" />
							<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
							<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
							<?php echo JHTML::_('form.token'); ?>
						</td>
						<td id="adminFormK2Sidebar"<?php if($this->mainframe->isSite() && !$this->params->get('sideBarDisplayFrontend')): ?> style="display:none;"<?php endif; ?> class="xmlParamsFields">
							<?php if($this->row->id): ?>
							<table class="sidebarDetails table">
								<tr>
									<td>
										<strong><?php echo JText::_('K2_ITEM_ID'); ?></strong>
									</td>
									<td>
										<?php echo $this->row->id; ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_PUBLISHED'); ?></strong>
									</td>
									<td>
										<?php echo ($this->row->published > 0) ? JText::_('K2_YES') : JText::_('K2_NO'); ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_FEATURED'); ?></strong>
									</td>
									<td>
										<?php echo ($this->row->featured > 0) ? JText::_('K2_YES'):	JText::_('K2_NO'); ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_CREATED_DATE'); ?></strong>
									</td>
									<td>
										<?php echo $this->lists['created']; ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_CREATED_BY'); ?></strong>
									</td>
									<td>
										<?php echo $this->row->author; ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_MODIFIED_DATE'); ?></strong>
									</td>
									<td>
										<?php echo $this->lists['modified']; ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_MODIFIED_BY'); ?></strong>
									</td>
									<td>
										<?php echo $this->row->moderator; ?>
									</td>
								</tr>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_HITS'); ?></strong>
									</td>
									<td>
										<?php echo $this->row->hits; ?>
										<?php if($this->row->hits): ?>
										<input id="resetHitsButton" type="button" value="<?php echo JText::_('K2_RESET'); ?>" class="button" name="resetHits" />
										<?php endif; ?>
									</td>
								</tr>
								<?php endif; ?>
								<?php if($this->row->id): ?>
								<tr>
									<td>
										<strong><?php echo JText::_('K2_RATING'); ?></strong>
									</td>
									<td>
										<?php echo $this->row->ratingCount; ?> <?php echo JText::_('K2_VOTES'); ?>
										<?php if($this->row->ratingCount): ?>
										<br />
										(<?php echo JText::_('K2_AVERAGE_RATING'); ?>: <?php echo number_format(($this->row->ratingSum/$this->row->ratingCount),2); ?>/5.00)
										<?php endif; ?>
										<input id="resetRatingButton" type="button" value="<?php echo JText::_('K2_RESET'); ?>" class="button" name="resetRating" />
									</td>
								</tr>
							</table>
							<?php endif; ?>
							<div id="k2Accordion">
								<h3><a href="#"><?php echo JText::_('K2_AUTHOR_PUBLISHING_STATUS'); ?></a></h3>
								<div>
									<table class="admintable table">
										<?php if(isset($this->lists['language'])): ?>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_LANGUAGE'); ?>
											</td>
											<td>
												<?php echo $this->lists['language']; ?>
											</td>
										</tr>
										<?php endif; ?>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_AUTHOR'); ?>
											</td>
											<td id="k2AuthorOptions">
												<span id="k2Author"><?php echo $this->row->author; ?></span>
												<?php if($this->mainframe->isAdmin() || ($this->mainframe->isSite() && $this->permissions->get('editAll'))): ?>
												<a class="modal" rel="{handler:'iframe', size: {x: 800, y: 460}}" href="index.php?option=com_k2&amp;view=users&amp;task=element&amp;tmpl=component"><?php echo JText::_('K2_CHANGE'); ?></a>
												<input type="hidden" name="created_by" value="<?php echo $this->row->created_by; ?>" />
												<?php endif; ?>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_AUTHOR_ALIAS'); ?>
											</td>
											<td>
												<input class="text_area" type="text" name="created_by_alias" maxlength="250" value="<?php echo $this->row->created_by_alias; ?>" />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ACCESS_LEVEL'); ?>
											</td>
											<td>
												<?php echo $this->lists['access']; ?>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_CREATION_DATE'); ?>
											</td>
											<td class="k2ItemFormDateField">
												<?php echo $this->lists['createdCalendar']; ?>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_START_PUBLISHING'); ?>
											</td>
											<td class="k2ItemFormDateField">
												<?php echo $this->lists['publish_up']; ?>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_FINISH_PUBLISHING'); ?>
											</td>
											<td class="k2ItemFormDateField">
												<?php echo $this->lists['publish_down']; ?>
											</td>
										</tr>
									</table>
								</div>
								<h3><a href="#"><?php echo JText::_('K2_METADATA_INFORMATION'); ?></a></h3>
								<div>
									<table class="admintable table">
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_DESCRIPTION'); ?>
											</td>
											<td>
												<textarea name="metadesc" rows="5" cols="20"><?php echo $this->row->metadesc; ?></textarea>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_KEYWORDS'); ?>
											</td>
											<td>
												<textarea name="metakey" rows="5" cols="20"><?php echo $this->row->metakey; ?></textarea>
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_ROBOTS'); ?>
											</td>
											<td>
												<input type="text" name="meta[robots]" value="<?php echo $this->lists['metadata']->get('robots'); ?>" />
											</td>
										</tr>
										<tr>
											<td align="right" class="key">
												<?php echo JText::_('K2_AUTHOR'); ?>
											</td>
											<td>
												<input type="text" name="meta[author]" value="<?php echo $this->lists['metadata']->get('author'); ?>" />
											</td>
										</tr>
									</table>
								</div>
								<?php if($this->mainframe->isAdmin()): ?>
								<h3><a href="#"><?php echo JText::_('K2_ITEM_VIEW_OPTIONS_IN_CATEGORY_LISTINGS'); ?></a></h3>
								<div>
									<?php if(version_compare( JVERSION, '1.6.0', 'ge' )): ?>
									<fieldset class="panelform">
										<ul class="adminformlist">
											<?php foreach($this->form->getFieldset('item-view-options-listings') as $field): ?>
											<li>
												<?php if($field->type=='header'): ?>
												<div class="paramValueHeader"><?php echo $field->input; ?></div>
												<?php elseif($field->type=='Spacer'): ?>
												<div class="paramValueSpacer">&nbsp;</div>
												<div class="clr"></div>
												<?php else: ?>
												<div class="paramLabel"><?php echo $field->label; ?></div>
												<div class="paramValue"><?php echo $field->input; ?></div>
												<div class="clr"></div>
												<?php endif; ?>
											</li>
											<?php endforeach; ?>
										</ul>
									</fieldset>
									<?php else: ?>
									<?php echo $this->form->render('params', 'item-view-options-listings'); ?>
									<?php endif; ?>
								</div>
								<h3><a href="#"><?php echo JText::_('K2_ITEM_VIEW_OPTIONS'); ?></a></h3>
								<div>
									<?php if(version_compare( JVERSION, '1.6.0', 'ge' )): ?>
									<fieldset class="panelform">
										<ul class="adminformlist">
											<?php foreach($this->form->getFieldset('item-view-options') as $field): ?>
											<li>
												<?php if($field->type=='header'): ?>
												<div class="paramValueHeader"><?php echo $field->input; ?></div>
												<?php elseif($field->type=='Spacer'): ?>
												<div class="paramValueSpacer">&nbsp;</div>
												<div class="clr"></div>
												<?php else: ?>
												<div class="paramLabel"><?php echo $field->label; ?></div>
												<div class="paramValue"><?php echo $field->input; ?></div>
												<div class="clr"></div>
												<?php endif; ?>
											</li>
											<?php endforeach; ?>
										</ul>
									</fieldset>
									<?php else: ?>
									<?php echo $this->form->render('params', 'item-view-options'); ?>
									<?php endif; ?>
								</div>
								<?php endif; ?>
								<?php if($this->aceAclFlag): ?>
								<h3><a href="#"><?php echo JText::_('AceACL') . ' ' . JText::_('COM_ACEACL_COMMON_PERMISSIONS'); ?></a></h3>
								<div><?php AceaclApi::getWidget('com_k2.item.'.$this->row->id, true); ?></div>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="clr"></div>
			<?php if($this->mainframe->isSite()): ?>
		</div>
	</div>
	<?php endif; ?>
</form>
