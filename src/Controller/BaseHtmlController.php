<?php

namespace Base\Controller;

class BaseHtmlController extends HtmlController
{
    // Заголовок html-страницы (для тега <title>)
    protected string $title = '';
    // Шаблон html-страницы
    private string $template;

    public function __construct($template = null)
    {
        $this->template = $template;
    }

    /**
     * Отрисовывает html-страницу с шаблоном (с <head>)
     * @param string $file - Файл, содержащий контент
     * @param array $params - Переменные, которые используются на html-странице
     * @return string - html-страница
     */
    protected function render(string $file, array $params = []): string
    {
        $content = $this->renderContent(static::BASE_URL . $file, $params);
        return $this->renderContent($this->template, [
                'content' => $content,
                'title' => $this->title
            ]);
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
        require_once $file;
        return ob_get_clean();
    }
}
