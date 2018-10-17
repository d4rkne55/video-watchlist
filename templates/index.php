<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Video Watchlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= ROOT ?>css/style.css">
</head>
<body>
    <form action="<?= ROOT ?>add" method="post">
        <input name="url" type="url">
        <button class="btn">Add</button>
    </form>

    <main>
        <?php
        foreach ($this->videos as $video) {
            $thumbnailUrl = $video['ThumbnailUrl'];

            if (empty($thumbnailUrl)) {
                $thumbnailUrl = 'https://s.ytimg.com/yts/img/meh7-vflGevej7.png';
            }
        ?>
        <article>
            <button class="delete" title="Remove">&#10005;</button>
            <a href="<?= $video['URL'] ?>" target="_blank">
                <img src="<?= $thumbnailUrl ?>">
                <h2><?= $video['Title'] ?></h2>
            </a>
        </article>
        <?php } ?>
    </main>

    <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="<?= ROOT ?>js/main.js"></script>
</body>
</html>