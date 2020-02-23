<?php

namespace PiePHP\Core;

class TemplateEngine
{
    private $content;
    private $handler = false;

    public function parse($file)
    {
        if (!file_exists($file)) {
            return null;
        }
        $this->handler = fopen($file, 'r');
        if ($this->handler) {
            $this->content = '';
            while (false !== ($line = fgets($this->handler))) {
                $this->content .= $this->parseLine(trim($line, "\r\n")) . PHP_EOL;
            }
            fclose($this->handler);
        }
        $this->handler = false;

        return $this->content;
    }

    private function parseLine($line)
    {
        $this->parseIf($line);
        $this->parseFor($line);
        $this->parseAdv($line);
        $this->parseEcho($line);
        $this->parseComment($line);

        return $line;
    }

    private function parseIf(&$line)
    {
        $line = preg_replace_callback_array(
            [
            '/@([i][f]|[e][l][s][e][\\s]*[i][f])\\s*(\\(.*\\))\\s*$/' => function ($matches) {
                return "<?php {$matches[1]} {$matches[2]}: ?>";
            },
            '/@([e][l][s][e])\\s*$/' => function ($matches) {
                return '<?php else: ?>';
            },
            '/@([e][n][d][i][f])\\s*$/' => function ($matches) {
                return '<?php endif; ?>';
            },
            ], $line
        );
    }

    private function parseFor(&$line)
    {
        $line = preg_replace_callback_array(
            [
            '/@([f][o][r]|[f][o][r][e][a][c][h])\\s*(\\(.*\\))\\s*$/' => function ($matches) {
                return "<?php {$matches[1]} {$matches[2]}: ?>";
            },
            '/@([e][n][d][f][o][r]|[e][n][d][f][o][r][e][a][c][h])\\s*$/' => function ($matches) {
                return "<?php {$matches[1]}; ?>";
            },
            ], $line
        );
    }

    private function parseAdv(&$line)
    {
        $line = preg_replace_callback_array(
            [
            '/@([i][s][s][e][t]|[e][m][p][t][y])\\s*(\\(.*\\))\\s*$/' => function ($matches) {
                return "<?php if ({$matches[1]} {$matches[2]}): ?>";
            },
            '/@([e][n][d][i][s][s][e][t]|[e][n][d][e][m][p][t][y])\\s*$/' => function ($matches) {
                return '<?php endif; ?>';
            },
            ], $line
        );
    }

    private function parseEcho(&$line)
    {
        $line = preg_replace_callback_array(
            [
            '/{{\\s+(.*?)\\s+}}/' => function ($matches) {
                return "<?= htmlentities({$matches[1]}) ?>";
            },
            '/{!!\\s+(.*?)\\s+!!}/' => function ($matches) {
                return "<?= {$matches[1]} ?>";
            },
            ], $line
        );
    }

    private function parseComment(&$line)
    {
        $line = preg_replace_callback_array(
            [
            '/{{--\\s+(.*?)\\s+--}}/' => function ($matches) {
                return '';
            },
            '/@[p][h][p]/' => function ($matches) {
                return '<?php ';
            },
            '/@[e][n][d][p][h][p]/' => function ($matches) {
                return ' ?>';
            },
            ], $line
        );
    }
}
