<?php slot::start('body_end') ?>
    <?=html::script(array(
        'js/byob/repacks/edit.js',
    ))?>
<?php slot::end() ?>
<?php
    $sections = Mozilla_BYOB_EditorRegistry::getSections($repack);
    if (!isset($section) || !isset($sections[$section])) {
        $section = 'general';
    }
    form::$data   = $form_data;
    form::$errors = isset($form_errors) ? $form_errors : array();

    $classes = array();
    if (empty(form::$errors) && 'true' == $show_review) {
        $classes[] = 'show_review';
    }

    $section_editor_name = !empty($editor->view_name) ? 
        $editor->view_name : 'repacks/edit/' . $section;
    $section_editor = View::factory($section_editor_name)->render();
?>
<?php slot::set('head_title', sprintf(_('customize :: %s'), html::specialchars($repack->display_title))); ?>
<?php slot::start('crumbs') ?>
    <a href="<?= $repack->url() ?>"><?=sprintf(_('%1$s :: customize your browser'), html::specialchars($repack->display_title)) ?></a>
<?php slot::end() ?>

<?= form::open(url::current() . '?section=' . $section , array('class'=>join(' ', $classes), 'id'=>'wizard'), array()); ?>
    <input type="hidden" name="changed" id="changed" value="false" />
    <input type="hidden" name="show_review" id="show_review" value="false" />
    <input type="hidden" name="done" id="done" value="false" />
    <input type="hidden" name="next_section" id="next_section" value="<?=$section?>" />

<?php if (!slot::get('is_popup')): ?>
<div class="editor">
  <h2><?=html::specialchars($repack->display_title)?></h2>

	<div class="editor-content">
		<div class="tab-set">
			<div class="tab-tabs">
        <ul>
        <?php $first = true ?>
        <?php foreach ($sections as $name => $title): ?>
        <?php 
          if (false === $title) continue;
          $url = url::base() . url::current() . '?section=' . $name;
          $classes = array();
          $tabclasses = array();
          if (true === $first) {
            $first = false;
            $classes[] = 'first';
          }
          if ($name == $section) {
            $classes[] = 'selected';
            $tabclasses[] = 'current';
          }
          if (!empty(form::$errors) && $name == $section) {
            $classes[] = 'error';
          } else if (!empty($repack->changed_sections) && in_array($name, $repack->changed_sections)) {
            $classes[] = 'checked';
          }
          $attr = (empty($classes)) ? '' : ' class="'.join(' ',$classes).'"';
          $tabattr =  (empty($tabclasses)) ? '' : ''.join(' ',$tabclasses);
        ?>
			  <a href="<?=$url?>"><div class="each-tab" id="<?=$tabattr?>"><li <?=$attr?>><?=$title?></li></div></a>
        <?php endforeach ?>
		    </ul>	
      </div>
			<div class="tab-content section-<?= $section ?>">
      <?php endif ?>
      <?=$section_editor?>
      <?php if (!slot::get('is_popup')): ?>
        <div class="tab-footer section_nav">
          <?php
            $names = array_keys($sections);
            $pos = array_search($section, $names);
            $prev_name = ($pos == 0) ?
                null : $names[$pos-1];
            $next_name = ($pos == count($names)-1) ?
                null : $names[$pos+1];
            $base_url = url::base() . url::current() . '?section=';
          ?>
          <?php if (null !== $prev_name && false !== $sections[$prev_name]): ?>
            <div class="prev_section float-left button blue">
              <a href="<?=$base_url.$prev_name?>"><?=_('&laquo;&nbsp; Previous Step')?></a>
            </div>
          <?php endif ?>
          <?php if (null !== $next_name && false !== $sections[$next_name]): ?>
            <div class="next_section float-left button blue"><a href="<?=$base_url.$next_name?>"><?=_('Next Step &nbsp;&raquo;')?></a></div>
          <?php endif ?>

          <!-- Review etc buttons go here -->

          <button id="save-and-review" class="button yellow float-right"  href="<?=url::full_current()?>?section=review"><?=_('Review and Build')?></button>
          <button id="save-and-close" class="button blue float-right"><?=_('Save and Close')?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif ?>
<?= form::close() ?>
<?=slot::get('after_form')?>
