<?php
    $none = true;
?>

<li class="section certificate_management">
<h3><?=_('Certificates')?> <a target="_top" href="<?=$repack->url()?>;edit?section=certificate_management"><?=_('edit')?></a></h3>
    <ul>
        <?php if (!empty($certificates)): ?>
            <?php $none = false; ?>
            <?php foreach ($certificates as $cert): ?>
                <li><?=html::specialchars(basename($cert))?></li>
            <?php endforeach ?>
        <?php endif ?>
        <?php if ($none): ?>
            <li class="empty">None.</li>
        <?php endif ?>
    </ul>
</li>
