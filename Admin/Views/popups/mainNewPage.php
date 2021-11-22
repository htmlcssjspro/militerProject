<?php
$layoutList = $this->getMainLayoutList();
?>

<section class="popup main-new-page dn">
    <div class="popup__wrapper">
        <form action="/admin/api/add-main-new-page" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <fieldset>
                <label>
                    <span>Label</span>
                    <span><input type="text" name="label" value=""></span>
                </label>
                <label>
                    <span>Page Uri</span>/
                    <span><input type="text" name="page_uri" value="" data-required="required"></span>
                </label>
                <label>
                    <span>Title</span>
                    <span><input type="text" name="title" value=""></span>
                </label>
                <label>
                    <span>Description</span>
                    <span><input type="text" name="description" value=""></span>
                </label>
                <label>
                    <span>H1</span>
                    <span><input type="text" name="h1" value=""></span>
                </label>
                <label>
                    <span>Layout</span>
                    <span>
                        <select name="layout">
                            <?php foreach ($layoutList as $layout) : ?>
                                <option value="<?= $layout ?>">
                                    <?= $layout ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                </label>
                <label>
                    <span>Main Content</span>
                    <span><input type="text" name="main" value=""></span>
                </label>
                <button class="btn_inline" type="submit">Добавить</button>
                <button class="btn_inline" type="reset">Сбросить</button>
            </fieldset>
        </form>
    </div>
</section>
