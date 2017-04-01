<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController {
    protected $renderer;
    protected $logger;

    public function __construct(\Slim\Views\PhpRenderer $renderer, \Monolog\Logger $logger) {
        $this->renderer = $renderer;
        $this->logger = $logger;
    }
    public function home(Request $request, Response $response, array $args) {
        $this->processHome($response);
    }
    public function imageReq(Request $request, Response $response, array $args) {
        $this->processImg($response, $args);
    }
    public function changeLang(Request $request, Response $response, array $args) {
        $this->processLang($response, $args);
    }
    private function processLang(Response $response, array $args) {
        // Route the chinese language
        $this->logger->info("Slim-language-Chinese' route");

        // Render index.phtml to chinese contents
        $codeContent = $this->genImgList();
        $args = [
            'codeContent' => $codeContent,
            'usage' => '使用方式',
            'note' => '<p><b>注意:</b> 如果你有些原因需要使用圖檔的附檔名，請加上: <code>/images/.jpg</code> 到網址.</p>',
            'code' => '<h2 class="top-space bottom-space">目前提供的 狀態碼:</h2>',
            'lang' => '<p><a href="/chinese">英文</a></p>',
            'apod' => '<p><a href="https://apod.nasa.gov/apod/">今日的 NASA APOD 圖檔</a></p>',
            'develop' => '<p>由<a href="https://twitter.com/peter279k">@peter279k 所開發</a>. <a href="https://api.nasa.gov/index.html">圖檔</a> 由 NASA Open API 所提供 (<a href="https://api.nasa.gov/index.html">APOD API</a>).</p>'
        ];

        return $this->renderer->render($response, 'index.phtml', $args);
    }
    private function processImg(Response $response, array $args) {
        // Route the HTTP status image
        $this->logger->info("Slim-ImageStatusCode' route");

        // Render image view
        return $this->renderer->render($response, 'image.phtml', [
            'statusCode' => $args['statusCode']
        ]);
    }
    private function processHome(Response $response) {
        // Route Root path log message
        $this->logger->info("Index-View '/' route");
        $codeContent = $this->genImgList();
        $args = ['codeContent' => $codeContent];
        // Render the index view
        return $this->renderer->render($response, 'index.phtml', $args);
    }
    private function genImgList() {
        $ulStr = '<ul class="col-sm-12 col-md-4 col-lg-4">';
        $ulEnd = '</ul>';
        $codeContent = $ulStr;
        $count = 1;
        $statusCodeLst = glob(__DIR__.'/../public/images/*.jpg');
        $arrLen = count($statusCodeLst);
        foreach($statusCodeLst as $value) {
            $fileName = pathinfo($value);
            if (is_numeric($fileName['filename'])) {
                if ($count % 19 === 0 && $count !== $arrLen) {
                    $codeContent .= $ulEnd.$ulStr;
                }
                $codeContent .= '<a href="/'.$fileName['filename'].'">
                    <li class="status" style="background-image:url(/images/'.$fileName['basename'].')"><span>'.$fileName['filename'].'</span></li>
                </a>';
            }
            $count += 1;
        }
        if($arrLen % 2 === 1) {
            $codeContent .= '</ul>';
        }

        return $codeContent;
    }
}
