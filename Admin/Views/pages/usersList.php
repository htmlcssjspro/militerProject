<section class="admin__userlist">
    UsersList

    <?php
    $order = [
        'username',
        'name',
        'email',
        'phone',
        'last_visit',
        'register_date',
    ];
    ?>

    <table>
        <tbody>
            <tr>
                <?php foreach ($order as $name) : ?>
                    <th><?= $Model->userDict[$name] ?></th>
                <?php endforeach; ?>
                <th><?= $Model->userDict['status'] ?></th>
            </tr>
            <?php foreach ($Model->usersList as $userData) : ?>
                <tr>
                    <?php foreach ($order as $name) : ?>
                        <td><?= $userData[$name] ?></td>
                    <?php endforeach; ?>
                    <td>
                        <form action="/admin/api/status" method="POST">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="user-uuid" value="<?= $userData['user_uuid'] ?>">
                            <select name="status">
                                <?php foreach ($Model->userDict['statusDict'] as $status => $interpretation) : ?>
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
