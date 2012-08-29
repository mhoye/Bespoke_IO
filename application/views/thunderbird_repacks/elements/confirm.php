<?php slot::set('head_title', $head_title . ' :: ' . html::specialchars($repack->title)); ?>
<?php slot::start('crumbs') ?>
    <a href="<?=$repack->url() ?>"><?= html::specialchars($repack->display_title) ?></a> :: <?=$crumbs?>
<?php slot::end() ?>
<div class="confirm">

    <?php if (!empty($repack)): ?>
        <?=View::factory('thunderbird_repacks/elements/details')->set(array(
            'repack' => $repack,
            'hide_actions' => true,
        ))->render() ?> 
    <?php endif ?>

    <form action="<?=$url?>" method="POST">
        <fieldset><legend><?=$message?></legend>
            <?php if (slot::exists('preamble')): ?>
                <div class="preamble">
                    <?=slot::get('preamble')?>
                </div>
            <?php endif ?>
            <p>
                <button class="button yellow large" name="confirm" id="confirm" value="yes"><?=_('Yes, confirm.')?></button>  
                <button class="button blue large" name="cancel" id="cancel" value="no"><?=_('No, cancel.')?></button> 
            </p>
        </fieldset>
    </form>

</div>
