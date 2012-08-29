<div class="intro">
    <p><?=_('You can customize and lock your browser\'s homepage.')?></p>
</div>
<div class="pane"> 
    <div>
        <fieldset>
            <div>
                <ul class="lock_pref">
                <?php
                    $homepage = form::value('lock_homepage');
                    if (empty($homepage)) $homepage = array(); 
                ?>
                <?php 
                    $ie_homepage = form::value('ie_homepage');
                    if (empty($ie_homepage)) $ie_homepage = array();
                ?>
                <!-- CHECKBOX: lock the homepage? -->
                <?php foreach (Repack_Model::$lockablePrefs as $name=>$label): ?>
                    <?php if ($name == 'homepage'): ?>
                        <li>
                        <?= form::checkbox('lock_homepage[]', $name, in_array($name, $homepage))?> 
                        <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock the homepage in the browser</b></acronym>')?>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>

                <!-- CHECKBOX: import from IE? -->
                <?php foreach (Repack_Model::$ie_imports as $name=>$label): ?>
                    <?php if ($name == 'ie_homepage'): ?>
                        <li>
                        <?= form::checkbox("ie_homepage[]", $name, in_array($name, $ie_homepage))?>
                        <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Import the homepage from Internet Explorer</b></acronym>')?>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>
                </ul>
            </div> 

        	<div class="user_title">
        		<p><?=_('Enter the url of the homepage for your browser:')?></p>
            <?= form::input(array('name'=>'home_url', 'size'=>'60'), form::value('home_url')) ?>
        	</div>
        </fieldset>
    </div>
</div>
