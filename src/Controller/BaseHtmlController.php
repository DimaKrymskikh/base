<?php

namespace Base\Controller;

use Base\Container\Container;
use Base\Session\ErrorsSession;
use Base\Session\FlashMessagesSession;

class BaseHtmlController extends HtmlController
{
    // Заголовок html-страницы (для тега <title>)
    protected string $title = '';
    // Массив переменных для шаблона
    protected array $templateParameters = [];
    // Шаблон html-страницы
    protected string $template;
    // Папка, в которой находятся представления
    protected string $viewsFolder;
    // Флэш-сообщения
    protected FlashMessagesSession $flashMessages;

    public function __construct()
    {
        $action = Container::getInstance()->get('action');
        
        $this->template = $action->template;
        $this->viewsFolder = $action->viewsFolder;
        
        $this->flashMessages = FlashMessagesSession::getInstance();
    }

    /**
     * Отрисовывает html-страницу с шаблоном (с <head>)
     * 
     * @param string $file - Файл, содержащий контент
     * @param array $params - Переменные, которые используются на html-странице
     * @return string - html-страница
     */
    #[\Override]
    protected function render(string $file, array $params = []): string
    {
        $content = $this->renderContent($this->viewsFolder.$file, $params);
        return $this->renderContent($this->template, array_merge($this->templateParameters, [
                'content' => $content,
                'title' => $this->title
            ]));
    }

    /**
     * Отрисовывает фрагмент html-страницы (используется при ajax-запросах)
     * 
     * @param string $file - Файл, содержащий контент
     * @param array $params - Переменные, которые используются на html-странице
     * @return string - Фрагмент html-страницы
     */
    #[\Override]
    protected function renderContent(string $file, array $params = []): string
    {
        ob_start();
        extract($params, EXTR_OVERWRITE);
        // Из-за тестов используется require, а не require_once
        require $file;
        return ob_get_clean();
    }

    /**
     * При ajax-запросе будет отрисован только контент, при http-запросе вся страница документа
     * 
     * @param string $file - файл с контентом
     * @param array $data - передаваемые в $file переменные
     * @return string - вся страница документа или только блок контента на странице документа
     */
    #[\Override]
    protected function conditionalRender(string $file, array $data): string
    {
        return filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') ? $this->renderContent($this->viewsFolder.$file, $data) : $this->render($file, $data);
    }
    
    #[\Override]
    protected function redirect(string $uri, int $code = 303): void
    {
        header('Location: http://'.filter_input(INPUT_SERVER, 'HTTP_HOST').'/'.trim($uri,'/'), true, $code);
    }
    
    /**
     * Получает сообщения об ошибках из сессии.
     * 
     * @return array
     */
    protected function getErrors(): array
    {
        return ErrorsSession::getInstance()->getErrors();
    }
}
