<div class="intro">
	<p><?=_('If your organization uses a proxy to access the web, specify it here to include it in your customized browser. Remember to lock the proxy settings if you wish to prevent end users from changing or disabling them.')?></p>
</div>
<div class="pane">
  <div> 
    <fieldset>
      <div>
        <ul class="lock_pref">
            <?php 
                $proxies = form::value('lock_proxy');
                if (empty($proxies)) $proxies = array();

                $ie_proxies = form::value('ie_proxy');
                if (empty($ie_proxies)) $ie_proxies = array();
            ?>
            <?php foreach (Repack_Model::$lockablePrefs as $name => $label): ?>
                <?php if ($name == 'proxy'): ?>
                    <li>
                      <?= form::checkbox('lock_proxy[]', $name, in_array($name, $proxies))?> 
                      <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Lock the proxy settings in the browser</b></acronym>')?> 
                    </li>
                <?php endif ?>
            <?php endforeach ?>

            <?php foreach (Repack_Model::$ie_imports as $name => $label): ?>
                <?php if ($name=='ie_proxy'): ?>
                    <li>
                      <?= form::checkbox('ie_proxy[]', $name, in_array($name, $ie_proxies))?> 
                      <?=_('<acronym title="End users will not be able to change this preference once the browser is deployed"><b>Import the proxy settings from Internet Explorer</b></acronym>')?> 
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
      </div>
    </fieldset>
    <fieldset>
      <div>
        <ul class="proxychoices">
          <li><?= form::radio('proxy_setting', '0', !((form::value('proxy_setting') == '0') ? true : false)) ?>
            <?=_('No proxy')?></li>
          <li><?= form::radio('proxy_setting', '4', ((form::value('proxy_setting') == '4') ? true : false)) ?> 
            <?=_('Auto-detect proxy settings for this network')?></li>
          <li><?= form::radio('proxy_setting', '5', ((form::value('proxy_setting') == '5') ? true : false)) ?> 
            <?=_('Use system proxy settings')?></li> 
          <li><?= form::radio('proxy_setting', '1', ((form::value('proxy_setting') == '1') ? true : false)) ?> 
            <?=_('Manual proxy configuration')?>
            <ul>
              <li>
                <div class="label"><?=_('HTTP Proxy: ') ?></div>
                <div class="proxyType">
                  <?= form::input('http_proxy', form::value('http_proxy')) ?>  	
                </div>
                <div class="port"><?=_('Port: ') ?></div>
                <div class="proxyInput">
                  <?= form::input('http_proxy_port', !!form::value('http_proxy_port', '0')) ?>
                </div>
              </li>
              <li>
                <div class="label"><?=_('SSL Proxy: ') ?></div>
                <div class="proxyType">
                  <?= form::input('ssl_proxy', form::value('ssl_proxy')) ?>	
                </div>
                <div class="port"><?=_('Port: ') ?></div>
                <div  class="proxyInput">
                  <?= form::input('ssl_proxy_port', form::value('ssl_proxy_port', '0')) ?>
                </div>
              </li>
              <li>
                <div class="label"><?=_('FTP Proxy: ') ?></div>
                <div class="proxyType">
                  <?= form::input('ftp_proxy', form::value('ftp_proxy')) ?>  
                </div>
                <div class="port"><?=_('Port: ') ?></div>
                <div  class="proxyInput">
                  <?= form::input('ftp_proxy_port', form::value('ftp_proxy_port','0')) ?>
                </div>
              </li>
              <li>
                <div class="label"><?=_('Gopher Proxy: ') ?></div>
                <div class="proxyType">
                  <?= form::input('gopher_proxy', form::value('gopher_proxy')) ?>	
                </div>
                <div class="port"><?=_('Port: ') ?></div>
                <div  class="proxyInput">
                  <?= form::input('gopher_proxy_port', form::value('gopher_proxy_port', '0')) ?>
                </div>
              </li>
              <li>
                <div class="label"><?=_('SOCKS Host: ') ?></div>
                <div class="proxyType">
                  <?= form::input('socks_host', form::value('socks_host')) ?>	
                </div>
                <div class="port"><?=_('Port: ') ?></div>
                <div  class="proxyInput">
                  <?= form::input('socks_host_port', form::value('socks_host_port', '0')) ?>
                </div>
              </li>
              <!-- 
              Radio buttons Socks v4 and Socks V5
              -->
              <li class="goleft">
                <?= form::radio('socks_version', '4', form::value('socks_version') == '4' ? true : false )?> <?=_(' SOCKS v4')?> &nbsp;
                <?= form::radio('socks_version', '5', ! (form::value('socks_version') == '4' ? true : false))?> <?=_(' SOCKS v5')?>
              </li>
              <li> 
                <div class="noproxyfor label"><?=_('No Proxy for: ')?></div>
                <?= form::input('no_proxy_for_address', form::value('no_proxy_for_address'))?>
                &nbsp;
                <?=_('Eg. localhost, 127.0.01') ?>
              </li>
            </ul>
          </li>
          <li>
            <?= form::radio('proxy_setting', '2', ((form::value('proxy_setting') === '2') ? true : false)) ?> 
            <?=_('Automatic proxy configuration URL:')?> 
            <?= form::input('proxy_config_url',  form::value('proxy_config_url'))?>
          </li>
        </ul>
      </div>
    </fieldset>
  </div>
</div>
