<?php

$order = [
    'username',
    'name',
    'email',
    'phone',
    'last_visit',
    'register_date',
];

$userDict = $Model->getUserDictionary();

?>

<h1><?= $Model->h1 ?></h1>

<section class="admin__userlist">
    <table>
        <tbody>
            <tr>
                <?php foreach ($order as $name) : ?>
                    <th><?= $userDict[$name] ?></th>
                <?php endforeach; ?>
                <th><?= $userDict['status'] ?></th>
            </tr>
            <?php foreach ($Model->getUsersList() as $userData) : ?>
                <tr>
                    <?php foreach ($order as $name) : ?>
                        <td><?= $userData[$name] ?></td>
                    <?php endforeach; ?>
                    <td>
                        <form action="/admin/api/status" method="POST">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="user_uuid" value="<?= $userData['user_uuid'] ?>">
                            <select name="status">
                                <?php foreach ($userDict['statusDict'] as $status => $interpretation) : ?>
                                    <option value="<?= $status ?>" <?= $userData['status'] === $status ? 'selected' : '' ?>><?= $interpretation ?></option>
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
