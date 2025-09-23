<?php

namespace App\Core;

use Exception;

class View
{
    protected string $viewFile;
    protected array $data;

    /**
     * View constructor.
     *
     * @param string $view The path to the view file (e.g., 'auth/login').
     * @param array $data The data to make available to the view.
     */
    public function __construct(string $view, array $data = [])
    {
        // This will create the full path, e.g., /var/www/html/public/views/auth/login.php
        $this->viewFile = ROOT_PATH . '/public/views/' . str_replace('.', '/', $view) . '.php';
        $this->data = $data;
    }

    /**
     * Renders the view with its layout.
     *
     * @throws Exception if the view file does not exist.
     */
    public function render(): void
    {
        if (!file_exists($this->viewFile)) {
            throw new Exception("View file not found: {$this->viewFile}");
        }

        // Make data available as variables in the view (e.g., $title)
        extract($this->data);

        // Start buffering output, so we can capture the view's HTML
        ob_start();
        require $this->viewFile;
        $pageContent = ob_get_clean();

        // Now, render the full layout, embedding the page content
        // This assumes you have header.php and footer.php in public/views/layouts/
        require ROOT_PATH . '/public/views/layouts/header.php';
        echo $pageContent;
        require ROOT_PATH . '/public/views/layouts/footer.php';
    }

    public function renderPartial(): void
    {
        if (!file_exists($this->viewFile)) {
            // In case of an error, we don't want to throw another one.
            // Just display a simple message.
            echo "<h1>Error</h1><p>View file not found: {$this->viewFile}</p>";
            return;
        }

        extract($this->data);
        require $this->viewFile;
    }
}