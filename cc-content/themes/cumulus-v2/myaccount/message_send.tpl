<?php $view->SetLayout ('myaccount'); ?>

<h1><?=Language::GetText('message_send_header')?></h1>

<?php if ($message): ?>
    <div class="message <?=$message_type?>"><?=$message?></div>
<?php endif; ?>


<div class="form wide">
    <form action="<?=HOST?>/myaccount/message/send/" method="post">
        <label class="<?=(isset ($errors['recipient'])) ? 'error' : ''?>"><?=Language::GetText('to')?>: </label>
        <input class="text" type="text" name="to" value="<?=$to?>" />
        <p class="hint"><em><?=Language::GetText('members_username')?></em></p>

        <label class="<?=(isset ($errors['subject'])) ? 'error' : ''?>"><?=Language::GetText('subject')?>: </label>
        <input class="text" type="text" name="subject" value="<?=htmlspecialchars($subject)?>" />

        <label class="<?=(isset ($errors['message'])) ? 'error' : ''?>"><?=Language::GetText('message')?>: </label>
        <textarea class="text" name="message" cols="45" rows="10"><?=htmlspecialchars($msg)?></textarea>

        <input type="hidden" name="submitted" value="yes" />
        <input class="button" type="submit" name="button" value="<?=Language::GetText('message_send_button')?>" />
    </form>
</div>