<?php $view->setLayout('myaccount'); ?>

<h1><?=Language::getText('update_video_header')?></h1>

<?php if ($message): ?>
    <div class="message <?=$message_type?>"><?=$message?></div>
<?php endif; ?>

<div class="form wide">

    <p><a href="<?=HOST?>/myaccount/myvideos/"><?=Language::getText('back_to_videos')?></a></p><br />
    <form action="<?=HOST?>/myaccount/editvideo/<?=$video->videoId?>/" method="post">

        <label class="<?=(isset($errors['title'])) ? 'error' : ''?>"><?=Language::getText('title')?>:</label>
        <input class="text" type="text" name="title" value="<?=$video->title?>" />

        <label class="<?=(isset($errors['tags'])) ? 'error' : ''?>"><?=Language::getText('tags')?>:</label>
        <input class="text" type="text" name="tags" value="<?=implode(', ', $video->tags)?>" />
        <p class="hint"><?=Language::getText('comma_delimited')?></p>

        <label class="<?=(isset($errors['cat_id'])) ? 'error' : ''?>"><?=Language::getText('category')?>:</label>
        <select class="dropdown" name="cat_id">
        <?php foreach ($categoryList as  $category): ?>
            <option value="<?=$category->categoryId?>"<?=($video->categoryId == $category->categoryId) ? ' selected="selected"' : ''?>><?=$category->name?></option>
        <?php endforeach; ?>
        </select>

        <label class="<?=(isset($errors['description'])) ? 'error' : ''?>"><?=Language::getText('description')?>:</label>
        <textarea class="text" name="description" rows="10" cols="45"><?=$video->description?></textarea>

        <input id="disable_embed" type="checkbox" name="disable_embed" value="1" <?=($video->disableEmbed) ? 'checked="checked"' : ''?> />
        <label for="disable_embed"><?=Language::getText('disable_embed')?></label> <em>(<?=Language::getText('disable_embed_description')?>)</em><br>

        <input id="gated_video" type="checkbox" name="gated" value="1" <?=($video->gated == '1') ? 'checked="checked"' : ''?> />
        <label for="gated_video"><?=Language::getText('gated')?></label> <em>(<?=Language::getText('gated_description')?>)</em><br>

        <input id="private_video" data-block="private_url" class="showhide" type="checkbox" name="private" value="1" <?=($video->private) ? 'checked="checked"' : ''?> />
        <label for="private_video"><?=Language::getText('private')?></label> <em>(<?=Language::getText('private_description')?>)</em><br>

        <p id="private_url" class="<?=($video->private) ? '' : 'hide'?>">
            <label class="<?=(isset($errors['private_url'])) ? 'error' : ''?>"><?=Language::getText('private_url')?>:</label>
            <?=HOST?>/private/videos/<span><?=(!empty($video->privateUrl)) ? $video->privateUrl : $privateUrl?></span>/

            <input type="hidden" name="private_url" value="<?=(!empty($video->privateUrl)) ? $video->privateUrl : $privateUrl?>" />
            <a href="" class="small"><?=Language::getText('regenerate')?></a>
        </p>

        <input type="hidden" name="submitted" value="TRUE" />
        <input class="button" type="submit" name="button" value="<?=Language::getText('update_video_button')?>" />
    </form>

</div>