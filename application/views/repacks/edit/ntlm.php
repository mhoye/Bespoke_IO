<div class="intro">
  <p><?=_('Specify a list of URIs that will use your Windows domain credentials for login.')?></p>
</div>
<div class="pane">
  <div>
    <fieldset>
      <p>The list of URIs can be separated by either commas or spaces.</p>
      <div class="user_title">
        <?= form::textarea('ntlm_uris', form::value('ntlm_uris'))?>
      </div>
    </fieldset>
  </div>
</div>
