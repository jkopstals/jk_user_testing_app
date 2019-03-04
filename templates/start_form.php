<form method="POST" action="\">
    <div>
        <div>
            <input type="text" name="name" id="name" value="<?= (isset($_POST['name']) ? $_POST['name'] : '') ?>" maxlength="255" placeholder="Ievadi savu vārdu">
<?php if(isset($errors['name'])) { ?>
            <div class="error"><?= $errors['name'] ?></div>
<?php } ?>
        </div>
        <div>
            <select name="test_uuid" id="test_uuid">
                <option value="" disabled selected>Izvēlies testu</option>
<?php foreach ($tests as $test) { ?>
                <option value="<?= $test->uuid ?>"
                <?php if(isset($_POST['test_uuid']) && $_POST['test_uuid'] == $test->uuid) { ?>
                    checked="checked"
                <?php } ?>
                ><?= $test->name ?></option>
<?php } ?>
            </select>
<?php if(isset($errors['test_uuid'])) { ?>
            <div class="error"><?= $errors['test_uuid'] ?></div>
<?php } ?>
        </div>
        <div><input name="submit" id="submit" type="submit" value="Sākt"></div>
    </div>
</form>