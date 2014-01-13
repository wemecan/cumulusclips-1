<?php $view->SetLayout ('myaccount'); ?>

<h1><?=Language::GetText('privacy_settings_header')?></h1>

<?php if ($message): ?>
    <div class="message <?=$message_type?>"><?=$message?></div>
<?php endif; ?>

<div class="form">
    <form action="<?=HOST?>/myaccount/privacy-settings/" method="post">

        <label><?=Language::GetText('alert_comment')?>:</label>
        <select class="dropdown" size="1" name="video_comment">
            <option value="1"<?=($privacy->videoComment) ? ' selected="selected"' : ''?>><?=Language::GetText('yes')?></option>
            <option value="0"<?=(!$privacy->videoComment) ? ' selected="selected"' : ''?>><?=Language::GetText('no')?></option>
        </select>

        <label><?=Language::GetText('alert_video')?>:</label>
        <select class="dropdown" size="1" name="new_video">
            <option value="1"<?=($privacy->newVideo) ? ' selected="selected"' : ''?>><?=Language::GetText('yes')?></option>
            <option value="0"<?=(!$privacy->newVideo) ? ' selected="selected"' : ''?>><?=Language::GetText('no')?></option>
        </select>

        <label><?=Language::GetText('alert_message')?>:</label>
        <select class="dropdown" size="1" name="new_message">
            <option value="1"<?=($privacy->newMessage) ? ' selected="selected"' : ''?>><?=Language::GetText('yes')?></option>
            <option value="0"<?=(!$privacy->newMessage) ? ' selected="selected"' : ''?>><?=Language::GetText('no')?></option>
        </select>

        <label><?=Language::GetText('alertVideoReady')?>:</label>
        <select class="dropdown" size="1" name="video_ready">
            <option value="1"<?=($privacy->videoReady) ? ' selected="selected"' : ''?>><?=Language::GetText('yes')?></option>
            <option value="0"<?=(!$privacy->videoReady) ? ' selected="selected"' : ''?>><?=Language::GetText('no')?></option>
        </select>

        <input type="hidden" name="submitted" value="TRUE" />
        <input class="button" type="submit" name="button" value="<?=Language::GetText('privacy_settings_button')?>" />
    </form>
</div>