<?php
// страницы с layout
$pageList = [
    'main' => [
        'class'      => 'main-page-list',
        'h2'         => 'Страницы сайта',
        'action'     => '/admin/api/update-main-sitemap',
        'pageList'   => $this->getMainPageListData(),
        'layoutList' => $this->getMainLayoutList(),
        'popup'      => 'main-new-page',
    ],
    'admin' => [
        'class'      => 'admin-page-list',
        'h2'         => 'Страницы панели администратора',
        'action'     => '/admin/api/update-admin-sitemap',
        'pageList'   => $this->getAdminPageListData(),
        'layoutList' => $this->getAdminLayoutList(),
        'popup'      => 'admin-new-page',
    ],
];
?>

<h1><?= $this->h1 ?></h1>
<div class="admin__page-list">
    <?php foreach ($pageList as $key => $list) : ?>
        <section class="<?= $list['class'] ?>">
            <h2><?= $list['h2'] ?></h2>
            <?php foreach ($list['pageList'] as $pageData) : ?>
                <div class="dropdown">
                    <div class="dropdown__toggle"><?= $pageData['label'] ?></div>
                    <div class="dropdown__content dn">
                        <form action="<?= $list['action'] ?>" method="POST">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="page_uri" value="<?= $pageData['page_uri'] ?>">
                            <fieldset>
                                <label>
                                    <span>Label</span>
                                    <span><input type="text" name="label" value="<?= $pageData['label'] ?>"></span>
                                </label>
                                <label>
                                    <span>Page Uri</span>/
                                    <span><input type="text" name="new_page_uri" value="<?= $pageData['page_uri'] ?>"></span>
                                </label>
                                <label>
                                    <span>Title</span>
                                    <span><input type="text" name="title" value="<?= $pageData['title'] ?>"></span>
                                </label>
                                <label>
                                    <span>Description</span>
                                    <span><input type="text" name="description" value="<?= $pageData['description'] ?>"></span>
                                </label>
                                <label>
                                    <span>H1</span>
                                    <span><input type="text" name="h1" value="<?= $pageData['h1'] ?>"></span>
                                </label>
                                <label>
                                    <span>Layout</span>
                                    <span>
                                        <select name="layout">
                                            <?php foreach ($list['layoutList'] as $layout) : ?>
                                                <option value="<?= $layout ?>" <?= $layout === $pageData['layout'] ? 'selected' : '' ?>>
                                                    <?= $layout ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </span>
                                </label>
                                <label>
                                    <span>Main Content</span>
                                    <span><input type="text" name="main" value="<?= $pageData['main'] ?>"></span>
                                </label>
                                <button type="submit" class="btn_inline">Обновить</button>
                                <button type="reset" class="btn_inline">Сбросить</button>
                                <?php if (!($key === 'admin' || $pageData['page_uri'] === '')) : ?>
                                    <button type="button" class="btn_inline btn_danger" data-href="/admin/api/del-page">Удалить</button>
                                <?php endif; ?>


                            </fieldset>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <button class="btn_new-page" type="button" data-popup="<?= $list['popup'] ?>">Добавить новую страницу</button>
        </section>
    <?php endforeach; ?>
</div>
