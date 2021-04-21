<section class="popup newpage dn">
    <div class="popup__wrapper">
        <form action="/admin/api/newpage" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
            <label>
                <span>label</span>
                <span><input type="text" name="label" value="label"></span>
            </label>
            <label>
                <span>page_url</span>
                <span><input type="text" name="page_url" value="/page"></span>
            </label>
            <label>
                <span>title</span>
                <span><input type="text" name="title" value="title"></span>
            </label>
            <label>
                <span>description</span>
                <span><input type="text" name="description" value="description"></span>
            </label>
            <label>
                <span>h1</span>
                <span><input type="text" name="h1" value="h1"></span>
            </label>
            <button class="btn" type="submit">Добавить новую страницу</button>
        </form>
    </div>
</section>
