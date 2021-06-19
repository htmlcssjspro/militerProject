<?php

$order = [
    'username',
    'name',
    'email',
    'phone',
    'last_visit',
    'register_date',
];

$userDict = $this->getDictionary();

?>

<h1><?= $this->h1 ?></h1>

<section class="admin__userlist">
    <table>
        <tbody>
            <tr>
                <?php foreach ($order as $name) : ?>
                    <th><?= $userDict[$name] ?></th>
                <?php endforeach; ?>
                <th><?= $userDict['status'] ?></th>
            </tr>
            <?php foreach ($this->getUsersList() as $userData) : ?>
                <tr>
                    <?php foreach ($order as $name) : ?>
                        <td><?= $userData[$name] ?></td>
                    <?php endforeach; ?>
                    <td>
                        <form action="/admin/api/status" method="POST">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="user_uuid" value="<?= $userData['user_uuid'] ?>">
                            <select name="status">
                                <?php foreach (['user', 'admin', 'blocked'] as $status) : ?>
                                    <option value="<?= $status ?>" <?= $userData['status'] === $status ? 'selected' : '' ?>>
                                        <?= $userDict[$status] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Изменить статус</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
