<?php

use Framework\Base;
use Framework\Response;
use Framework\Request;

class Controller extends Base
{
    /**
     * @Route '/'
     */
    public function showIndex() {
        $stmt = $this->DB->query('SELECT URL, Title, ThumbnailUrl, DateAdded FROM videos ORDER BY DateAdded DESC, ID DESC');

        $this->view->render('index.php', array(
            'videos' => $stmt->fetchAll()
        ));
    }

    /**
     * @Route '/add'
     */
    public function addVideo(array $data) {
        $scraper = new Scraper($_POST['url']);
        /** @var VideoPageParser $page */
        // TODO catch exception(s)
        // TODO check for response code
        $page = $scraper->parse();

//        echo '<pre>';
//        var_dump([
//            'url' => $scraper->url,
//            'title' => $page->getTitle(),
//            'thumbnail' => $page->getThumbnailUrl()
//        ]);
//
//        echo '<br>' . htmlspecialchars($scraper->html). '</pre>';
//        exit;

        $stmt = $this->DB->prepare('INSERT INTO videos (URL, Title, ThumbnailUrl, DateAdded) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $scraper->url,
            $page->getTitle(),
            $page->getThumbnailUrl(),
            (new DateTime())->format('Y-m-d')
        ]);

        Response::redirect('');
    }
}
