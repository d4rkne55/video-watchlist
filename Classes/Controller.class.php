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
     * @Method 'POST'
     */
    public function addVideo() {
        if (Request::isAjax()) {
            try {
                $scraper = new Scraper($_POST['url']);
                /** @var VideoPageParser $page */
                $page = $scraper->parse();
            } catch (Exception $e) {
                if ($e->getCode() != 0) {
                    Response::setCode($e->getCode());
                    exit;
                }

                Response::setCode(500);
                Response::json(array(
                    'error' => $e->getMessage()
                ));
            }

            $stmt = $this->DB->prepare('INSERT INTO videos (URL, Title, ThumbnailUrl, DateAdded) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $scraper->url,
                $page->getTitle(),
                $page->getThumbnailUrl(),
                (new DateTime())->format('Y-m-d')
            ]);

            Response::json(array(
                'url' => $scraper->url,
                'title' => $page->getTitle(),
                'thumbnail' => $page->getThumbnailUrl()
            ));
        }
    }

    /**
     * @Route '/delete'
     * @Method 'POST'
     */
    public function deleteVideo() {
        if (Request::isAjax()) {
            $stmt = $this->DB->prepare('DELETE FROM videos WHERE URL = ?');

            if ($stmt->execute([$_POST['url']])) {
                Response::json(true);
            } else {
                Response::json(false);
            }
        }
    }
}
