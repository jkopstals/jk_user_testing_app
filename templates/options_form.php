<div class="section">
    <div class="container">
        <form method="POST" action="/" id="option_form"> 
            <input type="hidden" id="respondent_uuid" name="respondent_uuid" value="<?= $respondent_uuid ?>">
            <input type="hidden" id="test_uuid" name="test_uuid" value="<?= $test_uuid ?>">
            <input type="hidden" id="question_uuid" name="question_uuid" value="<?= $question_uuid ?>">
<?php if(isset($errors['option_uuid'])) { ?>
            <div class="error" id="option_error"><?= $errors['option_uuid'] ?></div>
<?php } ?>
            <div class="row" id="option_select">
                <?php foreach ($options as $option) { ?>
                <div class="col">
                    <div class="box radiobox"><input type="radio" name="option_uuid" id="option_<?= $option->uuid?>" value="<?= $option->uuid?>"><?= $option->label?></div>
                </div>
                <?php } ?>
            </div>

            <div class="progresscontainer">
                <div class="progressbar" id="progressbar">
                    <div id="progressbar_percent" style="width:<?= $progress_percent ?>%"></div>
                </div>
            </div>
            <input type="submit" id="option_submit" value="Nākamais">
        </form>
    </div>
</div>