<?php

namespace Base\Controller;

use Base\Container\Container;

class BaseHtmlController extends HtmlController
{
    // Заголовок html-страницы (для тега <title>)
    protected string $title = '';
    // Массив переменных для шаблона
    private array $templateParameters = [];
    // Шаблон html-страницы
    private string $template;
    // Папка, в которой находятся представления
    private string $viewsFolder;

    public function __construct(
        protected Container $container
    ) {
        $this->template = $this->container->get('is_find_route') ? $this->container->get('module')->template : $this->container->get('error_router')->template;
        $this->viewsFolder = $this->container->get('is_find_route') ? $this->container->get('module')->views_folder : $this->container->get('error_router')->views_folder;
    }

    /**
     * Отрисовывает html-страницу с шаблоном (с <head>)
     * @param string $file - Файл, содержащий контент
     * @param array $params - Переменные, которые используются на html-странице
     * @return string - html-страница
     */
    protected function render(string $file, array $params = []): string
    {
        $content = $this->renderContent($this->viewsFolder . $file, $params);
        return $this->renderContent($this->template, array_merge($this->templateParameters, [
                'content' => $content,
                'title' => $this->title
            ]));
    }

    /**
     * Отрисовывает фрагмент html-страницы (используется при ajax-запросах)
     * @param string $file - Файл, содержащий контент
     * @param array $params - Переменные, которые используются на html-странице
     * @return string - Фрагмент html-страницы
     */
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
     * @param string $file - файл с контентом
     * @param array $data - передаваемые в $file переменные
     * @return string - вся страница документа или только блок контента на странице документа
     */
    protected function conditionalRender(string $file, array $data): string
    {
        return filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') ? $this->renderContent($this->viewsFolder . $file, $data) : $this->render($file, $data);
    }

    /**
     * Добавляет параметры в шаблон
     * @param string $key
     * @param string $value
     * @return void
     */
    protected function pushTemplateParameters(string $key, string $value): void
    {
        $this->templateParameters[$key] = $value;
    }
}
