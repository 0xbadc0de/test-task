<?php include (__DIR__ . '/../header.php') ?>

<!-- Content -->
<div class="container">

    <div class="page-header">
        <h1>Add new user</h1>
    </div>

    <form action="/post" id="user-form" class="form-inline">
        <div class="form-group">
            <label for="userName">Name</label>
            <input type="text" minlength="1" autofocus required name="name" class="form-control" autocomplete="off" id="userName" value="John Smith">
        </div>
        <div class="form-group">
            <label for="userEmail">Email</label>
            <input type="email" required name="email" class="form-control" autocomplete="off" id="userEmail" value="john@example.com">
        </div>
        <button type="submit" class="btn">Submit</button>
    </form>

    <hr>

    <div class="page-header">
        <h1>User list</h1>
    </div>

    <?php if($list): ?>
        <table id="user-table" class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th scope="col">Email</th>
                <th scope="col">Name</th>
                <th scope="col" class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $user): ?>
                <tr>
                    <td><a href="mailto:<?= $user['email'] ?>" title="Send email"><?= $user['email'] ?></a></td>
                    <td><?= $user['name'] ?></td>
                    <td class="text-right">
                        <button data-action="remove" data-position="<?= $index ?>" class="btn btn-danger">Remove</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
    <div class="text-center">
        <h3 class="text-muted">There is no entries to display</h3>
    </div>
    <?php endif ?>
</div>

<script type="text/javascript" src="/js/user.js"></script>

<?php include (__DIR__ . '/../footer.php') ?>
