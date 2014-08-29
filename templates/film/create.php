<?php
use \App\Component\Form;
$form = Form::create()->of($entry);
?>
<h2>Add <?php echo f('controller.name') ?></h2>

<form method="post">

    <?php echo $form->formatInput('episode') ?>

    <div class="command-bar">
        <input type="submit">
        <a href="<?php echo f('controller.url') ?>">List</a>
    </div>

</form>
