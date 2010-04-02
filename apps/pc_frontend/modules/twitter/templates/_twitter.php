<?php use_stylesheet('/opTwitterlikePlugin/css/twitter') ?>
<?php use_javascript('/sfProtoculousPlugin/js/prototype') ?>
<?php use_javascript('/opTwitterlikePlugin/js/twitter') ?>

<?php slot('body') ?>
<div class="twitter">
<form method="post" action="<?php echo url_for('@twitter_post') ?>">
<span id="count">
<span id="count_num">140</span>
<span id="count_plus" title="もう少し入力できます"></span>
</span>
<?php echo $form->renderHiddenFields() ?>
<?php echo $form['body'] ?>
<input type="submit" id="submit" value="送信" />
<?php echo $form['twitter'].$form['twitter']->renderLabel() ?>
</form>

<ol id="timeline" class="activities">
<?php foreach ($activities as $activity): ?>
<?php include_partial('default/activityRecord', array('activity' => $activity)); ?>
<?php endforeach; ?>
</ol>

</div>
<?php end_slot() ?>

<?php op_include_box('twitter_'.$gadget->id, get_slot('body'), array(
  'title' => 'Twtter風ガジェット',
)) ?>
