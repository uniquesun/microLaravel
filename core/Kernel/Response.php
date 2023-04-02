<?php

namespace Core\Kernel;

class Response
{
    protected $headers = [];
    protected $content = '';
    protected $code = 200;


    private function sendContent()
    {
        echo $this->content;
    }

    private function sendHeader()
    {
        foreach ($this->headers as $key => $header)
            header($key . ': ' . $header);

    }

    public function send(): Response
    {
        $this->sendHeader();
        $this->sendContent();
        return $this;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }


    public function setContent($content)
    {
        if (is_array($content)) $content = json_encode($content);
        $this->content = $content;
        return $this;
    }


    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }


    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

}