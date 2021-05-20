<h1><?= $Model->h1 ?></h1>
<section class="admin__pages">
    <button class="btn_newpage" type="button" data-popup=".newpage">Добавить новую страницу</button>
    <?php foreach ($Model->getPagesData() as $pageData) : ?>
        <form action="/admin/api/updatesitemap" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <fieldset>
                <legend><?= $pageData['label'] ?></legend>
                <label>
                    <span>label</span>
                    <span><input type="text" name="label" value="<?= $pageData['label'] ?>"></span>
                </label>
                <label>
                    <span>page_uri</span>
                    <span><input type="text" name="page_uri" value="<?= $pageData['page_uri'] ?>"></span>
                </label>
                <label>
                    <span>title</span>
                    <span><input type="text" name="title" value="<?= $pageData['title'] ?>"></span>
                </label>
                <label>
                    <span>description</span>
                    <span><input type="text" name="description" value="<?= $pageData['description'] ?>"></span>
                </label>
                <label>
                    <span>h1</span>
                    <span><input type="text" name="h1" value="<?= $pageData['h1'] ?>"></span>
                </label>
                <button type="submit">Обновить</button>
            </fieldset>
        </form>
    <?php endforeach; ?>
    <button class="btn_newpage" type="button" data-popup=".newpage">Добавить новую страницу</button>
</section>
